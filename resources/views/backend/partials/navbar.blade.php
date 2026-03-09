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
                {{-- pricing & plan   --}}
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('plan.index') ? 'active' : '' }}" href="{{ route('plan.index') }}">
                        <i data-feather="package" class="nav-icon me-2 icon-xxs"></i> Subscription
                    </a>
                </li>


                {{-- properties Users --}}
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('property-owner.index') ? 'active' : '' }}"
                        href="{{ route('property-owner.index') }}">
                        <i data-feather="home" class="nav-icon me-2 icon-xxs"></i>Porichalok Users
                    </a>
                </li>
                {{-- resident-information --}}
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('resident-information.index') ? 'active' : '' }}"
                        href="{{ route('resident-information.index') }}">
                        <i data-feather="users" class="nav-icon me-2 icon-xxs"></i> Resident Users
                    </a>
                </li>
                {{-- Properties --}}
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin-property.*') ? 'active' : '' }}"
                        href="{{ route('admin-property.index') }}">
                        <i data-feather="home" class="nav-icon me-2 icon-xxs"></i> Properties
                    </a>
                </li>

                {{-- emergencies --}}
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin-emergency.*') ? 'active' : '' }}"
                        href="{{ route('admin-emergency.index') }}">
                        <i data-feather="alert-triangle" class="nav-icon me-2 icon-xxs"></i> Emergencies
                    </a>
                </li>
                {{-- announcements --}}
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin-announcement.*') ? 'active' : '' }}"
                        href="{{ route('admin-announcement.index') }}">
                        <i data-feather="alert-circle" class="nav-icon me-2 icon-xxs"></i> Announcements
                    </a>
                </li>
                {{-- admin-maintenance --}}
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin-maintenance.*') ? 'active' : '' }}"
                        href="{{ route('admin-maintenance.index') }}">
                        <i data-feather="tool" class="nav-icon me-2 icon-xxs"></i> Maintenances
                    </a>
                </li>

                {{-- Categories section --}}
                <li class="nav-item">
                    <a class="nav-link collapsed {{ Route::is('categories.index') ? 'active' : '' }}" href#!"
                        data-bs-toggle="collapse" data-bs-target="#navCategories" aria-expanded="false"
                        aria-controls="navCategories">
                        <i data-feather="list" class="nav-icon me-2 icon-xxs"></i> Categories
                    </a>
                    <div id="navCategories" class="collapse {{ Request::is('categories*') ? 'show' : '' }}"
                        data-bs-parent="#sideNavbar">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link {{ request('section') == 'payment' ? 'active' : '' }}"
                                    href="{{ route('categories.index', ['section' => 'payment']) }}"> Payment </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request('section') == 'emergency' ? 'active' : '' }}"
                                    href="{{ route('categories.index', ['section' => 'emergency']) }}"> Emergency </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request('section') == 'maintenance' ? 'active' : '' }}"
                                    href="{{ route('categories.index', ['section' => 'maintenance']) }}"> Maintenance
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request('section') == 'announcement' ? 'active' : '' }}"
                                    href="{{ route('categories.index', ['section' => 'announcement']) }}"> Announcement
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request('section') == 'document' ? 'active' : '' }}"
                                    href="{{ route('categories.index', ['section' => 'document']) }}"> Document </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request('section') == 'security_contact' ? 'active' : '' }}"
                                    href="{{ route('categories.index', ['section' => 'security_contact']) }}"> Security
                                    Contact </a>
                            </li>
                        </ul>
                    </div>
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


            </ul>
        </div>
    </div>

</div>
