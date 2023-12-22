<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use App\Models\Currency;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    public function configuration()
    {
        $currency_symbol = Currency::where(['currency_code' => Helpers::currency_code()])->first();

        $language_code = null;
        $languages = Helpers::get_business_settings('language');
        foreach($languages as $language) {
            if($language['default']) {
                $language_code = $language['code'];
            }
        }

        $active_method_list = [];
        $digital_payment_methods = ['ssl_commerz_payment', 'razor_pay', 'paypal', 'stripe', 'senang_pay', 'paystack', 'bkash', 'paymob', 'flutterwave', 'mercadopago'];
        $data = BusinessSetting::whereIn('key', $digital_payment_methods)->get();
        foreach ($data as $d) {
            $value = json_decode($d['value'], true);
            if ($value['status'] == 1) {
                $active_method_list[] = $d['key'];
            }
        }

        return response()->json([
            'company_name' => Helpers::get_business_settings('business_name'),
            'company_logo' => Helpers::get_business_settings('logo'),
            'company_address' => Helpers::get_business_settings('address'),
            'company_phone' => (string)Helpers::get_business_settings('phone'),
            'company_email' => Helpers::get_business_settings('email'),
            'base_urls' => [
                'customer_image_url' => asset('storage/app/public/customer'),
                'agent_image_url' => asset('storage/app/public/agent'),
                'linked_website_image_url' => asset('storage/app/public/website'),
                'purpose_image_url' => asset('storage/app/public/purpose'),
                'notification_image_url' => asset('storage/app/public/notification'),
                'company_image_url' => asset('storage/app/public/business'),
                'banner_image_url' => asset('storage/app/public/banner'),
            ],
            'currency_symbol' => isset($currency_symbol) ? $currency_symbol->currency_symbol : null,
            'currency_position' => Helpers::get_business_settings('currency_symbol_position') ?? 'right',

            'cashout_charge_percent' => (float) Helpers::get_business_settings('cashout_charge_percent'),
            'sendmoney_charge_flat' => (float) Helpers::get_business_settings('sendmoney_charge_flat'),
            'agent_commission_percent' => (float) Helpers::get_business_settings('agent_commission_percent'),
            'withdraw_charge_percent' => (float) Helpers::get_business_settings('withdraw_charge_percent'),
            'admin_commission' => (float) Helpers::get_business_settings('admin_commission'),
            'two_factor' => (integer) Helpers::get_business_settings('two_factor'),
            'country' => Helpers::get_business_settings('country') ?? 'BD',

            'terms_and_conditions' => Helpers::get_business_settings('terms_and_conditions'),
            'privacy_policy' => Helpers::get_business_settings('privacy_policy'),
            'about_us' => Helpers::get_business_settings('about_us'),
            'phone_verification' => Helpers::get_business_settings('phone_verification'),
            'email_verification' => Helpers::get_business_settings('email_verification'),
            'user_app_theme' => (string) Helpers::get_business_settings('app_theme'),
            'software_version' => (string)env('SOFTWARE_VERSION')??null,
            'language_code' => (string)$language_code,
//            'maintenance_mode' => (boolean)Helpers::get_business_settings('maintenance_mode') ?? 0,
            'active_payment_method_list' => $active_method_list,
            'otp_resend_time' => Helpers::get_business_settings('otp_resend_time') ?? 60,
            'agent_self_registration' => Helpers::get_business_settings('agent_self_registration') ?? 1,
            'system_feature' => [
                'add_money_status' => Helpers::get_business_settings('add_money_status') ?? 1,
                'send_money_status' => Helpers::get_business_settings('send_money_status') ?? 1,
                'cash_out_status' => Helpers::get_business_settings('cash_out_status') ?? 1,
                'send_money_request_status' => Helpers::get_business_settings('send_money_request_status') ?? 1,
                'withdraw_request_status' => Helpers::get_business_settings('withdraw_request_status') ?? 1,
                'linked_website_status' => Helpers::get_business_settings('linked_website_status') ?? 1,
                'banner_status' => Helpers::get_business_settings('banner_status') ?? 1,
            ],
            'customer_add_money_limit' => Helpers::get_business_settings('customer_add_money_limit'),
            'customer_send_money_limit' => Helpers::get_business_settings('customer_send_money_limit'),
            'customer_send_money_request_limit' => Helpers::get_business_settings('customer_send_money_request_limit'),
            'customer_cash_out_limit' => Helpers::get_business_settings('customer_cash_out_limit'),
            'customer_withdraw_request_limit' => Helpers::get_business_settings('customer_withdraw_request_limit'),
            'agent_add_money_limit' => Helpers::get_business_settings('agent_add_money_limit'),
            'agent_send_money_limit' => Helpers::get_business_settings('agent_send_money_limit'),
            'agent_send_money_request_limit' => Helpers::get_business_settings('agent_send_money_request_limit'),
            'agent_withdraw_request_limit' => Helpers::get_business_settings('agent_withdraw_request_limit'),
        ]);
    }
}
