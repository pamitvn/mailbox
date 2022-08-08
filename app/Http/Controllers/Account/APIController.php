<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Str;

class APIController extends Controller
{
    public function index()
    {
        return inertia('Account/API', [
            'api_key' => auth()->user()->api_key,
        ]);
    }

    public function store(Request $request)
    {
        auth()->user()->update([
            'api_key' => Str::uuid()->toString(),
        ]);

        send_current_user_message('success', __('Created new API token'));

        return back();
    }
}
