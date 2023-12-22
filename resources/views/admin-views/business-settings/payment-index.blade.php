@extends('layouts.admin.app')

@section('title', translate('Payment Setup'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="d-flex align-items-center gap-3 pb-2">
            <img width="24" src="{{asset('public/assets/admin/img/media/business-setup.png')}}" alt="">
            <h2 class="page-header-title">{{translate('Business Setup')}}</h2>
        </div>
        <!-- End Page Header -->

        <div class="inline-page-menu my-4">
            @include('admin-views.business-settings.partial._business-setup-tabs')
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{translate('sslcommerz')}}</h5>
                    </div>
                    <div class="card-body">
                        @php($config=\App\CentralLogics\Helpers::get_business_settings('ssl_commerz_payment'))
                        <form
                            action="{{env('APP_MODE')!='demo'?route('admin.business-settings.payment-method-update',['ssl_commerz_payment']):'javascript:'}}"
                            method="post">
                            @csrf
                            @if(isset($config))
                                <div class="form-group">
                                    <label class="control-label">{{translate('sslcommerz')}} {{translate('payment')}}</label>
                                </div>
                                <div class="mb-2 d-flex align-iems-center gap-2">
                                    <input type="radio" name="status" id="ssl_active" value="1" {{$config['status']==1?'checked':''}}>
                                    <label class="mb-0 cursor-pointer" for="ssl_active">{{translate('active')}}</label>
                                </div>
                                <div class="form-group d-flex align-iems-center gap-2">
                                    <input type="radio" name="status" id="ssl_inactive" value="0" {{$config['status']==0?'checked':''}}>
                                    <label class="mb-0 cursor-pointer" for="ssl_inactive">{{translate('inactive')}}</label>
                                </div>
                                <div class="form-group">
                                    <label>{{translate('store')}} {{translate('id')}} </label>
                                    <input type="text" class="form-control" name="store_id"
                                           value="{{env('APP_MODE')!='demo'?$config['store_id']:''}}">
                                </div>
                                <div class="form-group">
                                    <label>{{translate('store')}} {{translate('password')}}</label>
                                    <input type="text" class="form-control" name="store_password"
                                           value="{{env('APP_MODE')!='demo'?$config['store_password']:''}}">
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                        onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                        class="btn btn-primary">{{translate('save')}}</button>
                                </div>
                            @else
                                <button type="submit" class="btn btn-primary">{{translate('configure')}}</button>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{translate('razorpay')}}</h5>
                    </div>
                    <div class="card-body">
                        @php($config=\App\CentralLogics\Helpers::get_business_settings('razor_pay'))
                        <form
                            action="{{env('APP_MODE')!='demo'?route('admin.business-settings.payment-method-update',['razor_pay']):'javascript:'}}"
                            method="post">
                            @csrf
                            @if(isset($config))
                                <div class="form-group">
                                    <label class="control-label">{{translate('razorpay')}}</label>
                                </div>
                                <div class="mb-2  d-flex align-iems-center gap-2">
                                    <input type="radio" name="status" id="razorpay_active" value="1" {{$config['status']==1?'checked':''}}>
                                    <label class="mb-0 cursor-pointer" for="razorpay_active">{{translate('active')}}</label>
                                </div>
                                <div class="form-group  d-flex align-iems-center gap-2">
                                    <input type="radio" name="status" id="razorpay_inactive" value="0" {{$config['status']==0?'checked':''}}>
                                    <label class="mb-0 cursor-pointer" for="razorpay_inactive">{{translate('inactive')}}</label>
                                </div>
                                <div class="form-group">
                                    <label>{{translate('razorkey')}}</label>
                                    <input type="text" class="form-control" name="razor_key"
                                           value="{{env('APP_MODE')!='demo'?$config['razor_key']:''}}">
                                </div>
                                <div class="form-group">
                                    <label>{{translate('razorsecret')}}</label>
                                    <input type="text" class="form-control" name="razor_secret"
                                           value="{{env('APP_MODE')!='demo'?$config['razor_secret']:''}}">
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                        onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                        class="btn btn-primary">{{translate('save')}}</button>
                                </div>
                            @else
                                <button type="submit"
                                        class="btn btn-primary">{{translate('configure')}}</button>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{translate('paypal')}}</h5>
                    </div>
                    <div class="card-body">
                        @php($config=\App\CentralLogics\Helpers::get_business_settings('paypal'))
                        <form
                            action="{{env('APP_MODE')!='demo'?route('admin.business-settings.payment-method-update',['paypal']):'javascript:'}}"
                            method="post">
                            @csrf
                            @if(isset($config))
                                <div class="form-group">
                                    <label class="control-label">{{translate('paypal')}}</label>
                                </div>
                                <div class="mb-2  d-flex align-iems-center gap-2">
                                    <input type="radio" name="status" id="paypal_active" value="1" {{$config['status']==1?'checked':''}}>
                                    <label class="mb-0 cursor-pointer" for="paypal_active">{{translate('active')}}</label>
                                </div>
                                <div class="form-group  d-flex align-iems-center gap-2">
                                    <input type="radio" name="status" id="paypal_inactive" value="0" {{$config['status']==0?'checked':''}}>
                                    <label class="mb-0 cursor-pointer" for="paypal_inactive">{{translate('inactive')}}</label>
                                </div>
                                <div class="form-group">
                                    <label>{{translate('paypal')}} {{translate('client')}} {{translate('id')}}</label>
                                    <input type="text" class="form-control" name="paypal_client_id"
                                           value="{{env('APP_MODE')!='demo'?$config['paypal_client_id']:''}}">
                                </div>
                                <div class="form-group">
                                    <label>{{translate('paypal')}} {{translate('secret')}}</label>
                                    <input type="text" class="form-control" name="paypal_secret"
                                           value="{{env('APP_MODE')!='demo'?$config['paypal_secret']:''}}">
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                        onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                        class="btn btn-primary">{{translate('save')}}</button>
                                </div>
                            @else
                                <button type="submit"
                                        class="btn btn-primary">{{translate('configure')}}</button>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6 pb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{translate('stripe')}}</h5>
                    </div>
                    <div class="card-body">
                        @php($config=\App\CentralLogics\Helpers::get_business_settings('stripe'))
                        <form
                            action="{{env('APP_MODE')!='demo'?route('admin.business-settings.payment-method-update',['stripe']):'javascript:'}}"
                            method="post">
                            @csrf
                            @if(isset($config))
                                <div class="form-group">
                                    <label class="control-label">{{translate('stripe')}}</label>
                                </div>
                                <div class="mb-2  d-flex align-iems-center gap-2">
                                    <input type="radio" name="status" id="stripe_active" value="1" {{$config['status']==1?'checked':''}}>
                                    <label class="mb-0 cursor-pointer" for="stripe_active">{{translate('active')}}</label>
                                </div>
                                <div class="form-group  d-flex align-iems-center gap-2">
                                    <input type="radio" name="status" id="stripe_inactive" value="0" {{$config['status']==0?'checked':''}}>
                                    <label class="mb-0 cursor-pointer" for="stripe_inactive">{{translate('inactive')}} </label>
                                </div>
                                <div class="form-group">
                                    <label>{{translate('published')}} {{translate('key')}}</label>
                                    <input type="text" class="form-control" name="published_key"
                                           value="{{env('APP_MODE')!='demo'?$config['published_key']:''}}">
                                </div>

                                <div class="form-group">
                                    <label>{{translate('api')}} {{translate('key')}}</label>
                                    <input type="text" class="form-control" name="api_key"
                                           value="{{env('APP_MODE')!='demo'?$config['api_key']:''}}">
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                        onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                        class="btn btn-primary">{{translate('save')}}</button>
                                </div>
                            @else
                                <button type="submit" class="btn btn-primary">{{translate('configure')}}</button>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{translate('paystack')}}</h5>
                    </div>
                    <div class="card-body">
                        @php($config=\App\CentralLogics\Helpers::get_business_settings('paystack'))
                        <form
                            action="{{env('APP_MODE')!='demo'?route('admin.business-settings.payment-method-update',['paystack']):'javascript:'}}"
                            method="post">
                            @csrf
                            @if(isset($config))
                                <div class="form-group">
                                    <label class="control-label">{{translate('paystack')}}</label>
                                </div>
                                <div class="mb-2 d-flex align-iems-center gap-2">
                                    <input type="radio" name="status" id="paystack_active" value="1" {{$config['status']==1?'checked':''}}>
                                    <label class="mb-0 cursor-pointer" for="paystack_active">{{translate('active')}}</label>
                                </div>
                                <div class="form-group d-flex align-iems-center gap-2">
                                    <input type="radio" name="status" id="paystack_inactive" value="0" {{$config['status']==0?'checked':''}}>
                                    <label class="mb-0 cursor-pointer" for="paystack_inactive">{{translate('inactive')}}</label>
                                </div>
                                <div class="form-group">
                                    <label>{{translate('publicKey')}}</label>
                                    <input type="text" class="form-control" name="publicKey"
                                           value="{{env('APP_MODE')!='demo'?$config['publicKey']:''}}">
                                </div>
                                <div class="form-group">
                                    <label>{{translate('secretKey')}} </label>
                                    <input type="text" class="form-control" name="secretKey"
                                           value="{{env('APP_MODE')!='demo'?$config['secretKey']:''}}">
                                </div>
                                <div class="form-group">
                                    <label>{{translate('paymentUrl')}} </label>
                                    <input type="text" class="form-control" name="paymentUrl"
                                           value="{{env('APP_MODE')!='demo'?$config['paymentUrl']:''}}">
                                </div>
                                <div class="form-group">
                                    <label>{{translate('merchantEmail')}} </label>
                                    <input type="text" class="form-control" name="merchantEmail"
                                           value="{{env('APP_MODE')!='demo'?$config['merchantEmail']:''}}">
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                        onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                        class="btn btn-primary">{{translate('save')}}</button>
                                </div>
                            @else
                                <button type="submit"
                                        class="btn btn-primary">{{translate('configure')}}</button>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{translate('senang')}} {{translate('pay')}}</h5>
                    </div>
                    <div class="card-body">
                        @php($config=\App\CentralLogics\Helpers::get_business_settings('senang_pay'))
                        <form
                            action="{{env('APP_MODE')!='demo'?route('admin.business-settings.payment-method-update',['senang_pay']):'javascript:'}}"
                            method="post">
                            @csrf
                            @if(isset($config))
                                <div class="form-group">
                                    <label class="control-label">{{translate('senang')}} {{translate('pay')}}</label>
                                </div>
                                <div class="mb-2 d-flex align-iems-center gap-2">
                                    <input type="radio" name="status" value="1" id="senang_active" {{$config['status']==1?'checked':''}}>
                                    <label class="mb-0 cursor-pointer" for="senang_active">{{translate('active')}}</label>
                                </div>
                                <div class="form-group d-flex align-iems-center gap-2">
                                    <input type="radio" name="status" id="senang_inactive" value="0" {{$config['status']==0?'checked':''}}>
                                    <label class="mb-0 cursor-pointer" for="senang_inactive">{{translate('inactive')}} </label>
                                </div>
                                <div class="form-group">
                                    <label>{{translate('secret')}} {{translate('key')}}</label>
                                    <input type="text" class="form-control" name="secret_key"
                                           value="{{env('APP_MODE')!='demo'?$config['secret_key']:''}}">
                                </div>

                                <div class="form-group">
                                    <label>{{translate('merchant')}} {{translate('id')}}</label>
                                    <input type="text" class="form-control" name="merchant_id"
                                           value="{{env('APP_MODE')!='demo'?$config['merchant_id']:''}}">
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                        onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                        class="btn btn-primary">{{translate('save')}}</button>
                                </div>
                            @else
                                <button type="submit" class="btn btn-primary">{{translate('configure')}}</button>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{translate('bkash')}}</h5>
                    </div>
                    <div class="card-body">
                        @php($config=\App\CentralLogics\Helpers::get_business_settings('bkash'))
                        <form
                            action="{{env('APP_MODE')!='demo'?route('admin.business-settings.payment-method-update',['bkash']):'javascript:'}}"
                            method="post">
                            @csrf
                            @if(isset($config))
                                <div class="form-group">
                                    <label class="control-label">{{translate('bkash')}}</label>
                                </div>
                                <div class="mb-2 d-flex align-iems-center gap-2">
                                    <input type="radio" name="status" value="1" id="bkash_active" {{$config['status']==1?'checked':''}}>
                                    <label class="mb-0 cursor-pointer" for="bkash_active">{{translate('active')}}</label>
                                </div>
                                <div class="form-group d-flex align-iems-center gap-2">
                                    <input type="radio" name="status" value="0" id="bkash_inactive" {{$config['status']==0?'checked':''}}>
                                    <label class="mb-0 cursor-pointer" for="bkash_inactive">{{translate('inactive')}}</label>
                                </div>
                                <div class="form-group">
                                    <label>{{translate('bkash')}} {{translate('api')}} {{translate('key')}}</label>
                                    <input type="text" class="form-control" name="api_key"
                                           value="{{env('APP_MODE')!='demo'?$config['api_key']??'':''}}">
                                </div>
                                <div class="form-group">
                                    <label>{{translate('bkash')}} {{translate('api')}} {{translate('secret')}}</label>
                                    <input type="text" class="form-control" name="api_secret"
                                           value="{{env('APP_MODE')!='demo'?$config['api_secret']??'':''}}" >
                                </div>
                                <div class="form-group">
                                    <label>{{translate('username')}} </label>
                                    <input type="text" class="form-control" name="username"
                                           value="{{env('APP_MODE')!='demo'?$config['username']??'':''}}" >
                                </div>
                                <div class="form-group">
                                    <label>{{translate('password')}} </label>
                                    <input type="text" class="form-control" name="password"
                                           value="{{env('APP_MODE')!='demo'?$config['password']??'':''}}">
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                        onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                        class="btn btn-primary">{{translate('save')}}</button>
                                </div>
                            @else
                                <button type="submit" class="btn btn-primary">{{translate('configure')}}</button>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{translate('paymob')}}</h5>
                    </div>
                    <div class="card-body">
                        @php($config=\App\CentralLogics\Helpers::get_business_settings('paymob'))
                        <form
                            action="{{env('APP_MODE')!='demo'?route('admin.business-settings.payment-method-update',['paymob']):'javascript:'}}"
                            method="post">
                            @csrf
                            @if(isset($config))
                                <div class="form-group">
                                    <label class="control-label">{{translate('paymob')}}</label>
                                </div>
                                <div class="mb-2 d-flex align-iems-center gap-2">
                                    <input type="radio" name="status" value="1" id="paymob_active" {{$config['status']==1?'checked':''}}>
                                    <label class="mb-0 cursor-pointer" for="paymob_active">{{translate('active')}}</label>
                                </div>
                                <div class="form-group d-flex align-iems-center gap-2">
                                    <input type="radio" name="status" value="0" id="paymob_inactive" {{$config['status']==0?'checked':''}}>
                                    <label class="mb-0 cursor-pointer" for="paymob_inactive">{{translate('inactive')}}</label>
                                </div>
                                <div class="form-group">
                                    <label>{{translate('api')}} {{translate('key')}}</label>
                                    <input type="text" class="form-control" name="api_key"
                                           value="{{env('APP_MODE')!='demo'?$config['api_key']??'':''}}">
                                </div>
                                <div class="form-group">
                                    <label>{{translate('iframe_id')}}</label>
                                    <input type="text" class="form-control" name="iframe_id"
                                           value="{{env('APP_MODE')!='demo'?$config['iframe_id']??'':''}}">
                                </div>
                                <div class="form-group">
                                    <label>{{translate('integration_id')}}</label>
                                    <input type="text" class="form-control" name="integration_id"
                                           value="{{env('APP_MODE')!='demo'?$config['integration_id']??'':''}}">
                                </div>
                                <div class="form-group">
                                    <label>{{translate('hmac')}}</label>
                                    <input type="text" class="form-control" name="hmac"
                                           value="{{env('APP_MODE')!='demo'?$config['hmac']??'':''}}">
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                        onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                        class="btn btn-primary">{{translate('save')}}</button>
                                </div>
                            @else
                                <button type="submit" class="btn btn-primary">{{translate('configure')}}</button>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{translate('flutterwave')}}</h5>
                    </div>
                    <div class="card-body">
                        @php($config=\App\CentralLogics\Helpers::get_business_settings('flutterwave'))
                        <form
                            action="{{env('APP_MODE')!='demo'?route('admin.business-settings.payment-method-update',['flutterwave']):'javascript:'}}"
                            method="post">
                            @csrf
                            @if(isset($config))
                                <div class="form-group">
                                    <label class="control-label">{{translate('flutterwave')}}</label>
                                </div>
                                <div class="mb-2 d-flex align-iems-center gap-2">
                                    <input type="radio" name="status" value="1" id="flutter_active" {{$config['status']==1?'checked':''}}>
                                    <label class="mb-0 cursor-pointer" for="flutter_active">{{translate('active')}}</label>
                                </div>
                                <div class="form-group d-flex align-iems-center gap-2">
                                    <input type="radio" name="status" value="0" id="flutter_inactive" {{$config['status']==0?'checked':''}}>
                                    <label class="mb-0 cursor-pointer" for="flutter_inactive">{{translate('inactive')}}</label>
                                </div>
                                <div class="form-group">
                                    <label>{{translate('public_key')}}</label>
                                    <input type="text" class="form-control" name="public_key"
                                           value="{{env('APP_MODE')!='demo'?$config['public_key']??'':''}}">
                                </div>
                                <div class="form-group">
                                    <label>{{translate('secret_key')}}</label>
                                    <input type="text" class="form-control" name="secret_key"
                                           value="{{env('APP_MODE')!='demo'?$config['secret_key']??'':''}}">
                                </div>
                                <div class="form-group">
                                    <label>{{translate('hash')}}</label>
                                    <input type="text" class="form-control" name="hash"
                                           value="{{env('APP_MODE')!='demo'?$config['hash']??'':''}}">
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                        onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                        class="btn btn-primary">{{translate('save')}}</button>
                                </div>
                            @else
                                <button type="submit"
                                        class="btn btn-primary">{{translate('configure')}}</button>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6 pb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{translate('mercadopago')}}</h5>
                    </div>
                    <div class="card-body">
                        @php($config=\App\CentralLogics\Helpers::get_business_settings('mercadopago'))
                        <form
                            action="{{env('APP_MODE')!='demo'?route('admin.business-settings.payment-method-update',['mercadopago']):'javascript:'}}"
                            method="post">
                            @csrf
                            @if(isset($config))
                                <div class="form-group">
                                    <label class="control-label">{{translate('mercadopago')}}</label>
                                </div>
                                <div class="mb-2 d-flex align-iems-center gap-2">
                                    <input type="radio" name="status" value="1" id="mercadopago_active" {{$config['status']==1?'checked':''}}>
                                    <label class="mb-0 cursor-pointer" for="mercadopago_active">{{translate('active')}}</label>
                                </div>
                                <div class="form-group d-flex align-iems-center gap-2">
                                    <input type="radio" name="status" value="0" id="mercadopago_inactive" {{$config['status']==0?'checked':''}}>
                                    <label class="mb-0 cursor-pointer" for="mercadopago_inactive">{{translate('inactive')}}</label>
                                </div>
                                <div class="form-group">
                                    <label>{{translate('public_key')}}</label>
                                    <input type="text" class="form-control" name="public_key"
                                           value="{{env('APP_MODE')!='demo'?$config['public_key']??'':''}}">
                                </div>
                                <div class="form-group">
                                    <label>{{translate('access_token')}}</label>
                                    <input type="text" class="form-control" name="access_token"
                                           value="{{env('APP_MODE')!='demo'?$config['access_token']??'':''}}">
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                        onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                        class="btn btn-primary">{{translate('save')}}</button>
                                </div>
                            @else
                                <button type="submit" class="btn btn-primary">{{translate('configure')}}</button>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_2')

@endpush
