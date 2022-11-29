<div class="app-menu navbar-menu bg-dark-100">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="#" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ URL::asset('assets/images/logo.png') }}" alt="" height="60">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('assets/images/logo.png') }}" alt="" height="60">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="#" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ URL::asset('assets/images/logo.png') }}" alt="" height="60">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('assets/images/logo.png') }}" alt="" height="60">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('dashboard') }}">
                        <i class="las la-chart-bar"></i> <span data-key="t-dashboards">Dashboards</span>
                    </a>
                </li> <!-- end Dashboard Menu -->


                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarSMS" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarSMS">
                        <i class=" las la-envelope-open-text"></i> <span data-key="t-layouts">SMS</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarSMS">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('sms-logs.lists') }}" class="nav-link">SMS
                                    Logs</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('sms-logs.quick-sms') }}" class="nav-link">Quick
                                    SMS</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('sms-logs.group-sms') }}" class="nav-link">Group SMS</a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('sms-logs.file-sms') }}" class="nav-link">File
                                    SMS</a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('templates.index') }}" class="nav-link">SMS
                                    Templates</a>
                            </li>

                            {{-- <li class="nav-item">
                                <a href="#" class="nav-link">SMS Bundle</a>
                            </li> --}}
                        </ul>
                    </div>
                </li> <!-- end Dashboard Menu -->

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarContacts" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarContacts">
                        <i class=" las la-users"></i> <span data-key="t-pages">Manage Contacts</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarContacts">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('contacts.lists') }}" class="nav-link"
                                    data-key="t-starter">Contacts</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('contacts.create') }}" class="nav-link" data-key="t-team"> Register
                                    New Contact</a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('contacts.import') }}" class="nav-link" data-key="t-team">Import
                                    Contacts</a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('contact-groups.index') }}" class="nav-link" data-key="t-timeline">
                                    Contact Groups</a>
                            </li>
                        </ul>
                    </div>
                </li>

                {{-- <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarSetUp" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarSetUp">
                        <i class=" las la-cogs"></i> <span data-key="t-forms">SetUp</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarSetUp">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="forms-elements.html" class="nav-link" data-key="t-basic-elements">
                                    Senders
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="forms-select.html" class="nav-link" data-key="t-form-select">
                                    Channels
                                </a>
                            </li>
                        </ul>
                    </div>
                </li> --}}

                @if (Auth::user()->hasRole('admin'))
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#sidebarUsers" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarUsers">
                            <i class="las la-users-cog"></i> <span data-key="t-tables">Manage Users</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarUsers">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('users.index') }}" class="nav-link">
                                        Users
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('users.create') }}" class="nav-link">
                                        Register New User
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('roles.index') }}" class="nav-link">
                                        Roles
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
