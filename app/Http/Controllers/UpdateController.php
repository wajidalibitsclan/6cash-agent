<?php

namespace App\Http\Controllers;

ini_set('max_execution_time', 180);

use App\CentralLogics\Helpers;
use App\Traits\ActivationClass;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use App\Models\BusinessSetting;
use Mockery\Exception;
use Illuminate\Support\Facades\DB;

class UpdateController extends Controller
{
    use ActivationClass;

    public function update_software_index()
    {
        return view('update.update-software');
    }

    public function update_software(Request $request)
    {
        Helpers::setEnvironmentValue('SOFTWARE_ID', 'MzczNTQxNDc=');
        Helpers::setEnvironmentValue('BUYER_USERNAME', $request['username']);
        Helpers::setEnvironmentValue('PURCHASE_CODE', $request['purchase_key']);
        Helpers::setEnvironmentValue('APP_MODE', 'live');
        Helpers::setEnvironmentValue('SOFTWARE_VERSION', '4.0');
        Helpers::setEnvironmentValue('APP_NAME', '6cash' . time());

        $data = $this->actch();
        try {
            if (!$data->getData()->active) {
                return redirect(base64_decode('aHR0cHM6Ly82YW10ZWNoLmNvbS9zb2Z0d2FyZS1hY3RpdmF0aW9u'));
            }
        } catch (Exception $exception) {
            Toastr::error('verification failed! try again');
            return back();
        }

        Artisan::call('migrate', ['--force' => true]);
        $previousRouteServiceProvier = base_path('app/Providers/RouteServiceProvider.php');
        $newRouteServiceProvier = base_path('app/Providers/RouteServiceProvider.txt');
        copy($newRouteServiceProvier, $previousRouteServiceProvier);
        Artisan::call('cache:clear');
        Artisan::call('view:clear');

        if (!BusinessSetting::where(['key' => 'payment_otp_verification'])->first()) {
            BusinessSetting::insert([
                'key' => 'payment_otp_verification',
                'value' => 1
            ]);
        }
        if (!BusinessSetting::where(['key' => 'hotline_number'])->first()) {
            BusinessSetting::insert([
                'key' => 'hotline_number',
                'value' => '134679'
            ]);
        }
        if (!BusinessSetting::where(['key' => 'merchant_commission_percent'])->first()) {
            BusinessSetting::insert([
                'key' => 'merchant_commission_percent',
                'value' => 10
            ]);
        }
        if (!BusinessSetting::where(['key' => 'payment'])->first()) {
            BusinessSetting::insert([
                'key' => 'payment',
                'value' => '{"status":1,"message":"payment done successfully."}'
            ]);
        }
        if (!BusinessSetting::where(['key' => 'withdraw_charge_percent'])->first()) {
            BusinessSetting::insert([
                'key' => 'withdraw_charge_percent',
                'value' => 5
            ]);
        }
        if (!BusinessSetting::where(['key' => 'add_money_bonus'])->first()) {
            BusinessSetting::insert([
                'key' => 'add_money_bonus',
                'value' => '{"status":1,"message":"Added to your account with bonus."}'
            ]);
        }

        if (!BusinessSetting::where(['key' => 'agent_self_registration'])->first()) {
            DB::table('business_settings')->updateOrInsert(['key' => 'agent_self_registration'], [
                'value' => 1
            ]);
        }

        if (!BusinessSetting::where(['key' => 'maximum_otp_hit'])->first()) {
            DB::table('business_settings')->updateOrInsert(['key' => 'maximum_otp_hit'], [
                'value' => 5
            ]);
        }

        if (!BusinessSetting::where(['key' => 'otp_resend_time'])->first()) {
            DB::table('business_settings')->updateOrInsert(['key' => 'otp_resend_time'], [
                'value' => 60
            ]);
        }

        if (!BusinessSetting::where(['key' => 'temporary_block_time'])->first()) {
            DB::table('business_settings')->updateOrInsert(['key' => 'temporary_block_time'], [
                'value' => 600
            ]);
        }

        if (!BusinessSetting::where(['key' => 'maximum_login_hit'])->first()) {
            DB::table('business_settings')->updateOrInsert(['key' => 'maximum_login_hit'], [
                'value' => 5
            ]);
        }

        if (!BusinessSetting::where(['key' => 'temporary_login_block_time'])->first()) {
            DB::table('business_settings')->updateOrInsert(['key' => 'temporary_login_block_time'], [
                'value' => 600
            ]);
        }

        if (!BusinessSetting::where(['key' => 'add_money_status'])->first()) {
            DB::table('business_settings')->updateOrInsert(['key' => 'add_money_status'], [
                'value' => 1
            ]);
        }

        if (!BusinessSetting::where(['key' => 'send_money_status'])->first()) {
            DB::table('business_settings')->updateOrInsert(['key' => 'send_money_status'], [
                'value' => 1
            ]);
        }

        if (!BusinessSetting::where(['key' => 'cash_out_status'])->first()) {
            DB::table('business_settings')->updateOrInsert(['key' => 'cash_out_status'], [
                'value' => 1
            ]);
        }

        if (!BusinessSetting::where(['key' => 'send_money_request_status'])->first()) {
            DB::table('business_settings')->updateOrInsert(['key' => 'send_money_request_status'], [
                'value' => 1
            ]);
        }
        if (!BusinessSetting::where(['key' => 'withdraw_request_status'])->first()) {
            DB::table('business_settings')->updateOrInsert(['key' => 'withdraw_request_status'], [
                'value' => 1
            ]);
        }

        if (!BusinessSetting::where(['key' => 'linked_website_status'])->first()) {
            DB::table('business_settings')->updateOrInsert(['key' => 'linked_website_status'], [
                'value' => 1
            ]);
        }

        if (!BusinessSetting::where(['key' => 'banner_status'])->first()) {
            DB::table('business_settings')->updateOrInsert(['key' => 'banner_status'], [
                'value' => 1
            ]);
        }

        if (!BusinessSetting::where(['key' => 'customer_add_money_limit'])->first()) {
            DB::table('business_settings')->updateOrInsert(['key' => 'customer_add_money_limit'], [
                'value' => '{"status":0,"transaction_limit_per_day":3,"max_amount_per_transaction":10,"total_transaction_amount_per_day":20,"transaction_limit_per_month":5,"total_transaction_amount_per_month":50}',
            ]);
        }

        if (!BusinessSetting::where(['key' => 'customer_send_money_limit'])->first()) {
            DB::table('business_settings')->updateOrInsert(['key' => 'customer_send_money_limit'], [
                'value' => '{"status":0,"transaction_limit_per_day":3,"max_amount_per_transaction":10,"total_transaction_amount_per_day":20,"transaction_limit_per_month":5,"total_transaction_amount_per_month":50}',
            ]);
        }

        if (!BusinessSetting::where(['key' => 'customer_send_money_request_limit'])->first()) {
            DB::table('business_settings')->updateOrInsert(['key' => 'customer_send_money_request_limit'], [
                'value' => '{"status":0,"transaction_limit_per_day":3,"max_amount_per_transaction":10,"total_transaction_amount_per_day":20,"transaction_limit_per_month":5,"total_transaction_amount_per_month":50}',
            ]);
        }

        if (!BusinessSetting::where(['key' => 'customer_cash_out_limit'])->first()) {
            DB::table('business_settings')->updateOrInsert(['key' => 'customer_cash_out_limit'], [
                'value' => '{"status":0,"transaction_limit_per_day":3,"max_amount_per_transaction":10,"total_transaction_amount_per_day":20,"transaction_limit_per_month":5,"total_transaction_amount_per_month":50}',
            ]);
        }

        if (!BusinessSetting::where(['key' => 'customer_withdraw_request_limit'])->first()) {
            DB::table('business_settings')->updateOrInsert(['key' => 'customer_withdraw_request_limit'], [
                'value' => '{"status":0,"transaction_limit_per_day":3,"max_amount_per_transaction":10,"total_transaction_amount_per_day":20,"transaction_limit_per_month":5,"total_transaction_amount_per_month":50}',
            ]);
        }

        if (!BusinessSetting::where(['key' => 'agent_add_money_limit'])->first()) {
            DB::table('business_settings')->updateOrInsert(['key' => 'agent_add_money_limit'], [
                'value' => '{"status":0,"transaction_limit_per_day":3,"max_amount_per_transaction":10,"total_transaction_amount_per_day":20,"transaction_limit_per_month":5,"total_transaction_amount_per_month":50}',
            ]);
        }

        if (!BusinessSetting::where(['key' => 'agent_send_money_limit'])->first()) {
            DB::table('business_settings')->updateOrInsert(['key' => 'agent_send_money_limit'], [
                'value' => '{"status":0,"transaction_limit_per_day":3,"max_amount_per_transaction":10,"total_transaction_amount_per_day":20,"transaction_limit_per_month":5,"total_transaction_amount_per_month":50}',
            ]);
        }

        if (!BusinessSetting::where(['key' => 'agent_send_money_request_limit'])->first()) {
            DB::table('business_settings')->updateOrInsert(['key' => 'agent_send_money_request_limit'], [
                'value' => '{"status":0,"transaction_limit_per_day":3,"max_amount_per_transaction":10,"total_transaction_amount_per_day":20,"transaction_limit_per_month":5,"total_transaction_amount_per_month":50}',
            ]);
        }

        if (!BusinessSetting::where(['key' => 'agent_withdraw_request_limit'])->first()) {
            DB::table('business_settings')->updateOrInsert(['key' => 'agent_withdraw_request_limit'], [
                'value' => '{"status":0,"transaction_limit_per_day":3,"max_amount_per_transaction":10,"total_transaction_amount_per_day":20,"transaction_limit_per_month":5,"total_transaction_amount_per_month":50}',
            ]);
        }

        return redirect('/admin/auth/login');
    }
}
