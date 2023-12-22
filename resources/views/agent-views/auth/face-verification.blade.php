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

    <!-- <script defer src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs"></script> -->
    <!-- <script defer src="https://cdn.jsdelivr.net/npm/face-api.js"></script> -->
    <script defer src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.js.map"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@4.11.0/dist/tf.min.js"></script> -->
    <script defer src="{{ asset('public/face/face.js') }}"></script>

    <style>
        .main {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        #canvas {
            position: relative;
        }

        #video {
            position: relative;
        }

        canvas {
            position: absolute;
            top: 0;
            left: 0;
        }

        .counter-main {
            display: flex;
            justify-content: center;
        }

        .counter {
            background: white;
            height: 50px;
            aspect-ratio: 1;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .progress {
            width: 150px;
            height: 150px;
            line-height: 150px;
            background: none;
            margin: 0 auto;
            box-shadow: none;
            position: relative;
        }

        .progress:after {
            content: "";
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 12px solid #fff;
            position: absolute;
            top: 0;
            left: 0;
        }

        .progress>span {
            width: 50%;
            height: 100%;
            overflow: hidden;
            position: absolute;
            top: 0;
            z-index: 1;
        }

        .progress .progress-left {
            left: 0;
        }

        .progress .progress-bar {
            width: 100%;
            height: 100%;
            background: none;
            border-width: 12px;
            border-style: solid;
            position: absolute;
            top: 0;
        }

        .progress .progress-left .progress-bar {
            left: 100%;
            border-top-right-radius: 80px;
            border-bottom-right-radius: 80px;
            border-left: 0;
            -webkit-transform-origin: center left;
            transform-origin: center left;
        }

        .progress .progress-right {
            right: 0;
        }

        .progress .progress-right .progress-bar {
            left: -100%;
            border-top-left-radius: 80px;
            border-bottom-left-radius: 80px;
            border-right: 0;
            -webkit-transform-origin: center right;
            transform-origin: center right;
            animation: loading-1 1.8s linear forwards;
        }

        .progress .progress-value {
            width: 90%;
            height: 90%;
            border-radius: 50%;
            background: #44484b;
            font-size: 24px;
            color: #fff;
            line-height: 135px;
            text-align: center;
            position: absolute;
            top: 5%;
            left: 5%;
        }

        .progress.blue .progress-bar {
            border-color: #049dff;
        }

        .progress.blue .progress-left .progress-bar {
            animation: loading-2 1.5s linear forwards 1.8s;
        }

        .progress.yellow .progress-bar {
            border-color: #fdba04;
        }

        .progress.yellow .progress-left .progress-bar {
            animation: loading-3 1s linear forwards 1.8s;
        }

        .progress.pink .progress-bar {
            border-color: #ed687c;
        }

        .progress.pink .progress-left .progress-bar {
            animation: loading-4 0.4s linear forwards 1.8s;
        }

        .progress.green .progress-bar {
            border-color: #1abc9c;
        }

        .progress.green .progress-left .progress-bar {
            animation: loading-5 1.2s linear forwards 1.8s;
        }

        @keyframes loading-1 {
            0% {
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(180deg);
                transform: rotate(180deg);
            }
        }

        @keyframes loading-2 {
            0% {
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(144deg);
                transform: rotate(144deg);
            }
        }

        @keyframes loading-3 {
            0% {
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(90deg);
                transform: rotate(90deg);
            }
        }

        @keyframes loading-4 {
            0% {
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(36deg);
                transform: rotate(36deg);
            }
        }

        @keyframes loading-5 {
            0% {
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(126deg);
                transform: rotate(126deg);
            }
        }

        @media only screen and (max-width: 990px) {
            .progress {
                margin-bottom: 20px;
            }
        }
    </style>

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
                    <div class="card">
                        <div class="card-body">
                            <div id="canvas">

                            </div>
                            <video id="videoElement" width="720" height="560" muted autoplay></video>

                            <div class="counter-main">
                                <div class="counter">
                                    <input type="text" class="btn btn-rounded" id="counter-input"
                                        class="btn btn-primary">
                                </div>
                            </div>
                            <div>
                                <h3>Please Blink Your Eyes</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- <canvas id="overlay" width="640" height="480"></canvas> -->


    <script></script>
</body>

</html>
