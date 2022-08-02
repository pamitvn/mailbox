<?php

use App\Events\Sockets\UserMessageEvent;
use App\PAM\Facades\AdminSetting;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Psr\Log\LoggerInterface;

if (! function_exists('table_name_of_model')) {
    function table_name_of_model(string $model)
    {
        return with(new $model)->getTable();
    }
}

if (! function_exists('dispatch_action')) {
    function dispatch_action($dispatch): mixed
    {
        $result = Arr::first(array_filter($dispatch, function ($val) {
            return ! is_null($val);
        }));

        if ($result) {
            return $result;
        }

        abort(404);
    }
}

if (! function_exists('settings')) {
    function settings($key = null, $default = null, $group = null)
    {
        $group ??= config('admin.settings.default');

        $settings = AdminSetting::get($group);

        return $key
            ? Arr::get($settings, $key, $default)
            : $settings;
    }
}

if (! function_exists('send_current_user_message')) {
    function send_current_user_message($type, $message, $userId = null): void
    {
        $userId ??= auth()->id();

        UserMessageEvent::dispatch($userId, [
            'type' => $type,
            'message' => $message,
        ]);
    }
}

if (! function_exists('send_message_if')) {
    function send_message_if($boolean, $message, $unlessMessage, $userId = null, $allowBack = false): RedirectResponse|null
    {
        send_current_user_message(
            $boolean ? 'success' : 'danger',
            $boolean ? $message : $unlessMessage,
            $userId
        );

        if ($allowBack) {
            return $boolean
                ? back()
                : back()->withErrors('Error', 'globalError');
        }

        return null;
    }
}

if (! function_exists('day_of_month_to_array')) {
    function day_of_month_to_array($start = null, $end = null): array
    {
        $start ??= Carbon::now()->startOfMonth();
        $end ??= Carbon::now()->day;

        $period = CarbonPeriod::create($start, $end);
        $dates = [];

        foreach ($period as $date) {
            $dates[] = $date->format('d-m-Y');
        }

        return $dates;
    }
}

if (! function_exists('group_date_chart_by_day')) {
    function group_chart_by_day(Collection $collect, array $dates)
    {
        $values = [];

        foreach ($dates as $day) {
            $date = \Illuminate\Support\Carbon::parse($day);
            $payload = $collect->get($date->day, 0);

            $values['month'][] = $payload;
            $values['week'][] = $date >= Carbon::parse($day)->startOfWeek() && $date <= Carbon::parse($day)->endOfWeek()
                ? $payload
                : 0;
            $values['today'][] = $date->day === now()->day ? $payload : 0;
        }

        return $values;
    }
}

if (! function_exists('pam_system_log')) {
    function pam_system_log($channel = 'pam-system'): LoggerInterface
    {
        return \Illuminate\Support\Facades\Log::channel($channel);
    }
}

require 'query.php';
