@extends('layouts.admin.app')

@section('title', translate('Charge Setup'))

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

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 text-capitalize d-flex align-items-center gap-1"><i
                        class="tio-briefcase"></i> {{translate('Transaction Charges')}} </h5>
            </div>
            <div class="card-body">
                <form action="{{route('admin.business-settings.charge-setup')}}" method="post"
                      enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-sm-6 col-xl-3">
                            @php($agent_commission_percent=\App\CentralLogics\helpers::get_business_settings('agent_commission_percent'))
                            <div class="form-group">
                                <label
                                    class="input-label text-capitalize d-flex flex-wrap align-items-center column-gap-2"
                                    for="agent_commission_percent">{{translate('Agent Commission')}}
                                    <small class="text-danger">( {{translate('percent (%)')}} )</small>

                                    <i class="tio-info cursor-pointer" data-toggle="tooltip" data-placement="top"
                                       title="{{ translate('The agent will get the percentage from cash out charge') }}"></i>
                                </label>
                                <input type="number" name="agent_commission_percent" class="form-control"
                                       id="agent_commission_percent" value="{{$agent_commission_percent??''}}" min="0"
                                       step="any" max="100" required>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            @php($cashout_charge_percent=\App\CentralLogics\helpers::get_business_settings('cashout_charge_percent'))
                            <div class="form-group">
                                <label
                                    class="input-label text-capitalize d-flex flex-wrap align-items-center column-gap-2"
                                    for="cashout_charge_percent">{{translate('cash_out_charge')}}
                                    <small class="text-danger">( {{translate('percent (%)')}} )</small>

                                    <i class="tio-info cursor-pointer" data-toggle="tooltip" data-placement="top"
                                       title="{{ translate('The customer will be charged the percentage of cash out amount') }}"></i>
                                </label>
                                <input type="number" name="cashout_charge_percent" class="form-control"
                                       id="cashout_charge_percent" value="{{$cashout_charge_percent??1}}" min="0"
                                       step="any" max="100" required>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            @php($withdraw_charge_percent = \App\CentralLogics\helpers::get_business_settings('withdraw_charge_percent'))
                            <div class="form-group">
                                <label
                                    class="input-label text-capitalize d-flex flex-wrap align-items-center column-gap-2">{{translate('withdraw_charge')}}
                                    <small class="text-danger">( {{translate('percent (%)')}} )</small>

                                    <i class="tio-info cursor-pointer" data-toggle="tooltip" data-placement="top"
                                       title="{{ translate('The withdraw request sender will be charged the percentage of the withdrawal amount') }}"></i>
                                </label>
                                <input type="number" name="withdraw_charge_percent" class="form-control"
                                       value="{{$withdraw_charge_percent??1}}" min="0" step="any" required>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            @php($sendmoney_charge_flat=\App\CentralLogics\helpers::get_business_settings('sendmoney_charge_flat'))
                            <div class="form-group">
                                <label
                                    class="input-label text-capitalize d-flex flex-wrap align-items-center column-gap-2"
                                    for="sendmoney_charge_flat">
                                    {{translate('send_money_charge')}}
                                    <small class="text-danger">( {{translate('flat')}} )</small>

                                    <i class="tio-info cursor-pointer" data-toggle="tooltip" data-placement="top"
                                       title="{{ translate('The customer will be charged the amount while sending money to others') }}"></i>
                                </label>
                                <input type="number" name="sendmoney_charge_flat" class="form-control"
                                       id="sendmoney_charge_flat" value="{{$sendmoney_charge_flat??0}}" min="0"
                                       step="any" required>
                            </div>
                        </div>
                        {{--@php($refer_commission=\App\CentralLogics\Helpers::get_business_settings('refer_commission'))--}}
                        {{--<div class="col-sm-6 col-xl-4">--}}
                        {{--<div class="form-group">--}}
                        {{--<label class="input-label d-inline" for="exampleFormControlInput1">{{translate('Refer Commission')}}</label>--}}
                        {{--<input type="text" value="{{$refer_commission??0}}"--}}
                        {{--name="refer_commission" class="form-control" placeholder="" required>--}}
                        {{--</div>--}}
                        {{--</div>--}}

                    </div>

                    <div class="d-flex justify-content-end gap-3">
                        <button type="reset" class="btn btn-secondary">{{translate('reset')}}</button>
                        <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                class="btn btn-primary">{{trans('messages.submit')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('script_2')

@endpush
