<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    public function index()
    {
        return inertia('Account/ResetPassword');
    }

    public function update(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'min:8', 'current_password:web,'.$request->user()->id],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required'],
        ]);

        $password = $request->input('password');

        auth()->user()->update([
            'password' => Hash::make($password),
        ]);

        return back()->with('success', __('Updated password'));
    }
}
