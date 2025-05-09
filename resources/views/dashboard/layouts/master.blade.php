<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Dashboard')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <!-- Page CSS -->
    @stack('styles') {{-- เผื่อมี CSS เพิ่มเฉพาะหน้า --}}

    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>

    <!-- Config -->
    <script src="{{ asset('assets/js/config.js') }}"></script>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <!-- jQuery (ต้องใช้กับ DataTables) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- sweetalert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="layout-menu-fixed">

    @if ($message = Session::get('success'))
    <script>
        Swal.fire({
            icon: 'success'
            , title: '{{ $message }}'
        , });

    </script>
    @endif

    @if ($message = Session::get('error'))
    <script>
        Swal.fire({
            icon: 'error'
            , title: '{{ $message }}'
        , });

    </script>
    @endif

    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            @include('dashboard.layouts.partials.sidebar')
            <!-- เมนูซ้าย -->

            <div class="layout-page">
                @include('dashboard.layouts.partials.header')
                <!-- Navbar -->

                <div class="content-wrapper">
                    <div class="container-xxl container-p-y">
                        @yield('content')
                    </div>

                    @include('dashboard.layouts.partials.footer')
                    <!-- Footer -->
                </div>
            </div>
        </div>
    </div>

    @include('dashboard.layouts.partials.scripts')
    <!-- JS -->
</body>
</html>
