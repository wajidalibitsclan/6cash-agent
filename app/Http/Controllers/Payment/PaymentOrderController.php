<?php

namespace App\Http\Controllers\Payment;

use App\CentralLogics\helpers;
use App\CentralLogics\SMS_module;
use App\Http\Controllers\Controller;
use App\Models\EMoney;
use App\Models\PaymentRecord;
use App\Models\PhoneVerification;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Stevebauman\Location\Facades\Location;

class PaymentOrderController extends Controller
{
    public function __construct(
        private EMoney $e_money,
        private PaymentRecord $payment_record,
        private PhoneVerification $phone_verification,
        private User $user
    ) {
    }

    /**
     * @param Request $request
     * @return Application|Factory|View|RedirectResponse
     */
    public function payment_process(Request $request): View|Factory|RedirectResponse|Application
    {
        $ip = env('APP_MODE') == 'live' ? $request->ip() : '61.247.180.82';
        $current_user_info = Location::get($ip);

        $payment_id = $request->payment_id;
        $payment_record = $this->payment_record->where(['id' => $payment_id])->first();
        if (isset($payment_record) && $payment_record->expired_at > Carbon::now()) {
            $merchant_user = $this->user->with('merchant')
                ->where(['id' => $payment_record->merchant_user_id])
                ->first();
            return view('payment.phone', compact('payment_id', 'merchant_user', 'current_user_info', 'payment_record'));
        }
        Toastr::warning(translate('Payment time expired'));
        return back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function send_otp(Request $request): RedirectResponse
    {
        $request->validate([
            'dial_country_code' => 'required|string',
            'phone' => 'required|min:8|max:20',
        ], [
            'phone.required' => translate('Phone is required'),
            'dial_country_code.required' => translate('Country code is required'),
        ]);

        $phone = $request->dial_country_code . $request->phone;
        $payment_id = $request->payment_id;
        $otp_status = Helpers::get_business_settings('payment_otp_verification');

        if (isset($otp_status) && $otp_status == 1) {
            $otp = mt_rand(1000, 9999);
            if (env('APP_MODE') != LIVE) {
                $otp = '1234'; //hard coded
            }

            $user = $this->user->where(['phone' => $phone, 'type' => CUSTOMER_TYPE])->first();

            if (isset($user)) {

                if ($user->is_kyc_verified != 1) {
                    Toastr::warning(translate('User is not verified, please complete your account verification'));
                    return back();
                }

                session()->put('user_phone', $user->phone);

                DB::table('phone_verifications')->updateOrInsert(['phone' => $phone], [
                    'otp' => $otp,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $response = SMS_module::send($request['phone'], $otp);

                Toastr::success(translate('OTP send !'));
                return redirect()->route('otp', compact('payment_id'));
            }
            Toastr::warning(translate('please enter a valid user phone number'));
            return back();
        }
        return redirect()->route('pin', compact('payment_id'));
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function otp_index(Request $request): View|Factory|Application
    {
        $payment_id = $request->payment_id;
        $payment_record = $this->payment_record->where(['id' => $payment_id])->first();
        $frontend_callback = $payment_record->callback;
        return view('payment.otp', compact('payment_id', 'frontend_callback'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function verify_otp(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => 'required|min:4|max:4',
        ], [
            'otp.required' => translate('OTP is required'),
            'otp.min' => translate('OTP must be 4 digit'),
            'otp.max' => translate('OTP must be 4 digit'),
        ]);

        $payment_id = $request->payment_id;
        $verify = $this->phone_verification->where(['phone' => session('user_phone'), 'otp' => $request['otp']])->first();

        if (isset($verify)) {
            $verify->delete();
            Toastr::success(translate('OTP verify success !'));
            return redirect()->route('pin', compact('payment_id'));
        }

        Toastr::warning(translate('OTP verify failed !'));
        return back();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function resend_otp(Request $request): JsonResponse
    {
        $phone = session('user_phone');

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

            return response()->json(['message' => 'OTP Send'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'OTP Send failed'], 404);
        }
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function pin_index(Request $request): Factory|View|Application
    {
        $payment_id = $request->payment_id;
        $payment_record = $this->payment_record->where(['id' => $payment_id])->first();
        $frontend_callback = $payment_record->callback;
        return view('payment.pin', compact('payment_id', 'frontend_callback'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function verify_pin(Request $request): RedirectResponse
    {
        $request->validate([
            'pin' => 'required|min:4|max:4',
        ], [
            'pin.required' => translate('Pin is required'),
            'pin.min' => translate('Pin must be 4 digit'),
            'pin.max' => translate('Pin must be 4 digit'),
        ]);

        $payment_id = $request->payment_id;
        $user = $this->user->where(['phone' => session('user_phone'), 'type' => CUSTOMER_TYPE])->first();

        if (!isset($user)) {
            Toastr::warning(translate('user not found !'));
            return back();
        }

        if (!Hash::check($request->pin, $user->password)) {
            Toastr::warning(translate('pin mismatched !'));
            return back();
        }

        $payment_record = $this->payment_record->where(['id' => $payment_id, 'transaction_id' => null, 'is_paid' => 0])->first();

        if (isset($payment_record) && $payment_record->expired_at > Carbon::now()) {
            $amount = $payment_record->amount;
            $merchant_user = $this->user->where('id', $payment_record->merchant_user_id)->first();
            $admin_user = $this->user->where('type', 0)->first();
            $user_emoney = $this->e_money->where('user_id', $user->id)->first();
            $merchant_emoney = $this->e_money->where('user_id', $payment_record->merchant_user_id)->first();
            $admin_emoney = $this->e_money->where('user_id', $admin_user->id)->first();

            if ($user_emoney->current_balance < $payment_record->amount) {
                Toastr::warning(translate('You do not have enough balance. Please generate eMoney first.'));
                return back();
            }

            $transaction_id = payment_transaction($user, $merchant_user, $user_emoney, $merchant_emoney, $amount, $admin_user, $admin_emoney);
            session()->put('transaction_id', $transaction_id);

            if ($transaction_id != null) {
                $payment_record->user_id = $user->id;
                $payment_record->transaction_id = $transaction_id;
                $payment_record->is_paid = 1;
                $payment_record->save();

                Toastr::success(translate('Payment successful !'));
                return redirect()->route('success', ['payment_id' => $request['payment_id']]);
            }
        }
        Toastr::warning(translate('Payment failed !'));
        return back();
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function success_index(Request $request): Factory|View|Application
    {
        $payment_id = $request->payment_id;
        return view('payment.success', compact('payment_id'));
    }

    /**
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function payment_success_callback(Request $request): Redirector|Application|RedirectResponse
    {
        $transaction_id = session('transaction_id');
        $payment_record = $this->payment_record->where(['id' => $request->payment_id])->first();

        $callback = $payment_record['callback']; //db callback
        $url = $callback . '?transaction_id=' . $transaction_id;

        return redirect($url);
    }


    public function success_payment()
    {
        return view('payment.success-payment');
    }

    public function failed_payment()
    {
        return view('payment.failed-payment');
    }
}
