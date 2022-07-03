<?php

use App\PAM\Facades\AdminSetting;
use Illuminate\Support\Arr;

if (!function_exists('table_name_of_model')) {
    function table_name_of_model(string $model)
    {
        return with(new $model)->getTable();
    }
}

if (!function_exists('dispatch_action')) {
    function dispatch_action($dispatch): mixed
    {
        $result = Arr::first(array_filter($dispatch, function ($val) {
            return !is_null($val);
        }));


        if ($result) {
            return $result;
        }

        abort(404);
    }
}

if (!function_exists('settings')) {
    function settings($key = null, $default = null, $group = null)
    {
        $group ??= config('admin.settings.default');

        $settings = AdminSetting::get($group);

        return $key
            ? Arr::get($settings, $key, $default)
            : $settings;
    }
}

if (!function_exists('send_current_user_message')) {
    function send_current_user_message($type, $message)
    {

    }
}


require "query.php";
