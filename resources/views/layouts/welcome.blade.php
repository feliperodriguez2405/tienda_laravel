<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>D'Jenny Supermercado - @yield('title', 'Autenticación')</title>

    <!-- CSRF Token (necesario para formularios en Laravel) -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Styles -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e7eb 100%);
            color: #2d3436;
            line-height: 1.6;
            overflow-x: hidden;
            padding-bottom: 60px; /* Espacio para el footer flotante */
        }

        /* Header */
        .auth-header {
            background: linear-gradient(90deg, #343a40 0%, #495057 100%);
            padding: 1rem 2rem;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 10;
        }

        .auth-header .logo {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
        }

        .auth-header .logo img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: 3px solid white;
        }

        .auth-header .logo span {
            color: #e9ecef;
            font-size: 2rem;
            font-weight: 700;
            text-transform: uppercase;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        }

        /* Contenedor Principal */
        .auth-container {
            max-width: 600px;
            margin: 2rem auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
            padding: 2rem;
        }

        .auth-container::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 150px;
            height: 150px;
            background: url('{{ asset('images/cart-icon.png') }}') no-repeat center;
            background-size: 80%;
            opacity: 0.1;
            transform: rotate(15deg);
        }

        .card-header {
            background: linear-gradient(90deg, #343a40 0%, #495057 100%);
            color: #e9ecef;
            text-align: center;
            padding: 1rem;
            font-size: 1.5rem;
            font-weight: 600;
            border-radius: 10px 10px 0 0;
            margin: -2rem -2rem 1.5rem -2rem;
        }

        .card-body {
            padding: 1rem;
        }

        /* Formularios */
        .form-control {
            border-radius: 5px;
            border: 1px solid #ddd;
            padding: 0.75rem;
            transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .form-control:focus {
            border-color: #343a40;
            box-shadow: 0 0 5px rgba(52, 58, 64, 0.3);
            outline: none;
        }

        .btn-primary {
            background: #343a40;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 5px;
            font-weight: 600;
            transition: background-color 0.2s ease, transform 0.2s ease;
        }

        .btn-primary:hover {
            background: #495057;
            transform: scale(1.05);
        }

        .btn-link {
            color: #343a40;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .btn-link:hover {
            color: #495057;
            text-decoration: underline;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
        }

        /* Footer Flotante */
        .auth-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: linear-gradient(90deg, #343a40 0%, #495057 100%);
            color: #e9ecef;
            text-align: center;
            padding: 1rem;
            font-size: 0.9rem;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        /* Detalles Temáticos */
        .store-decoration {
            position: absolute;
            bottom: -10px;
            left: -10px;
            width: 100px;
            height: 100px;
            background: url('{{ asset('images/shopping-bag.png') }}') no-repeat center;
            background-size: 60%;
            opacity: 0.05;
            z-index: 0;
        }

        @media (max-width: 768px) {
            .auth-container {
                margin: 1rem;
                padding: 1rem;
            }

            .auth-header .logo span {
                font-size: 1.5rem;
            }

            .auth-header .logo img {
                width: 50px;
                height: 50px;
            }

            .auth-footer {
                font-size: 0.8rem;
                padding: 0.75rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="auth-header">
        <div class="logo">
            <img src="{{ asset('images/tienda.jpg') }}" alt="D'Jenny Logo">
            <span>D'Jenny</span>
        </div>
        <!-- Enlace a Inicio corregido -->
        <a href="{{ url('/') }}" class="btn btn-link">
            <i class="bi bi-house-door"></i> Inicio
        </a>
    </header>

    <!-- Contenedor de Autenticación -->
    <div class="auth-container">
        @yield('content')
        <div class="store-decoration"></div>
    </div>

    <!-- Footer Flotante -->
    <footer class="auth-footer">
        © {{ date('Y') }} Supermercado D'Jenny. Todos los derechos reservados.
    </footer>
</body>
</html>