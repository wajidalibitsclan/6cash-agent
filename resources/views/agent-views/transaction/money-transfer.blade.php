@extends('layouts.agent.app')


@section('content')
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-md-12 my-5">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h3>Total Debit</h3>
                                <h1>{{ $statistics['debit'] }}</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h3>Total Credit</h3>
                                <h1>{{ $statistics['credit'] }}</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h3>Total Balance</h3>
                                <h1>{{ $statistics['balance'] }}</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h3>Total Comission</h3>
                                <h1>{{ $statistics['commission'] }}</h1>
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
                            <a href="{{ route('agent.transaction.history', ['string' => 'cash_in']) }}"
                                class="btn btn-light">{{ translate('cash_in') }}</a>
                            <a href="{{ route('agent.transaction.history', ['string' => 'cash_out']) }}"
                                class="btn btn-light">{{ translate('cash_out') }}</a>
                            <a href="{{ route('agent.transaction.history', ['string' => 'send_money']) }}"
                                class="btn btn-light">{{ translate('Send Money') }}</a>
                            <a href="{{ route('agent.transaction.history', ['string' => 'received_money']) }}"
                                class="btn btn-light">{{ translate('received_money') }}</a>
                            <a href="{{ route('agent.transaction.history', ['string' => 'add_money']) }}"
                                class="btn btn-light">{{ translate('Add Money') }}</a>

                        </div>
                    </div>
                </div>
                <div class="table-responsive datatable-custom">
                    <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                        id="datatable-custom">
                        <thead class="thead-light">
                            <tr>
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
                            {{-- @foreach ($transaction['transactions'] as $transaction)
                                <tr>
                                    <td>{{ $transaction['transaction_id'] }}</td>
                                    <td>{{ $transaction['transaction_type'] }}</td>
                                    <td>{{ $transaction['debit'] }}</td>
                                    <td>{{ $transaction['credit'] }}</td>
                                    <td>{{ $transaction['amount'] }}</td>
                                    <td>{{ $transaction['user_info']['name'] }}, {{ $transaction['user_info']['phone'] }}
                                    </td>
                                    <td>{{ $transaction['sender'] ? $transaction['sender']['name'] : 'NiLL' }},
                                        {{ $transaction['sender'] ? $transaction['sender']['phone'] : '' }}</td>
                                    <td>
                                        {{ $transaction['receiver'] ? $transaction['receiver']['name'] : 'NiLL' }},
                                        {{ $transaction['receiver'] ? $transaction['receiver']['phone'] : '' }}
                                    </td>
                                </tr>
                            @endforeach --}}
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
