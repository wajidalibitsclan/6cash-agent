<?php

namespace App\Http\Controllers\Gateway;

use App\CentralLogics\Helpers;
use App\Exceptions\TransactionFailedException;
use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use App\Models\EMoney;
use App\Models\User;
use App\Traits\TransactionTrait;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use KingFlamez\Rave\Facades\Rave as Flutterwave;

class FlutterwaveController extends Controller
{
    use TransactionTrait;

    public function initialize()
    {
        //This generates a payment reference
        $reference = Flutterwave::generateReference();

        $user_data = User::find(session('user_id'));
        // Enter the details of the payment
        $data = [
            'payment_options' => 'card,banktransfer',
            'amount' => session('amount'), //hard coded
            'email' => $user_data['email'] ?? EXAMPLE_MAIL,
            'tx_ref' => $reference,
            'currency' => Helpers::currency_code(),
            'redirect_url' => route('flutterwave_callback'),
            'customer' => [
                'email' => $user_data['email'] ?? EXAMPLE_MAIL,
                "phone_number" => $user_data['phone'],
                "name" => $user_data['f_name']??'' . ' ' . $user_data['l_name']??'',
            ],

            "customizations" => [
                "title" => BusinessSetting::where(['key'=>'business_name'])->first()->value??'6CASH',
                "description" => null,
            ]
        ];

        $payment = Flutterwave::initializePayment($data);

        if ($payment['status'] !== 'success') {
            //return to callback


            //payment-fail if no callback

            return \redirect()->route('payment-fail');
        }
        return redirect($payment['data']['link']);

    }

    public function callback()
    {
        $status = request()->status;

        $user_id = session('user_id');
        $amount = session('amount');

        //if payment is successful
        if ($status == 'successful') {
            /** ADD Money Transaction */
            $transaction_id = $this->add_money_transaction(Helpers::get_admin_id(), $user_id, $amount);

            //if failed
            if (is_null($transaction_id)) {
                Helpers::add_fund($user_id, $amount, 'flutterwave', null, 'failed');
                return \redirect()->route('payment-fail');
            }

            //if success
            Helpers::add_fund($user_id, $amount, 'flutterwave', null, 'success');

            /** Update Transaction limits data  */
            Helpers::add_money_transaction_limit_update($user_id, $amount);

            return \redirect()->route('payment-success');

        } elseif ($status ==  'cancelled'){
             //Put desired action/code after transaction has been cancelled here
            Helpers::add_fund($user_id, $amount, 'flutterwave', null, 'cancel');
            return \redirect()->route('payment-fail');

         } else{
            //fund record for failed
            Helpers::add_fund($user_id, $amount, 'flutterwave', null, 'failed');
            return \redirect()->route('payment-fail');
        }

        // Get the transaction from your DB using the transaction reference (txref)
        // Check if you have previously given value for the transaction. If you have, redirect to your successpage else, continue
        // Confirm that the currency on your db transaction is equal to the returned currency
        // Confirm that the db transaction amount is equal to the returned amount
        // Update the db transaction record (including parameters that didn't exist before the transaction is completed. for audit purpose)
        // Give value for the transaction
        // Update the transaction to note that you have given value for the transaction
        // You can also redirect to your success page from here

    }
}
