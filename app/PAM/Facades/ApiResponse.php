<?php

namespace App\PAM\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \App\PAM\ApiResponse
 */
class ApiResponse extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \App\PAM\ApiResponse::$getFacadeAccessor;
    }
}
