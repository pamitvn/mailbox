<?php

namespace App\Providers;

use App\Models\User;
use App\Observers\UserObserver;
use App\PAM\ApiResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }

        $this->app->singleton(ApiResponse::$getFacadeAccessor, fn() => new ApiResponse());
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->defineGate();
        $this->defineObserver();
    }

    protected function defineGate()
    {
        Gate::after(function ($user, $ability, $result, $arguments) {
            if ($user->isAdministrator()) {
                return true;
            }
        });
    }

    protected function defineObserver()
    {
        User::observe(UserObserver::class);
    }
}
