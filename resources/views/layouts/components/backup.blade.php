<div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1">
    <div class="d-flex align-items-stretch" id="kt_header_nav">
        <div class="header-menu align-items-stretch" data-kt-drawer="true" data-kt-drawer-name="header-menu"
            data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true"
            data-kt-drawer-width="{default:'200px', '300px': '225px'}" data-kt-drawer-direction="start"
            data-kt-drawer-toggle="#kt_header_menu_mobile_toggle" data-kt-swapper="true" data-kt-swapper-mode="prepend"
            data-kt-swapper-parent="{default: '#kt_body', lg: '#kt_header_nav'}">
            <div class="menu menu-rounded menu-column menu-lg-row menu-active-bg menu-title-gray-700 menu-state-primary menu-arrow-gray-500 fw-semibold my-5 my-lg-0 px-4 px-lg-0 align-items-stretch"
                id="#kt_header_menu" data-kt-menu="true">
                <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start"
                    class="menu-item menu-lg-down-accordion me-lg-1">
                    <span class="menu-link py-3">
                        <span class="menu-title">Dashboards</span>
                        <span class="menu-arrow d-lg-none"></span>
                    </span>
                    <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-200px"
                        style="">
                        <div class="menu-item">
                            <a class="menu-link py-3" href="{{ route('backstreet.dashboards.callcenter') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Call Center</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link py-3" href="/ceres-html-pro/index.html">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Omni Channel</span>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start"
                    class="menu-item menu-lg-down-accordion me-lg-1">
                    <span class="menu-link py-3">
                        <span class="menu-title">Call Center</span>
                        <span class="menu-arrow d-lg-none"></span>
                    </span>
                    <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-200px"
                        style="">
                        <div class="menu-item">
                            <a class="menu-link py-3" href="{{ route('backstreet.tickets.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Insidents</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link py-3" href="{{ route('backstreet.callrecords.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Call Records</span>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start"
                    class="menu-item menu-lg-down-accordion me-lg-1">
                    <span class="menu-link py-3">
                        <span class="menu-title">Omni Channel</span>
                        <span class="menu-arrow d-lg-none"></span>
                    </span>
                    <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-200px"
                        style="">
                        <div class="menu-item">
                            <a class="menu-link py-3" href="/ceres-html-pro/index.html">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Report Tickets</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link py-3" href="/ceres-html-pro/index.html">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Report CDR</span>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start"
                    class="menu-item menu-lg-down-accordion me-lg-1">
                    <span class="menu-link py-3">
                        <span class="menu-title">User Managements</span>
                        <span class="menu-arrow d-lg-none"></span>
                    </span>
                    <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-200px"
                        style="">
                        <div class="menu-item">
                            <a class="menu-link py-3" href="/ceres-html-pro/index.html">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">List Users</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link py-3" href="/ceres-html-pro/index.html">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Roles</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex align-items-stretch flex-shrink-0">
        <div class="topbar d-flex align-items-stretch flex-shrink-0">
            <div class="d-flex align-items-center ms-2 ms-lg-3">
                <div class="btn btn-icon btn-custom btn-active-light w-35px h-35px w-md-40px h-md-40px"
                    id="kt_activities_toggle">
                    <i class="ki-duotone ki-notification-on fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                        <span class="path4"></span>
                        <span class="path5"></span></i>
                </div>
            </div>

            <div class="d-flex align-items-center ms-2 ms-lg-3">

                <a href="#" class="btn btn-icon btn-custom btn-active-light w-35px h-35px w-md-40px h-md-40px"
                    data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-attach="parent"
                    data-kt-menu-placement="bottom-end">
                    <i class="ki-duotone ki-night-day theme-light-show fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                        <span class="path4"></span>
                        <span class="path5"></span>
                        <span class="path6"></span>
                        <span class="path7"></span>
                        <span class="path8"></span>
                        <span class="path9"></span>
                        <span class="path10"></span>
                    </i>
                    <i class="ki-duotone ki-moon theme-dark-show fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </a>

                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold py-4 fs-base w-150px"
                    data-kt-menu="true" data-kt-element="theme-mode-menu">
                    <div class="menu-item px-3 my-0">
                        <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="light">
                            <span class="menu-icon" data-kt-element="icon">
                                <i class="ki-duotone ki-night-day fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                    <span class="path5"></span>
                                    <span class="path6"></span>
                                    <span class="path7"></span>
                                    <span class="path8"></span>
                                    <span class="path9"></span>
                                    <span class="path10"></span></i>
                            </span>
                            <span class="menu-title">
                                Light
                            </span>
                        </a>
                    </div>
                    <div class="menu-item px-3 my-0">
                        <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="dark">
                            <span class="menu-icon" data-kt-element="icon">
                                <i class="ki-duotone ki-moon fs-2"><span class="path1"></span><span
                                        class="path2"></span></i>
                            </span>
                            <span class="menu-title">
                                Dark
                            </span>
                        </a>
                    </div>
                    <div class="menu-item px-3 my-0">
                        <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="system">
                            <span class="menu-icon" data-kt-element="icon">
                                <i class="ki-duotone ki-screen fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                </i>
                            </span>
                            <span class="menu-title">
                                System
                            </span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center ms-2 ms-lg-3" id="kt_header_user_menu_toggle">
                <div class="cursor-pointer symbol symbol-35px symbol-md-40px"
                    data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent"
                    data-kt-menu-placement="bottom-end">
                    <img alt="Pic" src="../../assets/media/avatars/300-1.jpg" />
                </div>

                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px"
                    data-kt-menu="true">
                    <div class="menu-item px-3">
                        <div class="menu-content d-flex align-items-center px-3">
                            <div class="symbol symbol-50px me-5">
                                <img alt="Logo" src="../../assets/media/avatars/300-1.jpg" />
                            </div>
                            <div class="d-flex flex-column">
                                <div class="fw-bold d-flex align-items-center fs-5">
                                    Max Smith <span
                                        class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">Pro</span>
                                </div>

                                <a href="#" class="fw-semibold text-muted text-hover-primary fs-7">
                                    max@kt.com </a>
                            </div>
                        </div>
                    </div>
                    <div class="menu-item px-5 my-1">
                        <a href="../../account/settings.html" class="menu-link px-5">
                            Account Settings
                        </a>
                    </div>
                    <div class="menu-item px-5">
                        <a href="../../authentication/sign-in/basic.html" class="menu-link px-5">
                            Sign Out
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
