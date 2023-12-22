@extends('layouts.admin.app')

@section('title', translate('Currency Exchange Rate'))

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

        .input-field-class {
            border: none;
        }
    </style>
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="d-flex align-items-center gap-3 mb-3">
            <h2 class="page-header-title">{{ translate('Currency Exchange Rate') }}</h2>
        </div>
        <!-- End Page Header -->

        <div class="card">
            <div class="card-body">
                <table id="datatable-custom">
                    <thead class="header-table">
                        <th>Sr#</th>
                        <th>{{ translate('Base Currency') }}</th>
                        <th>{{ translate('Base Exchange Rate') }}</th>
                        <th>{{ translate('Country') }}</th>
                        <th>{{ translate('Currency Code') }}</th>
                        <th>{{ translate('Currency Symbol') }}</th>
                        <th>{{ translate('Exchange Rate') }}</th>
                        <th>{{ translate('Actions') }}</th>
                    </thead>
                    <tbody>
                        @foreach ($currencies as $key => $currency)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $baseCurrency->country }}</td>
                                <td>{{ $baseCurrency->exchange_rate }}</td>
                                <td>{{ $currency->country }}</td>
                                <td>{{ $currency->currency_code }}</td>
                                <td>{{ $currency->currency_symbol }}</td>
                                <td>{{ $currency->exchange_rate }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal"
                                        onclick="handleModal({{ $currency }})" data-target="#exampleModal">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('admin.currency.update') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Currency Exchange Rate</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="" class="font-weight-bold text-primary">Base</label>

                            <div class="d-flex justify-content-between">
                                <div>
                                    <label for="" class="font-weight-bold">Currency</label>
                                    <input type="text" value="" class="input-field-class"
                                        id="base-currency-country" readonly>
                                </div>
                                <div>
                                    <label for="" class="font-weight-bold">Currency Code</label>
                                    <input type="text" value="" class="input-field-class" id="base-currency-code"
                                        readonly>
                                </div>
                                <div>
                                    <label for="" class="font-weight-bold">Exchange Rate</label>
                                    <input type="text" value="" class="input-field-class"
                                        id="base-currency-exchange-rate" readonly>
                                </div>
                            </div>


                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="" class="font-weight-bold text-primary">Destination</label>
                            <input type="hidden" value="" name="id" id="destination-currency-id">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <label for="" class="font-weight-bold">Currency</label>
                                    <input type="text" value="" class="input-field-class"
                                        id="destination-currency-country" readonly>
                                </div>
                                <div>
                                    <label for="" class="font-weight-bold">Currency Code</label>
                                    <input type="text" value="" class="input-field-class"
                                        id="destination-currency-code" readonly>
                                </div>
                                <div>
                                    <label for="" class="font-weight-bold">Exchange Rate</label>
                                    <input type="text" value="" name="exchange_rate" class="form-control"
                                        id="destination-currency-exchange-rate">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('script_2')
    <script>
        const baseCurrency = @json($baseCurrency);

        $(document).ready(function() {
            $('#datatable-custom').DataTable({
                responsive: true,
                // scrollX: true
            }); // Correct
        });
        /* ON Page Load Set Base Currency Values in Modal */
        function onLoadPage() {
            let baseCurrencyCode = document.getElementById('base-currency-code');
            let baseCurrencyCountry = document.getElementById('base-currency-country')
            let baseCurrencyExchangeRate = document.getElementById('base-currency-exchange-rate')

            baseCurrencyCode.value = baseCurrency.currency_code
            baseCurrencyCountry.value = baseCurrency.country
            baseCurrencyExchangeRate.value = baseCurrency.exchange_rate
        }
        onLoadPage();
        /* Modal Handler to Set Form Values */
        function handleModal(currency) {
            let destinationExchangeRate = document.getElementById('destination-currency-exchange-rate');
            let destinationCurrencyCode = document.getElementById('destination-currency-code');
            let destinationCurrencyCountry = document.getElementById('destination-currency-country');
            let destinationCurrencyId = document.getElementById('destination-currency-id');
            destinationExchangeRate.value = currency.exchange_rate;
            destinationCurrencyCode.value = currency.currency_code;
            destinationCurrencyCountry.value = currency.country;
            destinationCurrencyId.value = currency.id;
        }
    </script>
@endpush
