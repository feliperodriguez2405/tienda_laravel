@extends('layouts.app')

@section('title', 'Inicio')

@section('content')
<div class="container">
    <h1>Bienvenido a D'Jenny Supermercado</h1>

    <!-- Contenido según el rol -->
    @if (auth()->check())
        @if (auth()->user()->role === 'admin')
            <div class="alert alert-info">
                <h3>Panel de Administrador</h3>
                <p>Como administrador, puedes gestionar usuarios y más.</p>
                <a href="{{ route('users.index') }}" class="btn btn-primary">Gestionar Usuarios</a>
            </div>
        @elseif (auth()->user()->role === 'cajero')
            <div class="alert alert-info">
                <h3>Panel de Cajero</h3>
                <p>Registra ventas y gestiona transacciones.</p>
                <a href="#" class="btn btn-primary">Registrar Venta</a> <!-- Ruta ficticia, ajusta según necesidad -->
            </div>
        @else
            <div class="alert alert-info">
                <h3>Panel de Usuario</h3>
                <p>Consulta tus compras y disfruta de nuestras ofertas.</p>
                <a href="#" class="btn btn-primary">Ver Mis Compras</a> <!-- Ruta ficticia, ajusta según necesidad -->
            </div>
        @endif
    @else
        <p>Por favor, inicia sesión para acceder a las funciones.</p>
    @endif

    <!-- Contenido general (como en tu ejemplo anterior) -->
    <div class="content-grid mt-4">
        <div class="content-card">
            <strong>Misión</strong>
            <p>Ofrecer productos frescos y de calidad con un servicio excepcional.</p>
        </div>
        <div class="content-card">
            <strong>Visión</strong>
            <p>Ser el supermercado preferido de la comunidad.</p>
        </div>
        <div class="content-card">
            <strong>Nuestro Sistema</strong>
            <p>Gestiona eficientemente nuestro inventario y ventas.</p>
        </div>
    </div>
</div>

<style>
    .content-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }
    .content-card {
        padding: 15px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #f9f9f9;
    }
</style>
@endsection