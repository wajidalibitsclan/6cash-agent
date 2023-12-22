@extends('layouts.agent.app')


@section('content')
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-md-12">
                <h1>{{ translate('Add Money Requests') }}</h1>
                <div class="mb-3">
                    <div class="d-flex justify-content-start">
                        <div>
                            {{-- <a href="{{ route('agent.transaction.history') }}"
                                class="btn btn-light">{{ translate('all') }}</a>
                            <a href="{{ route('agent.transaction.history', ['string' => 'cash_in']) }}"
                                class="btn btn-light">{{ translate('cash_in') }}</a>
                            <a href="{{ route('agent.transaction.history', ['string' => 'cash_out']) }}"
                                class="btn btn-light">{{ translate('cash_out') }}</a>
                            <a href="{{ route('agent.transaction.history', ['string' => 'send_money']) }}"
                                class="btn btn-light">{{ translate('Send Money') }}</a>
                            <a href="{{ route('agent.tranasaction.history', ['string' => 'received_money']) }}"
                                class="btn btn-light">{{ translate('received_money') }}</a>
                            <a href="{{ route('agent.transaction.history', ['string' => 'add_money']) }}"
                                class="btn btn-light">{{ translate('Add Money') }}</a> --}}

                        </div>
                    </div>
                </div>
                <div class="table-responsive datatable-custom">
                    <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                        id="datatable-custom">
                        <thead class="thead-light">
                            <tr>
                                <th>TID#</th>
                                <th>{{ translate('Amount') }}</th>
                                <th>{{ translate('User Info') }}</th>
                                <th>{{ translate('Status') }}</th>
                                <th>{{ translate('CreatedAt') }}</th>
                            </tr>
                        </thead>

                        <tbody id="set-rows">
                            @foreach ($transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->transaction_id }}</td>
                                    <td>{{ $transaction->amount }}</td>
                                    <td>{{ $transaction->user->f_name }} {{ ' ' }}
                                        {{ $transaction->user->l_name }}</td>
                                    <td><span
                                            class="{{ $transaction->status === 'approve' ? 'badge badge-success' : 'badge badge-danger' }}">{{ $transaction->status }}</span>
                                    </td>
                                    <td>{{ $transaction->created_at->diffForHumans() }}</td>
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
