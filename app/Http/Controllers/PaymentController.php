<?php

namespace App\Http\Controllers;

//use App\Model\Order;
use App\Models\EMoney;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class PaymentController extends Controller
{
    public function __construct(
        private EMoney $e_money,
        private User $user
    ) {
    }

    /**
     * @param Request $request
     * @return Application|Factory|View|JsonResponse
     */
    public function payment(Request $request)
    {
        $unused_balance = $this->e_money->with('user')->whereHas('user', function ($q) {
            $q->where('type', '=', ADMIN_TYPE);
        })->sum('current_balance');

        if ($request->amount > $unused_balance) {
            Toastr::error(translate('The requested amount is too big'));
        }

        if (session()->has('payment_method') == false) {
            session()->put('payment_method', 'ssl_commerz_payment');
        }

        session()->put('amount', $request->amount);
        session()->put('user_id', $request['user_id']);

        $user = $this->user->where('type', '!=', 0)->find($request['user_id']);

        if (isset($user)) {
            return view('payment-view', ['payment_method' => $request['payment_method']]);
        }

        return response()->json(['errors' => ['code' => 'order-payment', 'message' => 'Data not found']], 403);
    }


    public function paymentAgent(Request $request)
    {
        $unused_balance = $this->e_money->with('user')->whereHas('user', function ($q) {
            $q->where('type', '=', ADMIN_TYPE);
        })->sum('current_balance');

        if ($request->amount > $unused_balance) {
            Toastr::error(translate('The requested amount is too big'));
        }

        if (session()->has('payment_method') == false) {
            session()->put('payment_method', 'ssl_commerz_payment');
        }

        session()->put('amount', $request->amount);
        session()->put('user_id', $request['user_id']);

        $user = $this->user->where('type', '!=', 0)->find($request['user_id']);

        if (isset($user)) {
            return view('payment-view', ['payment_method' => $request['payment_method']]);
        }

        return response()->json(['errors' => ['code' => 'order-payment', 'message' => 'Data not found']], 403);
    }




    /**
     * @return Application|JsonResponse|RedirectResponse|Redirector
     */
    public function success(): JsonResponse|Redirector|RedirectResponse|Application
    {
        if (session()->has('callback')) {
            return redirect(session('callback') . '/success');
        }

        // return response()->json(['message' => 'Payment succeeded'], 200);
        return redirect()->route('success.payment');
    }

    /**
     * @return Application|JsonResponse|RedirectResponse|Redirector
     */
    public function fail(): JsonResponse|Redirector|RedirectResponse|Application
    {
        if (session()->has('callback')) {
            return redirect(session('callback') . '/fail');
        }

        return redirect()->route('fail-payment.page');
        // return response()->json(['message' => 'Payment failed'], 403);
    }
}
