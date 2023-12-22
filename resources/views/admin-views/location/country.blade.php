@extends('layouts.admin.app')

@section('title', translate('Country'))

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
            <h2 class="page-header-title">{{ translate('Countries') }}</h2>
        </div>
        <!-- End Page Header -->

        <div class="card">
            <div class="card-body">
                <table id="datatable-custom">
                    <thead class="header-table">
                        <th>Sr#</th>
                        <th>{{ translate('Name') }}</th>
                        <th>{{ translate('Status') }}</th>
                        <th>{{ translate('Actions') }}</th>
                    </thead>
                    <tbody>
                        @foreach ($countries as $key => $country)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $country->name }}</td>
                                <td>
                                    <label class="switcher" for="welcome_status_{{ $country->id }}">
                                        <input type="checkbox" name="welcome_status" class="switcher_input"
                                            id="welcome_status_{{ $country->id }}"
                                            {{ $country ? ($country->status == 'active' ? 'checked' : '') : '' }}
                                            onclick="location.href='{{ route('admin.country.status', [$country->id]) }}'">
                                        <span class="switcher_control"></span>
                                    </label>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <div>
                                            <a href="{{ route('admin.add.city', ['country' => $country->id]) }}"
                                                class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-plus"></i> Add
                                                City</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

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
