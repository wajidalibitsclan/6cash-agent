@extends('layouts.admin.app')

@section('title', translate('Verification List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://use.fontawesome.com/74721296a6.js"></script>
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="d-flex align-items-center gap-3 mb-3">
            <img width="24" src="{{asset('public/assets/admin/img/media/rating.png')}}" alt="">
            <h2 class="page-header-title">{{translate('Add New Customer')}}</h2>
        </div>
        <!-- End Page Header -->

        <!-- Card -->
        <div class="card">
            <!-- Header -->
            <div class="card-header __wrap-gap-10">
                <div class="d-flex align-items-center gap-2">
                    <h5 class="card-header-title">{{translate('Verification requests list')}}</h5>
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
                <table
                    class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                    style="width: 100%">
                    <thead class="thead-light">
                    <tr>
                        <th>{{translate('SL')}}</th>
                        <th>{{translate('name')}}</th>
                        <th>{{translate('contact')}}</th>
                        <th>{{translate('Identification Type')}}</th>
                        <th>{{translate('Identification Number')}}</th>
                        <th>{{translate('Identification Image')}}</th>
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
                                    <div class="media-body">
                                        {{$customer['f_name'].' '.$customer['l_name']}}
                                    </div>
                                </a>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <a href="tel:{{$customer['phone']}}" class="text-dark">{{$customer['phone']}}</a>
                                    @if(isset($customer['email']))
                                        <a class="text-dark" href="mailto:{{ $customer['email'] }}" class="text-primary">{{ $customer['email'] }}</a>
                                    @else
                                        <span class="badge badge-soft-danger text-danger">{{ translate('Email unavailable') }}</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if(isset($customer['identification_type']))
                                    {{translate($customer['identification_type'])}}
                                @else
                                    <span class="badge badge-soft-danger text-danger">{{ translate('Type unavailable') }}</span>
                                @endif
                            </td>
                            <td>
                                @if(isset($customer['identification_number']))
                                    {{ $customer['identification_number']  }}
                                @else
                                    <span class="badge badge-soft-danger text-danger">{{ translate('Number unavailable') }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2" data-toggle="" data-placement="top" title="{{translate('click for bigger view')}}">
                                    @foreach(json_decode($customer['identification_image'], true) as $identification_image)
                                        @php($image_full_path = asset('storage/app/public/user/identity'). '/' .$identification_image)
                                        <div class="w-120 mx-h80 overflow-hidden">
                                            <img class="cursor-pointer rounded img-fit"
                                             onerror="this.src='{{asset('public/assets/admin/img/900x400/img1.jpg')}}'"
                                             src="{{$image_full_path}}"
                                             onclick="show_modal('{{$image_full_path}}')">
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a class="action-btn btn btn-outline-primary"
                                    href="{{route('admin.customer.view',[$customer['id']])}}">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </a>
                                    @if($customer['is_kyc_verified'] == 0)
                                        <a class="action-btn btn btn-outline-success"
                                        href="{{route('admin.customer.kyc_status_update',[$customer['id'], 1])}}">
                                            <i class="fa fa-check" aria-hidden="true"></i>
                                        </a>
                                        <a class="action-btn btn btn-outline-danger"
                                        href="{{route('admin.customer.kyc_status_update',[$customer['id'], 2])}}">
                                            <i class="fa fa-times" aria-hidden="true"></i>
                                        </a>
                                    @elseif($customer['is_kyc_verified'] == 2)
                                        <span class="badge badge-soft-danger"> {{translate('Denied')}}</span>
                                    @endif
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
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade bd-example-modal-lg" id="identification_image_view_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-body p-0">
                            <div data-dismiss="modal">
                                <img onerror='this.src="{{asset('public/assets/admin/img/900x400/img1.jpg')}}"' alt=""
                                     class="" id="identification_image_element" style="width: 100%">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal End -->
        </div>
        <!-- End Card -->
    </div>
@endsection

@push('script_2')
    <script>
        function show_modal(image_location) {
            $('#identification_image_view_modal').modal('show');
            if(image_location != null || image_location !== '') {
                $('#identification_image_element').attr("src", image_location);
            }
        }
    </script>
@endpush
