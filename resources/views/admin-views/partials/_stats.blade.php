<div class="col-sm-6 col-xl-3">
    <!-- Card -->
    <div class="dashboard--card h-100">
        <h6 class="subtitle">{{translate('Total Generated eMoney')}}</h6>
        <h2 class="title">
            {{ Helpers::set_symbol($data['total_balance']??0) }}
        </h2>
        <img src="{{asset('public/assets/admin/img/media/dollar-1.png')}}" class="dashboard-icon" alt="">
    </div>
    <!-- End Card -->
</div>

<div class="col-sm-6 col-xl-3">
    <!-- Card -->
    <div class="dashboard--card h-100">
        <h6 class="subtitle">{{translate('eMoney Being Used')}}</h6>
        <h2 class="title">
            {{ Helpers::set_symbol($data['used_balance']??0) }}
        </h2>
        <img src="{{asset('public/assets/admin/img/media/dollar-2.png')}}" class="dashboard-icon" alt="">
    </div>
    <!-- End Card -->
</div>

<div class="col-sm-6 col-xl-3">
    <!-- Card -->
    <div class="dashboard--card h-100">
        <h6 class="subtitle">{{translate('Unused eMoney')}}</h6>
        <h2 class="title">
            {{ Helpers::set_symbol($data['unused_balance']??0) }}
        </h2>
        <img src="{{asset('public/assets/admin/img/media/dollar-3.png')}}" class="dashboard-icon" alt="">
    </div>
    <!-- End Card -->
</div>

<div class="col-sm-6 col-xl-3">
    <!-- Card -->
    <div class="dashboard--card h-100">
        <h6 class="subtitle">{{translate('Total Earn from Charges')}}</h6>
        <h2 class="title">
            {{ Helpers::set_symbol($data['total_earned']??0) }}
        </h2>
        <img src="{{asset('public/assets/admin/img/media/dollar-4.png')}}" class="dashboard-icon" alt="">
    </div>
    <!-- End Card -->
</div>

