<?php

namespace App\Http\Controllers\Agent\Auth;

use App\Models\User;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use App\CentralLogics\helpers;
use Illuminate\Support\Carbon;
use Gregwar\Captcha\PhraseBuilder;
use Illuminate\Support\Facades\DB;
use Gregwar\Captcha\CaptchaBuilder;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Stevebauman\Location\Facades\Location;

class LoginController extends Controller
{
    public $basePath;
    public function __construct()
    {
        $this->basePath = env('APP_URL');
    }
    // CAPTA Handling
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
    // Login Page
    public function login(Request $request)
    {
        try {
            $ip = env('APP_MODE') == 'live' ? $request->ip() : '61.247.180.82';
            $current_user_info = Location::get($ip);
            return view('agent-views.auth.login', compact('current_user_info'));
        } catch (\Exception $error) {
            return back()->withErrors($error->getMessage());
        }
    }
    // Login Submit 
    public function submit(Request $request)
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

            $validator = Validator::make($request->all(), [
                'country_code' => 'required|string',
                'phone' => 'required|string',
                'password' => 'required|string',
            ]);
            if ($validator->fails()) {
                Toastr::error($validator->getMessageBag());
                return back();
            }
            $phone = $request->country_code . $request->phone;
            $user = User::where(['phone' => $phone, 'type' => AGENT_TYPE])->first();
            //availability check
            if (!isset($user)) {
                Toastr::error(translate('User not found!'));
                return back();
            }

            //status active check
            if (isset($user->is_active) && $user->is_active == false) {
                Toastr::error(translate('You have been blocked'));
                return back();
            }
            $temp_block_time = Helpers::get_business_settings('temporary_login_block_time') ?? 600; // seconds

            //if temporarily blocked
            if ($user->is_temp_blocked) {
                //if 'temporary block period' has not expired
                if (isset($user->temp_block_time) && Carbon::parse($user->temp_block_time)->DiffInSeconds() <= $temp_block_time) {
                    $time = $temp_block_time - Carbon::parse($user->temp_block_time)->DiffInSeconds();
                    Toastr::error(translate('Your account is temporarily blocked. Please_try_again_after_') . CarbonInterval::seconds($time)->cascade()->forHumans());
                    return back();
                }

                //reset
                $user->login_hit_count = 0;
                $user->is_temp_blocked = 0;
                $user->temp_block_time = null;
                $user->save();
            }

            //password check
            if (!Hash::check($request['password'], $user['password'])) {
                self::update_user_hit_count($user);
                Toastr::error(translate('Invalid Credentials'));
                return back();
            }

            //req within blocking
            if (isset($user->temp_block_time) && Carbon::parse($user->temp_block_time)->DiffInSeconds() <= $temp_block_time) {
                $time = $temp_block_time - Carbon::parse($user->temp_block_time)->DiffInSeconds();
                Toastr::error(translate('Try_again_after') . ' ' . CarbonInterval::seconds($time)->cascade()->forHumans());
                return back();
            }

            //if everything is okay
            $user->update(['last_active_at' => now()]);
            Auth::login($user);
            return redirect()->route('agent.dashboard');
        } catch (\Exception $error) {
            return back()->withErrors($error->getMessage());
        }
    }
    // Validation Errors Handling
    public function validationErrors($errors)
    {
        $errorsString = '';
        foreach ($errors as $error) {
            if (isset($error['dial_country_code'])) {
                $errorsString += translate('Dial Country code is required');
            }
            if (isset($error['phone'])) {
                $errorsString += translate('Phone number is not valid');
            }
            if (isset($error['password'])) {
                $errorsString += translate('Password is not valid, must have 4 characters, and number');
            }
        }
        return $errorsString;
    }

    public function update_user_hit_count($user): void
    {
        $max_login_hit = Helpers::get_business_settings('maximum_login_hit') ?? 5;

        $user->login_hit_count += 1;
        if ($user->login_hit_count >= $max_login_hit) {
            $user->is_temp_blocked = 1;
            $user->temp_block_time = now();
        }
        $user->save();
    }




    // Logout action
    public function logout()
    {
        Session::forget('token');
        Auth::logout();
        return redirect()->route('agent.login');
    }
}
