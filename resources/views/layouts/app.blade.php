<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Default Title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <!-- jQuery (ต้องใช้กับ DataTables) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>
<style>
    @font-face {
        font-family: 'Prompt';
        src: url('/fonts/Prompt-Bold.ttf') format('woff2');
        font-weight: normal;
    }

    body {
        font-family: 'Prompt', sans-serif;
        font-size: 20px;
        /* สำหรับเว้นที่ให้ bottom navbar */
    }

    .navbar {
        background-color: #ededed;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    }

    .btn-register {
        background: linear-gradient(to right, #91d5ff, #1186fc);
        color: rgb(0, 0, 0);
        text-decoration: none;
        border: none;
        font-size: 23px;
        padding: 0.5rem 1.2rem;
        border-radius: 30px;
        font-weight: bold;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(17, 134, 252, 0.3);
    }

    .btn-register:hover {
        transform: translateY(-2px) scale(1.03);
        box-shadow: 0 6px 16px rgba(17, 134, 252, 0.45);
        filter: brightness(1.05);
        color: #ffffff;
    }

    .btn-login {
        background: linear-gradient(to right, #ffffff, #ffffff);
        color: rgb(0, 0, 0);
        text-decoration: none;
        border: none;
        font-size: 23px;
        padding: 0.5rem 1.2rem;
        border-radius: 30px;
        font-weight: bold;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(36, 36, 36, 0.3);
    }

    .btn-login:hover {
        transform: translateY(-2px) scale(1.03);
        box-shadow: 0 6px 16px rgba(252, 252, 252, 0.45);
        filter: brightness(1.05);
        color: #1186fc;
    }

    .bottom-nav {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background-color: #ededed;
        display: flex;
        justify-content: space-around;
        align-items: center;
        padding: 10px 0;
        z-index: 1000;
    }

    .bottom-nav a {
        color: rgb(0, 0, 0);
        text-align: center;
        font-size: 20px;
        text-decoration: none;
        transition: all 0.3s ease;
        padding: 5px 10px;
        border-radius: 0.5rem;
    }

    .bottom-nav a:hover {
        background-color: rgba(255, 255, 255, 0.15);
        transform: scale(1.05);
        color: #1186fc;
    }

    .bottom-nav a i {
        display: block;
        font-size: 20px;
    }

    .profile-shadow {
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
        /* X, Y, blur, color */
        transition: all 0.3s ease;
        border-radius: 0.5rem;
    }

    .profile-shadow:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
    }

    /* Default (Desktop and larger) */
    .logo-img {
        width: 8rem;
    }

    .project-title {
        font-size: 45px;
        font-weight: bold;
    }

    .project-subtitle {
        font-size: 1.25rem;
        /* fs-5 */
        margin-top: -1rem;
    }

    /* Responsive for screen <= 500px */
    @media (max-width: 500px) {
        .logo-img {
            width: 6rem;
        }

        .project-title {
            font-size: 34px;
        }

        .project-subtitle {
            font-size: 1rem;
            margin-top: -0.5rem;
        }
    }

    ::-webkit-scrollbar {
        width: 10px;
    }

    ::-webkit-scrollbar-track {
        background-color: #c0e7ff;
    }

    ::-webkit-scrollbar-thumb {
        background-color: white;
        border-radius: 10px;
        border: 2px solid transparent;
        background-clip: content-box;
    }

    /* Optional: on hover */
    ::-webkit-scrollbar-thumb:hover {
        background-color: #f0f0f0;
    }

</style>

<body>

    @if ($message = Session::get('success'))
    <script>
        Swal.fire({
            icon: 'success'
            , title: '{{ $message }}'
        , });

    </script>
    @endif

    {{-- Top Navbar: แสดงเฉพาะบน desktop --}}
    <nav class="navbar navbar-expand-lg navbar-dark  p-0">
        <div class="container">
            <a class="navbar-brand text-dark d-flex  align-items-center" href="#">
                <img src="{{ asset('navbar/Logo.png') }}" alt="Logo" class="logo-img me-2">
                <div class="d-flex flex-column">
                    <div class="project-title">โปรเจ็คติวเตอร์</div>
                    <div class="project-subtitle">ระบบจองรายคอร์สเรียนพิเศษ</div>
                </div>

            </a>
            <div class="collapse navbar-collapse justify-content-end d-none d-lg-block">
                <ul class="navbar-nav align-items-center">

                    @guest
                    <li class="nav-item me-2">
                        <a class="btn-register" href="{{route('RegisterPage')}}">สมัครสมาชิก</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn-login" href="{{route('LoginPage')}}">เข้าสู่ระบบ</a>
                    </li>
                    @endguest
                    @auth
                    <li class="nav-item dropdown ">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                            <img src="{{ asset('navbar/user-unknow.png') }}" alt="avatar" class="rounded-circle me-2 bg-white profile-shadow" style="width: 5rem;">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#">โปรไฟล์</a></li>
                            <li><a class="dropdown-item" href="{{route('UsersAccount')}}">ประวัติการจองคอร์ส</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form method="POST" action="{{route('logout')}}">
                                    @csrf
                                    <button class="dropdown-item" type="submit">ออกจากระบบ</button>
                                </form>
                            </li>
                        </ul>

                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    {{-- Bottom Navbar: แสดงเฉพาะบนมือถือ --}}
    <div class="bottom-nav d-lg-none">
        <a href="#">
            <i class="fas fa-home"></i>
            หน้าหลัก
        </a>
        <div class="dropdown-center " style="margin-top: -4rem">
            <a class="nav-link d-flex align-items-center " href="#" role="button" data-bs-toggle="dropdown">
                <img src="{{ asset('navbar/user-unknow.png') }}" alt="avatar" class="rounded-circle me-2 bg-white profile-shadow" style="width: 6.5rem;">
            </a>
            <ul class="dropdown-menu dropdown-menu-center ">
                @auth
                <li><a class="dropdown-item" href="{{route('UsersAccount')}}">โปรไฟล์ของฉัน</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <form method="POST" action="{{route('logout')}}">
                        @csrf
                        <button class="dropdown-item" type="submit">ออกจากระบบ</button>
                    </form>
                </li>
                @endauth

                @guest
                <li><a class="dropdown-item text-dark" href="{{route('RegisterPage')}}">สมัครสมาชิก</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item text-dark" href="{{route('LoginPage')}}">เข้าสู่ระบบ</a></li>
                @endguest
            </ul>
        </div>
        <a href="#">
            <i class="fas fa-book-open"></i>
            คอร์ส
        </a>

    </div>
    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
