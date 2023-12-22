@extends('layouts.admin.app')

@section('title', translate('Log'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="d-flex align-items-center gap-3 mb-3">
            <img width="24" src="{{asset('public/assets/admin/img/media/agent.png')}}" alt="">
            <h2 class="page-header-title">{{translate('Details')}}</h2>
        </div>
        <!-- End Page Header -->

        <!-- Page Header -->
        <div class="page-header mb-4">
            <!-- Nav Scroller -->
            <div class="js-nav-scroller hs-nav-scroller-horizontal">
                <!-- Nav -->
                @include('admin-views.view.partails.navbar')
                <!-- End Nav -->
            </div>
            <!-- End Nav Scroller -->
        </div>
        <!-- End Page Header -->

        <div class="card">
            <div class="card-header flex-between __wrap-gap-10">
                <div class="d-flex align-items-center gap-2">
                    <h5 class="card-header-title">{{translate('Logs')}}</h5>
                    <span class="badge badge-soft-secondary text-dark">{{ $user_logs->total() }}</span>
                </div>
                <div>
                    <form action="{{url()->current()}}" method="GET">
                        <div class="input-group">
                            <input id="datatableSearch_" type="search" name="search"
                                    class="form-control mn-md-w280"
                                    placeholder="{{translate('Search by ip, deviceId, browser, os or device model')}}" aria-label="Search"
                                    value="{{$search}}" required autocomplete="off">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">{{translate('Search')}}</i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Table -->
            <div class="table-responsive datatable-custom">
                <table
                    class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                    <thead class="thead-light">
                        <tr>
                            <th >{{translate('name')}}</th>
                            <th>{{translate('phone')}}</th>
                            <th>{{translate('ip_address')}}</th>
                            <th>{{translate('device_id')}}</th>
                            {{--<th>{{translate('browser')}}</th>--}}
                            <th>{{translate('os')}}</th>
                            <th>{{translate('device_model')}}</th>
                            <th class="text-center">{{translate('login_time')}}</th>
                        </tr>
                    </thead>

                    <tbody id="set-rows">
                    @foreach($user_logs as $key=>$user_log)
                        @if($user_log->user)
                            <tr>
                                <td>
                                    <a class="d-block font-size-sm text-body"
                                        href="{{route('admin.customer.view',[$user_log->user['id']])}}">
                                        {{$user_log->user['f_name'].' '.$user_log->user['l_name']}}
                                    </a>
                                </td>
                                <td>
                                    {{$user_log->user['phone']}}
                                </td>
                                <td>{{ $user_log->ip_address }}</td>
                                <td>{{ $user_log->device_id }}</td>
                                {{--<td>{{ $user_log->browser }}</td>--}}
                                <td>{{ $user_log->os }}</td>
                                <td>{{ $user_log->device_model }}</td>
                                <td class="text-center">{{ date('d-M-Y H:iA', strtotime($user_log->created_at)) }}</td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="table-responsive mt-4 px-3">
                <div class="d-flex justify-content-end">
                    {!! $user_logs->links() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_2')

@endpush
