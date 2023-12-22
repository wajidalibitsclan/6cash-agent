@extends('layouts.agent.app')


@section('content')
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h1>{{ translate('withdraw') }}</h1>

                        <form action="{{ route('agent.withdraw.action') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="">{{ translate('Payment Method') }}</label>
                                <select onchange="getMethodFields(event)" class="form-control" name="method_id" required>
                                    <option value="" selected disabled>{{ translate('Select Payment Method') }}
                                    </option>
                                    @foreach ($withdrawalMethods as $method)
                                        <option value="{{ $method->id }}">{{ $method->method_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <input type="hidden" value="{{ Auth::user()->id }}" name="id">
                            <div id="payment-method">
                            </div>
                            <div class="form-group my-3">
                                <label for="">Available Balance:</label>
                                <h3>{{ $agent->emoney->current_balance }} <span class="eur">EUR</span></h3>
                            </div>
                            <div class="form-group">
                                <label for="">{{ translate('Amount') }} <span class="text-danger"
                                        id="amount-input-error">Amount Should be less than Your Balance</span></label>
                                <input type="text" name="amount" oninput="validateInputField(this,event)"
                                    placeholder="{{ translate('Amount') }}" id="amount-input" class="form-control"
                                    style="border: none;font-size: 34px;font-weight:bold">
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
                            <div class="form-group mb-2">
                                <label for="">{{ translate('note') }}</label>
                                <textarea name="note" class="form-control" placeholder="{{ translate('note') }}" cols="3" rows="3"></textarea>
                            </div>
                            <div class="form-group mb-2">
                                <label for="">{{ translate('PIN') }}</label>
                                <input type="password" oninput="validateInput(event)"
                                    placeholder="{{ translate('Enter your PIN') }}" name="pin" class="form-control"
                                    required>
                            </div>
                            <div class="form-group mt-2">
                                <button type="submit" class="btn btn-primary">{{ translate('withdraw') }}</button>
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
        const emoney = @json($agent->emoney);
        $("#amount-input-error").hide();

        function suggestionHandler(amount) {
            if (amount > emoney.current_balance) {
                $("#amount-input").val("")
                $("#amount-input-error").show();
                setTimeout(() => {
                    $("#amount-input-error").hide();
                }, 3000);
            } else {
                $("#amount-input-error").hide();
                $("#amount-input").val(amount)
            }
        }

        function validateInputField(input, event) {
            let amount = parseFloat(event.target.value);
            $("#amount-input-error").hide();

            var hasHyphen = /-/.test(input.value);
            input.value = input.value.replace(/[^0-9-]/g, '');
            if (hasHyphen) {
                input.value = input.value.replace(/-/g, '');
            }

            if (amount < 0) {
                $("#amount-input").val("")
            }
            if (event.target.value > emoney.current_balance) {
                $("#amount-input-error").show();
                setTimeout(() => {
                    $("#amount-input-error").hide();
                }, 3000);
                $("#amount-input").val("")
            }
        }

        function getMethodFields(event) {
            $.ajax({
                url: '{{ route('agent.withdraw.method') }}',
                type: 'POST',
                data: {
                    id: event.target.value,
                    _token: '{{ csrf_token() }}',
                },
                success: function(res) {
                    console.log(res)
                    $(".remove-input").remove();
                    for (let i = 0; i < res.data.method_fields.length; i++) {
                        $("#payment-method").append(
                            `<input type="${res.data.method_fields[i].input_type}" name="${res.data.method_fields[i].input_name}" placeholder="${res.data.method_fields[i].placeholder}" class="remove-input form-control mt-2"  required/>`
                        )
                    }
                }
            })
        }

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
