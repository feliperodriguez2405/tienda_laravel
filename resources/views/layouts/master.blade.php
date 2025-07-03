<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Panel de Cajero')</title>
    <link rel="icon" href="{{ asset('images/djenny.png') }}" type="image/x-icon">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Estilos externos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
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
<footer class="footer">
    <div class="container">
        <div class="row gy-4 align-items-start">
            <!-- Redes Sociales -->
            <div class="col-12 col-md-4 text-center text-md-start mb-4 mb-md-0">
                <h5 class="mb-3 text-uppercase fw-bold">Síguenos</h5>
                <div class="d-flex justify-content-center justify-content-md-start gap-3">
                    <a href="https://wa.me/573172343575?text=¡Hola! Estoy interesado en obtener más información sobre el software D'Jenny para tiendas de abarrotes." target="_blank" title="Contactar por WhatsApp">
                        <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/whatsapp.svg" alt="WhatsApp" width="28" height="28" style="filter: invert(1);">
                    </a>
                    <a href="https://www.facebook.com/profile.php?id=61560973980821" target="_blank" class="d-inline-block" title="Facebook">
                        <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/facebook.svg" alt="Facebook" width="28" height="28" style="filter: invert(1);">
                    </a>
                    <a href="https://www.instagram.com/alphasoft.5/" target="_blank" class="d-inline-block" title="Instagram">
                        <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/instagram.svg" alt="Instagram" width="28" height="28" style="filter: invert(1);">
                    </a>
                </div>
            </div>

            <!-- Manuales -->
            <div class="col-12 col-md-4 text-center">
                <h5 class="text-uppercase fw-bold mb-3">Manuales</h5>
                <div class="d-grid gap-2 mx-auto" style="max-width: 200px;">
                    <a href="{{ route('manual') }}" target="_blank" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-file-earmark-text-fill me-1"></i> Manual de Usuario
                    </a>
                    <a href="{{ route('manual') }}" target="_blank" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-file-earmark-text-fill me-1"></i> Manual Técnico
                    </a>
                </div>
            </div>

            <!-- Información y Mapa -->
            <div class="col-12 col-md-4 text-center text-md-end">
                <h5 class="mb-3 text-uppercase fw-bold">Encuéntranos</h5>
                <a href="https://www.google.com/maps?q=Carrera+36A+%232e-57+Neiva-Huila" 
                   target="_blank" 
                   class="text-white text-decoration-underline" 
                   style="font-weight: 500;">
                    Carrera 36A #2e-57
                    <div>Neiva - Huila</div>
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

    <!-- Botón flotante de WhatsApp -->
    <div class="whatsapp-float">
        <a href="https://wa.me/573172343575?text=¡Hola! Estoy interesado en obtener más información sobre el software D'Jenny para tiendas de abarrotes." target="_blank" title="Contactar por WhatsApp">
            <i class="bi bi-whatsapp" style="font-size: 2rem;"></i>
        </a>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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