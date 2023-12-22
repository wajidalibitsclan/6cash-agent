<?php

namespace App\Http\Controllers\Agent\Auth;

use App\Models\City;
use App\Models\User;
use Illuminate\Http\Request;
use App\CentralLogics\helpers;
use Illuminate\Support\Carbon;
use App\Models\BusinessSetting;
use Illuminate\Validation\Rule;
use Gregwar\Captcha\PhraseBuilder;
use Illuminate\Support\Facades\DB;
use Gregwar\Captcha\CaptchaBuilder;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Stevebauman\Location\Facades\Location;
use App\CentralLogics\SMS_module;


class RegisterController extends Controller
{
    public $basePath;
    public function __construct()
    {
        $this->basePath = env('APP_URL');
    }
    // CAPTCHA Handling
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
    //Register Page
    public function index(Request $request)
    {
        try {
            $ip = env('APP_MODE') == 'live' ? $request->ip() : '61.247.180.82';
            $current_user_info = Location::get($ip);
            return view('agent-views.auth.register', compact('current_user_info'));
        } catch (\Exception $error) {
            return redirect()->back()->withErrors($error)->withInput();
        }
    }
    // CHECK Phone Number
    public function checkPhone(Request $request)
    {
        try {
            //recaptcha validation
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

            $agent = User::where(['phone' => $request->country_code . $request->phone])->first();

            if (!is_null($agent) && $agent->type == 1) {
                Toastr::error('This phone is already taken');
                return back();
            }

            // if (isset($agent) && $agent->type != 1) {
            //     Toastr::error(translate('This phone is already register as customer'));
            //     return back();
            // }

            if (BusinessSetting::where(['key' => 'phone_verification'])->first()->value) {

                $otp_interval_time = Helpers::get_business_settings('otp_resend_time') ?? 60; // seconds
                $otp_verification_data = DB::table('phone_verifications')->where('phone', $request->country_code . $request->phone)->first();

                if (isset($otp_verification_data) &&  Carbon::parse($otp_verification_data->created_at)->DiffInSeconds() < $otp_interval_time) {
                    $time = $otp_interval_time - Carbon::parse($otp_verification_data->created_at)->DiffInSeconds();
                    Toastr::error(translate('please_try_again_after_') . $time . ' ' . translate('seconds'));
                    return back();
                }

                $otp = mt_rand(1000, 9999);
                if (env('APP_MODE') != LIVE) {
                    $otp = '1234'; //hard coded
                }

                DB::table('phone_verifications')->updateOrInsert(['phone' => $request->country_code . $request->phone], [
                    'otp' => $otp,
                    'otp_hit_count' => 0,
                    'is_temp_blocked' => 0,
                    'temp_block_time' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $phoneNo = $request->country_code . $request->phone;

                SMS_module::send($phoneNo, $otp);

                Session::put('phone', $phoneNo);
                Session::put('phone_no', $request->phone);
                Session::put('country_code', $request->country_code);
                Session::put('otp-page', 'can-visit');

                Toastr::success(translate('Number is ready to register'));
                return redirect()->route('agent.auth.otp');
            } else {
                Toastr::error(translate('OTP sent failed'));
                return back();
            }

            // return;
            // $endpoint = $this->basePath . "/api/v1/agent/auth/check-phone";
            // $header = [
            //     'Accept' => 'application/json',
            //     'Content-Type' => 'application/json',
            //     'User-Agent' => 'Dart'
            // ];

            // $payload = [
            //     'phone' => $request->country_code . $request->phone,
            // ];

            // $response = Http::withHeaders($header)->post($endpoint, $payload);
            // $responseBody = $response->json();

            // if (isset($responseBody['otp']) && $responseBody['otp'] === 'active') {
            //     $phoneNo = $request->country_code . $request->phone;
            //     Session::put('phone', $phoneNo);
            //     Session::put('phone_no', $request->phone);
            //     Session::put('country_code', $request->country_code);
            //     Session::put('otp-page', 'can-visit');
            //     return redirect()->route('agent.auth.otp');
            // } else {
            //     return back()->withErrors(translate('Something went wrong!'));
            // }
        } catch (\Exception $error) {
            return redirect()->back()->withErrors($error)->withInput();
        }
    }
    // Verify OTP 
    public function verifyOtp(Request $request)
    {
        try {
            $ip = env('APP_MODE') == 'live' ? $request->ip() : '61.247.180.82';
            $current_user_info = Location::get($ip);
            return view('agent-views.auth.otp', compact('current_user_info'));
        } catch (\Exception $error) {
            return redirect()->back()->with('error', $error);
        }
    }
    // Check OTP
    public function checkOtp(Request $request)
    {
        try {
            $endpoint = $this->basePath . "/api/v1/agent/auth/verify-phone";
            $header = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'User-Agent' => 'Dart'
            ];

            $payload = [
                'phone' => $request->country_code . $request->phone,
                'otp' => $request->otp
            ];

            $response = Http::withHeaders($header)->post($endpoint, $payload);
            $responseBody = $response->json();

            if (isset($responseBody['code']) && $responseBody['code'] === 'success') {
                Session::put('otp', $request->otp);
                Session::put('face-page', 'can-visit');
                Session::put('information-page', 'can-visit');
                Session::remove('otp-page');
                return redirect()->route('agent.auth.face.verification');
            } else {
                return redirect()->back()->with('error', translate('Something went wrong!'));
            }
        } catch (\Exception $error) {
            return redirect()->back()->with('error', $error);
        }
    }
    // Resend OTP
    public function resendOtp(Request $request)
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

            if (isset($responseBody['otp'])) {
                Toastr::success(translate('OTP sent to your number successfully!'));
                return back();
            }
        } catch (\Exception $error) {
            return redirect()->back()->withErrors($error)->withInput();
        }
    }
    // Infomation Page
    public function information(Request $request)
    {
        try {
            $ip = env('APP_MODE') == 'live' ? $request->ip() : '61.247.180.82';
            $current_user_info = Location::get($ip);
            return view('agent-views.auth.information', compact('current_user_info'));
        } catch (\Exception $error) {
            return redirect()->back()->withErrors($error)->withInput();
        }
    }
    // Set PIN page
    public function setPin(Request $request)
    {
        try {
            $ip = env('APP_MODE') == 'live' ? $request->ip() : '61.247.180.82';
            $current_user_info = Location::get($ip);
            return view('agent-views.auth.set-pin', compact('current_user_info'));
        } catch (\Exception $error) {
            return redirect()->back()->withErrors($error)->withInput();
        }
    }
    // Information Submit
    public function setInformation(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'f_name' => 'required',
                'l_name' => 'required',
                'gender' => 'required',
                'occupation' => 'required',
                'dial_country_code' => 'required',
                'phone' => [
                    'required',
                    Rule::unique('users')->where(function ($query) {
                        return $query->whereNull('deleted_at');
                    }),
                    'min:5',
                    'max:20',
                ],
                'email' => '',
                'country' => 'required',
                'city' => 'required'
            ], [
                'f_name.required' => translate('First Name is required'),
                'l_name.required' => translate('Last Name is required'),
                'gender.required' => translate('Gender is required'),
                'occupation.required' => translate('Occupation is required'),
                'dial_country_code.required' => translate('Dial Country code is required'),
                'phone.required' => translate('Phone is required'),
                'phone.min' => translate('Phone must have at least 5 characters'),
                'phone.max' => translate('Phone must be less than 20 characters'),
                'country.required' => translate('Country is required'),
                'city.required' => translate('City is required'),
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $city = City::where(['country_id' => $request->country_id, 'name' => $request->city])->first();

            Session::put('user', [
                'f_name' => $request->f_name,
                'l_name' => $request->l_name,
                'email' => $request->email,
                'gender' => $request->gender,
                'occupation' => $request->occupation,
                'dial_country_code' => $request->dial_country_code,
                'phone' => $request->phone,
                'country' => $request->country,
                'city' => $request->city,
                'country_id' => $request->country_id,
                'city_id' => $city->id

            ]);

            Session::remove('face-page');
            Session::remove('information-page');
            Session::put('set-page', 'can-visit');

            return redirect()->route('agent.set.pin');
        } catch (\Exception $error) {
            return redirect()->back()->withErrors($error)->withInput();
        }
    }
    // Final Register Page to SET PIN
    public function submit(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'password' => 'required|min:4|max:4',
                'c_password' => 'required|same:password'
            ], [
                'password.required' => translate('Password is required'),
                'password.min' => translate('Password must have 4 characters'),
                'password.max' => translate('Password must have 4 characters')
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator->getMessageBag());
            }

            $endpoint = $this->basePath . "/api/v1/agent/auth/register";
            $header = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'User-Agent' => 'Dart'
            ];

            $payload = [
                'f_name' => $request->f_name,
                'l_name' => $request->l_name,
                'email' => $request->email,
                'gender' => $request->gender,
                'occupation' => $request->occupation,
                'dial_country_code' => $request->dial_country_code,
                'phone' => $request->phone,
                'password' => $request->password,
                'c_password' => $request->c_password,
                'occupation' => $request->occupation,
                'gender' => $request->gender,
                'otp' => $request->otp,
                'country' => $request->country,
                'city' => $request->city,
                'country_id' => $request->country_id,
                'city_id' => $request->city_id
            ];

            $response = Http::withHeaders($header)->post($endpoint, $payload);
            $responseBody = $response->json();

            if (isset($responseBody['code']) && $responseBody['code'] === 'success') {
                Session::remove('set-page');
                return redirect()->route('agent.login');
            } else {
                return redirect()->back()->withErrors(translate('Something went wrong!'));
            }
        } catch (\Exception $error) {
            return back()->withErrors($error->getMessage());
        }
    }
    // Face Recognition
    public function faceRecognition(Request $request)
    {
        try {
            $ip = env('APP_MODE') == 'live' ? $request->ip() : '61.247.180.82';
            $current_user_info = Location::get($ip);
            return view('agent-views.auth.face-verification', compact('current_user_info'));
        } catch (\Exception $error) {
            return back()->withErrors($error->getMessage());
        }
    }
}
