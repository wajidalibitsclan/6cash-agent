<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\helpers;
use App\Http\Controllers\Controller;
use App\Models\EMoney;
use App\Models\User;
use App\Models\WithdrawalMethod;
use App\Models\WithdrawRequest;
use App\Traits\TransactionTrait;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use OpenSpout\Common\Exception\InvalidArgumentException;
use OpenSpout\Common\Exception\IOException;
use OpenSpout\Common\Exception\UnsupportedTypeException;
use OpenSpout\Writer\Exception\WriterNotOpenedException;
use Rap2hpoutre\FastExcel\FastExcel;
use Symfony\Component\HttpFoundation\StreamedResponse;

class WithdrawController extends Controller
{
    use TransactionTrait;

    public function __construct(
        private WithdrawRequest $withdraw_request,
        private WithdrawalMethod $withdrawal_method,
        private User $user,
        private EMoney $e_money
    )
    {}

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request): Factory|View|Application
    {
        $query_param = [];
        $search = $request['search'];
        $request_status =  $request->has('request_status') ? $request['request_status'] : 'all';;

        $method = $request->withdrawal_method;
        $withdraw_requests = $this->withdraw_request->with('user', 'withdrawal_method')
            ->when($request->has('search'), function ($query) use ($request) {
                $key = explode(' ', $request['search']);

                $user_ids = $this->user->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('id', 'like', "%{$value}%")
                            ->orWhere('phone', 'like', "%{$value}%")
                            ->orWhere('f_name', 'like', "%{$value}%")
                            ->orWhere('l_name', 'like', "%{$value}%")
                            ->orWhere('email', 'like', "%{$value}%");
                    }
                })->get()->pluck('id')->toArray();

               // $query_param = ['search' => $request['search'], 'request_status' => $request['request_status']];
                return $query->whereIn('user_id', $user_ids);
            })
            ->when($request->has('request_status') && $request->request_status != 'all', function ($query) use ($request) {
                return $query->where('request_status', $request->request_status);
            })
            ->when($request->has('withdrawal_method') && $request->withdrawal_method != 'all', function ($query) use ($request) {
                return $query->where('withdrawal_method_id', $request->withdrawal_method);
            });

        $query_param = ['search' => $request['search'], 'request_status' => $request['request_status']];
        $withdraw_requests = $withdraw_requests->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        $withdrawal_methods = $this->withdrawal_method->latest()->get();

        return view('admin-views.withdraw.index', compact('withdraw_requests', 'withdrawal_methods', 'method', 'search', 'request_status'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function status_update(Request $request): RedirectResponse
    {
        $request->validate([
            'request_id' => 'required',
            'request_status' => 'required|in:approve,deny',
        ]);

        $withdraw_request = $this->withdraw_request->with(['user'])->find($request['request_id']);

        if (!isset($withdraw_request->user)) {
            Toastr::error(translate('The request sender is unavailable'));
            return back();
        }

        /** deny */
        if ($request->request_status == 'deny'){
            $account = $this->e_money->where(['user_id' => $withdraw_request->user->id])->first();
            $account->pending_balance -= ($withdraw_request['amount'] + $withdraw_request['admin_charge']);
            $account->current_balance += ($withdraw_request['amount'] + $withdraw_request['admin_charge']);
            $account->save();

            //record in withdraw_requests table
            $withdraw_request->request_status = $request->request_status == 'deny' ? 'denied' : 'approved' ;
            $withdraw_request->is_paid = 0;
            $withdraw_request->admin_note = $request->admin_note ?? null;
            $withdraw_request->save();
        }

        /** approve */
        if ($request->request_status == 'approve')
        {
            $admin = $this->user->with(['emoney'])->where('type', 0)->first();
            if ($admin->emoney->current_balance < ($withdraw_request['amount'] + $withdraw_request['admin_charge'])) {
                Toastr::warning(translate('You do not have enough balance. Please generate eMoney first.'));
                return back();
            }

            $this->accept_withdraw_transaction($withdraw_request->user_id, $withdraw_request['amount'], $withdraw_request['admin_charge']);

            $withdraw_request->request_status = $request->request_status == 'approve' ? 'approved' : 'denied';
            $withdraw_request->is_paid = 1;
            $withdraw_request->admin_note = $request->admin_note ?? null;
            $withdraw_request->save();
        }

        $data = [
            'title' => $request->request_status == 'approve' ? translate('Withdraw_request_accepted') : translate('Withdraw_request_denied'),
            'description' => '',
            'image' => '',
            'order_id'=>'',
        ];
        send_push_notification_to_device($withdraw_request->user->fcm_token, $data);

        Toastr::success(translate('The request has been successfully updated'));
        return back();
    }

    /**
     * @param Request $request
     * @return string|StreamedResponse
     * @throws IOException
     * @throws InvalidArgumentException
     * @throws UnsupportedTypeException
     * @throws WriterNotOpenedException
     */
    public function download(Request $request): StreamedResponse|string
    {
        $withdraw_requests = $this->withdraw_request
            ->with('user', 'withdrawal_method')
            ->when($request->has('search'), function ($query) use ($request) {
                $key = explode(' ', $request['search']);

                $user_ids = $this->user->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('id', 'like', "%{$value}%")
                            ->orWhere('phone', 'like', "%{$value}%")
                            ->orWhere('f_name', 'like', "%{$value}%")
                            ->orWhere('l_name', 'like', "%{$value}%")
                            ->orWhere('email', 'like', "%{$value}%");
                    }
                })->get()->pluck('id')->toArray();

                return $query->whereIn('user_id', $user_ids);
            })
            ->when($request->has('request_status') && $request->request_status != 'all', function ($query) use ($request) {
                return $query->where('request_status', $request->request_status);
            })
            ->when($request->has('withdrawal_method') && $request->withdrawal_method != 'all', function ($query) use ($request) {
                return $query->where('withdrawal_method_id', $request->withdrawal_method);
            })->get();

        $storage = [];

        foreach ($withdraw_requests as $key=>$withdraw_request) {
            if (!is_null($withdraw_request->user) && !is_null($withdraw_request->withdrawal_method_fields)) {

                $field_string = null;
                foreach($withdraw_request->withdrawal_method_fields as $key2=>$item) {
                    $field_string .= $key2 . ':' . $item . ', ';
                }
                $data = [
                    translate('No') => $key+1,
                    translate('UserName') => $withdraw_request->user->f_name . ' ' . $withdraw_request->user->l_name,
                    translate('UserPhone') => $withdraw_request->user->phone,
                    translate('UserEmail') => $withdraw_request->user->email,
                    translate('MethodName') => $withdraw_request->withdrawal_method->method_name??'',
                    translate('Amount') => $withdraw_request->amount,
                    translate('Request Status') => $withdraw_request->request_status,
                    translate('Withdrawal Method Fields') => $field_string
                ];
                $storage[] = $data;
            }
        }

        return (new FastExcel($storage))->download(time() . '-file.xlsx');
    }
}
