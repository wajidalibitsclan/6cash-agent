<!-- Header -->
<div class="card-header">
    <h5 class="card-header-title">
        {{translate('Top Transactions')}}
    </h5>
    <a href="{{route('admin.transaction.index', ['trx_type'=>'all'])}}" class="fs-12px">{{translate('View All')}}</a>
</div>
<!-- End Header -->

<!-- Body -->
<div class="card-body">
    @foreach($top_transactions as $key=>$top_transaction)
        @if(isset($top_transaction->user))
            <div
                @if($top_transaction->user['type']==1)
                onclick="location.href='{{route('admin.agent.view',[$top_transaction->user['id']])}}'"
                @elseif($top_transaction->user['type']==2)
                onclick="location.href='{{route('admin.customer.view',[$top_transaction->user['id']])}}'"
                @elseif($top_transaction->user['type']==3)
                onclick="location.href='{{route('admin.merchant.view',[$top_transaction->user['id']])}}'"
                @endif
                class="cursor-pointer d-flex justify-content-between gap-2 align-items-center mb-4">
                <div class="d-flex gap-3 align-items-center">
                    <div class="avatar rounded border">
                        <img class="rounded img-fit"
                            src="{{asset('storage/app/public')}}/{{ $top_transaction->user['type']==1?'agent':'customer' }}/{{ $top_transaction->user['image'] ?? '' }}"
                            onerror="this.src='{{asset('public/assets/admin/img/160x160/img2.jpg')}}'"
                            alt="{{$top_transaction->user->phone}} image">
                    </div>
                    <span class="hover-to-primary">
                        {{ Str::limit($top_transaction->user->f_name . ' (' . $top_transaction->user->phone . ')', 20) }}
                    </span>
                </div>
                <div>
                    <span class="fs-18">
                        {{ Helpers::set_symbol($top_transaction['total_transaction']) }}
                    </span>
                </div>
            </div>
        @endif
    @endforeach
</div>
<!-- End Body -->
