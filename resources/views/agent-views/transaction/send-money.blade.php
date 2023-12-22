@extends('layouts.agent.app')


@section('content')
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h1>{{ translate('send_money') }}</h1>

                        <div class="container p-0">
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <label for="">Available Balance:</label>
                                        <h3 style="font-size: 52px">{{ $agent->emoney->current_balance }} <span
                                                class="eur">EUR</span></h3>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group mb-1" style="margin-top: -40px">
                                        {{-- <label for="scane">{{ translate('Scan') }}</label> --}}
                                        <div class="svg-scaner">
                                            {!! $userData['qr_code'] !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <form action="" method="POST">
                            @csrf
                            {{-- Currency  --}}
                            <input type="hidden" name="exchange_rate" id="exchange_rate">
                            <input type="hidden" name="currency_code" id="currency_code">


                            <div class="container p-0">
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="d-flex align-items-center justify-cotent-between">
                                            <div class="form-group d-flex" style="margin-bottom: 0 !important; flex: 1;">

                                                <div class="mr-3"> <label for="">Country</label>
                                                    <select name="country" class="form-control" id=""
                                                        onchange="getCities(event)">
                                                        <option value="" disabled selected>Select Country</option>
                                                        @foreach ($countries as $country)
                                                            <option value="{{ $country->id }}">{{ $country->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div style="width: 80%;">
                                                    <label for="">City</label>
                                                    <select name="city" class="form-control" id="city"
                                                        onchange="getAgent(event)">
                                                        <option value="" class="remove-city" disabled selected>Select
                                                            City
                                                        </option>
                                                    </select>
                                                </div>
                                                <div></div>
                                            </div>
                                            <div class="form-group"
                                                style="padding-left: 30px; padding-top: 25px; margin-bottom: 0 !important;">
                                                <div
                                                    class="js-form-message form-group merchant-login-form-group align-items-center flex-column">
                                                    <div class="w-100">
                                                        <label for=""
                                                            class="mr-3">{{ translate('Search Agent Number') }}</label>
                                                    </div>

                                                    <div class="d-flex">
                                                        <select id="country_code_filter" name="country_code"
                                                            style="max-width: 250px !important"
                                                            class="form-control __form-control __form-control-select bg-light"
                                                            required>
                                                            <option value="">{{ translate('country code') }}</option>
                                                            @foreach (PHONE_CODE as $country_code)
                                                                <option value="{{ $country_code['code'] }}"
                                                                    {{ strpos($country_code['name'], $current_user_info && $current_user_info->countryName) !== false ? 'selected' : '' }}>
                                                                    {{ $country_code['name'] }}</option>
                                                            @endforeach
                                                        </select>
                                                        <input type="text"
                                                            class="form-control __form-control __form-control-input"
                                                            name="phone" id="phone" required tabindex="1"
                                                            onkeyup="getAgentByNumber(event)"
                                                            placeholder="{{ translate('Enter your phone no.') }}"
                                                            data-msg="{{ translate('Please enter a valid phone number.') }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="">Agents</label>
                                <table class="table table-striped">
                                    <thead>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Phone Number</th>
                                        <th>Email</th>
                                        <th>Actions</th>
                                    </thead>
                                    <tbody id="agent-table">
                                    </tbody>
                                </table>
                            </div>
                        </form>

                        <form action="{{ route('agent.sent.money') }}" method="POST">
                            @csrf
                            {{-- Inputs  --}}
                            <input type="hidden" name="plateform_fee" id="set_plateform_fee">
                            <input type="hidden" name="reciever_amount" id="set_reciever_amount">
                            <input type="hidden" name="reciever_amount_exchange" id="set_reciever_amount_exchange">
                            <input type="hidden" name="sender_fee" id="set_sender_fee">
                            <input type="hidden" name="country_id" id="set_country_id">
                            <input type="hidden" name="city_id" id="set_city_id">
                            <input type="hidden" name="secret_pin" id="set_secret_pin">
                            <input type="hidden" name="agent_id" id="set_agent_id">
                            <input type="hidden" name="from_currency_code" id="set_from_currency_code">
                            <input type="hidden" name="to_currency_code" id="set_to_currency_code">

                            {{-- /Inputs  --}}
                            <input type="hidden" value="{{ Auth::user()->id }}" name="id">

                            <div class="container p-0">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h4>Sender Customer</h4>
                                        <div>
                                            <label for="">Name</label>
                                            <input type="text" class="form-control" name="sender_customer_name"
                                                placeholder="Customer Name">
                                        </div>
                                        <div class="mt-3">
                                            <div class="js-form-message form-group merchant-login-form-group flex-column">
                                                <label for="" class="mr-3">{{ translate('Phone') }}</label>
                                                <div class="d-flex">
                                                    <select id="country_code" name="sender_customer_country_code"
                                                        style="max-width: 250px !important"
                                                        class="form-control __form-control __form-control-select bg-light"
                                                        required>
                                                        <option value="">{{ translate('country code') }}</option>
                                                        @foreach (PHONE_CODE as $country_code)
                                                            <option value="{{ $country_code['code'] }}"
                                                                {{ strpos($country_code['name'], $current_user_info && $current_user_info->countryName) !== false ? 'selected' : '' }}>
                                                                {{ $country_code['name'] }}</option>
                                                        @endforeach
                                                    </select>
                                                    <input type="text" style="width: 70%"
                                                        class="form-control __form-control __form-control-input"
                                                        name="sender_customer_phone" id="phone" tabindex="1"
                                                        placeholder="{{ translate('Enter your phone no.') }}"
                                                        data-msg="{{ translate('Please enter a valid phone number.') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h4>Receiver Customer</h4>
                                        <div>
                                            <label for="">Name</label>
                                            <input type="text" class="form-control" name="receiver_customer_name"
                                                placeholder="Customer Name">
                                        </div>
                                        <div class="mt-3">
                                            <div class="js-form-message form-group merchant-login-form-group flex-column">
                                                <label for="" class="mr-3">{{ translate('Phone') }}</label>
                                                <div class="d-flex">
                                                    <select id="country_code" name="receiver_customer_country_code"
                                                        style="max-width: 250px !important"
                                                        class="form-control __form-control __form-control-select bg-light"
                                                        required>
                                                        <option value="">{{ translate('country code') }}</option>
                                                        @foreach (PHONE_CODE as $country_code)
                                                            <option value="{{ $country_code['code'] }}"
                                                                {{ strpos($country_code['name'], $current_user_info && $current_user_info->countryName) !== false ? 'selected' : '' }}>
                                                                {{ $country_code['name'] }}</option>
                                                        @endforeach
                                                    </select>
                                                    <input type="text" style="width: 70%"
                                                        class="form-control __form-control __form-control-input"
                                                        name="receiver_customer_phone" id="phone" tabindex="1"
                                                        placeholder="{{ translate('Enter your phone no.') }}"
                                                        data-msg="{{ translate('Please enter a valid phone number.') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="container p-0">
                                <div class="row">
                                    <div class="col-md-9">
                                        
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Available Balance:</label>
                                            <h3 style="font-size: 32px">{{ $agent->emoney->current_balance }} <span
                                                    class="eur">EUR</span></h3>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}



                            <div>
                                <input type="hidden" name="agent" value="" id="selectedAgent">
                            </div>


                            <div class="container p-0">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="">{{ translate('Amount') }} <strong>(Euro)</strong> <span
                                                    class="text-danger" id="amount-input-error"
                                                    style="display: none">Amount Should be less than
                                                    Your
                                                    Balance</span></label>
                                            <input type="text" name="amount_first"
                                                placeholder="{{ translate('Amount') }}" id="amount-input"
                                                oninput="inputAmount(event,this)" class="form-control"
                                                style="border: 1px solid #e7eaf3;
                                                height: 87px;font-size: 32px; padding-top: 0;font-weight:bold">
                                            <input type="hidden" name="amount" id="amount-set">
                                        </div>
                                    </div>

                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <label for="purpose">{{ translate('Purpose') }}
                                                ({{ translate('Optional') }})</label>
                                            <textarea placeholder="{{ translate('add purpose') }}" class="form-control" name="purpose" id=""
                                                cols="3" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group mt-2">
                                <button type="button" onclick="suggestionHandler(500)"
                                    class="btn btn-light">500</button>
                                <button type="button" onclick="suggestionHandler(1000)"
                                    class="btn btn-light">1,000</button>
                                <button type="button" onclick="suggestionHandler(2000)"
                                    class="btn btn-light">2,000</button>
                                <button type="button" onclick="suggestionHandler(5000)"
                                    class="btn btn-light">5,000</button>
                            </div>
                            <div class="form-group">
                                <label for="">Exchange Rate & Charges</label>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <p>Commission</p>
                                                <div class="d-flex align-items-center">
                                                    <h2 id="charge">0</h2>
                                                    <span class="currency_sign">EUR</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <p>Plateform Fee</p>
                                                <div class="d-flex align-items-center">
                                                    <h2 id="fee">0</h2>
                                                    <span class="currency_sign">EUR</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <p>Collected Amount</p>
                                                <div class="d-flex align-items-center">
                                                    <h2 id="recieved">0</h2>
                                                    <span class="currency_sign">EUR</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <p>Receivable Amount</p>
                                                <div class="d-flex">
                                                    <div class="d-flex align-items-center">
                                                        <h2 id="recieved_currency">0</h2>
                                                        <span id="set_currency_sign" class="currency_sign">EUR</span>
                                                    </div>
                                                    <div class="mx-2">
                                                        <select name="" id="" class="form-control"
                                                            onchange="changeCurrency(event)">
                                                            <option value="">Currencies</option>
                                                            @foreach ($currencies as $currency)
                                                                <option value="{{ $currency }}">
                                                                    {{ $currency->currency_code }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="container p-0">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="" style="margin-bottom: 19px;">{{ translate('Secret PIN') }}
                                            <button type="button" class="btn btn-sm btn-warning"
                                                onclick="generateKey()">Generate
                                                Key</button></label>
                                        <input type="text" aria-label="4 characters required" class="form-control"
                                            id="secret_pin" placeholder="{{ translate('Generate Secret PIN Number') }}"
                                            name="secret_pin" required disabled>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="" style="margin-bottom: 30px;">{{ translate('PIN') }}</label>
                                        <input type="password" oninput="validateInput(event)"
                                            aria-label="4 characters required" class="form-control"
                                            placeholder="{{ translate('Enter PIN Number') }}" name="pin">
                                    </div>
                                </div>
                            </div>


                            <div class="form-group mt-2 mb-3">
                                <button type="submit" class="btn btn-primary">{{ translate('send_money') }}</button>
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
        //Plateform Fee
        const fee = @json($agent);
        const emoney = @json($agent->emoney);

        $("#amount-input-error").hide();

        // GET Cities 
        function getCities(event) {
            let country = event.target.value;
            $.ajax({
                url: "{{ route('agent.get.cities') }}",
                type: 'POST',
                data: {
                    country: country,
                    _token: "{{ csrf_token() }}"
                },
                success: function(res) {
                    // console.log(res);
                    $(".remove-city").remove();
                    $(".remove-agent").remove();
                    $("#city").append(
                        `<option value="" class="remove-city" selected disabled>Select City</option>`);
                    res.data.cities.forEach(element => {
                        $("#city").append(
                            `<option class="remove-city" value="${element.id}">${element.name}</option>`
                        );
                    });

                    $("#currency_code").val(res.data.currency.currency_code);
                    $("#exchange_rate").val(res.data.currency.exchange_rate);
                    $("#set_to_currency_code").val(res.data.currency.currency_code);
                    $("#set_from_currency_code").val('EUR');
                    $("#set_country_id").val(country);
                }
            })
        }
        //GET Agents
        function getAgent(event) {
            let city = event.target.value;
            $.ajax({
                url: "{{ route('agent.get.agents') }}",
                type: 'POST',
                data: {
                    city: city,
                    _token: "{{ csrf_token() }}"
                },
                success: function(res) {
                    $(".remove-items").remove();
                    res.data.users.forEach(element => {
                        $("#agent-table").append(
                            `<tr class="remove-items">
                                <td>${element.f_name}</td>
                                <td>${element.l_name}</td>    
                                <td>${element.phone}</td>    
                                <td>${element.email}</td>    
                                <td>
                                    <input type="checkbox" class="agent-checkbox" onchange="selectAgent(this,${element.id},${element.city_id},${element.country_id}, this.checked)">
                                </td>    
                            </tr>`
                        );
                    });

                    $("#set_city_id").val(city);
                }
            })
        }

        function getAgentByNumber(event) {
            let phone = event.target.value;
            let country_code = $("#country_code_filter").val();
            if (phone.length !== 0) {
                $.ajax({
                    url: "{{ route('agent.get.agents.number') }}",
                    type: 'POST',
                    data: {
                        phone: phone,
                        country_code: country_code,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(res) {
                        $(".remove-items").remove();
                        res.data.forEach(element => {
                            $("#agent-table").append(
                                `<tr class="remove-items">
                                <td>${element.f_name}</td>
                                <td>${element.l_name}</td>    
                                <td>${element.phone}</td>    
                                <td>${element.email}</td>    
                                <td>
                                    <input type="checkbox" class="agent-checkbox" onchange="selectAgent(this,${element.id},${element.city_id},${element.country_id}, this.checked, true)">
                                </td>    
                            </tr>`
                            );
                        });
                    }
                })
            } else {
                $(".remove-items").remove();
            }

        }

        function getAgentCityWithCurrency(country) {
            $.ajax({
                url: "{{ route('agent.get.cities') }}",
                type: 'POST',
                data: {
                    country: country,
                    _token: "{{ csrf_token() }}"
                },
                success: function(res) {
                    // console.log(res);
                    $("#currency_code").val(res.data.currency.currency_code);
                    $("#exchange_rate").val(res.data.currency.exchange_rate);
                    $("#set_to_currency_code").val(res.data.currency.currency_code);
                    $("#set_from_currency_code").val('EUR');
                    $("#set_country_id").val(country);
                }
            })
        }

        function inputAmount(event, input = null) {
            let amount;


            if (input !== null) {
                var hasHyphen = /-/.test(input.value);
                input.value = input.value.replace(/[^0-9-]/g, '');
                if (hasHyphen) {
                    input.value = input.value.replace(/-/g, '');
                }
            }

            if (Number.isInteger(event)) {
                amount = event;
            } else {
                amount = event.target.value;
            }

            if (amount === '') {
                $("#amount-input").val("")
                $("#charge").text('0.00')
                $("#fee").text('0.00')
                $("#recieved").text('0.00')
                $("#recieved_currency").text('0.00')
                return;
            }


            if (amount > emoney.current_balance) {
                $("#amount-input-error").show();
                setTimeout(() => {
                    $("#amount-input-error").hide();
                }, 3000);
                $("#amount-input").val("")
                $("#charge").text('0.00')
                $("#fee").text('0.00')
                $("#recieved").text('0.00')
                $("#recieved_currency").text('0.00')
                return;
            }

            if (parseFloat(amount) <= 0) {
                $("#amount-input").val(0);
            }

            let feeCharges = (amount / 100) * fee.fee;
            let recieverAmount = +amount + feeCharges;

            console.log(amount);
            $("#amount-set").val(recieverAmount);
            console.log(recieverAmount, 'dafasfsa');

            let agentCharge = ((feeCharges / 100) * fee.commission);
            let currency_code = $("#currency_code").val();
            let exchange_rate = $("#exchange_rate").val();
            let recieved_currency = parseFloat(amount) * exchange_rate;

            $("#recieved").text(recieverAmount.toFixed(2));
            $("#fee").text(feeCharges.toFixed(2));
            $("#charge").text(agentCharge.toFixed(2))
            $("#currency_sign").text('EUR')
            $("#set_currency_sign").text(currency_code);
            $("#recieved_currency").text(recieved_currency.toFixed(2));
            //SET Inputs
            amount = parseFloat(amount);
            $("#set_plateform_fee").val(feeCharges.toFixed(2));
            $("#set_reciever_amount").val(amount.toFixed(2));
            $("#set_reciever_amount_exchange").val(recieved_currency.toFixed(2));
            $("#set_sender_fee").val(agentCharge.toFixed(2));
        }

        function selectAgent(checkbox, agentId, cityId, countryId, isChecked, byNumber = false) {
            console.log(agentId);
            console.log(cityId, countryId);
            if (isChecked) {
                $("#set_agent_id").val(agentId);
                $(".agent-checkbox").not(checkbox).prop("checked", false);
                $("#set_city_id").val(cityId);
                $("#set_country_id").val(countryId)
                if (byNumber) {
                    getAgentCityWithCurrency(countryId);
                }
            } else {
                $("#set_agent_id").val("");
            }
        }

        function generateKey() {
            const randomPart = Math.floor(Math.random() * 10000000).toString().padStart(7, '0');
            const key = 'RS-' + randomPart;
            $("#secret_pin").val(key);
            $("#set_secret_pin").val(key);
        }

        function suggestionHandler(amount) {
            $("#amount-input").val(amount);
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
            inputAmount(amount);
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

        function changeCurrency(event) {
            // alert(event.target.value)
            const data = JSON.parse(event.target.value);
            const amount = $("#set_reciever_amount").val();
            if (amount === '') {
                return;
            }
            const result = parseFloat(data.exchange_rate) * parseFloat(amount);
            $("#recieved_currency").text(result.toFixed(2));
            $("#set_currency_sign").text(data.currency_code)
            console.log(result);
        }


        $(document).ready(function() {
            // Attach a change event listener to all checkboxes with the class 'agent-checkbox'
            $(".agent-checkbox").change(function() {
                // If the current checkbox is checked, uncheck all other checkboxes
                if ($(this).prop("checked")) {
                    $(".agent-checkbox").not(this).prop("checked", false);
                }
            });
        });
    </script>
@endpush
