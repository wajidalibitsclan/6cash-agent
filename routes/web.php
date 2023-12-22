<?php

use App\Http\Controllers\Gateway\FlutterwaveController;
use App\Http\Controllers\Gateway\MercadoPagoController;
use App\Http\Controllers\Gateway\PaymobController;
use App\Http\Controllers\Gateway\PaypalPaymentController;
use App\Http\Controllers\Gateway\PaystackController;
use App\Http\Controllers\Gateway\RazorPayController;
use App\Http\Controllers\Gateway\SslCommerzPaymentController;
use App\Http\Controllers\Gateway\StripePaymentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Gateway\BkashPaymentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Payment\PaymentOrderController;
use App\Models\Country;
use Illuminate\Support\Facades\Artisan;

require __DIR__ . '/agent.php';




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect(\route('admin.auth.login'));
});
Route::get('/home', function () {
    return redirect(\route('admin.auth.login'));
});

Route::group(['prefix' => ''], function () {
    Route::get('/payment', [PaymentController::class, 'payment'])->name('payment-mobile');
    Route::get('set-payment-method/{name}', [PaymentController::class, 'payment'])->name('set-payment-method');
});


Route::get('/payment/agent', [PaymentController::class, 'paymentAgent'])->name('payment-agent');
// SSLCOMMERZ Start
/*Route::get('/example1', 'SslCommerzPaymentController@exampleEasyCheckout');
Route::get('/example2', 'SslCommerzPaymentController@exampleHostedCheckout');*/
Route::post('pay-ssl', [SslCommerzPaymentController::class, 'index']);
Route::post('/success', [SslCommerzPaymentController::class, 'success']);
Route::post('/fail', [SslCommerzPaymentController::class, 'fail']);
Route::post('/cancel', [SslCommerzPaymentController::class, 'cancel']);
Route::post('/ipn', [SslCommerzPaymentController::class, 'ipn']);
//SSLCOMMERZ END

/*paypal*/
/*Route::get('/paypal', function (){return view('paypal-test');})->name('paypal');*/
Route::post('pay-paypal', [PaypalPaymentController::class, 'payWithpaypal'])->name('pay-paypal');
Route::get('paypal-status', [PaypalPaymentController::class, 'getPaymentStatus'])->name('paypal-status');
/*paypal*/

/*Route::get('stripe', function (){
return view('stripe-test');
});*/
Route::get('pay-stripe', [StripePaymentController::class, 'payment_process_3d'])->name('pay-stripe');
Route::get('pay-stripe/success', [StripePaymentController::class, 'success'])->name('pay-stripe.success');
Route::get('pay-stripe/fail', [StripePaymentController::class, 'fail'])->name('pay-stripe.fail');


// Get Route For Show Payment Form
Route::get('paywithrazorpay', [RazorPayController::class, 'payWithRazorpay'])->name('paywithrazorpay');
Route::post('payment-razor', [RazorPayController::class, 'payment'])->name('payment-razor');

///*Route::fallback(function () {
//return redirect('/admin/auth/login');
//});*/
//
////internal point pay
//Route::post('internal-point-pay', 'InternalPointPayController@payment')->name('internal-point-pay');

Route::get('payment-success', [PaymentController::class, 'success'])->name('payment-success');
Route::get('payment-fail', [PaymentController::class, 'fail'])->name('payment-fail');

////senang pay
//Route::match(['get', 'post'], '/return-senang-pay', 'SenangPayController@return_senang_pay')->name('return-senang-pay');
//

//paystack
Route::post('/paystack-pay', [PaystackController::class, 'redirectToGateway'])->name('paystack-pay');
Route::get('/paystack-callback', [PaystackController::class, 'handleGatewayCallback'])->name('paystack-callback');
Route::get('/paystack', function () {
    return view('paystack');
});

///*Route::fallback(function () {
//return redirect('/admin/auth/login');
//});*/
//Route::match(['get', 'post'], '/return-senang-pay', 'SenangPayController@return_senang_pay')->name('return-senang-pay');
//
//Route::get('payment-success', 'PaymentController@success')->name('payment-success');
//Route::get('payment-fail', 'PaymentController@fail')->name('payment-fail');
//
////bkash
Route::group(['prefix' => 'bkash'], function () {
    // Payment Routes for bKash
    Route::get('make-payment', [BkashPaymentController::class, 'make_tokenize_payment'])->name('bkash.make-payment');
    Route::any('callback', [BkashPaymentController::class, 'callback'])->name('bkash.callback');

    // Refund Routes for bKash
    /*Route::get('refund', 'BkashRefundController@index')->name('bkash-refund');
    Route::post('refund', 'BkashRefundController@refund')->name('bkash-refund');*/
});

// paymob
Route::post('/paymob-credit', [PaymobController::class, 'credit'])->name('paymob-credit');
Route::get('/paymob-callback', [PaymobController::class, 'callback'])->name('paymob-callback');

//// The callback url after a payment
Route::get('mercadopago/home', [MercadoPagoController::class, 'index'])->name('mercadopago.index');
Route::post('mercadopago/make-payment', [MercadoPagoController::class, 'make_payment'])->name('mercadopago.make_payment');
Route::get('mercadopago/get-user', [MercadoPagoController::class, 'get_test_user'])->name('mercadopago.get-user');

// The route that the button calls to initialize payment
Route::post('/flutterwave-pay', [FlutterwaveController::class, 'initialize'])->name('flutterwave_pay');
// The callback url after a payment
Route::get('/rave/callback', [FlutterwaveController::class, 'callback'])->name('flutterwave_callback');

//Route::get('add-currency', function () {
//    $currencies = file_get_contents("installation/currency.json");
//    $decoded = json_decode($currencies, true);
//    $keep = [];
//    foreach ($decoded as $item) {
//        array_push($keep, [
//            'country'         => $item['name'],
//            'currency_code'   => $item['code'],
//            'currency_symbol' => $item['symbol_native'],
//            'exchange_rate'   => 1,
//        ]);
//    }
//    DB::table('currencies')->insert($keep);
//    return response()->json(['ok']);
//});
//
//Route::match(['get','post'], '/test',[\App\Http\Controllers\SenangPayController::class,'pay'])->name('test');


Route::get('authentication-failed', function () {
    $errors = [];
    array_push($errors, ['code' => 'auth-001', 'message' => 'Unauthorized.']);
    return response()->json([
        'errors' => $errors
    ], 401);
})->name('authentication-failed');

Route::get('payment-process', [PaymentOrderController::class, 'payment_process'])->name('payment-process');
Route::post('send-otp', [PaymentOrderController::class, 'send_otp'])->name('send-otp');
Route::get('otp', [PaymentOrderController::class, 'otp_index'])->name('otp');
Route::post('verify-otp', [PaymentOrderController::class, 'verify_otp'])->name('verify-otp');
Route::get('resend-otp', [PaymentOrderController::class, 'resend_otp'])->name('resend-otp');
Route::get('pin', [PaymentOrderController::class, 'pin_index'])->name('pin');
Route::post('verify-pin', [PaymentOrderController::class, 'verify_pin'])->name('verify-pin');
Route::get('success', [PaymentOrderController::class, 'success_index'])->name('success');
Route::get('success-callback', [PaymentOrderController::class, 'payment_success_callback'])->name('success-callback');
Route::get('back-to-callback', [PaymentOrderController::class, 'back_to_callback'])->name('back-to-callback');

//New Success page
Route::get('/success-payment', [PaymentOrderController::class, 'success_payment'])->name('success.payment');
//New Failed page
Route::get('/failed-payment', [PaymentOrderController::class, 'failed_payment'])->name('fail-payment.page');



Route::group(['prefix' => 'pages', 'as' => 'pages.'], function () {
    Route::get('terms-conditions', [PageController::class, 'get_terms_and_conditions'])->name('terms-conditions');
    Route::get('privacy-policy', [PageController::class, 'get_privacy_policy'])->name('privacy-policy');
    Route::get('about-us', [PageController::class, 'get_about_us'])->name('about-us');
});



Route::get('clear', function () {
    Artisan::call('optimize:clear');
});





// Route::get('/for-test', function () {
//     $test = Country::withACtiveCityAndCurrency()->find(165);
//     dd($test);
// });
