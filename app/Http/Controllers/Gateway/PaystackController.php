<?php

namespace App\Http\Controllers\Gateway;

use App\CentralLogics\Helpers;
use App\Exceptions\TransactionFailedException;
use App\Http\Controllers\Controller;
use App\Models\EMoney;
use App\Traits\TransactionTrait;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Unicodeveloper\Paystack\Facades\Paystack;

class PaystackController extends Controller
{
    use TransactionTrait;

    public function redirectToGateway(Request $request)
    {
        try {
            //

            return Paystack::getAuthorizationUrl()->redirectNow();
        } catch (\Exception $e) {
            Toastr::error('Your currency is not supported by Paystack.');
            return Redirect::back()->withErrors(['error' => 'Failed']);
        }
    }

    public function handleGatewayCallback()
    {
        $paymentDetails = Paystack::getPaymentData();

        $user_id = session('user_id');
        $amount = session('amount');

        if ($paymentDetails['status'] == true) {
            /** ADD Money Transaction */
            $transaction_id = $this->add_money_transaction(Helpers::get_admin_id(), $user_id, $amount);

            //if failed
            if (is_null($transaction_id)) {
                Helpers::add_fund($user_id, $amount, 'paystack', null, 'failed');
                return \redirect()->route('payment-fail');
            }

            //if success
            Helpers::add_fund($user_id, $amount, 'paystack', null, 'success');

            /** Update Transaction limits data  */
            Helpers::add_money_transaction_limit_update($user_id, $amount);

            return \redirect()->route('payment-success');

        } else {
            //fund record for failed
            Helpers::add_fund($user_id, $amount, 'paystack', null, 'failed');
            return \redirect()->route('payment-fail');
        }

    }
}
