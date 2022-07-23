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
        $this->app->singleton(ApiResponse::$getFacadeAccessor, fn () => new ApiResponse());
        $this->app->singleton(AdminSetting::$getFacadeAccessor, fn (Application $app) => new AdminSetting($app->make('config')->get('admin.settings')));
        $this->app->singleton(ParentManager::$getFacadeAccessor, fn () => new ParentManager);
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
            Charts\UserSpendingChart::class,
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
                    'group' => 'General',
                    'items' => [
                        [
                            'label' => 'Home',
                            'class' => '',
                            'icon' => '<svg class="shrink-0 h-6 w-6" viewBox="0 0 24 24"> <path class="fill-current text-slate-400" d="M12 0C5.383 0 0 5.383 0 12s5.383 12 12 12 12-5.383 12-12S18.617 0 12 0z" /> <path class="fill-current text-slate-600" d="M12 3c-4.963 0-9 4.037-9 9s4.037 9 9 9 9-4.037 9-9-4.037-9-9-9z" /> <path class="fill-current text-slate-400" d="M12 15c-1.654 0-3-1.346-3-3 0-.462.113-.894.3-1.285L6 6l4.714 3.301A2.973 2.973 0 0112 9c1.654 0 3 1.346 3 3s-1.346 3-3 3z" /> </svg>',
                            'iconActive' => '<svg class="shrink-0 h-6 w-6" viewBox="0 0 24 24"> <path class="fill-current text-slate-400 !text-indigo-500" d="M12 0C5.383 0 0 5.383 0 12s5.383 12 12 12 12-5.383 12-12S18.617 0 12 0z" /> <path class="fill-current text-slate-600 text-indigo-600" d="M12 3c-4.963 0-9 4.037-9 9s4.037 9 9 9 9-4.037 9-9-4.037-9-9-9z" /> <path class="fill-current text-slate-400 text-indigo-200" d="M12 15c-1.654 0-3-1.346-3-3 0-.462.113-.894.3-1.285L6 6l4.714 3.301A2.973 2.973 0 0112 9c1.654 0 3 1.346 3 3s-1.346 3-3 3z" /> </svg>',
                            'target' => url('/'),
                        ],
                        [
                            'label' => 'Statistics',
                            'class' => '',
                            'icon' => '<svg class="shrink-0 h-6 w-6" viewBox="0 0 24 24"><path class="fill-current text-slate-400" d="M13 6.068a6.035 6.035 0 0 1 4.932 4.933H24c-.486-5.846-5.154-10.515-11-11v6.067Z"></path><path class="fill-current text-slate-700" d="M18.007 13c-.474 2.833-2.919 5-5.864 5a5.888 5.888 0 0 1-3.694-1.304L4 20.731C6.131 22.752 8.992 24 12.143 24c6.232 0 11.35-4.851 11.857-11h-5.993Z"></path><path class="fill-current text-slate-600" d="M6.939 15.007A5.861 5.861 0 0 1 6 11.829c0-2.937 2.167-5.376 5-5.85V0C4.85.507 0 5.614 0 11.83c0 2.695.922 5.174 2.456 7.17l4.483-3.993Z"></path></svg>',
                            'iconActive' => '<svg class="shrink-0 h-6 w-6" viewBox="0 0 24 24"><path class="fill-current text-slate-400 text-indigo-300" d="M13 6.068a6.035 6.035 0 0 1 4.932 4.933H24c-.486-5.846-5.154-10.515-11-11v6.067Z"></path><path class="fill-current text-slate-700 !text-indigo-500" d="M18.007 13c-.474 2.833-2.919 5-5.864 5a5.888 5.888 0 0 1-3.694-1.304L4 20.731C6.131 22.752 8.992 24 12.143 24c6.232 0 11.35-4.851 11.857-11h-5.993Z"></path><path class="fill-current text-slate-600 text-indigo-600" d="M6.939 15.007A5.861 5.861 0 0 1 6 11.829c0-2.937 2.167-5.376 5-5.85V0C4.85.507 0 5.614 0 11.83c0 2.695.922 5.174 2.456 7.17l4.483-3.993Z"></path></svg>',
                            'target' => fn () => route('statistic'),
                            'auth' => true,
                        ],
                        [
                            'label' => 'Account',
                            'class' => '',
                            'icon' => '<svg class="shrink-0 h-6 w-6" viewBox="0 0 24 24"><path class="fill-current text-slate-400" d="M13 15l11-7L11.504.136a1 1 0 00-1.019.007L0 7l13 8z"></path><path class="fill-current text-slate-700" d="M13 15L0 7v9c0 .355.189.685.496.864L13 24v-9z"></path><path class="fill-current text-slate-600" d="M13 15.047V24l10.573-7.181A.999.999 0 0024 16V8l-11 7.047z"></path></svg>',
                            'iconActive' => '<svg class="shrink-0 h-6 w-6" viewBox="0 0 24 24"><path class="fill-current text-slate-400 text-indigo-300" d="M13 15l11-7L11.504.136a1 1 0 00-1.019.007L0 7l13 8z"></path><path class="fill-current text-slate-700 !text-indigo-600" d="M13 15L0 7v9c0 .355.189.685.496.864L13 24v-9z"></path><path class="fill-current text-slate-600 text-indigo-500" d="M13 15.047V24l10.573-7.181A.999.999 0 0024 16V8l-11 7.047z"></path></svg>',
                            'target' => 'javascript:;',
                            'auth' => true,
                            'items' => [
                                [
                                    'label' => 'Profile',
                                    'class' => '',
                                    'icon' => '<i class="fa-regular fa-user"></i>',
                                    'target' => route('account.profile'),
                                ],
                                [
                                    'label' => 'Reset Password',
                                    'class' => '',
                                    'icon' => "<i data-feather='key'></i>",
                                    'target' => route('account.reset-password'),
                                ],
                                [
                                    'label' => 'API',
                                    'class' => '',
                                    'icon' => "<i data-feather='tool'></i>",
                                    'target' => route('account.api'),
                                ],
                            ],
                        ],
                        [
                            'label' => 'Orders',
                            'class' => '',
                            'icon' => '<svg class="shrink-0 h-6 w-6" viewBox="0 0 24 24"><path class="fill-current text-slate-600" d="M16 13v4H8v-4H0l3-9h18l3 9h-8Z"></path><path class="fill-current text-slate-400" d="m23.72 12 .229.686A.984.984 0 0 1 24 13v8a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1v-8c0-.107.017-.213.051-.314L.28 12H8v4h8v-4H23.72ZM13 0v7h3l-4 5-4-5h3V0h2Z"></path></svg>',
                            'iconActive' => '<svg class="shrink-0 h-6 w-6" viewBox="0 0 24 24"><path class="fill-current text-slate-600 text-indigo-500" d="M16 13v4H8v-4H0l3-9h18l3 9h-8Z"></path><path class="fill-current text-slate-400 text-indigo-300" d="m23.72 12 .229.686A.984.984 0 0 1 24 13v8a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1v-8c0-.107.017-.213.051-.314L.28 12H8v4h8v-4H23.72ZM13 0v7h3l-4 5-4-5h3V0h2Z"></path></svg>',
                            'target' => route('orders'),
                            'auth' => true,
                        ],
                        [
                            'label' => 'Recharge',
                            'class' => '',
                            'icon' => '<svg class="shrink-0 h-6 w-6" viewBox="0 0 24 24"><path class="fill-current text-slate-600" d="M20 7a.75.75 0 01-.75-.75 1.5 1.5 0 00-1.5-1.5.75.75 0 110-1.5 1.5 1.5 0 001.5-1.5.75.75 0 111.5 0 1.5 1.5 0 001.5 1.5.75.75 0 110 1.5 1.5 1.5 0 00-1.5 1.5A.75.75 0 0120 7zM4 23a.75.75 0 01-.75-.75 1.5 1.5 0 00-1.5-1.5.75.75 0 110-1.5 1.5 1.5 0 001.5-1.5.75.75 0 111.5 0 1.5 1.5 0 001.5 1.5.75.75 0 110 1.5 1.5 1.5 0 00-1.5 1.5A.75.75 0 014 23z"></path><path class="fill-current text-slate-400" d="M17 23a1 1 0 01-1-1 4 4 0 00-4-4 1 1 0 010-2 4 4 0 004-4 1 1 0 012 0 4 4 0 004 4 1 1 0 010 2 4 4 0 00-4 4 1 1 0 01-1 1zM7 13a1 1 0 01-1-1 4 4 0 00-4-4 1 1 0 110-2 4 4 0 004-4 1 1 0 112 0 4 4 0 004 4 1 1 0 010 2 4 4 0 00-4 4 1 1 0 01-1 1z"></path></svg>',
                            'iconActive' => '<svg class="shrink-0 h-6 w-6" viewBox="0 0 24 24"><path class="fill-current text-slate-600 text-indigo-500" d="M20 7a.75.75 0 01-.75-.75 1.5 1.5 0 00-1.5-1.5.75.75 0 110-1.5 1.5 1.5 0 001.5-1.5.75.75 0 111.5 0 1.5 1.5 0 001.5 1.5.75.75 0 110 1.5 1.5 1.5 0 00-1.5 1.5A.75.75 0 0120 7zM4 23a.75.75 0 01-.75-.75 1.5 1.5 0 00-1.5-1.5.75.75 0 110-1.5 1.5 1.5 0 001.5-1.5.75.75 0 111.5 0 1.5 1.5 0 001.5 1.5.75.75 0 110 1.5 1.5 1.5 0 00-1.5 1.5A.75.75 0 014 23z"></path><path class="fill-current text-slate-400 text-indigo-300" d="M17 23a1 1 0 01-1-1 4 4 0 00-4-4 1 1 0 010-2 4 4 0 004-4 1 1 0 012 0 4 4 0 004 4 1 1 0 010 2 4 4 0 00-4 4 1 1 0 01-1 1zM7 13a1 1 0 01-1-1 4 4 0 00-4-4 1 1 0 110-2 4 4 0 004-4 1 1 0 112 0 4 4 0 004 4 1 1 0 010 2 4 4 0 00-4 4 1 1 0 01-1 1z"></path></svg>',
                            'target' => route('recharge'),
                            'auth' => true,
                        ],
                        [
                            'label' => 'API Docs',
                            'class' => '',
                            'icon' => '<svg class="shrink-0 h-6 w-6" viewBox="0 0 24 24"><path class="fill-current text-slate-600" d="M19 5h1v14h-2V7.414L5.707 19.707 5 19H4V5h2v11.586L18.293 4.293 19 5Z"></path><path class="fill-current text-slate-400" d="M5 9a4 4 0 1 1 0-8 4 4 0 0 1 0 8Zm14 0a4 4 0 1 1 0-8 4 4 0 0 1 0 8ZM5 23a4 4 0 1 1 0-8 4 4 0 0 1 0 8Zm14 0a4 4 0 1 1 0-8 4 4 0 0 1 0 8Z"></path></svg>',
                            'iconActive' => '<svg class="shrink-0 h-6 w-6" viewBox="0 0 24 24"><path class="fill-current text-slate-600" d="M19 5h1v14h-2V7.414L5.707 19.707 5 19H4V5h2v11.586L18.293 4.293 19 5Z"></path><path class="fill-current text-slate-400" d="M5 9a4 4 0 1 1 0-8 4 4 0 0 1 0 8Zm14 0a4 4 0 1 1 0-8 4 4 0 0 1 0 8ZM5 23a4 4 0 1 1 0-8 4 4 0 0 1 0 8Zm14 0a4 4 0 1 1 0-8 4 4 0 0 1 0 8Z"></path></svg>',
                            'target' => 'https://documenter.getpostman.com/view/12129573/UzQrRn9d',
                            'auth' => true,
                        ],
                    ],
                ],
            ],
            'account' => [
                [
                    'label' => 'Profile',
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"> <path stroke="none" d="M0 0h24v24H0z" fill="none"></path> <circle cx="12" cy="7" r="4"></circle> <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path> </svg>',
                    'target' => route('account.profile'),
                ],
                [
                    'label' => 'Reset Password',
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"> <path stroke="none" d="M0 0h24v24H0z" fill="none"></path> <rect x="5" y="11" width="14" height="10" rx="2"></rect> <circle cx="12" cy="16" r="1"></circle> <path d="M8 11v-4a4 4 0 0 1 8 0v4"></path> </svg>',
                    'target' => route('account.reset-password'),
                ],
                [
                    'label' => 'API',
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"> <path stroke="none" d="M0 0h24v24H0z" fill="none"></path> <path d="M4 13h5"></path> <path d="M12 16v-8h3a2 2 0 0 1 2 2v1a2 2 0 0 1 -2 2h-3"></path> <path d="M20 8v8"></path> <path d="M9 16v-5.5a2.5 2.5 0 0 0 -5 0v5.5"></path> </svg>',
                    'target' => route('account.api'),
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
                            'target' => fn () => route('admin.statistics'),
                        ],
                        [
                            'label' => 'Services',
                            'class' => '',
                            'icon' => "<i data-feather='server'></i>",
                            'target' => route('admin.service.index'),
                            'extraMatched' => 'admin\/services(.+)',
                        ],
                        [
                            'label' => 'Users',
                            'class' => '',
                            'icon' => "<i data-feather='users'></i>",
                            'target' => route('admin.user.index'),
                            'extraMatched' => 'admin\/users(.+)',
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
                                    'extraMatched' => 'admin\/user-blacklisted(.+)',
                                ],
                            ],
                        ],
                        [
                            'label' => 'Banks',
                            'class' => '',
                            'icon' => "<i data-feather='dollar-sign'></i>",
                            'target' => route('admin.bank.index'),
                            'extraMatched' => 'admin\/banks(.+)',
                        ],
                        [
                            'label' => 'Recharge History',
                            'class' => '',
                            'icon' => "<i data-feather='dollar-sign'></i>",
                            'target' => route('admin.recharge-history.index'),
                            'extraMatched' => 'admin\/recharge-histories(.+)',
                        ],
                        [
                            'label' => 'Settings',
                            'class' => '',
                            'icon' => "<i data-feather='settings'></i>",
                            'target' => route('admin.setting', config('admin.settings.default')),
                            'extraMatched' => 'admin\/settings(.+)',
                        ],
                        [
                            'label' => 'Admin API Docs',
                            'class' => '',
                            'icon' => "<i data-feather='paperclip'></i>",
                            'target' => 'https://documenter.getpostman.com/view/12129573/UzQrRnDv',
                        ],
                    ],
                ],
            ],
        ]]);
    }
}
