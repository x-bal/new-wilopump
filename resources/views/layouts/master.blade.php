<!doctype html>
<html lang="en-US" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Wilo Pump - {{ $title }}</title>
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/') }}img/favicons/apple-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/') }}img/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/') }}img/favicons/favicon-16x16.png">
    <!-- <link rel="shortcut icon" type="image/x-icon" href="{{ asset('/') }}img/favicons/favicon.ico"> -->
    <link rel="manifest" href="{{ asset('/') }}img/favicons/manifest.json">
    <!-- <meta name="msapplication-TileImage" content="{{ asset('/') }}img/favicons/mstile-150x150.png"> -->
    <meta name="theme-color" content="#ffffff">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&amp;display=swap" rel="stylesheet">
    <link href="{{ asset('/') }}css/phoenix.min.css" rel="stylesheet" id="style-default">
    <link href="{{ asset('/') }}css/user.min.css" rel="stylesheet" id="user-style-default">
    <style>
        body {
            opacity: 0;
        }
    </style>
</head>

<body>
    <main class="main" id="top">
        <div class="container-fluid px-0">
            <nav class="navbar navbar-light navbar-vertical navbar-vibrant navbar-expand-lg">
                <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
                    <div class="navbar-vertical-content scrollbar">
                        <ul class="navbar-nav flex-column" id="navbarVerticalNav">
                            <li class="nav-item"><a class="nav-link active" href="/dashboard">
                                    <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="cast"></span></span><span class="nav-link-text">Dashbboard</span></div>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link dropdown-indicator" href="#master" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="master">
                                    <div class="d-flex align-items-center">
                                        <div class="dropdown-indicator-icon d-flex flex-center"><span class="fas fa-caret-right fs-0"></span></div><span class="nav-link-icon"><span data-feather="file-text"></span></span><span class="nav-link-text">Data Master</span>
                                    </div>
                                </a>
                                <ul class="nav collapse parent" id="master">
                                    <li class="nav-item">
                                        <a class="nav-link" href="" data-bs-toggle="" aria-expanded="false">
                                            <div class="d-flex align-items-center">
                                                <span class="nav-link-text">Data User</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="" data-bs-toggle="" aria-expanded="false">
                                            <div class="d-flex align-items-center">
                                                <span class="nav-link-text">Data Device</span>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <nav class="navbar navbar-light navbar-top navbar-expand">
                <div class="navbar-logo"><button class="btn navbar-toggler navbar-toggler-humburger-icon" type="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalCollapse" aria-controls="navbarVerticalCollapse" aria-expanded="false" aria-label="Toggle Navigation"><span class="navbar-toggle-icon"><span class="toggle-line"></span></span></button> <a class="navbar-brand me-1 me-sm-3" href="/dashboard">
                        <div class="d-flex align-items-center">
                            <div class="d-flex align-items-center"><img src="{{ asset('/') }}img/wilologo.png" alt=" wilo" width="70">
                                <!-- <p class="logo-text ms-2 d-none d-sm-block">Wilo Pump</p> -->
                            </div>
                        </div>
                    </a></div>
                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav navbar-nav-icons ms-auto flex-row">
                        <li class="nav-item dropdown"><a class="nav-link lh-1 px-0 ms-5" id="navbarDropdownUser" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <div class="avatar avatar-l"><img class="rounded-circle" src="{{ asset('/') }}img/team/57.png" alt=""></div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end py-0 dropdown-profile shadow border border-300" aria-labelledby="navbarDropdownUser">
                                <div class="card bg-white position-relative border-0">
                                    <div class="card-body p-0 overflow-auto scrollbar" style="height: 18rem;">
                                        <div class="text-center pt-4 pb-3">
                                            <div class="avatar avatar-xl"><img class="rounded-circle" src="{{ asset('/') }}img/team/57.png" alt=""></div>
                                            <h6 class="mt-2">{{ auth()->user()->name }}</h6>
                                        </div>

                                        <ul class="nav d-flex flex-column mb-2">
                                            <li class="nav-item"><a class="nav-link px-3" href="#!"><span class="me-2 text-900" data-feather="user"></span>Profile</a>
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
            <div class="content">
                @yield('content')

                <footer class="footer">
                    <div class="row g-0 justify-content-between align-items-center h-100 mb-3">
                        <div class="col-12 col-sm-auto text-center">
                            <p class="mb-0 text-900">Thank you for creating with phoenix<span class="d-none d-sm-inline-block"></span><span class="mx-1">|</span><br class="d-sm-none">2022 &copy; <a href="https://themewagon.com">Themewagon</a></p>
                        </div>
                        <div class="col-12 col-sm-auto text-center">
                            <p class="mb-0 text-600">v1.0.1</p>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </main>
    <script src="{{ asset('/') }}js/phoenix.js"></script>
</body>

</html>