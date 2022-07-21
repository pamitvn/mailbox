<?php

namespace App\Http\Controllers\Admin;

use App\Events\Admin\DoActionEvent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DoActionController extends Controller
{
    public function __invoke(Request $request)
    {
        return dispatch_action(event(new DoActionEvent($request)));
    }
}
