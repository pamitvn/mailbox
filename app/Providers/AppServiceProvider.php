<?php

namespace App\Providers;

use App\Charts;
use App\Models\Order;
use App\Models\Product;
use App\Models\Service;
use App\Models\User;
use App\Observers\OrderObserver;
use App\Observers\ProductObserver;
use App\Observers\ServiceObserver;
use App\Observers\UserObserver;
use App\PAM\AdminSetting;
use App\PAM\ApiResponse;
use App\PAM\ParentManager;
use ConsoleTVs\Charts\Registrar as RegistrarCharts;
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
        $this->app->singleton(ParentManager::$getFacadeAccessor, fn() => new ParentManager);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(RegistrarCharts $charts)
    {
        $this->defineGate();
        $this->defineObserver();

        Route::matched(function () {
            $this->defineLayoutMenu();
        });

        $charts->register([
            Charts\UserSpendingChart::class
        ]);
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
        Service::observe(ServiceObserver::class);
        Order::observe(OrderObserver::class);
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
                    'label' => 'Statistics',
                    'class' => '',
                    'icon' => "<i data-feather='bar-chart-2'></i>",
                    'target' => fn() => route('statistic'),
                    'auth' => true
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
                [
                    'label' => 'Orders',
                    'class' => '',
                    'icon' => "<i data-feather='shopping-bag'></i>",
                    'target' => route('orders'),
                    'auth' => true,
                ],
                [
                    'label' => 'Recharge',
                    'class' => '',
                    'icon' => "<i data-feather='dollar-sign'></i>",
                    'target' => route('recharge'),
                    'auth' => true,
                ],
                [
                    'label' => 'API Docs',
                    'class' => '',
                    'icon' => "<i data-feather='paperclip'></i>",
                    'target' => 'https://documenter.getpostman.com/view/12129573/UzQrRn9d',
                    'auth' => true,
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
                            'label' => 'Statistics',
                            'class' => '',
                            'icon' => "<i data-feather='bar-chart-2'></i>",
                            'target' => fn() => route('admin.statistics')
                        ],
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
                        [
                            'label' => 'Admin API Docs',
                            'class' => '',
                            'icon' => "<i data-feather='paperclip'></i>",
                            'target' => 'https://documenter.getpostman.com/view/12129573/UzQrRnDv'
                        ],
                    ]
                ],
            ],
        ]]);
    }
}
