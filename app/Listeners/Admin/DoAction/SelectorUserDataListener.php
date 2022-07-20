<?php

namespace App\Listeners\Admin\DoAction;

use App\Events\Admin\DoActionEvent;
use App\Models\User;
use App\PAM\Facades\ApiResponse;
use Illuminate\Support\Arr;

class SelectorUserDataListener
{
    public function handle(DoActionEvent $event)
    {
        if (! $event->isAction('selector') || ! $event->isTarget('type', 'user')) {
            return null;
        }

        $params = $event->request->except('action', 'type');
        $search = Arr::get($params, 'search');

        $users = User::query()->select(['id', 'name', 'username', 'email']);

        search_by_cols($users, $search, [
            'id',
            'name',
            'username',
            'email',
        ]);

        return ApiResponse::withSuccess()->withData($users->get());
    }
}
