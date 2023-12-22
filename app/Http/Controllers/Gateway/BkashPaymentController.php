<?php

namespace App\Http\Controllers\Gateway;

use App\CentralLogics\helpers;
use App\Exceptions\TransactionFailedException;
use App\Http\Controllers\Controller;
use App\Models\EMoney;
use App\Models\User;
use App\Traits\TransactionTrait;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BkashPaymentController extends Controller
{
    private $base_url;
    private $app_key;
    private $app_secret;
    private $username;
    private $password;

    use TransactionTrait;

    public function __construct()
    {
        $config = Helpers::get_business_settings('bkash');

        $bkash_app_key = $config['api_key']; // bKash Merchant API APP KEY
        $bkash_app_secret = $config['api_secret']; // bKash Merchant API APP SECRET
        $bkash_username = $config['username']; // bKash Merchant API USERNAME
        $bkash_password = $config['password']; // bKash Merchant API PASSWORD
        $bkash_base_url = (env('APP_MODE') == 'live') ? 'https://tokenized.pay.bka.sh/v1.2.0-beta' : 'https://tokenized.sandbox.bka.sh/v1.2.0-beta';

        $this->app_key = $bkash_app_key;
        $this->app_secret = $bkash_app_secret;
        $this->username = $bkash_username;
        $this->password = $bkash_password;
        $this->base_url = $bkash_base_url;
    }

    public function getToken()
    {
        $request_data = array(
            'app_key' => $this->app_key,
            'app_secret' => $this->app_secret
        );
        $url = curl_init($this->base_url . '/tokenized/checkout/token/grant');
        $request_data_json = json_encode($request_data);
        $header = array(
            'Content-Type:application/json',
            'username:' . $this->username,
            'password:' . $this->password
        );

        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_POSTFIELDS, $request_data_json);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($url, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

        $resultdata = curl_exec($url);
        curl_close($url);

        $response = json_decode($resultdata, true);
        return $response;

    }

    public function make_tokenize_payment(Request $request)
    {
        $user_data = User::find($request['customer_id']);
        $response = self::getToken();

        if (!isset($response['id_token'])){
            return \redirect()->route('payment-fail');
        }

        $auth = $response['id_token'];
        session()->put('token', $auth);
        $callbackURL = route('bkash.callback', ['callback' => $request['callback'], 'token' => $auth]);

        $requestbody = array(
            'mode' => '0011',
            'amount' => $request['order_amount'],
            'currency' => 'BDT',
            'intent' => 'sale',
            'payerReference' => $user_data['phone'],
            'merchantInvoiceNumber' => 'invoice_' . Str::random('15'),
            'callbackURL' => $callbackURL
        );

        $url = curl_init($this->base_url . '/tokenized/checkout/create');
        $requestbodyJson = json_encode($requestbody);

        $header = array(
            'Content-Type:application/json',
            'Authorization:' . $auth,
            'X-APP-Key:' . $this->app_key
        );

        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_POSTFIELDS, $requestbodyJson);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($url, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        $resultdata = curl_exec($url);
        curl_close($url);

        $obj = json_decode($resultdata);
        return redirect()->away($obj->{'bkashURL'});
    }

    public function callback(Request $request)
    {
        $paymentID = $_GET['paymentID'];
        $auth = $_GET['token'];

        $request_body = array(
            'paymentID' => $paymentID
        );
        $url = curl_init($this->base_url . '/tokenized/checkout/execute');

        $request_body_json = json_encode($request_body);

        $header = array(
            'Content-Type:application/json',
            'Authorization:' . $auth,
            'X-APP-Key:' . $this->app_key
        );
        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_POSTFIELDS, $request_body_json);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($url, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        $resultdata = curl_exec($url);
        curl_close($url);
        $obj = json_decode($resultdata);

        $user_id = session('user_id');
        $amount = session('amount');

        //if success
        if ($obj->statusCode == '0000') {
            /** ADD Money Transaction */
            $transaction_id = $this->add_money_transaction(Helpers::get_admin_id(), $user_id, $amount);

            //if failed
            if (is_null($transaction_id)) {
                Helpers::add_fund($user_id, $amount, 'bkash', null, 'failed');
                return \redirect()->route('payment-fail');
            }

            //if success
            Helpers::add_fund($user_id, $amount, 'bkash', null, 'success');

            /** Update Transaction limits data  */
            Helpers::add_money_transaction_limit_update($user_id, $amount);

            return \redirect()->route('payment-success');

        } else {
            Helpers::add_fund($user_id, $amount, 'bkash', null, 'failed');
            return \redirect()->route('payment-fail');
        }
    }
}
