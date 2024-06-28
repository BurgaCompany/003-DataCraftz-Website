<div class="lime-sidebar">
    <div class="lime-sidebar-inner slimscroll">
        <ul class="accordion-menu">
            <li class="sidebar-title">
                Apps
            </li>

            @if (Auth::check() && !Auth::user()->hasRole('Root'))
                <li>
                    @if (Auth::user()->hasRole('Admin'))
                        <a href="{{ route('dashboard_admin') }}"
                            class="{{ request()->is('dashboard_admin') ? 'active' : '' }}">
                            <i class="material-icons">dashboard</i>Dashboard
                        </a>
                    @elseif (Auth::user()->hasRole('PO'))
                        <a href="{{ route('dashboard_po') }}" class="{{ request()->is('dashboard_po') ? 'active' : '' }}">
                            <i class="material-icons">dashboard</i>Dashboard
                        </a>
                    @elseif (Auth::user()->hasRole('Upt'))
                        <a href="{{ route('dashboard_upt') }}"
                            class="{{ request()->is('dashboard_upt') ? 'active' : '' }}">
                            <i class="material-icons">dashboard</i>Dashboard
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}">
                            <i class="material-icons">dashboard</i>Dashboard
                        </a>
                    @endif
                </li>
            @endif


            @if (Auth::check() && Auth::user()->hasRole('Root'))
                <li>
                    <a href="{{ route('upts.index') }}" class="{{ request()->is('upts*') ? 'active' : '' }}">
                        <i class="material-icons">person_outline</i>Management Upt
                    </a>
                </li>
                <li>
                    <a href="{{ route('otobuses.index') }}" class="{{ request()->is('otobuses*') ? 'active' : '' }}">
                        <i class="material-icons">person_outline</i>Management PO
                    </a>
                </li>
            @endif

            @auth
                @if (Auth::check() && Auth::user()->hasRole('Upt'))
                    <li>
                        <a href="{{ route('admins.index') }}" class="{{ request()->is('admins*') ? 'active' : '' }}">
                            <i class="material-icons">group</i>Management Admin
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('bus_stations.index') }}"
                            class="{{ request()->is('bus_stations*') ? 'active' : '' }}">
                            <i class="material-icons">departure_board</i>Management Terminal
                        </a>
                    </li>
                @endif
            @endauth

            @if (Auth::check() && Auth::user()->hasRole('PO'))
                <li>
                    <a href="{{ route('drivers.index') }}" class="{{ request()->is('drivers*') ? 'active' : '' }}">
                        <i class="material-icons">person_outline</i>Management Sopir
                    </a>
                </li>
                <li>
                    <a href="{{ route('bus_conductors.index') }}"
                        class="{{ request()->is('bus_conductors*') ? 'active' : '' }}">
                        <i class="material-icons">person_outline</i>Management Kondektur
                    </a>
                </li>
                <li>
                    <a href="{{ route('busses.index') }}" class="{{ request()->is('busses*') ? 'active' : '' }}">
                        <i class="material-icons">directions_bus</i>Management Bus
                    </a>
                </li>
            @endif
            @if (Auth::check() && Auth::user()->hasAnyRole(['Root', 'Upt', 'Admin']))
                <li>
                    <a href="{{ route('transits.index') }}" class="{{ request()->is('transits*') ? 'active' : '' }}">
                        <i class="material-icons">directions_bus</i>Data Transit
                    </a>
                </li>
            @endif
            <li>
                <a href="{{ route('schedules.index') }}" class="{{ request()->is('schedules*') ? 'active' : '' }}">
                    <i class="material-icons">schedule</i>Jadwal
                </a>
            </li>
            <li>
                <a href="{{ route('banks.index') }}" class="{{ request()->is('banks*') ? 'active' : '' }}">
                    <i class="material-icons">account_balance</i>Akun Bank
                </a>
            </li>
            <li>
                <a href="{{ route('reservations.index') }}"
                    class="{{ request()->is('reservations*') ? 'active' : '' }}">
                    <i class="material-icons">history</i>Riwayat Pemesanan
                </a>
            </li>
            <li>
                <a href="{{ route('deposits.index') }}" class="{{ request()->is('deposits*') ? 'active' : '' }}">
                    <i class="material-icons">history</i>Riwayat Withdraw
                </a>
            </li>
        </ul>
    </div>
</div>
