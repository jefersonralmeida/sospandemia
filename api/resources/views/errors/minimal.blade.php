<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title')</title>

    <!-- Scripts -->
    <script src="{{ asset('auth/js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('auth/css/app.css') }}" rel="stylesheet">
    <style>
        .error-code {
            color: #DDD;
        }
        .error-desc {
            text-align: center;
            color: #999;
        }
    </style>

</head>
<body>
<div>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <a class="navbar-brand p-0" href="{{ uiRoute() }}">
                            <img src="{{ asset('auth/logo.png') }}" height="50" alt="">
                        </a>
                    </div>

                    <div class="card-body compact-body">
                        <div>
                            <h3 style="text-align: center;">
                                <span class="error-code">@yield('code')</span> Ops! @yield('title')
                            </h3>
                            <hr/>
                            <p class="error-desc m-4">
                                @yield('message')
                            </p>
                        </div>
                    </div>

                    <div class="card-footer">
                        <a class="nav-link p-0" href="{{ uiRoute() }}">
                            Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
