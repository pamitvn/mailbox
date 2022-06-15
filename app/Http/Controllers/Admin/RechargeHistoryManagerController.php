<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RechargeHistory;
use Illuminate\Http\Request;

class RechargeHistoryManagerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $perPage = $request->get('perPage', 10);

        $user = RechargeHistory::with('bank')->orderBy('id', 'desc')
            ->when($search, fn($query) => $query->where('note', 'LIKE', "%{$search}%"));

        return inertia('Admin/Recharge/HistoryManager', [
            'paginationData' => $user->paginate($perPage)
        ]);
    }


    public function destroy($history)
    {
        $history = RechargeHistory::findOrFail($history);
        $history->delete();

        return back()->with('success', __('Deleted history #:id', ['id' => $history->id]));
    }
}
