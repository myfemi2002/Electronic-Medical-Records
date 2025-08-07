@php
    $assetBase = asset('backend/assets');
@endphp

<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <title>@yield('title', 'Dashboard') | Dusty - Responsive Admin Dashboard Template</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
        <meta name="author" content="Zoyothemes">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ $assetBase }}/images/favicon.ico">

        <!-- App css -->
        <link href="{{ $assetBase }}/css/app.min.css" rel="stylesheet" type="text/css" id="app-style">

        <!-- Icons -->
        <link href="{{ $assetBase }}/css/icons.min.css" rel="stylesheet" type="text/css">

        <link rel="stylesheet" href="{{ asset('backend/assets/Font-Awesome/css/all.css') }}">
        
        <!-- Additional CSS -->
        <link rel="stylesheet" type="text/css" href="{{ asset('backend/assets/toaster/toastr.css') }}"> 
        <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css"> -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />

        
        <!-- Trix CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/trix@2.1.12/dist/trix.min.css">

        <!-- Custom CSS from child templates -->
        @stack('css')

        <script src="{{ $assetBase }}/js/head.js"></script>

    </head>

    <!-- body start -->
    <body data-menu-color="dark" data-sidebar="default">

    <!-- Begin page -->
    <div id="app-layout">

        <!-- Topbar Start -->
         @include('admin.body.topbar')
        <!-- end Topbar -->

        <!-- Left Sidebar Start -->
        <div class="app-sidebar-menu">
            <div class="h-100" data-simplebar="">

                <!--- Sidemenu -->
                @include('admin.body.sidebar')
                <!-- End Sidebar -->
         
                <div class="clearfix"></div>

            </div>
        </div>
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">
            <div class="content">

                <!-- Start Content-->
                 @yield('admin')
                <!-- container-fluid -->
            </div>
            <!-- content -->

            <!-- Footer Start -->
            <footer class="footer">
                @include('admin.body.footer')
            </footer>
            <!-- end Footer -->

        </div>
        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>
    <!-- END wrapper -->
  
    <!-- Vendor Scripts (keeping existing jQuery to avoid conflicts) -->
    <script src="{{ $assetBase }}/libs/jquery/jquery.min.js"></script>
    <script src="{{ $assetBase }}/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{ $assetBase }}/libs/iconify-icon/iconify-icon.min.js"></script>
    <script src="{{ $assetBase }}/libs/simplebar/simplebar.min.js"></script>
    <script src="{{ $assetBase }}/libs/node-waves/waves.min.js"></script>
    <script src="{{ $assetBase }}/libs/waypoints/lib/jquery.waypoints.min.js"></script>
    <script src="{{ $assetBase }}/libs/jquery.counterup/jquery.counterup.min.js"></script>
    <script src="{{ $assetBase }}/libs/feather-icons/feather.min.js"></script>

    <!-- COMMENTED OUT ApexCharts to prevent conflicts with Google Charts -->
    {{-- <script src="{{ $assetBase }}/libs/apexcharts/apexcharts.min.js"></script> --}}

    <!-- Vector map-->
    <script src="{{ $assetBase }}/libs/jsvectormap/js/jsvectormap.min.js"></script>
    <script src="{{ $assetBase }}/libs/jsvectormap/maps/world-merc.js"></script>

    <!-- Widgets Init Js -->
    {{-- <script src="{{ $assetBase }}/js/pages/crm-dashboard.init.js"></script> --}}

    <!-- App js-->
    <script src="{{ $assetBase }}/js/app.js"></script>

    <!-- Additional Scripts -->
    <script src="{{ asset('backend/assets/validation/validate.min.js') }}"></script>
    <script src="{{ asset('backend/assets/handlebars/handlebars.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/notify/0.4.2/notify.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('backend/assets/sweetalert-code/code.js') }}"></script>
    
    <!-- Toastr -->
    <script src="{{ asset('backend/assets/toaster/toastr.min.js') }}"></script>
    
    <!-- Trix Editor -->
    <script src="https://cdn.jsdelivr.net/npm/trix@2.1.12/dist/trix.umd.min.js"></script>

    <!-- Notifications Script -->
    <script>
        @if(Session::has('message'))
        var type = "{{ Session::get('alert-type', 'info') }}";
        switch(type) {
            case 'info':
                toastr.info("{{ Session::get('message') }}");
                break;
            case 'success':
                toastr.success("{{ Session::get('message') }}");
                break;
            case 'warning':
                toastr.warning("{{ Session::get('message') }}");
                break;
            case 'error':
                toastr.error("{{ Session::get('message') }}");
                break;
        }
        @endif
    </script>

    <!-- Custom JS from child templates -->
    @stack('js')

    </body>

</html>