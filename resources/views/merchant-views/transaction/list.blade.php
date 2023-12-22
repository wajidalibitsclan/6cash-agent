@extends('layouts.merchant.app')

@section('title', translate('Transaction'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="d-flex align-items-center gap-3 mb-3">
            <img width="22" src="{{asset('public/assets/admin/img/media/lending.png')}}" alt="">
            <h1 class="page-header-title">{{translate('transaction')}}</h1>
        </div>

        <div class="d-flex flex-wrap justify-content-between align-items-center border-bottom gap-3 mb-3">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link {{$trx_type=='all'?'active':''}}"
                        href="{{url()->current()}}?trx_type=all">
                        {{translate('all')}}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{$trx_type=='debit'?'active':''}}"
                        href="{{url()->current()}}?trx_type=debit">
                        {{translate('debit')}}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{$trx_type=='credit'?'active':''}}"
                        href="{{url()->current()}}?trx_type=credit">
                        {{translate('credit')}}
                    </a>
                </li>
            </ul>
        </div>

        <div class="card">
            <div class="card-header __wrap-gap-10 flex-between">
                <div class="d-flex align-items-center gap-2">
                    <h5 class="card-header-title">{{translate('transaction Table')}}</h5>
                    <span class="badge badge-soft-secondary text-dark">{{ $transactions->total() }}</span>
                </div>
                <div>
                    <form action="{{url()->current()}}?trx_type={{$trx_type}}" method="GET">
                        <div class="input-group">
                            <input type="hidden" name="trx_type" value="{{$trx_type}}">

                            <input id="datatableSearch_" type="search" name="search"
                                    class="form-control mn-md-w280"
                                    placeholder="{{translate('Search by user info')}}" aria-label="Search"
                                    value="{{$search}}" required autocomplete="off">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">{{translate('Search')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Table -->
            <div class="table-responsive datatable-custom">
                <table
                    class="table table-borderless table-nowrap table-align-middle card-table table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th>{{translate('SL')}}</th>
                            <th>{{translate('Transaction Id')}}</th>
                            <th>{{translate('Sender')}}</th>
                            <th>{{translate('Receiver')}}</th>
                            <th>{{translate('Debit')}}</th>
                            <th>{{translate('Credit')}}</th>
                            <th>{{translate('Type')}}</th>
                            <th>{{translate('Balance')}}</th>
                            <th>{{translate('Time')}}</th>
                        </tr>
                    </thead>

                    <tbody>
                    @foreach($transactions as $key=>$transaction)
                        <tr>
                            <td>{{$transactions->firstitem()+$key}}</td>
                            <td>{{ $transaction->transaction_id??'' }}</td>
                            <td>
                                @php($sender_info = Helpers::get_user_info($transaction['from_user_id']))
                                @if($sender_info != null)
                                <div>
                                    <span>{{ $sender_info->f_name ?? '' }}</span>
                                </div>
                                <div>
                                    <a class="text-dark" href="tel:{{ $sender_info->phone ?? ''}}">{{ $sender_info->phone ?? ''}}</a>
                                </div>

                                @else
                                    <span class="text-muted badge badge-danger text-dark">{{ translate('User unavailable') }}</span>
                                @endif
                            </td>
                            <td>
                                @php($receiver_info = Helpers::get_user_info($transaction['to_user_id']))
                                @if($receiver_info != null)
                                    <div>
                                        <span>{{ $receiver_info->f_name ?? '' }}</span>
                                    </div>
                                    <div>
                                        <a class="text-dark" href="tel:{{ $receiver_info->phone ?? '' }}">{{ $receiver_info->phone ?? '' }}</a>
                                    </div>
                                @else
                                    <span class="text-muted badge badge-danger text-dark">{{ translate('User unavailable') }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="">
                                    {{ Helpers::set_symbol($transaction['debit']) }}
                                </span>
                            </td>
                            <td>
                                <span class="">
                                    {{ Helpers::set_symbol($transaction['credit']) }}
                                </span>
                            </td>
                            <td>
                                <span class="text-uppercase text-muted badge badge-light">{{ translate($transaction['transaction_type']) }}</span>
                            </td>
                            <td>
                                <span class="">{{ Helpers::set_symbol($transaction['balance']) }}</span>
                            </td>
                            <td>
                                <span class="text-muted">{{ $transaction->created_at->diffForHumans() }}</span>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="table-responsive mt-4 px-3">
                <div class="d-flex justify-content-end">
                    {!! $transactions->links() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_2')

@endpush
