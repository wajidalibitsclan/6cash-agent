@extends('layouts.admin.app')

@section('title', translate('SMS Module Setup'))

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

        <div class="row __badge-break">
            <div class="col-md-6">
                <div class="card mb-30">
                    <div class="card-header">
                        <h5 class="mb-0">{{translate('twilio_sms')}}</h5>
                    </div>
                    <div class="card-body">
                        <span class="badge badge-soft-info mb-3">{{translate('NB : #OTP# will be replace with otp')}}</span>
                        @php($config=\App\CentralLogics\Helpers::get_business_settings('twilio_sms'))
                        <form action="{{env('APP_MODE')!='demo'?route('admin.business-settings.sms-module-update',['twilio_sms']):'javascript:'}}"
                              method="post">
                            @csrf

                            <div class="form-group">
                                <label class="control-label">{{translate('twilio_sms')}}</label>
                            </div>
                            <div class="mb-2 d-flex align-iems-center gap-2">
                                <input type="radio" id="twilio_active" name="status" value="1" {{isset($config) && $config['status']==1?'checked':''}}>
                                <label class="mb-0 cursor-pointer" for="twilio_active">{{translate('active')}}</label>
                            </div>

                            <div class="form-group d-flex align-iems-center gap-2">
                                <input type="radio" name="status" id="twilio_inactive" value="0" {{isset($config) && $config['status']==0?'checked':''}}>
                                <label class="mb-0 cursor-pointer" for="twilio_inactive">{{translate('inactive')}} </label>
                            </div>

                            <div class="form-group">
                                <label class="text-capitalize">{{translate('sid')}}</label>
                                <input type="text" class="form-control" name="sid" alue="{{env('APP_MODE')!='demo'?$config['sid']??"":''}}">
                            </div>

                            <div class="form-group">
                                <label class="text-capitalize">{{translate('messaging_service_sid')}}</label>
                                <input type="text" class="form-control" name="messaging_service_sid"
                                       value="{{env('APP_MODE')!='demo'?$config['messaging_service_sid']??"":''}}">
                            </div>

                            <div class="form-group">
                                <label>{{translate('token')}}</label>
                                <input type="text" class="form-control" name="token"
                                       value="{{env('APP_MODE')!='demo'?$config['token']??"":''}}">
                            </div>

                            <div class="form-group">
                                <label>{{translate('from')}}</label>
                                <input type="text" class="form-control" name="from"
                                       value="{{env('APP_MODE')!='demo'?$config['from']??"":''}}">
                            </div>

                            <div class="form-group">
                                <label>{{translate('otp_template')}}</label>
                                <input type="text" class="form-control" name="otp_template"
                                       value="{{env('APP_MODE')!='demo'?$config['otp_template']??"":''}}">
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                    onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                    class="btn btn-primary">{{translate('save')}}</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{translate('2factor_sms')}}</h5>
                    </div>
                    <div class="card-body">
                        <span class="badge badge-soft-info">{{translate("EX of SMS provider's template : your OTP is XXXX here, please check.")}}</span><br>
                        <span class="badge badge-soft-info mb-3">{{translate('NB : XXXX will be replace with otp')}}</span>
                        @php($config=\App\CentralLogics\Helpers::get_business_settings('2factor_sms'))
                        <form action="{{env('APP_MODE')!='demo'?route('admin.business-settings.sms-module-update',['2factor_sms']):'javascript:'}}"
                              method="post">
                            @csrf

                            <div class="form-group">
                                <label class="control-label">{{translate('2factor_sms')}}</label>
                            </div>
                            <div class="mb-2 d-flex align-iems-center gap-2">
                                <input type="radio" name="status" id="towf_active" value="1" {{isset($config) && $config['status']==1?'checked':''}}>
                                <label class="mb-0 cursor-pointer" for="towf_active">{{translate('active')}}</label>
                            </div>
                            <div class="form-group d-flex align-iems-center gap-2">
                                <input type="radio" name="status" id="towf_inactive" value="0" {{isset($config) && $config['status']==0?'checked':''}}>
                                <label class="mb-0 cursor-pointer" for="towf_inactive">{{translate('inactive')}} </label>
                            </div>
                            <div class="form-group">
                                <label class="text-capitalize">{{translate('api_key')}}</label>
                                <input type="text" class="form-control" name="api_key"
                                       value="{{env('APP_MODE')!='demo'?$config['api_key']??"":''}}">
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                    onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                    class="btn btn-primary">{{translate('save')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card mb-30">
                    <div class="card-header">
                        <h5 class="mb-0">{{translate('nexmo_sms')}}</h5>
                    </div>
                    <div class="card-body">
                        <span class="badge badge-soft-info mb-3">{{translate('NB : #OTP# will be replace with otp')}}</span>
                        @php($config=\App\CentralLogics\Helpers::get_business_settings('nexmo_sms'))
                        <form action="{{env('APP_MODE')!='demo'?route('admin.business-settings.sms-module-update',['nexmo_sms']):'javascript:'}}"
                              method="post">
                            @csrf

                            <div class="form-group">
                                <label class="control-label">{{translate('nexmo_sms')}}</label>
                            </div>
                            <div class="mb-2 d-flex align-iems-center gap-2">
                                <input type="radio" name="status" id="nexmo_active" value="1" {{isset($config) && $config['status']==1?'checked':''}}>
                                <label class="mb-0 cursor-pointer" for="nexmo_active">{{translate('active')}}</label>
                                <br>
                            </div>
                            <div class="form-group d-flex align-iems-center gap-2">
                                <input type="radio" name="status" id="nexmo_inactive" value="0" {{isset($config) && $config['status']==0?'checked':''}}>
                                <label class="mb-0 cursor-pointer" for="nexmo_inactive">{{translate('inactive')}} </label>
                                <br>
                            </div>
                            <div class="form-group">
                                <label class="text-capitalize">{{translate('api_key')}}</label>
                                <input type="text" class="form-control" name="api_key"
                                       value="{{env('APP_MODE')!='demo'?$config['api_key']??"":''}}">
                            </div>
                            <div class="form-group">
                                <label>{{translate('api_secret')}}</label>
                                <input type="text" class="form-control" name="api_secret"
                                       value="{{env('APP_MODE')!='demo'?$config['api_secret']??"":''}}">
                            </div>

                            <div class="form-group">
                                <label>{{translate('from')}}</label>
                                <input type="text" class="form-control" name="from"
                                       value="{{env('APP_MODE')!='demo'?$config['from']??"":''}}">
                            </div>

                            <div class="form-group">
                                <label>{{translate('otp_template')}}</label>
                                <input type="text" class="form-control" name="otp_template"
                                       value="{{env('APP_MODE')!='demo'?$config['otp_template']??"":''}}">
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                    onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                    class="btn btn-primary">{{translate('save')}}</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{translate('msg91_sms')}}</h5>
                    </div>
                    <div class="card-body">
                        <span class="badge badge-soft-info mb-3">{{translate('NB : Keep an OTP variable in your SMS providers OTP Template.')}}</span><br>
                        @php($config=\App\CentralLogics\Helpers::get_business_settings('msg91_sms'))
                        <form action="{{env('APP_MODE')!='demo'?route('admin.business-settings.sms-module-update',['msg91_sms']):'javascript:'}}"
                              method="post">
                            @csrf

                            <div class="form-group">
                                <label class="control-label">{{translate('msg91_sms')}}</label>
                            </div>
                            <div class="mb-2 d-flex align-iems-center gap-2">
                                <input type="radio" name="status" id="msg91_active" value="1" {{isset($config) && $config['status']==1?'checked':''}}>
                                <label class="mb-0 cursor-pointer" for="msg91_active">{{translate('active')}}</label>
                                <br>
                            </div>
                            <div class="form-group d-flex align-iems-center gap-2">
                                <input type="radio" name="status" id="msg91_inactive" value="0" {{isset($config) && $config['status']==0?'checked':''}}>
                                <label class="mb-0 cursor-pointer" for="msg91_inactive">{{translate('inactive')}} </label>
                                <br>
                            </div>
                            <div class="form-group">
                                <label class="text-capitalize">{{translate('template_id')}}</label>
                                <input type="text" class="form-control" name="template_id"
                                       value="{{env('APP_MODE')!='demo'?$config['template_id']??"":''}}">
                            </div>
                            <div class="form-group">
                                <label class="text-capitalize">{{translate('authkey')}}</label>
                                <input type="text" class="form-control" name="authkey"
                                       value="{{env('APP_MODE')!='demo'?$config['authkey']??"":''}}">
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                    onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                    class="btn btn-primary">{{translate('save')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_2')

@endpush
