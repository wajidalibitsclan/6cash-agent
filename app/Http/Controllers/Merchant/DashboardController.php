<?php

namespace App\Http\Controllers\Merchant;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\EMoney;
use App\Models\User;
use App\Models\WithdrawalMethod;
use App\Models\WithdrawRequest;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        private EMoney $e_money,
        private User $user,
        private WithdrawalMethod $withdrawal_method,
        private WithdrawRequest $withdraw_request
    ){}

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function dashboard(Request $request): Factory|View|Application
    {
        $balance = self::get_balance_stat();

        $query_param = [];
        $withdraw_requests = $this->withdraw_request->with('withdrawal_method')
            ->when($request->has('withdrawal_method') && $request->withdrawal_method != 'all', function ($query) use ($request) {
                return $query->where('withdrawal_method_id', $request->withdrawal_method);
            })
            ->where('user_id', auth()->user()->id)
            ->latest()
            ->paginate(Helpers::pagination_limit())
            ->appends($query_param);

        $method = $request->withdrawal_method;
        $withdrawal_methods = $this->withdrawal_method->latest()->get();

        return view('merchant-views.dashboard', compact('balance', 'withdraw_requests', 'withdrawal_methods', 'method'));
    }

    /**
     * @return array
     */
    public function get_balance_stat(): array
    {
        $current_balance = $this->e_money->where('user_id', auth()->user()->id)->sum('current_balance');
        $pending_balance = $this->e_money->where('user_id', auth()->user()->id)->sum('pending_balance');
        $total_withdraw = $this->withdraw_request->where('user_id', auth()->user()->id)
            ->where(['is_paid' => 1, 'request_status' => 'approved'])
            ->sum(\DB::raw('amount + admin_charge'));

        $balance = [];
        $balance['current_balance'] = $current_balance;
        $balance['pending_balance'] = $pending_balance;
        $balance['total_withdraw'] = $total_withdraw;

        return $balance;
    }

    /**
     * @return Application|Factory|View
     */
    public function settings(): Factory|View|Application
    {
        return view('merchant-views.settings');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function settings_update(Request $request): RedirectResponse
    {
        $request->validate([
            'f_name' => 'required',
            'l_name' => 'required',
        ]);

        $merchant = $this->user->find(auth('user')->id());
        $merchant->f_name = $request->f_name;
        $merchant->l_name = $request->l_name;
        $merchant->email = $request->email;
        $merchant->image = $request->has('image') ? Helpers::update('merchant/', $merchant->image, 'png', $request->file('image')) : $merchant->image;
        $merchant->save();
        Toastr::success('Merchant updated successfully!');
        return back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function settings_password_update(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => 'required|same:confirm_password|max:20|min:8',
            'confirm_password' => 'required',
        ]);
        $merchant = $this->user->find(auth('user')->id());
        $merchant->password = bcrypt($request['password']);
        $merchant->save();
        Toastr::success('Merchant password updated successfully!');
        return back();
    }
}
