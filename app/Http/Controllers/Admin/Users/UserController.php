<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Bavix\Wallet\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $user = User::query()
            ->with('wallet')
            ->orderByDesc('id');

        search_by_cols($user, $search, [
            'name',
            'username',
            'email',
        ]);

        query_by_cols($user, [
            'id',
            'username',
            'email',
        ], $request->all());

        return inertia('Admin/User/Manager', [
            'paginationData' => cursor_paginate_with_params($user, $request->all()),
            'statistics' => [
                'total' => number_format(User::count()),
                'totalBalance' => number_format(Wallet::where('holder_type', User::class)->sum('balance')),
            ],
        ]);
    }

    public function create()
    {
        return inertia('Admin/User/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'username' => ['required', 'string', 'min:6', 'max:32', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:150', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'has_storage' => ['boolean'],
        ]);

        $user = User::create(array_merge($data, [
            'password' => Hash::make($request->input('password')),
        ]));

        return send_message_if(
            boolean: ! is_null($user),
            message: __('Created new user'),
            unlessMessage: __('User cannot be created'),
            allowBack: true
        );
    }

    public function edit(User $user)
    {
        return inertia('Admin/User/Edit', [
            'user' => $user,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'username' => ['required', 'string', 'min:6', 'max:32', Rule::unique('users', 'username')->ignoreModel($user)],
            'email' => ['required', 'string', 'email', 'max:150', Rule::unique('users', 'email')->ignoreModel($user)],
            'password' => ['nullable', 'string', 'min:8'],
            'has_storage' => ['boolean'],
        ]);

        $status = $user->update(array_merge($data, [
            'password' => $request->input('password') ? Hash::make($request->input('password')) : $user->password,
        ]));

        return send_message_if(
            boolean: $status,
            message: __('Updated user #:id', ['id' => $user->id]),
            unlessMessage: __('User #:id cannot be updated', ['id' => $user->id]),
            allowBack: true
        );
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', "You can't erase yourself");
        }

        return send_message_if(
            boolean: $user->delete(),
            message: __('Deleted user #:id', ['id' => $user->id]),
            unlessMessage: __('User #:id cannot be deleted', ['id' => $user->id]),
            allowBack: true
        );
    }
}
