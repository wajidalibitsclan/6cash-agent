<?php

namespace App\Http\Controllers\Gateway;

//use App\CentralLogics\CartManager;
use App\CentralLogics\Helpers;
//use App\CentralLogics\OrderManager;
use App\Exceptions\TransactionFailedException;
use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\EMoney;
use App\Traits\TransactionTrait;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function App\CentralLogics\translate;

class PaymobController extends Controller
{
    use TransactionTrait;

    protected function cURL($url, $json)
    {
        // Create curl resource
        $ch = curl_init($url);

        // Request headers
        $headers = array();
        $headers[] = 'Content-Type: application/json';

        // Return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($json));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // $output contains the output string
        $output = curl_exec($ch);

        // Close curl resource to free up system resources
        curl_close($ch);
        return json_decode($output);
    }

    protected function GETcURL($url)
    {
        // Create curl resource
        $ch = curl_init($url);

        // Request headers
        $headers = array();
        $headers[] = 'Content-Type: application/json';

        // Return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // $output contains the output string
        $output = curl_exec($ch);

        // Close curl resource to free up system resources
        curl_close($ch);
        return json_decode($output);
    }

    public function credit()
    {
        $currency_code = Currency::where(['currency_code' => 'EGP'])->first();
        if (isset($currency_code) == false) {
            Toastr::error(translate('paymob_supports_EGP_currency'));
            return back()->withErrors(['error' => 'Failed']);
        }

        $config = Helpers::get_business_settings('paymob');
        try {
            $token = $this->getToken();
            $order = $this->createOrder($token);
            $paymentToken = $this->getPaymentToken($order, $token);
        }catch (\Exception $exception){
            Toastr::error(translate('country_permission_denied_or_misconfiguration'));
            return back()->withErrors(['error' => 'Failed']);
        }
        return \Redirect::away('https://portal.weaccept.co/api/acceptance/iframes/' . $config['iframe_id'] . '?payment_token=' . $paymentToken);
    }

    public function getToken()
    {
        $config = Helpers::get_business_settings('paymob');
        $response = $this->cURL(
            'https://accept.paymobsolutions.com/api/auth/tokens',
            ['api_key' => $config['api_key']]
        );

        return $response->token;
    }

    public function createOrder($token)
    {
        $value = session('amount');

        $items = []; //items will be here

        $data = [
            "auth_token" => $token,
            "delivery_needed" => "false",
            "amount_cents" => round($value,2) * 100,
            "currency" => "EGP",
            "items" => $items,

        ];
        $response = $this->cURL(
            'https://accept.paymob.com/api/ecommerce/orders',
            $data
        );

        return $response;
    }

    public function getPaymentToken($order, $token)
    {
        $value = session('amount');

        $config = Helpers::get_business_settings('paymob');
        $billingData = [
            "apartment" => "not given",
            "email" => "not given",
            "floor" => "not given",
            "first_name" => "not given",
            "street" => "not given",
            "building" => "not given",
            "phone_number" => "not given",
            "shipping_method" => "PKG",
            "postal_code" => "not given",
            "city" => "not given",
            "country" => "not given",
            "last_name" => "not given",
            "state" => "not given",
        ];
        $data = [
            "auth_token" => $token,
            "amount_cents" => round($value,2) * 100,
            "expiration" => 3600,
            "order_id" => null,
            "billing_data" => $billingData,
            "currency" => "EGP",
            "integration_id" => $config['integration_id']
        ];

        $response = $this->cURL(
            'https://accept.paymob.com/api/acceptance/payment_keys',
            $data
        );

        return $response->token;
    }

    public function callback(Request $request)
    {
        $config = Helpers::get_business_settings('paymob');
        $data = $request->all();
        ksort($data);
        $hmac = $data['hmac'];
        $array = [
            'amount_cents',
            'created_at',
            'currency',
            'error_occured',
            'has_parent_transaction',
            'id',
            'integration_id',
            'is_3d_secure',
            'is_auth',
            'is_capture',
            'is_refunded',
            'is_standalone_payment',
            'is_voided',
            'order',
            'owner',
            'pending',
            'source_data_pan',
            'source_data_sub_type',
            'source_data_type',
            'success',
        ];
        $connectedString = '';
        foreach ($data as $key => $element) {
            if (in_array($key, $array)) {
                $connectedString .= $element;
            }
        }
        $secret = $config['hmac'];
        $hased = hash_hmac('sha512', $connectedString, $secret);

        $user_id = session('user_id');
        $amount = session('amount');

        if ($hased == $hmac) {
            /** ADD Money Transaction */
            $transaction_id = $this->add_money_transaction(Helpers::get_admin_id(), $user_id, $amount);

            //if failed
            if (is_null($transaction_id)) {
                Helpers::add_fund($user_id, $amount, 'paymob', null, 'failed');
                return \redirect()->route('payment-fail');
            }

            //if success
            Helpers::add_fund($user_id, $amount, 'paymob', null, 'success');

            /** Update Transaction limits data  */
            Helpers::add_money_transaction_limit_update($user_id, $amount);

            return \redirect()->route('payment-success');
        }

        //fund record for failed
        Helpers::add_fund($user_id, $amount, 'paymob', null, 'failed');
        return \redirect()->route('payment-fail');
    }
}
