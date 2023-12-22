<?php

use App\Http\Controllers\Admin\AgentController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BonusController;
use App\Http\Controllers\Admin\BusinessSettingsController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EMoneyController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\HelpTopicController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\SMSModuleController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Agent\Transaction\TransactionController as TransactionTransactionController;
use App\Http\Controllers\Admin\TransferController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WithdrawalController;
use App\Http\Controllers\Admin\WithdrawController;
use App\Http\Controllers\Admin\MerchantController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\PurposeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CurrencyExchangeController;

Route::group(['namespace' => 'Admin', 'as' => 'admin.'], function () {
    Route::get('lang/{locale}', [LanguageController::class, 'lang'])->name('lang');

    //authentication
    Route::group(['namespace' => 'Auth', 'prefix' => 'auth', 'as' => 'auth.'], function () {
        Route::get('/code/captcha/{tmp}', 'LoginController@captcha')->name('default-captcha');
        Route::get('login', [LoginController::class, 'login'])->name('login');
        Route::post('login', [LoginController::class, 'submit']);
        Route::get('logout', [LoginController::class, 'logout'])->name('logout');
    });

    Route::group(['middleware' => ['admin']], function () {

        //new
        Route::get('/transaction/add-money-requests', [TransactionController::class, 'addMoneyRequest'])->name('add.money.request');
        Route::post('/transaction/add-money-status', [TransactionTransactionController::class, 'approveMoney'])->name('add.money.status');
        //dashboard
        Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');
        Route::get('settings', [DashboardController::class, 'settings'])->name('settings');
        Route::post('settings', [DashboardController::class, 'settings_update']);
        Route::post('settings-password', [DashboardController::class, 'settings_password_update'])->name('settings-password');

        /* Location */
        Route::get('/countries', [LocationController::class, 'country'])->name('location.country');
        Route::get('/country/status/{id}', [LocationController::class, 'countryStatus'])->name('country.status');
        Route::get('/city/add/{country}', [LocationController::class, 'addCity'])->name('add.city');
        Route::get('/city/edit/{id}', [LocationController::class, 'editCity'])->name('edit.city');
        Route::post('/city/update', [LocationController::class, 'updateCity'])->name('city.update');
        Route::post('/city/store', [LocationController::class, 'storeCity'])->name('city.store');
        Route::get('/cities', [LocationController::class, 'city'])->name('location.city');
        Route::get('/city/status/{id}', [LocationController::class, 'cityStatus'])->name('city.status');

        /** Pages */
        Route::group(['prefix' => 'pages', 'as' => 'pages.'], function () {
            Route::get('terms-and-conditions', [PageController::class, 'terms_and_conditions'])->name('terms-and-conditions');
            Route::post('terms-and-conditions', [PageController::class, 'terms_and_conditions_update']);

            Route::get('privacy-policy', [PageController::class, 'privacy_policy'])->name('privacy-policy');
            Route::post('privacy-policy', [PageController::class, 'privacy_policy_update']);

            Route::get('about-us', [PageController::class, 'about_us'])->name('about-us');
            Route::post('about-us', [PageController::class, 'about_us_update']);
        });

        /** Currency **/
        Route::group(['prefix' => 'currency', 'as' => 'currency.'], function () {
            Route::get('list', [CurrencyExchangeController::class, 'index'])->name('list');
            Route::post('update', [CurrencyExchangeController::class, 'update'])->name('update');
        });

        Route::group(['prefix' => 'business-settings', 'as' => 'business-settings.'], function () {
            //business setup
            Route::get('business-setup', [BusinessSettingsController::class, 'business_index'])->name('business-setup');
            Route::post('update-setup', [BusinessSettingsController::class, 'business_setup'])->name('update-setup');

            //payment method
            Route::get('payment-method', [BusinessSettingsController::class, 'payment_index'])->name('payment-method');
            Route::post('payment-method-update/{payment_method}', [BusinessSettingsController::class, 'payment_update'])->name('payment-method-update');

            //sms module
            Route::get('sms-module', [SMSModuleController::class, 'sms_index'])->name('sms-module');
            Route::post('sms-module-update/{sms_module}', [SMSModuleController::class, 'sms_update'])->name('sms-module-update');

            //charge setup
            Route::get('charge-setup', [BusinessSettingsController::class, 'charge_setup_index'])->name('charge-setup');
            Route::put('charge-setup', [BusinessSettingsController::class, 'charge_setup_update']);

            //app settings
            Route::get('app-settings', [BusinessSettingsController::class, 'app_settings'])->name('app_settings');
            Route::get('app-setting-update', [BusinessSettingsController::class, 'app_setting_update'])->name('app_setting_update');

            //recaptcha
            Route::get('recaptcha', [BusinessSettingsController::class, 'recaptcha_index'])->name('recaptcha_index');
            Route::post('recaptcha-update', [BusinessSettingsController::class, 'recaptcha_update'])->name('recaptcha_update');

            //push notification
            Route::get('fcm-index', [BusinessSettingsController::class, 'fcm_index'])->name('fcm-index');
            Route::post('update-fcm', [BusinessSettingsController::class, 'update_fcm'])->name('update-fcm');
            Route::post('update-fcm-messages', [BusinessSettingsController::class, 'update_fcm_messages'])->name('update-fcm-messages');

            //language
            Route::group(['prefix' => 'language', 'as' => 'language.', 'middleware' => []], function () {
                Route::get('', [LanguageController::class, 'index'])->name('index');
                Route::post('add-new', [LanguageController::class, 'store'])->name('add-new');
                Route::get('update-status', [LanguageController::class, 'update_status'])->name('update-status');
                Route::get('update-default-status', [LanguageController::class, 'update_default_status'])->name('update-default-status');
                Route::post('update', [LanguageController::class, 'update'])->name('update');
                Route::get('translate/{lang}', [LanguageController::class, 'translate'])->name('translate');
                Route::post('translate-submit/{lang}', [LanguageController::class, 'translate_submit'])->name('translate-submit');
                Route::post('remove-key/{lang}', [LanguageController::class, 'translate_key_remove'])->name('remove-key');
                Route::get('delete/{lang}', [LanguageController::class, 'delete'])->name('delete');
            });

            Route::get('otp-setup', [BusinessSettingsController::class, 'otp_setup'])->name('otp_setup_index');
            Route::post('otp-setup-update', [BusinessSettingsController::class, 'otp_setup_update'])->name('otp_setup_update');

            Route::get('system-feature', [BusinessSettingsController::class, 'system_feature'])->name('system_feature');
            Route::post('system-feature-update', [BusinessSettingsController::class, 'system_feature_update'])->name('system_feature_update');

            Route::get('customer-transaction-limits', [BusinessSettingsController::class, 'customer_transaction_limits_index'])->name('customer_transaction_limits');
            Route::get('agent-transaction-limits', [BusinessSettingsController::class, 'agent_transaction_limits_index'])->name('agent_transaction_limits');
            Route::post('transaction-limits/{transaction_type}', [BusinessSettingsController::class, 'transaction_limits_update'])->name('transaction_limits_update');
        });

        Route::group(['prefix' => 'merchant-config', 'as' => 'merchant-config.'], function () {
            Route::get('merchant-payment-otp', [BusinessSettingsController::class, 'merchant_payment_otp_index'])->name('merchant-payment-otp');
            Route::post('merchant-payment-otp-verification-update', [BusinessSettingsController::class, 'merchant_payment_otp_update'])->name('merchant-payment-otp-verification-update');
            Route::get('settings', [BusinessSettingsController::class, 'merchant_settings_index'])->name('settings');
            Route::post('settings-update', [BusinessSettingsController::class, 'merchant_settings_update'])->name('settings-update');
        });

        //linked-website
        Route::get('linked-website', [BusinessSettingsController::class, 'linked_website'])->name('linked-website');
        Route::post('linked-website', [BusinessSettingsController::class, 'linked_website_add']);
        Route::get('linked-website/update/{id}', [BusinessSettingsController::class, 'linked_website_edit'])->name('linked-website-edit');
        Route::put('linked-website', [BusinessSettingsController::class, 'linked_website_update']);
        Route::get('linked-website/status/{id}', [BusinessSettingsController::class, 'linked_website_status'])->name('linked-website-status');
        Route::get('linked-website-delete', [BusinessSettingsController::class, 'linked_website_delete'])->name('linked-website-delete');

        //notification
        Route::group(['prefix' => 'notification', 'as' => 'notification.'], function () {
            Route::get('add-new', [NotificationController::class, 'index'])->name('add-new');
            Route::post('store', [NotificationController::class, 'store'])->name('store');
            Route::get('edit/{id}', [NotificationController::class, 'edit'])->name('edit');
            Route::post('update/{id}', [NotificationController::class, 'update'])->name('update');
            Route::get('status/{id}/{status}', [NotificationController::class, 'status'])->name('status');
            Route::delete('delete/{id}', [NotificationController::class, 'delete'])->name('delete');
        });

        //notification
        Route::group(['prefix' => 'banner', 'as' => 'banner.'], function () {
            Route::get('add-new', [BannerController::class, 'index'])->name('index');
            Route::post('store', [BannerController::class, 'store'])->name('store');
            Route::get('edit/{id}', [BannerController::class, 'edit'])->name('edit');
            Route::post('update/{id}', [BannerController::class, 'update'])->name('update');
            Route::get('status/{id}', [BannerController::class, 'status'])->name('status');
            Route::get('delete/{id}', [BannerController::class, 'delete'])->name('delete');
        });

        Route::group(['prefix' => 'bonus', 'as' => 'bonus.'], function () {
            Route::get('add-new', [BonusController::class, 'index'])->name('index');
            Route::post('store', [BonusController::class, 'store'])->name('store');
            Route::get('edit/{id}', [BonusController::class, 'edit'])->name('edit');
            Route::put('update/{id}', [BonusController::class, 'update'])->name('update');
            Route::get('status/{id}', [BonusController::class, 'status'])->name('status');
            Route::get('delete/{id}', [BonusController::class, 'delete'])->name('delete');
        });

        Route::group(['prefix' => 'helpTopic', 'as' => 'helpTopic.'], function () {
            Route::get('list', [HelpTopicController::class, 'list'])->name('list');
            Route::post('add-new', [HelpTopicController::class, 'store'])->name('add-new');
            Route::get('status/{id}', [HelpTopicController::class, 'status']);
            Route::get('edit/{id}', [HelpTopicController::class, 'edit']);
            Route::post('update/{id}', [HelpTopicController::class, 'update']);
            Route::post('delete', [HelpTopicController::class, 'destroy'])->name('delete');
        });

        //customer
        Route::group(['prefix' => 'customer', 'as' => 'customer.', 'middleware' => []], function () {
            Route::get('add', [CustomerController::class, 'index'])->name('add');
            Route::post('store', [CustomerController::class, 'store'])->name('store');
            Route::get('list', [CustomerController::class, 'customer_list'])->name('list');
            Route::get('view/{user_id}', [CustomerController::class, 'view'])->name('view');
            Route::get('edit/{id}', [CustomerController::class, 'edit'])->name('edit');
            Route::post('update/{id}', [CustomerController::class, 'update'])->name('update');
            Route::get('transaction/{user_id}', [CustomerController::class, 'transaction'])->name('transaction');
            Route::get('log/{user_id}', [CustomerController::class, 'log'])->name('log');
            Route::post('search', [CustomerController::class, 'search'])->name('search');
            Route::get('status/{id}', [CustomerController::class, 'status'])->name('status');
            Route::get('kyc-requests', [CustomerController::class, 'get_kyc_request'])->name('kyc_requests');
            Route::get('kyc-status-update/{id}/{status}', [CustomerController::class, 'update_kyc_status'])->name('kyc_status_update');
        });
        Route::get('admin/transaction/{user_id}', [AdminController::class, 'transaction'])->name('admin.transaction');
        Route::get('admin/view/{user_id}', [AdminController::class, 'view'])->name('admin.view');


        //agent
        Route::group(['prefix' => 'agent', 'as' => 'agent.'], function () {
            Route::get('add', [AgentController::class, 'index'])->name('add');
            Route::post('store', [AgentController::class, 'store'])->name('store');
            Route::get('list', [AgentController::class, 'list'])->name('list');
            Route::get('view/{user_id}', [CustomerController::class, 'view'])->name('view');
            Route::get('transaction/{user_id}', [CustomerController::class, 'transaction'])->name('transaction');
            Route::get('log/{user_id}', [CustomerController::class, 'log'])->name('log');
            Route::get('edit/{id}', [AgentController::class, 'edit'])->name('edit');
            Route::post('update/{id}', [AgentController::class, 'update'])->name('update');
            Route::post('search', [AgentController::class, 'search'])->name('search');
            Route::get('status/{id}', [AgentController::class, 'status'])->name('status');
            Route::get('kyc-requests', [AgentController::class, 'get_kyc_request'])->name('kyc_requests');
            Route::get('kyc-status-update/{id}/{status}', [AgentController::class, 'update_kyc_status'])->name('kyc_status_update');
            /*Fee*/
            Route::get('/fee', [AgentController::class, 'fee'])->name('fee');
            Route::post('/fee', [AgentController::class, 'feeUpdate'])->name('fee.update');
            Route::get('/commission/status/{id}', [AgentController::class, 'commissionStatus'])->name('commission.status');
            Route::get('/fee/status/{id}', [AgentController::class, 'feeStatus'])->name('fee.status');
            Route::get('/commission/{id}', [AgentController::class, 'commission'])->name('commission');
            Route::post('/commission', [AgentController::class, 'commissionUpdate'])->name('commission.update');
        });

        //merchant
        Route::group(['prefix' => 'merchant', 'as' => 'merchant.'], function () {
            Route::get('add', [MerchantController::class, 'index'])->name('add');
            Route::post('store', [MerchantController::class, 'store'])->name('store');
            Route::get('list', [MerchantController::class, 'list'])->name('list');
            Route::get('view/{user_id}', [MerchantController::class, 'view'])->name('view');
            Route::get('transaction/{user_id}', [MerchantController::class, 'transaction'])->name('transaction');
            Route::get('edit/{id}', [MerchantController::class, 'edit'])->name('edit');
            Route::post('update/{id}', [MerchantController::class, 'update'])->name('update');
            Route::post('search', [MerchantController::class, 'search'])->name('search');
            Route::get('status/{id}', [MerchantController::class, 'status'])->name('status');
        });

        Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
            Route::get('log', [UserController::class, 'log'])->name('log');
        });

        //transaction
        Route::group(['prefix' => 'transaction', 'as' => 'transaction.'], function () {
            Route::get('index', [TransactionController::class, 'index'])->name('index');
            Route::post('store', [TransactionController::class, 'store'])->name('store');

            Route::get('request-money', [TransactionController::class, 'request_money'])->name('request_money');
            Route::get('request-money-status/{slug}', [TransactionController::class, 'request_money_status_change'])->name('request_money_status_change');


            Route::get('get-user', [TransferController::class, 'get_user'])->name('get_user');
        });

        //expense transaction
        Route::group(['prefix' => 'expense', 'as' => 'expense.'], function () {
            Route::get('index', [ExpenseController::class, 'index'])->name('index');
        });

        //withdraw
        Route::group(['prefix' => 'withdraw', 'as' => 'withdraw.'], function () {
            Route::get('requests', [WithdrawController::class, 'index'])->name('requests');
            Route::get('status-update', [WithdrawController::class, 'status_update'])->name('status_update');
            Route::get('download', [WithdrawController::class, 'download'])->name('download');
        });

        //transfer
        Route::group(['prefix' => 'transfer', 'as' => 'transfer.'], function () {
            Route::get('index', [TransferController::class, 'index'])->name('index');
            Route::post('store', [TransferController::class, 'store'])->name('store');


            Route::get('get-user', [TransferController::class, 'get_user'])->name('get_user');
        });

        //eMoney
        Route::group(['prefix' => 'emoney', 'as' => 'emoney.'], function () {
            Route::get('index', [EMoneyController::class, 'index'])->name('index');
            Route::post('store', [EMoneyController::class, 'store'])->name('store');
        });

        //purpose
        Route::group(['prefix' => 'purpose', 'as' => 'purpose.'], function () {
            Route::get('index', [PurposeController::class, 'index'])->name('index');
            Route::post('store', [PurposeController::class, 'store'])->name('store');
            Route::get('edit/{id}', [PurposeController::class, 'edit'])->name('edit');
            Route::post('update', [PurposeController::class, 'update'])->name('update');
            Route::get('delete/{id}', [PurposeController::class, 'delete'])->name('delete');
        });

        //purpose
        Route::group(['prefix' => 'withdrawal-methods', 'as' => 'withdrawal_methods.'], function () {
            Route::get('add-method', [WithdrawalController::class, 'add_method'])->name('add');
            Route::post('store', [WithdrawalController::class, 'store_method'])->name('store');
            Route::post('delete', [WithdrawalController::class, 'delete_method'])->name('delete');
        });
    });
});
