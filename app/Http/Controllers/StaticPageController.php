<?php

namespace App\Http\Controllers;

class StaticPageController extends Controller
{
    public function home()
    {
        return inertia('Home');
    }
}
