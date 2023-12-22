@extends('layouts.admin.app')

@section('title', translate('Agent List'))

@push('css_or_js')
    <script src="https://use.fontawesome.com/74721296a6.js"></script>
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="d-flex align-items-center gap-3 mb-3">
            <img width="24" src="{{ asset('public/assets/admin/img/media/agent.png') }}" alt="">
            <h2 class="page-header-title">{{ translate('Agent List') }}</h2>
        </div>
        <!-- End Page Header -->

        <!-- Card -->
        <div class="card">
            <!-- Header -->
            <div class="card-header __wrap-gap-10">
                <div class="d-flex align-items-center gap-2">
                    <h5 class="card-header-title">{{ translate('Agent Table') }}</h5>
                    <span class="badge badge-soft-secondary text-dark">{{ $agents->total() }}</span>
                </div>
                <div class="d-flex flex-wrap gap-3">
                    <form action="{{ url()->current() }}" method="GET">
                        <div class="input-group">
                            <input id="datatableSearch_" type="search" name="search" class="form-control mn-md-w280"
                                placeholder="{{ translate('Search by Name') }}" aria-label="Search"
                                value="{{ $search }}" required autocomplete="off">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">{{ translate('Search') }}</button>
                            </div>
                        </div>
                    </form>
                    <a href="{{ route('admin.agent.add') }}" class="btn btn-primary">
                        <i class="tio-add"></i> {{ translate('Add') }} {{ translate('Agent') }}
                    </a>
                    <a href="{{ route('admin.agent.fee') }}" class="btn btn-primary">
                        <i class="tio-add"></i> {{ translate('Add') }} {{ translate('Fee') }}
                    </a>
                </div>
            </div>
            <!-- End Header -->

            <!-- Table -->
            <div class="table-responsive datatable-custom">
                <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                    <thead class="thead-light">
                        <tr>
                            <th>{{ translate('SL') }}</th>
                            <th>{{ translate('name') }}</th>
                            <th>{{ translate('phone') }}</th>
                            <th>{{ translate('email') }}</th>
                            <th>{{ translate('status') }}</th>
                            <th>{{ translate('commission') }} %</th>
                            <th>{{ translate('fee') }} %</th>
                            {{-- <th>{{ translate('commission verify') }}</th> --}}
                            {{-- <th>{{ translate('fee verify') }}</th> --}}
                            <th class="text-center">{{ translate('action') }}</th>
                        </tr>
                    </thead>

                    <tbody id="set-rows">
                        @foreach ($agents as $key => $agent)
                            <tr>
                                <td>{{ $agents->firstitem() + $key }}</td>
                                <td>
                                    <a href="{{ route('admin.agent.view', [$agent['id']]) }}"
                                        class="media gap-3 align-items-center text-dark">
                                        <div class="avatar avatar-lg border rounded-circle">
                                            <img class="rounded-circle img-fit"
                                                onerror="this.src='{{ asset('public/assets/admin/img/160x160/img1.jpg') }}'"
                                                src="{{ asset('storage/app/public/agent') }}/{{ $agent['image'] }}">
                                        </div>
                                        <div class="media-body">
                                            {{ $agent['f_name'] . ' ' . $agent['l_name'] }}
                                        </div>
                                    </a>
                                </td>
                                <td>
                                    <a class="text-dark" href="tel:{{ $agent['phone'] }}">{{ $agent['phone'] }}</a>
                                </td>
                                <td>
                                    @if (isset($agent['email']))
                                        <a href="mailto:{{ $agent['email'] }}" class="text-dark">{{ $agent['email'] }}</a>
                                    @else
                                        <span class="badge-pill badge-soft-dark text-muted">Email unavailable</span>
                                    @endif
                                </td>
                                <td>
                                    <label class="switcher" for="welcome_status_{{ $agent['id'] }}">
                                        <input type="checkbox" name="welcome_status" class="switcher_input"
                                            id="welcome_status_{{ $agent['id'] }}"
                                            {{ $agent ? ($agent['is_active'] == 1 ? 'checked' : '') : '' }}
                                            onclick="location.href='{{ route('admin.agent.status', [$agent['id']]) }}'">
                                        <span class="switcher_control"></span>
                                    </label>
                                </td>

                                <td>
                                    {{ $agent['commission'] }}%
                                </td>
                                <td>
                                    {{ $agent['fee'] }}%
                                </td>
                                {{-- @dd($agent) --}}
                                {{-- <td>
                                    <label class="switcher" for="welcome_status_commission_{{ $agent['id'] }}">
                                        <input type="checkbox" name="welcome_status" class="switcher_input"
                                            id="welcome_status_commission_{{ $agent['id'] }}"
                                            {{ $agent ? ($agent->is_commission_verified == 1 ? 'checked' : '') : '' }}
                                            onclick="location.href='{{ route('admin.agent.commission.status', [$agent['id']]) }}'">
                                        <span class="switcher_control"></span>
                                    </label>
                                </td>
                                <td>
                                    <label class="switcher" for="welcome_status_fee_{{ $agent['id'] }}">
                                        <input type="checkbox" name="welcome_status" class="switcher_input"
                                            id="welcome_status_fee_{{ $agent['id'] }}"
                                            {{ $agent ? ($agent->is_fee_verified == 1 ? 'checked' : '') : '' }}
                                            onclick="location.href='{{ route('admin.agent.fee.status', [$agent['id']]) }}'">
                                        <span class="switcher_control"></span>
                                    </label>
                                </td> --}}
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        {{-- <a class="action-btn btn btn-outline-warning"
                                            href="{{ route('admin.agent.commission', [$agent['id']]) }}">
                                            <i class=" fa fa-solid fa-plus" aria-hidden="true"></i>
                                        </a> --}}
                                        <a class="action-btn btn btn-outline-primary"
                                            href="{{ route('admin.agent.view', [$agent['id']]) }}">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </a>
                                        <a class="action-btn btn btn-outline-info"
                                            href="{{ route('admin.agent.edit', [$agent['id']]) }}">
                                            <i class="fa fa-pencil" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="table-responsive mt-4 px-3">
                <div class="d-flex justify-content-end">
                    {!! $agents->links() !!}
                </div>
            </div>
        </div>
        <!-- End Card -->
    </div>
@endsection

@push('script_2')
@endpush
