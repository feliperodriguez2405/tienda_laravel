<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>D'Jenny</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <style>
            /* Estilos básicos */
            body {
                font-family: 'Nunito', sans-serif;
                background-color: #f8fafc;
                margin: 0;
                padding: 0;
            }

            .bg-primary {
                background-color: #EF3B2D; /* Color principal de la tienda */
            }

            .logo {
                font-size: 2rem;
                font-weight: bold;
                color: #fff;
                text-transform: uppercase;
            }

            /* Navbar */
            .navbar {
                background-color: #EF3B2D;
                padding: 1rem;
                color: white;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .navbar a {
                color: white;
                text-decoration: none;
                margin: 0 1rem;
                font-weight: 600;
            }

            .navbar a:hover {
                text-decoration: underline;
            }

            /* Hero Section */
            .hero-section {
                text-align: center;
                margin-top: 50px;
                padding: 20px;
                background-color: #fff;
                border-radius: 10px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }

            .hero-section img {
                width: 150px;
                margin-bottom: 30px;
            }

            .hero-section h1 {
                font-size: 3rem;
                color: #EF3B2D;
                font-weight: 700;
            }

            .hero-section p {
                font-size: 1.2rem;
                color: #333;
                margin-top: 20px;
                line-height: 1.6;
            }

            /* Footer */
            .footer {
                background-color: #333;
                color: #fff;
                text-align: center;
                padding: 1rem 0;
                position: fixed;
                width: 100%;
                bottom: 0;
            }

            .footer p {
                margin: 0;
            }
        </style>
    </head>
    <body>
        <!-- Navbar -->
        <div class="navbar">
            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 flex justify-between items-center">
                <!-- Logo de la empresa -->
                <div class="logo">
                <img src="{{ asset('images/tienda.jpg') }}" alt="D'Jenny Logo">
                </div>
                <div>
                    @if (Route::has('login'))
                        <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
                            @auth
                                <a href="{{ url('/home') }}" class="text-sm text-white">Home</a>
                            @else
                                <a href="{{ route('login') }}" class="text-sm text-white">Log in</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="ml-4 text-sm text-white">Register</a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Hero Section -->
        <div class="hero-section max-w-6xl mx-auto">
            <!-- Logo de la empresa -->
            <img src="{{ asset('images/tienda.jpg') }}" alt="D'Jenny Logo">
            <h1>Bienvenidos a D'Jenny</h1>
            
            <!-- Misión y Visión -->
            <p><strong>Misión:</strong> Ofrecemos productos de calidad, exclusivos y personalizados, con el objetivo de proporcionar una experiencia única de compra a nuestros clientes.</p>
            <p><strong>Visión:</strong> Ser una empresa líder en el mercado, ofreciendo soluciones innovadoras a través de productos únicos y un servicio excepcional.</p>

            <!-- Propósito del software -->
            <p><strong>Propósito del software:</strong> Este software ha sido diseñado para gestionar el inventario de productos disponibles en la tienda y generar reportes detallados de ventas. Su objetivo es optimizar la administración del negocio y facilitar la toma de decisiones estratégicas a través de la visualización de productos y los reportes de rendimiento de ventas.</p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; 2025 D'Jenny. Todos los derechos reservados.</p>
        </div>
    </body>
</html>
