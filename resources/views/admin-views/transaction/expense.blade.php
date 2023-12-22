@extends('layouts.admin.app')

@section('title', translate('transaction List'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
            <div class="d-flex align-items-center gap-3">
                <img width="22" src="{{asset('public/assets/admin/img/media/lending.png')}}" alt="">
                <h1 class="page-header-title">{{translate('Expense')}} {{ translate('Transaction') }}</h1>
            </div>

            <div class="">
                <select name="date_range" class="form-control js-select2-custom mn-w160" id="date_range" required>
                    <option value="" selected disabled>{{translate('Filter')}}</option>
                    <option value="this_week" {{$query_param['date_range']=='this_week'?'selected':''}}>{{translate('This Week')}}</option>
                    <option value="this_month" {{$query_param['date_range']=='this_month'?'selected':''}}>{{translate('This Month')}}</option>
                    <option value="last_month" {{$query_param['date_range']=='last_month'?'selected':''}}>{{translate('Last Month')}}</option>
                    <option value="this_year" {{$query_param['date_range']=='this_year'?'selected':''}}>{{translate('This Year')}}</option>
                    <option value="last_year" {{$query_param['date_range']=='last_year'?'selected':''}}>{{translate('Last Year')}}</option>
                </select>
            </div>
        </div>

        <div class="row g-2">
            <div class="col-sm-6 col-xl-4">
                <!-- Card -->
                <div class="dashboard--card h-100">
                    <h6 class="subtitle">{{translate('Total Debit')}}</h6>
                    <h2 class="title">{{ Helpers::set_symbol($total_expense??0) }}</h2>
                    <img src="{{asset('public/assets/admin/img/media/dollar-2.png')}}" class="dashboard-icon" alt="">
                </div>
                <!-- End Card -->
            </div>

            <div class="col-sm-6 col-xl-4">
                <!-- Card -->
                <div class="dashboard--card h-100">
                    <h6 class="subtitle">{{translate('Total Users')}}</h6>
                    <h2 class="title">{{ $total_users??0 }}</h2>
                    <img src="{{asset('public/assets/admin/img/media/dollar-3.png')}}" class="dashboard-icon" alt="">
                </div>
                <!-- End Card -->
            </div>

            <div class="col-sm-6 col-xl-4">
                <!-- Card -->
                <div class="dashboard--card h-100">
                    <h6 class="subtitle">{{translate('Total Transactions')}}</h6>
                    <h2 class="title"> {{ $transactions->total() }}
                    </h2>
                    <img src="{{asset('public/assets/admin/img/media/dollar-4.png')}}" class="dashboard-icon" alt="">
                </div>
                <!-- End Card -->
            </div>
        </div>

        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3 mt-5">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-header flex-between __wrap-gap-10">
                        <div class="d-flex align-items-center gap-2">
                            <h5 class="card-header-title">{{translate('Expense Transactions')}}</h5>
                            <span class="badge badge-soft-secondary text-dark">{{ $transactions->total() }}</span>
                        </div>
                        <div>
                            <form action="{{url()->current()}}" method="get">
                                <div class="input-group">
                                    <input id="datatableSearch_" type="search" name="search"
                                           class="form-control mn-md-w280"
                                           placeholder="{{translate('Search by user info')}}" aria-label="Search"
                                           value="{{$search}}" required autocomplete="off">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary">{{translate('Search')}}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- Table -->
                    <div class="table-responsive datatable-custom">
                        <table
                            class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table table-striped">
                            <thead class="thead-light">
                            <tr>
                                <th>{{translate('SL')}}</th>
                                <th>{{translate('Transaction Id')}}</th>
                                <th>{{translate('Receiver')}}</th>
                                <th>{{translate('Debit')}}</th>
                                <th>{{translate('Type')}}</th>
                                <th>{{translate('Balance')}}</th>
                                <th>{{translate('Time')}}</th>
                            </tr>
                            </thead>

                            <tbody>
                            @forelse($transactions as $key=>$transaction)
                                <tr>
                                    <td>{{$transactions->firstitem()+$key}}</td>
                                    <td>{{ $transaction->transaction_id??'' }}</td>
                                    <td>
                                        @php($receiver_info = Helpers::get_user_info($transaction['to_user_id']))
                                        @if($receiver_info != null)
                                            <a href="{{route('admin.customer.view',[$transaction['to_user_id']])}}">
                                                {{ $receiver_info->f_name ?? '' }} {{ $receiver_info->phone ?? '' }}
                                            </a>
                                        @else
                                            <span class="text-muted badge badge-danger text-dark">{{ translate('User unavailable') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="">
                                            {{ Helpers::set_symbol($transaction['debit']) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-uppercase text-muted badge badge-light">{{ translate($transaction['transaction_type']) }}</span>
                                    </td>
                                    <td>
                                        <span class="">{{ Helpers::set_symbol($transaction['balance']) }}</span>
                                    </td>
                                    <td>
                                        <span class="text-muted badge badge-light">{{ $transaction->created_at->diffForHumans() }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr class="text-center"><td colspan="7">{{translate('No Data Available')}}</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="table-responsive mt-4 px-3">
                        <div class="d-flex justify-content-end">
                            {!! $transactions->links() !!}
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Table -->
        </div>
    </div>

@endsection

@push('script_2')
    <script>
        $("#date_range").change( function() {
            let val = $("#date_range option:selected").val();
            location.href = '{{route('admin.expense.index')}}' + '?date_range=' + val;
        });
    </script>
@endpush
