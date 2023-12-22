<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\helpers;
use App\Exceptions\TransactionFailedException;
use App\Http\Controllers\Controller;
use App\Models\AddMoney;
use App\Models\EMoney;
use App\Models\RequestMoney;
use App\Models\Transaction;
use App\Models\User;
use App\Models\WithdrawalMethod;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use function App\CentralLogics\translate;

class TransactionController extends Controller
{
    public function __construct(
        private EMoney $e_money,
        private RequestMoney $request_money,
        private Transaction $transaction,
        private User $user,
        private WithdrawalMethod $withdrawal_method
    ) {
    }

    /**
     * @param Request $request
     * @return Application|Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request): Factory|\Illuminate\Contracts\View\View|Application
    {
        $trx_type = $request->has('trx_type') ? $request['trx_type'] : 'all';
        $search = $request['search'];
        $query_param = [];
        $key = explode(' ', $request['search']);

        $users = $this->user->where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('id', 'like', "%{$value}%")
                    ->orWhere('phone', 'like', "%{$value}%")
                    ->orWhere('f_name', 'like', "%{$value}%")
                    ->orWhere('l_name', 'like', "%{$value}%")
                    ->orWhere('email', 'like', "%{$value}%");
            }
        })->get()->pluck('id')->toArray();

        $transactions = $this->transaction->when($request->has('search'), function ($q) use ($key, $users) {
            foreach ($key as $value) {
                $q->orWhereIn('from_user_id', $users)
                    ->orWhereIn('to_user_id', $users)
                    ->orWhere('transaction_id', 'like', "%{$value}%")
                    ->orWhere('transaction_type', 'like', "%{$value}%");
            }
        })
            ->when($request['trx_type'] != 'all', function ($query) use ($request) {
                if ($request['trx_type'] == 'debit') {
                    return $query->where('debit', '!=', 0);
                } else {
                    return $query->where('credit', '!=', 0);
                }
            });

        $query_param = ['search' => $search, 'trx_type' => $trx_type];

        $transactions = $transactions->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.transaction.index', compact('transactions', 'search',  'trx_type'));
    }

    /**
     * @param Request $request
     * @return Application|Factory|\Illuminate\Contracts\View\View
     */
    public function request_money(Request $request): Factory|\Illuminate\Contracts\View\View|Application
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);

            $users = $this->user->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('id', 'like', "%{$value}%")
                        ->orWhere('phone', 'like', "%{$value}%")
                        ->orWhere('f_name', 'like', "%{$value}%")
                        ->orWhere('l_name', 'like', "%{$value}%")
                        ->orWhere('email', 'like', "%{$value}%");
                }
            })->get()->pluck('id')->toArray();

            $request_money = $this->request_money->where(function ($q) use ($key, $users) {
                foreach ($key as $value) {
                    $q->orWhereIn('from_user_id', $users)
                        ->orWhere('to_user_id', $users)
                        ->orWhere('type', 'like', "%{$value}%")
                        ->orWhere('amount', 'like', "%{$value}%")
                        ->orWhere('note', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        } else {
            $request_money = $this->request_money;
        }

        if ($request->has('withdrawal_method') && $request->withdrawal_method != 'all') {
            $request_money = $request_money->where('withdrawal_method_id', $request->withdrawal_method);
        }
        $withdrawal_methods = $this->withdrawal_method->get();

        $request_money = $request_money->with('withdrawal_method')->where('to_user_id', Helpers::get_admin_id())->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return View('admin-views.transaction.request_money_list', compact('request_money', 'search', 'withdrawal_methods'));
    }

    /**
     * @param Request $request
     * @param $slug
     * @return RedirectResponse
     * @throws \Exception
     */
    public function request_money_status_change(Request $request, $slug): RedirectResponse
    {
        $request_money = $this->request_money->find($request->id);

        //access check
        if ($request_money->to_user_id != $request->user()->id) {
            Toastr::error(translate('unauthorized request'));
            return back();
        }

        if (strtolower($slug) == 'deny') {
            try {
                $request_money->type = 'denied';
                $request_money->save();
            } catch (\Exception $e) {
                Toastr::error(translate('Something went wrong'));
                return back();
            }

            //send notification
            Helpers::send_transaction_notification($request_money->from_user_id, $request_money->amount, 'denied_money');
            Helpers::send_transaction_notification($request_money->to_user_id, $request_money->amount, 'denied_money');

            Toastr::success(translate('Successfully changed the status'));
            return back();
        } elseif (strtolower($slug) == 'approve') {

            //START TRANSACTION
            DB::beginTransaction();
            $data = [];
            $data['from_user_id'] = $request_money->to_user_id;     //$data['from_user_id'] ##payment perspective##     //$request_money->to_user_id ##request sending perspective##
            $data['to_user_id'] = $request_money->from_user_id;

            try {
                $sendmoney_charge = 0;   //since agent transaction has no change
                $data['user_id'] = $data['from_user_id'];
                $data['type'] = 'debit';
                $data['transaction_type'] = SEND_MONEY;
                $data['ref_trans_id'] = null;
                $data['amount'] = $request_money->amount + $sendmoney_charge;

                if (strtolower($data['type']) == 'debit' && $this->e_money->where('user_id', $data['from_user_id'])->first()->current_balance < $data['amount']) {
                    Toastr::error(translate('Insufficient Balance'));
                    return back();
                }

                $customer_transaction = Helpers::make_transaction($data);

                //send notification
                Helpers::send_transaction_notification($data['user_id'], $data['amount'], $data['transaction_type']);

                if ($customer_transaction == null) {
                    throw new TransactionFailedException('Transaction from sender is failed');
                }

                //customer(receiver) transaction
                $data['user_id'] = $data['to_user_id'];
                $data['type'] = 'credit';
                $data['transaction_type'] = RECEIVED_MONEY;
                $data['ref_trans_id'] = $customer_transaction;
                $data['amount'] = $request_money->amount;
                $agent_transaction = Helpers::make_transaction($data);

                //send notification
                Helpers::send_transaction_notification($data['user_id'], $data['amount'], $data['transaction_type']);

                if ($agent_transaction == null) {
                    throw new TransactionFailedException('Transaction to receiver is failed');
                }

                //request money status update
                $request_money->type = 'approved';
                $request_money->save();

                DB::commit();
            } catch (TransactionFailedException $e) {
                DB::rollBack();
                //return response()->json(['message' => $e->getMessage()], 501);
                Toastr::error(translate('Status change failed'));
                return back();
            }

            Toastr::success(translate('Successfully changed the status'));
            return back();
        } else {
            Toastr::error(translate('Status change failed'));
            return back();
        }
    }

    public function addMoneyRequest()
    {
        try {
            $transactions = AddMoney::with('user')->orderBy('created_at', 'desc')->get();
            return view('admin-views.transaction.request_money_add', compact('transactions'));
        } catch (\Exception $erorr) {
        }
    }
}
