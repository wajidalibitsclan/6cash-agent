<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use App\Models\Currency;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class CurrencyExchangeController extends Controller
{
    /* CURRENCIES */
    public function __construct(private Currency $currency, private BusinessSetting $businessSetting)
    {
    }
    public function index()
    {
        try {
            $businessCurrency = $this->businessSetting->where('key', 'currency')->first();
            $currencies = $this->currency->whereNot('currency_code', $businessCurrency->value)->get();
            $baseCurrency = $this->currency->where('currency_code', $businessCurrency->value)->first();

            return view('admin-views.currency.currency-exchange', compact('currencies', 'baseCurrency'));
        } catch (\Exception $error) {
            Toastr::error($error->getMessage());
            return back();
        }
    }

    public function update(Request $request)
    {
        try {
            $currency = $this->currency->where('id', $request->id)->first();
            if (is_null($currency)) {
                Toastr::error(translate('Something went wrong!'));
                return back();
            }

            $currency->update([
                'exchange_rate' => $request->exchange_rate
            ]);
            Toastr::success(translate('Currency Exchange Rate updated successfully!'));
            return back();
        } catch (\Exception $error) {
            Toastr::error($error->getMessage());
            return back();
        }
    }
}
