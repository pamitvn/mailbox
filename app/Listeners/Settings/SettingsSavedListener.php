<?php

namespace App\Listeners\Settings;

use App\PAM\AdminSetting;
use Illuminate\Support\Facades\Cache;
use Spatie\LaravelSettings\Events\SettingsSaved;

class SettingsSavedListener
{
    public function handle(SettingsSaved $event)
    {
        Cache::forget(AdminSetting::$cacheKey);
    }
}
