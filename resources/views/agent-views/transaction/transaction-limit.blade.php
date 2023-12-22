@extends('layouts.agent.app')

@section('content')
    <style>
        .monthly-limit {
            display: none
        }
    </style>


    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-md-12">
                <h1>{{ translate('Trasaction Limit') }}</h1>
                <div class="mb-3">
                    <button class="btn btn-light" onclick="switchTab('daily-limit')">{{ translate('Daily Limit') }}</button>
                    <button class="btn btn-light"
                        onclick="switchTab('monthly-limit')">{{ translate('Monthly Limit') }}</button>
                </div>
                <div class="card daily-limit">
                    <div class="card-body">
                        <h1 class="ml-3">{{ translate('Daily Limit') }}</h1>
                        <div class="d-flex mt-4">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="px-3">
                                                        <h3>{{ translate('send_money') }}</h3>
                                                    </div>
                                                    <div>
                                                        <div class="card mb-2">
                                                            <div class="card-body text-center">
                                                                <p>{{ translate('Transaction') }}</p>
                                                                <p>{{ $userData['transaction_limits']['daily_send_money_count'] }}
                                                                    {{ translate('Times') }} <span class="text-primary">(Max
                                                                        25 times)</span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="card mb-2">
                                                            <div class="card-body text-center">
                                                                <p>{{ translate('Total Transaction') }}</p>
                                                                <p>${{ $userData['transaction_limits']['daily_send_money_amount'] }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="px-3">
                                                        <h3>{{ translate('Send Money Request') }}</h3>
                                                    </div>
                                                    <div>
                                                        <div class="card mb-2">
                                                            <div class="card-body text-center">
                                                                <p>{{ translate('Transaction') }}</p>
                                                                <p>{{ $userData['transaction_limits']['daily_send_money_request_count'] }}
                                                                    {{ translate('Times') }} <span
                                                                        class="text-primary">(Max 25 times)</span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="card mb-2">
                                                            <div class="card-body text-center">
                                                                <p>{{ translate('Total Transaction') }}</p>
                                                                <p>${{ $userData['transaction_limits']['daily_send_money_request_amount'] }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="px-3">
                                                        <h3>{{ translate('Add Money') }}</h3>
                                                    </div>
                                                    <div>
                                                        <div class="card mb-2">
                                                            <div class="card-body text-center">
                                                                <p>{{ translate('Transaction') }}</p>
                                                                <p>{{ $userData['transaction_limits']['daily_add_money_count'] }}
                                                                    {{ translate('Times') }} <span
                                                                        class="text-primary">(Max 25 times)</span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="card mb-2">
                                                            <div class="card-body text-center">
                                                                <p>{{ translate('Total Transaction') }}</p>
                                                                <p>${{ $userData['transaction_limits']['daily_add_money_amount'] }}
                                                                </p>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="px-3">
                                                        <h3>{{ translate('withdraw') }}</h3>
                                                    </div>
                                                    <div>
                                                        <div class="card mb-2">
                                                            <div class="card-body text-center">
                                                                <p>{{ translate('Transaction') }}</p>
                                                                <p>{{ $userData['transaction_limits']['daily_withdraw_request_count'] }}
                                                                    {{ translate('Times') }} <span
                                                                        class="text-primary">(Max 25 times)</span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="card mb-2">
                                                            <div class="card-body text-center">
                                                                <p>{{ translate('Total Transaction') }}</p>
                                                                <p>${{ $userData['transaction_limits']['daily_withdraw_request_amount'] }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card monthly-limit">
                    <div class="card-body">
                        <h1>{{ translate('Monthly Limit') }}</h1>
                        <div class="d-flex">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="px-3">
                                                        <h3>{{ translate('Send Money') }}</h3>
                                                    </div>
                                                    <div>
                                                        <div class="card mb-2">
                                                            <div class="card-body text-center">
                                                                <p>{{ translate('Transaction') }}</p>
                                                                <p>{{ $userData['transaction_limits']['monthly_send_money_count'] }}
                                                                    {{ translate('Times') }} <span
                                                                        class="text-primary">(Max 25 times)</span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="card mb-2">
                                                            <div class="card-body text-center">
                                                                <p>{{ translate('Total Transaction') }}</p>
                                                                <p>${{ $userData['transaction_limits']['monthly_send_money_amount'] }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="px-3">
                                                        <h3>{{ translate('Send Money Request') }}</h3>
                                                    </div>
                                                    <div>
                                                        <div class="card mb-2">
                                                            <div class="card-body text-center">
                                                                <p>{{ translate('Transaction') }}</p>
                                                                <p>{{ $userData['transaction_limits']['monthly_send_money_request_count'] }}
                                                                    {{ translate('Times') }} <span
                                                                        class="text-primary">(Max 25 times)</span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="card mb-2">
                                                            <div class="card-body text-center">
                                                                <p>{{ translate('Total Transaction') }}</p>
                                                                <p>${{ $userData['transaction_limits']['monthly_send_money_request_amount'] }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="px-3">
                                                        <h3>{{ translate('Add Money') }}</h3>
                                                    </div>
                                                    <div>
                                                        <div class="card mb-2">
                                                            <div class="card-body text-center">
                                                                <p>{{ translate('Transaction') }}</p>
                                                                <p>{{ $userData['transaction_limits']['monthly_add_money_count'] }}
                                                                    {{ translate('Times') }} <span
                                                                        class="text-primary">(Max 25 times)</span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="card mb-2">
                                                            <div class="card-body text-center">
                                                                <p>{{ translate('Total Transaction') }}</p>
                                                                <p>${{ $userData['transaction_limits']['monthly_add_money_amount'] }}
                                                                </p>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="px-3">
                                                        <h3>{{ translate('withdraw') }}</h3>
                                                    </div>
                                                    <div>
                                                        <div class="card mb-2">
                                                            <div class="card-body text-center">
                                                                <p>{{ translate('Transaction') }}</p>
                                                                <p>{{ $userData['transaction_limits']['monthly_withdraw_request_count'] }}
                                                                    {{ translate('Times') }} <span
                                                                        class="text-primary">(Max 25 times)</span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="card mb-2">
                                                            <div class="card-body text-center">
                                                                <p>{{ translate('Total Transaction') }}</p>
                                                                <p>${{ $userData['transaction_limits']['monthly_withdraw_request_amount'] }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
    <script>
        function switchTab(tab) {
            if (tab === 'daily-limit') {
                $(".monthly-limit").css('display', 'none');
                $("." + tab).css('display', 'block');
            } else {
                $(".daily-limit").css('display', 'none');
                $("." + tab).css('display', 'block');
            }
        }
    </script>
@endpush
