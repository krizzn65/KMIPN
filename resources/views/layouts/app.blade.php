i
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/minimalist-ui.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title> Wisanggeni</title>

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
    <!-- Sidebar Toggle Button (Mobile) -->
    <button class="sidebar-toggle" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar Navigation -->
    <aside class="sidebar" id="sidebar">
        <!-- Logo -->
        <div class="sidebar-logo">
            <div class="logo-text">
                <span class="logo-accent">W</span>ISANGGENI
            </div>
        </div>

        <!-- Menu Section -->
        <div class="sidebar-section">
            <h3 class="section-title">Menu</h3>
            <ul class="sidebar-nav">
                <li class="sidebar-item">
                    <a href="{{ url('/dashboard') }}"
                        class="sidebar-link {{ request()->is('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ url('/history') }}" class="sidebar-link {{ request()->is('history') ? 'active' : '' }}">
                        <i class="fas fa-history"></i>
                        <span>History</span>
                    </a>
                </li>
            </ul>
        </div>


        <!-- TOOL Section -->
        <div class="sidebar-section">
            <h3 class="section-title">TOOL</h3>
            <ul class="sidebar-nav">
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link" id="theme-toggle">
                        <i class="fas fa-moon"></i>
                        <span>Dark Mode</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="main-content">
        <div class="container-fluid py-4">
            @yield('content')
        </div>
    </main>

    <!-- JavaScript for sidebar toggle and theme switching -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const themeToggle = document.getElementById('theme-toggle');

            // Sidebar toggle functionality
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('open');
            });

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                if (window.innerWidth <= 1024) {
                    if (!sidebar.contains(event.target) && !sidebarToggle.contains(event.target) && sidebar
                        .classList.contains('open')) {
                        sidebar.classList.remove('open');
                    }
                }
            });

            // Theme switching functionality
            function initTheme() {
                const savedTheme = localStorage.getItem('theme') || 'light';
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                const theme = savedTheme === 'auto' ? (prefersDark ? 'dark' : 'light') : savedTheme;

                document.documentElement.setAttribute('data-theme', theme);
                updateThemeToggle(theme);
            }

            function toggleTheme() {
                const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

                document.documentElement.setAttribute('data-theme', newTheme);
                localStorage.setItem('theme', newTheme);
                updateThemeToggle(newTheme);
            }

            function updateThemeToggle(theme) {
                const themeToggle = document.getElementById('theme-toggle');
                const icon = themeToggle.querySelector('i');
                const text = themeToggle.querySelector('span');

                if (theme === 'dark') {
                    icon.className = 'fas fa-sun';
                    text.textContent = 'Light Mode';
                } else {
                    icon.className = 'fas fa-moon';
                    text.textContent = 'Dark Mode';
                }
            }

            // Initialize theme
            initTheme();

            // Add click event to theme toggle
            themeToggle.addEventListener('click', function(e) {
                e.preventDefault();
                toggleTheme();
            });
        });
    </script>
</body>

<script src="{{ asset('js/app.js') }}"></script>


</body>

</html>
