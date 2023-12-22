@extends('layouts.admin.app')

@section('title', translate('Withdraw_Requests'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="d-flex align-items-center gap-3 mb-3">
            <img width="24" src="{{asset('public/assets/admin/img/media/cash-withdrawal.png')}}" alt="">
            <h1 class="page-header-title">{{translate('Withdraw_Requests')}}</h1>
        </div>
        <!-- End Page Header -->

        <div class="d-flex flex-wrap justify-content-between align-items-center border-bottom gap-3 mb-3">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link {{$request_status=='all'?'active':''}}"
                        href="{{url()->current()}}?request_status=all">
                        {{translate('all')}}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{$request_status=='pending'?'active':''}}"
                        href="{{url()->current()}}?request_status=pending">
                        {{translate('pending')}}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{$request_status=='approved'?'active':''}}"
                        href="{{url()->current()}}?request_status=approved">
                        {{translate('approved')}}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{$request_status=='denied'?'active':''}}"
                        href="{{url()->current()}}?request_status=denied">
                        {{translate('denied')}}
                    </a>
                </li>
            </ul>
        </div>

        <div class="card">
            <div class="card-header flex-between __wrap-gap-10">
                <div class="d-flex align-items-center gap-2">
                    <h5 class="card-header-title">{{translate('transaction Table')}}</h5>
                    <span class="badge badge-soft-secondary text-dark">{{ $withdraw_requests->total() }}</span>
                </div>

                <div class="flex-between __wrap-gap-10 align-items-center">
                        {{-- <a class="btn-sm btn-secondary" href="{{route('admin.withdraw.download', ['withdrawal_method'=>$method,'search'=>$search, 'request_status'=>$request_status])}}">{{translate('Export')}}</a> --}}
                    <div class="">
                        <button type="button" class="btn btn-outline-primary" data-toggle="dropdown" aria-expanded="true">
                            <i class="tio-download-to"></i>
                            {{translate('Export')}}
                            <i class="tio-chevron-down"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2" href="{{route('admin.withdraw.download', ['withdrawal_method'=>$method,'search'=>$search, 'request_status'=>$request_status])}}">
                                    <img width="20" src="{{asset('public/assets/admin/img/media/excel.png')}}" alt="">
                                    <span>Excel</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="">
                        <select name="withdrawal_method" class="form-control js-select2-custom" id="withdrawal_method" required>
                            <option value="all" selected>{{translate('Filter by method')}}</option>
                            @foreach($withdrawal_methods as $withdrawal_method)
                                <option value="{{$withdrawal_method->id}}" {{ $method == $withdrawal_method->id ? 'selected' : '' }}>{{translate($withdrawal_method->method_name)}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <form action="{{url()->current()}}" method="GET">
                            <div class="input-group">
                                <input id="datatableSearch_" type="search" name="search"
                                        class="form-control mn-md-w280"
                                        placeholder="{{translate('Search by Name')}}" aria-label="Search"
                                        value="{{$search??''}}" required autocomplete="off">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary">{{translate('Search')}}
                                    </button>
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
                        <th>{{translate('SL')}}</th>
                        <th>{{translate('Sender')}}</th>
                        <th>{{translate('Sender Type')}}</th>
                        <th>{{translate('Requested Amount')}}</th>
                        <th>{{translate('Admin Charge')}}</th>
                        <th>{{translate('Withdrawal Method')}}</th>
                        <th>{{translate('Withdrawal Method Fields')}}</th>
                        <th>{{translate('Sender_Note')}}</th>
                        {{--<th>{{translate('Admin_Note')}}</th>--}}
                        <th>{{translate('Request_Status')}}</th>
                        <th>{{translate('Payment_Status')}}</th>
                        <th>{{translate('Requested time')}}</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($withdraw_requests as $key=>$withdraw_request)
                        <tr>
                            <td>{{$withdraw_requests->firstitem()+$key}}</td>
                            <td>
                                @if($withdraw_request->user)
                                    <span class="d-block font-size-sm text-body">
                                        <a href="{{route('admin.customer.view',[$withdraw_request->user->id])}}">
                                            {{ $withdraw_request->user->f_name . ' ' . $withdraw_request->user->l_name }}
                                        </a>
                                    </span>
                                @else
                                    <span class="badge badge-pill">{{translate('User_not_available')}}</span>
                                @endif
                            </td>
                            <td>
                                @if($withdraw_request->user)
                                    <small class="badge badge-pill">
                                        {{ $withdraw_request->user->type == 1 ? translate('Agent') : ($withdraw_request->user->type == 3 ? translate('Merchant') : translate('Customer')) }}
                                    </small>
                                @else
                                    <span class="badge badge-pill">{{translate('Not_available')}}</span>
                                @endif
                            </td>
                            <td>{{ Helpers::set_symbol($withdraw_request->amount) }}</td>
                            <td>{{ Helpers::set_symbol($withdraw_request->admin_charge) }}</td>
                            <td><span class="badge badge-pill">{{ translate($withdraw_request->withdrawal_method ? $withdraw_request->withdrawal_method->method_name : '') }}</span></td>
                            <td>
                                @foreach($withdraw_request->withdrawal_method_fields as $key=>$item)
                                    {{translate($key) . ': ' . $item}} <br/>
                                @endforeach
                            </td>
                            <td>
                                <div class="mx-w300 mn-w160 text-wrap">
                                    {{ $withdraw_request->sender_note }}
                                </div>
                            </td>
                            {{--<td>
                              <div class="mx-w300 mn-w160 text-wrap">
                                {{ $withdraw_request->admin_note }}
                              </div>
                            </td>--}}
                            <td>
                                @if( $withdraw_request->request_status == 'pending' )
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.withdraw.status_update', ['request_id'=>$withdraw_request->id, 'request_status'=>'approve']) }}" class="btn btn-primary btn-sm"> {{translate('Approve')}}</a>
                                        <a href="{{ route('admin.withdraw.status_update', ['request_id'=>$withdraw_request->id, 'request_status'=>'deny']) }}" class="btn btn-warning btn-sm"> {{translate('Deny')}}</a>
                                    </div>
                                @elseif( $withdraw_request->request_status == 'approved' )
                                    <span class="badge badge-pill badge-soft-success"> {{translate('Approved')}}</span>
                                @elseif( $withdraw_request->request_status == 'denied' )
                                    <span class="badge badge-pill badge-soft-danger"> {{translate('Denied')}}</span>
                                @endif
                            </td>
                            <td>
                                @if($withdraw_request->is_paid )
                                    <span class="badge badge-pill badge-soft-success">{{translate('Paid')}}</span>
                                @else
                                    <span class="badge badge-pill badge-soft-danger">{{translate('Not_Paid')}}</span>
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

@push('script_2')
    <script>
        $("#withdrawal_method").on('change', function (event) {
            location.href = "{{route('admin.withdraw.requests')}}" + '?request_status=all' + '&withdrawal_method=' + $(this).val();
        })
    </script>
@endpush
