<!-- Header -->
<div class="card-header">
    <h5 class="card-header-title">
        {{translate('Top Agents')}}
    </h5>
    <a href="{{route('admin.agent.list')}}" class="fs-12px">{{translate('View All')}}</a>
</div>
<!-- End Header -->

<!-- Body -->
<div class="card-body">
    <div class="grid-container">
        @foreach($top_agents as $key=>$top_agent)
            @if(isset($top_agent->user))
                <div class="cursor-pointer"
                     onclick="location.href='{{route('admin.agent.view', [$top_agent['user_id']])}}'">
                    <div class="dashboard--card h-100 p-4">
                        <div class="bg-primary text-white rounded-10 d-flex flex-column align-items-center mb-2 fs-10 px-3 py-2">
                            <span>{{ translate('Total Transaction') }} :</span>
                            <span>{{ Helpers::set_symbol($top_agent['total_transaction']) }}</span>
                        </div>

                        <div class="avatar avatar-lg border border-width-2 rounded-circle mx-auto">
                            <img class="rounded-circle img-fit"
                                 onerror="this.src='{{asset('public/assets/admin/img/400x400/img2.jpg')}}'"
                                 src="{{asset('storage/app/public/agent'.'/'. $top_agent->user['image']  ?? '' )}}">
                        </div>
                        <div class="text-center font-weight-bolder mt-2">{{$top_agent->user['f_name']??'Not exist'}}</div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</div>
<!-- End Body -->
