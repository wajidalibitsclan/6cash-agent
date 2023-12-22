@extends('layouts.agent.app')


@section('content')
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-md-12">
                <h1>{{ translate('Send Requests') }}</h1>
                <div class="mb-3">
                    <div class="d-flex justify-content-start">
                        <div>
                            <a href="{{ route('agent.send.request') }}" class="btn btn-light">{{ translate('All') }}</a>
                            <a href="{{ route('agent.send.request', ['string' => 'pending']) }}"
                                class="btn btn-light">{{ translate('pending') }}</a>
                            <a href="{{ route('agent.send.request', ['string' => 'approved']) }}"
                                class="btn btn-light">{{ translate('Approved') }}</a>
                            <a href="{{ route('agent.send.request', ['string' => 'denied']) }}"
                                class="btn btn-light">{{ translate('denied') }}</a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive datatable-custom">
                    <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                        id="datatable-custom">
                        <thead class="thead-light">
                            <tr>
                                <th>ID#</th>
                                <th>{{ translate('Receiver') }}</th>
                                <th>{{ translate('type') }}</th>
                                <th>{{ translate('Amount') }}</th>
                                <th>{{ translate('note') }}</th>
                                <th>{{ translate('CreatedAt') }}</th>
                            </tr>
                        </thead>

                        <tbody id="set-rows">
                            @foreach ($requestMoney['requested_money'] as $requests)
                                <tr>
                                    <td>{{ $requests['id'] }}</td>
                                    <td>{{ $requests['receiver']['name'] . ', ' . $requests['receiver']['phone'] }}</td>
                                    <td>{{ $requests['type'] }}</td>
                                    <td>{{ $requests['amount'] }}</td>
                                    <td>{{ $requests['note'] }}</td>
                                    <td>{{ \Carbon\Carbon::parse($requests['created_at'])->format('Y-m-d H:i:s') }}</td>
                                </tr>
                            @endforeach
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
                // responsive: true,
                // scrollX: true
            }); // Correct
        });
    </script>
@endpush
