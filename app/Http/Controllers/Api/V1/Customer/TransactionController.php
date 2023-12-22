<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\CentralLogics\helpers;
use App\Exceptions\TransactionFailedException;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Models\EMoney;
use App\Models\RequestMoney;
use App\Models\Transaction;
use App\Models\TransactionLimit;
use App\Models\User;
use App\Models\WithdrawalMethod;
use App\Models\WithdrawRequest;
use App\Traits\TransactionTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    use TransactionTrait;

    public function __construct(
        private WithdrawalMethod $withdrawal_method,
        private WithdrawRequest $withdraw_request,
        private User $user,
        private EMoney $e_money,
        private RequestMoney $request_money
    ) {
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function send_money(Request $request): JsonResponse
    {
        $validator = Validator::make(
            $request->all(),
            [
                'pin' => 'required|min:4|max:4',
                'phone' => 'required',
                'purpose' => '',
                'amount' => 'required|min:0|not_in:0',
            ],
            [
                'amount.not_in' => translate('Amount must be greater than zero!'),
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        if (isset($request->web) && $request->web === 'done') {
            $userData = $this->user->where('id', $request->id)->first();

            // return response()->json([
            //     'data' => $userData
            // ]);
            /** feature active check */
            $send_money_status = Helpers::get_business_settings('send_money_status');

            if (!$send_money_status)
                return response()->json(['message' => translate('send money feature is not activate')], 403);

            $receiver_phone = Helpers::filter_phone($request->phone);
            $user = $this->user->where('phone', $receiver_phone)->first();

            /** Transaction validation check */
            if (!isset($user))
                return response()->json(['message' => translate('Receiver not found')], 403); //Receiver Check

            if ($user->is_kyc_verified != 1)
                return response()->json(['message' => translate('Receiver is not verified')], 403); //kyc check

            if ($userData->is_kyc_verified != 1)
                return response()->json(['message' => translate('Complete your account verification')], 403); //kyc check

            if ($userData->phone == $receiver_phone)
                return response()->json(['message' => translate('Transaction should not with own number')], 400); //own number check

            if ($user->type != 2)
                return response()->json(['message' => translate('Receiver must be a user')], 400); //'if receiver is customer' check

            if (!Helpers::pin_check($userData->id, $request->pin))
                return response()->json(['message' => translate('PIN is incorrect')], 403); //PIN Check

            /** transaction limits check */
            $send_money_limit = Helpers::get_business_settings('customer_send_money_limit');

            if (isset($send_money_limit) && $send_money_limit['status'] == 1) {
                $check = Helpers::check_customer_transaction_limit($userData, $request['amount'], 'send_money', $send_money_limit);
                if (!$check['status']) {
                    return response()->json(['message' => translate($check['message'])], 400);
                }
            }

            /** Transaction */
            $customer_transaction = $this->customer_send_money_transaction($userData->id, Helpers::get_user_id($receiver_phone), $request['amount']);

            if (is_null($customer_transaction)) {
                return response()->json(['message' => translate('fail')], 501);
            } //if failed

            /** Update Transaction limits data  */
            if (isset($send_money_limit) && $send_money_limit['status'] == 1) {
                $transaction_limit = TransactionLimit::where(['user_id' => $userData->id, 'type' => 'send_money'])->first();
                $transaction_limit->user_id = $userData->id;
                $transaction_limit->todays_count += 1;
                $transaction_limit->todays_amount += $request['amount'];
                $transaction_limit->this_months_count += 1;
                $transaction_limit->this_months_amount += $request['amount'];
                $transaction_limit->type = 'send_money';
                $transaction_limit->updated_at = now();
                $transaction_limit->update();
            }

            return response()->json(['message' => 'success', 'transaction_id' => $customer_transaction], 200);
        } else {
            /** feature active check */
            $send_money_status = Helpers::get_business_settings('send_money_status');

            if (!$send_money_status)
                return response()->json(['message' => translate('send money feature is not activate')], 403);

            $receiver_phone = Helpers::filter_phone($request->phone);
            $user = $this->user->where('phone', $receiver_phone)->first();

            /** Transaction validation check */
            if (!isset($user))
                return response()->json(['message' => translate('Receiver not found')], 403); //Receiver Check

            if ($user->is_kyc_verified != 1)
                return response()->json(['message' => translate('Receiver is not verified')], 403); //kyc check

            if ($request->user()->is_kyc_verified != 1)
                return response()->json(['message' => translate('Complete your account verification')], 403); //kyc check

            if ($request->user()->phone == $receiver_phone)
                return response()->json(['message' => translate('Transaction should not with own number')], 400); //own number check

            if ($user->type != 2)
                return response()->json(['message' => translate('Receiver must be a user')], 400); //'if receiver is customer' check

            if (!Helpers::pin_check($request->user()->id, $request->pin))
                return response()->json(['message' => translate('PIN is incorrect')], 403); //PIN Check

            /** transaction limits check */
            $send_money_limit = Helpers::get_business_settings('customer_send_money_limit');

            if (isset($send_money_limit) && $send_money_limit['status'] == 1) {
                $check = Helpers::check_customer_transaction_limit($request->user(), $request['amount'], 'send_money', $send_money_limit);
                if (!$check['status']) {
                    return response()->json(['message' => translate($check['message'])], 400);
                }
            }

            /** Transaction */
            $customer_transaction = $this->customer_send_money_transaction($request->user()->id, Helpers::get_user_id($receiver_phone), $request['amount']);

            if (is_null($customer_transaction)) {
                return response()->json(['message' => translate('fail')], 501);
            } //if failed

            /** Update Transaction limits data  */
            if (isset($send_money_limit) && $send_money_limit['status'] == 1) {
                $transaction_limit = TransactionLimit::where(['user_id' => $request->user()->id, 'type' => 'send_money'])->first();
                $transaction_limit->user_id = $request->user()->id;
                $transaction_limit->todays_count += 1;
                $transaction_limit->todays_amount += $request['amount'];
                $transaction_limit->this_months_count += 1;
                $transaction_limit->this_months_amount += $request['amount'];
                $transaction_limit->type = 'send_money';
                $transaction_limit->updated_at = now();
                $transaction_limit->update();
            }

            return response()->json(['message' => 'success', 'transaction_id' => $customer_transaction], 200);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function cash_out(Request $request): JsonResponse
    {
        $validator = Validator::make(
            $request->all(),
            [
                'pin' => 'required|min:4|max:4',
                'phone' => 'required',
                'amount' => 'required|min:0|not_in:0',
            ],
            [
                'amount.not_in' => translate('Amount must be greater than zero!'),
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        /** feature active check */
        $cash_out_status = Helpers::get_business_settings('cash_out_status');

        if (!$cash_out_status)
            return response()->json(['message' => translate('cash out feature is not activate')], 403);

        $receiver_phone = Helpers::filter_phone($request->phone);
        $user = $this->user->where('phone', $receiver_phone)->first();

        /** Transaction validation check */
        if (!isset($user))
            return response()->json(['message' => translate('Receiver not found')], 403); //Receiver Check

        if ($user->is_kyc_verified != 1)
            return response()->json(['message' => translate('Receiver is not verified')], 403); //kyc check

        if ($request->user()->is_kyc_verified != 1)
            return response()->json(['message' => translate('Verify your account information')], 403); //kyc check

        if ($request->user()->phone == $receiver_phone)
            return response()->json(['message' => translate('Transaction should not with own number')], 400); //own number check

        if ($user->type != 1)
            return response()->json(['message' => translate('Receiver must be an agent')], 400); //'if receiver is customer' check

        if (!Helpers::pin_check($request->user()->id, $request->pin))
            return response()->json(['message' => translate('PIN is incorrect')], 403); //PIN Check

        /** transaction limits check */
        $cash_out_limit = Helpers::get_business_settings('customer_cash_out_limit');

        if (isset($cash_out_limit) && $cash_out_limit['status'] == 1) {
            $check = Helpers::check_customer_transaction_limit($request->user(), $request['amount'], 'cash_out', $cash_out_limit);
            if (!$check['status']) {
                return response()->json(['message' => translate($check['message'])], 400);
            }
        }

        /** Transaction */
        $customer_transaction = $this->customer_cash_out_transaction($request->user()->id, Helpers::get_user_id($receiver_phone), $request['amount']);

        if (is_null($customer_transaction)) return response()->json(['message' => translate('fail')], 501); //if failed

        /** Update Transaction limits data  */
        if (isset($cash_out_limit) && $cash_out_limit['status'] == 1) {
            $transaction_limit = TransactionLimit::where(['user_id' => $request->user()->id, 'type' => 'cash_out'])->first();
            $transaction_limit->user_id = $request->user()->id;
            $transaction_limit->todays_count += 1;
            $transaction_limit->todays_amount += $request['amount'];
            $transaction_limit->this_months_count += 1;
            $transaction_limit->this_months_amount += $request['amount'];
            $transaction_limit->type = 'cash_out';
            $transaction_limit->updated_at = now();
            $transaction_limit->update();
        }

        return response()->json(['message' => 'success', 'transaction_id' => $customer_transaction], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function request_money(Request $request): JsonResponse
    {
        $validator = Validator::make(
            $request->all(),
            [
                'phone' => 'required',
                'amount' => 'required|min:0|not_in:0',
                'note' => '',
            ],
            [
                'amount.not_in' => translate('Amount must be greater than zero!'),
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        /** feature active check */
        $send_money_request_status = Helpers::get_business_settings('send_money_request_status');

        if (!$send_money_request_status)
            return response()->json(['message' => translate('request money feature is not activate')], 403);

        $receiver_phone = Helpers::filter_phone($request->phone);
        $user = $this->user->where('phone', $receiver_phone)->first();

        /** Transaction validation check */
        if (!isset($user))
            return response()->json(['message' => translate('Receiver not found')], 403); //Receiver Check

        if ($user->is_kyc_verified != 1)
            return response()->json(['message' => translate('Receiver is not verified')], 403); //kyc check

        if ($request->user()->is_kyc_verified != 1)
            return response()->json(['message' => translate('Verify your account information')], 403); //kyc check

        if ($request->user()->phone == $receiver_phone)
            return response()->json(['message' => translate('Transaction should not with own number')], 400); //own number check

        if ($user->type !=  2)
            return response()->json(['message' => translate('Receiver must be a user')], 400); //'if receiver is customer' check

        /** transaction limits check */
        $send_money_request_limit = Helpers::get_business_settings('customer_send_money_request_limit');

        if (isset($send_money_request_limit) && $send_money_request_limit['status'] == 1) {
            $check = Helpers::check_customer_transaction_limit($request->user(), $request['amount'], 'send_money_request', $send_money_request_limit);
            if (!$check['status']) {
                return response()->json(['message' => translate($check['message'])], 400);
            }
        }

        $request_money = $this->request_money;
        $request_money->from_user_id = $request->user()->id;
        $request_money->to_user_id = Helpers::get_user_id($receiver_phone);
        $request_money->type = 'pending';
        $request_money->amount = $request->amount;
        $request_money->note = $request->note;
        $request_money->save();

        /** Update Transaction limits data  */
        if (isset($send_money_request_limit) && $send_money_request_limit['status'] == 1) {
            $transaction_limit = TransactionLimit::where(['user_id' => $request->user()->id, 'type' => 'send_money_request'])->first();
            $transaction_limit->user_id = $request->user()->id;
            $transaction_limit->todays_count += 1;
            $transaction_limit->todays_amount += $request['amount'];
            $transaction_limit->this_months_count += 1;
            $transaction_limit->this_months_amount += $request['amount'];
            $transaction_limit->type = 'send_money_request';
            $transaction_limit->updated_at = now();
            $transaction_limit->update();
        }

        //send notification
        Helpers::send_transaction_notification($request_money->from_user_id, $request->amount, 'request_money');
        Helpers::send_transaction_notification($request_money->to_user_id, $request->amount, 'request_money');

        return response()->json(['message' => 'success'], 200);
    }

    /**
     * @param Request $request
     * @param $slug
     * @return JsonResponse
     * @throws \Exception
     */
    public function request_money_status(Request $request, $slug): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'pin' => 'required|min:4|max:4',
            'id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        if (!in_array(strtolower($slug), ['deny', 'approve'])) {
            return response()->json(['message' => translate('Invalid request')], 403);
        }

        /** feature active check */
        $send_money_status = Helpers::get_business_settings('send_money_status');

        if (!$send_money_status)
            return response()->json(['message' => translate('send money feature is not activate')], 403);

        $request_money = $this->request_money->find($request->id);

        /** Transaction validation check */
        if (!isset($request_money))
            return response()->json(['message' => translate('Request not found')], 404);

        if ($this->user->find($request_money->to_user_id)->is_kyc_verified != 1)
            return response()->json(['message' => 'Receiver is not verified'], 403); //kyc check

        if ($request->user()->is_kyc_verified != 1)
            return response()->json(['message' => 'Complete your account verification'], 403); //kyc check

        if ($request_money->to_user_id != $request->user()->id)
            return response()->json(['message' => 'unauthorized request'], 403); //access check

        if (!Helpers::pin_check($request->user()->id, $request->pin))
            return response()->json(['message' => 'PIN is incorrect'], 403); //PIN Check

        //if deny
        if (strtolower($slug) == 'deny') {
            $request_money->type = 'denied';
            $request_money->note = $request->note;
            $request_money->save();

            //send notification
            Helpers::send_transaction_notification($request_money->from_user_id, $request_money->amount, 'denied_money');
            Helpers::send_transaction_notification($request_money->to_user_id, $request_money->amount, 'denied_money');

            return response()->json(['message' => 'success'], 200);
        }

        //if approved
        /** transaction limits check */
        $send_money_limit = Helpers::get_business_settings('customer_send_money_limit');

        if (isset($send_money_limit) && $send_money_limit['status'] == 1) {
            $check = Helpers::check_customer_transaction_limit($request->user(), $request_money->amount, 'send_money', $send_money_limit);
            if (!$check['status']) {
                return response()->json(['message' => translate($check['message'])], 400);
            }
        }

        /** Transaction */
        $customer_transaction = $this->customer_request_money_transaction($request_money->to_user_id, $request_money->from_user_id, $request_money->amount);

        if (is_null($customer_transaction)) return response()->json(['message' => translate('fail')], 501); //if failed

        /** Update Transaction limits data  */
        if (isset($send_money_limit) && $send_money_limit['status'] == 1) {
            $transaction_limit = TransactionLimit::where(['user_id' => $request->user()->id, 'type' => 'send_money'])->first();
            $transaction_limit->user_id = $request->user()->id;
            $transaction_limit->todays_count += 1;
            $transaction_limit->todays_amount += $request_money->amount;
            $transaction_limit->this_months_count += 1;
            $transaction_limit->this_months_amount += $request_money->amount;
            $transaction_limit->type = 'send_money';
            $transaction_limit->updated_at = now();
            $transaction_limit->update();
        }

        //update request money status
        $request_money->type = 'approved';
        $request_money->note = $request->note;
        $request_money->save();

        return response()->json(['message' => 'success', 'transaction_id' => $customer_transaction], 200); //if success
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function add_money(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required',
            'payment_method' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        /** feature active check */
        $add_money_status = Helpers::get_business_settings('add_money_status');

        if (!$add_money_status)
            return response()->json(['message' => translate('add money feature is not activate')], 403);

        if ($request->user()->is_kyc_verified != 1) {
            return response()->json(['message' => translate('Verify your account information')], 403); //kyc check
        }

        //emoney check
        $amount = $request->amount;
        $bonus = Helpers::get_add_money_bonus($amount, $request->user()->id, 'customer');
        $total_amount = $amount + $bonus;

        $admin_emoney = $this->e_money->where('user_id', Helpers::get_admin_id())->first();
        if ($admin_emoney && $total_amount > $admin_emoney->current_balance) {
            return response()->json(['message' => translate('The amount is too big. Please contact with admin')], 403);
        }

        /** transaction limits check */
        $add_money_limit = Helpers::get_business_settings('customer_add_money_limit');

        if (isset($add_money_limit) && $add_money_limit['status'] == 1) {
            $check = Helpers::check_customer_transaction_limit($request->user(), $request['amount'], 'add_money', $add_money_limit);
            if (!$check['status']) {
                return response()->json(['message' => translate($check['message'])], 400);
            }
        }

        $user_id = $request->user()->id;
        $amount = $request->amount;
        $payment_method = $request->payment_method;
        $link = route('payment-mobile', ['user_id' => $user_id, 'amount' => $amount, 'payment_method' => $payment_method]);
        return response()->json(['link' => $link], 200);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function transaction_history(Request $request): array
    {
        $limit = $request->has('limit') ? $request->limit : 10;
        $offset = $request->has('offset') ? $request->offset : 1;

        $transactions = Transaction::where('user_id', $request->user()->id);

        $transactions->when(request('transaction_type') == CASH_IN, function ($q) {
            return $q->where('transaction_type', CASH_IN);
        });
        $transactions->when(request('transaction_type') == CASH_OUT, function ($q) {
            return $q->where('transaction_type', CASH_OUT);
        });
        $transactions->when(request('transaction_type') == SEND_MONEY, function ($q) {
            return $q->where('transaction_type', SEND_MONEY);
        });
        $transactions->when(request('transaction_type') == RECEIVED_MONEY, function ($q) {
            return $q->where('transaction_type', RECEIVED_MONEY);
        });
        $transactions->when(request('transaction_type') == ADD_MONEY, function ($q) {
            return $q->where('transaction_type', ADD_MONEY);
        });

        $transactions = $transactions
            ->customer()
            ->where('transaction_type', '!=', ADMIN_CHARGE)
            ->where('transaction_type', '!=', 'agent_commission')
            ->orderBy("created_at", 'desc')
            ->paginate($limit, ['*'], 'page', $offset);

        $transactions = TransactionResource::collection($transactions);

        return [
            'total_size' => $transactions->total(),
            'limit' => (int)$limit,
            'offset' => (int)$offset,
            'transactions' => $transactions->items()
        ];
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function withdrawal_methods(Request $request): JsonResponse
    {
        $withdrawal_methods = $this->withdrawal_method->latest()->get();
        return response()->json(response_formatter(DEFAULT_200, $withdrawal_methods, null), 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function withdraw(Request $request): JsonResponse
    {
        $validator = Validator::make(
            $request->all(),
            [
                'pin' => 'required|min:4|max:4',
                'amount' => 'required|min:0|not_in:0',
                'note' => 'max:255',
                'withdrawal_method_id' => 'required',
                'withdrawal_method_fields' => 'required',
            ],
            [
                'amount.not_in' => translate('Amount must be greater than zero!'),
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        /** feature active check */
        $withdraw_request_status = Helpers::get_business_settings('withdraw_request_status');

        if (!$withdraw_request_status)
            return response()->json(['message' => translate('withdraw request feature is not activate')], 403);

        if ($request->user()->is_kyc_verified != 1) {
            return response()->json(['message' => translate('Your account is not verified, Complete your account verification')], 403);
        }

        //input fields validation check
        $withdrawal_method = $this->withdrawal_method->find($request->withdrawal_method_id);
        $fields = array_column($withdrawal_method->method_fields, 'input_name');

        $values = (array)json_decode(base64_decode($request->withdrawal_method_fields))[0];

        foreach ($fields as $field) {
            if (!key_exists($field, $values)) {
                return response()->json(response_formatter(DEFAULT_400, $fields, null), 400);
            }
        }

        /** transaction limits check */
        $withdraw_request_limit = Helpers::get_business_settings('customer_withdraw_request_limit');

        if (isset($withdraw_request_limit) && $withdraw_request_limit['status'] == 1) {
            $check = Helpers::check_customer_transaction_limit($request->user(), $request['amount'], 'withdraw_request', $withdraw_request_limit);
            if (!$check['status']) {
                return response()->json(['message' => translate($check['message'])], 400);
            }
        }

        $amount = $request->amount;
        $charge = helpers::get_withdraw_charge($amount);
        $total_amount = $amount + $charge;

        /** DB Operations */
        $withdraw_request = $this->withdraw_request;
        $withdraw_request->user_id = $request->user()->id;
        $withdraw_request->amount = $amount;
        $withdraw_request->admin_charge = $charge;
        $withdraw_request->request_status = 'pending';
        $withdraw_request->is_paid = 0;
        $withdraw_request->sender_note = $request->sender_note;
        $withdraw_request->withdrawal_method_id = $request->withdrawal_method_id;
        $withdraw_request->withdrawal_method_fields = $values;


        $user_emoney = $this->e_money->where('user_id', $request->user()->id)->first();
        if ($user_emoney->current_balance < $total_amount) {
            return response()->json(['message' => translate('Your account do not have enough balance')], 403);
        }

        $user_emoney->current_balance -= $total_amount;
        $user_emoney->pending_balance += $total_amount;

        DB::transaction(function () use ($withdraw_request, $user_emoney) {
            $withdraw_request->save();
            $user_emoney->save();
        });

        /** Update Transaction limits data  */
        if (isset($withdraw_request_limit) && $withdraw_request_limit['status'] == 1) {
            $transaction_limit = TransactionLimit::where(['user_id' => $request->user()->id, 'type' => 'withdraw_request'])->first();
            $transaction_limit->user_id = $request->user()->id;
            $transaction_limit->todays_count += 1;
            $transaction_limit->todays_amount += $request['amount'];
            $transaction_limit->this_months_count += 1;
            $transaction_limit->this_months_amount += $request['amount'];
            $transaction_limit->type = 'withdraw_request';
            $transaction_limit->updated_at = now();
            $transaction_limit->update();
        }

        return response()->json(response_formatter(DEFAULT_STORE_200, null, null), 200);
    }
}
