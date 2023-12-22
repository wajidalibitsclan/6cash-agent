@extends('layouts.agent.app')
@push('css_or_js')
    <style>
        .tick-verify-icon,
        .wrong-verify-icon {
            background-color: #014f5b;
            color: #ffffff;
            width: 20px;
            height: 20px;
            border-radius: 100px;
            font-size: 11px;
            display: flex !important;
            align-items: center;
            justify-content: center;
            font-weight: 900 !important;
            margin-right: 7px
        }

        .wrong-verify-icon {
            background-color: #db1d40 !important;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-md-12">
                <h1>{{ translate('Money Transfer') }}</h1>
                <div class="mb-3">
                    <div class="d-flex justify-content-start">
                        <div>
                            <a href="{{ route('agent.transaction.detail') }}" class="btn btn-light">{{ translate('All') }}</a>
                            <a href="{{ route('agent.transaction.detail', ['string' => 'cash_in']) }}"
                                class="btn btn-light">{{ translate('Cash In') }}</a>
                            <a href="{{ route('agent.transaction.detail', ['string' => 'cash_out']) }}"
                                class="btn btn-light">{{ translate('Cash Out') }}</a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive datatable-custom">
                    <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                        id="datatable-custom">
                        <thead class="thead-light">
                            <tr>
                                <th>ID#</th>
                                <th>{{ translate('Type') }}</th>
                                <th>{{ translate('Collected Amount') }}</th>
                                <th>{{ translate('Amount') }}</th>
                                <th>{{ translate('Status') }}</th>
                                <th>{{ translate('Payable') }}</th>
                                <th>{{ translate('Payable Exchange') }}</th>
                                <th>{{ translate('Receiver Agent') }}</th>
                                <th>{{ translate('Sender Customer') }}</th>
                                <th>{{ translate('Receiver Customer') }}</th>
                                <th>{{ translate('Created At') }}</th>
                                <th>{{ translate('Action') }}</th>
                            </tr>
                        </thead>

                        <tbody id="set-rows">
                            @foreach ($request as $key => $requests)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        @if ($requests->sender_id === auth()->user()->id)
                                            cash_out
                                        @endif
                                        @if ($requests->receiver_id === auth()->user()->id)
                                            cash_in
                                        @endif
                                    </td>
                                    <td>{{ $requests->amount }} <span
                                            class="eur">{{ $requests->base_currency_code }}</span></td>
                                    <td>{{ $requests->receiver_amount }} <span
                                            class="eur">{{ $requests->base_currency_code }}</span></td>
                                    <td>
                                        <div
                                            class="badge {{ $requests->status === 'pending' || $requests->status === 'cancel' || $requests->status === 'reject' ? 'badge-danger' : 'badge-success' }}">
                                            {{ $requests->status }}
                                        </div>
                                    </td>
                                    <td>
                                        @if ($requests->sender_id === auth()->user()->id)
                                            0.00 <span class="eur">{{ $requests->base_currency_code }}</span>
                                        @else
                                            {{ $requests->receiver_amount }} <span
                                                class="eur">{{ $requests->base_currency_code }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($requests->sender_id === auth()->user()->id)
                                            0.00 <span class="eur">{{ $requests->destination_currency_code }}</span>
                                        @else
                                            {{ $requests->receiver_amount_exchange }} <span
                                                class="eur">{{ $requests->destination_currency_code }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @isset($requests->receiver)
                                            {{ $requests->receiver->f_name }}{{ ' ' }}{{ $requests->receiver->l_name }}
                                        @endisset
                                    </td>
                                    <td>
                                        {{ $requests->senderCustomer->name }}, {{ $requests->senderCustomer->phone }}
                                    </td>
                                    <td>
                                        {{ $requests->receiverCustomer->name }}, {{ $requests->receiverCustomer->phone }}
                                    </td>
                                    <td>
                                        {{ $requests->created_at->diffForHumans() }}
                                    </td>
                                    <td>
                                        @if (strtotime($requests->created_at) > strtotime('-3 hours') &&
                                                $requests->sender_id === auth()->user()->id &&
                                                $requests->status !== 'cancel')
                                            @if ($requests->status !== 'reject')
                                                @if ($requests->status !== 'complete')
                                                    <a href="javascript:void(0)" class="btn btn-sm btn-danger"
                                                        onclick="cancelTransaction({{ $requests->id }})">Cancel</a>
                                                @else
                                                    <p>No actions</p>
                                                @endif
                                            @else
                                                <p>No actions</p>
                                            @endif
                                        @elseif($requests->receiver_id === auth()->user()->id && $requests->status === 'pending')
                                            <!-- Button trigger modal -->
                                            <button type="button" class="btn btn-sm btn-primary"
                                                onclick="setTransactionData({{ $requests }})" data-toggle="modal"
                                                data-target="#exampleModalCenter">
                                                Verify
                                            </button>
                                        @else
                                            <p>No actions</p>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <form action="{{ route('agent.transaction.verified') }}" method="POST">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Verify Customer</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div>
                                        <div class="d-flex justify-content-between">
                                            <div class="mb-3">
                                                <label for="">Sender Customer Name</label>
                                                <input type="hidden" class="form-control border-0" name="customer_phone"
                                                    id="customer_phone" readonly>
                                                <p id="sender_customer_name"></p>
                                            </div>
                                            <div>
                                                <label for="">Sender Customer Phone</label>
                                                <p id="sender_customer_phone"></p>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <label for="">Receiver Customer Name</label>
                                                <p id="receiver_customer_name"></p>
                                            </div>
                                            <div>
                                                <label for="">Receiver Customer Phone</label>
                                                <p id="receiver_customer_phone"></p>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="">Amount Payable</label>
                                            <div class="d-flex justify-content-between">
                                                <div class="d-flex align-items-center">
                                                    <label for="" class="mb-0 mr-2">Base:</label>
                                                    <input type="hidden" class="form-control border-0"
                                                        name="amount_payable" id="amount_payable">
                                                    <p id="base_amount" class="mb-0 font-weight-bold"></p>
                                                    <p id="base" class="mb-0 ml-1"></p>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <label for="" class="mb-0 mr-2">Exchange:</label>
                                                    <input type="hidden" class="form-control border-0"
                                                        name="amount_payable_exchange" id="amount_payable_exchange">
                                                    <p id="destination_amount" class="mb-0 font-weight-bold"></p>
                                                    <p id="destination" class="mb-0 ml-1"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <input type="hidden" id="transaction_id" name="transaction_id">
                                        <input type="text" name="secret_pin" class="form-control"
                                            placeholder="SECRET-PIN" id="secret_pin">
                                        {{-- <a href="javascript:void(0)" onclick="checkSecretPin()" id="secret_pin"
                                            class="btn btn-sm btn-primary d-flex align-items-center">Verify
                                            PIN</a> --}}
                                    </div>
                                    <div id="pin-status">

                                    </div>
                                </div>
                                <div class="modal-footer">
                                    {{-- <button type="button" class="btn btn-danger" onclick="rejectStatus()">Reject</button> --}}
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Update</button>
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
        $(document).ready(function() {
            $('#datatable-custom').DataTable({
                // responsive: true,
                // scrollX: true
            }); // Correct
        });

        function checkSecretPin() {
            let transactionId = $("#transaction_id").val();
            let secret_pin = $("#secret_pin").val();
            $.ajax({
                url: "{{ route('agent.transaction.secret.pin') }}",
                type: "POST",
                data: {
                    id: transactionId,
                    secret_pin: secret_pin,
                    _token: "{{ csrf_token() }}"
                },
                success: function(res) {
                    $(".remove-icon").remove();
                    if (res.status === 200) {
                        $("#pin-status").append(
                            `<div class="remove-icon text-primary d-flex mt-3 ml-2"><i class="fa-solid fa-check tick-verify-icon"></i> Verified</div>`
                        );
                    } else {
                        $("#pin-status").append(
                            `<div class="remove-icon text-danger d-flex mt-3 ml-2"> <i class="fa-solid fa-xmark wrong-verify-icon"></i> Wrong</div>`
                        );
                    }

                    setTimeout(() => {
                        $(".remove-icon").remove();
                    }, 5000);
                }
            })
        }

        function rejectStatus() {
            let id = $("#transaction_id").val();
            $.ajax({
                url: "{{ route('agent.transaction.reject') }}",
                type: 'POST',
                data: {
                    transaction_id: id,
                    _token: "{{ csrf_token() }}"
                },
                success: function(res) {
                    if (res.status === 200) {
                        location.reload();
                    } else {
                        location.reload();
                    }
                }
            })
        }

        function setTransactionData(data) {
            console.log(data);
            /*Customer*/
            $("#sender_customer_name").text(data.sender_customer.name)
            $("#sender_customer_phone").text(data.sender_customer.phone)

            $("#receiver_customer_name").text(data.receiver_customer.name)
            $("#receiver_customer_phone").text(data.receiver_customer.phone)

            $("#transaction_id").val(data.id)
            $("#customer_phone").val(data.customer_phone);
            $("#amount_payable").val(data.receiver_amount);
            $("#amount_payable_exchange").val(data.receiver_amount_exchange);
            $("#base_amount").text(data.receiver_amount);
            $("#destination_amount").text(data.receiver_amount_exchange)
            $("#base").text(data.base_currency_code);
            $("#destination").text(data.destination_currency_code);


            $("#secret_pin").val('');
            $(".remove-icon").remove();
        }

        function cancelTransaction(id) {
            Swal.fire({
                title: "Are you sure?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Sure!"
            }).then((result) => {
                console.log(result);
                if (result.value) {
                    $.ajax({
                        url: "{{ route('agent.transaction.cancel') }}",
                        type: 'POST',
                        data: {
                            transaction_detail_id: id,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(res) {
                            if (res.status === 200) {
                                location.reload();
                            } else {
                                location.reload();
                            }
                        }
                    })
                }
            });
        }
    </script>
@endpush
