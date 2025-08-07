@php
    $assetBase = asset('backend/assets');
@endphp

<div id="sidebar-menu">

    <div class="logo-box">
        <a class='logo logo-light' href='{{ route('admin.dashboard') }}'>
            <span class="logo-sm">
                <img src="{{ $assetBase }}/images/logo-sm.png" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ $assetBase }}/images/logo-light.png" alt="" height="24">
            </span>
        </a>
        <a class='logo logo-dark' href='{{ route('admin.dashboard') }}'>
            <span class="logo-sm">
                <img src="{{ $assetBase }}/images/logo-sm.png" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ $assetBase }}/images/logo-dark.png" alt="" height="24">
            </span>
        </a>
    </div>

    <ul id="side-menu">

        <li class="menu-title">Main</li>
        
        <li>
            <a class='tp-link' href='{{ route('admin.dashboard') }}'>
                <span class="nav-icon">
                 <iconify-icon icon="solar:home-2-bold-duotone"></iconify-icon>
              </span>
                <span> Dashboard </span>
            </a>
        </li>
        
        <!-- <li>
            <a class='tp-link' href='{{ route('admin.laying-hands.index') }}'>
                <i class="mdi mdi-hands-pray me-2"></i>Laying of Hands
            </a>
        </li> -->
        <li class="menu-title mt-2">Laying of Hands Requests</li>

        <li>
            <a class='tp-link' href='{{ route('admin.laying-hands.index') }}'>
                <i class="mdi mdi-view-dashboard me-2"></i>Overview
            </a>
        </li>
        <li>
            <a class='tp-link' href='{{ route('admin.laying-hands.pending') }}'>
                <i class="mdi mdi-clock-outline me-2"></i>Pending Requests
                @if(\App\Models\LayingHandsRequest::pending()->count() > 0)
                    <span class="badge bg-warning ms-2">{{ \App\Models\LayingHandsRequest::pending()->count() }}</span>
                @endif
            </a>
        </li>
        <li>
            <a class='tp-link' href='{{ route('admin.laying-hands.approved') }}'>
                <i class="mdi mdi-check-circle-outline me-2"></i>Approved Requests
            </a>
        </li>
        <li>
            <a class='tp-link' href='{{ route('admin.laying-hands.notified') }}'>
                <i class="mdi mdi-bell-outline me-2"></i>Notified Requests
            </a>
        </li>
        <li>
            <a class='tp-link' href='{{ route('admin.laying-hands.treated') }}'>
                <i class="mdi mdi-hands-pray me-2"></i>Treated Requests
            </a>
        </li>
        <li>
            <a class='tp-link' href='{{ route('admin.laying-hands.declined') }}'>
                <i class="mdi mdi-close-circle-outline me-2"></i>Declined Requests
            </a>
        </li>




        
        <li class="menu-title mt-2">Access Control</li>

        <li>
            <a class="tp-link" href="#">
                <i class="mdi mdi-account-multiple-outline me-2"></i>Group Name List
            </a>
        </li>
        <li>
            <a class="tp-link" href="#">
                <i class="mdi mdi-lock-outline me-2"></i>Permission List
            </a>
        </li>
        <li>
            <a class="tp-link" href="#">
                <i class="mdi mdi-account-key-outline me-2"></i>Role List
            </a>
        </li>
        <li>
            <a class="tp-link" href="#">
                <i class="mdi mdi-shield-key-outline me-2"></i>Roles With Permission
            </a>
        </li>


        <li class="menu-title mt-2">System Security Settings</li>
        
        <li>
            <a class='tp-link' href='{{ route('admin.login-attempts.index') }}'>
                <i class="mdi mdi-shield-account me-2"></i>Login Attempts
            </a>
        </li>
        
        <li>
            <a class='tp-link' href='{{ route('admin.login-attempts.suspicious') }}'>
                <i class="mdi mdi-shield-alert me-2"></i>Suspicious Activity
            </a>
        </li>
        
       
        <li>
            <a class="tp-link" href="{{ route('admin.user-bans.index') }}">
                <i class="mdi mdi-account-cancel-outline me-2"></i>Ban User
            </a>
        </li>




        <li>
            <a class="tp-link" href="{{ route('two-factor.setup') }}">
                <i class="mdi mdi-shield-key-outline me-2"></i>Two-Factor Auth
            </a>
        </li>
        <li>
            <a class="tp-link" href="{{ route('admin.ip.index') }}">
                <i class="mdi mdi-lan-connect me-2"></i>IP Management
            </a>
        </li>

        <li>
            <a class="tp-link" href="{{ route('admin.ip.access-logs') }}">
                <i class="mdi mdi-file-document-alert-outline me-2"></i>Access Logs
            </a>
        </li>


        <li>
            <a class="tp-link" href="#">
                <i class="mdi mdi-database-lock-outline me-2"></i>Backup Settings
            </a>
        </li>




      </ul>
</div>