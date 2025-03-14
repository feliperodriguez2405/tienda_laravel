@extends('layouts.app2')

@section('title', 'Panel de Usuario')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <!-- Sidebar -->
            <div class="list-group">
                <a href="{{ route('user.dashboard') }}" class="list-group-item list-group-item-action active">
                    <i class="bi bi-house-door"></i> Inicio
                </a>
                <a href="{{ route('user.products') }}" class="list-group-item list-group-item-action">
                    <i class="bi bi-cart"></i> Ver Productos
                </a>
                <a href="{{ route('user.orders') }}" class="list-group-item list-group-item-action">
                    <i class="bi bi-basket"></i> Mis Pedidos
                </a>
                <a href="{{ route('user.settings') }}" class="list-group-item list-group-item-action">
                    <i class="bi bi-gear"></i> Configuración
                </a>
                <a href="{{ route('logout') }}" class="list-group-item list-group-item-action text-danger"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>

        <div class="col-md-9">
            <!-- Contenido principal -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5><i class="bi bi-house-door"></i> Bienvenido a Tu Supermercado</h5>
                </div>
                <div class="card-body">
                    <p class="lead">Hola, <strong>{{ Auth::user()->name }}</strong>. Aquí puedes ver los productos y gestionar tus compras.</p>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border-success mb-3">
                                <div class="card-header bg-success text-white">Productos Disponibles</div>
                                <div class="card-body">
                                    <p class="card-text">Explora y agrega productos a tu carrito.</p>
                                    <a href="{{ route('user.products') }}" class="btn btn-success">Ver Productos</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-info mb-3">
                                <div class="card-header bg-info text-white">Mis Pedidos</div>
                                <div class="card-body">
                                    <p class="card-text">Consulta el estado de tus pedidos anteriores.</p>
                                    <a href="{{ route('user.orders') }}" class="btn btn-info">Ver Pedidos</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <p class="text-muted text-center">© {{ date('Y') }} Supermercado Ipiranga | Usuario</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
