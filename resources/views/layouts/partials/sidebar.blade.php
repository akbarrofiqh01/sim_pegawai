@php
    $currentRoute = Route::currentRouteName();
    $settingRoutes = ['roles.list', 'permissions.list'];
    $isSettingActive = in_array($currentRoute, $settingRoutes);

    $settingRoutesKepegawaian = ['pegawai.list', 'jabatan.list', 'cuti.list'];
    $isSettingActiveKepegawaian = in_array($currentRoute, $settingRoutesKepegawaian);
@endphp

<div class="sticky">
    <div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
    <div class="app-sidebar">
        <div class="side-header">
            <a class="header-brand1" href="{{ route('dashboard') }}">
                <img src="{{ asset('backend/images/brand/logo.png') }}" class="header-brand-img desktop-logo"
                    alt="logo">
                <img src="{{ asset('backend/images/brand/logo-1.png') }}" class="header-brand-img toggle-logo"
                    alt="logo">
                <img src="{{ asset('backend/images/brand/logo-2.png') }}" class="header-brand-img light-logo"
                    alt="logo">
                <img src="{{ asset('backend/images/brand/logo-3.png') }}" class="header-brand-img light-logo1"
                    alt="logo">
            </a>
            <!-- LOGO -->
        </div>
        <div class="main-sidemenu">
            <div class="slide-left disabled" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191"
                    width="24" height="24" viewBox="0 0 24 24">
                    <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z" />
                </svg></div>
            <ul class="side-menu">
                <li class="sub-category">
                    <h3>Main Menu</h3>
                </li>
                <li class="slide">
                    <a class="side-menu__item {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                        data-bs-toggle="slide" href="{{ route('dashboard') }}"><i
                            class="side-menu__icon fe fe-home"></i><span class="side-menu__label">Dashboard</span></a>
                </li>
                @can('lihat menu kepegawaian')
                    <li class="slide {{ $isSettingActiveKepegawaian ? 'is-expanded active' : '' }}">
                        <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)">
                            <i class="side-menu__icon fe fe-users"></i>
                            <span class="side-menu__label">Kepegawaian</span>
                            <i class="angle fe fe-chevron-right"></i>
                        </a>
                        <ul class="slide-menu {{ $isSettingActiveKepegawaian ? 'open' : '' }}">
                            @can('lihat pegawai')
                                <li>
                                    <a href="{{ route('pegawai.list') }}"
                                        class="slide-item {{ $currentRoute === 'pegawai.list' ? 'active' : '' }}">
                                        Pegawai
                                    </a>
                                </li>
                            @endcan
                            @can('lihat data jabatan')
                                <li>
                                    <a href="{{ route('jabatan.list') }}"
                                        class="slide-item {{ $currentRoute === 'jabatan.list' ? 'active' : '' }}">
                                        Jabatan
                                    </a>
                                </li>
                            @endcan
                            @can('lihat cuti')
                                <li>
                                    <a href="{{ route('cuti.list') }}"
                                        class="slide-item {{ $currentRoute === 'cuti.list' ? 'active' : '' }}">
                                        Cuti
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                @can('lihat menu pengaturan')
                    <li class="slide {{ $isSettingActive ? 'is-expanded active' : '' }}">
                        <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)">
                            <i class="side-menu__icon fe fe-settings"></i>
                            <span class="side-menu__label">Pengaturan</span>
                            <i class="angle fe fe-chevron-right"></i>
                        </a>
                        <ul class="slide-menu {{ $isSettingActive ? 'open' : '' }}">
                            @can('lihat role user')
                                <li>
                                    <a href="{{ route('roles.list') }}"
                                        class="slide-item {{ $currentRoute === 'roles.list' ? 'active' : '' }}">
                                        Roles
                                    </a>
                                </li>
                            @endcan
                            @can('lihat permissions user')
                                <li>
                                    <a href="{{ route('permissions.list') }}"
                                        class="slide-item {{ $currentRoute === 'permissions.list' ? 'active' : '' }}">
                                        Permissions
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
            </ul>
            <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191"
                    width="24" height="24" viewBox="0 0 24 24">
                    <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z" />
                </svg></div>
        </div>
    </div>
    <!--/APP-SIDEBAR-->
</div>
