<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\helpers;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserLogHistory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function __construct(
        private User $user,
        private UserLogHistory $user_log_history
    ) {
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function customer_login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'dial_country_code' => 'required|string',
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, Helpers::error_processor($validator)), 400);
        }

        $phone = $request->dial_country_code . $request->phone;
        $user = $this->user->customer()->where('phone', $phone)->first();

        //availability check
        if (!isset($user)) {
            return response()->json(response_formatter(AUTH_LOGIN_404, null, Helpers::error_processor($validator)), 404);
        }

        //status active check
        if (isset($user->is_active) && $user->is_active == false) {
            return response()->json(response_formatter(AUTH_BLOCK_LOGIN_403, null, Helpers::error_processor($validator)), 403);
        }

        $temp_block_time = Helpers::get_business_settings('temporary_login_block_time') ?? 600; // seconds

        //if temporarily blocked
        if ($user->is_temp_blocked) {
            //if 'temporary block period' has not expired
            if (isset($user->temp_block_time) && Carbon::parse($user->temp_block_time)->DiffInSeconds() <= $temp_block_time) {
                $time = $temp_block_time - Carbon::parse($user->temp_block_time)->DiffInSeconds();
                $response = [
                    "response_code" => "auth_login_401",
                    "message" => translate('Your account is temporarily blocked. Please_try_again_after_') . CarbonInterval::seconds($time)->cascade()->forHumans(),
                ];
                return response()->json(response_formatter($response, null, null), 403);
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
            return response()->json(response_formatter(AUTH_LOGIN_401, null, Helpers::error_processor($validator)), 401);
        }

        //req within blocking
        if (isset($user->temp_block_time) && Carbon::parse($user->temp_block_time)->DiffInSeconds() <= $temp_block_time) {
            $time = $temp_block_time - Carbon::parse($user->temp_block_time)->DiffInSeconds();

            $response = [
                "response_code" => "auth_login_401",
                "message" => translate('Try_again_after') . ' ' . CarbonInterval::seconds($time)->cascade()->forHumans()
            ];
            return response()->json(response_formatter($response, null, null), 403);
        }

        //log user history
        $log_status = self::log_user_history($request, $user->id);
        if (!$log_status) {
            return response()->json(response_formatter(AUTH_LOGIN_400, null, Helpers::error_processor($validator)), 400);
        }

        //if everything is okay
        $user->update(['last_active_at' => now()]);
        $user->AauthAcessToken()->delete();
        $token = $user->createToken('CustomerAuthToken')->accessToken;
        return response()->json(response_formatter(AUTH_LOGIN_200, $token, null), 200);
    }

    /**
     * @param $user
     * @return void
     */
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

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function agent_login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'dial_country_code' => 'required|string',
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);


        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, Helpers::error_processor($validator)), 400);
        }
        $phone = $request->dial_country_code . $request->phone;
        $user = User::where(['phone' => $phone, 'type' => AGENT_TYPE])->first();
        //availability check
        if (!isset($user)) {
            return response()->json(response_formatter(AUTH_LOGIN_404, null, Helpers::error_processor($validator)), 404);
        }

        //status active check
        if (isset($user->is_active) && $user->is_active == false) {
            return response()->json(response_formatter(AUTH_BLOCK_LOGIN_403, null, Helpers::error_processor($validator)), 403);
        }
        $temp_block_time = Helpers::get_business_settings('temporary_login_block_time') ?? 600; // seconds

        //if temporarily blocked
        if ($user->is_temp_blocked) {
            //if 'temporary block period' has not expired
            if (isset($user->temp_block_time) && Carbon::parse($user->temp_block_time)->DiffInSeconds() <= $temp_block_time) {
                $time = $temp_block_time - Carbon::parse($user->temp_block_time)->DiffInSeconds();
                $response = [
                    "response_code" => "auth_login_401",
                    "message" => translate('Your account is temporarily blocked. Please_try_again_after_') . CarbonInterval::seconds($time)->cascade()->forHumans(),
                ];
                return response()->json(response_formatter($response, null, null), 403);
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
            return response()->json(response_formatter(AUTH_LOGIN_401, null, Helpers::error_processor($validator)), 401);
        }

        //req within blocking
        if (isset($user->temp_block_time) && Carbon::parse($user->temp_block_time)->DiffInSeconds() <= $temp_block_time) {
            $time = $temp_block_time - Carbon::parse($user->temp_block_time)->DiffInSeconds();

            $response = [
                "response_code" => "auth_login_401",
                "message" => translate('Try_again_after') . ' ' . CarbonInterval::seconds($time)->cascade()->forHumans()
            ];
            return response()->json(response_formatter($response, null, null), 403);
        }

        //log user history

        if (true) {
        } else {
            $log_status = self::log_user_history($request, $user->id);

            if (!$log_status) {
                return response()->json(response_formatter(AUTH_LOGIN_400, null, Helpers::error_processor($validator)), 400);
            }
        }

        //if everything is okay
        $user->update(['last_active_at' => now()]);
        $user->AauthAcessToken()->delete();
        $token = $user->createToken('AgentAuthToken')->accessToken;
        return response()->json(response_formatter(AUTH_LOGIN_200, $token, null), 200);
    }

    /**
     * @param $request
     * @param $user_id
     * @return bool
     */
    public function log_user_history($request, $user_id): bool
    {
        $ip_address = $request->ip();
        $device_id = $request->header('device-id');
        $browser = $request->header('browser');
        $os = $request->header('os');
        $device_model = $request->header('device-model');



        if ($device_id == '' || $os == '' || $device_model == '') {
            return false;
        }

        //History will be logged here
        DB::beginTransaction();
        try {
            $this->user_log_history->where('user_id', $user_id)->update(['is_active' => 0]);

            $this->user_log_history->create(
                [
                    'ip_address' => $ip_address,
                    'device_id' => $device_id,
                    'browser' => $browser,
                    'os' => $os,
                    'device_model' => $device_model,
                    'user_id' => $user_id,
                ]
            );
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return false;
        }

        return true;
    }

    public function test()
    {
        return response()->json([
            'status' => 'done'
        ]);
    }
}
