@extends('layouts.admin.app')

@section('title', translate('Country'))

@push('css_or_js')
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="d-flex align-items-center gap-3 mb-3">
            <h2 class="page-header-title">{{ translate('Add City') }}</h2>
        </div>
        <!-- End Page Header -->

        <div class="card card-body">
            <form action="{{ route('admin.city.store') }}" method="POST">
                @csrf
                <input type="hidden" name="country_id" value="{{ $country }}">
                <div class="form-group">
                    <label for="">City</label>
                    <input type="text" name="name" class="form-control" placeholder="Enter City Name">
                </div>
                <div class="form-group">
                    <label for="">Status</label>
                    <select name="status" id="" class="form-control">
                        <option value="" disabled selected>Select Status</option>
                        <option value="active">Active</option>
                        <option value="block">Block</option>
                    </select>
                </div>
                <div class="form-group d-flex">
                    <button type="submit" class="btn btn-sm btn-primary">Add City</button>
                    <div class="mx-2">
                        <a href="{{ route('admin.location.country') }}" class="btn btn-sm btn-secondary">Back</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('script_2')
@endpush
