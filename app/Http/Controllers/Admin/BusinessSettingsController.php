<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use App\Models\LinkedWebsite;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BusinessSettingsController extends Controller
{
    public function __construct(
        private BusinessSetting $business_setting,
        private LinkedWebsite $linked_website
    ){}

    /**
     * @return Application|Factory|View
     */
    public function business_index(): Factory|View|Application
    {
        return view('admin-views.business-settings.business-index');
    }

    /**
     * @return Application|Factory|View
     */
    public function charge_setup_index(): Factory|View|Application
    {
        return view('admin-views.business-settings.charge-setup-index');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function business_setup(Request $request): RedirectResponse
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('update_option_is_disable_for_demo'));
            return back();
        }

        DB::table('business_settings')->updateOrInsert(['key' => 'business_name'], [
            'value' => $request['restaurant_name']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'currency'], [
            'value' => $request['currency']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'pagination_limit'], [
            'value' => $request['pagination_limit']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'timezone'], [
            'value' => $request['timezone']
        ]);

        $curr_logo = $this->business_setting->where(['key' => 'logo'])->first() ?? '';
        if ($request->has('logo')) {
            $image_name = Helpers::update('business/', $curr_logo->value ?? '', 'png', $request->file('logo'));
        } else {
            $image_name = $curr_logo['value'] ?? '';
        }

        DB::table('business_settings')->updateOrInsert(['key' => 'logo'], [
            'value' => $image_name
        ]);

        /** Favicon */
        $curr_favicon = helpers::get_business_settings('favicon');
        if ($request->has('favicon')) {
            $favicon_name = Helpers::update('favicon/', $curr_favicon ?? '', 'png', $request->file('favicon'));
        } else {
            $favicon_name = $curr_favicon ?? '';
        }

        DB::table('business_settings')->updateOrInsert(['key' => 'favicon'], [
            'value' => $favicon_name
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'phone'], [
            'value' => $request['phone']
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'hotline_number'], [
            'value' => $request['hotline_number']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'email'], [
            'value' => $request['email']
        ]);


        DB::table('business_settings')->updateOrInsert(['key' => 'inactive_auth_minute'], [
            'value' => $request['inactive_auth_minute']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'two_factor'], [
            'value' => $request['two_factor']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'phone_verification'], [
            'value' => $request['phone_verification']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'email_verification'], [
            'value' => $request['email_verification']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'refer_commission'], [
            'value' => $request['refer_commission']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'address'], [
            'value' => $request['address']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'footer_text'], [
            'value' => $request['footer_text']
        ]);


        DB::table('business_settings')->updateOrInsert(['key' => 'currency_symbol_position'], [
            'value' => $request['currency_symbol_position']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'admin_commission'], [
            'value' => $request['admin_commission']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'country'], [
            'value' => $request['country']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'agent_self_registration'], [
            'value' => $request['agent_self_registration']
        ]);

        Toastr::success(translate('successfully_updated_to_changes_restart_the_app'));
        return back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function charge_setup_update(Request $request): RedirectResponse
    {
        DB::table('business_settings')->updateOrInsert(['key' => 'agent_commission_percent'], [
            'value' => $request['agent_commission_percent']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'cashout_charge_percent'], [
            'value' => $request['cashout_charge_percent']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'sendmoney_charge_flat'], [
            'value' => $request['sendmoney_charge_flat']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'withdraw_charge_percent'], [
            'value' => $request['withdraw_charge_percent']
        ]);

        Toastr::success(translate('successfully_updated_to_changes_restart_the_app'));
        return back();
    }

    /**
     * @return Application|Factory|View
     */
    public function payment_index(): Factory|View|Application
    {
        return view('admin-views.business-settings.payment-index');
    }

    /**
     * @param Request $request
     * @param $name
     * @return RedirectResponse
     */
    public function payment_update(Request $request, $name): RedirectResponse
    {
        if ($name == 'cash_on_delivery') {
            $payment = $this->business_setting->where('key', 'cash_on_delivery')->first();
            if (!isset($payment)) {
                DB::table('business_settings')->insert([
                    'key' => 'cash_on_delivery',
                    'value' => json_encode([
                        'status' => $request['status'],
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('business_settings')->where(['key' => 'cash_on_delivery'])->update([
                    'key' => 'cash_on_delivery',
                    'value' => json_encode([
                        'status' => $request['status'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'digital_payment') {
            $payment = $this->business_setting->where('key', 'digital_payment')->first();
            if (!isset($payment)) {
                DB::table('business_settings')->insert([
                    'key' => 'digital_payment',
                    'value' => json_encode([
                        'status' => $request['status'],
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('business_settings')->where(['key' => 'digital_payment'])->update([
                    'key' => 'digital_payment',
                    'value' => json_encode([
                        'status' => $request['status'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'ssl_commerz_payment') {
            $payment = $this->business_setting->where('key', 'ssl_commerz_payment')->first();
            if (!isset($payment)) {
                DB::table('business_settings')->insert([
                    'key' => 'ssl_commerz_payment',
                    'value' => json_encode([
                        'status' => 1,
                        'store_id' => '',
                        'store_password' => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('business_settings')->where(['key' => 'ssl_commerz_payment'])->update([
                    'key' => 'ssl_commerz_payment',
                    'value' => json_encode([
                        'status' => $request['status'],
                        'store_id' => $request['store_id'],
                        'store_password' => $request['store_password'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'razor_pay') {
            $payment = $this->business_setting->where('key', 'razor_pay')->first();
            if (!isset($payment)) {
                DB::table('business_settings')->insert([
                    'key' => 'razor_pay',
                    'value' => json_encode([
                        'status' => 1,
                        'razor_key' => '',
                        'razor_secret' => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('business_settings')->where(['key' => 'razor_pay'])->update([
                    'key' => 'razor_pay',
                    'value' => json_encode([
                        'status' => $request['status'],
                        'razor_key' => $request['razor_key'],
                        'razor_secret' => $request['razor_secret'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'paypal') {
            $payment = $this->business_setting->where('key', 'paypal')->first();
            if (!isset($payment)) {
                DB::table('business_settings')->insert([
                    'key' => 'paypal',
                    'value' => json_encode([
                        'status' => 1,
                        'paypal_client_id' => '',
                        'paypal_secret' => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('business_settings')->where(['key' => 'paypal'])->update([
                    'key' => 'paypal',
                    'value' => json_encode([
                        'status' => $request['status'],
                        'paypal_client_id' => $request['paypal_client_id'],
                        'paypal_secret' => $request['paypal_secret'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'stripe') {
            $payment = $this->business_setting->where('key', 'stripe')->first();
            if (!isset($payment)) {
                DB::table('business_settings')->insert([
                    'key' => 'stripe',
                    'value' => json_encode([
                        'status' => 1,
                        'api_key' => '',
                        'published_key' => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('business_settings')->where(['key' => 'stripe'])->update([
                    'key' => 'stripe',
                    'value' => json_encode([
                        'status' => $request['status'],
                        'api_key' => $request['api_key'],
                        'published_key' => $request['published_key'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'senang_pay') {
            $payment = $this->business_setting->where('key', 'senang_pay')->first();
            if (!isset($payment)) {
                DB::table('business_settings')->insert([
                    'key' => 'senang_pay',
                    'value' => json_encode([
                        'status' => 1,
                        'secret_key' => '',
                        'merchant_id' => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('business_settings')->where(['key' => 'senang_pay'])->update([
                    'key' => 'senang_pay',
                    'value' => json_encode([
                        'status' => $request['status'],
                        'secret_key' => $request['secret_key'],
                        'merchant_id' => $request['merchant_id'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'paystack') {
            $payment = $this->business_setting->where('key', 'paystack')->first();
            if (!isset($payment)) {
                DB::table('business_settings')->insert([
                    'key' => 'paystack',
                    'value' => json_encode([
                        'status' => 1,
                        'publicKey' => '',
                        'secretKey' => '',
                        'paymentUrl' => '',
                        'merchantEmail' => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                DB::table('business_settings')->where(['key' => 'paystack'])->update([
                    'key' => 'paystack',
                    'value' => json_encode([
                        'status' => $request['status'],
                        'publicKey' => $request['publicKey'],
                        'secretKey' => $request['secretKey'],
                        'paymentUrl' => $request['paymentUrl'],
                        'merchantEmail' => $request['merchantEmail'],
                    ]),
                    'updated_at' => now()
                ]);
            }
        } else if ($name == 'internal_point') {
            $payment = $this->business_setting->where('key', 'internal_point')->first();
            if (!isset($payment)) {
                DB::table('business_settings')->insert([
                    'key' => 'internal_point',
                    'value' => json_encode([
                        'status' => $request['status'],
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('business_settings')->where(['key' => 'internal_point'])->update([
                    'key' => 'internal_point',
                    'value' => json_encode([
                        'status' => $request['status'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } else if ($name == 'bkash') {
            DB::table('business_settings')->updateOrInsert(['key' => 'bkash'], [
                'value' => json_encode([
                    'status' => $request['status'],
                    'api_key' => $request['api_key'],
                    'api_secret' => $request['api_secret'],
                    'username' => $request['username'],
                    'password' => $request['password'],
                ])
            ]);
        } else if ($name == 'paymob') {
            DB::table('business_settings')->updateOrInsert(['key' => 'paymob'], [
                'value' => json_encode([
                    'status' => $request['status'],
                    'api_key' => $request['api_key'],
                    'iframe_id' => $request['iframe_id'],
                    'integration_id' => $request['integration_id'],
                    'hmac' => $request['hmac']
                ])
            ]);
        } else if ($name == 'flutterwave') {
            DB::table('business_settings')->updateOrInsert(['key' => 'flutterwave'], [
                'value' => json_encode([
                    'status' => $request['status'],
                    'public_key' => $request['public_key'],
                    'secret_key' => $request['secret_key'],
                    'hash' => $request['hash']
                ])
            ]);
        } else if ($name == 'mercadopago') {
            DB::table('business_settings')->updateOrInsert(['key' => 'mercadopago'], [
                'value' => json_encode([
                    'status' => $request['status'],
                    'public_key' => $request['public_key'],
                    'access_token' => $request['access_token']
                ])
            ]);
        }

        Toastr::success(translate('payment settings updated!'));
        return back();
    }

    /**
     * @return Application|Factory|View
     */
    public function fcm_index(): View|Factory|Application
    {
        return view('admin-views.business-settings.fcm-index');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function update_fcm(Request $request): RedirectResponse
    {
        DB::table('business_settings')->updateOrInsert(['key' => 'push_notification_key'], [
            'value' => $request['push_notification_key']
        ]);

        Toastr::success(translate('settings_updated'));
        return back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function update_fcm_messages(Request $request): RedirectResponse
    {
        DB::table('business_settings')->updateOrInsert(['key' => 'money_transfer_message'], [
            'value' => json_encode([
                'status' => $request['money_transfer_status'] == 1 ? 1 : 0,
                'message' => $request['money_transfer_message']
            ])
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => CASH_IN], [
            'value' => json_encode([
                'status' => $request['cash_in_status'] == 1 ? 1 : 0,
                'message' => $request['cash_in_message']
            ])
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => CASH_OUT], [
            'value' => json_encode([
                'status' => $request['cash_out_status'] == 1 ? 1 : 0,
                'message' => $request['cash_out_message']
            ])
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => SEND_MONEY], [
            'value' => json_encode([
                'status' => $request['send_money_status'] == 1 ? 1 : 0,
                'message' => $request['send_money_message']
            ])
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'request_money'], [
            'value' => json_encode([
                'status' => $request['request_money_status'] == 1 ? 1 : 0,
                'message' => $request['request_money_message']
            ])
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'denied_money'], [
            'value' => json_encode([
                'status' => $request['denied_money_status'] == 1 ? 1 : 0,
                'message' => $request['denied_money_message']
            ])
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'approved_money'], [
            'value' => json_encode([
                'status' => $request['approved_money_status'] == 1 ? 1 : 0,
                'message' => $request['approved_money_message']
            ])
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => ADD_MONEY], [
            'value' => json_encode([
                'status' => $request['add_money_status'] == 1 ? 1 : 0,
                'message' => $request['add_money_message']
            ])
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => ADD_MONEY_BONUS], [
            'value' => json_encode([
                'status' => $request['add_money_bonus_status'] == 1 ? 1 : 0,
                'message' => $request['add_money_bonus_message']
            ])
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => RECEIVED_MONEY], [
            'value' => json_encode([
                'status' => $request['received_money_status'] == 1 ? 1 : 0,
                'message' => $request['received_money_message']
            ])
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => PAYMENT], [
            'value' => json_encode([
                'status' => $request['payment_money_status'] == 1 ? 1 : 0,
                'message' => $request['payment_money_message']
            ])
        ]);

        Toastr::success(translate('message_updated'));
        return back();
    }

    //linked website

    /**
     * @return Application|Factory|View
     */
    public function linked_website(): Factory|View|Application
    {
        $linked_websites = $this->linked_website->latest()->paginate(Helpers::pagination_limit());
        return view('admin-views.linked-website.index', compact('linked_websites'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function linked_website_add(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'url' => 'required',
            'image' => 'required',
        ]);

        $linked_websites = $this->linked_website;
        $linked_websites->name = $request->name;
        $linked_websites->url = $request->url;
        $linked_websites->status = 1;
        $linked_websites->image = Helpers::upload('website/', 'png', $request->file('image'));
        $linked_websites->save();

        Toastr::success(translate('Added Successfully!'));
        return back();
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function linked_website_edit($id): Factory|View|Application
    {
        $linked_website = $this->linked_website->find($id);
        return view('admin-views.linked-website.edit', compact('linked_website'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function linked_website_update(Request $request): RedirectResponse
    {
        $linked_websites = $this->linked_website->find($request->id);
        $linked_websites->name = $request->name;
        $linked_websites->url = $request->url;
        $linked_websites->status = 1;
        $linked_websites->image = $request->has('image') ? Helpers::upload('website/', 'png', $request->file('image')) : $linked_websites->image;
        $linked_websites->save();

        Toastr::success(translate('Updated Successfully!'));
        return back();
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    PUBLIC FUNCTION linked_website_status($id): RedirectResponse
    {
        $linked_websites = $this->linked_website->find($id);
        $linked_websites->status = !$linked_websites->status;
        $linked_websites->save();

        Toastr::success(translate('Status Updated Successfully!'));
        return back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function linked_website_delete(Request $request): RedirectResponse
    {
        $linked_website = $this->linked_website->find($request->id);
        if (Storage::disk('public')->exists('banner/' . $linked_website['image'])) {
            Storage::disk('public')->delete('banner/' . $linked_website['image']);
        }
        $linked_website->delete();

        Toastr::success(translate('Website removed!'));
        return back();
    }

    //recaptcha

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function recaptcha_index(Request $request): Factory|View|Application
    {
        return view('admin-views.business-settings.recaptcha-index');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function recaptcha_update(Request $request): RedirectResponse
    {
        DB::table('business_settings')->updateOrInsert(['key' => 'recaptcha'], [
            'key' => 'recaptcha',
            'value' => json_encode([
                'status' => $request['status'],
                'site_key' => $request['site_key'],
                'secret_key' => $request['secret_key']
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);


        Toastr::success('Updated Successfully');
        return back();
    }

    //app settings

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function app_settings(Request $request): Factory|View|Application
    {
        return view('admin-views.business-settings.app-setting-index');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function app_setting_update(Request $request): RedirectResponse
    {
        DB::table('business_settings')->updateOrInsert(['key' => 'app_theme'], [
            'value' => $request['theme']
        ]);

        Toastr::success('App theme Updated Successfully');
        return back();
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function merchant_payment_otp_index(Request $request): Factory|View|Application
    {
        return view('admin-views.business-settings.merchant-payment-otp-index');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function merchant_payment_otp_update(Request $request): RedirectResponse
    {
        DB::table('business_settings')->updateOrInsert(['key' => 'payment_otp_verification'], [
            'value' => $request['payment_otp_verification']
        ]);

        Toastr::success('Updated Successfully');
        return back();
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function merchant_settings_index(Request $request): Factory|View|Application
    {
        return view('admin-views.business-settings.merchant-settings-index');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function merchant_settings_update(Request $request): RedirectResponse
    {
        DB::table('business_settings')->updateOrInsert(['key' => 'merchant_commission_percent'], [
            'value' => $request['merchant_commission_percent']
        ]);

        Toastr::success('Settings updated');
        return back();
    }

    public function otp_setup(): Factory|View|Application
    {
        return view('admin-views.business-settings.otp-setup');
    }

    public function otp_setup_update(Request $request): RedirectResponse
    {
        DB::table('business_settings')->updateOrInsert(['key' => 'maximum_otp_hit'], [
            'value' => $request['maximum_otp_hit'],
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'otp_resend_time'], [
            'value' => $request['otp_resend_time'],
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'temporary_block_time'], [
            'value' => $request['temporary_block_time'],
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'maximum_login_hit'], [
            'value' => $request['maximum_login_hit'],
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'temporary_login_block_time'], [
            'value' => $request['temporary_login_block_time'],
        ]);

        Toastr::success(translate('Settings updated!'));
        return back();
    }

    public function system_feature(): Factory|View|Application
    {
        return view('admin-views.business-settings.system-feature');
    }

    public function system_feature_update(Request $request): RedirectResponse
    {
        DB::table('business_settings')->updateOrInsert(['key' => 'add_money_status'], [
            'value' => $request['add_money_status'],
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'send_money_status'], [
            'value' => $request['send_money_status'],
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'cash_out_status'], [
            'value' => $request['cash_out_status'],
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'send_money_request_status'], [
            'value' => $request['send_money_request_status'],
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'withdraw_request_status'], [
            'value' => $request['withdraw_request_status'],
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'linked_website_status'], [
            'value' => $request['linked_website_status'],
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'banner_status'], [
            'value' => $request['banner_status'],
        ]);

        Toastr::success(translate('Settings updated!'));
        return back();
    }

    public function customer_transaction_limits_index(): Factory|View|Application
    {
        return view('admin-views.business-settings.customer-transaction-limits-index');
    }

    public function agent_transaction_limits_index(): Factory|View|Application
    {
        return view('admin-views.business-settings.agent-transaction-limits-index');
    }

    public function transaction_limits_update(Request $request, $name): RedirectResponse
    {
        $transaction_limit_per_day = (int)$request['transaction_limit_per_day'];
        $max_amount_per_transaction = (float)$request['max_amount_per_transaction'];
        $total_transaction_amount_per_day = (float)$request['total_transaction_amount_per_day'];
        $transaction_limit_per_month = (int)$request['transaction_limit_per_month'];
        $total_transaction_amount_per_month = (float)$request['total_transaction_amount_per_month'];

        if ($transaction_limit_per_day > $transaction_limit_per_month) {
            Toastr::error(translate('Transaction limit per day cannot be greater than the transaction limit per month.'));
            return back();
        }

        if ($max_amount_per_transaction > $total_transaction_amount_per_day) {
            Toastr::error(translate('Maximum amount per transaction cannot be greater than the total transaction amount per day.'));
            return back();
        }

        if ($total_transaction_amount_per_day > $total_transaction_amount_per_month) {
            Toastr::error(translate('Total transaction amount per day cannot be greater than the total transaction amount per month.'));
            return back();
        }

        if ($name == 'customer_add_money_limit') {
            DB::table('business_settings')->updateOrInsert(['key' => 'customer_add_money_limit'], [
                'value' => json_encode([
                    'status' => (int)$request['status'],
                    'transaction_limit_per_day' => (int)$request['transaction_limit_per_day'],
                    'max_amount_per_transaction' => (float)$request['max_amount_per_transaction'],
                    'total_transaction_amount_per_day' => (float)$request['total_transaction_amount_per_day'],
                    'transaction_limit_per_month' => (int)$request['transaction_limit_per_month'],
                    'total_transaction_amount_per_month' => (float)$request['total_transaction_amount_per_month']
                ])
            ]);

        } elseif ($name == 'customer_send_money_limit') {
            DB::table('business_settings')->updateOrInsert(['key' => 'customer_send_money_limit'], [
                'value' => json_encode([
                    'status' => (int)$request['status'],
                    'transaction_limit_per_day' => (int)$request['transaction_limit_per_day'],
                    'max_amount_per_transaction' => (float)$request['max_amount_per_transaction'],
                    'total_transaction_amount_per_day' => (float)$request['total_transaction_amount_per_day'],
                    'transaction_limit_per_month' => (int)$request['transaction_limit_per_month'],
                    'total_transaction_amount_per_month' => (float)$request['total_transaction_amount_per_month']
                ])
            ]);
        }elseif ($name == 'customer_cash_out_limit') {
            DB::table('business_settings')->updateOrInsert(['key' => 'customer_cash_out_limit'], [
                'value' => json_encode([
                    'status' => (int)$request['status'],
                    'transaction_limit_per_day' => (int)$request['transaction_limit_per_day'],
                    'max_amount_per_transaction' => (float)$request['max_amount_per_transaction'],
                    'total_transaction_amount_per_day' => (float)$request['total_transaction_amount_per_day'],
                    'transaction_limit_per_month' => (int)$request['transaction_limit_per_month'],
                    'total_transaction_amount_per_month' => (float)$request['total_transaction_amount_per_month']
                ])
            ]);
        }elseif ($name == 'customer_send_money_request_limit') {
            DB::table('business_settings')->updateOrInsert(['key' => 'customer_send_money_request_limit'], [
                'value' => json_encode([
                    'status' => (int)$request['status'],
                    'transaction_limit_per_day' => (int)$request['transaction_limit_per_day'],
                    'max_amount_per_transaction' => (float)$request['max_amount_per_transaction'],
                    'total_transaction_amount_per_day' => (float)$request['total_transaction_amount_per_day'],
                    'transaction_limit_per_month' => (int)$request['transaction_limit_per_month'],
                    'total_transaction_amount_per_month' => (float)$request['total_transaction_amount_per_month']
                ])
            ]);
        }elseif ($name == 'customer_withdraw_request_limit') {
            DB::table('business_settings')->updateOrInsert(['key' => 'customer_withdraw_request_limit'], [
                'value' => json_encode([
                    'status' => (int)$request['status'],
                    'transaction_limit_per_day' => (int)$request['transaction_limit_per_day'],
                    'max_amount_per_transaction' => (float)$request['max_amount_per_transaction'],
                    'total_transaction_amount_per_day' => (float)$request['total_transaction_amount_per_day'],
                    'transaction_limit_per_month' => (int)$request['transaction_limit_per_month'],
                    'total_transaction_amount_per_month' => (float)$request['total_transaction_amount_per_month']
                ])
            ]);
        }

        /** Agent transaction limits**/

        elseif ($name == 'agent_add_money_limit') {
            DB::table('business_settings')->updateOrInsert(['key' => 'agent_add_money_limit'], [
                'value' => json_encode([
                    'status' => (int)$request['status'],
                    'transaction_limit_per_day' => (int)$request['transaction_limit_per_day'],
                    'max_amount_per_transaction' => (float)$request['max_amount_per_transaction'],
                    'total_transaction_amount_per_day' => (float)$request['total_transaction_amount_per_day'],
                    'transaction_limit_per_month' => (int)$request['transaction_limit_per_month'],
                    'total_transaction_amount_per_month' => (float)$request['total_transaction_amount_per_month']
                ])
            ]);

        } elseif ($name == 'agent_send_money_limit') {
            DB::table('business_settings')->updateOrInsert(['key' => 'agent_send_money_limit'], [
                'value' => json_encode([
                    'status' => (int)$request['status'],
                    'transaction_limit_per_day' => (int)$request['transaction_limit_per_day'],
                    'max_amount_per_transaction' => (float)$request['max_amount_per_transaction'],
                    'total_transaction_amount_per_day' => (float)$request['total_transaction_amount_per_day'],
                    'transaction_limit_per_month' => (int)$request['transaction_limit_per_month'],
                    'total_transaction_amount_per_month' => (float)$request['total_transaction_amount_per_month']
                ])
            ]);
        }elseif ($name == 'agent_send_money_request_limit') {
            DB::table('business_settings')->updateOrInsert(['key' => 'agent_send_money_request_limit'], [
                'value' => json_encode([
                    'status' => (int)$request['status'],
                    'transaction_limit_per_day' => (int)$request['transaction_limit_per_day'],
                    'max_amount_per_transaction' => (float)$request['max_amount_per_transaction'],
                    'total_transaction_amount_per_day' => (float)$request['total_transaction_amount_per_day'],
                    'transaction_limit_per_month' => (int)$request['transaction_limit_per_month'],
                    'total_transaction_amount_per_month' => (float)$request['total_transaction_amount_per_month']
                ])
            ]);
        }elseif ($name == 'agent_withdraw_request_limit') {
            DB::table('business_settings')->updateOrInsert(['key' => 'agent_withdraw_request_limit'], [
                'value' => json_encode([
                    'status' => (int)$request['status'],
                    'transaction_limit_per_day' => (int)$request['transaction_limit_per_day'],
                    'max_amount_per_transaction' => (float)$request['max_amount_per_transaction'],
                    'total_transaction_amount_per_day' => (float)$request['total_transaction_amount_per_day'],
                    'transaction_limit_per_month' => (int)$request['transaction_limit_per_month'],
                    'total_transaction_amount_per_month' => (float)$request['total_transaction_amount_per_month']
                ])
            ]);
        }

        Toastr::success(translate('Settings updated!'));
        return back();
    }

}
