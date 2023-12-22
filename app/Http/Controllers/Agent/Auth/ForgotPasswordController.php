<?php

namespace App\Http\Controllers\Agent\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stevebauman\Location\Facades\Location;
use Illuminate\Support\Facades\Session;
use Gregwar\Captcha\PhraseBuilder;
use Gregwar\Captcha\CaptchaBuilder;
use App\CentralLogics\helpers;
use Illuminate\Support\Facades\Http;


class ForgotPasswordController extends Controller
{
    public $basePath;
    public function __construct()
    {
        $this->basePath = env('APP_URL');
    }
    //Captcha Handing
    public function captcha($tmp): void
    {
        $phrase = new PhraseBuilder;
        $code = $phrase->build(4);
        $builder = new CaptchaBuilder($code, $phrase);
        $builder->setBackgroundColor(220, 210, 230);
        $builder->setMaxAngle(25);
        $builder->setMaxBehindLines(0);
        $builder->setMaxFrontLines(0);
        $builder->build($width = 100, $height = 40, $font = null);
        $phrase = $builder->getPhrase();

        if (Session::has('default_captcha_code')) {
            Session::forget('default_captcha_code');
        }
        Session::put('default_captcha_code', $phrase);
        header("Cache-Control: no-cache, must-revalidate");
        header("Content-Type:image/jpeg");
        $builder->output();
    }
    // Forgot Password Page
    public function index(Request $request)
    {
        try {
            $ip = env('APP_MODE') == 'live' ? $request->ip() : '61.247.180.82';
            $current_user_info = Location::get($ip);
            return view('agent-views.auth.forgot-password', compact('current_user_info'));
        } catch (\Exception $error) {
            return back()->withErrors($error->getMessage());
        }
    }
    // Forgot Password Submit
    public function submit(Request $request)
    {
        try {
            $recaptcha = Helpers::get_business_settings('recaptcha');
            if (isset($recaptcha) && $recaptcha['status'] == 1) {
                $request->validate([
                    'g-recaptcha-response' => [
                        function ($attribute, $value, $fail) {
                            $secret_key = Helpers::get_business_settings('recaptcha')['secret_key'];
                            $response = $value;
                            $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $response;
                            $response = \file_get_contents($url);
                            $response = json_decode($response);
                            if (!$response->success) {
                                $fail(translate($attribute . ' google reCaptcha failed'));
                            }
                        },
                    ],
                ]);
            } else {
                if (strtolower($request->default_captcha_value) != strtolower(Session('default_captcha_code'))) {
                    Session::forget('default_captcha_code');
                    return back()->withErrors(translate('Captcha Failed'));
                }
            }

            $endpoint = $this->basePath . "/api/v1/agent/auth/forgot-password";
            $header = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'User-Agent' => 'Dart'
            ];

            $phone = $request->country_code . $request->phone;
            $payload = [
                'phone' => $phone,
            ];

            $response = Http::withHeaders($header)->post($endpoint, $payload);
            $responseBody = $response->json();

            if (isset($responseBody['errors']['validation-error'])) {
                $errors = $this->validationErrors($responseBody['errors']);
                return back()->withErrors($errors);
            }

            if (isset($responseBody['code']) && $responseBody['code'] === 'success') {
                Session::put('phone', $phone);
                return redirect()->route('agent.fp.otp')->with('success', translate('OTP has been sent!'));
            } else {
                return back()->withErrors(translate('Something went wrong!'));
            }
        } catch (\Exception $error) {
            return back()->withErrors($error->getMessage());
        }
    }
    // Verify OTP
    public function verifyOtp(Request $request)
    {
        try {
            $ip = env('APP_MODE') == 'live' ? $request->ip() : '61.247.180.82';
            $current_user_info = Location::get($ip);
            return view('agent-views.auth.forgot-password-otp', compact('current_user_info'));
        } catch (\Exception $error) {
            return back()->withErrors($error->getMessage());
        }
    }
    // Check OTP 
    public function checkOtp(Request $request)
    {
        try {
            $endpoint = $this->basePath . "/api/v1/agent/auth/verify-token";
            $header = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'User-Agent' => 'Dart'
            ];

            $payload = [
                'phone' => $request->phone,
                'otp' => $request->otp
            ];

            $response = Http::withHeaders($header)->post($endpoint, $payload);
            $responseBody = $response->json();

            if (isset($responseBody['otp'])) {
                return back()->withErrors('errors', $responseBody['message']);
            }

            if (isset($responseBody['code']) && $responseBody['code'] === 'success') {
                Session::put('otp', $request->otp);
                Session::put('reset-page', 'can-visit');
                return redirect()->route('agent.auth.set.reset.pin');
            } else {
                return back()->withErrors('errors', translate('Something went wrong!'));
            }
        } catch (\Exception $error) {
            return back()->withErrors($error->getMessage());
        }
    }
    // Reset PIN
    public function resetPin(Request $request)
    {
        try {
            $ip = env('APP_MODE') == 'live' ? $request->ip() : '61.247.180.82';
            $current_user_info = Location::get($ip);
            return view('agent-views.auth.reset-pin', compact('current_user_info'));
        } catch (\Exception $error) {
            return back()->withErrors($error->getMessage());
        }
    }
    // SET PIN
    public function setPin(Request $request)
    {
        try {
            $endpoint = $this->basePath . "/api/v1/agent/auth/reset-password";
            $header = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'User-Agent' => 'Dart'
            ];

            $payload = [
                'phone' => $request->phone,
                'otp' => $request->otp,
                'password' => $request->password,
                'confirm_password' => $request->c_password
            ];

            $response = Http::withHeaders($header)->put($endpoint, $payload);
            $responseBody = $response->json();

            if (isset($responseBody['code']) && $responseBody['code'] === 'success') {
                Session::remove('reset-page');
                Session::remove('otp');
                return redirect()->route('agent.login');
            } else {
                return back()->with('errors', translate('Something went wrong!'));
            }
        } catch (\Exception $error) {
            return back()->withErrors($error->getMessage());
        }
    }
    // Resent OTP 
    public function resentOTP(Request $request)
    {
        try {
            $endpoint = $this->basePath . "/api/v1/agent/auth/resend-otp";
            $header = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'User-Agent' => 'Dart'
            ];

            $payload = [
                'phone' => $request->phone,
            ];

            $response = Http::withHeaders($header)->post($endpoint, $payload);
            $responseBody = $response->json();
        } catch (\Exception $error) {
            return back()->withErrors($error->getMessage());
        }
    }
}
