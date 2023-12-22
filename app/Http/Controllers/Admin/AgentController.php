<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\EMoney;
use App\Models\Fee;
use App\Models\Transaction;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Stevebauman\Location\Facades\Location;

class AgentController extends Controller
{
    public function __construct(
        private User $user,
        private EMoney $e_money,
        private Transaction $transaction
    ) {
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request): View|Factory|Application
    {
        $ip = env('APP_MODE') == 'live' ? $request->ip() : '61.247.180.82';
        $current_user_info = Location::get($ip);
        return view('admin-views.agent.index', compact('current_user_info'));
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function list(Request $request): Factory|View|Application
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $agents = $this->user->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('f_name', 'like', "%{$value}%")
                        ->orWhere('l_name', 'like', "%{$value}%")
                        ->orWhere('phone', 'like', "%{$value}%")
                        ->orWhere('email', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        } else {
            $agents = $this->user;
        }

        $agents = $agents->agent()->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.agent.list', compact('agents', 'search'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $key = explode(' ', $request['search']);
        $delivery_men = DeliveryMan::where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('f_name', 'like', "%{$value}%")
                    ->orWhere('l_name', 'like', "%{$value}%")
                    ->orWhere('email', 'like', "%{$value}%")
                    ->orWhere('phone', 'like', "%{$value}%")
                    ->orWhere('identity_number', 'like', "%{$value}%");
            }
        })->get();
        return response()->json([
            'view' => view('admin-views.agent.partials._table', compact('delivery_men'))->render()
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'f_name' => 'required',
            'l_name' => 'required',
            'image' => 'mimes:jpeg,jpg,png,gif|max:10000', // max 10000kb
            'email' => '',
            //'phone' => 'required|unique:users|min:8|max:20',
            'phone' => [
                'required',
                Rule::unique('users')->where(function ($query) {
                    return $query->whereNull('deleted_at');
                }),
                'min:5',
                'max:20',
            ],
            'country_code' => 'required',
            'gender' => 'required',
            'occupation' => 'required',
            'password' => 'required|min:4|max:4',
        ], [
            'password.min' => translate('Password must contain 4 characters'),
            'password.max' => translate('Password must contain 4 characters'),
        ]);

        $phone = $request->country_code . $request->phone;
        $agent = $this->user->where(['phone' => $phone])->first();
        if (isset($agent)) {
            Toastr::warning(translate('This phone number is already taken'));
            return back();
        }

        DB::transaction(function () use ($request, $phone) {
            $user = $this->user;
            $user->f_name = $request->f_name;
            $user->l_name = $request->l_name;
            $user->image = Helpers::upload('agent/', 'png', $request->file('image'));
            $user->email = $request->email;
            $user->dial_country_code = $request->country_code;
            $user->phone = $phone;
            $user->gender = $request->gender;
            $user->occupation = $request->occupation;
            $user->password = bcrypt($request->password);
            $user->type = AGENT_TYPE;    //['Admin'=>0, 'Agent'=>1, 'Customer'=>2]
            $user->referral_id = $request->referral_id ?? null;
            $user->save();

            $user->find($user->id);
            $user->unique_id = $user->id . mt_rand(1111, 99999);
            $user->save();

            $emoney = $this->e_money;
            $emoney->user_id = $user->id;
            $emoney->save();
        });

        Toastr::success(translate('Agent Added Successfully!'));
        return redirect(route('admin.agent.list'));
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function edit($id): View|Factory|Application
    {
        $agent = $this->user->find($id);
        return view('admin-views.agent.edit', compact('agent'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return Application|RedirectResponse|Redirector
     */
    public function update(Request $request, $id): Redirector|Application|RedirectResponse
    {
        $request->validate([
            'f_name' => 'required',
            'l_name' => 'required',
            'occupation' => 'required',
            'commission' => 'required',
            'fee' => 'required'
        ]);

        $agent = $this->user->find($id);
        $agent->f_name = $request->f_name;
        $agent->l_name = $request->l_name;
        $agent->image = $request->has('image') ? Helpers::update('agent/', $agent->image, 'png', $request->file('image')) : $agent->image;
        $agent->email = $request->has('email') ? $request->email : $agent->email;
        $agent->gender = $request->has('gender') ? $request->gender : $agent->gender;
        $agent->occupation = $request->occupation;
        $agent->commission = $request->commission;
        $agent->fee = $request->fee;
        if ($request->has('password') && strlen($request->password) > 3) {
            $agent->password = bcrypt($request->password);
        }
        $agent->type = AGENT_TYPE;
        $agent->referral_id = $request->referral_id ?? null;
        $agent->save();

        Toastr::success('Agent updated successfully!');
        return redirect(route('admin.agent.list'));
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function view($id): View|Factory|Application
    {
        $user = $this->user->with('emoney')->find($id);
        return view('admin-views.view.details', compact('user'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return Application|Factory|View
     */
    public function transaction(Request $request, $id): View|Factory|Application
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

            $transactions = $this->transaction->where(function ($q) use ($key, $users) {
                foreach ($key as $value) {
                    $q->orWhereIn('from_user_id', $users)
                        ->orWhere('to_user_id', $users)
                        ->orWhere('transaction_type', 'like', "%{$value}%")
                        ->orWhere('balance', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        } else {
            $transactions = $this->transaction;
        }


        $transactions = $transactions->where('user_id', $id)->latest()->paginate(Helpers::pagination_limit())->appends($query_param);

        $user = $this->user->find($id);
        return view('admin-views.view.transaction', compact('user', 'transactions', 'search'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function status(Request $request): RedirectResponse
    {
        $user = $this->user->find($request->id);
        $user->is_active = !$user->is_active;
        $user->save();
        Toastr::success('Agent status updated!');

        return back();
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function get_kyc_request(Request $request): Factory|View|Application
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $agents = $this->user->where('is_kyc_verified', '!=', 1)->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('f_name', 'like', "%{$value}%")
                        ->orWhere('l_name', 'like', "%{$value}%")
                        ->orWhere('email', 'like', "%{$value}%")
                        ->orWhere('phone', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        } else {
            $agents = $this->user->where('is_kyc_verified', '!=', 1);
        }

        $agents = $agents->orderByDesc('id')->agent()->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.agent.kyc_list', compact('agents', 'search'));
    }

    /**
     * @param $id
     * @param $status
     * @return RedirectResponse
     */
    public function update_kyc_status($id, $status): RedirectResponse
    {
        $user = $this->user->find($id);
        if (!isset($user)) {
            Toastr::error(translate('agent not found'));
            return back();
        }
        $user->is_kyc_verified = in_array($status, [1, 2]) ? $status : $user->is_kyc_verified;
        $user->save();

        $data = [
            'title' => $status == 1 ? translate('verification_request_is_accepted') : translate('verification_request_is_denied'),
            'description' => '',
            'image' => '',
            'order_id' => '',
        ];
        send_push_notification_to_device($user->fcm_token, $data);

        Toastr::success(translate('Successfully updated.'));
        return back();
    }

    /* FEE */
    public function fee()
    {
        $fee = Fee::first();
        return view('admin-views.fee.fee', compact('fee'));
    }
    /* UPDATE FEE */
    public function feeUpdate(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'fee' => 'required'
            ], [
                'fee.required' => translate('Fee is required')
            ]);

            if ($validation->fails()) {
                Toastr::error($validation->getMessageBag());
                return back();
            }

            $fee = Fee::first();
            $fee->update([
                'fee' => $request->fee
            ]);
            //Agent type 1
            User::where('type', 1)->update([
                'fee' => $fee->fee
            ]);

            Toastr::success(translate('Fee updated successfully!'));
            return back();
        } catch (\Exception $error) {
            Toastr::error($error);
            return back();
        }
    }

    /* Commission Status */
    public function commissionStatus(Request $request): RedirectResponse
    {
        try {
            $user = $this->user->find($request->id);
            $user->is_commission_verified = $user->is_commission_verified === 1 ? 0 : 1;
            $user->save();
            Toastr::success(translate('Agent Commission status updated!'));
            return back();
        } catch (\Exception $error) {
            Toastr::error($error);
            return back();
        }
    }

    /* Fee Status */
    public function feeStatus(Request $request): RedirectResponse
    {
        try {
            $user = $this->user->find($request->id);
            $user->is_fee_verified = $user->is_fee_verified === 1 ? 0 : 1;
            $user->save();
            Toastr::success(translate('Agent Fee status updated!'));
            return back();
        } catch (\Exception $error) {
            Toastr::error($error);
            return back();
        }
    }
    /* COMMISSION */
    public function commission($id)
    {
        try {
            $commission = User::where('id', $id)->first();
            if (is_null($commission)) {
                Toastr::error(translate('Something went wrong!'));
                return back();
            }

            if ($commission->is_commission_verified === 0) {
                Toastr::error(translate('Please Commission Activate first.'));
                return back();
            }

            return view('admin-views.fee.commission', compact('commission'));
        } catch (\Exception $error) {
            Toastr::error($error);
            return back();
        }
    }

    /* COMMISSION UPDATE */
    public function commissionUpdate(Request $request)
    {
        try {
            $commission = User::where('id', $request->id)->first();
            $commission->update([
                'commission' => $request->fee
            ]);
            Toastr::success(translate('Commission updated successfully!'));
            return redirect()->route('admin.agent.list');
        } catch (\Exception $error) {
            Toastr::error(translate($error));
            return back();
        }
    }
}
