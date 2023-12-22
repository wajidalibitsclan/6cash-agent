@extends('layouts.admin.app')

@section('title', translate('Add Agent Fee'))

@push('css_or_js')
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="card">
            <div class="card-header">
                <h2 class="page-header-title">{{ translate('Add Agent Fee') }}</h2>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.agent.fee.update') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="">% Fee</label>
                        <input type="number" name="fee" value="{{ $fee->fee }}" class="form-control"
                            placeholder="Enter Fee" oninput="validateInput(this)">
                    </div>
                    <div class="form-group d-flex">
                        <button type="submit" class="btn btn-sm btn-primary">Add Fee</button>
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
