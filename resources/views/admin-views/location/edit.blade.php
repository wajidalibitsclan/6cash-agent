@extends('layouts.admin.app')

@section('title', translate('Edit City'))

@push('css_or_js')
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="d-flex align-items-center gap-3 mb-3">
            <h2 class="page-header-title">{{ translate('Edit City') }}</h2>
        </div>
        <!-- End Page Header -->

        <div class="card card-body">
            <form action="{{ route('admin.city.update') }}" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{ $city->id }}">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="">City</label>
                        <input type="text" value="{{ $city->name }}" name="name" class="form-control"
                            placeholder="Enter City Name">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Country</label>
                        <select name="country_id" id="" class="form-control">
                            <option value="" disabled selected>Select Country</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country->id }}"
                                    {{ $city->country->id === $country->id ? 'selected' : '' }}>{{ $country->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Status</label>
                        <select name="status" id="" class="form-control">
                            <option value="" disabled selected>Select Status</option>
                            <option value="active" {{ $city->status === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="block" {{ $city->status === 'block' ? 'selected' : '' }}>Block</option>
                        </select>
                    </div>
                </div>
                <div class="form-group d-flex">
                    <button type="submit" class="btn btn-sm btn-primary">Update City</button>
                    <div class="mx-2">
                        <a href="{{ route('admin.location.city') }}" class="btn btn-sm btn-secondary">Back</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('script_2')
@endpush
