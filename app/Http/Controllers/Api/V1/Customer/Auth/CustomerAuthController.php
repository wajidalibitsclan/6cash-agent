<?php

namespace App\Http\Controllers\Api\V1\Customer\Auth;

use App\CentralLogics\Helpers;
use App\CentralLogics\SMS_module;
use App\Http\Controllers\Controller;
use App\Http\Resources\RequestMoneyResource;
use App\Models\BusinessSetting;
use App\Models\LinkedWebsite;
use App\Models\PhoneVerification;
use App\Models\Purpose;
use App\Models\RequestMoney;
use App\Models\TransactionLimit;
use App\Models\User;
use App\Models\WithdrawRequest;
use Carbon\CarbonInterval;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CustomerAuthController extends Controller
{
    public function __construct(
        private User $user,
        private BusinessSetting $business_setting,
        private PhoneVerification $phone_verification,
        private LinkedWebsite $linked_website,
        private RequestMoney $request_money,
        private Purpose $purpose,
        public WithdrawRequest $withdraw_request
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

        $customer = $this->user->where(['phone' => $request['phone']])->first();

        if (isset($customer) && $customer->type == 2) {
            return response()->json([
                'message' => 'This phone is already taken',
                'user_type' => 'customer',
            ], 403);
        }

        if (isset($customer) && $customer->type != 2) {
            return response()->json([
                'message' => 'This phone is already register as agent',
                'user_type' => 'agent',
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
            return response()->json([
                'message' => 'OTP sent successfully',
                'otp' => 'active'
            ], 200);
        } catch (Exception $e) {
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
                        ['code' => 'otp', 'message' => translate('Too_many_attempts. please_try_again_after_') . CarbonInterval::seconds($time)->cascade()->forHumans()]
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
    public function logout(Request $request): JsonResponse
    {
        if (Auth::check()) {
            Auth::user()->AauthAcessToken()->delete();
            return response()->json(['message' => 'Logout successful'], 200);
        } else {
            return response()->json(['message' => 'Logout failed'], 403);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update_profile(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'f_name' => 'required',
            'l_name' => 'required',
            'gender' => 'required',
            'occupation' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $user = $this->user->find($request->user()->id);
        $user->f_name = $request->f_name;
        $user->l_name = $request->l_name;
        $user->email = $request->email;
        $user->image = $request->has('image') ? Helpers::update('customer/', $user->image, 'png', $request->image) : $user->image;
        $user->gender = $request->gender;
        $user->occupation = $request->occupation;
        $user->save();
        return response()->json(['message' => 'Profile successfully updated'], 200);
    }

    //PIN

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function verify_pin(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'pin' => 'required|min:4|max:4'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        if (Helpers::pin_check($request->user()->id, $request->pin)) {
            return response()->json(['message' => 'PIN is correct'], 200);
        } else {
            return response()->json(['message' => 'PIN is incorrect'], 403);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function change_pin(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'old_pin' => 'required|min:4|max:4',
            'new_pin' => 'required|min:4|max:4',
            'confirm_pin' => 'required|min:4|max:4',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        //PIN Check
        if (!Helpers::pin_check($request->user()->id, $request->old_pin)) {
            return response()->json(['message' => 'Old PIN is incorrect'], 401);
        }

        //PIN & Confirm PIN Match
        if ($request->new_pin != $request->confirm_pin) {
            return response()->json(['message' => 'PIN Mismatch'], 404);
        }

        //Change PIN
        try {
            $user = $this->user->find($request->user()->id);
            $user->password = bcrypt($request->confirm_pin);
            $user->save();
            return response()->json(['message' => 'PIN updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'PIN updated failed'], 401);
        }
    }

    /**
     * @param $phone
     * @param $otp
     * @return bool
     */
    public function verify_otp($phone, $otp): bool
    {
        $verify = $this->phone_verification->where(['phone' => $phone, 'otp' => $otp])->first();

        if (isset($verify)) {
            $verify->delete();
            return true;
        } else {
            return false;
        }
    }

    //fcm

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update_fcm_token(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $user = $this->user->find($request->user()->id);
        if (isset($user)) {
            $user->fcm_token = $request->token;
            $user->save();
            return response()->json(['message' => 'FCM token successfully updated'], 200);
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }


    //General

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function get_customer(Request $request)
    {
        try {
            $customer = $this->user->with('emoney')->customer()->find($request->user()->id);
            $pending_withdraw = $this->withdraw_request->where(['user_id' => $customer->id, 'request_status' => 'pending'])->count();

            $data = [];
            $data['name'] = $customer['f_name'] . ' ' . $customer['l_name'];
            $data['phone'] = $customer['phone'];
            $data['type'] = $customer['type'];
            $data['image'] = $customer['image'];
            $qr = Helpers::get_qrcode($data);

            $transaction_limit_data = TransactionLimit::where(['user_id' => $request->user()->id])->get();

            $types = [
                'add_money',
                'send_money',
                'cash_out',
                'send_money_request',
                'withdraw_request'
            ];

            $limits = [];

            foreach ($types as $type) {

                $typeData = $transaction_limit_data->where('type', $type)->first();

                $currentDay = now()->day;
                $currentMonth = now()->month;
                $currentYear = now()->year;

                if ($typeData) {
                    if ($currentDay !== $typeData['updated_at']->day || $currentMonth !== $typeData['updated_at']->month) {
                        $typeData['todays_count'] = 0;
                        $typeData['todays_amount'] = 0;
                    }

                    if ($currentMonth !== $typeData['updated_at']->month || $currentYear !== $typeData['updated_at']->year) {
                        $typeData['this_months_count'] = 0;
                        $typeData['this_months_amount'] = 0;
                    }

                    $limits["daily_{$type}_count"] = $typeData['todays_count'];
                    $limits["monthly_{$type}_count"] = $typeData['this_months_count'];
                    $limits["daily_{$type}_amount"] = $typeData['todays_amount'];
                    $limits["monthly_{$type}_amount"] = $typeData['this_months_amount'];

                    $typeData->save();
                } else {
                    $limits["daily_{$type}_count"] = 0;
                    $limits["monthly_{$type}_count"] = 0;
                    $limits["daily_{$type}_amount"] = 0;
                    $limits["monthly_{$type}_amount"] = 0;
                }
            }

            return response()->json(
                [
                    'f_name' => $customer->f_name,
                    'l_name' => $customer->l_name,
                    'phone' => $customer->phone,
                    'email' => $customer->email,
                    'image' => $customer->image,
                    'type' => $customer->type,
                    'gender' => $customer->gender,
                    'occupation' => $customer->occupation,
                    'two_factor' => (int)$customer->two_factor,
                    'fcm_token' => $customer->fcm_token,
                    'balance' => (float)$customer->emoney->current_balance,
                    'pending_balance' => (float)$customer->emoney->pending_balance,
                    'pending_withdraw_count' => $pending_withdraw,
                    'unique_id' => $customer->unique_id,
                    'qr_code' => strval($qr),
                    'is_kyc_verified' => (int)$customer->is_kyc_verified,
                    'transaction_limits' => $limits
                ],
                200
            );
        } catch (Exception $e) {
            return response()->json([], 200);
        }
    }

    /**
     * @param Request $request
     * @return array
     */
    public function get_requested_money(Request $request): array
    {
        $limit = $request->has('limit') ? $request->limit : 10;
        $offset = $request->has('offset') ? $request->offset : 1;

        $request_money = $this->request_money->where('to_user_id', $request->user()->id);

        $request_money->when(request('type') == 'pending', function ($q) {
            return $q->where('type', 'pending');
        });
        $request_money->when(request('type') == 'approved', function ($q) {
            return $q->where('type', 'approved');
        });
        $request_money->when(request('type') == 'denied', function ($q) {
            return $q->where('type', 'denied');
        });

        $request_money = RequestMoneyResource::collection($request_money->latest()->paginate($limit, ['*'], 'page', $offset));
        return [
            'total_size' => $request_money->total(),
            'limit' => $limit,
            'offset' => $offset,
            'requested_money' => $request_money->items()
        ];
    }

    /**
     * @param Request $request
     * @return array
     */
    public function get_own_requested_money(Request $request): array
    {
        $limit = $request->has('limit') ? $request->limit : 10;
        $offset = $request->has('offset') ? $request->offset : 1;

        $request_money = $this->request_money->where('from_user_id', $request->user()->id);

        $request_money->when(request('type') == 'pending', function ($q) {
            return $q->where('type', 'pending');
        });
        $request_money->when(request('type') == 'approved', function ($q) {
            return $q->where('type', 'approved');
        });
        $request_money->when(request('type') == 'denied', function ($q) {
            return $q->where('type', 'denied');
        });

        $request_money = RequestMoneyResource::collection($request_money->latest()->paginate($limit, ['*'], 'page', $offset));
        return [
            'total_size' => $request_money->total(),
            'limit' => $limit,
            'offset' => $offset,
            'requested_money' => $request_money->items()
        ];
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update_two_factor(Request $request): JsonResponse
    {
        try {
            $user = $this->user->find($request->user()->id);
            $user->two_factor = !$request->user()->two_factor;
            $user->save();
            return response()->json(['message' => 'Two factor updated'], 200);
        } catch (\Exception $e) {
            return response()->json(['errors' => 'failed'], 403);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function get_purpose(Request $request): mixed
    {
        $purposes = $this->purpose->select('title', 'logo', 'color')->get();
        return $purposes;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function linked_website(Request $request): mixed
    {
        $linked_websites = $this->linked_website->select('name', 'image', 'url')->active()->orderBy("id", "desc")->take(20)->get();
        return $linked_websites;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function remove_account(Request $request): JsonResponse
    {
        $customer = $this->user->find($request->user()->id);
        if (isset($customer)) {
            Helpers::file_remover('customer/', $customer->image);
            $customer->delete();
        } else {
            return response()->json(['status_code' => 404, 'message' => translate('Not found')], 200);
        }

        return response()->json(['status_code' => 200, 'message' => translate('Successfully deleted')], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update_kyc_information(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'identification_number' => 'required',
            'identification_type' => 'required|in:passport,driving_licence,nid,trade_license',
            'identification_image' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $identity_images = [];
        foreach ($request->identification_image as $image) {
            $identity_images[] = Helpers::file_uploader('user/identity/', 'png', $image);
        }

        $user = $this->user->find($request->user()->id);
        if ($user->is_kyc_verified == 1) {
            return response()->json(Helpers::response_formatter(DEFAULT_FAIL_200), 200);
        }
        $user->identification_number = $request->identification_number;
        $user->identification_type = $request->identification_type;
        $user->identification_image = $identity_images;
        $user->is_kyc_verified = 0;
        $user->save();

        return response()->json(Helpers::response_formatter(DEFAULT_UPDATE_200), 200);
    }
}
