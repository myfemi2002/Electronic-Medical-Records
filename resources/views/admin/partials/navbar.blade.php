{{-- resources/views/admin/partials/navbar.blade.php --}}
<!-- Top Navigation Bar -->
<nav class="navbar navbar-expand-lg top-navbar sticky-top">
    <div class="container-fluid">
        <!-- Brand -->
        <a class="navbar-brand d-flex align-items-center" href="{{ route('admin.dashboard') }}">
            <span class="brand-icon">EMS</span>
            <div class="d-none d-md-block">
                <div>EMS Dashboard</div>
                <small class="d-block" style="font-size: 0.75rem; color: var(--text-secondary);">Electronics Medical Records</small>
            </div>
        </a>
        
        <!-- Navbar Toggler -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Navbar Content -->
        <div class="collapse navbar-collapse" id="navbarContent">
            <div class="ms-auto d-flex align-items-center gap-3 mt-3 mt-lg-0">
                <!-- Search Box -->
                <div class="search-box w-100">
                    <i class="bi bi-search"></i>
                    <input type="search" class="form-control" placeholder="Search modules..." aria-label="Search">
                </div>
                
                <!-- Navigation Icons -->
                <div class="nav-icons d-flex gap-2">
                    <button type="button" class="btn position-relative" title="Alerts" aria-label="Alerts">
                        <i class="bi bi-bell-fill"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                            3
                        </span>
                    </button>
                    
                    <button type="button" class="btn" title="Messages" aria-label="Messages">
                        <i class="bi bi-envelope"></i>
                    </button>
                    
                    <button type="button" class="btn" id="themeToggle" title="Toggle Theme" aria-label="Toggle Theme">
                        <i class="bi bi-sun-fill" id="themeIcon"></i>
                    </button>
                    
                    <div class="dropdown">
                        <button type="button" class="btn dropdown-toggle" title="Profile" aria-label="Profile" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <div class="dropdown-item-text">
                                    <strong>{{ auth()->user()->name ?? 'Admin User' }}</strong><br>
                                    <small class="text-muted">{{ auth()->user()->email ?? '' }}</small>
                                </div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('admin.profile') }}"><i class="bi bi-person me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('admin.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>