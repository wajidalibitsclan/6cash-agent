<div class="col-sm-6 col-md-4">
    <!-- Card -->
    <a class="dashboard--card h-100" href="#">
        <h6 class="subtitle">{{translate('Current Ballance')}}</h6>
        <h2 class="title">{{ Helpers::set_symbol($data['current_balance']??0) }}</h2>
        <img src="{{asset('public/assets/admin/img/media/dollar-1.png')}}" class="dashboard-icon" alt="">
    </a>
    <!-- End Card -->
</div>

<div class="col-sm-6 col-md-4">
    <!-- Card -->
    <a class="dashboard--card h-100" href="#">
        <h6 class="subtitle">{{translate('Pending Ballance')}}</h6>
        <h2 class="title">{{ Helpers::set_symbol($data['pending_balance']??0) }}</h2>
        <img src="{{asset('public/assets/admin/img/media/dollar-4.png')}}" class="dashboard-icon" alt="">
    </a>
    <!-- End Card -->
</div>

<div class="col-sm-6 col-md-4">
    <!-- Card -->
    <a class="dashboard--card h-100" href="#">
        <h6 class="subtitle">{{translate('Total Withdraw')}}</h6>
        <h2 class="title">{{ Helpers::set_symbol($data['total_withdraw']??0) }}</h2>
        <img src="{{asset('public/assets/admin/img/media/dollar-2.png')}}" class="dashboard-icon" alt="">
    </a>
    <!-- End Card -->
</div>




