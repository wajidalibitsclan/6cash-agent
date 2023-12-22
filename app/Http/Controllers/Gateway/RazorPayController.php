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
use Razorpay\Api\Api;

class RazorPayController extends Controller
{
    use TransactionTrait;

    public function payWithRazorpay()
    {
        return view('razor-pay');
    }

    public function payment(Request $request)
    {
        //Input items of form
        $input = $request->all();
        //get API Configuration
        $api = new Api(config('razor.razor_key'), config('razor.razor_secret'));
        //Fetch payment information by razorpay_payment_id
        $payment = $api->payment->fetch($input['razorpay_payment_id']);

        $user_id = $request->user_id;
        $amount = $request->amount;

        if(count($input)  && !empty($input['razorpay_payment_id'])) {
            try {
                $response = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount' => $payment['amount']));

            } catch (\Exception $e) {
                //error
            }
        }


        /** ADD Money Transaction */
        $transaction_id = $this->add_money_transaction(Helpers::get_admin_id(), $user_id, $amount);

        //if failed
        if (is_null($transaction_id)) {
            Helpers::add_fund($user_id, $amount, 'razor-pay', null, 'failed');
            return \redirect()->route('payment-fail');
        }

        //if success
        Helpers::add_fund($user_id, $amount, 'razor-pay', null, 'success');

        /** Update Transaction limits data  */
        Helpers::add_money_transaction_limit_update($user_id, $amount);

        return \redirect()->route('payment-success');
    }

}
