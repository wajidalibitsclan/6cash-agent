<?php

use App\Http\Controllers\Agent\Auth\ForgotPasswordController;
use App\Http\Controllers\Agent\Auth\LoginController;
use App\Http\Controllers\Agent\Auth\RegisterController;
use App\Http\Controllers\Agent\DashboardController;
use App\Http\Controllers\Agent\Profile\ProfileController;
use App\Http\Controllers\Agent\Static\StaticPageController;
use Illuminate\Support\Facades\Route;
use App\CentralLogics\Twilio;
use App\Http\Controllers\Agent\Transaction\TransactionController;

Route::group(['prefix' => 'agent'], function () {
    //authentication
    Route::get('/', function () {
        return redirect(\route('agent.auth.login'));
    });
    Route::group(['prefix' => 'auth', 'middleware' => ['guestAuthAgent']], function () {
        Route::get('code/captcha/{tmp}', 'LoginController@captcha')->name('default-captcha');
        Route::get('login', [LoginController::class, 'login'])->name('agent.login');
        Route::post('login', [LoginController::class, 'submit'])->name('agent.auth.login');
        Route::get('register', [RegisterController::class, 'index'])->name('agent.auth.register');
        Route::post('register', [RegisterController::class, 'submit'])->name('agent.auth.registered');
        Route::get('otp', [RegisterController::class, 'verifyOtp'])->name('agent.auth.otp')->middleware('preventPage');
        Route::post('resend-otp', [RegisterController::class, 'resendOtp'])->name('agent.auth.resend.otp');
        Route::post('verify-otp', [RegisterController::class, 'checkOtp'])->name('agent.auth.verify.otp');
        Route::get('information', [RegisterController::class, 'information'])->name('agent.information')->middleware('preventPage');
        Route::post('set-information', [RegisterController::class, 'setInformation'])->name('agent.set.information');
        Route::get('set-pin', [RegisterController::class, 'setPin'])->name('agent.set.pin')->middleware('preventPage');
        Route::post('check-phone', [RegisterController::class, 'checkPhone'])->name('agent.auth.checkphone');
        Route::post('resend-otp-fq', [ForgotPasswordController::class, 'resentOTP'])->name('agent.auth.fq.resend.otp');
        Route::get('forgot-password', [ForgotPasswordController::class, 'index'])->name('agent.forgot.password');
        Route::post('forgot-passowrd', [ForgotPasswordController::class, 'submit'])->name('agent.auth.forgot.password.otp');
        Route::get('fp-otp', [ForgotPasswordController::class, 'verifyOtp'])->name('agent.fp.otp');
        Route::post('fp-otp-check', [ForgotPasswordController::class, 'checkOtp'])->name('agent.fq.otp.check');
        Route::get('reset-pin', [ForgotPasswordController::class, 'resetPin'])->name('agent.auth.set.reset.pin')->middleware('preventPage');
        Route::post('reset-pin', [ForgotPasswordController::class, 'setPin'])->name('agent.auth.reset.pin');
        Route::get('face-verification', [RegisterController::class, 'faceRecognition'])->name('agent.auth.face.verification')->middleware('preventPage');
    });



    Route::middleware(['checkAuthToken'])->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('agent.dashboard');
        //Static Pages
        Route::get('about-us', [StaticPageController::class, 'aboutUs'])->name('agent.aboutus');
        Route::get('faq', [StaticPageController::class, 'faq'])->name('agent.faq');
        Route::get('terms-of-use', [StaticPageController::class, 'termOfUse'])->name('agent.term');
        Route::get('support', [StaticPageController::class, 'support'])->name('agent.support');
        Route::get('privacy-policy', [StaticPageController::class, 'privacyPolicy'])->name('agent.policy');
        //Profile
        Route::get('profile', [ProfileController::class, 'index'])->name('agent.profile');
        Route::post('profile', [ProfileController::class, 'submit'])->name('agent.profile.edit');
        Route::get('verify-account', [ProfileController::class, 'updateAccount'])->name('agent.verify.account');
        Route::post('verify-account', [ProfileController::class, 'verifyAccount'])->name('agent.verified.account');
        //Transaction
        Route::get('/add-money', [TransactionController::class, 'addMoney'])->name('agent.add.money');
        Route::post('/add-money', [TransactionController::class, 'addedMoney'])->name('agent.added.money');
        Route::get('/add-money-request', [TransactionController::class, 'addMoneyRequest'])->name('agent.add.money.request');

        Route::get('/send-money', [TransactionController::class, 'sendMoney'])->name('agent.send.money');
        Route::post('/send-money', [TransactionController::class, 'sentMoney'])->name('agent.sent.money');
        Route::get('/transaction-history/{string?}', [TransactionController::class, 'history'])->name('agent.transaction.history');
        Route::get('/transaction-limit', [TransactionController::class, 'transactionLimit'])->name('agent.transaction.limit');
        Route::get('/withdraw-history/{string?}', [TransactionController::class, 'withdrawHistory'])->name('agent.withdraw.history');
        Route::get('/send-request/{string?}', [TransactionController::class, 'sendRequests'])->name('agent.send.request');
        Route::get('/requests/{string?}', [TransactionController::class, 'requests'])->name('agent.requests');
        Route::get('/request-money', [TransactionController::class, 'requestMoney'])->name('agent.request.money');
        Route::post('/request-money', [TransactionController::class, 'requestedMoney'])->name('agent.requested.money');
        Route::get('/withdraw', [TransactionController::class, 'withdraw'])->name('agent.withdraw');
        Route::post('/withdraw', [TransactionController::class, 'withdrawAction'])->name('agent.withdraw.action');

        Route::post('/withdraw-methods', [TransactionController::class, 'getWithdrawalMethods'])->name('agent.withdraw.method');

        Route::get('/change-pin', [ProfileController::class, 'changePin'])->name('agent.change.pin');
        Route::post('/change-pin', [ProfileController::class, 'updatePin'])->name('agent.update.pin');
        Route::get('/remove-account/{id}', [ProfileController::class, 'removeAccount'])->name('agent.remove.account');
        /* LOCATIONS */
        Route::post('/get/cities', [ProfileController::class, 'getCities'])->name('agent.get.cities');
        Route::post('/get/agents', [ProfileController::class, 'getAgent'])->name('agent.get.agents');
        Route::post('/get/agent/bynumber', [ProfileController::class, 'getAgentRecord'])->name('agent.get.agents.number');

        Route::post('/city/city_id', [ProfileController::class, 'fetchCityId'])->name('agent.city.city_id');

        /* Transaction Detail*/
        Route::get('/transaction/detail/{string?}', [TransactionController::class, 'requestSendMoney'])->name('agent.transaction.detail');
        Route::post('/transaction/cancel', [TransactionController::class, 'cancelTransaction'])->name('agent.transaction.cancel');
        Route::post('/transaction/check-pin', [TransactionController::class, 'verifySecretPin'])->name('agent.transaction.secret.pin');
        Route::post('/transaction/received', [TransactionController::class, 'verifyTransaction'])->name('agent.transaction.verified');
        Route::post('/transaction/reject', [TransactionController::class, 'rejectTransaction'])->name('agent.transaction.reject');

        Route::get('logout', [LoginController::class, 'logout'])->name('agent.logout');
    });
});


Route::post('/city/list', [ProfileController::class, 'getCityList'])->name('agent.city.list');





Route::get('/test-otp', function () {
    $sms = new Twilio();
    $sms->sendOTP('+923167936302', 'This is new message');
    dd("donennnnnnnnnnnnnnnnnnnnnnnnnnn");
});
