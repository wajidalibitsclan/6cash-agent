@extends('layouts.merchant.app')

@section('title', translate('dashboard'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header pb-2">
            <h1 class="page-header-title text-primary mb-1">{{translate('welcome')}} , {{auth('user')->user()->f_name}}.</h1>
            <p>{{ translate('welcome_to_6cash_merchant_panel') }}</p>
        </div>
        <!-- End Page Header -->

        <!-- Card -->
        <div class="card card-body mb-3">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2 pb-1">
                <h5 class="card-title d-flex align-items-center gap-2">
                    <img src="{{asset('public/assets/admin/img/media/business-analytics.png')}}" alt="">
                    {{translate('E-Money Statistics')}}
                </h5>
                <div class="">
                    <select class="custom-select" name="statistics_type" onchange="order_stats_update(this.value)">
                        <option value="overall">
                            Overall Statistics
                        </option>
                        <option value="today">
                            Today s Statistics
                        </option>
                        <option value="this_month">
                            This Month s Statistics
                        </option>
                    </select>
                </div>
            </div>

            <div class="row g-2" id="order_stats">
                @include('merchant-views.partials._stats', ['data'=>$balance])
            </div>
        </div>
        <!-- End Card -->
        
        <div class="card">
            <div class="card-header d-flex justify-content-between flex-wrap gap-2 align-items-center">
                <h5 class="card-header-title mb-0">{{translate('Withdraw Table')}}</h5>

                <div class="">
                    <select name="withdrawal_method" class="form-control js-select2-custom" id="withdrawal_method" required>
                        <option value="all" selected>{{translate('Filter by method')}}</option>
                        @foreach($withdrawal_methods as $withdrawal_method)
                            <option value="{{$withdrawal_method->id}}" {{ $method == $withdrawal_method->id ? 'selected' : '' }}>{{translate($withdrawal_method->method_name)}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive datatable-custom">
                <table
                    class="table table-borderless table-nowrap table-align-middle card-table table-striped text-dark">
                    <thead class="thead-light">
                        <tr>
                            <th>{{translate('SL')}}</th>
                            <th>{{translate('Requested Amount')}}</th>
                            <th>{{translate('Withdrawal Method')}}</th>
                            <th>{{translate('Withdrawal Method Fields')}}</th>
                            <th>{{translate('Sender_Note')}}</th>
                            <th>{{translate('Admin_Note')}}</th>
                            <th>{{translate('Request_Status')}}</th>
                            <th>{{translate('Payment_Status')}}</th>
                            <th>{{translate('Requested time')}}</th>
                        </tr>
                    </thead>

                    <tbody>
                    @forelse($withdraw_requests as $key=>$withdraw_request)
                        <tr>
                            <td>{{$withdraw_requests->firstitem()+$key}}</td>
                            <td>{{ Helpers::set_symbol($withdraw_request->amount) }}</td>
                            <td><span>{{ translate($withdraw_request->withdrawal_method ? $withdraw_request->withdrawal_method->method_name : '') }}</span></td>
                            <td>
                                @foreach($withdraw_request->withdrawal_method_fields as $key=>$item)
                                    {{translate($key) . ': ' . $item}} <br/>
                            @endforeach
                            <td>
                                <div class="text-wrap mx-w300 mn-w160">{{ $withdraw_request->sender_note }}</div>
                            </td>
                            <td>
                                <div class="text-wrap mx-w300 mn-w160">{{ $withdraw_request->admin_note }}</div>
                            </td>
                            <td>
                                @if( $withdraw_request->request_status == 'pending' )
                                    <span class="badge badge-pill badge-primary"> {{translate('Pending')}}</span>
                                @elseif( $withdraw_request->request_status == 'approved' )
                                    <span class="badge badge-pill badge-success"> {{translate('Approved')}}</span>
                                @elseif( $withdraw_request->request_status == 'denied' )
                                    <span class="badge badge-pill badge-danger"> {{translate('Denied')}}</span>
                                @endif
                            </td>
                            <td>
                                @if($withdraw_request->is_paid )
                                    <span class="badge badge-pill badge-success">{{translate('Paid')}}</span>
                                @else
                                    <span class="badge badge-pill badge-danger">{{translate('Not_Paid')}}</span>
                                @endif
                            </td>
                            <td>{{ date_time_formatter($withdraw_request->created_at) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="10" class="text-center">{{translate('No_data_available')}}</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="table-responsive mt-4 px-3">
                <div class="d-flex justify-content-end">
                    {!! $withdraw_requests->links() !!}
                </div>
            </div>
        </div>

    </div>

        @endsection

        @push('script')
            <script src="{{asset('public/assets/admin')}}/vendor/chart.js/dist/Chart.min.js"></script>
            <script src="{{asset('public/assets/admin')}}/vendor/chart.js.extensions/chartjs-extensions.js"></script>
            <script src="{{asset('public/assets/admin')}}/vendor/chartjs-plugin-datalabels/dist/chartjs-plugin-datalabels.min.js"></script>
        @endpush


        @push('script_2')
            <script>
                $("#withdrawal_method").on('change', function (event) {
                    location.href = "{{route('merchant.dashboard')}}" + '?withdrawal_method=' + $(this).val();
                })
            </script>

        @endpush
