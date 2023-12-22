@extends('layouts.admin.app')

@section('title', translate('Settings'))

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
            <div class="card-body">
                    <form action="{{route('admin.business-settings.system_feature_update')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-sm-6 col-xl-4">
                                @php($add_money_status=\App\CentralLogics\Helpers::get_business_settings('add_money_status'))
                                <div class="d-flex flex-wrap flex-grow-1 justify-content-between mb-4 mb-4">
                                    <span class="text-dark">
                                        {{translate('Add Money')}}
                                    </span>
                                    <label class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                        <input type="hidden" name="add_money_status" value="0">
                                        <input type="checkbox" name="add_money_status" value="1" class="toggle-switch-input" {{ isset($add_money_status) && $add_money_status==1?'checked':''}}>
                                        <span class="toggle-switch-label text">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xl-4">
                                @php($send_money_status=\App\CentralLogics\Helpers::get_business_settings('send_money_status'))
                                <div class="d-flex flex-wrap flex-grow-1 justify-content-between mb-4">
                                    <span class="text-dark">
                                        {{translate('Send Money')}}

                                    </span>
                                    <label class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                        <input type="hidden" name="send_money_status" value="0">
                                        <input type="checkbox" name="send_money_status" value="1" class="toggle-switch-input" {{ isset($send_money_status) && $send_money_status==1?'checked':''}}>
                                        <span class="toggle-switch-label text">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xl-4">
                                @php($cash_out_status=\App\CentralLogics\Helpers::get_business_settings('cash_out_status'))
                                <div class="d-flex flex-wrap flex-grow-1 justify-content-between mb-4">
                                    <span class="text-dark">
                                        {{translate('Cash Out')}}

                                    </span>
                                    <label class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                        <input type="hidden" name="cash_out_status" value="0">
                                        <input type="checkbox" name="cash_out_status" value="1" class="toggle-switch-input" {{ isset($cash_out_status) && $cash_out_status==1?'checked':''}}>
                                        <span class="toggle-switch-label text">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xl-4">
                                @php($send_money_request_status=\App\CentralLogics\Helpers::get_business_settings('send_money_request_status'))
                                <div class="d-flex flex-wrap flex-grow-1 justify-content-between mb-4">
                                    <span class="text-dark">
                                        {{translate('Request Money')}}

                                    </span>
                                    <label class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                        <input type="hidden" name="send_money_request_status" value="0">
                                        <input type="checkbox" name="send_money_request_status" value="1" class="toggle-switch-input" {{ isset($send_money_request_status) && $send_money_request_status==1?'checked':''}}>
                                        <span class="toggle-switch-label text">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xl-4">
                                @php($withdraw_request_status=\App\CentralLogics\Helpers::get_business_settings('withdraw_request_status'))
                                <div class="d-flex flex-wrap flex-grow-1 justify-content-between mb-4">
                                    <span class="text-dark">
                                        {{translate('Withdraw Request')}}

                                    </span>
                                    <label class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                        <input type="hidden" name="withdraw_request_status" value="0">
                                        <input type="checkbox" name="withdraw_request_status" value="1" class="toggle-switch-input" {{ isset($withdraw_request_status) && $withdraw_request_status==1?'checked':''}}>
                                        <span class="toggle-switch-label text">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                    </label>
                                </div>
                            </div>

                            <div class="col-sm-6 col-xl-4">
                                @php($linked_website_status=\App\CentralLogics\Helpers::get_business_settings('linked_website_status'))
                                <div class="d-flex flex-wrap flex-grow-1 justify-content-between mb-4">
                                    <span class="text-dark">
                                        {{translate('Linked Website')}}

                                    </span>
                                    <label class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                        <input type="hidden" name="linked_website_status" value="0">
                                        <input type="checkbox" name="linked_website_status" value="1" class="toggle-switch-input" {{ isset($linked_website_status) && $linked_website_status==1?'checked':''}}>
                                        <span class="toggle-switch-label text">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xl-4">
                                @php($banner_status=\App\CentralLogics\Helpers::get_business_settings('banner_status'))
                                <div class="d-flex flex-wrap flex-grow-1 justify-content-between mb-4">
                                    <span class="text-dark">
                                        {{translate('Banner')}}

                                    </span>
                                    <label class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                        <input type="hidden" name="banner_status" value="0">
                                        <input type="checkbox" name="banner_status" value="1" class="toggle-switch-input" {{ isset($banner_status) && $banner_status==1?'checked':''}}>
                                        <span class="toggle-switch-label text">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                    </label>
                                </div>
                            </div>

                        </div>

                        <div class="d-flex justify-content-end gap-3">
                            <button type="reset" class="btn btn-secondary">{{translate('reset')}}</button>
                            <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}" class="btn btn-primary">{{trans('messages.submit')}}</button>
                        </div>
                    </form>
            </div>
        </div>
    </div>

@endsection

@push('script_2')
    <script>

        function maintenance_mode() {
        @if(env('APP_MODE')=='demo')
            toastr.warning('{{translate('Sorry! You can not enable maintenance mode in demo!')}}');
        @else
            Swal.fire({
                title: '{{translate('Are you sure?')}}',
                text: '{{translate('Be careful before you turn on/off maintenance mode')}}',
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#014F5B',
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.get({
                        url: '#',
                        contentType: false,
                        processData: false,
                        beforeSend: function () {
                            $('#loading').show();
                        },
                        success: function (data) {
                            toastr.success(data.message);
                        },
                        complete: function () {
                            $('#loading').hide();
                        },
                    });
                } else {
                    location.reload();
                }
            })
        @endif
        };

        function readURL(input, viewId) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $(`#${viewId}`).attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function () {
            readURL(this, 'viewer');
        });
        $("#customFileEg2").change(function () {
            readURL(this, 'viewer1');
        });
    </script>

    <script>
        $(document).on('ready', function () {
            @php($country=\App\CentralLogics\Helpers::get_business_settings('country')??'BD')
            $("#country option[value='{{$country}}']").attr('selected', 'selected').change();
        })
    </script>
@endpush
