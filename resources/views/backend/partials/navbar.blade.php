<div class="app-menu">
    <!-- Sidebar -->

    <div class="navbar-vertical navbar nav-dashboard">
        <div class="h-100" data-simplebar>
            <!-- Brand logo -->
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <img src="{{ asset('assets/backend/images/brand/logo/logo-2.png') }}"
                    alt="dash ui - bootstrap 5 admin dashboard template">
            </a>
            <!-- Navbar nav -->
            <ul class="navbar-nav flex-column" id="sideNavbar">

                <!-- Nav item -->
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i data-feather="grid" class="nav-icon me-2 icon-xxs"></i>Dashboard
                    </a>
                </li>

                {{-- properties Users --}}
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('property-owner.index') ? 'active' : '' }}"
                        href="{{ route('property-owner.index') }}">
                        <i data-feather="home" class="nav-icon me-2 icon-xxs"></i> Properties Users
                    </a>
                </li>
                {{-- resident-information --}}
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('resident-information.index') ? 'active' : '' }}"
                        href="{{ route('resident-information.index') }}">
                        <i data-feather="users" class="nav-icon me-2 icon-xxs"></i> Resident Users
                    </a>
                </li>
                {{-- faq section   --}}
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('faq.index') ? 'active' : '' }}" href="{{ route('faq.index') }}">
                        <i data-feather="help-circle" class="nav-icon me-2 icon-xxs"></i> FAQ </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ Route::is('v1.setting.mail.show') ? 'active' : '' }}"
                        href="{{ route('v1.setting.mail.show') }}">
                        <i data-feather="mail" class="nav-icon me-2 icon-xxs"></i> Email Setting
                    </a>
                </li>

                {{-- pricing & plan   --}}
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('plan.index') ? 'active' : '' }}"
                        href="{{ route('plan.index') }}">
                        <i data-feather="credit-card" class="nav-icon me-2 icon-xxs"></i> Subscription Plans
                    </a>
                </li>
            </ul>
        </div>
    </div>

</div>
