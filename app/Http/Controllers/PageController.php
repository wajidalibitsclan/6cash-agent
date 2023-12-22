<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class PageController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function get_terms_and_conditions(): View|Factory|Application
    {
        return view('terms-and-conditions');
    }

    /**
     * @return Application|Factory|View
     */
    public function get_privacy_policy(): Factory|View|Application
    {
        return view('privacy-policy');
    }

    /**
     * @return Application|Factory|View
     */
    public function get_about_us(): Factory|View|Application
    {
        return view('about-us');
    }

}
