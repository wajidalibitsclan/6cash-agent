@extends('layouts.agent.app')


@section('content')
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-md-12">
                @if (auth()->user()->is_kyc_verified === 0)
                    <div class="alert alert-danger">
                        {{ translate('You account is not verified yet!') }} <a
                            href="{{ route('agent.verify.account') }}">{{ translate('Verify?') }}</a>
                    </div>
                @elseif (auth()->user()->is_kyc_verified === 2)
                    {
                    <div class="alert alert-danger">
                        {{ translate('Your account is blocked!') }}
                    </div>
                    }
                @endif
            </div>
            <div class="col-md-12">
                <div class="container">
                    <div class="row">
                        <div class="col-md-3">
                            <a href="{{ route('agent.send.money') }}">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <i class="tio-users-switch"></i>
                                        <span>{{ translate('Send Money') }}</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('agent.withdraw') }}">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <i class="tio-pound-outlined"></i>
                                        <span>{{ translate('withdraw') }}</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('agent.add.money') }}">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <i class="tio-pound"></i>
                                        <span>{{ translate('Add Money') }}</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('agent.request.money') }}">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <i class="tio-money"></i>
                                        <span>{{ translate('Request Money') }}</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="col-md-12 mt-4">
                <div class="container">
                    <div class="row">
                        @foreach ($linkedWebsites as $websites)
                            <div class="col-md-3">
                                <a href="{{ $websites['url'] }}">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <img style="width:100px"
                                                src="{{ asset('storage/app/public/website/' . $websites['image']) }}"
                                                alt="">

                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div> --}}
            <div class="col-md-12 mt-4">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <canvas id="transactionChart" class="w-100"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('public/assets/admin') }}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset('public/assets/admin') }}/vendor/chart.js.extensions/chartjs-extensions.js"></script>
    <script
        src="{{ asset('public/assets/admin') }}/vendor/chartjs-plugin-datalabels/dist/chartjs-plugin-datalabels.min.js">
    </script>
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
                    data: [{{ $transaction[1] }}, {{ $transaction[2] }}, {{ $transaction[3] }},
                        {{ $transaction[4] }}, {{ $transaction[5] }}, {{ $transaction[6] }},
                        {{ $transaction[7] }}, {{ $transaction[8] }}, {{ $transaction[9] }},
                        {{ $transaction[10] }}, {{ $transaction[11] }}, {{ $transaction[12] }}
                    ],
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
