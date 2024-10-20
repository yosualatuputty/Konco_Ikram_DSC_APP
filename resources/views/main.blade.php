<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SafeVision Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
</head>

<body>
    <div class="container">
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

            <h1>Dashboard</h1>
            <section class="dashboard">
                <a href="/manufacturing-operation" class="card-link">
                    <div class="card">
                        <div class="content">
                            <h3>Manufacturing Operation</h3>
                            <p>CAM2-LIVING</p>
                        </div>
                        <div class="image">
                            <img src="https://images.ctfassets.net/fqtbha7ac6p4/wCuEjVSAM8mCoq9sTo5QC/f5ed83d82cc37670583b774a94f09ac4/Blog-UpToDate__2_.jpg" alt="Living Room" class="card-image">
                        </div>
                    </div>
                </a>
                <a href="/construction-operation" class="card-link">
                    <div class="card">
                        <div class="content">
                            <h3>Construction Operation</h3>
                            <p>CAM2-LIVING</p>
                        </div>
                        <div class="image">
                            <img src="https://images.ctfassets.net/fqtbha7ac6p4/wCuEjVSAM8mCoq9sTo5QC/f5ed83d82cc37670583b774a94f09ac4/Blog-UpToDate__2_.jpg" alt="Living Room" class="card-image">
                        </div>
                    </div>
                </a>
            </section>
        </main>
    </div>
    <script src="{{ asset('js/main.js') }}"></script>
</body>

</html>
