@extends('layouts.agent.app')


@section('content')
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h1>{{ translate('Change PIN') }}</h1>
                        <div>
                            <form action="{{ route('agent.update.pin') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ Auth::user()->id }}">
                                <div class="form-group">
                                    <label for="old_pin">{{ translate('Old PIN') }}</label>
                                    <input type="password" oninput="validateInput(event)" class="form-control"
                                        placeholder="{{ translate('Old PIN') }}" name="old_pin" required>
                                </div>
                                <div class="form-group">
                                    <label for="new_pin">{{ translate('New PIN') }}</label>
                                    <input type="password" oninput="validateInput(event)" class="form-control"
                                        placeholder="{{ translate('New PIN') }}" name="new_pin" required>
                                </div>
                                <div class="form-group">
                                    <label for="confirm_pin">{{ translate('confirm PIN') }}</label>
                                    <input type="password" oninput="validateInput(event)" class="form-control"
                                        placeholder="{{ translate('confirm PIN') }}" name="confirm_pin" required>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">{{ translate('update') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        function validateInput(event) {
            const input = event.target;
            const inputValue = input.value.replace(/\D/g, ''); // Remove non-digits

            if (inputValue.length > 4) {
                input.value = inputValue.slice(0, 4); // Keep only the first 4 digits
            } else {
                input.value = inputValue;
            }
        }
    </script>
@endpush
