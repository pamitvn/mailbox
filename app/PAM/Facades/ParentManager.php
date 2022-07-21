<?php

namespace App\PAM\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \App\PAM\ParentManager
 */
class ParentManager extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \App\PAM\ParentManager::$getFacadeAccessor;
    }
}
