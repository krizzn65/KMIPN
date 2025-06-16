<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title> Aqua Monitor</title>

    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('build/assets/app-DqME6eCz.css') }}">
    <script src="{{ asset('build/assets/app-D4nMHFhB.js') }}" defer></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.3.0/exceljs.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>


    <style>
        
    </style>
</head>
<body>
     {{-- Navbar atas (desktop) --}}
        <nav class="navbar navbar-expand-lg navbar-top d-none d-md-flex">
            <div class="container">
                <a class="navbar-brand text-white" href="/dashboard">AQUATOR</a>
                <div class="ms-auto d-flex align-items-center">
                    <a class="mx-2 nav-link" href="/dashboard">Dashboard</a>
                    <a class="mx-2 nav-link" href="/history">History</a>
                    <div class="form-check form-switch ms-3">
                        <input class="form-check-input" type="checkbox" id="themeSwitch">
                        <label class="form-check-label text-white" for="themeSwitch">Dark</label>
                    </div>
                </div>
            </div>
        </nav>

        {{-- Navbar bawah (mobile) --}}
        <div class="navbar-bottom d-md-none">
        <a href="/dashboard" class="mx-2 d-flex flex-column align-items-center">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </a>
        <a href="/history" class="mx-2 d-flex flex-column align-items-center">
            <i class="fas fa-clock-rotate-left"></i>
            <span>History</span>
        </a>
        <a href="#" id="mobileThemeSwitch" class="mx-2 d-flex flex-column align-items-center">
            <i class="fas fa-circle-half-stroke"></i>
            <span>Theme</span>
        </a>
        </div>

        <main class="py-4 main-container">
            @yield('content')
        </main>

        {{-- <footer>
            &copy; 2025 Aqua Monitor | All Rights Reserved
        </footer> --}}
    </div>

     <script src="{{ asset('js/app.js') }}"></script>
     

</body>
</html>
    