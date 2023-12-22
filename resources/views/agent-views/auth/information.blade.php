<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required Meta Tags Always Come First -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Title -->
    <title>{{ translate('Agent') }} | {{ translate('Login') }}</title>
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
                            <form class="" action="{{ route('agent.set.information') }}" method="post"
                                id="form-id">
                                @csrf
                                <input type="hidden" name="country_id" id="set_country_id">
                                <input type="hidden" name="otp" value="{{ session('otp') }}">
                                <div class="text-center">
                                    <div class="mb-5">
                                        <h2 class="text-capitalize">{{ translate('information') }}</h2>
                                    </div>
                                </div>
                                <input type="hidden" value="{{ session('phone_no') }}" name="phone">
                                <input type="hidden" value="{{ session('country_code') }}" name="dial_country_code">
                                <!-- Form Group -->
                                <div class="form-group">
                                    <label for="">{{ translate('Select Your Gender') }}</label>
                                    <select id="gender" name="gender"
                                        class="form-control __form-control __form-control-select" required>
                                        <option value="">{{ translate('Select Your Gender') }}</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="occupation"
                                        placeholder="Occupation">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="f_name" placeholder="First Name">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="l_name" placeholder="Last Name">
                                </div>
                                <div class="form-group">
                                    <select name="country" id="" class="form-control"
                                        onchange="selectCity(event)">
                                        <option value="" selected disabled>Select Country</option>
                                        @foreach (\App\Models\Country::where('status', 'active')->get() as $country)
                                            <option value="{{ $country->name }}">{{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <select name="city" id="city-list" class="form-control">
                                        <option value="" selected disabled>Select City</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Email Address(( Optional ))</label>
                                    <input type="text" class="form-control" name="email"
                                        placeholder="Type Email Address">
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
        function selectCity(country) {
            let country_name = country.target.value;
            $.ajax({
                url: "{{ route('agent.city.list') }}",
                type: "post",
                data: {
                    country_name: country_name,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    $("#set_country_id").val(response.country_id);
                    $(".remove-city-list").remove();
                    response.data.forEach(element => {
                        $("#city-list").append(
                            `<option class="remove-city-list" value="${element.name}">${element.name}</option>`
                        )
                    });
                }
            });
        }
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
