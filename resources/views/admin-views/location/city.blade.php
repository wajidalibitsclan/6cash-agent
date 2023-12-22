@extends('layouts.admin.app')

@section('title', translate('City'))

@push('css_or_js')
    <style>
        a.paginate_button:hover {
            background: rgba(55, 125, 255, .1) !important;
            border-color: rgba(55, 125, 255, .1) !important;
        }

        div#datatable-custom_paginate .paginate_button:hover {
            color: #1e2022 !important;
        }

        a.paginate_button {
            border-radius: 5px !important;
        }

        div#datatable-custom_paginate a.paginate_button.current {
            background: #014f5b !important;
            color: #ffffff !important;
        }

        .header-table {
            background: #e5edee !important;
            color: #1e2022 !important;
            border-color: #e5edee !important;
        }
    </style>
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="d-flex align-items-center gap-3 mb-3">
            <h2 class="page-header-title">{{ translate('City') }}</h2>
        </div>
        <!-- End Page Header -->

        <div class="card card-body">
            <table id="datatable-custom">
                <thead class="header-table">
                    <th>Sr#</th>
                    <th>{{ translate('Name') }}</th>
                    <th>{{ translate('Country') }}</th>
                    <th>{{ translate('Status') }}</th>
                    <th>{{ translate('Actions') }}</th>
                </thead>
                <tbody>
                    @foreach ($cities as $key => $city)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $city->name }}</td>
                            <td>
                                {{ $city->country->name }}
                            </td>
                            <td>
                                <label class="switcher" for="welcome_status_{{ $city->id }}">
                                    <input type="checkbox" name="welcome_status" class="switcher_input"
                                        id="welcome_status_{{ $city->id }}"
                                        {{ $city ? ($city->status == 'active' ? 'checked' : '') : '' }}
                                        onclick="location.href='{{ route('admin.city.status', [$city->id]) }}'">
                                    <span class="switcher_control"></span>
                                </label>
                            </td>
                            <td class="d-flex">
                                <a class="action-btn btn btn-outline-info mx-2"
                                    href="{{ route('admin.edit.city', [$city->id]) }}">
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>

@endsection

@push('script_2')
    <script>
        $(document).ready(function() {
            $('#datatable-custom').DataTable({
                responsive: true,
                // scrollX: true
            }); // Correct
        });
    </script>
@endpush
