<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\RechargeHistory;
use App\Models\User;
use Bavix\Wallet\Exceptions\InsufficientFunds;
use Bavix\Wallet\Internal\Service\DatabaseServiceInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserBalanceController extends Controller
{
    public function index(User $user)
    {
        return inertia('Admin/User/Balance', [
            'user' => $user->load('wallet'),
            'banks' => Bank::get(['name', 'id']),
        ]);
    }

    public function update(Request $request, User $user)
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
                        'note' => 'Duyệt nạp tiền',
                    ]);
                }
            });

            send_current_user_message('success', __('Set balance successfully'));

            return back();
        } catch (InsufficientFunds $exception) {
            $user->withdraw($user->balance);

            send_current_user_message('success', __('Set balance successfully'));

            return back();
        } catch (Exception $exception) {
            send_current_user_message('danger', $exception->getMessage());

            return back()->with('error', $exception->getMessage())->withErrors('Error', 'globalError');
        }
    }
}
