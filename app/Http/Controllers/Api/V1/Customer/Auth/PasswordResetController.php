<?php

namespace App\Http\Controllers\Api\V1\Customer\Auth;

use App\CentralLogics\Helpers;
use App\CentralLogics\SMS_module;
use App\Http\Controllers\Admin\Exception;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\CarbonInterval;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use function bcrypt;
use function now;
use function response;

class PasswordResetController extends Controller
{
    public function __construct(
        private User $user
    ){}

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function reset_password_request(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $customer = $this->user->where(['phone' => $request->phone])->first();

        //type check
        if(isset($customer) && $customer->type != CUSTOMER_TYPE) {
            return response()->json(['errors' => [
                ['code' => 'forbidden', 'message' => 'Access forbidden!']
            ]], 403);
        }

        if (isset($customer)) {
            $otp_interval_time= Helpers::get_business_settings('otp_resend_time') ?? 60; // seconds
            $password_verification_data= DB::table('password_resets')->where('phone', $request['phone'])->first();

            if(isset($password_verification_data) && Carbon::parse($password_verification_data->created_at)->DiffInSeconds() < $otp_interval_time){
                $time = $otp_interval_time - Carbon::parse($password_verification_data->created_at)->DiffInSeconds();

                return response()->json(['errors' => [
                    ['code' => 'otp', 'message' => translate('please_try_again_after_') . $time . ' ' . translate('seconds')]
                ]], 403);
            }

            $otp = rand(1000, 9999);
            if(env('APP_MODE') != LIVE) {
                $otp = '1234'; //hard coded
            }

            DB::table('password_resets')->updateOrInsert(['phone' => $request->phone], [
                'token' => $otp,
                'otp_hit_count' => 0,
                'is_temp_blocked' => 0,
                'temp_block_time' => null,
                'created_at' => now(),
            ]);

            try {
                $response = SMS_module::send($customer['phone'], $otp);
                return response()->json([
                    'message' => 'OTP sent successfully.'
                ], 200);
            } catch (Exception $e) {
                return response()->json([
                    'message' => $response
                ], 200);
            }
        }
        return response()->json(['errors' => [
            ['code' => 'not-found', 'message' => 'Customer not found!']
        ]], 404);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function verify_token(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
            'otp' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $max_otp_hit = Helpers::get_business_settings('maximum_otp_hit') ?? 5;
        $max_otp_hit_time = Helpers::get_business_settings('otp_resend_time') ?? 60;    // seconds
        $temp_block_time = Helpers::get_business_settings('temporary_block_time') ?? 600;   // seconds

        $data = DB::table('password_resets')->where(['token' => $request['otp'], 'phone' => $request->phone])->first();

        if (isset($data)) {

            if(isset($data->temp_block_time ) && Carbon::parse($data->temp_block_time)->DiffInSeconds() <= $temp_block_time){
                $time = $temp_block_time - Carbon::parse($data->temp_block_time)->DiffInSeconds();

                return response()->json(['errors' => [
                    ['code' => 'otp_block_time', 'message' => translate('please_try_again_after_') . $time . ' ' . translate('seconds')]
                ]], 403);
            }

            return response()->json(['message' => "OTP found, you can proceed"], 200);
        }
        else{
            $verification_data=  DB::table('password_resets')->where(['phone' => $request->phone])->first();

            if(isset($verification_data)){
                $time = $temp_block_time - Carbon::parse($verification_data->temp_block_time)->DiffInSeconds();

                if(isset($verification_data->temp_block_time ) && Carbon::parse($verification_data->temp_block_time)->DiffInSeconds() <= $temp_block_time){
                    $time= $temp_block_time - Carbon::parse($verification_data->temp_block_time)->DiffInSeconds();

                    return response()->json(['errors' => [
                        ['code' => 'otp_block_time', 'message' => translate('please_try_again_after_') . $time . ' ' . translate('seconds')]
                    ]], 403);
                }

                if($verification_data->is_temp_blocked == 1 && Carbon::parse($verification_data->created_at)->DiffInSeconds() >= $temp_block_time){
                    DB::table('password_resets')->updateOrInsert(['phone' => $request['phone']],
                        [
                            'otp_hit_count' => 0,
                            'is_temp_blocked' => 0,
                            'temp_block_time' => null,
                            'created_at' => now(),
                        ]);
                }

                if($verification_data->otp_hit_count >= $max_otp_hit &&  Carbon::parse($verification_data->created_at)->DiffInSeconds() < $max_otp_hit_time &&  $verification_data->is_temp_blocked == 0){

                    DB::table('password_resets')->updateOrInsert(['phone' => $request['phone']],
                        [
                            'is_temp_blocked' => 1,
                            'temp_block_time' => now(),
                            'created_at' => now(),
                        ]);

                    return response()->json(['errors' => [
                        ['code' => 'otp_block_time', 'message' => translate('Too_many_attempts. Please_try_again_after_'). CarbonInterval::seconds($time)->cascade()->forHumans()]
                    ]], 403);
                }
            }

            DB::table('password_resets')->updateOrInsert(['phone' => $request['phone']],
                [
                    'otp_hit_count' => DB::raw('otp_hit_count + 1'),
                    'created_at' => now(),
                    'temp_block_time' => null,
                ]);
        }

        return response()->json(['errors' => [
            ['code' => 'invalid', 'message' => 'Invalid OTP.']
        ]], 400);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function reset_password_submit(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
            'otp' => 'required',
            'password' => 'required',
            'confirm_password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $data = DB::table('password_resets')->where(['phone' => $request->phone])
            ->where(['token' => $request['otp']])->first();

        if (isset($data)) {

            if ($request['password'] == $request['confirm_password']) {
                $customer = $this->user->where(['phone' => $request->phone])->first();
                $customer->password = bcrypt($request['confirm_password']);
                $customer->save();

                DB::table('password_resets')
                    ->where(['phone' => $request->phone])
                    ->where(['token' => $request['otp']])->delete();

                return response()->json(['message' => 'Password changed successfully.'], 200);
            }
            return response()->json(['errors' => [
                ['code' => 'mismatch', 'message' => "Password didn't match!"]
            ]], 401);
        }
        return response()->json(['errors' => [
            ['code' => 'invalid', 'message' => 'Invalid OTP.']
        ]], 400);
    }
}
