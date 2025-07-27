@extends('layouts.app')

@section('title', 'Configurar Correo de Notificaciones')

@section('content')
<div class="container py-4">
    <h2 class="text-primary fw-bold mb-4">Configurar Correo de Notificaciones</h2>

    <!-- Alerta sobre cómo obtener la contraseña -->
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <strong>Nota:</strong> La contraseña requerida no es la contraseña de tu cuenta de Gmail. Debes generar una <strong>contraseña de aplicación</strong> habilitando la autenticación en dos pasos en los ajustes de tu cuenta de Google. Sigue estos pasos:
        <ol>
            <li>Ve a la configuración de tu cuenta de Google.</li>
            <li>Activa la autenticación en dos pasos en la sección de Seguridad.</li>
            <li>Genera una contraseña de aplicación para tu aplicación (Tienda D'jenny).</li>
            <li>Usa esa contraseña en el campo de abajo.</li>
        </ol>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <!-- Mostrar alertas -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('smtp_success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('smtp_success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('smtp_error'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{ session('smtp_error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Mostrar estado actual del correo -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title">Estado Actual del Correo</h5>
            <p><strong>Correo Configurado:</strong> {{ $correo_notificaciones }}</p>
            <p><strong>Contraseña Configurada:</strong> {{ config('mail.mailers.smtp.password') ? config('mail.mailers.smtp.password') : 'No' }}</p>
            <p><strong>Estado de la Conexión SMTP:</strong> 
                @if (session('smtp_success'))
                    <span class="text-success">Conexión verificada</span>
                @elseif (session('smtp_error'))
                    <span class="text-danger">Conexión fallida</span>
                @else
                    <span class="text-success">Conexión no probada en esta vista</span>
                @endif
            </p>
        </div>
    </div>

    <!-- Formulario para configurar correo -->
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.proveedores.guardar-correo') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="correo_notificaciones" class="form-label">Correo para Notificaciones</label>
                    <input type="email" 
                           class="form-control @error('correo_notificaciones') is-invalid @enderror" 
                           id="correo_notificaciones" 
                           name="correo_notificaciones" 
                           value="{{ old('correo_notificaciones', $correo_notificaciones) }}" 
                           required>
                    @error('correo_notificaciones')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password_notificaciones" class="form-label">Contraseña</label>
                    <input type="password" 
                           class="form-control @error('password_notificaciones') is-invalid @enderror" 
                           id="password_notificaciones" 
                           name="password_notificaciones" 
                           required>
                    @error('password_notificaciones')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="{{ route('proveedores.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>
@endsection
