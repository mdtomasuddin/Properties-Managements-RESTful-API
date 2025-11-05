<div class="app-menu">
    <!-- Sidebar -->

    <div class="navbar-vertical navbar nav-dashboard">
        <div class="h-100" data-simplebar>
            <!-- Brand logo -->
            <a class="navbar-brand" href="{{route('dashboard')}}">
                <img src="{{ asset('assets/backend/images/brand/logo/logo-2.svg')}}"
                    alt="dash ui - bootstrap 5 admin dashboard template">
            </a>
            <!-- Navbar nav -->
            <ul class="navbar-nav flex-column" id="sideNavbar">

                <!-- Nav item -->
                <li class="nav-item">
                    <a class="nav-link {{Route::is('dashboard') ? 'active': ''}}" href="{{route('dashboard')}}">
                        <i data-feather="home" class="nav-icon me-2 icon-xxs"></i>Dashboard
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{Route::is('v1.setting.mail.show') ? 'active': ''}}" href="{{route('v1.setting.mail.show')}}">
                        <i data-feather="mail" class="nav-icon me-2 icon-xxs"></i> Email Setting
                    </a>
                </li>
            </ul>
        </div>
    </div>

</div>
