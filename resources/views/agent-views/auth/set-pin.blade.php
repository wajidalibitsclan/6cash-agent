<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required Meta Tags Always Come First -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Title -->
    <title>{{ translate('Agent') }} | {{ translate('Set PIN') }}</title>

    <!-- Favicon -->
    <link rel="shortcut icon"
        href="{{ asset('storage/app/public/favicon') }}/{{ Helpers::get_business_settings('favicon') ?? null }}" />

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&amp;display=swap" rel="stylesheet">
    <!-- CSS Implementing Plugins -->
    <link rel="stylesheet" href="{{ asset('public/assets/admin') }}/css/vendor.min.css">
    <link rel="stylesheet" href="{{ asset('public/assets/admin') }}/vendor/icon-set/style.css">
    <!-- CSS Front Template -->
    <link rel="stylesheet" href="{{ asset('public/assets/admin') }}/css/theme.minc619.css?v=1.0">
    <link rel="stylesheet" href="{{ asset('public/assets/admin') }}/css/style.css">
    <link rel="stylesheet" href="{{ asset('public/assets/admin') }}/css/custom.css">
    <link rel="stylesheet" href="{{ asset('public/assets/admin') }}/css/toastr.css">

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body>
    <!-- ========== MAIN CONTENT ========== -->
    <main id="content" role="main" class="main h-100vh d-flex flex-column justify-content-center">
        <div class="position-fixed top-0 right-0 left-0 bg-img-hero h-100"
            style="background-image: url({{ asset('public/assets/admin') }}/svg/components/login_background.svg);opacity: 0.5">
        </div>
        @php($systemlogo = \App\Models\BusinessSetting::where(['key' => 'logo'])->first()->value ?? '')
        <!-- Content -->
        <div class="container py-5 d-flex justify-content-center">
            <!-- Card -->
            <div class="login-card d-inline-block">
                <div class="row no-gutters">
                    <div class="col-md-6">
                        <div class="bg-primary h-100 d-flex align-items-center justify-content-center py-5">
                            <div class="text-center">
                                <h1 class="text-white text-uppercase">
                                    {{ translate('Welcome to ' . Helpers::get_business_settings('business_name') ?? translate('6cash')) }}
                                </h1>
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
                            <form class="" action="{{ route('agent.auth.registered') }}" method="post"
                                id="form-id">
                                @csrf
                                <div class="text-center">
                                    <div class="mb-5">
                                        <h2 class="text-capitalize">
                                            {{ translate('set your 4 digit Pin for future login') }}</h2>
                                    </div>
                                </div>

                                <div>
                                    <input type="hidden" name="otp" value="{{ session('otp') }}">
                                    <input type="hidden" name="f_name" value="{{ session('user')['f_name'] }}">
                                    <input type="hidden" name="l_name" value="{{ session('user')['l_name'] }}">
                                    <input type="hidden" name="email" value="{{ session('user')['email'] }}">
                                    <input type="hidden" name="phone" value="{{ session('user')['phone'] }}">
                                    <input type="hidden" name="country" value="{{ session('user')['country'] }}">
                                    <input type="hidden" name="city" value="{{ session('user')['city'] }}">
                                    <input type="hidden" name="city_id" value="{{ session('user')['city_id'] }}">
                                    <input type="hidden" name="country_id"
                                        value="{{ session('user')['country_id'] }}">


                                    <input type="hidden" name="dial_country_code"
                                        value="{{ session('user')['dial_country_code'] }}">
                                    <input type="hidden" name="gender" value="{{ session('user')['gender'] }}">
                                    <input type="hidden" name="occupation"
                                        value="{{ session('user')['occupation'] }}">


                                </div>

                                <!-- Form Group -->
                                <div class="js-form-message form-group">
                                    <div class="input-group input-group-merge">
                                        <input type="password" class="js-toggle-password form-control __form-control"
                                            name="password" id="signupSrPassword"
                                            placeholder="{{ translate('set your PIN') }}"
                                            oninput="validateInput(event)" aria-label="4 characters required" required
                                            data-msg="{{ translate('Your PIN is invalid. Please try again.') }}"
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
                                <div class="js-form-message form-group">
                                    <div class="input-group input-group-merge">
                                        <input type="password" class="js-toggle-password form-control __form-control"
                                            name="c_password" id="signupSrPassword"
                                            placeholder="{{ translate('confirm PIN') }}"
                                            oninput="validateInput(event)" aria-label="4 characters required" required
                                            data-msg="{{ translate('confirm PIN is invalid. Please try again.') }}"
                                            data-hs-toggle-password-options='{
                                                        "target": "#ConfirmchangePassTarget",
                                                "defaultClass": "tio-hidden-outlined",
                                                "showClass": "tio-visible-outlined",
                                                "classChangeTarget": "#ConfirmchangePassIcon"
                                                }'>
                                        <div id="ConfirmchangePassTarget" class="input-group-append">
                                            <a class="input-group-text" href="javascript:">
                                                <i id="ConfirmchangePassIcon" class="tio-visible-outlined"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Form Group -->





                                @if (env('APP_MODE') == 'demo')
                                    <div class="card-footer">
                                        <div class="row">
                                            <div class="col-10">
                                                <span>{{ translate('Phone') }} : +880123456789</span><br>
                                                <span>{{ translate('Password') }} : {{ translate('12345678') }}</span>
                                            </div>
                                            <div class="col-2">
                                                <span class="btn btn-primary" onclick="copy_cred()"><i
                                                        class="tio-copy"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="d-flex justify-content-center mt-5">
                                    <button type="submit"
                                        class="btn btn-primary sign-in-button">{{ translate('Proceed') }}</button>
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
    <script src="{{ asset('public/assets/admin') }}/js/vendor.min.js"></script>

    <!-- JS Front -->
    <script src="{{ asset('public/assets/admin') }}/js/theme.min.js"></script>
    <script src="{{ asset('public/assets/admin') }}/js/toastr.js"></script>
    {!! Toastr::message() !!}

    @if ($errors->any())
        <script>
            @foreach ($errors->all() as $error)
                toastr.error('{{ $error }}', Error, {
                    CloseButton: true,
                    ProgressBar: true
                });
            @endforeach
        </script>
    @endif

    <!-- JS Plugins Init. -->
    <script>
        $(document).on('ready', function() {
            // INITIALIZATION OF SHOW PASSWORD
            // =======================================================
            $('.js-toggle-password').each(function() {
                new HSTogglePassword(this).init()
            });

            // INITIALIZATION OF FORM VALIDATION
            // =======================================================
            $('.js-validate').each(function() {
                $.HSCore.components.HSValidation.init($(this));
            });
        });

        function validateInput(event) {
            const input = event.target;
            const inputValue = input.value.replace(/\D/g, ''); // Remove non-digits

            if (inputValue.length > 4) {
                input.value = inputValue.slice(0, 4); // Keep only the first 4 digits
            } else {
                input.value = inputValue;
            }
        }
    </script>

    {{-- recaptcha scripts start --}}
    @if (isset($recaptcha) && $recaptcha['status'] == 1)
        <script type="text/javascript">
            var onloadCallback = function() {
                grecaptcha.render('recaptcha_element', {
                    'sitekey': '{{ \App\CentralLogics\Helpers::get_business_settings('recaptcha')['site_key'] }}'
                });
            };
        </script>
        <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
        <script>
            $("#form-id").on('submit', function(e) {
                var response = grecaptcha.getResponse();

                if (response.length === 0) {
                    e.preventDefault();
                    toastr.error("{{ translate('Please check the recaptcha') }}");
                }
            });
        </script>
    @else
        <script type="text/javascript">
            function re_captcha() {
                $url = "{{ URL('/agent/auth/code/captcha') }}";
                $url = $url + "/" + Math.random();
                document.getElementById('default_recaptcha_id').src = $url;
                console.log('url: ' + $url);
            }
        </script>
    @endif
    {{-- recaptcha scripts end --}}

    @if (env('APP_MODE') == 'demo')
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
        if (/MSIE \d|Trident.*rv:/.test(navigator.userAgent)) document.write(
            '<script src="{{ asset('public//assets/admin') }}/vendor/babel-polyfill/polyfill.min.js"><\/script>');
    </script>
</body>

</html>
