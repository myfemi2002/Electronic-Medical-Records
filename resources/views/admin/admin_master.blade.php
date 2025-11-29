{{-- resources/views/admin/admin_master.blade.php --}}
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'EMS Dashboard') - Electronics Medical Records System</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    @stack('css')
 <style>
    /* Light Mode Variables */
    :root[data-theme="light"] {
        --primary-color: #4f46e5;
        --secondary-color: #06b6d4;
        --bg-gradient-start: #f1f5f9;
        --bg-gradient-end: #e2e8f0;
        --card-bg: #ffffff;
        --border-color: #e2e8f0;
        --text-primary: #1e293b;
        --text-secondary: #64748b;
        --text-muted: #94a3b8;
        --hover-bg: #f8fafc;
        --navbar-bg: rgba(255, 255, 255, 0.95);
        --tile-bg: rgba(255, 255, 255, 0.8);
        --tile-hover-bg: #ffffff;
        --stats-card-bg: #ffffff;
        --shadow-color: rgba(0, 0, 0, 0.1);
        --success-color: #10b981;
        --warning-color: #f59e0b;
        --danger-color: #ef4444;
        --info-color: #3b82f6;
    }
    
    /* Dark Mode Variables */
    :root[data-theme="dark"],
    :root {
        --primary-color: #4f46e5;
        --secondary-color: #06b6d4;
        --bg-gradient-start: #0f172a;
        --bg-gradient-end: #1e293b;
        --card-bg: #1e293b;
        --border-color: #334155;
        --text-primary: #e2e8f0;
        --text-secondary: #e2e8f0;
        --text-muted: #94a3b8;
        --hover-bg: #334155;
        --navbar-bg: rgba(30, 41, 59, 0.95);
        --tile-bg: rgba(30, 41, 59, 0.6);
        --tile-hover-bg: rgba(30, 41, 59, 0.9);
        --stats-card-bg: rgba(30, 41, 59, 0.6);
        --shadow-color: rgba(0, 0, 0, 0.3);
        --success-color: #10b981;
        --warning-color: #f59e0b;
        --danger-color: #ef4444;
        --info-color: #3b82f6;
    }
    
    * {
        font-family: 'Inter', sans-serif;
        transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
    }
    
    body {
        background: linear-gradient(135deg, var(--bg-gradient-start) 0%, var(--bg-gradient-end) 100%);
        min-height: 100vh;
        color: var(--text-primary);
    }
    
    /* Top Navigation Bar */
    .top-navbar {
        background: var(--navbar-bg);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid var(--border-color);
        box-shadow: 0 2px 10px var(--shadow-color);
    }
    
    .navbar-brand {
        font-weight: 600;
        font-size: 1.1rem;
        color: var(--text-primary) !important;
    }
    
    .brand-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        margin-right: 12px;
        font-size: 0.875rem;
        color: white;
    }
    
    .search-box {
        position: relative;
        max-width: 300px;
    }
    
    .search-box input {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 10px;
        color: var(--text-primary) !important;
        padding-left: 40px;
    }
    
    .search-box input::placeholder {
        color: var(--text-muted);
        opacity: 1;
    }
    
    .search-box input:focus {
        background: var(--hover-bg);
        border-color: var(--primary-color);
        color: var(--text-primary) !important;
        box-shadow: 0 0 0 0.2rem rgba(79, 70, 229, 0.25);
    }
    
    .search-box i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-secondary);
    }
    
    .nav-icons .btn {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        color: var(--text-secondary);
        border-radius: 10px;
        width: 40px;
        height: 40px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .nav-icons .btn:hover {
        background: var(--hover-bg);
        border-color: var(--primary-color);
        color: var(--text-primary);
    }
    
    /* Page Header */
    .page-header {
        margin-bottom: 1.5rem;
    }
    
    .page-header h1,
    .page-header h3,
    .page-header .page-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--text-primary);
    }
    
    .page-header p {
        color: var(--text-secondary);
        margin-bottom: 0;
        font-size: 0.875rem;
    }
    
    /* Role Badge */
    .role-badge {
        display: inline-block;
        padding: 0.35rem 0.75rem;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 600;
        background: linear-gradient(135deg, rgba(79, 70, 229, 0.15), rgba(6, 182, 212, 0.15));
        border: 1px solid var(--primary-color);
        color: var(--text-primary);
        margin-top: 0.5rem;
    }
    
    /* Dashboard Cards/Tiles */
    .dashboard-tile {
        background: var(--tile-bg);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 1.25rem;
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        color: inherit;
        display: block;
        height: 100%;
        min-height: 140px;
        position: relative;
    }
    
    .dashboard-tile:hover {
        background: var(--tile-hover-bg);
        border-color: var(--primary-color);
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(79, 70, 229, 0.2);
        color: inherit;
    }
    
    .dashboard-tile:focus {
        outline: 3px solid rgba(79, 70, 229, 0.5);
        outline-offset: 2px;
    }
    
    .tile-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, rgba(79, 70, 229, 0.1), rgba(6, 182, 212, 0.1));
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.75rem;
        font-size: 1.5rem;
        color: var(--primary-color);
    }
    
    .tile-title {
        font-size: 0.95rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
        color: var(--text-primary);
    }
    
    .tile-description {
        font-size: 0.75rem;
        color: var(--text-secondary);
        margin-bottom: 0;
    }
    
    .tile-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        font-size: 0.65rem;
        font-weight: 600;
        background: var(--danger-color);
        color: white;
    }
    
    /* Stats Section */
    .stats-card {
        background: var(--stats-card-bg);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 1.25rem;
    }
    
    .stats-card h6 {
        color: var(--text-secondary);
        font-size: 0.75rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
    
    .stats-card h3 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: var(--text-primary);
    }
    
    .stats-card .badge {
        font-weight: 500;
        font-size: 0.7rem;
    }
    
    /* Section Headers */
    .section-header {
        margin-top: 2rem;
        margin-bottom: 1rem;
        border-left: 4px solid var(--primary-color);
        padding-left: 1rem;
    }
    
    .section-header h4 {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }
    
    .section-header p {
        color: var(--text-muted);
        font-size: 0.8rem;
        margin: 0;
    }
    
    /* Queue Widget */
    .queue-widget {
        background: var(--stats-card-bg);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .queue-widget h5 {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.75rem;
    }
    
    .queue-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem;
        border-radius: 8px;
        background: var(--tile-bg);
        margin-bottom: 0.5rem;
    }
    
    .queue-item span {
        font-size: 0.8rem;
        color: var(--text-secondary);
    }
    
    .queue-count {
        background: var(--primary-color);
        color: white;
        padding: 0.25rem 0.6rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.75rem;
    }
    
    /* Queue List Panel */
    .queue-list-panel {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        overflow: hidden;
        height: fit-content;
        position: sticky;
        top: 80px;
    }
    
    .queue-list-header {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        padding: 1rem 1.25rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .queue-list-header h5 {
        font-size: 0.95rem;
        font-weight: 600;
        color: white;
        margin: 0;
    }
    
    .queue-count-badge {
        background: rgba(255, 255, 255, 0.3);
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .queue-list-body {
        max-height: 600px;
        overflow-y: auto;
        padding: 0.75rem;
    }
    
    .queue-patient-item {
        background: var(--tile-bg);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 0.85rem;
        margin-bottom: 0.75rem;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .queue-patient-item:hover {
        background: var(--tile-hover-bg);
        transform: translateX(5px);
        box-shadow: 0 4px 12px var(--shadow-color);
    }
    
    .queue-patient-item:last-child {
        margin-bottom: 0;
    }
    
    /* Priority borders */
    .queue-patient-item.priority-critical {
        border-left: 4px solid var(--danger-color);
    }
    
    .queue-patient-item.priority-moderate {
        border-left: 4px solid var(--warning-color);
    }
    
    .queue-patient-item.priority-mild {
        border-left: 4px solid var(--success-color);
    }
    
    .patient-info {
        margin-bottom: 0.5rem;
    }
    
    .patient-id {
        font-size: 0.7rem;
        color: var(--text-muted);
        font-weight: 600;
        margin-bottom: 0.15rem;
    }
    
    .patient-name {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }
    
    .patient-complaint {
        font-size: 0.75rem;
        color: var(--text-secondary);
        line-height: 1.3;
    }
    
    .patient-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    .priority-badge {
        padding: 0.25rem 0.6rem;
        border-radius: 6px;
        font-size: 0.65rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
    }
    
    .priority-badge.critical {
        background: rgba(239, 68, 68, 0.15);
        color: var(--danger-color);
    }
    
    .priority-badge.moderate {
        background: rgba(245, 158, 11, 0.15);
        color: var(--warning-color);
    }
    
    .priority-badge.mild {
        background: rgba(16, 185, 129, 0.15);
        color: var(--success-color);
    }
    
    .wait-time {
        font-size: 0.7rem;
        color: var(--text-muted);
        font-weight: 500;
    }
    
    .queue-list-footer {
        padding: 0.75rem;
        border-top: 1px solid var(--border-color);
    }
    
    .queue-list-footer .btn {
        font-size: 0.8rem;
        padding: 0.5rem;
        border-color: var(--border-color);
        color: var(--text-primary);
    }
    
    .queue-list-footer .btn:hover {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }
    
    /* Scrollbar styling for queue list */
    .queue-list-body::-webkit-scrollbar {
        width: 6px;
    }
    
    .queue-list-body::-webkit-scrollbar-track {
        background: var(--tile-bg);
        border-radius: 10px;
    }
    
    .queue-list-body::-webkit-scrollbar-thumb {
        background: var(--border-color);
        border-radius: 10px;
    }
    
    .queue-list-body::-webkit-scrollbar-thumb:hover {
        background: var(--primary-color);
    }
    
    /* =====================================================
       GLOBAL TABLE STYLING FOR DARK MODE - ALL TABLES 
       ===================================================== */
    .table {
        color: var(--text-primary);
    }
    
    .table thead tr {
        border-bottom: 2px solid var(--border-color);
    }
    
    .table thead th {
        color: var(--text-primary) !important;
        font-weight: 600;
        background: transparent;
        border: none;
        border-bottom: 2px solid var(--border-color);
    }
    
    .table tbody tr {
        border-bottom: 1px solid var(--border-color);
    }
    
    .table tbody tr:hover {
        background: var(--hover-bg);
    }
    
    .table tbody td {
        color: var(--text-primary) !important;
        border: none;
        border-bottom: 1px solid var(--border-color);
        vertical-align: middle;
    }
    
    .table .text-secondary {
        color: var(--text-secondary) !important;
    }
    
    .table .text-muted {
        color: var(--text-muted) !important;
    }
    
    .table .empty-state {
        border: none !important;
    }
    
    .table .empty-icon {
        font-size: 3rem;
        color: var(--text-muted);
    }
    
    .table .empty-text {
        color: var(--text-muted);
        margin-top: 1rem;
        margin-bottom: 1rem;
    }
    
    /* Table Hover State */
    .table-hover tbody tr:hover {
        background-color: var(--hover-bg) !important;
    }
    
    /* Responsive Tables */
    .table-responsive {
        background: transparent;
    }
    
    /* =====================================================
       CARD STYLING FOR DARK MODE 
       ===================================================== */
    .card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        color: var(--text-primary);
    }
    
    .card-header {
        background: var(--tile-bg);
        border-bottom: 1px solid var(--border-color);
        color: var(--text-primary);
    }
    
    .card-title {
        color: var(--text-primary);
        margin-bottom: 0;
    }
    
    .card-body {
        background: var(--card-bg);
        color: var(--text-primary);
    }
    
    /* =====================================================
       FORM ELEMENTS FOR DARK MODE 
       ===================================================== */
    .form-control,
    .form-select {
        background: var(--card-bg) !important;
        border: 1px solid var(--border-color) !important;
        color: var(--text-primary) !important;
    }
    
    .form-control:focus,
    .form-select:focus {
        background: var(--card-bg) !important;
        border-color: var(--primary-color) !important;
        color: var(--text-primary) !important;
        box-shadow: 0 0 0 0.2rem rgba(79, 70, 229, 0.25);
    }
    
    .form-control::placeholder {
        color: var(--text-muted) !important;
        opacity: 1;
    }
    
    .form-control:disabled,
    .form-select:disabled {
        background: var(--hover-bg) !important;
        color: var(--text-muted) !important;
    }
    
    /* Form Labels */
    .form-label,
    label {
        color: var(--text-primary) !important;
        font-weight: 500;
    }
    
    /* Select Dropdown */
    .form-select option {
        background: var(--card-bg);
        color: var(--text-primary);
    }
    
    /* Textarea */
    textarea.form-control {
        background: var(--card-bg) !important;
        border: 1px solid var(--border-color) !important;
        color: var(--text-primary) !important;
    }
    
    /* Input Group */
    .input-group .btn {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        color: var(--text-primary);
    }
    
    .input-group .btn:hover {
        background: var(--hover-bg);
        border-color: var(--primary-color);
        color: var(--text-primary);
    }
    
    /* File Input */
    .form-control[type="file"] {
        background: var(--card-bg) !important;
        border: 1px solid var(--border-color) !important;
        color: var(--text-primary) !important;
    }
    
    .form-control[type="file"]::file-selector-button {
        background: var(--tile-bg);
        border: 1px solid var(--border-color);
        color: var(--text-primary);
        padding: 0.375rem 0.75rem;
        margin-right: 0.75rem;
        border-radius: 0.25rem;
    }
    
    .form-control[type="file"]::file-selector-button:hover {
        background: var(--hover-bg);
    }
    
    /* Button Outline Variants for Dark Mode */
    .btn-outline-secondary {
        color: var(--text-primary) !important;
        border-color: var(--border-color) !important;
    }
    
    .btn-outline-secondary:hover {
        background: var(--primary-color) !important;
        border-color: var(--primary-color) !important;
        color: white !important;
    }
    
    /* Date Input */
    .form-control[type="date"] {
        background: var(--card-bg) !important;
        border: 1px solid var(--border-color) !important;
        color: var(--text-primary) !important;
    }
    
    .form-control[type="date"]::-webkit-calendar-picker-indicator {
        filter: invert(0.8);
    }
    
    /* Number Input */
    .form-control[type="number"] {
        background: var(--card-bg) !important;
        border: 1px solid var(--border-color) !important;
        color: var(--text-primary) !important;
    }
    
    /* Breadcrumb Styling */
    .breadcrumb {
        background: transparent;
    }
    
    .breadcrumb-item a {
        color: var(--text-secondary);
        text-decoration: none;
    }
    
    .breadcrumb-item a:hover {
        color: var(--primary-color);
    }
    
    .breadcrumb-item.active {
        color: var(--text-primary);
    }
    
    .breadcrumb-item + .breadcrumb-item::before {
        color: var(--text-muted);
    }
    
    /* Buttons */
    .btn-secondary {
        background: var(--tile-bg) !important;
        border-color: var(--border-color) !important;
        color: var(--text-primary) !important;
    }
    
    .btn-secondary:hover {
        background: var(--hover-bg) !important;
        border-color: var(--border-color) !important;
        color: var(--text-primary) !important;
    }
    
    /* Error Messages */
    .text-danger {
        color: var(--danger-color) !important;
    }
    
    /* Small Text */
    small, .small {
        color: var(--text-secondary);
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .search-box {
            max-width: 100%;
            margin-bottom: 1rem;
        }
        
        .page-header h1 {
            font-size: 1.25rem;
        }
        
        .dashboard-tile {
            min-height: 130px;
        }
        
        .tile-title {
            font-size: 0.9rem;
        }
    }

    /* Fix for empty table state background */
    /* Comprehensive Table Background Fix */
    .table,
    .table > :not(caption) > * > * {
        background-color: transparent !important;
    }

    .table tbody,
    .table thead,
    .table tfoot {
        background-color: transparent !important;
    }

    .table > tbody {
        background-color: transparent !important;
    }

    /* Stats Card Table Container */
    .stats-card .table-responsive,
    .stats-card .table {
        background: transparent !important;
    }

    /* Table Rows */
    .table tbody tr {
        background: transparent !important;
    }

    .table tbody tr:hover {
        background: var(--hover-bg) !important;
    }

    /* Empty State Specific */
    .table tbody tr.empty-state,
    .table tbody tr .empty-state {
        background: transparent !important;
    }

    .table tbody tr td.empty-state {
        background: transparent !important;
    }
</style>


</head>
<body>
    
    @include('admin.partials.navbar')

    <!-- Main Content -->
    <main class="container-fluid py-4">
        <div class="container">
            @yield('admin')
        </div>
    </main>
    
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery (if needed) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    @stack('scripts')
    
    <!-- Custom JavaScript -->
    <script>
        // Theme Toggle Functionality
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const html = document.documentElement;

        // Get saved theme from localStorage or default to 'dark'
        const savedTheme = localStorage.getItem('theme') || 'dark';
        html.setAttribute('data-theme', savedTheme);
        updateThemeIcon(savedTheme);

        // Theme toggle button click handler
        themeToggle.addEventListener('click', function() {
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcon(newTheme);
            
            showToast(`Switched to ${newTheme} mode`, 'success');
        });

        // Update theme icon based on current theme
        function updateThemeIcon(theme) {
            if (theme === 'dark') {
                themeIcon.classList.remove('bi-moon-fill');
                themeIcon.classList.add('bi-sun-fill');
                themeToggle.setAttribute('title', 'Switch to Light Mode');
            } else {
                themeIcon.classList.remove('bi-sun-fill');
                themeIcon.classList.add('bi-moon-fill');
                themeToggle.setAttribute('title', 'Switch to Dark Mode');
            }
        }

        // Toast notification function
        function showToast(message, type = 'info') {
            const toastContainer = document.getElementById('toastContainer') || createToastContainer();
            
            const toast = document.createElement('div');
            toast.className = `toast align-items-center text-white bg-${type} border-0`;
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'assertive');
            toast.setAttribute('aria-atomic', 'true');
            
            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            `;
            
            toastContainer.appendChild(toast);
            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();
            
            toast.addEventListener('hidden.bs.toast', () => toast.remove());
        }
        
        function createToastContainer() {
            const container = document.createElement('div');
            container.id = 'toastContainer';
            container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
            document.body.appendChild(container);
            return container;
        }
        
        // Search functionality
        const searchInput = document.querySelector('.search-box input');
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                document.querySelectorAll('.dashboard-tile').forEach(tile => {
                    const title = tile.querySelector('.tile-title')?.textContent.toLowerCase() || '';
                    const description = tile.querySelector('.tile-description')?.textContent.toLowerCase() || '';
                    
                    if (title.includes(searchTerm) || description.includes(searchTerm)) {
                        tile.closest('.col-6, .col-md-4, .col-lg-3, .col-xl-2')?.style.setProperty('display', '');
                    } else {
                        tile.closest('.col-6, .col-md-4, .col-lg-3, .col-xl-2')?.style.setProperty('display', 'none');
                    }
                });

                // Also search section headers and hide empty sections
                document.querySelectorAll('.section-header').forEach(header => {
                    const nextRow = header.nextElementSibling;
                    
                    if (nextRow && nextRow.classList.contains('row')) {
                        const visibleTiles = nextRow.querySelectorAll('.col-6:not([style*="display: none"])').length;
                        
                        if (visibleTiles === 0 && searchTerm !== '') {
                            header.style.display = 'none';
                            nextRow.style.display = 'none';
                        } else {
                            header.style.display = '';
                            nextRow.style.display = '';
                        }
                    }
                });
            });
        }

        // Display Laravel session messages
        @if(session('message'))
            showToast('{{ session('message') }}', '{{ session('alert-type', 'info') }}');
        @endif
    </script>
</body>
</html>