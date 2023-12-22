<?php

namespace App\Http\Controllers\Gateway;

use App\CentralLogics\helpers;
use App\Exceptions\TransactionFailedException;
use App\Http\Controllers\Controller;
use App\Models\EMoney;
use App\Traits\TransactionTrait;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SenangPayController extends Controller
{
    use TransactionTrait;

    public function return_senang_pay(Request $request)
    {
        $user_id = session('user_id');
        $amount = session('amount');

        /** ADD Money Transaction */
        $transaction_id = $this->add_money_transaction(Helpers::get_admin_id(), $user_id, $amount);

        //if failed
        if (is_null($transaction_id)) {
            Helpers::add_fund($user_id, $amount, 'senang-pay', null, 'failed');
            return \redirect()->route('payment-fail');
        }

        //if success
        Helpers::add_fund($user_id, $amount, 'senang-pay', null, 'success');

        /** Update Transaction limits data  */
        Helpers::add_money_transaction_limit_update($user_id, $amount);

        return \redirect()->route('payment-success');
    }
}
