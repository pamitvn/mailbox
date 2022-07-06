<?php

namespace App\Providers;

use App\Models\Product;
use App\Models\User;
use App\Observers\ProductObserver;
use App\Observers\UserObserver;
use App\PAM\AdminSetting;
use App\PAM\ApiResponse;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
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
        $this->app->singleton(ApiResponse::$getFacadeAccessor, fn() => new ApiResponse());
        $this->app->singleton(AdminSetting::$getFacadeAccessor, fn(Application $app) => new AdminSetting($app->make('config')->get('admin.settings')));
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

        Route::matched(function () {
            $this->defineLayoutMenu();
        });
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
        Product::observe(ProductObserver::class);
    }

    protected function defineLayoutMenu()
    {
        config(['web-config.layouts.menu' => [
            'main' => [
                [
                    'label' => 'Home',
                    'class' => '',
                    'icon' => "<i data-feather='activity'></i>",
                    'target' => url('/')
                ],
                [
                    'label' => 'Account',
                    'class' => '',
                    'icon' => "<i data-feather='info'></i>",
                    'target' => 'javascript:;',
                    'auth' => true,
                    'items' => [
                        [
                            'label' => 'Profile',
                            'class' => '',
                            'icon' => '<i class="fa-regular fa-user"></i>',
                            'target' => route('account.profile')
                        ],
                        [
                            'label' => 'Reset Password',
                            'class' => '',
                            'icon' => "<i data-feather='key'></i>",
                            'target' => route('account.reset-password')
                        ],
                        [
                            'label' => 'API',
                            'class' => '',
                            'icon' => "<i data-feather='tool'></i>",
                            'target' => route('account.api')
                        ],
                    ]
                ],
            ],
            'account' => [
                [
                    'label' => 'Profile',
                    'class' => '',
                    'target' => route('account.profile')
                ],
                [
                    'label' => 'Reset Password',
                    'class' => '',
                    'target' => route('account.reset-password')
                ],
                [
                    'label' => 'API',
                    'class' => '',
                    'target' => route('account.api')
                ],
            ],
            'admin' => [
                [
                    'group' => 'Admin Area',
                    'items' => [
                        [
                            'label' => 'Services',
                            'class' => '',
                            'icon' => "<i data-feather='server'></i>",
                            'target' => route('admin.service.index'),
                            'extraMatched' => 'admin\/services(.+)'
                        ],
                        [
                            'label' => 'Users',
                            'class' => '',
                            'icon' => "<i data-feather='users'></i>",
                            'target' => route('admin.user.index'),
                            'extraMatched' => 'admin\/users(.+)'
                        ],
                        [
                            'label' => 'Blacklisted',
                            'class' => '',
                            'icon' => "<i data-feather='hexagon'></i>",
                            'target' => 'javascript:;',
                            'items' => [
                                [
                                    'label' => 'Users',
                                    'class' => '',
                                    'icon' => "<i data-feather='users'></i>",
                                    'target' => route('admin.blacklisted.user.index'),
                                    'extraMatched' => 'admin\/user-blacklisted(.+)'
                                ],
                            ]
                        ],
                        [
                            'label' => 'Banks',
                            'class' => '',
                            'icon' => "<i data-feather='dollar-sign'></i>",
                            'target' => route('admin.bank.index'),
                            'extraMatched' => 'admin\/banks(.+)'
                        ],
                        [
                            'label' => 'Recharge History',
                            'class' => '',
                            'icon' => "<i data-feather='dollar-sign'></i>",
                            'target' => route('admin.recharge-history.index'),
                            'extraMatched' => 'admin\/recharge-histories(.+)'
                        ],
                        [
                            'label' => 'Settings',
                            'class' => '',
                            'icon' => "<i data-feather='settings'></i>",
                            'target' => route('admin.setting', config('admin.settings.default')),
                            'extraMatched' => 'admin\/settings(.+)'
                        ],
                    ]
                ],
            ],
        ]]);
    }
}
