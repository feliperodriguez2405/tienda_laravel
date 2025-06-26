@extends('layouts.master')

@section('title', 'Djenny')

<!-- Barra de navegación -->
@section('navbar')
<a class="navbar-brand animate__animated animate__pulse" href="{{ route('user.dashboard') }}">
    <i class="bi bi-shop me-2"></i>Bienvenido a D'jenny
</a>
<button class="navbar-toggler"
    type="button"
    data-bs-toggle="collapse"
    data-bs-target="#navbarNav"
    aria-controls="navbarNav"
    aria-expanded="false"
    aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
</button>
<div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('user.dashboard') }}">
                <i class="bi bi-cart me-1"></i>Productos
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('user.orders') }}">
                <i class="bi bi-basket me-1"></i>Mis Pedidos
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('user.reviews') }}">
                <i class="bi bi-basket me-1"></i> Reseñas
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('user.profile') }}">
                <i class="bi bi-person me-1"></i>Perfil
            </a>
        </li>
        <li class="nav-item">
            <div class="cart-container position-relative">
                <a href="{{ route('user.cart') }}" class="nav-link cart-btn">
                    <i class="bi bi-cart3 me-1"></i>Carrito
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        {{ session('cart') ? count(session('cart')) : 0 }}
                        <span class="visually-hidden">items en carrito</span>
                    </span>
                </a>
            </div>
        </li>
        @auth
        <li class="nav-item">
            <a class="nav-link text-danger logout-link"
                href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-right me-1"></i>Cerrar Sesión
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li>
        @else
        <li class="nav-item">
            <a class="nav-link" href="{{ route('login') }}">
                <i class="bi bi-box-arrow-in-right me-1"></i>Iniciar Sesión
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('register') }}">
                <i class="bi bi-person-plus me-1"></i>Registrarse
            </a>
        </li>
        @endauth
    </ul>
</div>
@endsection

<!-- Footer -->
@section('footer')
<div class="container">
    <p class="mb-4">© {{ date('Y') }} D'jenny - Todos los derechos reservados.</p>
</div>
@endsection

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/app.js') }}"></script>

@stack('scripts')