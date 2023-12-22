@extends('layouts.admin.app')

@section('title', translate('Add Commission'))

@push('css_or_js')
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        {{-- <div class="d-flex align-items-center gap-3 mb-3">
            <h2 class="page-header-title">{{ translate('Add Commission') }}</h2>
        </div> --}}
        <!-- End Page Header -->

        <div class="card">
            <div class="card-header">
                <h2 class="page-header-title">{{ translate('Add Commission') }}</h2>

            </div>
            <div class="card-body">
                <form action="{{ route('admin.agent.commission.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $commission->id }}">
                    <div class="form-group">
                        <label for="">Name</label>
                        <p>{{ $commission->f_name }}{{ ' ' }}{{ $commission->l_name }}</p>
                    </div>
                    <div class="form-group">
                        <label for="">Phone</label>
                        <p>{{ $commission->phone }}</p>
                    </div>
                    <div class="form-group">
                        <label for="">% Commission Fee</label>
                        <input type="number" name="fee" class="form-control" value="{{ $commission->commission }}"
                            placeholder="Enter Commission Fee" oninput="validateInput(this)">
                    </div>
                    <div class="form-group d-flex">
                        <button type="submit" class="btn btn-sm btn-primary">Add Commission</button>
                        <a href="{{ route('admin.agent.list') }}" class="btn btn-sm btn-secondary mx-2">Back</a>
                    </div>
                </form>
            </div>

        </div>
    </div>

@endsection

@push('script_2')
    <script>
        function validateInput(inputField) {
            var inputValue = inputField.value;
            if (!Number.isInteger(Number(inputValue)) || inputValue < 0 || inputValue > 100) {
                // document.getElementById('errorMessage').textContent = 'Please enter a valid integer between 0 and 100.';
                inputField.value = ''; // Clear the input field
            } else {
                // document.getElementById('errorMessage').textContent = '';
            }
        }
    </script>
@endpush
