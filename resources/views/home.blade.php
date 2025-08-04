<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Tienda D'Jenny</title>
    <meta name="keywords" content="supermercado, abarrotes, productos frescos">
    <meta name="description" content="D'Jenny Supermercado ofrece productos frescos y de calidad para tus necesidades diarias.">
    <meta name="author" content="D'Jenny">
    <link rel="preload" href="{{ asset('images/loading.gif') }}" as="image">
    <link rel="preload" href="{{ asset('html/css/bootstrap.min.css') }}" as="style">
    <link rel="preload" href="{{ asset('html/css/custom-style.css') }}" as="style">
    <link rel="stylesheet" href="{{ asset('html/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('html/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('html/css/responsive.css') }}" media="screen and (max-width: 1200px)">
    <link rel="stylesheet" href="{{ asset('html/css/jquery.mCustomScrollbar.min.css') }}">
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="icon" href="{{ asset('images/djenny.png') }}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        .map_section {
            width: 100%;
            margin: 20px 0;
        }
        .map_section iframe {
            width: 100%;
            height: 600px;
            border: 0;
        }
        .clients_box {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        .clients_box figure {
            margin-bottom: 15px;
        }
        .clients_box img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 50%;
            display: block;
            margin: 0 auto;
        }
        .clients_box h3 {
            font-size: 22px;
            margin-bottom: 10px;
        }
        .clients_box p {
            font-size: 16px;
            color: #666;
            line-height: 1.5;
        }
        .clients_box .rating i {
            font-size: 18px;
            color: #f1c40f;
        }
        .glasses_box {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            height: 600px; /* Maintained height for long boxes */
            justify-content: space-between;
            padding: 20px;
            box-sizing: border-box;
        }
        .glasses_box figure {
            width: 200px; /* Reduced width for smaller images */
            height: 200px; /* Reduced height for smaller images */
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto;
        }
        .glasses_box img {
            width: 100%;
            height: 100%;
            object-fit: contain; /* Changed to contain to preserve image quality and aspect ratio */
        }
        .glasses_box h3 {
            font-size: 20px;
            margin: 10px 0;
        }
        .glasses_box .description {
            font-size: 16px;
            flex-grow: 1;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 4; /* Allows up to 4 lines of text */
            -webkit-box-orient: vertical;
        }
        .glasses_box .price {
            font-size: 18px;
            margin: 10px 0;
        }
        .logo img {
            max-width: 150px;
            height: auto;
        }
        .pagination {
            justify-content: center;
            margin-top: 20px;
        }
        .header {
            position: relative;
            top: -20px;
        }
        .logo_section {
            position: relative;
            top: -30px;
        }
        .nav-item select {
            display: flex;
            align-items: center;
            background: none;
            border: none;
            color: inherit;
            font-size: inherit;
            cursor: pointer;
            padding-left: 20px;
            background-image: url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/icons/gear-fill.svg');
            background-repeat: no-repeat;
            background-position: left center;
            background-size: 16px;
        }
        .nav-item select option {
            color: #000;
        }
        .carousel-caption {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            text-align: center;
        }
        .carousel-caption .text-bg {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 100%;
        }
        .carousel-caption .banner-img {
            width: 400px;
            height: 300px;
            object-fit: contain;
            margin: 20px auto;
        }
    </style>
</head>
<body class="main-layout">
    <div class="loader_bg">
        <div class="loader"><img src="{{ asset('images/loading.gif') }}" alt="Cargando" /></div>
    </div>
    <header>
        <div class="header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col logo_section">
                        <div class="full">
                            <div class="center-desk">
                                <div class="logo">
                                    <a href="{{ route('home') }}"><img src="{{ asset('images/logo.png') }}" alt="Logo D'Jenny" class="hero-img" /></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-9 col-lg-9 col-md-9 col-sm-9">
                        <nav class="navigation navbar navbar-expand-md navbar-dark">
                            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Alternar navegación">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse" id="navbarsExample04">
                                <ul class="navbar-nav mr-auto">
                                    <li class="nav-item active">
                                        <a class="nav-link" href="{{ route('home') }}">Inicio</a>
                                    </li>
                                    @if (Route::has('login'))
                                        @auth
                                            <li class="nav-item">
                                                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="nav-link">Cerrar Sesión</button>
                                                </form>
                                            </li>
                                        @else
                                            <li class="nav-item d_none login_btn">
                                                <a class="nav-link" href="{{ route('login') }}">Iniciar Sesión</a>
                                            </li>
                                            @if (Route::has('register'))
                                                <li class="nav-item d_none">
                                                    <a class="nav-link" href="{{ route('register') }}">Registrarse</a>
                                                </li>
                                            @endif
                                        @endauth
                                    @endif
                                    <li class="nav-item">
                                        <select class="nav-link" onchange="window.open(this.value, '_blank')">
                                            <option value="" disabled selected>Más información</option>
                                            <option value="{{ route('manual') }}">Manual de Usuario</option>
                                            <option value="{{ route('manualTec') }}">Manual Técnico</option>
                                            <option value="{{ route('developers') }}">Desarrolladores</option>
                                        </select>
                                    </li>
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <section class="banner_main">
        <div id="banner1" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#banner1" data-slide-to="0" class="active"></li>
                <li data-target="#banner1" data-slide-to="1"></li>
                <li data-target="#banner1" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="container">
                        <div class="carousel-caption">
                            <div class="text-bg">
                                <h1><span class="blu">Bienvenidos</span><br>a D'Jenny Supermercado</h1>
                                <figure><img src="{{ asset('images/tendero.png') }}" alt="Aceite" class="banner-img"/></figure>
                                <a class="read_more" href="{{ route('login') }}">Comprar Ahora</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="container">
                        <div class="carousel-caption">
                            <div class="text-bg">
                                <h1><span class="blu">Productos Frescos</span><br>de D'Jenny Supermercado</h1>
                                <figure><img src="{{ asset('images/productos.png') }}" alt="Café" class="banner-img"/></figure>
                                <a class="read_more" href="{{ route('login') }}">Comprar Ahora</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="container">
                        <div class="carousel-caption">
                            <div class="text-bg">
                                <h1><span class="blu">Calidad Garantizada</span><br>en D'Jenny Supermercado</h1>
                                <figure><img src="{{ asset('images/pan.png') }}" alt="Pan" class="banner-img"/></figure>
                                <a class="read_more" href="{{ route('login') }}">Comprar Ahora</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <a class="carousel-control-prev" href="#banner1" role="button" data-slide="prev">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
                <span class="sr-only">Anterior</span>
            </a>
            <a class="carousel-control-next" href="#banner1" role="button" data-slide="next">
                <i class="fa fa-arrow-right" aria-hidden="true"></i>
                <span class="sr-only">Siguiente</span>
            </a>
        </div>
    </section>
    <div class="about">
        <div class="container">
            <div class="row d_flex">
                <div class="col-md-5">
                    <div class="about_img">
                        <figure><img src="{{ asset('images/tienda2.png') }}" alt="Arroz Supremo" class="about-img"/></figure>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="titlepage">
                        <h2>Acerca de Nuestra Tienda</h2>
                        <p>En D'Jenny Supermercado, nos dedicamos a ofrecer productos frescos y de calidad para las necesidades diarias de tu hogar. Con precios competitivos y un servicio excepcional, somos la elección preferida de la comunidad.</p>
                    </div>
                    <a class="read_more" href="{{ route('productos.index') }}">Ver Más</a>
                </div>
            </div>
        </div>
    </div>
    <div class="glasses">
        <div class="container">
            <div class="row">
                <div class="col-md-10 offset-md-1">
                    <div class="titlepage">
                        <h2>Nuestros Productos</h2>
                        <p>Descubre una selección aleatoria de 8 de nuestros productos activos.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                @forelse ($productos ?? [] as $index => $producto)
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                        <div class="glasses_box">
                            <figure>
                                <img src="{{ asset('storage/' . ($producto->imagen ?? 'images/placeholder.png')) }}" alt="{{ Str::title($producto->nombre ?? 'Producto') }}" class="glasses-img"/>
                            </figure>
                            <h3>{{ Str::title($producto->nombre ?? 'Producto') }}</h3>
                            <p class="description">{{ Str::title($producto->descripcion ?? 'Sin descripción') }}</p>
                            <p class="price"><span class="blu">COP</span>{{ number_format($producto->precio ?? 0, 0) }}</p>
                            <p>{{ Str::title($producto->categoria_nombre ?? 'Sin categoría') }}</p>
                        </div>
                    </div>
                @empty
                    <div class="col-md-12">
                        <p>No hay productos activos disponibles en este momento.</p>
                    </div>
                @endforelse
                <div class="col-md-12">
                    <a class="read_more" href="{{ route('productos.index') }}">Ver Todos los Productos</a>
                </div>
            </div>
        </div>
    </div>
    <div id="about" class="shop">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-5">
                    <div class="shop_img">
                        <figure><img src="{{ asset('images/fresco.png') }}" alt="Huevos" class="shop-img"/></figure>
                    </div>
                </div>
                <div class="col-md-7 padding_right0">
                    <div class="max_width">
                        <div class="titlepage">
                            <h2>Lo Mejor en D'Jenny Supermercado</h2>
                            <p>Explora nuestra amplia gama de productos frescos y de calidad. Desde alimentos básicos hasta artículos especializados, en D'Jenny encontrarás todo lo que necesitas para tu hogar con la mejor atención.</p>
                            <a class="read_more" href="{{ route('productos.index') }}">Comprar Ahora</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clients">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="titlepage">
                        <h2>Qué Dicen Nuestros Clientes</h2>
                        <p>Conoce una selección aleatoria de 8 opiniones de nuestros clientes.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div id="myCarousel" class="carousel slide clients_Carousel" data-ride="carousel">
                        <ol class="carousel-indicators">
                            @forelse ($reseñas ?? [] as $index => $reseña)
                                <li data-target="#myCarousel" data-slide-to="{{ $index }}" @if($index == 0) class="active" @endif></li>
                            @empty
                                <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                            @endforelse
                        </ol>
                        <div class="carousel-inner">
                            @forelse ($reseñas ?? [] as $index => $reseña)
                                <div class="carousel-item @if($index == 0) active @endif">
                                    <div class="container">
                                        <div class="carousel-caption">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="clients_box">
                                                        <figure><img src="{{ asset('images/persona.png') }}" alt="{{ Str::title($reseña->user->name ?? 'Anónimo') }}" onError="this.src='{{ asset('images/placeholder.png') }}'"/></figure>
                                                        <h3>{{ Str::title($reseña->user->name ?? 'Anónimo') }}</h3>
                                                        <div class="rating">
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                @if ($i <= ($reseña->calificacion ?? 0))
                                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                                @else
                                                                    <i class="fa fa-star-o" aria-hidden="true"></i>
                                                                @endif
                                                            @endfor
                                                        </div>
                                                        <p>{{ $reseña->comentario ?? 'Sin comentario' }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="carousel-item active">
                                    <div class="container">
                                        <div class="carousel-caption">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="clients_box">
                                                        <figure><img src="{{ asset('images/persona.png') }}" alt="Cliente" onError="this.src='{{ asset('images/placeholder.png') }}'"/></figure>
                                                        <h3>Sin Reseñas</h3>
                                                        <div class="rating">
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                <i class="fa fa-star-o" aria-hidden="true"></i>
                                                            @endfor
                                                        </div>
                                                        <p>Aún no hay reseñas disponibles.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                        <a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">
                            <i class="fa fa-angle-left"></i>
                        </a>
                        <a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
                            <i class="fa fa-angle-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="map_section">
        <div class="map-container">
            <iframe src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d3984.617385749955!2d-75.26511422502979!3d2.925829097050505!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zMsKwNTUnMzMuMCJOIDc1wrAxNSc0NS4xIlc!5e0!3m2!1ses!2sco!4v1748389528042!5m2!1ses!2sco" allowfullscreen loading="lazy" title="Ubicación de D'Jenny"></iframe>
        </div>
    </div>
    <footer>
        <div class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <ul class="location_icon">
                            <li><a href="https://www.google.com/maps/place/Cra+36A+%23+2E-57,+Neiva,+Huila,+Colombia/@2.9258443,-75.2625363,1740m/data=!3m1!1e3!4m6!3m5!1s0x8e3b740e5538b951:0xb3cc2cd1f8ad9fe6!8m2!3d2.925813!4d-75.262554!16s%2Fg%2F11x2m3tsgk?hl=es&entry=ttu&g_ep=EgoyMDI1MDYzMC4wIKXMDSoASAFQAw%3D%3D"><i class="fa fa-map-marker" aria-hidden="true"></i></a><br>Carrera 36A #2e-57, Neiva - Huila</li>
                            <li><a href="https://wa.me/573172343575?text=Hola%2C%20quiero%20más%20información%20sobre%20la%20tienda%20de%20abarrotes%20Donde%20Jenny%20que%20vi%20en%20el%20footer%20de%20su%20sitio."><i class="fa fa-phone" aria-hidden="true"></i></a><br>+57 317 2343 575</li>
                            <li><a href="https://instagram.com" target="_blank"><i class="fa fa-instagram" aria-hidden="true"></i></a><br>Instagram</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="copyright">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <p>© 2025 D'Jenny - Todos los derechos reservados.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <script defer src="{{ asset('html/js/jquery.min.js') }}"></script>
    <script defer src="{{ asset('html/js/popper.min.js') }}"></script>
    <script defer src="{{ asset('html/js/bootstrap.bundle.min.js') }}"></script>
    <script defer src="{{ asset('html/js/jquery.mCustomScrollbar.concat.min.js') }}"></script>
    <script defer src="{{ asset('html/js/custom.js') }}"></script>
    <script>
        window.addEventListener('load', function() {
            setTimeout(function() {
                const loader = document.querySelector('.loader_bg');
                loader.style.display = 'none';
            }, 2000);
        });
    </script>
</body>
</html>