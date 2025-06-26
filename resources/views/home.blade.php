
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
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: all 0.3s ease;
            position: relative;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.15);
        }

        .product-card .image-container {
            width: 100%;
            aspect-ratio: 4 / 3; /* Maintains proportional height */
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa; /* Fallback background for empty space */
        }

        .product-card img {
            width: 100%;
            height: 100%;
            object-fit: contain; /* Ensures full image is visible without cropping */
            object-position: center; /* Centers image */
            transition: transform 0.3s ease;
        }

        .product-card:hover img {
            transform: scale(1.05);
        }

        .product-card .card-body {
            padding: 1.5rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .product-card .card-title {
            font-size: 1.2rem;
            color: #263238;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .product-card .price {
            font-size: 1.1rem;
            color: #398129;
            font-weight: 700;
        }

        .product-card .small {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 1rem;
        }

        .product-card .btn {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .product-card .btn:hover {
            background-color: #2e6b21;
            border-color: #2e6b21;
        }

        .empty-message {
            color: #263238;
            font-size: 1.2rem;
            text-align: center;
            padding: 2rem;
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
            padding: 3rem 1.5rem;
            margin-top: 4rem;
            position: relative;
            font-family: 'Inter', sans-serif;
        }

        .footer-container {
            max-width: 1280px;
            margin: 0 auto;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            align-items: start;
        }

        .footer-section h5 {
            font-size: 1.2rem;
            font-weight: 800;
            text-transform: uppercase;
            margin-bottom: 1.2rem;
            color: #e0f7fa;
        }

        .social-links {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .social-icons {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .social-icon {
            width: 32px;
            height: 32px;
            filter: invert(1);
            transition: transform 0.3s ease;
        }

        .social-icon:hover {
            transform: scale(1.2);
        }

        .contact-info p {
            font-size: 0.95rem;
            color: #e0f7fa;
            margin-bottom: 1rem;
            line-height: 1.5;
        }

        .map-container {
            position: relative;
            padding-bottom: 56.25%; /* 16:9 Aspect Ratio */
            height: 0;
            overflow: hidden;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .map-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 0;
        }

        .footer-divider {
            border: none;
            border-top: 1px solid rgba(255, 255, 255, 0.25);
            margin: 2rem 0;
        }

        .footer-bottom {
            text-align: center;
        }

        .footer-bottom p {
            font-size: 0.9rem;
            color: #e0f7fa;
            opacity: 0.9;
            margin: 0;
        }

        /* Responsive Adjustments */
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

            .footer {
                padding: 2rem 1rem;
            }

            .footer-grid {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .social-links {
                align-items: center;
            }

            .contact-info {
                text-align: center;
            }

            .map-container {
                padding-bottom: 75%; /* Slightly taller for smaller screens */
            }

            .product-card .image-container {
                aspect-ratio: 4 / 3; /* Maintain aspect ratio on smaller screens */
            }
        }

        @media (max-width: 576px) {
            .products-grid {
                grid-template-columns: 1fr;
            }

            .content-grid {
                grid-template-columns: 1fr;
            }

            .footer-section h5 {
                font-size: 1.1rem;
            }

            .social-icon {
                width: 28px;
                height: 28px;
            }

            .contact-info p {
                font-size: 0.9rem;
            }

            .footer-bottom p {
                font-size: 0.85rem;
            }

            .product-card .image-container {
                aspect-ratio: 4 / 3; /* Consistent aspect ratio */
                max-height: 180px; /* Slightly smaller for mobile */
            }
        }

        @keyframes slideDown {
            from { transform: translateY(-30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>

    <!-- Fonts and Animate.css -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="icon" href="{{ asset('images/djenny.png') }}" type="image/x-icon">
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
                <a href="{{ route('manual') }}">Manual de Usuario</a>
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

    <!-- Productos Destacados -->
    <section>
        <h2 class="text-center mb-3" style="color: #FF6608; font-weight: 800; font-size: 2rem;">Productos Destacados</h2>
        <div class="products-grid">
            <div class="product-card animate__animated animate__fadeInUp" data-delay="0">
                <div class="image-container">
                    <img src="{{ asset('images/arroz-supremo.png') }}" class="card-img-top" alt="Arroz Premium" loading="lazy">
                </div>
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Arroz Premium</h5>
                    <p class="mb-2">Granos y Cereales</p>
                    <p class="price mb-2"><strong>12.000 COP</strong></p>
                    <p class="small">Stock: 100</p>
                </div>
            </div>
            <div class="product-card animate__animated animate__fadeInUp" data-delay="100">
                <div class="image-container">
                    <img src="{{ asset('images/product2.jpg') }}" class="card-img-top" alt="Aceite de Cocina" loading="lazy">
                </div>
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Aceite de Cocina</h5>
                    <p class="mb-2">Aceites y Grasas</p>
                    <p class="price mb-2"><strong>8.500 COP</strong></p>
                    <p class="small">Stock: 50</p>
                </div>
            </div>
            <div class="product-card animate__animated animate__fadeInUp" data-delay="200">
                <div class="image-container">
                    <img src="{{ asset('images/product3.jpg') }}" class="card-img-top" alt="Leche Entera" loading="lazy">
                </div>
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Leche Entera</h5>
                    <p class="mb-2">Lácteos</p>
                    <p class="price mb-2"><strong>3.200 COP</strong></p>
                    <p class="small">Stock: 200</p>
                </div>
            </div>
            <div class="product-card animate__animated animate__fadeInUp" data-delay="300">
                <div class="image-container">
                    <img src="{{ asset('images/product4.jpg') }}" class="card-img-top" alt="Café Molido" loading="lazy">
                </div>
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Café Molido</h5>
                    <p class="mb-2">Bebidas</p>
                    <p class="price mb-2"><strong>15.000 COP</strong></p>
                    <p class="small">Stock: 75</p>
                </div>
            </div>
            <div class="product-card animate__animated animate__fadeInUp" data-delay="400">
                <div class="image-container">
                    <img src="{{ asset('images/product5.jpg') }}" class="card-img-top" alt="Pan Integral" loading="lazy">
                </div>
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Pan Integral</h5>
                    <p class="mb-2">Panadería</p>
                    <p class="price mb-2"><strong>4.500 COP</strong></p>
                    <p class="small">Stock: 60</p>
                </div>
            </div>
            <div class="product-card animate__animated animate__fadeInUp" data-delay="500">
                <div class="image-container">
                    <img src="{{ asset('images/product6.jpg') }}" class="card-img-top" alt="Huevos AA" loading="lazy">
                </div>
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Huevos AA</h5>
                    <p class="mb-2">Huevos y Derivados</p>
                    <p class="price mb-2"><strong>10.000 COP</strong></p>
                    <p class="small">Stock: 90</p>
                </div>
            </div>
        </div>
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
        <div class="footer-container">
            <div class="footer-grid">
                <!-- Redes Sociales -->
                <div class="footer-section social-links">
                    <h5>Síguenos</h5>
                    <div class="social-icons">
                        <a href="https://wa.me/1234567890" target="_blank" aria-label="WhatsApp">
                            <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/whatsapp.svg" alt="WhatsApp" class="social-icon">
                        </a>
                        <a href="https://facebook.com" target="_blank" aria-label="Facebook">
                            <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/facebook.svg" alt="Facebook" class="social-icon">
                        </a>
                        <a href="https://instagram.com" target="_blank" aria-label="Instagram">
                            <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/instagram.svg" alt="Instagram" class="social-icon">
                        </a>
                    </div>
                </div>

                <!-- Información y Mapa -->
                <div class="footer-section contact-info">
                    <h5>Encuéntranos</h5>
                    <p>Dirección: Carrera 36A #2e-57, Neiva - Huila</p>
                    <div class="map-container">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d3984.617385749955!2d-75.26511422502979!3d2.925829097050505!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zMsKwNTUnMzMuMCJOIDc1wrAxNSc0NS4xIlc!5e0!3m2!1ses!2sco!4v1748389528042!5m2!1ses!2sco" allowfullscreen loading="lazy" title="Ubicación de D'Jenny"></iframe>
                    </div>
                </div>
            </div>

            <!-- Línea inferior -->
            <hr class="footer-divider">
            <div class="footer-bottom">
                <p>© {{ date('Y') }} D'Jenny - Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- JavaScript for Animations -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const delay = entry.target.getAttribute('data-delay') || 0;
                        setTimeout(() => {
                            entry.target.classList.add('animate__animated', 'animate__fadeInUp');
                        }, delay);
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1 });

            document.querySelectorAll('.product-card, .content-card').forEach(card => {
                observer.observe(card);
            });

            document.querySelectorAll('.product-card, .content-card').forEach(card => {
                card.addEventListener('mouseenter', () => {
                    card.style.transform = 'translateY(-10px)';
                    card.style.boxShadow = '0 12px 24px rgba(0, 0, 0, 0.15)';
                });
                card.addEventListener('mouseleave', () => {
                    card.style.transform = 'translateY(0)';
                    card.style.boxShadow = '0 4px 15px rgba(0, 0, 0, 0.1)';
                });
            });
        });
    </script>
</body>
</html>
