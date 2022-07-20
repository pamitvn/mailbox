<?php

namespace App\Http\Middleware;

use App\PAM\Layout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     *
     * @param  Request  $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @param  Request  $request
     * @return array
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'site' => [
                'name' => settings('site_name'),
            ],
            'urlPrev' => fn () => url()->previous(),
            'auth' => [
                'isLoggedIn' => fn () => auth()->check(),
                'user' => fn () => auth()->user(),
            ],
            'flash' => [
                'message' => fn () => $request->session()->get('message'),
                'status' => fn () => $request->session()->get('status'),
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
            'layouts' => fn () => array_merge(Layout::make()->all(), [
                'menu' => array_merge(Layout::make()->all('menu'), [
                    'main' => Gate::allows('admin') ? array_merge(Layout::make()->all('menu.main'), Layout::make()->all('menu.admin')) : Layout::make()->all('menu.main'),
                ]),
            ]),
            'notification' => fn () => settings(group: 'notification'),
        ]);
    }
}
