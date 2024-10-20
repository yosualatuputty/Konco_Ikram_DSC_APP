<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
</head>
<body>
    <aside class="sidebar">
        <nav>
            <div class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="Eye Of God Logo" width="100">
            </div>
            <ul>
                <li><a href="{{ route('dashboard') }}">
                    <img src="{{ asset('images/Logo-house.png') }}" alt="House Logo" class="nav-icon">
                    Dashboard</a></li>
                <!-- <li><a href="#">
                    <img src="{{ asset('images/Logo-database.png') }}" alt="Database Logo" class="nav-icon">
                    Database</a></li> -->
                <li><a href="/statistics-apd">
                    <img src="{{ asset('images/logo-statistic.png') }}" alt="Database Logo" class="nav-icon">
                    Statistics APD</a></li>
                <li><a href="/statistics-drowsy">
                    <img src="{{ asset('images/logo-statistic.png') }}" alt="Database Logo" class="nav-icon">
                    Statistics Drowsy</a></li>
            </ul>
        </nav>
    </aside>

    <header class="header">
        <div class="user-info">
            Ikram Sabila
        </div>
    </header>

    <main class="main-content">
        @yield('content')
    </main>
</body>
</html>
