<aside class="sidebar bg-sidebar">
    <div class="sidebar-start">
        <div class="sidebar-head">
            <a href="#" class="logo-wrapper" title="Home">
                <span class="sr-only">Home</span>
                <span class="icon logo" aria-hidden="true"></span>
                <div class="logo-text">
                    <span class="logo-title">Fleet XP</span>
                    <span class="logo-subtitle">Super Admin</span>
                </div>
            </a>
            <button class="sidebar-toggle transparent-btn" title="Menu" type="button">
                <span class="sr-only">Toggle menu</span>
                <span class="icon menu-toggle" aria-hidden="true"></span>
            </button>
        </div>
        <div class="sidebar-body">
            <ul class="sidebar-body-menu">
                <li>
                    <a class="{{ request()->is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><i class="fa-solid fa-gauge"></i>Dashboard</a>
                </li>
                <li>
                    <a class="show-cat-btn {{ request()->is('overviewOMC', 'addOMC', 'editOMC*', 'omc/viewDealer*', 'omc/viewProfile*') ? 'active' : '' }}" href="#">
                        <i class="fa-solid fa-oil-well"></i>OMCs
                        <span class="category__btn transparent-btn" title="Open list">
                            <span class="sr-only">Open list</span>
                            <span class="icon arrow-down" aria-hidden="true"></span>
                        </span>
                    </a>
                    <!-- Dropdown menu -->
                    <ul class="cat-sub-menu">
                        <li>
                            <a class="{{ request()->is('overviewOMC') ? 'active' : '' }}" href="{{ route('overviewOMC') }}">
                                <i class="fa-solid fa-gauge"></i>Overview
                            </a>
                        </li>
                        <li>
                            <a class="{{ request()->is('addOMC') ? 'active' : '' }}" href="{{ route('addOMC') }}">
                                <i class="fa-solid fa-plus"></i>Add
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a class="show-cat-btn {{ request()->is('overviewDealer', 'addDealer', 'editDealer*', 'dealer/viewDealerProfile*', 'dealer/dealerStationOverview*', 'dealer/dealerOMCOverview*') ? 'active' : '' }}" href="#">
                        <i class="fa-solid fa-people-arrows"></i>Dealers
                        <span class="category__btn transparent-btn" title="Open list">
                            <span class="sr-only">Open list</span>
                            <span class="icon arrow-down" aria-hidden="true"></span>
                        </span>
                    </a>
                    <ul class="cat-sub-menu">
                        <li>
                            <a class="{{ request()->is('overviewDealer') ? 'active' : '' }}" href="{{ route('overviewDealer') }}">
                                <i class="fa-solid fa-gauge"></i>Overview
                            </a>
                        </li>
                        <li>
                            <a class="{{ request()->is('addDealer') ? 'active' : '' }}" href="{{ route('addDealer') }}">
                                <i class="fa-solid fa-plus"></i>Add
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a class="show-cat-btn {{ request()->is('overviewStation', 'addStation', 'editStation*', 'station/viewStationProfile*', 'station/stationDealerOverview*') ? 'active' : '' }}" href="#">
                        <i class="fa-solid fa-charging-station"></i>Stations
                        <span class="category__btn transparent-btn" title="Open list">
                            <span class="sr-only">Open list</span>
                            <span class="icon arrow-down" aria-hidden="true"></span>
                        </span>
                    </a>
                    <ul class="cat-sub-menu">
                        <li>
                            <a class="{{ request()->is('overviewStation') ? 'active' : '' }}" href="{{ route('overviewStation') }}">
                                <i class="fa-solid fa-gauge"></i>Overview
                            </a>
                        </li>
                        <li>
                            <a class="{{ request()->is('addStation') ? 'active' : '' }}" href="{{ route('addStation') }}">
                                <i class="fa-solid fa-plus"></i>Add
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a class="show-cat-btn" href="##">
                        <i class="fa-solid fa-users-rays"></i>Attendants
                        <span class="category__btn transparent-btn" title="Open list">
                            <span class="sr-only">Open list</span>
                            <span class="icon arrow-down" aria-hidden="true"></span>
                        </span>
                    </a>
                    <ul class="cat-sub-menu">
                        <li>
                            <a href="posts.html"><i class="fa-solid fa-plus"></i>Add</a>
                        </li>
                        <li>
                            <a href="posts.html"><i class="fa-solid fa-list-check"></i>Manage</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="comments.html">
                        <i class="fa-solid fa-map-location-dot"></i>Maps
                    </a>
                </li>
                <li>
                    <a class="show-cat-btn" href="##">
                        <i class="fa-solid fa-biohazard"></i>Sensors
                    </a>
                </li>
                <li>
                    <a class="show-cat-btn {{ request()->is('overviewUser', 'addUser', 'editUser*', 'users/viewUserProfile*') ? 'active' : '' }}" href="#">
                        <i class="fa-solid fa-users"></i>Users
                        <span class="category__btn transparent-btn" title="Open list">
                            <span class="sr-only">Open list</span>
                            <span class="icon arrow-down" aria-hidden="true"></span>
                        </span>
                    </a>
                    <ul class="cat-sub-menu">
                        <li>
                            <a class="{{ request()->is('overviewUser') ? 'active' : '' }}" href="{{ route('overviewUser') }}">
                                <i class="fa-solid fa-gauge"></i>Overview
                            </a>
                        </li>
                        <li>
                            <a class="{{ request()->is('addUser') ? 'active' : '' }}" href="{{ route('addUser') }}">
                                <i class="fa-solid fa-plus"></i>Add
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a class="show-cat-btn" href="##">
                        <i class="fa-solid fa-users" aria-hidden="true"></i>Reports
                        <span class="category__btn transparent-btn" title="Open list">
                            <span class="sr-only">Open list</span>
                            <span class="icon arrow-down" aria-hidden="true"></span>
                        </span>
                    </a>
                </li>
            </ul>
            <span class="system-menu__title">system</span>
            <ul class="sidebar-body-menu">
                <li>
                    <a href="appearance.html"><span class="icon edit" aria-hidden="true"></span>Appearance</a>
                </li>
                <li>
                    <a href="##"><span class="icon setting" aria-hidden="true"></span>Settings</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="sidebar-footer">
        <a href="##" class="sidebar-user">
            <span class="sidebar-user-img">
                <picture>
                    <source srcset="{{ Vite::asset('resources/img/avatar/avatar-illustrated-01.webp') }}" type="image/webp">
                    <img src="{{ Vite::asset('resources/img/avatar/avatar-illustrated-01.webp') }}" alt="User name">
                  </picture>
            </span>
            <div class="sidebar-user-info">
                <span class="sidebar-user__title">Elijah Fullah</span>
                <span class="sidebar-user__subtitle">Support manager</span>
            </div>
        </a>
    </div>
</aside>