

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>D'Jenny Supermercado</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Poppins', sans-serif;
                background-color: #f5f7fa;
                color: #2d3436;
                line-height: 1.6;
            }

            /* Navbar */
            .navbar {
                background: linear-gradient(90deg, #EF3B2D 0%, #ff6348 100%);
                padding: 1.5rem 2rem;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                position: sticky;
                top: 0;
                z-index: 1000;
            }

            .navbar-container {
                max-width: 1200px;
                margin: 0 auto;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .logo {
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .logo img {
                width: 50px;
                height: 50px;
                object-fit: contain;
            }

            .logo span {
                color: white;
                font-size: 1.8rem;
                font-weight: 600;
                text-transform: uppercase;
            }

            .nav-links a {
                color: white;
                text-decoration: none;
                margin-left: 2rem;
                font-weight: 500;
                transition: all 0.3s ease;
            }

            .nav-links a:hover {
                color: #ffeaa7;
                transform: translateY(-2px);
            }

            /* Hero Section */
            .hero-section {
                max-width: 1200px;
                margin: 4rem auto;
                padding: 3rem;
                background: white;
                border-radius: 20px;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
                position: relative;
                overflow: hidden;
            }

            .hero-section::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 5px;
                background: linear-gradient(90deg, #EF3B2D, #ffeaa7);
            }

            .hero-header {
                text-align: center;
                margin-bottom: 2rem;
            }

            .hero-header img {
                width: 120px;
                margin-bottom: 1.5rem;
                border-radius: 50%;
                border: 4px solid #EF3B2D;
            }

            .hero-header h1 {
                color: #EF3B2D;
                font-size: 2.5rem;
                font-weight: 700;
                margin-bottom: 1rem;
            }

            .content-grid {
                display: grid;
                gap: 2rem;
                margin: 0 1rem;
            }

            .content-card {
                background: #fff;
                padding: 1.5rem;
                border-radius: 10px;
                border-left: 4px solid #EF3B2D;
                transition: transform 0.3s ease;
            }

            .content-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            }

            .content-card strong {
                color: #EF3B2D;
                display: block;
                margin-bottom: 0.5rem;
                font-size: 1.1rem;
            }

            /* Footer */
            .footer {
                background: #2d3436;
                color: white;
                padding: 2rem;
                margin-top: 4rem;
            }

            .footer-content {
                max-width: 1200px;
                margin: 0 auto;
                text-align: center;
            }

            .footer-content p {
                opacity: 0.9;
                font-size: 0.9rem;
            }

            /* Supermarket Elements */
            .cart-icon {
                position: absolute;
                top: 20px;
                right: 20px;
                opacity: 0.1;
                font-size: 100px;
            }

            @media (max-width: 768px) {
                .navbar-container {
                    flex-direction: column;
                    gap: 1rem;
                }

                .nav-links a {
                    margin: 0.5rem;
                }

                .hero-section {
                    margin: 2rem 1rem;
                    padding: 2rem 1rem;
                }
            }
        </style>
    </head>
    <body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-container">
            <div class="logo">
                <img src="{{ asset('images/tienda.jpg') }}" alt="D'Jenny Logo">
                <span>D'Jenny</span>
            </div>
            <div class="nav-links">
                <!-- Lógica de navegación -->
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/home') }}">Inicio</a>
                        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                            @csrf
                            <button type="submit" class="nav-link-btn">Cerrar Sesión</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}">Iniciar Sesión</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Registrarse</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <section class="hero-section">
        <div class="hero-header">
            <img src="{{ asset('images/tienda.jpg') }}" alt="D'Jenny Logo">
            <h1>Bienvenidos a D'Jenny Supermercado</h1>
        </div>
        <div class="content-grid">
            <div class="content-card">
                <strong>Misión</strong>
                <p>Ofrecer productos frescos y de calidad con un servicio excepcional que haga de cada compra una experiencia agradable y conveniente.</p>
            </div>
            <div class="content-card">
                <strong>Visión</strong>
                <p>Ser el supermercado preferido de la comunidad, destacándonos por nuestra variedad, precios competitivos y atención personalizada.</p>
            </div>
            <div class="content-card">
                <strong>Nuestro Sistema</strong>
                <p>Gestiona eficientemente nuestro inventario y ventas, proporcionando informes detallados para optimizar tu experiencia de compra.</p>
            </div>
        </div>
    </section>

    <!-- Pie de página -->
    <footer class="footer">
        <div class="footer-content">
            <p>© 2025 Supermercado D'Jenny. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>

