<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('dashboard') }}">E-Voting</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('dashboard') }}">Ev</a>
        </div>
        <ul class="sidebar-menu">
            <li class="{{ Request::is('dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('dashboard') }}"><i class="fas fa-gauge"></i> <span>Dashboard</span></a>
            </li>
            <li class="{{ Request::is('users*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('users') }}"><i class="fas fa-user"></i> <span>Operator</span></a>
            </li>
            <li class="{{ Request::is('school-years*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('school-years') }}"><i class="fas fa-calendar"></i> <span>Tahun
                        Pelajaran</span></a>
            </li>
            <li class="{{ Request::is('classrooms*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('classrooms') }}"><i class="fas fa-school-flag"></i>
                    <span>Kelas</span></a>
            </li>
            <li class="{{ Request::is('candidates*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('candidates') }}"><i class="fas fa-user-group"></i>
                    <span>Kandidat</span></a>
            </li>
            <li class="menu-header">Pengaturan</li>
            <li class="{{ Request::is('profile*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('profile') }}"><i class="fa-regular fa-user"></i>
                    <span>Profile</span></a>
            </li>
            <li class="{{ Request::is('logout*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('logout') }}"><i class="fa-solid fa-right-from-bracket"></i>
                    <span>Logout</span></a>
            </li>
        </ul>
    </aside>
</div>
