@extends('layouts.agent.app')


@section('content')
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-md-12 my-5">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h3>Cash out</h3>
                                <h1>{{ $statistics['debit'] }} <span class="eur">EUR</span></h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h3>Cash in</h3>
                                <h1>{{ $statistics['credit'] }} <span class="eur">EUR</span></h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h3>Total Balance</h3>
                                <h1>{{ $statistics['balance'] }} <span class="eur">EUR</span></h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h3>Total Comission</h3>
                                <h1>{{ $statistics['commission'] }} <span class="eur">EUR</span></h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <h1>{{ translate('Trasaction History') }}</h1>

                <div class="mb-3">
                    <div class="d-flex justify-content-start">
                        <div>
                            <a href="{{ route('agent.transaction.history') }}"
                                class="btn btn-light">{{ translate('all') }}</a>
                            {{-- <a href="{{ route('agent.transaction.history', ['string' => 'cash_in']) }}"
                                class="btn btn-light">{{ translate('cash_in') }}</a>
                            <a href="{{ route('agent.transaction.history', ['string' => 'cash_out']) }}"
                                class="btn btn-light">{{ translate('cash_out') }}</a> --}}
                            <a href="{{ route('agent.transaction.history', ['string' => 'send_money']) }}"
                                class="btn btn-light">{{ translate('Cash Out') }}</a>
                            <a href="{{ route('agent.transaction.history', ['string' => 'received_money']) }}"
                                class="btn btn-light">{{ translate('Cash In') }}</a>
                            <a href="{{ route('agent.transaction.history', ['string' => 'add_money']) }}"
                                class="btn btn-light">{{ translate('Add Money') }}</a>
                            <a href="{{ route('agent.transaction.history', ['string' => 'cancel']) }}"
                                class="btn btn-light">{{ translate('Canceled') }}</a>
                            {{-- <a href="{{ route('agent.transaction.history', ['string' => 'reject']) }}"
                                class="btn btn-light">{{ translate('Rejected') }}</a> --}}

                        </div>
                    </div>
                </div>
                <div class="table-responsive datatable-custom">
                    <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                        id="datatable-custom">
                        <thead class="thead-light">
                            <tr>
                                <th>ID#</th>
                                <th>TID#</th>
                                <th>{{ translate('Transaction Type') }}</th>
                                <th>{{ translate('Debit') }}</th>
                                <th>{{ translate('credit') }}</th>
                                <th>{{ translate('Amount') }}</th>
                                <th>{{ translate('User Info') }}</th>
                                <th>{{ translate('Sender') }}</th>
                                <th>{{ translate('Receiver') }}</th>
                            </tr>
                        </thead>

                        <tbody id="set-rows">
                            @foreach ($transaction as $key => $transaction)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $transaction['transaction_id'] }}</td>
                                    <td>{{ $transaction['transaction_type'] }}</td>
                                    <td>{{ $transaction['debit'] }} <span class="eur">EUR</span></td>
                                    <td>{{ $transaction['credit'] }} <span class="eur">EUR</span></td>
                                    <td>{{ $transaction->balance }} <span class="eur">EUR</span></td>
                                    <td>{{ $transaction->agent ? $transaction->agent->f_name : 'NiLL' }},
                                        {{ $transaction->agent ? $transaction->agent->phone : 'NiLL' }}
                                    </td>
                                    <td>{{ $transaction->sender ? $transaction->sender->f_name : 'NiLL' }},
                                        {{ $transaction->sender ? $transaction->sender->phone : 'NiLL' }}</td>
                                    <td>
                                        {{ $transaction->receiver ? $transaction->receiver->f_name : 'NiLL' }},
                                        {{ $transaction->receiver ? $transaction->receiver->phone : 'NiLL' }}</td>
                                    </td>
                                </tr>
                            @endforeach
                            {{-- @empty
                                <tr class="text-center">
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-center">{{ translate('Empty Records!') }}</td>
                                </tr>
                            @endforelse --}}

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        $(document).ready(function() {
            $('#datatable-custom').DataTable({
                responsive: true,
                // scrollX: true
            }); // Correct
        });
    </script>
@endpush
