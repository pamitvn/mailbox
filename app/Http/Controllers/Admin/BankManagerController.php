<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use File;
use Illuminate\Http\Request;

class BankManagerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $perPage = $request->get('perPage', 10);

        $bank = Bank::orderBy('id', 'desc')
            ->when($search, fn($query) => $query->where('name', 'LIKE', "%{$search}%"));

        return inertia('Admin/Bank/BankManager', [
            'paginationData' => $bank->paginate($perPage)
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
            'image' => [
                'nullable',
                'image',
                'mimes:jpeg,png',
                'mimetypes:image/jpeg,image/png',
                'max:2048',
            ]
        ]);

        if ($request->file('image')) {
            File::ensureDirectoryExists(storage_path('app/public/banks'));

            $file = $request->file('image');
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(storage_path('app/public/banks'), $filename);
            $data['image'] = 'banks/' . $filename;
        }

        Bank::create($data);

        return back()->with('success', __('Created new bank'));
    }

    public function edit(Bank $bank)
    {
        return inertia('Admin/Bank/Edit', [
            'bank' => $bank
        ]);
    }

    public function update(Request $request, Bank $bank)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'accountId' => ['required', 'string', 'max:150'],
            'accountName' => ['required', 'string', 'max:150'],
            'image' => [
                'nullable',
                'image',
                'mimes:jpeg,png',
                'mimetypes:image/jpeg,image/png',
                'max:2048',
            ]
        ]);

        if ($request->file('image')) {
            $this->removeImage($bank->image);
            File::ensureDirectoryExists(storage_path('app/public/banks'));

            $file = $request->file('image');
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(storage_path('app/public/banks'), $filename);
            $data['image'] = 'banks/' . $filename;
        } else {
            unset($data['image']);
        }

        $bank->update($data);

        return back()->with('success', __('Updated bank #:id', ['id' => $bank->id]));
    }

    public function destroy(Bank $bank)
    {
        $bank->delete();
        $this->removeImage($bank->image);

        return back()->with('success', __('Deleted bank #:id', ['id' => $bank->id]));
    }

    protected function removeImage($path)
    {
        File::delete($path);
    }
}
