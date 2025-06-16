<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title> Aqua Monitor</title>

    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* Navbar Styling */
        .navbar {
            background: linear-gradient(90deg, #00A9A4, #007F73);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease;
        }

        .navbar-brand {
            font-size: 24px;
            font-weight: 600;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 10px;
            text-transform: uppercase;
            opacity: 0;
            animation: fadeInLogo 1s forwards;
        }

        .navbar-brand img {
            width: 30px;
            height: auto;
        }

        @keyframes fadeInLogo {
            0% { opacity: 0; transform: translateY(-10px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        .navbar-nav .nav-link {
            font-size: 16px;
            font-weight: 500;
            padding: 12px 20px;
            color: #fff;
            transition: all 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 5px;
            transform: scale(1.1);
        }

        .navbar-nav .nav-item.active .nav-link {
            font-weight: bold;
            color: #FFC107;
        }

        /* Navbar Toggler */
        .navbar-toggler {
            border: none;
            outline: none;
        }

        .navbar-toggler:focus {
            box-shadow: none;
        }

        .navbar-toggler-icon {
            background: none;
            font-size: 24px;
            color: white;
        }

        /* Background & Layout */
        body {
            background-color: #F4F9F9;
            color: #333;
        }

        .main-container {
            padding: 20px;
        }

        /* Card Styling */
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background: linear-gradient(90deg, #00A9A4, #007F73);
            color: #fff;
            font-weight: bold;
        }

        .btn-primary {
            background: #00A9A4;
            border-color: #00A9A4;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: #004D3B;
            border-color: #004D3B;
            transform: scale(1.05);
        }

        /* Footer */
        footer {
            background: linear-gradient(90deg, #00A9A4, #007F73);
            color: #fff;
            text-align: center;
            padding: 10px;
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/dashboard') }}">
                    {{-- <img src="{{ asset('assets/icon.png') }}?v={{ time() }}" alt="Logo" width="150" height="150"> --}}
                    Aqua Monitor
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/history') }}">History</a>
                        </li>
                        @guest
                            {{-- <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Login</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">Register</a>
                                </li>
                            @endif --}}
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                    {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('logout') }}" 
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
                                </ul>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4 main-container">
            @yield('content')
        </main>

        <footer>
            &copy; 2025 Aqua Monitor | All Rights Reserved
        </footer>
    </div>
</body>
</html>
    