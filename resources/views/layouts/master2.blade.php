<!doctype html>
<html lang="en-US" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Wilo Pump - {{ $title }}</title>

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/') }}img/icons/apple-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/') }}img/icons/android-chrome-512x512.png">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/') }}img/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/') }}img/icons/favicon-16x16.png">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('/') }}img/icons/favicon.ico">
    <!-- <link rel="manifest" href="{{ asset('/') }}img/icons/manifest.json"> -->
    <!-- <meta name="msapplication-TileImage" content="{{ asset('/') }}img/icons/mstile-150x150.png"> -->
    <meta name="theme-color" content="#ffffff">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&amp;display=swap" rel="stylesheet">
    <link href="{{ asset('/') }}css/phoenix.min.css" rel="stylesheet" id="style-default">
    <link href="{{ asset('/') }}css/user.min.css" rel="stylesheet" id="user-style-default">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css" integrity="sha512-O03ntXoVqaGUTAeAmvQ2YSzkCvclZEcPQu1eqloPaHfJ5RuNGiS4l+3duaidD801P50J28EHyonCV06CUlTSag==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            opacity: 0;
        }
    </style>

    @stack('style')
</head>

<body>
    <main class="main" id="top">
        <div class="container-fluid px-0">
            @if(request()->is('slider') && request('q') == 'full')

            @else
            <!-- Sidebar -->
            <nav class="navbar navbar-light navbar-vertical navbar-vibrant navbar-expand-lg">

                <div class="collapse navbar-collapse" id="navbarHorizontalCollapse">
                    <div class="navbar-vertical-content scrollbar">
                        <ul class="navbar-nav flex-column hide" id="navbarVerticalNav">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="/dashboard">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-icon">
                                            <span data-feather="cast"></span>
                                        </span>
                                        <span class="nav-link-text nav-master">Dashbboard</span>
                                    </div>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('slider') ? 'active' : '' }}" href="/slider">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-icon">
                                            <span data-feather="clock"></span>
                                        </span>
                                        <span class="nav-link-text nav-master">Slider</span>
                                    </div>
                                </a>
                            </li>

                            @if(auth()->user()->level == 'Admin')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('user*') || request()->is('device*') ? 'active' : '' }} dropdown-indicator" href="#master" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="master">
                                    <div class="d-flex align-items-center">
                                        <div class="dropdown-indicator-icon d-flex flex-center"><span class="fas fa-caret-right fs-0"></span></div><span class="nav-link-icon"><span data-feather="file-text"></span></span><span class="nav-link-text nav-master">Data Master</span>
                                    </div>
                                </a>
                                <ul class="nav collapse parent {{ request()->is('user*') || request()->is('device*') ? 'show' : '' }}" id="master">
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('user.index') }}" data-bs-toggle="" aria-expanded="false">
                                            <div class="d-flex align-items-center">
                                                <span class="nav-link-text">Data User</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('device.index') }}" data-bs-toggle="" aria-expanded="false">
                                            <div class="d-flex align-items-center">
                                                <span class="nav-link-text">Data Device</span>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            @endif

                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('view-data') ? 'active' : '' }}" href="/view-data">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-icon">
                                            <i class="fas fa-chart-bar"></i>
                                        </span>
                                        <span class="nav-link-text nav-master">View Data</span>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('chart') ? 'active' : '' }}" href="/chart">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-icon">
                                            <i class="fas fa-chart-line"></i>
                                        </span>
                                        <span class="nav-link-text nav-master">Chart</span>
                                    </div>
                                </a>
                            </li>
                            @if(auth()->user()->level == 'Admin')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('access-viewer') ? 'active' : '' }}" href="/access-viewer">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-icon">
                                            <span data-feather="settings"></span>
                                        </span>
                                        <span class="nav-link-text nav-master">Access Viewer</span>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('setting') ? 'active' : '' }}" href="/setting">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-icon">
                                            <span data-feather="settings"></span>
                                        </span>
                                        <span class="nav-link-text nav-master">Setting</span>
                                    </div>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- End Sidebar -->

            <!-- Navbar -->
            <nav class="navbar navbar-light navbar-top navbar-expand">
                <div class="navbar-logo">
                    <a class="navbar-brand me-1 me-sm-3" href="/dashboard">
                        <div class="d-flex align-items-center">
                            <div class="d-flex align-items-center"><img src="{{ asset('/') }}img/wilologo.png" alt=" wilo" width="70">
                            </div>
                        </div>
                    </a>
                </div>
                <div class="collapse navbar-collapse">
                    <button class="btn btn-sm btn-outline-success" id="toggle-button" type="button">
                        <span class="nav-link-icon">
                            <span data-feather="align-justify"></span>
                        </span>
                    </button>
                    <ul class="navbar-nav navbar-nav-icons ms-auto flex-row">
                        <li class="nav-item dropdown"><a class="nav-link lh-1 px-0 ms-5" id="navbarDropdownUser" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <div class="avatar avatar-l"><img class="rounded-circle" src="{{ asset('storage/'. auth()->user()->image) }}" alt=""></div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end py-0 dropdown-profile shadow border border-300" aria-labelledby="navbarDropdownUser">
                                <div class="card bg-white position-relative border-0">
                                    <div class="card-body p-0 overflow-auto scrollbar" style="height: 18rem;">
                                        <div class="text-center pt-4 pb-3">
                                            <div class="avatar avatar-xl"><img class="rounded-circle" src="{{ asset('storage/'. auth()->user()->image) }}" alt=""></div>
                                            <h6 class="mt-2">{{ auth()->user()->name }}</h6>
                                        </div>

                                        <ul class="nav d-flex flex-column mb-2">
                                            <li class="nav-item"><a class="nav-link px-3" href="/profile"><span class="me-2 text-900" data-feather="user"></span>Profile</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link px-3" href="{{ route('logout') }}" onclick="event.preventDefault();            document.getElementById('logout-form').submit();"><span class="me-2" data-feather="log-out"></span>
                                                    Logout

                                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                                        @csrf
                                                    </form>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
            <!-- End Navbar -->
            @endif

            <div class="content">
                @yield('content')

                @if(request()->is('slider') && request('q') == 'full')

                @else
                <footer class="footer">
                    <div class="row g-0 justify-content-between align-items-center h-100 mb-3">
                        <div class="col-12 col-sm-auto text-center">
                            <p class="mb-0 text-900"><span class="d-none d-sm-inline-block"><br class="d-sm-none">2022 &copy; <a href="https://wilo.com/id/en/" target="__blank">PT Wilo Pumps Indonesia</a></p>
                        </div>
                        <div class="col-12 col-sm-auto text-center">
                            <p class="mb-0 text-600">v1.0.1</p>
                        </div>
                    </div>
                </footer>
                @endif
            </div>
        </div>
    </main>
    <script src="{{ asset('/') }}js/phoenix.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js" integrity="sha512-Zq9o+E00xhhR/7vJ49mxFNJ0KQw1E1TMWkPTxrWcnpfEFDEXgUiwJHIKit93EW/XxE31HSI5GEOW06G6BF1AtA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


    @if(session('success'))
    <script>
        iziToast.success({
            title: 'Success',
            position: 'topRight',
            message: '{{ session("success") }}',
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        iziToast.error({
            title: 'Error',
            position: 'topRight',
            message: '{{ session("error") }}',
        });
    </script>
    @endif


    <script>
        // localStorage.setItem('sidebar', 'expand')

        $("#toggle-button").on('click', function() {
            let sidebar = localStorage.getItem('sidebar')

            if (sidebar == 'expand') {
                localStorage.setItem("sidebar", 'close')

                $(".navbar-vibrant").css("width", "100px")
                $(".navbar-logo").css("width", "100px")
                $(".nav-master").css("display", "none")

            } else {
                localStorage.setItem("sidebar", 'expand')

                $(".navbar-vibrant").css("width", "")
                $(".navbar-logo").css("width", "")
                $(".nav-master").css("display", "")
            }
        })
    </script>

    @stack('script')
</body>

</html>