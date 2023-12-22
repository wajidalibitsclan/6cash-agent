<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required Meta Tags Always Come First -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Title -->
    <title>{{translate('Merchant')}} | {{translate('Login')}}</title>

    <!-- Favicon -->
    <link rel="shortcut icon"
          href="{{asset('storage/app/public/favicon')}}/{{Helpers::get_business_settings('favicon') ?? null}}"/>

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&amp;display=swap" rel="stylesheet">
    <!-- CSS Implementing Plugins -->
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/css/vendor.min.css">
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/vendor/icon-set/style.css">
    <!-- CSS Front Template -->
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/css/theme.minc619.css?v=1.0">
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/css/style.css">
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/css/custom.css">
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/css/toastr.css">

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body>
<!-- ========== MAIN CONTENT ========== -->
<main id="content" role="main" class="main h-100vh d-flex flex-column justify-content-center">
    <div class="position-fixed top-0 right-0 left-0 bg-img-hero h-100"
         style="background-image: url({{asset('public/assets/admin')}}/svg/components/login_background.svg);opacity: 0.5">
    </div>
@php($systemlogo=\App\Models\BusinessSetting::where(['key'=>'logo'])->first()->value??'')
<!-- Content -->
    <div class="container py-5 d-flex justify-content-center">
        <!-- Card -->
        <div class="login-card d-inline-block">
            <div class="row no-gutters">
                <div class="col-md-6">
                    <div class="bg-primary h-100 d-flex align-items-center justify-content-center py-5">
                        <div class="text-center">
                            <h1 class="text-white text-uppercase">{{ translate('Welcome to '. Helpers::get_business_settings('business_name') ?? translate('6cash')) }}</h1>
                            <hr class="bg-white" style="width: 40%">
                            <div class="text-white text-uppercase">
                                <span class="w-50 d-inline-block">
                                    {{ translate((Helpers::get_business_settings('business_name') ?? translate('6cash')) . ' is a secured and user-friendly digital wallet') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="bg-white h-100 d-flex align-items-center justify-content-center py-5 px-4">
                        <!-- Form -->
                        <form class="" action="{{route('merchant.auth.login')}}" method="post" id="form-id">
                            @csrf
                            <div class="text-center">
                                <div class="mb-5">
                                    <h2 class="text-capitalize">{{translate('sign in')}}</h2>
                                </div>
                            </div>

                            <!-- Form Group -->
                            <div class="js-form-message form-group merchant-login-form-group">
                                <select id="country_code" name="country_code" class="form-control __form-control __form-control-select" required>
                                    <option value="">{{ translate('country code') }}</option>
                                    @foreach(PHONE_CODE as $country_code)
                                        <option value="{{ $country_code['code'] }}" {{ strpos($country_code['name'], $current_user_info && $current_user_info->countryName) !== false ? 'selected' : '' }}>{{ $country_code['name'] }}</option>
                                    @endforeach
                                </select>
                                <input type="text" class="form-control __form-control __form-control-input" name="phone" id="phone" required
                                    tabindex="1" placeholder="{{translate('Enter your phone no.')}}"
                                    data-msg="{{translate('Please enter a valid phone number.')}}">
                            </div>
                            <!-- End Form Group -->

                            <!-- Form Group -->
                            <div class="js-form-message form-group">
                                <div class="input-group input-group-merge">
                                    <input type="password"
                                        class="js-toggle-password form-control __form-control"
                                        name="password" id="signupSrPassword"
                                        placeholder="{{translate('Enter your password')}}"
                                        aria-label="8+ characters required" required
                                        data-msg="{{translate('Your password is invalid. Please try again.')}}"
                                        data-hs-toggle-password-options='{
                                                        "target": "#changePassTarget",
                                                "defaultClass": "tio-hidden-outlined",
                                                "showClass": "tio-visible-outlined",
                                                "classChangeTarget": "#changePassIcon"
                                                }'>
                                    <div id="changePassTarget" class="input-group-append">
                                        <a class="input-group-text" href="javascript:">
                                            <i id="changePassIcon" class="tio-visible-outlined"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- End Form Group -->

                            <!-- Checkbox -->
                            {{-- <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="termsCheckbox" name="remember">
                                    <label class="custom-control-label text-muted" for="termsCheckbox">{{translate('remember me')}}</label>
                                </div>
                            </div> --}}

                            {{-- recaptcha --}}
                            @php($recaptcha = \App\CentralLogics\Helpers::get_business_settings('recaptcha'))
                            @if(isset($recaptcha) && $recaptcha['status'] == 1)
                                <div class="w-100" id="recaptcha_element" data-type="image"></div>
                            @else
                                <div class="row g-2">
                                    <div class="col-6">
                                        <input type="text" class="form-control form-control-lg border-0" name="default_captcha_value" value="" placeholder="{{translate('Enter captcha')}}" autocomplete="off">
                                    </div>
                                    <div class="col-6 input-icons bg-white rounded" style="cursor: pointer" data-toggle="tooltip" data-placement="right" title="{{translate('Click to refresh')}}">
                                        <a onclick="javascript:re_captcha();">
                                            <img src="{{ URL('/admin/auth/code/captcha/1') }}" class="input-field h-75 rounded-10 border-bottom-0" id="default_recaptcha_id" style="width: 90%;">
                                            {{--<i class="tio-refresh icon"></i>--}}
                                        </a>
                                    </div>
                                </div>
                            @endif

                            @if(env('APP_MODE')=='demo')
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-10">
                                            <span>{{translate('Phone')}} : +880123456789</span><br>
                                            <span>{{translate('Password')}} : {{translate('12345678')}}</span>
                                        </div>
                                        <div class="col-2">
                                            <span class="btn btn-primary" onclick="copy_cred()"><i class="tio-copy"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="d-flex justify-content-center mt-5">
                                <button type="submit" class="btn btn-primary sign-in-button">{{translate('sign_in')}}</button>
                            </div>
                        </form>
                        <!-- End Form -->
                    </div>
                </div>
            </div>
        </div>
        <!-- End Card -->
    </div>
    <!-- End Content -->
</main>
<!-- ========== END MAIN CONTENT ========== -->


<!-- JS Implementing Plugins -->
<script src="{{asset('public/assets/admin')}}/js/vendor.min.js"></script>

<!-- JS Front -->
<script src="{{asset('public/assets/admin')}}/js/theme.min.js"></script>
<script src="{{asset('public/assets/admin')}}/js/toastr.js"></script>
{!! Toastr::message() !!}

@if ($errors->any())
    <script>
        @foreach($errors->all() as $error)
        toastr.error('{{$error}}', Error, {
            CloseButton: true,
            ProgressBar: true
        });
        @endforeach
    </script>
@endif

<!-- JS Plugins Init. -->
<script>
    $(document).on('ready', function () {
        // INITIALIZATION OF SHOW PASSWORD
        // =======================================================
        $('.js-toggle-password').each(function () {
            new HSTogglePassword(this).init()
        });

        // INITIALIZATION OF FORM VALIDATION
        // =======================================================
        $('.js-validate').each(function () {
            $.HSCore.components.HSValidation.init($(this));
        });
    });
</script>

{{-- recaptcha scripts start --}}
@if(isset($recaptcha) && $recaptcha['status'] == 1)
    <script type="text/javascript">
        var onloadCallback = function () {
            grecaptcha.render('recaptcha_element', {
                'sitekey': '{{ \App\CentralLogics\Helpers::get_business_settings('recaptcha')['site_key'] }}'
            });
        };
    </script>
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
    <script>
        $("#form-id").on('submit',function(e) {
            var response = grecaptcha.getResponse();

            if (response.length === 0) {
                e.preventDefault();
                toastr.error("{{translate('Please check the recaptcha')}}");
            }
        });
    </script>
@else
    <script type="text/javascript">
        function re_captcha() {
            $url = "{{ URL('/merchant/auth/code/captcha') }}";
            $url = $url + "/" + Math.random();
            document.getElementById('default_recaptcha_id').src = $url;
            console.log('url: '+ $url);
        }
    </script>
@endif
{{-- recaptcha scripts end --}}

@if(env('APP_MODE')=='demo')
    <script>
        function copy_cred() {
            $('#phone').val('+8801100000000');
            $('#signupSrPassword').val('12345678');
            toastr.success('Copied successfully!', 'Success!', {
                CloseButton: true,
                ProgressBar: true
            });
            return false;
        }
    </script>
@endif

<!-- IE Support -->
<script>
    if (/MSIE \d|Trident.*rv:/.test(navigator.userAgent)) document.write('<script src="{{asset('public//assets/admin')}}/vendor/babel-polyfill/polyfill.min.js"><\/script>');
</script>
</body>
</html>
