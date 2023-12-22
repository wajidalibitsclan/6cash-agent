@extends('layouts.admin.app')

@section('title', translate('Customer List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://use.fontawesome.com/74721296a6.js"></script>
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="d-flex align-items-center gap-3 mb-3">
            <img width="24" src="{{asset('public/assets/admin/img/media/rating.png')}}" alt="">
            <h2 class="page-header-title">{{translate('Customer List')}}</h2>
        </div>
        <!-- End Page Header -->

        <!-- Card -->
        <div class="card">
            <!-- Header -->
            <div class="card-header __wrap-gap-10">
                <div class="d-flex align-items-center gap-2">
                    <h5 class="card-header-title">{{translate('Customer Table')}}</h5>
                    <span class="badge badge-soft-secondary text-dark">{{ $customers->total() }}</span>
                </div>
                <div class="d-flex flex-wrap gap-3">
                    <form action="{{url()->current()}}" method="GET">
                        <div class="input-group">
                            <input id="datatableSearch_" type="search" name="search"
                                   class="form-control mn-md-w280"
                                   placeholder="{{translate('Search by Name')}}" aria-label="Search"
                                   value="{{$search}}" required autocomplete="off">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">{{translate('Search')}}</button>
                            </div>
                        </div>
                    </form>
                    <a href="{{route('admin.customer.add')}}" class="btn btn-primary">
                        <i class="tio-add"></i> {{translate('Add')}} {{translate('Customer')}}
                    </a>
                </div>
            </div>
            <!-- End Header -->

            <!-- Table -->
            <div class="table-responsive datatable-custom">
                <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                    <thead class="thead-light">
                        <tr>
                            <th>{{translate('SL')}}</th>
                            <th>{{translate('name')}}</th>
                            <th>{{translate('Contacts')}}</th>
                            <th>{{translate('status')}}</th>
                            <th class="text-center">{{translate('action')}}</th>
                        </tr>
                    </thead>

                    <tbody id="set-rows">
                    @foreach($customers as $key=>$customer)
                        <tr>
                            <td>{{$customers->firstitem()+$key}}</td>
                            <td>
                                <a class="media gap-3 align-items-center text-dark" href="{{route('admin.customer.view',[$customer['id']])}}">
                                    <div class="avatar avatar-lg border rounded-circle">
                                        <img class="rounded-circle img-fit"
                                        onerror="this.src='{{asset('public/assets/admin/img/160x160/img1.jpg')}}'"
                                        src="{{asset('storage/app/public/customer')}}/{{$customer['image']}}">
                                    </div>
                                    <div class="card-body">
                                        {{$customer['f_name'].' '.$customer['l_name']}}
                                    </div>
                                </a>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <a class="text-dark" href="tel:{{$customer['phone']}}">{{$customer['phone']}}</a>
                                    @if(isset($customer['email']))
                                        <a class="text-dark" href="mailto:{{ $customer['email'] }}" class="text-primary">{{ $customer['email'] }}</a>
                                    @else
                                        <span class="text-muted text-left">{{ translate('Email Unavailable') }}</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <label class="switcher" for="welcome_status_{{$customer['id']}}">
                                    <input type="checkbox" name="welcome_status"
                                           class="switcher_input"
                                           id="welcome_status_{{$customer['id']}}" {{$customer?($customer['is_active']==1?'checked':''):''}}
                                           onclick="location.href='{{route('admin.customer.status',[$customer['id']])}}'">

                                    <span class="switcher_control"></span>
                                </label>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a class="action-btn btn btn-outline-primary"
                                    href="{{route('admin.customer.view',[$customer['id']])}}">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </a>
                                    <a class="action-btn btn btn-outline-info"
                                    href="{{route('admin.customer.edit',[$customer['id']])}}">
                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- End Table -->

            <!-- Pagination -->
            <div class="table-responsive mt-4 px-3">
                <div class="d-flex justify-content-end">
                    {!! $customers->links() !!}
                    <nav id="datatablePagination" aria-label="Activity pagination"></nav>
                </div>
            </div>
        </div>
        <!-- End Card -->
    </div>
@endsection

@push('script_2')

@endpush
