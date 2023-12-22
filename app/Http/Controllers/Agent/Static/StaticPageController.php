<?php

namespace App\Http\Controllers\Agent\Static;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StaticPageController extends Controller
{
    //About Us Page
    public function aboutUs()
    {
        try {
            return view('agent-views.static.about-us');
        } catch (\Exception $error) {
            return back()->withErrors($error->getMessage());
        }
    }
    // Privacy Policy Page
    public function privacyPolicy()
    {
        try {
            return view('agent-views.static.privacy-policy');
        } catch (\Exception $error) {
            return back()->withErrors($error->getMessage());
        }
    }
    // Term of Use Page
    public function termOfUse()
    {
        try {
            return view('agent-views.static.terms-use');
        } catch (\Exception $error) {
            return back()->withErrors($error->getMessage());
        }
    }
    // FAQ Page
    public function faq()
    {
        try {
            return view('agent-views.static.faq');
        } catch (\Exception $error) {
            return back()->withErrors($error->getMessage());
        }
    }
    // Support Page 
    public function support()
    {
        try {
            return view('agent-views.static.support');
        } catch (\Exception $error) {
            return back()->withErrors($error->getMessage());
        }
    }
}
