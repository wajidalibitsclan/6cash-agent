@extends('layouts.admin.app')

@section('title', translate('dashboard'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header pb-2">
            <h1 class="page-header-title text-primary mb-1">{{translate('welcome')}} , {{auth('user')->user()->f_name}}.</h1>
            <p class="welcome-msg">{{ translate('welcome_to_6cash_admin_panel') }}</p>
        </div>
        <!-- End Page Header -->

        <!-- Card -->
        <div class="card card-body mb-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2 pb-1">
                <h5 class="card-title d-flex align-items-center gap-2">
                    <img src="{{asset('public/assets/admin/img/media/business-analytics.png')}}" alt="">
                    {{translate('E-Money Statistics')}}
                </h5>
                <div class="">

                </div>
            </div>

            <div class="row g-2" id="order_stats">
                @include('admin-views.partials._stats', ['data'=>$balance])
            </div>
        </div>
        <!-- End Card -->

        <div class="row gx-2 gx-lg-3 mb-4">
            <div class="col-lg-12">
                <!-- Card -->
                <div class="card h-100">
                    <!-- Body -->
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <h5 class="card-title d-flex align-items-center gap-2">
                                <img src="{{asset('public/assets/admin/img/media/business-analytics.png')}}" class="card-icon" alt="">
                                {{translate('Transaction statistics for business analytics')}}
                            </h5>
                            <div class="mb-2">
                                <div class="d-flex flex-wrap statistics-btn-grp">
                                    <label>
                                        <input type="radio" hidden checked>
                                        <span data-order-type="yearOrder">{{translate('This Year')}}</span>
                                    </label>
                                    {{--<label>
                                        <input type="radio" name="order__statistics" hidden>
                                        <span data-order-type="MonthOrder">This Month</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="order__statistics" hidden>
                                        <span data-order-type="WeekOrder">This Week</span>
                                    </label>--}}
                                </div>
                            </div>
                        </div>

                        <div class="" style="height: 400px">
                            <canvas id="transactionChart" class="w-100"></canvas>
                        </div>

                    </div>
                    <!-- End Body -->
                </div>
                <!-- End Card -->
            </div>
        </div>
        <!-- End Row -->

        <div class="row g-2">
            <div class="col-lg-6 col-xl-4">
                <div class="card h-100">
                    @include('admin-views.partials._top-agent',['top_agents'=>$data['top_agents']])
                </div>
            </div>

            <div class="col-lg-6 col-xl-4">
                <div class="card h-100">
                    @include('admin-views.partials._top-customer',['top_customers'=>$data['top_customers']])
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card h-100">
                    @include('admin-views.partials._top-transactions',['top_transactions'=>$data['top_transactions']])
                </div>
            </div>
        </div>
        <!-- End Row -->


        @endsection

        @push('script')
            <script src="{{asset('public/assets/admin')}}/vendor/chart.js/dist/Chart.min.js"></script>
            <script src="{{asset('public/assets/admin')}}/vendor/chart.js.extensions/chartjs-extensions.js"></script>
            <script
                src="{{asset('public/assets/admin')}}/vendor/chartjs-plugin-datalabels/dist/chartjs-plugin-datalabels.min.js"></script>
        @endpush


        @push('script_2')
            <script>
                var ctx = document.getElementById("transactionChart");
                var myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                        datasets: [{
                            label: 'Transaction',
                            data: [{{$transaction[1]}}, {{$transaction[2]}}, {{$transaction[3]}}, {{$transaction[4]}}, {{$transaction[5]}}, {{$transaction[6]}}, {{$transaction[7]}}, {{$transaction[8]}}, {{$transaction[9]}}, {{$transaction[10]}}, {{$transaction[11]}}, {{$transaction[12]}}],
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(153, 102, 255, 0.2)',
                                'rgba(255, 159, 64, 0.2)',
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(153, 102, 255, 0.2)',
                                'rgba(255, 159, 64, 0.2)'
                            ],
                            borderColor: [
                                'rgba(0,0,0,.2)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)',
                                'rgba(255,99,132,1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)'
                            ],
                            borderWidth: 1,
                        }]
                    },
                    options: {
                        plugins: {
                            datalabels: {
                                display: false,
                            },
                        },
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            xAxes: [{
                                ticks: {
                                    maxRotation: 90,
                                    minRotation: 80
                                },
                                gridLines: {
                                    offsetGridLines: true // à rajouter
                                }
                            }],
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                });
            </script>

    @endpush
