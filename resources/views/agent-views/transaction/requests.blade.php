@extends('layouts.agent.app')


@section('content')
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-md-12">
                <h1>{{ translate('Requests') }}</h1>
                <div class="mb-3">
                    <div class="d-flex justify-content-start">
                        <div>
                            <a href="{{ route('agent.withdraw.history') }}" class="btn btn-light">{{ translate('All') }}</a>
                            <a href="{{ route('agent.withdraw.history', ['string' => 'pending']) }}"
                                class="btn btn-light">{{ translate('pending') }}</a>
                            <a href="{{ route('agent.withdraw.history', ['string' => 'accepted']) }}"
                                class="btn btn-light">{{ translate('Accepted') }}</a>
                            <a href="{{ route('agent.withdraw.history', ['string' => 'denied']) }}"
                                class="btn btn-light">{{ translate('Denied') }}</a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive datatable-custom">
                    <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                        <thead class="thead-light">
                            <tr>
                                <th>SR#</th>
                                <th>{{ translate('User') }}</th>
                                <th>{{ translate('Amount') }}</th>
                                <th>{{ translate('Admin Charge') }}</th>
                                <th>{{ translate('Request_Status') }}</th>
                                <th>{{ translate('Paid') }}</th>
                                <th>{{ translate('Sender Note') }}</th>
                                <th>{{ translate('Admin_Note') }}</th>
                                <th>{{ translate('Withdraw Method Fields') }}</th>
                                <th>{{ translate('Withdraw Method') }}</th>
                                <th>{{ translate('CreatedAt') }}</th>
                            </tr>
                        </thead>

                        <tbody id="set-rows">


                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
