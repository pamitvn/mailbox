<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Str;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        return inertia('Account/Profile', [
            'user' => (new UserResource(User::find(auth()->id())))->toArray($request)
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore(auth()->id())]
        ]);

        auth()->user()->update([
            'name' => $request->input('name'),
            'email' => $request->input('email')
        ]);

        return back()->with('status', __('Updated user profile'));
    }

}
