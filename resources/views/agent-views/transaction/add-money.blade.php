@extends('layouts.agent.app')


@section('content')
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h1>{{ translate('Add Money') }}</h1>

                        <form action="{{ route('agent.added.money') }}" method="POST">
                            @csrf
                            <input type="hidden" value="{{ Auth::user()->id }}" name="id">
                            {{-- <div class="form-group">
                                <label for="">{{ translate('payment Method') }}</label>
                                <select class="form-control" name="payment_method" id="">
                                    <option value="" disabled>{{ translate('Select Payment Method') }}</option>
                                    @foreach ($activePaymentMethods as $payment)
                                        <option value="{{ $payment['slug'] }}">{{ $payment['name'] }}</option>
                                    @endforeach
                                </select>
                            </div> --}}
                            <div class="form-group">
                                <label for="">{{ translate('Amount') }}</label>
                                <input type="text" oninput="validateInputField(this)" name="amount"
                                    placeholder="{{ translate('Amount') }}" id="amount-input" class="form-control"
                                    style="border: none;font-size: 34px;font-weight:bold">
                            </div>
                            <div class="form-group">
                                <h3>{{ translate('Available Balance') }} <span
                                        class="text-primary">{{ $userData['balance'] }} EUR</span>
                                </h3>
                            </div>
                            <div class="form-group mt-2">
                                <button type="button" onclick="suggestionHandler(500)" class="btn btn-light">500</button>
                                <button type="button" onclick="suggestionHandler(1000)"
                                    class="btn btn-light">1,000</button>
                                <button type="button" onclick="suggestionHandler(2000)"
                                    class="btn btn-light">2,000</button>
                                <button type="button" onclick="suggestionHandler(5000)"
                                    class="btn btn-light">5,000</button>
                            </div>
                            <div class="form-group mt-2">
                                <button type="submit" class="btn btn-primary">{{ translate('Add Money') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        function suggestionHandler(amount) {
            $("#amount-input").val(amount)
        }

        function validateInputField(input) {
            var hasHyphen = /-/.test(input.value);
            input.value = input.value.replace(/[^0-9-]/g, '');
            if (hasHyphen) {
                input.value = input.value.replace(/-/g, '');
            }
        }
    </script>
@endpush
