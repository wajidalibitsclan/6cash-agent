<?php

namespace App\Http\Controllers\Gateway;

use App\CentralLogics\Helpers;
use App\Exceptions\TransactionFailedException;
use App\Http\Controllers\Controller;
use App\Models\EMoney;
use App\Models\User;
use App\Traits\TransactionTrait;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class PaypalPaymentController extends Controller
{
    use TransactionTrait;

    public function __construct()
    {
        $paypal_conf = Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential(
                $paypal_conf['client_id'],
                $paypal_conf['secret'])
        );
        $this->_api_context->setConfig($paypal_conf['settings']);
    }

    public function payWithpaypal(Request $request)
    {
        $tr_ref = Str::random(6) . '-' . rand(1, 1000);

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $pay_amount = session('amount');
        $user_id = session('user_id');
        $customer = User::find($user_id);

        $items_array = [];
        $item = new Item();
        $item->setName($customer->f_name)
            ->setCurrency(Helpers::currency_code())
            ->setQuantity(1)
            ->setPrice($pay_amount);
        array_push($items_array, $item);

        $item_list = new ItemList();
        $item_list->setItems($items_array);

        $amount = new Amount();
        $amount->setCurrency(Helpers::currency_code())
            ->setTotal($pay_amount);

        \session()->put('transaction_reference', $tr_ref);
        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription($tr_ref);

        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(URL::route('paypal-status'))
            ->setCancelUrl(URL::route('payment-fail'));

        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));
        try {
            $payment->create($this->_api_context);

            foreach ($payment->getLinks() as $link) {
                if ($link->getRel() == 'approval_url') {
                    $redirect_url = $link->getHref();
                    break;
                }
            }

            Session::put('paypal_payment_id', $payment->getId());
            if (isset($redirect_url)) {
                return Redirect::away($redirect_url);
            }

        } catch (\Exception $ex) {
            Toastr::error('Your currency is not supported by PAYPAL.');
            return back()->withErrors(['error' => 'Failed']);
        }

        Session::put('error', 'Configure your paypal account.');
        return back()->withErrors(['error' => 'Failed']);
    }

    public function getPaymentStatus(Request $request)
    {
        $payment_id = Session::get('paypal_payment_id');
        if (empty($request['PayerID']) || empty($request['token'])) {
            Session::put('error', 'Payment failed');
            return Redirect::back();
        }

        $payment = Payment::get($payment_id, $this->_api_context);
        $execution = new PaymentExecution();
        $execution->setPayerId($request['PayerID']);

        /**Execute the payment **/
        $result = $payment->execute($execution, $this->_api_context);

        $user_id = session('user_id');
        $amount = session('amount');

        if ($result->getState() == 'approved') {
            /** ADD Money Transaction */
            $transaction_id = $this->add_money_transaction(Helpers::get_admin_id(), $user_id, $amount);

            //if failed
            if (is_null($transaction_id)) {
                Helpers::add_fund($user_id, $amount, 'paypal', null, 'failed');
                return \redirect()->route('payment-fail');
            }

            //if success
            Helpers::add_fund($user_id, $amount, 'paypal', null, 'success');

            /** Update Transaction limits data  */
            Helpers::add_money_transaction_limit_update($user_id, $amount);

            return \redirect()->route('payment-success');
        }

        //fund record for failed
        Helpers::add_fund($user_id, $amount, 'paypal', null, 'failed');
        return \redirect()->route('payment-fail');
    }
}
