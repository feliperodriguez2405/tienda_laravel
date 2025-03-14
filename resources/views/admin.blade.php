@extends('layouts.app')

@section('title', 'Panel de Administración')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <!-- Sidebar -->
            <div class="list-group">
                <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action active">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <a href="{{ route('productos.index') }}" class="list-group-item list-group-item-action">
                    <i class="bi bi-box-seam"></i> Gestionar Productos
                </a>
                <a href="{{ route('categorias.index') }}" class="list-group-item list-group-item-action">
                    <i class="bi bi-tags"></i> Gestionar Categorías
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
                    <h5><i class="bi bi-speedometer2"></i> Panel de Administración</h5>
                </div>
                <div class="card-body">
                    <p class="lead">Bienvenido, <strong>{{ Auth::user()->name }}</strong>. Aquí puedes administrar la tienda.</p>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border-info mb-3">
                                <div class="card-header bg-info text-white">Productos</div>
                                <div class="card-body">
                                    <p class="card-text">Administra los productos disponibles en la tienda.</p>
                                    <a href="{{ route('productos.index') }}" class="btn btn-info">Ver Productos</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-warning mb-3">
                                <div class="card-header bg-warning text-dark">Categorías</div>
                                <div class="card-body">
                                    <p class="card-text">Gestiona las categorías de productos.</p>
                                    <a href="{{ route('categorias.index') }}" class="btn btn-warning">Ver Categorías</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <p class="text-muted text-center">© {{ date('Y') }} Tienda Online | Administrador</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
