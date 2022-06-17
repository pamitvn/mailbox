<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\RechargeHistory;
use App\Models\User;
use Bavix\Wallet\Exceptions\InsufficientFunds;
use Bavix\Wallet\Internal\Exceptions\ExceptionInterface;
use Bavix\Wallet\Internal\Service\DatabaseServiceInterface;
use Bavix\Wallet\Models\Wallet;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserManagerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $user = User::query()
            ->with('wallet')
            ->orderBy('id', 'desc');

        search_by_cols($user, $search, [
            'name',
            'username',
            'email'
        ]);

        query_by_cols($user, [
            'id',
            'username',
            'email',
        ], $request->all());

        return inertia('Admin/User/UserManager', [
            'paginationData' => paginate_with_params($user, $request->all()),
            'statistics' => [
                'total' => number_format(User::count()),
                'totalBalance' => number_format(Wallet::where('holder_type', User::class)->sum('balance')),
            ]
        ]);
    }

    public function create()
    {
        return inertia('Admin/User/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'username' => ['required', 'string', 'min:6', 'max:32', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:150', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        User::create(array_merge($request->only(['name', 'username', 'email', 'password', 'balance']), [
            'password' => Hash::make($request->input('password'))
        ]));

        return back()->with('success', __('Created new user'));
    }

    public function edit(User $user)
    {
        return inertia('Admin/User/Edit', [
            'user' => $user
        ]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'username' => ['required', 'string', 'min:6', 'max:32', Rule::unique('users', 'username')->ignoreModel($user)],
            'email' => ['required', 'string', 'email', 'max:150', Rule::unique('users', 'email')->ignoreModel($user)],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $user->update(array_merge($request->only(['name', 'username', 'email', 'password', 'balance']), [
            'password' => $request->input('password') ? Hash::make($request->input('password')) : $user->password
        ]));

        return back()->with('success', __('Updated user #:id', ['id' => $user->id]));
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', "You can't erase yourself");
        }

        $user->delete();

        return back()->with('success', __('Deleted user #:id', ['id' => $user->id]));
    }

    public function balance(User $user)
    {
        return inertia('Admin/User/Balance', [
            'user' => $user->load('wallet'),
            'banks' => Bank::get(['name', 'id'])
        ]);
    }

    public function storeBalance(Request $request, User $user)
    {
        $request->validate([
            'bank' => ['nullable', Rule::exists('banks', 'id')],
            'action' => ['required', Rule::in(['+', '-'])],
            'amount' => ['required', 'integer', 'min:1'],
        ]);

        try {
            app(DatabaseServiceInterface::class)->transaction(function () use ($request, $user) {
                $action = $request->get('action', '+');
                $bank = $request->get('bank');
                $amount = $request->get('amount');

                $balance = $user->balance;

                if ($action === '+') {
                    $user->deposit($amount);
                } elseif ($action === '-') {
                    $user->withdraw($amount);
                }

                if ($action === '+' && $bank) {
                    RechargeHistory::create([
                        'user_id' => $user->id,
                        'bank_id' => $bank,
                        'before_transaction' => $balance,
                        'after_transaction' => $user->balance,
                        'amount' => $amount,
                        'note' => 'Duyệt nạp tiền'
                    ]);
                }
            });

            return back()->with('success', 'Set balance successfully');
        } catch (InsufficientFunds $exception) {
            $user->withdraw($user->balance);
            return back()->with('success', 'Set balance successfully');
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage())->withErrors('Error', 'globalError');
        }
    }
}
