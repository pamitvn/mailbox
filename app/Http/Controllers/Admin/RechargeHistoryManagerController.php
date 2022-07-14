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

        $records = RechargeHistory::query()
            ->with(['bank', 'user'])
            ->orderBy('id', 'desc');

        search_by_cols($records, $search, [
            'note'
        ]);

        return inertia('Admin/Recharge/HistoryManager', [
            'paginationData' => paginate_with_params($records, $request->all())
        ]);
    }


    public function destroy($history)
    {
        $history = RechargeHistory::findOrFail($history);
        $history->delete();

        return back()->with('success', __('Deleted history #:id', ['id' => $history->id]));
    }
}
