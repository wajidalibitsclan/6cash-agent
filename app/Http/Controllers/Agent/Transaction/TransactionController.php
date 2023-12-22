<?php

namespace App\Http\Controllers\Agent\Transaction;

use App\Models\Customer;
use App\Models\User;
use App\Models\EMoney;
use App\Models\Country;
use App\Models\AddMoney;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\CentralLogics\helpers;
use Illuminate\Support\Carbon;
use App\Models\BusinessSetting;
use App\Models\TransactionLimit;
use App\Models\WithdrawalMethod;
use App\Traits\TransactionTrait;
use App\Models\TransactionDetail;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Stevebauman\Location\Facades\Location;

class TransactionController extends Controller
{
    use TransactionTrait;
    public $basePath;
    public $token;
    public $withdrawalMethod;
    public $e_money;
    public $user;
    private $transactionDetail;
    private $transactionData;
    private $addMoney;
    private $customer;
    public function __construct()
    {
        $this->basePath = env('APP_URL');
        $this->token = Session::get('token');
        $this->withdrawalMethod = new WithdrawalMethod();
        $this->e_money = new EMoney();
        $this->user = new User();
        $this->transactionDetail = new TransactionDetail();
        $this->transactionData = new Transaction();
        $this->addMoney = new AddMoney();
        $this->customer = new Customer();
    }

    //Add Money Request
    public function addMoneyRequest()
    {
        try {
            $transactions = $this->addMoney->with('user')->where('user_id', Auth::user()->id)->get();
            return view('agent-views.transaction.add-money-request', compact('transactions'));
        } catch (\Exception $error) {
        }
    }
    //Add Money
    public function addMoney()
    {
        try {
            $id = Auth::user()->id;
            $endpoint = $this->basePath . "/api/v1/agent/get-agent-web";
            $header = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'User-Agent' => 'Dart',
                'Authorization' => 'Bearer ' . $this->token
            ];
            $payload = [
                'id' => $id,
                'web' => 'done'
            ];
            $response = Http::withHeaders($header)->get($endpoint, $payload);
            $userData = $response->json();

            $paymentMethods = [
                [
                    'name' => 'SSL Commerz Payment',
                    'slug' => 'ssl_commerz_payment',
                ],
                [
                    'name' => 'Razor Pay',
                    'slug' => 'razor_pay',
                ],
                [
                    'name' => 'Paypal',
                    'slug' => 'paypal',
                ],
                [
                    'name' => 'Stripe',
                    'slug' => 'stripe',
                ],
                [
                    'name' => 'Senang Pay',
                    'slug' => 'senang_pay',
                ],
                [
                    'name' => 'Paystack',
                    'slug' => 'paystack',
                ],
                [
                    'name' => 'Bkash',
                    'slug' => 'bkash',
                ],
                [
                    'name' => 'Paymob',
                    'slug' => 'paymob',
                ],
                [
                    'name' => 'Paymob',
                    'slug' => 'paymob',
                ],
                [
                    'name' => 'Flutterwave',
                    'slug' => 'flutterwave'
                ],
                [
                    'name' => 'Mercadopago',
                    'slug' => 'mercadopago'
                ]
            ];

            $activePaymentMethods = [];
            foreach ($paymentMethods as $paymentMethod) {
                $payMethod = BusinessSetting::where(['key' => $paymentMethod['slug']])->first();
                $status = json_decode($payMethod['value']);
                if ($status->status !== '0') {
                    array_push($activePaymentMethods, $paymentMethod);
                }
            }

            return view('agent-views.transaction.add-money', compact('userData', 'activePaymentMethods'));
        } catch (\Exception $error) {
            return redirect()->back()->withErrors($error->getMessage());
        }
    }
    //Add Money Submit 
    public function addedMoney(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'amount' => 'required',
            ]);

            if ($validator->fails()) {
                return back()->with('errors', $validator->getMessageBag());
            }

            $user = $this->user->where('id', $request->id)->first();
            /** feature active check */
            $add_money_status = Helpers::get_business_settings('add_money_status');
            if (!$add_money_status) {
                Toastr::error(translate('add money feature is not activate'));
                return back();
            }


            //kyc check
            if ($user->is_kyc_verified != 1) {
                Toastr::error(translate('Complete your account verification'));
                return back();
            }

            //emoney check
            $amount = $request->amount;
            $bonus = Helpers::get_add_money_bonus($amount, $user->id, 'agent');
            $total_amount = $amount + $bonus;

            $admin_emoney = $this->e_money->where('user_id', Helpers::get_admin_id())->first();

            if ($admin_emoney && $total_amount > $admin_emoney->current_balance) {
                Toastr::error(translate('The amount is too big. Please contact with admin'));
                return back();
            }
            /** transaction limits check */
            $add_money_limit = Helpers::get_business_settings('agent_add_money_limit');

            if (isset($add_money_limit) && $add_money_limit['status'] == 1) {
                $check = Helpers::check_customer_transaction_limit($user, $request->amount, 'add_money', $add_money_limit);
                if (!$check['status']) {
                    Toastr::error(translate($check['message']));
                    return back();
                }
            }

            $user_id = $user->id;
            $amount = $request->amount;

            $unused_balance = $this->e_money->with('user')->whereHas('user', function ($q) {
                $q->where('type', '=', ADMIN_TYPE);
            })->sum('current_balance');

            if ($request->amount > $unused_balance) {
                Toastr::error(translate('The requested amount is too big'));
                return back();
            }

            $tran = Str::random(6) . '-' . rand(1, 1000);

            $this->addMoney->create([
                'transaction_id' => $tran,
                'status' => 'pending',
                'amount' => $request->amount,
                'user_id' => $user->id
            ]);

            Toastr::success(translate('Request money to Admin!'));
            return back();
        } catch (\Exception $error) {
            Toastr::error($error->getMessage());
            return back();
        }
    }
    // Approve Money
    public function approveMoney(Request $request)
    {
        try {
            $user = $this->user->where('id', $request->id)->first();
            if (is_null($user)) {
                Toastr::error(translate('Something went wrong!'));
                return back();
            }

            $addMoney = $this->addMoney->where(['user_id' => $request->id, 'transaction_id' => $request->transaction_id])->first();

            if (is_null($addMoney)) {
                Toastr::error(translate('Something went wrong!'));
                return back();
            }

            $tran = Str::random(6) . '-' . rand(1, 1000);
            $currency_code = Helpers::get_business_settings('currency');
            $currencies_not_supported_cents = ['BIF', 'CLP', 'DJF', 'GNF', 'JPY', 'KMF', 'KRW', 'MGA', 'PYG', 'RWF', 'UGX', 'VND', 'VUV', 'XAF', 'XOF', 'XPF'];
            $unit_amount = in_array($currency_code, $currencies_not_supported_cents) ? (int)$request->amount : ($request->amount * 100);
            /** ADD Money Transaction */
            $transaction_id = $this->add_money_transaction(Helpers::get_admin_id(), $user->id, $request->amount);
            //if failed
            if (is_null($transaction_id)) {
                $addMoney->update([
                    'status' => $request->status,
                ]);
                Helpers::add_fund($user->id, $request->amount, 'not specified', null, 'failed');
                Toastr::error(translate('Payment failed !'));
                return back();
            }
            //if success
            Helpers::add_fund($user->id, $request->amount, 'not specified', null, 'success');
            /** Update Transaction limits data  */
            Helpers::add_money_transaction_limit_update($user->id, $request->amount);
            if ($request->status === 'approve') {
                Toastr::success(translate('Add Money approved!'));
            } else {
                Toastr::error(translate('Add Money rejected !'));
            }
            $addMoney->update([
                'status' => $request->status,
            ]);
            return back();
        } catch (\Exception $error) {
            return redirect()->back()->withErrors($error->getMessage());
        }
    }
    // Send Money Submit
    public function sendMoney(Request $request)
    {
        try {
            // $agent = Auth::user();
            $agent = $this->user->with('emoney')->where('id', auth()->user()->id)->first();
            $endpoint = $this->basePath . "/api/v1/agent/get-agent-web";
            $header = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'User-Agent' => 'Dart',
                'Authorization' => 'Bearer ' . $this->token
            ];
            $payload = [
                'id' => $agent->id,
                'web' => 'done'
            ];

            $response = Http::withHeaders($header)->get($endpoint, $payload);
            $userData = $response->json();
            $ip = env('APP_MODE') == 'live' ? $request->ip() : '61.247.180.82';
            $current_user_info = Location::get($ip);
            $countries = Country::active()->withACtiveCityAndCurrency();

            $currencies = [];
            foreach ($countries as $country) {
                array_push($currencies, $country->currency);
            }

            return view('agent-views.transaction.send-money', compact('userData', 'countries', 'current_user_info', 'agent', 'currencies'));
        } catch (\Exception $error) {
            return redirect()->back()->withErrors($error->getMessage());
        }
    }
    // Send Money Submit New Flow
    public function sentMoney(Request $request)
    {
        try {
            // dd($request->all());
            $validation = Validator::make(
                $request->all(),
                [
                    'pin' => 'required|min:4|max:4',
                    'sender_customer_name' => 'required',
                    'receiver_customer_name' => 'required',
                    'sender_customer_phone' => 'required',
                    'receiver_customer_phone' => 'required',
                    'purpose' => '',
                    'amount' => 'required|min:0|not_in:0',
                    'plateform_fee' => 'required',
                    'sender_fee' => 'required',
                    'agent_id' => 'required',
                    'from_currency_code' => 'required',
                    'to_currency_code' => 'required'
                ],
                [
                    'amount.not_in' => translate('Amount must be greater than zero!'),
                    'agent_id.required' => translate('No agent selected!'),
                    'plateform_fee.required' => translate('Plateform fee is not set yet!'),
                ]
            );

            if ($validation->fails()) {
                return back()->withErrors($validation->getMessageBag());
            }

            $userData = $this->user->where('id', $request->id)->first();
            /** feature active check */
            $send_money_status = Helpers::get_business_settings('send_money_status');

            if (!$send_money_status) {
                Toastr::error(translate('send money feature is not activate'));
                return back();
            }

            $user = $this->user->where('id', $request->agent_id)->first();
            /** Transaction validation check */
            if (!isset($user)) {
                Toastr::error(translate('Receiver not found'));
                return back();
            }

            if ($user->is_kyc_verified != 1) {
                Toastr::error(translate('Receiver is not verified'));
                return back();
            }

            if ($userData->is_kyc_verified != 1) {
                Toastr::error(translate('Complete your account verification'));
                return back();
            }


            if ($userData->phone == $user->phone) {
                Toastr::error(translate('Transaction should not with own number'));
                return back();
            }

            if ($user->type != 1) {
                Toastr::error(translate('Receiver must be an agent'));
                return back();
            }

            if (!Helpers::pin_check($userData->id, $request->pin)) {
                Toastr::error(translate('PIN is incorrect'));
                return back();
            }

            //Check Limits
            $send_money_limit = Helpers::get_business_settings('customer_send_money_limit');
            if (isset($send_money_limit) && $send_money_limit['status'] == 1) {
                $check = Helpers::check_customer_transaction_limit($userData, $request->amount, 'send_money', $send_money_limit);
                if (!$check['status']) {
                    Toastr::error(translate($check['message']));
                    return back();
                }
            }

            $eMoney = $this->e_money->where('user_id', $userData->id)->first();

            $currentBalance = floatval($eMoney->current_balance);
            $sendingAmount = floatval($request->amount);

            //Check Balance
            if ($currentBalance < $sendingAmount) {
                Toastr::error(translate('Insufficient balance!'));
                return back();
            }

            $balance = $currentBalance - $sendingAmount;
            $receiverFee = (floatval($request->plateform_fee) / 100) * floatval($user->commission);
            $adminFee = floatval($request->plateform_fee) - ($receiverFee + floatval($request->sender_fee));

            $eMoney->update([
                'current_balance' => $balance
            ]);

            /*Storing Customer*/
            $senderCustomer = $this->customer->create([
                'name' => $request->sender_customer_name,
                'phone' => $request->sender_customer_country_code . $request->sender_customer_phone,
                'type' => 'sender'
            ]);

            $receiverCustomer = $this->customer->create([
                'name' => $request->receiver_customer_name,
                'phone' => $request->receiver_customer_country_code . $request->receiver_customer_phone,
                'type' => 'receiver'
            ]);


            $transactionDetail = $this->transactionDetail->create([
                'plateform_fee' => $request->plateform_fee,
                'sender_fee' => $request->sender_fee,
                'receiver_fee' => $receiverFee,
                'admin_fee' => $adminFee,
                'amount' => $request->amount,
                'receiver_amount' => $request->reciever_amount,
                'receiver_amount_exchange' => $request->reciever_amount_exchange,
                'secret_pin' => $request->secret_pin,
                'status' => 'pending',
                'base_currency_code' => $request->from_currency_code,
                'destination_currency_code' => $request->to_currency_code,
                'sender_id' => $request->id,
                'receiver_id' => $request->agent_id,
                'country_id' => $request->country_id,
                'city_id' => $request->city_id,
                'sender_customer_id' => $senderCustomer->id,
                'receiver_customer_id' => $receiverCustomer->id
            ]);

            $sendingMoneyRemain = floatval($sendingAmount) - floatval($request->sender_fee);
            /* Sending Money */
            $transaction = $this->transactionData->create([
                'user_id' => $request->id,
                'ref_trans_id' => null,
                'transaction_type' => SEND_MONEY,
                'debit' => $sendingMoneyRemain,
                'credit' => 0,
                'balance' => $sendingMoneyRemain,
                'from_user_id' => $request->id,
                'to_user_id' => $request->agent_id,
                'note' => null,
                'transaction_id' => Str::random(5) . Carbon::now()->timestamp,
                'transaction_detail_id' => $transactionDetail->id
            ]);

            $eMoney->update([
                'current_balance' => $balance + floatval($request->sender_fee)
            ]);

            /* Received Money */
            $this->transactionData->create([
                'user_id' => $request->id,
                'ref_trans_id' => $transaction->transaction_id,
                'transaction_type' => RECEIVED_MONEY,
                'debit' => 0,
                'credit' => $request->sender_fee,
                'balance' => $sendingAmount,
                'from_user_id' => $request->id,
                'to_user_id' => $request->id,
                'note' => "Charged Amount For Send Money",
                'transaction_id' => Str::random(5) . Carbon::now()->timestamp,
                'transaction_detail_id' => $transactionDetail->id,
                "is_commission" => 1
            ]);

            /** Admins' credit */
            $adminEMoney = $this->e_money->where('user_id', Helpers::get_admin_id())->first();
            $adminEMoney->charge_earned += $adminFee;
            $adminEMoney->current_balance += $adminFee;
            $adminEMoney->save();

            $this->transactionData->create([
                'user_id' => $request->id,
                'ref_trans_id' => $transaction->transaction_id,
                'transaction_type' => ADMIN_CHARGE,
                'debit' => $adminFee,
                'credit' => 0,
                'balance' => $adminEMoney->current_balance,
                'from_user_id' => $request->id,
                'to_user_id' =>  Helpers::get_admin_id(),
                'note' => null,
                'transaction_id' => Str::random(5) . Carbon::now()->timestamp,
                'transaction_detail_id' => $transactionDetail->id,
                'is_commission' => 1
            ]);


            if ($transaction->transaction_id) {
                Toastr::success(translate('Money has been sent successfully!'));
                // return back();
                return redirect()->route('agent.transaction.detail');
            } else {
                Toastr::error(translate('Something went wrong!'));
                return back();
            }
        } catch (\Exception $error) {
            return back()->withErrors($error->getMessage());
        }
    }

    public function cancelTransaction(Request $request)
    {
        try {
            // $validation = Validator::make($request->all(), [
            //     'transaction_detail_id' => 'required',
            // ]);

            // if ($validation->fails()) {
            //     return back()->withErrors($validation->getMessageBag());
            // }

            $transaction = $this->transactionDetail->with('transaction')->where('id', $request->transaction_detail_id)->first();
            if (is_null($transaction)) {
                Toastr::error(translate('No transaction found!'));
                return response()->json([
                    'error' => true,
                    'status' => 403
                ]);
            }

            $currentDateTime = Carbon::now();
            $transactionCreatedTime = $transaction->created_at;
            $timeDifferenceInHours = $currentDateTime->diffInHours($transactionCreatedTime);

            // dd($timeDifferenceInHours);

            if ($timeDifferenceInHours > 3) {
                Toastr::error(translate('Sorry, You cannot revert this transaction!'));
                return response()->json([
                    'error' => true,
                    'status' => 403
                ]);
            }

            $adminFee = floatval($transaction->admin_fee);
            $senderFee = floatval($transaction->sender_fee);
            $receiverFee = floatval($transaction->receiver_fee);

            // dd($adminFee, $senderFee, $receiverFee);
            /* Return Amount */
            $sender = $this->e_money->where('user_id', $transaction->sender_id)->first();
            $sender->update([
                'current_balance' => floatval($sender->current_balance) - floatval($senderFee)
            ]);

            $admin = $this->e_money->where('user_id', Helpers::get_admin_id())->first();
            $admin->update([
                'current_balance' => floatval($admin->current_balance) - floatval($adminFee)
            ]);

            // $receiver = $this->e_money->where('user_id', $transaction->receiver_id)->first();
            // $receiver->update([
            //     'current_balance' => floatval($receiver->current_balance) - floatval($receiverFee)
            // ]);

            // $total = $adminFee + $senderFee + $receiverFee;

            $sender->update([
                'current_balance' => floatval($sender->current_balance) + floatval($transaction->amount) - floatval($transaction->sender_fee)
            ]);

            foreach ($transaction->transaction as $trans) {
                if ($trans->to_user_id !==  Helpers::get_admin_id()) {
                    $this->transactionData->create([
                        'user_id' => $trans->user_id,
                        'ref_trans_id' => $trans->ref_trans_id,
                        'transaction_type' => CANCEL,
                        'debit' => $trans->credit,
                        'credit' => $trans->debit,
                        'balance' => $trans->balance,
                        'from_user_id' => $trans->from_user_id,
                        'to_user_id' =>  $trans->to_user_id,
                        'note' => $trans->note,
                        'transaction_id' => $trans->transaction_id,
                        'transaction_detail_id' => $trans->transaction_detail_id,
                        'is_commission' => $trans->is_commission
                    ]);
                }
            }

            $transaction->update([
                'status' => 'cancel'
            ]);

            // dd($transaction);
            // Transaction::create([
            //     'user_id' => $transaction->sender_id,
            //     'ref_trans_id' => null,
            //     'transaction_type' => RECEIVED_MONEY,
            //     'debit' => $transaction->amount,
            //     'credit' => 0,
            //     'balance' => $transaction->amount,
            //     'from_user_id' => $transaction->sender_id,
            //     'to_user_id' =>  $transaction->sender_id,
            //     'note' => null,
            //     'transaction_id' => Str::random(5) . Carbon::now()->timestamp,
            //     'transaction_detail_id' => $transaction->id
            // ]);

            Toastr::success(translate('Cancel Transaction Successfully!'));
            return response()->json([
                'message' => 'Your transaction has been canceled!',
                'status' => 200
            ]);
        } catch (\Exception $error) {
            return back()->withErrors($error->getMessage());
        }
    }

    public function rejectTransaction(Request $request)
    {
        try {
            $transaction = $this->transactionDetail->with('transaction')->where('id', $request->transaction_id)->first();
            if (is_null($transaction)) {
                Toastr::error(translate('No transaction found!'));
                return response()->json([
                    'error' => true,
                    'status' => 403
                ]);
            }

            $adminFee = floatval($transaction->admin_fee);
            $senderFee = floatval($transaction->sender_fee);

            /* Return Amount */
            $sender = $this->e_money->where('user_id', $transaction->sender_id)->first();
            $sender->update([
                'current_balance' => floatval($sender->current_balance) - floatval($senderFee)
            ]);

            $admin = $this->e_money->where('user_id', Helpers::get_admin_id())->first();
            $admin->update([
                'current_balance' => floatval($admin->current_balance) - floatval($adminFee)
            ]);

            $sender->update([
                'current_balance' => floatval($sender->current_balance) + floatval($transaction->amount) - floatval($transaction->sender_fee)
            ]);

            foreach ($transaction->transaction as $trans) {
                if ($trans->to_user_id !==  Helpers::get_admin_id()) {
                    $this->transactionData->create([
                        'user_id' => $trans->user_id,
                        'ref_trans_id' => $trans->ref_trans_id,
                        'transaction_type' => REJECTED,
                        'debit' => $trans->credit,
                        'credit' => $trans->debit,
                        'balance' => $trans->balance,
                        'from_user_id' => $trans->from_user_id,
                        'to_user_id' =>  $trans->to_user_id,
                        'note' => $trans->note,
                        'transaction_id' => $trans->transaction_id,
                        'transaction_detail_id' => $trans->transaction_detail_id,
                        'is_commission' => $trans->is_commission
                    ]);
                }
            }

            $transaction->update([
                'status' => 'reject'
            ]);

            Toastr::success(translate('Rejected Transaction Successfully!'));
            return response()->json([
                'message' => 'Your transaction has been rejected!',
                'status' => 200
            ]);
        } catch (\Exception $error) {
            Toastr::error($error->getMessage());
            return response()->json([
                'status' => 403,
                'message' => 'error'
            ]);
        }
    }
    public function verifySecretPin(Request $request)
    {
        try {
            $transaction = $this->transactionDetail->where(['id' => $request->id, 'secret_pin' => $request->secret_pin])->first();
            if (is_null($transaction)) {
                Toastr::error(translate('No Transaction found!'));
                return response()->json([
                    'status' => 404,
                    'error' => false
                ]);
            }

            return response()->json([
                'status' => 200,
                'message' => 'Secret PIN verified'
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 403,
                'message' => translate('Something went wrong!')
            ]);
        }
    }

    public function verifyTransaction(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'transaction_id' => 'required',
                'secret_pin' => 'required'
            ], [
                'secret_pin.required' => translate('Secret Pin is required!'),
            ]);

            if ($validation->fails()) {
                return back()->withErrors($validation->getMessageBag());
            }
            $transaction = $this->transactionDetail->where(['id' => $request->transaction_id, 'secret_pin' => $request->secret_pin])->first();
            if (is_null($transaction)) {
                Toastr::error(translate('Sorry, Invalid PIN or No transaction found!'));
                return back();
            }
            $transaction->update(['status' => 'complete']);

            $EMoney = $this->e_money->where('user_id', auth()->user()->id)->first();
            $EMoney->update([
                'current_balance' => floatval($EMoney->current_balance) + floatval($transaction->receiver_amount)
            ]);

            $this->transactionData->create([
                'user_id' => $transaction->receiver_id,
                'ref_trans_id' => null,
                'transaction_type' => RECEIVED_MONEY,
                'debit' => 0,
                'credit' => $transaction->receiver_amount,
                'balance' => $transaction->receiver_amount,
                'from_user_id' => $transaction->sender_id,
                'to_user_id' =>  $transaction->receiver_id,
                'note' => "Amount has been received by Agent",
                'transaction_id' => Str::random(5) . Carbon::now()->timestamp,
                'transaction_detail_id' => $transaction->id
            ]);

            $EMoney->update([
                'current_balance' => floatval($EMoney->current_balance) + floatval($transaction->receiver_fee)
            ]);

            $this->transactionData->create([
                'user_id' => $transaction->receiver_id,
                'ref_trans_id' => null,
                'transaction_type' => RECEIVED_MONEY,
                'debit' => 0,
                'credit' => $transaction->receiver_fee,
                'balance' => $transaction->receiver_fee,
                'from_user_id' => $transaction->sender_id,
                'to_user_id' =>  $transaction->receiver_id,
                'note' => "Fee charged",
                'transaction_id' => Str::random(5) . Carbon::now()->timestamp,
                'transaction_detail_id' => $transaction->id,
                'is_commission' => 1
            ]);


            $this->transactionData->create([
                'user_id' => $transaction->receiver_id,
                'ref_trans_id' => null,
                'transaction_type' => SEND_MONEY,
                'debit' => $transaction->receiver_amount,
                'credit' => 0,
                'balance' => $transaction->receiver_amount,
                'from_user_id' => $transaction->sender_id,
                'to_user_id' =>  $transaction->receiver_id,
                'note' => "Amount has been received by Agent",
                'transaction_id' => Str::random(5) . Carbon::now()->timestamp,
                'transaction_detail_id' => $transaction->id
            ]);



            $EMoney->update([
                'current_balance' => floatval($EMoney->current_balance) - floatval($transaction->receiver_amount)
            ]);


            Toastr::success(translate('Money has been received!'));
            return back();
        } catch (\Exception $error) {
            return back()->withErrors($error->getMessage());
        }
    }

    // Transaction History Page
    public function history($transaction_type = null)
    {
        try {
            $userId = Auth::user()->id;
            $transactions = $this->transactionData->with(['sender', 'receiver', 'agent'])->where('user_id', $userId);

            $transactions->when($transaction_type == CASH_IN, function ($q) {
                return $q->where('transaction_type', CASH_IN);
            });
            $transactions->when($transaction_type == CASH_OUT, function ($q) {
                return $q->where('transaction_type', CASH_OUT);
            });
            $transactions->when($transaction_type == SEND_MONEY, function ($q) {
                return $q->where('transaction_type', SEND_MONEY);
            });
            $transactions->when($transaction_type == RECEIVED_MONEY, function ($q) {
                return $q->where('transaction_type', RECEIVED_MONEY);
            });
            $transactions->when($transaction_type == ADD_MONEY, function ($q) {
                return $q->where('transaction_type', ADD_MONEY);
            });

            $transactions->when($transaction_type == CANCEL, function ($q) {
                return $q->where('transaction_type', CANCEL);
            });
            $transactions->when($transaction_type == REJECTED, function ($q) {
                return $q->where('transaction_type', REJECTED);
            });

            $transactions = $transactions
                ->agent()
                ->where('transaction_type', '!=', ADMIN_CHARGE)
                ->orderBy("created_at", 'desc')
                ->paginate(10);

            $transaction = $transactions;

            // dd($transaction);
            $transactionData = $this->transactionData->where(['user_id' => auth()->user()->id])->where('to_user_id', '!=', Helpers::get_admin_id())->get();

            $totalDebit = 0;
            $totalCredit = 0;
            $totalBalance = 0;
            $totalCommission = 0;


            foreach ($transactionData as $trans) {
                if ($trans->transaction_type === 'cancel' || $trans->transaction_type === 'reject') {
                    $totalDebit -= floatval($trans->credit);
                    $totalCredit -= floatval($trans->debit);
                } else {

                    $totalDebit += floatval($trans->debit);
                    $totalCredit += floatval($trans->credit);
                }
            }

            // dd($totalDebit);

            $commission = $this->transactionData->where(['is_commission' => 1, 'to_user_id' => auth()->user()->id])->whereNot('transaction_type', 'cancel')->whereNot('transaction_type', 'reject')->sum('credit');
            $commissionCancel = $this->transactionData->where(['is_commission' => 1, 'to_user_id' => auth()->user()->id, 'transaction_type' => 'cancel'])->sum('debit');
            $commissionReject = $this->transactionData->where(['is_commission' => 1, 'to_user_id' => auth()->user()->id, 'transaction_type' => 'reject'])->sum('debit');

            $agent = $this->user->with('emoney')->where('id', $userId)->first();
            // $commissionCancel = Transaction::where(['is_commission' => 1, 'to_user_id' => auth()->user()->id])->get();
            $totalCommission = $commission - $commissionCancel - $commissionReject;
            $statistics = [
                'debit' => $totalDebit,
                'credit' => $totalCredit,
                'balance' => $agent->emoney->current_balance,
                'commission' => $totalCommission
            ];


            // dd($transaction);
            return view('agent-views.transaction.history', compact('transaction', 'statistics'));


            // $userId = Auth::user()->id;
            // $endpoint = $this->basePath . "/api/v1/agent/transaction-history-web";
            // $header = [
            //     'Accept' => 'application/json',
            //     'Content-Type' => 'application/json',
            //     'User-Agent' => 'Dart',
            //     'Authorization' => 'Bearer ' . $this->token
            // ];

            // if (is_null($transaction_type)) {
            //     $payload = [
            //         'web' => 'done',
            //         'id' => $userId,
            //     ];
            // } else {
            //     $payload = [
            //         'web' => 'done',
            //         'id' => $userId,
            //         'transaction_type' => $transaction_type
            //     ];
            // }

            // $response = Http::withHeaders($header)->get($endpoint, $payload);
            // $transaction = $response->json();

            // $totalDebit = 0;
            // $totalCredit = 0;
            // $totalBalance = 0;
            // $totalCommission = 0;

            // if (isset($transaction['transactions']) && count($transaction['transactions']) > 0) {
            //     foreach ($transaction['transactions'] as $trans) {
            //         if ($trans['transaction_type'] == 'cancel' || $trans['transaction_type'] == 'reject') {

            //             $totalDebit -= $trans['credit'];
            //             $totalCredit -= $trans['debit'];
            //         } else {
            //             $totalDebit += $trans['debit'];
            //             $totalCredit += $trans['credit'];
            //         }
            //     }
            // }

            // $commission = Transaction::where(['is_commission' => 1, 'to_user_id' => auth()->user()->id])->whereNot('transaction_type', 'cancel')->whereNot('transaction_type', 'reject')->sum('credit');
            // $commissionCancel = Transaction::where(['is_commission' => 1, 'to_user_id' => auth()->user()->id, 'transaction_type' => 'cancel'])->sum('debit');
            // $commissionReject = Transaction::where(['is_commission' => 1, 'to_user_id' => auth()->user()->id, 'transaction_type' => 'reject'])->sum('debit');


            // $agent = User::with('emoney')->agent()->find($userId);
            // // $commissionCancel = Transaction::where(['is_commission' => 1, 'to_user_id' => auth()->user()->id])->get();
            // $totalCommission = $commission - $commissionCancel - $commissionReject;
            // $statistics = [
            //     'debit' => $totalDebit,
            //     'credit' => $totalCredit,
            //     'balance' => $agent->emoney->current_balance,
            //     'commission' => $totalCommission
            // ];

            // return view('agent-views.transaction.history', compact('transaction', 'statistics'));
        } catch (\Exception $error) {
            return back()->withErrors($error->getMessage());
        }
    }
    // Transaction Limit 
    public function transactionLimit()
    {
        try {
            $id = Auth::user()->id;
            $endpoint = $this->basePath . "/api/v1/agent/get-agent-web";
            $header = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'User-Agent' => 'Dart',
                'Authorization' => 'Bearer ' . $this->token
            ];
            $payload = [
                'id' => $id,
                'web' => 'done'
            ];
            $response = Http::withHeaders($header)->get($endpoint, $payload);
            $userData = $response->json();

            return view('agent-views.transaction.transaction-limit', compact('userData'));
        } catch (\Exception $error) {
            return redirect()->back()->withErrors($error->getMessage());
        }
    }
    // Withdraw History
    public function withdrawHistory($status = null)
    {
        try {
            $id = Auth::user()->id;
            $endpoint = $this->basePath . "/api/v1/agent/withdrawal-requests-web";
            $header = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'User-Agent' => 'Dart',
                'Authorization' => 'Bearer ' . $this->token
            ];
            $payload = [
                'id' => $id,
                'web' => 'done',
                'request_status' => $status
            ];
            $response = Http::withHeaders($header)->get($endpoint, $payload);
            $withdrawHistory = $response->json();

            return view('agent-views.transaction.withdraw-history', compact('withdrawHistory'));
        } catch (\Exception $error) {
            return redirect()->back()->withErrors($error->getMessage());
        }
    }
    // Request Money 
    public function sendRequests($status = null)
    {
        try {
            $id = Auth::user()->id;
            $endpoint = $this->basePath . "/api/v1/agent/get-requested-money-web";
            $header = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'User-Agent' => 'Dart',
                'Authorization' => 'Bearer ' . $this->token
            ];
            $payload = [
                'id' => $id,
                'web' => 'done',
                'type' => $status
            ];
            $response = Http::withHeaders($header)->get($endpoint, $payload);
            $requestMoney = $response->json();
            return view('agent-views.transaction.send-request', compact('requestMoney'));
        } catch (\Exception $error) {
            return redirect()->back()->withErrors($error->getMessage());
        }
    }
    //Requests 
    public function requests($status = null)
    {

        try {
            $id = Auth::user()->id;
            $endpoint = $this->basePath . "/api/v1/agent/get-requested-money-web";
            $header = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'User-Agent' => 'Dart',
                'Authorization' => 'Bearer ' . $this->token
            ];
            $payload = [
                'id' => $id,
                'web' => 'done',
                'request_status' => $status
            ];
            $response = Http::withHeaders($header)->get($endpoint, $payload);
            $requests = $response->json();

            return view('agent-views.transaction.requests', compact('requests'));
        } catch (\Exception $error) {
            return redirect()->back()->withErrors($error->getMessage());
        }
    }
    // Request Money 
    public function requestMoney()
    {
        try {
            $id = Auth::user()->id;
            $endpoint = $this->basePath . "/api/v1/agent/get-agent-web";
            $header = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'User-Agent' => 'Dart',
                'Authorization' => 'Bearer ' . $this->token
            ];
            $payload = [
                'id' => $id,
                'web' => 'done'
            ];
            $response = Http::withHeaders($header)->get($endpoint, $payload);
            $userData = $response->json();

            return view('agent-views.transaction.request-money', compact('userData'));
        } catch (\Exception $error) {
            return redirect()->back()->withErrors($error->getMessage());
        }
    }
    // Request Money Submit
    public function requestedMoney(Request $request)
    {
        try {
            $id = Auth::user()->id;
            $endpoint = $this->basePath . "/api/v1/agent/request-money-web";
            $header = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'User-Agent' => 'Dart',
                'Authorization' => 'Bearer ' . $this->token
            ];
            $payload = [
                'id' => $id,
                'amount' => $request->amount,
                'note' => $request->note,
                'web' => 'done'
            ];
            $response = Http::withHeaders($header)->post($endpoint, $payload);
            $responseBody = $response->json();
            if (isset($responseBody['message']) && $responseBody['message'] == 'success') {
                Toastr::success(translate('Requested successfully!'));
                return back();
            } else {
                return redirect()->back()->withErrors('Something went wrong!');
            }
        } catch (\Exception $error) {
            return redirect()->back()->withErrors($error->getMessage());
        }
    }
    // Withdraw 
    public function withdraw()
    {
        try {
            $withdrawalMethods = $this->withdrawalMethod->get();
            $agent = User::with('emoney')->where('id', auth()->user()->id)->first();
            return view('agent-views.transaction.withdraw', compact('withdrawalMethods', 'agent'));
        } catch (\Exception $error) {
            return redirect()->back()->withErrors($error->getMessage());
        }
    }
    // Withdraw Action 
    public function withdrawAction(Request $request)
    {
        try {
            $data = $request->all();
            $withdrawalMethods = $this->withdrawalMethod->where('id', $request->method_id)->first();
            $id = Auth::user()->id;
            $endpoint = $this->basePath . "/api/v1/agent/withdraw-web";
            $header = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'User-Agent' => 'Dart',
                'Authorization' => 'Bearer ' . $this->token
            ];

            $payload = [
                'id' => $id,
                'amount' => $request->amount,
                'sender_note' => $request->note,
                'pin' => $request->pin,
                'withdrawal_method_id' => $withdrawalMethods->id,
                'withdrawal_method_fields' => $withdrawalMethods->method_fields,
                'data' => $data,
                'web' => 'done'
            ];
            $response = Http::withHeaders($header)->post($endpoint, $payload);
            $responseBody = $response->json();

            if (isset($responseBody['response_code']) && $responseBody['response_code'] === 'default_store_200') {
                Toastr::success(translate('Successfully added'));
                return redirect()->back();
            } else {
                return redirect()->back()->withErrors(translate('Something went wrong!'));
            }
        } catch (\Exception $error) {
            return redirect()->back()->withErrors($error->getMessage());
        }
    }
    // GET WithDraw Methods
    public function getWithdrawalMethods(Request $request)
    {
        try {
            $withdrawalMethods = $this->withdrawalMethod->where('id', $request->id)->first();
            return response()->json([
                'status' => 200,
                'data' => $withdrawalMethods
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 401,
                'error' => $error->getMessage()
            ]);
        }
    }
    // Add Money New Flow
    public function addMoneyNew(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'amount' => 'required',
            ]);

            if ($validator->fails()) {
                return back()->with('errors', $validator->getMessageBag());
            }

            $user = User::where('id', $request->id)->first();
            $add_money_status = Helpers::get_business_settings('add_money_status');

            if (!$add_money_status)
                return response()->json(['message' => translate('add money feature is not activate')], 403);
            if ($user->is_kyc_verified != 1) {
                return response()->json(['message' => 'Complete your account verification'], 403);
            }

            $amount = $request->amount;
            $bonus = Helpers::get_add_money_bonus($amount, $user->id, 'agent');
            $total_amount = $amount + $bonus;

            $admin_emoney = $this->e_money->where('user_id', Helpers::get_admin_id())->first();
            if ($admin_emoney && $total_amount > $admin_emoney->current_balance) {
                return back()->with('error', 'The amount is too big. Please contact with admin');
            }

            $add_money_limit = Helpers::get_business_settings('agent_add_money_limit');

            if (isset($add_money_limit) && $add_money_limit['status'] == 1) {
                $check = Helpers::check_customer_transaction_limit($user, $request['amount'], 'add_money', $add_money_limit);
                if (!$check['status']) {
                    return back()->with('error', translate($check['message']));
                }
            }

            $user_id = $user->id;
            $amount = $request->amount;
            $payment_method = $request->payment_method;

            $link = route('payment-agent', ['user_id' => $user_id, 'amount' => $amount, 'payment_method' => $payment_method]);
            return response()->json(['link' => $link], 200);
        } catch (\Exception $error) {
            return back()->withErrors($error->getMessage());
        }
    }


    public function requestSendMoney($status = null)
    {
        try {
            if (is_null($status)) {
                $request = $this->transactionDetail->with(['receiver', 'senderCustomer', 'receiverCustomer'])->where('sender_id', auth()->user()->id)->orWhere('receiver_id', auth()->user()->id)->orderBy('created_at', 'desc')->get();
            } else {
                if ($status === 'cash_in') {
                    $request = $this->transactionDetail->with(['receiver', 'senderCustomer', 'receiverCustomer'])->where('receiver_id', auth()->user()->id)->orderBy('created_at', 'desc')->get();
                }
                if ($status === 'cash_out') {
                    $request = $this->transactionDetail->with(['receiver', 'senderCustomer', 'receiverCustomer'])->where('sender_id', auth()->user()->id)->orderBy('created_at', 'desc')->get();
                }
            }

            return view('agent-views.transaction.transaction-requests', compact('request'));
        } catch (\Exception $error) {
            Toastr::error($error->getMessage());
            return back();
        }
    }
}
