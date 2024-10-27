<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Watch Later') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    
    <!-- LazySizes Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js" async=""></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    <style>
        body {
            background-color: #121212; /* Dark background */
            color: #ffffff; /* White text */
        }

        .dropdown-item {
            color: #000000;
        }

        .dropdown-item:hover {
            background-color: #FFD700; /* Optional: Change background color on hover */
            color: #000000; /* Optional: Change text color on hover for visibility */
        }

        .navbar {
            background-color: #000; /* Black navbar */
        }

        .navbar-brand, .nav-link {
            color: #FFD700; /* Yellow text */
        }

        .nav-link:hover {
            color: #FFC107; /* Lighter yellow on hover */
        }

        .container {
            margin-top: 50px;
            background-color: #1e1e1e; /* Dark card background */
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        .btn-primary {
            background-color: #FFD700; /* Yellow button */
            border: none;
        }

        .btn-primary:hover {
            background-color: #FFC107; /* Lighter yellow on hover */
        }

        .alert {
            background-color: #FF5733; /* Red alert background */
            color: #ffffff; /* White text */
        }

        /* Optional: Styling for loading placeholder images */
        .lazyload {
            opacity: 0; /* Hide until loaded */
            transition: opacity 0.5s ease-in; /* Fade in effect */
        }

        .lazyloaded {
            opacity: 1; /* Show image when loaded */
        }

        .modal-content {
            background-color: #333; /* Background color to match card style */
            color: #fff; /* Text color */
        }

        .modal-header {
            border-bottom: 1px solid #444; /* Style for header */
        }

        .modal-footer {
            border-top: 1px solid #444; /* Style for footer */
        }

        .modal-custom .modal-content {
            box-shadow: 0 4px 20px rgba(255, 215, 0, 0.8); /* Yellow shadow */
            border: none; /* Remove border if needed */
        }

        /* Optional: Change the background color of the modal */
        .modal-custom .modal-content {
            background-color: #1c1c1c; /* Adjust as needed for your design */
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name', 'Watch Later') }}" style="height: 40px; margin-right: 8px;">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        <!-- Add any left-side navigation items here -->
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle text-white" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('movies.favorites') }}">
                                    My Favorites
                                </a>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
