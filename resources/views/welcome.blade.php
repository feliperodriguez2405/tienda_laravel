<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tienda D'Jenny</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
        font-family: 'Poppins', sans-serif;
        background-color: #e0f7fa;
        color: #263238; /* antes era #455a64 */
        line-height: 1.6;
    }

        /* Navbar */
        .navbar {
            background: #006064;
            padding: 1.2rem 2rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 999;
            animation: slideDown 0.4s ease-out;
        }

        .navbar-container {
            max-width: 1200px;
            margin: auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo img {
            width: 48px;
            height: 48px;
            object-fit: cover;
            border-radius: 8px;
        }

        .logo span {
            color: #ffffff;
            font-size: 1.6rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .nav-links a, .nav-link-btn {
            color: #ffffff;
            text-decoration: none;
            margin-left: 1.8rem;
            font-weight: 500;
            transition: all 0.3s ease;
            font-size: 1rem;
            background: none;
            border: none;
            cursor: pointer;
        }

        .nav-links a:hover,
        .nav-link-btn:hover {
            color: #e0f7fa;
            transform: scale(1.05);
        }

        /* Hero Section */
        .hero-section {
            max-width: 1200px;
            margin: 4rem auto;
            padding: 3rem;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
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
            background: linear-gradient(90deg, #00bcd4, #0097a7);
        }

        .hero-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .hero-header img {
            width: 100px;
            margin-bottom: 1rem;
            border-radius: 30%;
            box-shadow: 0 0 0 6px #e0f7fa;
        }

        .hero-header h1 {
            color: #0097a7;
            font-size: 2.7rem;
            font-weight: 800;
            margin-bottom: 0.8rem;
        }

        /* Cards Section */
        .content-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin: 0 1rem;
        }

        .content-card {
            background: #ffffff;
            padding: 1.5rem;
            border-radius: 12px;
            border-left: 4px solid #00bcd4;
            transition: all 0.3s ease;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
            font-size: 0.95rem;
            color: #37474f;
        }

        .content-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        .content-card strong {
            color: #006064; 
            font-weight: 700;
            font-size: 1.1rem;
            display: block;
            margin-bottom: 0.6rem;
            font-size: 1.1rem;
            
        }

        .content-card p {
            font-size: 0.95rem;
            color: #455a64;
        }

        /* Footer */
        .footer {
            background: #006064;
            color: #ffffff;
            padding: 2rem;
            margin-top: 4rem;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
        }

        .footer-content p {
            opacity: 0.85;
            font-size: 0.9rem;
        }

        /* Animaciones */
        @keyframes slideDown {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar-container {
                flex-direction: column;
                gap: 1rem;
            }

            .nav-links {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
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

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-container">
            <div class="logo">
                <img src="{{ asset('images/djenny.png') }}" alt="D'Jenny Logo">
                <span>D'Jenny</span>
            </div>
            <div class="nav-links">
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

    <!-- Hero -->
    <section class="hero-section">
        <div class="hero-header">
            <img src="{{ asset('images/djenny.png') }}" alt="D'Jenny Logo">
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

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <p>© 2025 Supermercado D'Jenny. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>
