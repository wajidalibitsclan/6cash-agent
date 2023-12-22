<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Order;
use App\Models\EMoney;
use App\Models\Transaction;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct(
        private User $user,
        private EMoney $e_money,
        private Transaction $transaction
    ){}

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function dashboard(Request $request)
    {
        $data = [];

        //top agents
        $top_agents = $this->transaction->with('user')
            ->agent()
            ->select(['user_id', DB::raw("(SUM(debit) + SUM(credit)) as total_transaction")])
            ->orderBy("total_transaction", 'desc')
            ->groupBy('user_id')
            ->take(6)
            ->get();

        //top customers
        $top_customers = $this->transaction->with('user')
            ->customer()
            ->select(['user_id', DB::raw("(SUM(debit) + SUM(credit)) as total_transaction")])
            ->orderBy("total_transaction", 'desc')
            ->groupBy('user_id')
            ->take(4)
            ->get();

        //top transactions
        $top_transactions = $this->transaction->with('user')
            ->notAdmin()
            ->where('ref_trans_id', null)
            ->select(['user_id', DB::raw("(SUM(debit) + SUM(credit)) as total_transaction")])
            ->orderBy("total_transaction", 'desc')
            ->groupBy('user_id')
            ->take(20)
            ->get();

        $data['top_agents'] = $top_agents;
        $data['top_customers'] = $top_customers;
        $data['top_transactions'] = $top_transactions;

        //balance
        $balance = self::get_balance_stat();


        $transaction = [];
        for ($i=1;$i<=12;$i++){
            $from = date('Y-'.$i.'-01');
            $to = date('Y-'.$i.'-30');
            $transaction[$i] = $this->transaction->where(['ref_trans_id' => 0])
                                ->whereBetween('created_at', [$from, $to])
                                ->select([DB::raw("SUM(debit) as total_credit")])
                                ->orderBy("total_credit", 'desc')
                                ->groupBy('user_id')
                                ->first()->total_credit??0;
        }

        return view('admin-views.dashboard', compact('balance', 'transaction', 'data'));
    }

    /**
     * @return array
     */
    public function get_balance_stat(): array
    {
        $used_balance = $this->e_money->with('user')
            ->where('user_id', '!=', Helpers::get_admin_id())
            ->sum('current_balance');

        $unused_balance = $this->e_money->with('user')
            ->where('user_id', Helpers::get_admin_id())
            ->sum('current_balance');

        $total_balance = $this->transaction->where('user_id', Helpers::get_admin_id())->where('transaction_type', CASH_IN)->sum('credit');
        $charge_earned = $this->e_money->with('user')->where('user_id', Auth::id())->first()->charge_earned ?? 0;
        $pending_balance = $this->e_money->with('user')->sum('pending_balance');

        $balance = [];
        $balance['total_balance'] = $total_balance;
        $balance['used_balance'] = $used_balance + $pending_balance;
        $balance['unused_balance'] = $unused_balance;
        $balance['total_earned'] = $charge_earned;

        return $balance;
    }

    /**
     * @return Application|Factory|View
     */
    public function settings(): View|Factory|Application
    {
        return view('admin-views.settings');
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
            'phone' => 'required',
        ]);

        $admin = $this->user->find(auth('user')->id());
        $admin->f_name = $request->f_name;
        $admin->l_name = $request->l_name;
        $admin->email = $request->email;
        $admin->phone = $request->phone;
        $admin->image = $request->has('image') ? Helpers::update('admin/', $admin->image, 'png', $request->file('image')) : $admin->image;
        $admin->save();
        Toastr::success('Admin updated successfully!');
        return back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function settings_password_update(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => 'required|same:confirm_password|min:8',
            'confirm_password' => 'required',
        ]);
        $admin = $this->user->find(auth('user')->id());
        $admin->password = bcrypt($request['password']);
        $admin->save();
        Toastr::success('Admin password updated successfully!');
        return back();
    }
}
