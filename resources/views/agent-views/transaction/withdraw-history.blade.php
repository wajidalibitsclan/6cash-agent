@extends('layouts.agent.app')


@push('css_or_js')
    {{-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        .datatable-custom .dataTables_filter,
        .datatable-custom .dataTables_info,
        .datatable-custom .dataTables_length,
        .datatable-custom .dataTables_paginate {
            display: block !important;
        }
    </style> --}}
@endpush

@section('content')
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-md-12">
                <h1>{{ translate('Withdraw History') }}</h1>
                <div class="mb-3">
                    <div class="d-flex justify-content-start">
                        <div>
                            <a href="{{ route('agent.withdraw.history') }}" class="btn btn-light">{{ translate('All') }}</a>
                            <a href="{{ route('agent.withdraw.history', ['string' => 'pending']) }}"
                                class="btn btn-light">{{ translate('pending') }}</a>
                            <a href="{{ route('agent.withdraw.history', ['string' => 'accepted']) }}"
                                class="btn btn-light">{{ translate('Accepted') }}</a>
                            <a href="{{ route('agent.withdraw.history', ['string' => 'denied']) }}"
                                class="btn btn-light">{{ translate('denied') }}</a>
                        </div>
                    </div>
                </div>
                <div class="datatable-custom">
                    <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                        id="datatable-custom">
                        <thead class="thead-light">
                            <tr>
                                <th>SR#</th>
                                <th>{{ translate('User') }}</th>
                                <th>{{ translate('Amount') }}</th>
                                {{-- <th>{{ translate('admin_charge') }}</th> --}}
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
                            @foreach ($withdrawHistory['content'] as $history)
                                <tr>
                                    <td class="text-center">{{ $history['id'] }}</td>
                                    <td class="text-center">
                                        {{ $history['user']['f_name'] . ' ' . $history['user']['l_name'] }}</td>
                                    <td class="text-center">{{ $history['amount'] }}</td>
                                    {{-- <td class="text-center">{{ $history['admin_charge'] }}</td> --}}
                                    <td class="text-center">{{ $history['request_status'] }}</td>
                                    <td class="text-center">{{ $history['is_paid'] }}</td>
                                    <td class="text-center">{{ $history['sender_note'] }}</td>
                                    <td class="text-center">{{ $history['admin_note'] }}</td>
                                    <td class="text-center">
                                        @if (isset($history['withdrawal_method']))
                                            @forelse ($history['withdrawal_method']['method_fields'] as $fields)
                                                <span class="badge badge-primary">{{ $fields['input_name'] }}</span>
                                            @empty
                                                <span>NiLL</span>
                                            @endforelse
                                        @else
                                            <span>NiLL</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {{ $history['withdrawal_method'] ? $history['withdrawal_method']['method_name'] : 'NiLL' }}

                                    </td>

                                    {{-- <td>{{ $history['withdrawal_method_fields'] }}</td> --}}
                                    {{-- <td>{{ $history['withdrawal_method'] }}</td> --}}
                                    <td>{{ \Carbon\Carbon::parse($history['created_at'])->format('Y-m-d H:i:s') }}</td>
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
                scrollX: true
            }); // Correct
        });
    </script>
@endpush
