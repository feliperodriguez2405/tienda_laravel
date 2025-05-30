<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Panel de Cajero')</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Estilos externos -->
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Estilos personalizados -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #006064;
            --secondary-color: #263238;
            --text-color: #212529;
            --bg-color: #e0f7fa;
        }

        [data-theme="dark"] {
            --primary-color: #263238;
            --secondary-color: #006064;
            --text-color: #e9ecef;
            --bg-color: #263238;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            font-family: 'Segoe UI', Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }

        /* Navbar */
        .navbar {
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            padding: 0.75rem 0;
            border-radius: 0 0 10px 10px;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.6rem;
            color: #ffffff;
            transition: transform 0.2s ease-in-out;
        }

        .navbar-brand:hover {
            transform: scale(1.05);
        }

        .nav-link {
            font-weight: 500;
            padding: 0.5rem 1rem;
            color: #e9ecef !important;
            transition: all 0.2s ease-in-out;
            border-radius: 5px;
        }

        .nav-link:hover {
            color: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
        }

        /* Theme Toggle */
        .theme-toggle {
            cursor: pointer;
            font-size: 1.2rem;
            color: #e9ecef;
            transition: transform 0.2s ease-in-out;
        }

        .theme-toggle:hover {
            transform: rotate(180deg);
        }

        /* Main Content */
        main {
            flex: 1 0 auto;
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Footer */
        footer {
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.15);
            padding: 1.5rem 0;
            border-radius: 10px 10px 0 0;
        }

        footer p {
            margin: 0;
            font-size: 0.9rem;
            color: #ffffff;
            opacity: 0.9;
        }

        footer h5 {
    font-size: 1.2rem;
    font-weight: bold;
    margin-bottom: 1rem;
}

footer a img:hover {
    transform: scale(1.2);
    transition: transform 0.3s ease;
}


        /* Botón Logout */
        .logout-link {
            transition: all 0.2s ease-in-out;
        }

        .logout-link:hover {
            transform: scale(1.05);
            color: #dc3545 !important;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .navbar-nav {
                text-align: center;
                padding-top: 1rem;
            }

            .nav-item {
                margin: 0.5rem 0;
            }

            .theme-toggle {
                margin: 0.5rem;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
        <!-- Navbar (se inyecta desde cada vista) -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
     @yield('navbar')
        <div class="nav-item">
            <i class="bi bi-moon-stars-fill theme-toggle ms-3" onclick="toggleTheme()"></i>
        </div>
        </div>
    </nav>

        <!-- Contenido dinámico -->
    <main class="container mt-4 mb-4">
        @yield('content')
    </main>
    
        <!-- Footer -->
<footer class="footer text-white pt-5 pb-4" style="background: linear-gradient(90deg, #006064, #263238); border-radius: 10px 10px 0 0;">
    <div class="container">
        <div class="row">
            <!-- Redes Sociales -->
            <div class="col-md-6 mb-4 mb-md-0">
                <h5 class="mb-3 text-uppercase fw-bold">Síguenos</h5>
                <div class="d-flex gap-3">
                    <a href="https://wa.me/1234567890" target="_blank" class="d-inline-block" title="WhatsApp">
                        <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/whatsapp.svg" alt="WhatsApp" width="28" height="28" style="filter: invert(1); transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.2)'" onmouseout="this.style.transform='scale(1)'">
                    </a>
                    <a href="https://www.facebook.com/profile.php?id=61560973980821" target="_blank" class="d-inline-block" title="Facebook">
                        <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/facebook.svg" alt="Facebook" width="28" height="28" style="filter: invert(1); transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.2)'" onmouseout="this.style.transform='scale(1)'">
                    </a>
                    <a href="https://www.instagram.com/alphasoft.5/" target="_blank" class="d-inline-block" title="Instagram">
                        <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/instagram.svg" alt="Instagram" width="28" height="28" style="filter: invert(1); transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.2)'" onmouseout="this.style.transform='scale(1)'">
                    </a>
                </div>
            </div>

            <!-- Información y Mapa -->
            <div class="col-md-6 text-md-end mt-4 mt-md-0">
                <h5 class="mb-2 text-uppercase fw-bold">Encuéntranos</h5>
                <a href="https://www.google.com/maps?q=Carrera+36A+%232e-57+Neiva-Huila" 
           target="_blank" 
           class="text-white text-decoration-underline"
           style="font-weight: 500;">
            Carrera 36A #2e-57 Neiva-Huila
        </a>
            </div>
        </div>

        <!-- Línea inferior -->
        <hr class="my-4 border-light opacity-25">
        <div class="text-center small">
            @yield('footer')
        </div>
    </div>
</footer>


    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script>
        function toggleTheme() {
            const body = document.body;
            const currentTheme = body.getAttribute('data-theme');
            body.setAttribute('data-theme', currentTheme === 'dark' ? 'light' : 'dark');
            localStorage.setItem('theme', body.getAttribute('data-theme'));
        }

        document.addEventListener('DOMContentLoaded', () => {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.body.setAttribute('data-theme', savedTheme);
        });
    </script>
    @stack('scripts')
</body>
</html>
