<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserBlacklisted;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserBlacklistedManagerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $searchByFn = fn(Builder $builder) => search_by_cols($builder, $search, [
            'id',
            'name',
            'username',
            'email'
        ]);
        $records = UserBlacklisted::query()
            ->with(['user', 'byUser'])
            ->orderBy('id', 'desc')
            ->when(fn() => filled($search), fn($query) => $query->whereHas('user', $searchByFn)->orWhereHas('byUser', $searchByFn));

        query_by_cols($records, [
            'id',
            'user_id',
            'by_user_id',
        ], $request->all());

        return inertia('Admin/Blacklisted/User/Manager', [
            'paginationData' => paginate_with_params($records, $request->all())
        ]);
    }

    public function create()
    {
        return inertia('Admin/Blacklisted/User/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'integer', Rule::unique(table_name_of_model(UserBlacklisted::class), 'user_id')],
            'reason' => ['required', 'string'],
            'duration' => ['nullable', 'date'],
        ]);

        UserBlacklisted::create(array_merge($data, [
            'by_user_id' => auth()->user()->id,
            'duration' => filled($data['duration']) ? Carbon::parse($data['duration'])->endOfDay() : null
        ]));

        return back()->with('success', __('User #:id has been added to the blacklist.', ['id' => $data['user_id']]));
    }

    public function edit(UserBlacklisted $blacklisted)
    {
        return inertia('Admin/Blacklisted/User/Edit', [
            'data' => $blacklisted->load(['user'])
        ]);
    }

    public function update(Request $request, UserBlacklisted $blacklisted)
    {
        $data = $request->validate([
            'reason' => ['required', 'string'],
            'duration' => ['nullable', 'date'],
        ]);

        $status = $blacklisted->update(array_merge($data, [
            'duration' => filled($data['duration']) ? Carbon::parse($data['duration'])->endOfDay() : null
        ]));

        if (!$status) {
            return back()->with('error', __('Blacklist #:id cannot be updated', ['id' => $blacklisted->id]))
                ->withErrors('Error', 'globalError');
        }

        return back()->with('success', __('Updated blacklist #:id', ['id' => $blacklisted->id]));
    }

    public function destroy(UserBlacklisted $blacklisted)
    {
        $status = $blacklisted->delete();

        if (!$status) {
            return back()->with('error', __('Blacklist #:id cannot be deleted', ['id' => $blacklisted->id]))->withErrors('Error', 'globalError');
        }

        return back()->with('success', __('Deleted blacklist #:id', ['id' => $blacklisted->id]));
    }
}
