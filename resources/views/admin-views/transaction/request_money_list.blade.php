@extends('layouts.admin.app')

@section('title', translate('Add money requests'))

@push('css_or_js')
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="d-flex align-items-center gap-3 mb-3">
            <img width="24" src="{{ asset('public/assets/admin/img/media/dollar-2.png') }}" alt="">
            <h2 class="page-header-title">{{ translate('Agent Requested Transactions') }}</h2>
        </div>
        <!-- End Page Header -->

        <div class="card">
            <div class="card-header flex-between __wrap-gap-10">
                <div class="d-flex align-items-center gap-2">
                    <h5 class="card-header-title">{{ translate('transaction Table') }}</h5>
                    <span class="badge badge-soft-secondary text-dark">{{ $request_money->total() }}</span>
                </div>
                <div class="flex-between">
                    <div>
                        <form action="{{ url()->current() }}" method="GET">
                            <div class="input-group">
                                <input id="datatableSearch_" type="search" name="search" class="form-control mn-md-w280"
                                    placeholder="{{ translate('Search by Agent') }}" aria-label="Search"
                                    value="{{ $search }}" required autocomplete="off">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary">{{ translate('Search') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Table -->
            <div class="table-responsive datatable-custom">
                <table
                    class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th>{{ translate('SL') }}</th>
                            <th>{{ translate('Agent') }}</th>
                            <th>{{ translate('Requested Amount') }}</th>
                            <th>{{ translate('Note') }}</th>
                            <th>{{ translate('Status') }}</th>
                            <th>{{ translate('Requested time') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($request_money as $key => $items)
                            <tr>
                                <td>
                                    {{ $request_money->firstitem() + $key }}
                                </td>
                                <td>
                                    @php($user = Helpers::get_user_info($items->from_user_id))
                                    @if (isset($user))
                                        <span class="d-block font-size-sm text-body">
                                            <a href="{{ route('admin.customer.view', [$user->id]) }}">
                                                {{ $user->f_name . ' ' . $user->l_name }}
                                            </a>
                                        </span>
                                    @else
                                        <span class="badge badge-soft-danger">{{ translate('User unavailable') }}</span>
                                    @endif
                                </td>
                                <td>{{ Helpers::set_symbol($items->amount) }}</td>
                                {{-- <td style="width: 30%">{{ $items->note }}</td> --}}
                                <td>
                                    <div class="mx-w300 mn-w160 text-wrap">
                                        {{ $items->note }}
                                    </div>
                                </td>
                                <td>
                                    @if (isset($user))
                                        @if ($items->type == 'pending')
                                            <div class="d-flex gap-2">
                                                {{-- <span class="btn btn-warning btn-sm" style="cursor: default"> {{translate('Pending')}}</span> --}}
                                                <a href="{{ route('admin.transaction.request_money_status_change', ['approve', 'id' => $items->id]) }}"
                                                    class="btn btn-primary btn-sm"> {{ translate('Approve') }}</a>
                                                <a href="{{ route('admin.transaction.request_money_status_change', ['deny', 'id' => $items->id]) }}"
                                                    class="btn btn-warning btn-sm"> {{ translate('Deny') }}</a>
                                            </div>
                                        @elseif($items->type == 'approved')
                                            <span class="badge badge-soft-success"> {{ translate('Approved') }}</span>
                                        @elseif($items->type == 'denied')
                                            <span class="badge badge-soft-danger"> {{ translate('Denied') }}</span>
                                        @endif
                                    @else
                                        @if ($items->type == 'pending')
                                            <div data-toggle="tooltip" data-placement="left"
                                                title="{{ translate('User unavailable') }}">
                                                {{-- <span class="btn btn-warning btn-sm" style="cursor: default"> {{translate('Pending')}}</span> --}}
                                                <a href="#" class="btn btn-primary btn-sm disabled">
                                                    {{ translate('Approve') }}</a>
                                                <a href="#" class="btn btn-warning btn-sm disabled">
                                                    {{ translate('Deny') }}</a>
                                            </div>
                                        @elseif($items->type == 'approved')
                                            <span class="badge badge-soft-success"> {{ translate('Approved') }}</span>
                                        @elseif($items->type == 'denied')
                                            <span class="badge badge-soft-danger"> {{ translate('Denied') }}</span>
                                        @endif
                                    @endif
                                </td>
                                <td>{{ $items->created_at->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="table-responsive mt-4 px-3">
                <div class="d-flex justify-content-end">
                    {!! $request_money->links() !!}
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script_2')
@endpush
