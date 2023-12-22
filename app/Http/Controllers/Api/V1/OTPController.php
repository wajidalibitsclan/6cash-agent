<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\helpers;
use App\CentralLogics\SMS_module;
use App\CentralLogics\Twilio;
use App\Http\Controllers\Controller;
use App\Models\PhoneVerification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OTPController extends Controller
{
    public function __construct(
        private PhoneVerification $phone_verification,
        private Twilio $twilio
    ) {
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function check_otp(Request $request): JsonResponse
    {
        try {
            $otp = mt_rand(1000, 9999);
            if (env('APP_MODE') != LIVE) {
                $otp = '1234'; //hard coded
            }
            DB::table('phone_verifications')->updateOrInsert(['phone' => $request->user()->phone], [
                'otp' => $otp,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            // $response = SMS_module::send($request->user()->phone, $otp);

            $messageBody = 'Your 6Cash Agent OTP is' . $otp;
            $response = $this->twilio->sendOTP($request->user()->phone, $messageBody);
            return response()->json(['message' => 'success'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'failed'], 200);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function verify_otp(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required|min:4|max:4'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $verify = $this->phone_verification->where(['phone' => $request->user()->phone, 'otp' => $request['otp']])->first();

        if (isset($verify)) {
            $verify->delete();
            return response()->json([
                'message' => 'OTP verified!',
            ], 200);
        }

        return response()->json(['errors' => [
            ['code' => 'otp', 'message' => 'OTP is not found!']
        ]], 404);
    }
}
