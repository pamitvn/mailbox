<?php

namespace App\PAM\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \App\PAM\AdminSetting
 */
class AdminSetting extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \App\PAM\AdminSetting::$getFacadeAccessor;
    }
}
