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
use MercadoPago\Payer;
use MercadoPago\Payment;
use MercadoPago\SDK;

class MercadoPagoController extends Controller
{
    use TransactionTrait;

    private $data;

    public function __construct()
    {
        $this->data = Helpers::get_business_settings('mercadopago');
    }
    public function index(Request $request)
    {
        $data = $this->data;

        $amount = session('amount');
        $ref_id = null;

        return view('payment-view-marcedo-pogo', compact('data', 'amount', 'ref_id'));
    }
    public function make_payment(Request $request)
    {

        SDK::setAccessToken($this->data['access_token']);
        $payment = new Payment();
        $payment->transaction_amount = (float)$request['transactionAmount'];
        $payment->token = $request['token'];
        $payment->description = $request['description'];
        $payment->installments = (int)$request['installments'];
        $payment->payment_method_id = $request['paymentMethodId'];
        $payment->issuer_id = (int)$request['issuer'];

        $payer = new Payer();
        $payer->email = $request['payer']['email'];
        $payer->identification = array(
            "type" => $request['payer']['identification']['type'],
            "number" => $request['payer']['identification']['number']
        );
        $payment->payer = $payer;

        $payment->save();

        $response = array(
            'status' => $payment->status,
            'status_detail' => $payment->status_detail,
            'id' => $payment->id
        );

        if($payment->error)
        {
            $response['error'] = $payment->error->message;
        }
        if($payment->status == 'approved')
        {
            $user_id = session('user_id');
            $amount = session('amount');

            /** ADD Money Transaction */
            $transaction_id = $this->add_money_transaction(Helpers::get_admin_id(), $user_id, $amount);

            //if failed
            if (is_null($transaction_id)) {
                Helpers::add_fund($user_id, $amount, 'mercadopago', null, 'failed');
                return \redirect()->route('payment-fail');
            }

            //if success
            Helpers::add_fund($user_id, $amount, 'mercadopago', null, 'success');

            /** Update Transaction limits data  */
            Helpers::add_money_transaction_limit_update($user_id, $amount);

            return \redirect()->route('payment-success');
        }
        return response()->json($response);
    }

    public function get_test_user(Request $request)
    {
        // curl -X POST \
        // -H "Content-Type: application/json" \
        // -H 'Authorization: Bearer PROD_ACCESS_TOKEN' \
        // "https://api.mercadopago.com/users/test_user" \
        // -d '{"site_id":"MLA"}'

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://api.mercadopago.com/users/test_user");
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer '.$this->data['access_token']
        ));
        curl_setopt($curl, CURLOPT_POSTFIELDS, '{"site_id":"MLA"}');
        $response = curl_exec($curl);
        //dd($response);

    }
}
