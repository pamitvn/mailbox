<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use File;
use Illuminate\Http\Request;

class BankController extends Controller
{
    public function index(Request $request)
    {
        $params = $request->all();
        $search = $request->get('search');

        $banks = Bank::query()->orderByDesc('id');

        search_by_cols($banks, $search, [
            'name',
        ]);

        return inertia('Admin/Bank/Manager', [
            'paginationData' => cursor_paginate_with_params($banks, $params),
        ]);
    }

    public function create()
    {
        return inertia('Admin/Bank/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'accountId' => ['required', 'string', 'max:150'],
            'accountName' => ['required', 'string', 'max:150'],
        ]);

        $bank = Bank::create($data);

        return send_message_if(
            boolean: filled($bank),
            message: __('Created new bank'),
            unlessMessage: __('Bank cannot be created'),
            allowBack: true
        );
    }

    public function edit(Bank $bank)
    {
        return inertia('Admin/Bank/Edit', [
            'bank' => $bank,
        ]);
    }

    public function update(Request $request, Bank $bank)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'accountId' => ['required', 'string', 'max:150'],
            'accountName' => ['required', 'string', 'max:150'],
        ]);

        return send_message_if(
            boolean: $bank->update($data),
            message: __('Updated bank #:id', ['id' => $bank->id]),
            unlessMessage: __('Updated #:id cannot be updated', ['id' => $bank->id]),
            allowBack: true
        );
    }

    public function destroy(Bank $bank)
    {
        $this->removeImage($bank->image);

        return send_message_if(
            boolean: $bank->delete(),
            message: __('Deleted bank #:id', ['id' => $bank->id]),
            unlessMessage: __('Bank #:id cannot be deleted', ['id' => $bank->id]),
            allowBack: true
        );
    }

    protected function removeImage($path)
    {
        File::delete($path);
    }
}
