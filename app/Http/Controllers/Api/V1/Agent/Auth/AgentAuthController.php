<?php

namespace App\Http\Controllers\Api\V1\Agent\Auth;

use App\CentralLogics\helpers;
use App\CentralLogics\SMS_module;
use App\CentralLogics\Twilio;
use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use App\Models\PhoneVerification;
use App\Models\User;
use Carbon\CarbonInterval;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AgentAuthController extends Controller
{
    public function __construct(
        private User $user,
        private BusinessSetting $business_setting,
        private PhoneVerification $phone_verification,
        private Twilio $twilio
    ) {
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function check_phone(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|min:5|max:20'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $agent = $this->user->where(['phone' => $request['phone']])->first();

        if (isset($agent) && $agent->type == 1) {
            return response()->json([
                'message' => 'This phone is already taken',
                'user_type' => 'agent',
            ], 403);
        }

        if (isset($agent) && $agent->type != 1) {
            return response()->json([
                'message' => 'This phone is already register as customer',
                'user_type' => 'customer',
            ], 403);
        }

        if ($this->business_setting->where(['key' => 'phone_verification'])->first()->value) {

            $otp_interval_time = Helpers::get_business_settings('otp_resend_time') ?? 60; // seconds
            $otp_verification_data = DB::table('phone_verifications')->where('phone', $request['phone'])->first();

            if (isset($otp_verification_data) &&  Carbon::parse($otp_verification_data->created_at)->DiffInSeconds() < $otp_interval_time) {
                $time = $otp_interval_time - Carbon::parse($otp_verification_data->created_at)->DiffInSeconds();

                return response()->json([
                    'code' => 'otp',
                    'message' => translate('please_try_again_after_') . $time . ' ' . translate('seconds')
                ], 200);
            }

            $otp = mt_rand(1000, 9999);
            if (env('APP_MODE') != LIVE) {
                $otp = '1234'; //hard coded
            }

            DB::table('phone_verifications')->updateOrInsert(['phone' => $request['phone']], [
                'otp' => $otp,
                'otp_hit_count' => 0,
                'is_temp_blocked' => 0,
                'temp_block_time' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $response = SMS_module::send($request['phone'], $otp);

            // $messsageBody = 'Your 6Cash Agent OTP code is ' . $otp;
            // $response = $this->twilio->sendOTP($request['phone'], $messsageBody);

            return response()->json([
                'message' => 'Number is ready to register',
                'otp' => 'active'
            ], 200);
        } else {
            return response()->json([
                'message' => 'OTP sent failed',
                'otp' => 'inactive'
            ], 200);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function verify_phone(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
            'otp' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $max_otp_hit = Helpers::get_business_settings('maximum_otp_hit') ?? 5;
        $max_otp_hit_time = Helpers::get_business_settings('otp_resend_time') ?? 60; // seconds
        $temp_block_time = Helpers::get_business_settings('temporary_block_time') ?? 600; // seconds


        $verify = $this->phone_verification->where(['phone' => $request['phone'], 'otp' => $request['otp']])->first();

        if (isset($verify)) {

            if (isset($verify->temp_block_time) && Carbon::parse($verify->temp_block_time)->DiffInSeconds() <= $temp_block_time) {
                $time = $temp_block_time - Carbon::parse($verify->temp_block_time)->DiffInSeconds();

                return response()->json(['errors' => [
                    ['code' => 'otp', 'message' => translate('please_try_again_after_') . CarbonInterval::seconds($time)->cascade()->forHumans()]
                ]], 404);
            }

            return response()->json([
                'message' => 'OTP verified!',
                'code' => 'success'
            ], 200);
        } else {
            $verification_data = $this->phone_verification->where('phone', $request['phone'])->first();

            if (isset($verification_data)) {

                if (isset($verification_data->temp_block_time) && Carbon::parse($verification_data->temp_block_time)->DiffInSeconds() <= $temp_block_time) {
                    $time = $temp_block_time - Carbon::parse($verification_data->temp_block_time)->DiffInSeconds();

                    return response()->json(['errors' => [
                        ['code' => 'otp', 'message' => translate('please_try_again_after_') . CarbonInterval::seconds($time)->cascade()->forHumans()]
                    ]], 404);
                }

                if ($verification_data->is_temp_blocked == 1 && Carbon::parse($verification_data->updated_at)->DiffInSeconds() >= $temp_block_time) {
                    DB::table('phone_verifications')->updateOrInsert(
                        ['phone' => $request['phone']],
                        [
                            'otp_hit_count' => 0,
                            'is_temp_blocked' => 0,
                            'temp_block_time' => null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }

                if ($verification_data->otp_hit_count >= $max_otp_hit &&  Carbon::parse($verification_data->updated_at)->DiffInSeconds() < $max_otp_hit_time &&  $verification_data->is_temp_blocked == 0) {

                    DB::table('phone_verifications')->updateOrInsert(
                        ['phone' => $request['phone']],
                        [
                            'is_temp_blocked' => 1,
                            'temp_block_time' => now(),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );

                    $time = $temp_block_time - Carbon::parse($verification_data->temp_block_time)->DiffInSeconds();

                    return response()->json(['errors' => [
                        ['code' => 'otp', 'message' => translate('Too_many_attempts. Please_try_again_after_') . CarbonInterval::seconds($time)->cascade()->forHumans()]
                    ]], 404);
                }
            }
            DB::table('phone_verifications')->updateOrInsert(
                ['phone' => $request['phone']],
                [
                    'otp_hit_count' => DB::raw('otp_hit_count + 1'),
                    'updated_at' => now(),
                    'temp_block_time' => null,
                ]
            );
        }


        return response()->json(['errors' => [
            ['code' => 'otp', 'message' => 'OTP is not matched!']
        ]], 404);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function resend_otp(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|min:5|max:20|unique:users'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $phone = $request['phone'];
        try {
            $otp = mt_rand(1000, 9999);
            if (env('APP_MODE') != LIVE) {
                $otp = '1234'; //hard coded
            }

            DB::table('phone_verifications')->updateOrInsert(['phone' => $phone], [
                'otp' => $otp,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $response = SMS_module::send($phone, $otp);

            // $messsageBody = 'Your 6Cash Agent OTP code is ' . $otp;
            // $response = $this->twilio->sendOTP($phone, $messsageBody);

            return response()->json([
                'message' => 'OTP sent successfully',
                'otp' => 'active'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'OTP sent failed',
                'otp' => 'inactive'
            ], 200);
        }
    }
}
