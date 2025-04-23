@php
    $menus = auth()->user()->getReadableMenus();
@endphp
<div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1">
    <div class="d-flex align-items-stretch" id="kt_header_nav">
        <div class="header-menu align-items-stretch" data-kt-drawer="true" data-kt-drawer-name="header-menu"
            data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true"
            data-kt-drawer-width="{default:'200px', '300px': '225px'}" data-kt-drawer-direction="start"
            data-kt-drawer-toggle="#kt_header_menu_mobile_toggle" data-kt-swapper="true" data-kt-swapper-mode="prepend"
            data-kt-swapper-parent="{default: '#kt_body', lg: '#kt_header_nav'}">
            <div class="menu menu-rounded menu-column menu-lg-row menu-active-bg menu-title-gray-700 menu-state-primary menu-arrow-gray-500 fw-semibold my-5 my-lg-0 px-4 px-lg-0 align-items-stretch"
                id="#kt_header_menu" data-kt-menu="true">

                @foreach ($menus as $menu)
                    @php
                        $isParentActive = false;
                        foreach ($menu->children ?? [] as $child) {
                            if (request()->is(ltrim($child->route, '/'))) {
                                $isParentActive = true;
                                break;
                            }
                        }
                    @endphp

                    <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start"
                        class="menu-item menu-lg-down-accordion me-lg-1 {{ $isParentActive ? 'here show' : '' }}">
                        <span class="menu-link py-3">
                            <span class="menu-title">{{ $menu->name }}</span>
                            <span class="menu-arrow d-lg-none"></span>
                        </span>
                        @if ($menu->children)
                            <div
                                class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-200px">
                                @foreach ($menu->children as $children)
                                    @php
                                        $isActive = request()->is(ltrim($children->route, '/'));
                                    @endphp
                                    <div class="menu-item">
                                        <a class="menu-link py-3 {{ $isActive ? 'active' : '' }}"
                                            href="{{ url($children->route) }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">{{ $children->name }}</span>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
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
                    <img alt="Pic" src="{{ asset('assets/media/avatars/300-1.jpg') }}" />
                </div>

                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px"
                    data-kt-menu="true">
                    <div class="menu-item px-3">
                        <div class="menu-content d-flex align-items-center px-3">
                            <div class="symbol symbol-50px me-5">
                                <img alt="Logo" src="{{ asset('assets/media/avatars/300-1.jpg') }}" />
                            </div>
                            <div class="d-flex flex-column">
                                <div class="fw-bold d-flex align-items-center fs-5">
                                    {{ Auth::user()->full_name }}
                                    @if (Auth::user()->detail)
                                        <span
                                            class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">{{ Auth::user()->detail->department->name }}</span>
                                    @endif
                                </div>

                                <a href="#" class="fw-semibold text-muted text-hover-primary fs-7">
                                    {{ Auth::user()->email }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="menu-item px-5 my-1">
                        <a href="" class="menu-link px-5">
                            Account Settings
                        </a>
                    </div>
                    <div class="menu-item px-5">
                        <a href="#" class="menu-link px-5"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Sign Out
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
