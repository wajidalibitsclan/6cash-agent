<?php

namespace App\Http\Controllers\Gateway;

use App\CentralLogics\Helpers;
use App\Exceptions\TransactionFailedException;
use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use App\Models\EMoney;
use App\Traits\TransactionTrait;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Stripe\Stripe;

class StripePaymentController extends Controller
{
    use TransactionTrait;

    public function payment_process_3d()
    {
        $tran = Str::random(6) . '-' . rand(1, 1000);
        session()->put('transaction_ref', $tran);

        $config = Helpers::get_business_settings('stripe');
        Stripe::setApiKey($config['api_key']);
        header('Content-Type: application/json');
        $currency_code = Helpers::get_business_settings('currency');

        $YOUR_DOMAIN = url('/');

        $currencies_not_supported_cents = ['BIF', 'CLP', 'DJF', 'GNF', 'JPY', 'KMF', 'KRW', 'MGA', 'PYG', 'RWF', 'UGX', 'VND', 'VUV', 'XAF', 'XOF', 'XPF'];
        $amount = session('amount');
        $checkout_session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => $currency_code ?? 'usd',
                    'unit_amount' => in_array($currency_code, $currencies_not_supported_cents) ? (int)$amount : ($amount * 100),
                    'product_data' => [
                        'name' => Helpers::get_business_settings('business_name') ?? 'No Title',
                        'images' => [asset('storage/app/public/system') . '/' . Helpers::get_business_settings('logo') ?? '']
                    ],
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/pay-stripe/success',
            'cancel_url' => url()->previous(),
        ]);

        // dd($checkout_session);
        return response()->json(['id' => $checkout_session->id]);
    }

    public function success()
    {
        $user_id = session('user_id');
        $amount = session('amount');

        /** ADD Money Transaction */
        $transaction_id = $this->add_money_transaction(Helpers::get_admin_id(), $user_id, $amount);

        //if failed
        if (is_null($transaction_id)) {
            Helpers::add_fund($user_id, $amount, 'stripe', null, 'failed');
            return \redirect()->route('payment-fail');
        }

        //if success
        Helpers::add_fund($user_id, $amount, 'stripe', null, 'success');
        /** Update Transaction limits data  */
        Helpers::add_money_transaction_limit_update($user_id, $amount);

        return \redirect()->route('payment-success');
    }

    public function fail()
    {
        Helpers::add_fund(session('user_id'), session('amount'), 'stripe', null, 'failed');
        return \redirect()->route('payment-fail');
    }
}
