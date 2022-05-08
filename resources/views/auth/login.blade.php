<!doctype html>
<html lang="en-US" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Wilo Pump - Login</title>
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/') }}img/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/') }}img/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/') }}img/favicons/favicon-16x16.png">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('/') }}img/favicons/favicon.ico">
    <link rel="manifest" href="{{ asset('/') }}img/favicons/manifest.json">
    <meta name="msapplication-TileImage" content="{{ asset('/') }}img/favicons/mstile-150x150.png">
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
            <div class="container">
                <div class="row flex-center min-vh-100 py-5">
                    <div class="col-sm-10 col-md-8 col-lg-5 col-xl-5 col-xxl-3"><a class="d-flex flex-center text-decoration-none mb-4" href="/">
                            <div class="d-flex align-items-center"><img src="{{ asset('/') }}img/wilo.png" alt="wilo" width="58"></div>
                        </a>
                        <div class="text-center mb-7">
                            <h3>Login</h3>
                            <p class="text-700">Get access to your account</p>
                        </div>

                        <div class="position-relative mt-4">
                            <hr class="bg-200">
                            <div class="divider-content-center">Use email & password</div>
                        </div>
                        <form action="{{ route('login') }}" method="post">
                            @csrf
                            <div class="mb-3 text-start">
                                <label class="form-label" for="email">Email address</label>
                                <div class="form-icon-container">
                                    <input class="form-control form-icon-input" id="email" name="email" type="email" placeholder="name@example.com" value="{{ old('email') }}">
                                    <span class="fas fa-user text-900 fs--1 form-icon"></span>
                                </div>

                                @error('email')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-3 text-start">
                                <label class="form-label" for="password">Password</label>
                                <div class="form-icon-container"><input class="form-control form-icon-input" type="password" name="password" placeholder="Password"><span class="fas fa-user text-900 fs--1 form-icon"></span>
                                </div>
                            </div>
                            <div class="row flex-between-center mb-7">
                            </div>
                            <button class="btn btn-primary w-100 mb-3" type="submit">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="{{ asset('/') }}js/phoenix.js"></script>
</body>

</html>