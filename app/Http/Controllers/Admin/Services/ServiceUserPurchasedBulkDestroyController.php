<?php

namespace App\Http\Controllers\Admin\Services;

use App\Http\Controllers\Controller;
use App\Jobs\UserPurchasedDestroyJob;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceUserPurchasedBulkDestroyController extends Controller
{
    public function __invoke(Request $request, Service $service)
    {
        $data = $request->validate([
            'includes' => ['required', 'array', 'min:1'],
        ]);

        dispatch(new UserPurchasedDestroyJob(auth()->user(), $service, $data['includes']));

        send_current_user_message('info', __('Added to the delete queue'));

        return back();
    }
}
