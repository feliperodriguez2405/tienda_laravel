<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
    <title>Tienda D'Jenny</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #e0f7fa;
            color: #263238;
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* Navbar */
        .navbar {
            background: #09222F;
            padding: 1rem 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            position: sticky;
            top: 0;
            z-index: 1000;
            animation: slideDown 0.5s ease-out;
        }

        .navbar-container {
            max-width: 1280px;
            margin: auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .logo img {
            width: 52px;
            height: 52px;
            object-fit: cover;
            border-radius: 10px;
            transition: transform 0.3s ease;
        }

        .logo img:hover {
            transform: scale(1.1);
        }

        .logo span {
            color: #ffffff;
            font-size: 1.8rem;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .nav-links a, .nav-link-btn {
            color: #ffffff;
            text-decoration: none;
            margin-left: 2rem;
            font-weight: 600;
            font-size: 1.05rem;
            transition: all 0.3s ease;
            background: none;
            border: none;
            cursor: pointer;
            position: relative;
        }

        .nav-links a::after, .nav-link-btn::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -4px;
            left: 0;
            background-color: #e0f7fa;
            transition: width 0.3s ease;
        }

        .nav-links a:hover::after, .nav-link-btn:hover::after {
            width: 100%;
        }

        .nav-links a:hover, .nav-link-btn:hover {
            color: #e0f7fa;
        }

        /* Carousel */
        .carousel {
            max-width: 1280px;
            margin: 2rem auto;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        .carousel-item img {
            max-height: 400px;
            object-fit: cover;
            transition: transform 0.6s ease;
        }

        .carousel-item img:hover {
            transform: scale(1.03);
        }

        /* Productos Grid */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2.5rem;
            padding: 2rem;
            max-width: 1280px;
            margin: 3rem auto;
        }

        .product-card {
            background: #09222F;
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: all 0.3s ease;
            position: relative;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.15);
        }

        .product-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .product-card:hover img {
            transform: scale(1.05);
        }

        .product-card .content {
            padding: 1.5rem;
        }

        .product-card h1 {
            font-size: 1.3rem;
            color: #FF6608;
            margin-bottom: 0.6rem;
            font-weight: 700;
        }

        .product-card p {
            font-size: 0.95rem;
            color: #e0f7fa;
            margin-bottom: 1.2rem;
        }

        .product-card a {
            display: inline-block;
            padding: 0.6rem 1.5rem;
            background: #398129;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .product-card a:hover {
            background: #398129;
            transform: translateY(-2px);
        }

        /* Hero Section */
        .hero-section {
            max-width: 1280px;
            margin: 4rem auto;
            padding: 4rem;
            background: #e0f7fa;
            border-radius: 24px;
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: linear-gradient(90deg, #FF6608, #0097a7);
        }

        .hero-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .hero-header img {
            width: 120px;
            margin-bottom: 1.5rem;
            border-radius: 50%;
            box-shadow: 0 0 0 8px #e0f7fa;
            transition: transform 0.3s ease;
        }

        .hero-header img:hover {
            transform: scale(1.1);
        }

        .hero-header h1 {
            color: #FF6608;
            font-size: 3rem;
            font-weight: 900;
            margin-bottom: 1rem;
            letter-spacing: -0.5px;
        }

        .content-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2.5rem;
            margin: 0 1.5rem;
        }

        .content-card {
            background: #09222F;
            padding: 2rem;
            border-radius: 16px;
            border-left: 5px solid #FF6608;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            font-size: 1rem;
            color: #37474f;
        }

        .content-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.15);
        }

        .content-card strong {
            color: #398129;
            font-weight: 800;
            font-size: 1.2rem;
            display: block;
            margin-bottom: 0.8rem;
        }

        .content-card p {
            font-size: 1rem;
            color: #e0f7fa;
        }

        /* Footer */
        .footer {
            background: #09222F;
            color: #ffffff;
            padding: 3rem 2rem;
            margin-top: 5rem;
            position: relative;
        }

        .footer-content {
            max-width: 1280px;
            margin: 0 auto;
            text-align: center;
        }

        .footer-content p {
            opacity: 0.9;
            font-size: 1rem;
            font-weight: 500;
        }

        @keyframes slideDown {
            from { transform: translateY(-30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        @media (max-width: 768px) {
            .navbar-container {
                flex-direction: column;
                gap: 1.5rem;
            }

            .nav-links {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 1rem;
            }

            .nav-links a, .nav-link-btn {
                margin: 0.5rem;
            }

            .hero-section {
                margin: 2rem 1rem;
                padding: 2.5rem 1.5rem;
            }

            .hero-header h1 {
                font-size: 2.2rem;
            }

            .carousel-item img {
                max-height: 300px;
            }
        }

        @media (max-width: 576px) {
            .products-grid {
                grid-template-columns: 1fr;
            }

            .content-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
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

    <!-- Carrusel Principal -->
    <div id="carouselExample" class="carousel slide">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('images/djenny.png') }}" class="d-block w-100" alt="...">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/tienda.jpg') }}" class="d-block w-100" alt="...">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/djenny.png') }}" class="d-block w-100" alt="...">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Siguiente</span>
        </button>
    </div>

    <!-- Productos Destacados -->
    <section class="products-grid">
        @for ($i = 1; $i <= 9; $i++)
            <div class="product-card">
                <img src="{{ asset('images/djenny.png') }}" alt="Producto {{ $i }}">
                <div class="content">
                    <h1>Producto {{ $i }}</h1>
                    <p>Descripción breve del producto número {{ $i }}. Calidad garantizada y precios accesibles para todos.</p>
                    <a href="{{ route('login') }}">Ver más</a>
                </div>
            </div>
        @endfor
    </section>

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
