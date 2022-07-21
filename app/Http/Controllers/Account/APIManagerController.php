<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Str;

class APIManagerController extends Controller
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

        return back()->with('success', 'Created new API token');
    }
}
