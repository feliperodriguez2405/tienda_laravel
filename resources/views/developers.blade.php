<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipo de Desarrollo - SENA</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome para íconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="icon" href="{{ asset('images/djenny.png') }}" type="image/x-icon">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            color: #333;
        }
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=1920');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            text-align: center;
        }
        .hero-section h1 {
            font-size: 3.5rem;
            font-weight: bold;
        }
        .hero-section p {
            font-size: 1.2rem;
            max-width: 800px;
            margin: 0 auto;
        }
        .team-card {
            transition: transform 0.3s, box-shadow 0.3s;
            border: none;
            border-radius: 15px;
            overflow: hidden;
            background: white;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .team-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }
        .team-card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }
        .social-icons a {
            color: #333;
            font-size: 1.5rem;
            margin: 0 10px;
            transition: color 0.3s;
        }
        .social-icons a:hover {
            color: #007bff;
        }
        .framework-section {
            background: #343a40;
            color: white;
            padding: 50px 0;
            text-align: center;
        }
        .framework-section img {
            max-width: 150px;
            margin-bottom: 20px;
        }
        footer {
            background: #212529;
            color: white;
            padding: 20px 0;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1>Nuestro Equipo de Desarrollo</h1>
            <p>Somos un equipo de estudiantes apasionados del SENA, cursando el Tecnólogo en Análisis y Desarrollo de Software. Esta es nuestra primera página web, construida con dedicación y utilizando Laravel como framework principal.</p>
        </div>
    </section>

    <!-- Team Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Conoce a Nuestro Equipo</h2>
            <div class="row">
                <!-- Mauro Suarez Ariza -->
                <div class="col-md-3 mb-4">
                    <div class="team-card">
                        <img src="https://via.placeholder.com/300x250?text=Mauro+Suarez" alt="Mauro Suarez">
                        <div class="p-4">
                            <h5>Mauro Suarez Ariza</h5>
                            <p class="text-muted">Desarrollador Backend</p>
                            <p>Apasionado por construir sistemas robustos y escalables. Experto en la lógica del servidor y en optimizar el rendimiento de aplicaciones con Laravel.</p>
                            <div class="social-icons">
                                <a href="https://facebook.com" target="_blank"><i class="fab fa-facebook"></i></a>
                                <a href="https://github.com" target="_blank"><i class="fab fa-github"></i></a>
                                <a href="https://linkedin.com" target="_blank"><i class="fab fa-linkedin"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Jaider Stiven Molina Trujillo -->
                <div class="col-md-3 mb-4">
                    <div class="team-card">
                        <img src="https://via.placeholder.com/300x250?text=Jaider+Molina" alt="Jaider Molina">
                        <div class="p-4">
                            <h5>Jaider Stiven Molina Trujillo</h5>
                            <p class="text-muted">Desarrollador Frontend</p>
                            <p>Creativo y detallista, especializado en diseñar interfaces de usuario dinámicas y responsivas, garantizando una experiencia fluida.</p>
                            <div class="social-icons">
                                <a href="https://facebook.com" target="_blank"><i class="fab fa-facebook"></i></a>
                                <a href="https://github.com" target="_blank"><i class="fab fa-github"></i></a>
                                <a href="https://linkedin.com" target="_blank"><i class="fab fa-linkedin"></i></a>
                                <a href="https://tiktok.com" target="_blank"><i class="fab fa-tiktok"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Luis Felipe Bermudez -->
                <div class="col-md-3 mb-4">
                    <div class="team-card">
                        <img src="https://via.placeholder.com/300x250?text=Luis+Bermudez" alt="Luis Bermudez">
                        <div class="p-4">
                            <h5>Luis Felipe Bermudez</h5>
                            <p class="text-muted">Desarrollador de Base de Datos</p>
                            <p>Experto en diseñar y optimizar bases de datos, asegurando un manejo eficiente y seguro de la información.</p>
                            <div class="social-icons">
                                <a href="https://facebook.com" target="_blank"><i class="fab fa-facebook"></i></a>
                                <a href="https://github.com" target="_blank"><i class="fab fa-github"></i></a>
                                <a href="https://linkedin.com" target="_blank"><i class="fab fa-linkedin"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Alex Frank Chambo Ramos -->
                <div class="col-md-3 mb-4">
                    <div class="team-card">
                        <img src="https://via.placeholder.com/300x250?text=Alex+Chambo" alt="Alex Chambo">
                        <div class="p-4">
                            <h5>Alex Frank Chambo Ramos</h5>
                            <p class="text-muted">Desarrollador en Análisis y Consultoría</p>
                            <p>Enfocado en analizar requerimientos y proponer soluciones innovadoras para garantizar el éxito del proyecto.</p>
                            <div class="social-icons">
                                <a href="https://facebook.com" target="_blank"><i class="fab fa-facebook"></i></a>
                                <a href="https://github.com" target="_blank"><i class="fab fa-github"></i></a>
                                <a href="https://linkedin.com" target="_blank"><i class="fab fa-linkedin"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Framework Section -->
    <section class="framework-section">
        <div class="container">
            <img src="https://laravel.com/img/logomark.min.svg" alt="Laravel Logo">
            <h2>Construido con Laravel</h2>
            <p>Nuestro proyecto está desarrollado con Laravel, un framework PHP robusto y elegante que nos permitió crear una aplicación web dinámica y escalable. Este es nuestro primer proyecto, y estamos emocionados de mostrar nuestras habilidades como estudiantes del SENA.</p>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; 2025 Equipo de Desarrollo SENA. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Bootstrap JS and Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>