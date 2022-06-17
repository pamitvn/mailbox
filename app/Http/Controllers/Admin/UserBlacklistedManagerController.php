<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserBlacklisted;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class UserBlacklistedManagerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $records = UserBlacklisted::query()
            ->with(['user', 'byUser'])
            ->orderBy('id', 'desc')
            ->when(fn() => filled($search), fn($query) => $query->orWhereHas('user', fn(Builder $builder) => search_by_cols($builder, [
                'id',
                'name',
                'username',
                'email'
            ])));

        search_by_cols($records, $search, [
            'reason',
        ]);

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
    }

    public function show(UserBlacklisted $userBlacklisted)
    {
    }

    public function edit(UserBlacklisted $userBlacklisted)
    {
    }

    public function update(Request $request, UserBlacklisted $userBlacklisted)
    {
    }

    public function destroy(UserBlacklisted $userBlacklisted)
    {
    }
}
