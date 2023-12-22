<div id="sidebarMain" class="d-none">
    <aside
        class="js-navbar-vertical-aside navbar navbar-vertical-aside navbar-vertical navbar-vertical-fixed navbar-expand-xl navbar-bordered  ">
        <div class="navbar-vertical-container">
            <div class="navbar-brand-wrapper justify-content-between">
                <!-- Logo -->
                @php($restaurant_logo = \App\CentralLogics\helpers::get_business_settings('logo'))
                <a class="navbar-brand" href="{{ route('agent.dashboard') }}" aria-label="Front">
                    <img class="w-100 side-logo"
                        onerror="this.src='{{ asset('public/assets/admin/img/1920x400/img2.jpg') }}'"
                        src="{{ asset('storage/app/public/business/' . $restaurant_logo) }}" alt="Logo">
                    {{-- <img class="navbar-brand-logo-mini"
                         onerror="this.src='{{asset('public/assets/admin/img/160x160/img2.jpg')}}'"
                         src="{{asset('storage/app/public/business/'.$restaurant_logo)}}" alt="Logo"> --}}
                </a>
                <!-- End Logo -->

                <!-- Navbar Vertical Toggle -->
                <!-- End Navbar Vertical Toggle -->

                <div class="navbar-nav-wrap-content-left">
                    <!-- Navbar Vertical Toggle -->
                    <button type="button" class="js-navbar-vertical-aside-toggle-invoker close mr-">
                        <i class="tio-first-page navbar-vertical-aside-toggle-short-align"></i>
                        <i class="tio-last-page navbar-vertical-aside-toggle-full-align"></i>
                    </button>
                    <!-- End Navbar Vertical Toggle -->
                </div>
            </div>

            <!-- Content -->
            <div class="navbar-vertical-content">
                <form class="sidebar--search-form">
                    <div class="search--form-group">
                        <button type="button" class="btn"><i class="tio-search"></i></button>
                        <input type="text" class="form-control form--control"
                            placeholder="{{ translate('Search Menu...') }}" id="search-sidebar-menu">
                    </div>
                </form>

                <ul class="navbar-nav navbar-nav-lg nav-tabs">
                    <!-- Dashboards -->
                    <li class="navbar-vertical-aside-has-menu {{ Request::is('agent/dashboard') ? 'active' : '' }}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link" href="{{ route('agent.dashboard') }}"
                            title="{{ translate('dashboard') }}">
                            <i class="tio-home-vs-1-outlined nav-icon"></i>
                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{ translate('dashboard') }}
                            </span>
                        </a>
                    </li>
                    <!-- End Dashboards -->

                    <!-- Transaction History -->
                    <li
                        class="navbar-vertical-aside-has-menu {{ Request::is('agent/transaction-history') || Request::is('agent/transaction-history/*') ? 'active' : '' }}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link"
                            href="{{ route('agent.transaction.history') }}" title="Transaction History">
                            <i class="tio-money-vs nav-icon"></i>
                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{ translate('Transaction History') }}
                            </span>
                        </a>
                    </li>

                    <li
                        class="navbar-vertical-aside-has-menu {{ Request::is('agent/transaction/detail') ? 'active' : '' }}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link"
                            href="{{ route('agent.transaction.detail') }}" title="Transaction History">
                            <i class="tio-money-vs nav-icon"></i>
                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{ translate('Money Transfer') }}
                            </span>
                        </a>
                    </li>

                    <!-- Notification -->
                    {{-- <li class="navbar-vertical-aside-has-menu {{ Request::is('admin') ? 'active' : '' }}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link" href="{{ route('admin.dashboard') }}"
                            title="Transaction History">
                            <i class="tio-home-vs-1-outlined nav-icon"></i>
                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                Notifications
                            </span>
                        </a>
                    </li> --}}

                    <!-- Notification -->
                    <li class="navbar-vertical-aside-has-menu {{ Request::is('agent/profile') ? 'active' : '' }}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link" href="{{ route('agent.profile') }}"
                            title="Transaction History">
                            <i class="tio-user-big-outlined nav-icon"></i>
                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{ translate('profile') }}
                            </span>
                        </a>
                    </li>


                    <!-- Transaction History -->
                    <li class="navbar-vertical-aside-has-menu {{ Request::is('agent/send-money') ? 'active' : '' }}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link" href="{{ route('agent.send.money') }}"
                            title="Transaction History">
                            <i class="tio-users-switch nav-icon"></i>
                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{ translate('Send Money') }}
                            </span>
                        </a>
                    </li>
                    <!-- Transaction History -->
                    <li class="navbar-vertical-aside-has-menu {{ Request::is('agent/add-money') ? 'active' : '' }}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link" href="{{ route('agent.add.money') }}"
                            title="Transaction History">
                            <i class="tio-pound nav-icon"></i>
                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{ translate('Add Money') }}
                            </span>
                        </a>
                    </li>
                    <!-- Transaction History -->
                    <li
                        class="navbar-vertical-aside-has-menu {{ Request::is('agent/request-money') ? 'active' : '' }}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link"
                            href="{{ route('agent.request.money') }}" title="Transaction History">
                            <i class="tio-money nav-icon"></i>
                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{ translate('Request Money') }}
                            </span>
                        </a>
                    </li>
                    <!-- Transaction History -->
                    <li class="navbar-vertical-aside-has-menu {{ Request::is('agent/withdraw') ? 'active' : '' }}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link" href="{{ route('agent.withdraw') }}"
                            title="Transaction History">
                            <i class="tio-pound-outlined nav-icon"></i>
                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{ translate('withdraw') }}
                            </span>
                        </a>
                    </li>
                    {{-- Users section --}}


                    <li class="nav-item">
                        <small class="nav-subtitle"
                            title="{{ translate('user') }} {{ translate('section') }}">{{ translate('settings') }}</small>
                        <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                    </li>
                    <li
                        class="navbar-vertical-aside-has-menu {{ Request::is('agent/send-request') || Request::is('agent/withdraw-history') || Request::is('agent/withdraw-history/*') || Request::is('agent/send-request/*') || Request::is('agent/transaction-limit') || Request::is('agent/change-pin') ? 'active' : '' }}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:">
                            <i class="tio-settings-outlined nav-icon"></i>
                            <span
                                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('settings') }}</span>
                        </a>
                        <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                            style="display: {{ Request::is('agent/send-request') || Request::is('agent/withdraw-history') || Request::is('agent/withdraw-history/*') || Request::is('agent/send-request/*') || Request::is('agent/transaction-limit') || Request::is('agent/change-pin') ? 'block' : 'none' }}">
                            <li
                                class="navbar-vertical-aside-has-menu {{ Request::is('agent/withdraw-history') || Request::is('agent/withdraw-history/*') ? 'active' : '' }}">
                                <a class="nav-link " href="{{ route('agent.withdraw.history') }}"
                                    title="{{ translate('add') }}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate">{{ translate('Withdraw History') }}</span>
                                </a>
                            </li>
                            <li
                                class="navbar-vertical-aside-has-menu {{ Request::is('agent/send-request') || Request::is('agent/send-request/*') ? 'active' : '' }}">
                                <a class="nav-link " href="{{ route('agent.send.request') }}"
                                    title="{{ translate('list') }}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate">{{ translate('Send Requests') }}</span>
                                </a>
                            </li>
                            <li
                                class="navbar-vertical-aside-has-menu {{ Request::is('agent/transaction-limit') ? 'active' : '' }}">
                                <a class="nav-link " href="{{ route('agent.transaction.limit') }}"
                                    title="{{ translate('list') }}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate">{{ translate('Transaction Limits') }}</span>
                                </a>
                            </li>
                            <li
                                class="navbar-vertical-aside-has-menu {{ Request::is('agent/add-money-request') ? 'active' : '' }}">
                                <a class="nav-link " href="{{ route('agent.add.money.request') }}"
                                    title="{{ translate('list') }}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate">{{ translate('Add Money Requests') }}</span>
                                </a>
                            </li>
                            {{-- <li
                                class="navbar-vertical-aside-has-menu {{ Request::is('agent/requests') || Request::is('agent/requests/*') ? 'active' : '' }}">
                                <a class="nav-link " href="{{ route('agent.requests') }}"
                                    title="{{ translate('list') }}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate">Requests</span>
                                </a>
                            </li> --}}
                            <li
                                class="navbar-vertical-aside-has-menu {{ Request::is('agent/change-pin') ? 'active' : '' }}">
                                <a class="nav-link " href="{{ route('agent.change.pin') }}"
                                    title="{{ translate('list') }}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate">{{ translate('Change PIN') }}</span>
                                </a>
                            </li>
                            <li class="navbar-vertical-aside-has-menu">
                                <a class="nav-link " href="javascript:void(0)"
                                    onclick="deleteAccount({{ Auth::user()->id }})" title="{{ translate('list') }}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate">{{ translate('Delete Account') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    {{-- Users section --}}
                    <li class="nav-item">
                        <small class="nav-subtitle"
                            title="{{ translate('user') }} {{ translate('section') }}">{{ translate('Policies') }}</small>
                        <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                    </li>

                    {{-- agent --}}
                    <li
                        class="navbar-vertical-aside-has-menu {{ Request::is('agent/privacy-policy') || Request::is('agent/about-us') || Request::is('agent/terms-of-use') ? 'active' : '' }}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:">
                            <i class="tio-pages-outlined nav-icon"></i>
                            <span
                                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('Policies') }}</span>
                        </a>
                        <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                            style="display: {{ Request::is('agent/privacy-policy') || Request::is('agent/about-us') || Request::is('agent/terms-of-use') ? 'block' : 'none' }}">
                            <li
                                class="navbar-vertical-aside-has-menu {{ Request::is('agent/privacy-policy') ? 'active' : '' }}">
                                <a class="nav-link " href="{{ route('agent.policy') }}"
                                    title="{{ translate('add') }}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate">{{ translate('Privacy Policy') }}</span>
                                </a>
                            </li>
                            <li
                                class="navbar-vertical-aside-has-menu {{ Request::is('agent/about-us') ? 'active' : '' }}">
                                <a class="nav-link " href="{{ route('agent.aboutus') }}"
                                    title="{{ translate('list') }}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate">{{ translate('about Us') }}</span>
                                </a>
                            </li>
                            <li
                                class="navbar-vertical-aside-has-menu {{ Request::is('agent/terms-of-use') ? 'active' : '' }}">
                                <a class="nav-link " href="{{ route('agent.term') }}"
                                    title="{{ translate('Verification Requests') }}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate">{{ translate('Terms of Use') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <small class="nav-subtitle"
                            title="{{ translate('user') }} {{ translate('section') }}">{{ translate('Support') }}</small>
                        <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                    </li>
                    <li
                        class="navbar-vertical-aside-has-menu {{ Request::is('agent/support') || Request::is('agent/faq') ? 'active' : '' }}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:">
                            <i class="tio-settings-outlined nav-icon"></i>
                            <span
                                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('Support') }}</span>
                        </a>
                        <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                            style="display: {{ Request::is('agent/support') || Request::is('agent/faq') ? 'block' : 'none' }}">
                            <li
                                class="navbar-vertical-aside-has-menu {{ Request::is('agent/support') ? 'active' : '' }}">
                                <a class="nav-link " href="{{ route('agent.support') }}"
                                    title="{{ translate('add') }}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate">24/7 {{ translate('Support') }}</span>
                                </a>
                            </li>
                            <li class="navbar-vertical-aside-has-menu {{ Request::is('agent/faq') ? 'active' : '' }}">
                                <a class="nav-link " href="{{ route('agent.faq') }}"
                                    title="{{ translate('list') }}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate">{{ translate('FAQ') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>


                </ul>
            </div>
            <!-- End Content -->
        </div>
    </aside>
</div>

<div id="sidebarCompact" class="d-none">

</div>

@push('script_2')
    <script>
        $(window).on('load', function() {
            if ($(".navbar-vertical-content li.active").length) {
                $('.navbar-vertical-content').animate({
                    scrollTop: $(".navbar-vertical-content li.active").offset().top - 150
                }, 10);
            }
        });

        //Sidebar Menu Search
        var $rows = $('.navbar-vertical-content  .navbar-nav > li');
        $('#search-sidebar-menu').keyup(function() {
            var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

            $rows.show().filter(function() {
                var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
                return !~text.indexOf(val);
            }).hide();
        });
    </script>
@endpush
