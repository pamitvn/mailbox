<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\RechargeHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;

class RechargeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $perPage = $request->get('perPage', 10);

        $histories = RechargeHistory::with('bank')->whereUserId(auth()->id())
            ->orderByDesc('id')
            ->when($search, fn ($query) => $query->where('note', 'LIKE', "%{$search}%"));

        return inertia('Recharge/RechargeManager', [
            'rechargeCode' => Blade::render(config('web-config.recharge_code'), array_merge(auth()->user()->toArray(), [
                'hostname' => request()->getHttpHost(),
            ])),
            'banks' => Bank::get(),
            'paginationData' => $histories->paginate($perPage),
        ]);
    }
}
