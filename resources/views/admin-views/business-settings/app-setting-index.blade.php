@extends('layouts.admin.app')

@section('title', translate('App settings'))

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
                <h5 class="mb-0">{{translate('Select Theme for User App')}}</h5>
            </div>
            <div class="card-body mt-3">
                @php($config=\App\CentralLogics\Helpers::get_business_settings('app_theme'))
                <div class="d-flex flex-wrap gap-4">
                    <div class="">
                        <div class="d-flex align-iems-center gap-2">
                            <label class="switcher" for="app_theme_1">
                                <input type="checkbox" name="welcome_status"
                                        class="switcher_input"
                                        id="app_theme_1" {{isset($config) && $config==1?'checked':''}}
                                        onclick="location.href='{{route('admin.business-settings.app_setting_update', ['theme' => 1])}}'">

                                <span class="switcher_control"></span>

                            </label>
                            <h5 class="mb-0">{{translate('Theme 1')}} </h5>
                        </div>

                        <div class="mt-4">
                            <img width="225" class="shadow-lg" src="{{asset('public/assets/admin/img/theme/theme_1.png')}}"/>
                        </div>

                    </div>

                    <div class="">
                        <div class="d-flex align-iems-center gap-2">
                            <label class="switcher" for="app_theme_3">
                                <input type="checkbox" name="welcome_status"
                                        class="switcher_input"
                                        id="app_theme_3" {{isset($config) && $config==3?'checked':''}}
                                        onclick="location.href='{{route('admin.business-settings.app_setting_update', ['theme' => 3])}}'">
                                <span class="switcher_control"></span>

                            </label>
                            <h5 class="mb-0">{{translate('Theme 2')}} </h5>
                        </div>

                        <div class="mt-4">
                            <img width="225" class="shadow-lg" src="{{asset('public/assets/admin/img/theme/theme_2.png')}}"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_2')

@endpush
