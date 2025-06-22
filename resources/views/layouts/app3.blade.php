@extends('layouts.master')

@section('title', 'Panel de Cajero')

<!-- Barra de navegación -->
@section('navbar')
    <a class="navbar-brand animate__animated animate__pulse" href="{{ route('cajero.dashboard') }}">
        <i class="bi bi-cash-stack me-2"></i>Panel de Cajero
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
                <a class="nav-link {{ Route::is('cajero.sale') ? 'active' : '' }}" href="{{ route('cajero.sale') }}">
                    <i class="bi bi-cart-check me-1"></i>Registrar Venta
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::is('cajero.transactions') ? 'active' : '' }}" href="{{ route('cajero.transactions') }}">
                    <i class="bi bi-receipt me-1"></i>Transacciones
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::is('cajero.close') ? 'active' : '' }}" href="{{ route('cajero.close') }}">
                    <i class="bi bi-cash me-1"></i>Cierre de Caja
                </a>
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
            @endauth
        </ul>
    </div>
@endsection

<!-- Footer -->
@section('footer')
<div class="text-center">
    <p class="mb-4">© {{ date('Y') }} Supermercado Online - Panel de Cajero</p>
</div>
@endsection