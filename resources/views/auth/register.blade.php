@extends('layouts.form')

@section('content')
<a href="{{ route('login') }}" class="back-arrow"><i class="bi bi-arrow-left"></i></a>

<div class="login-header">
    <img src="{{ asset('images/djenny.png') }}" alt="Logo de D'Jenny">
    <h2>Crear Cuenta</h2>
</div>

@if ($errors->any())
    <div class="alert">
        <ul class="mb-0" style="list-style: none; padding-left: 0;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('register') }}">
    @csrf

    <!-- Nombre -->
    <div class="form-group">
        <label for="name">Nombre</label>
        <input id="name" 
               type="text" 
               class="form-control @error('name') is-invalid @enderror" 
               name="name" 
               value="{{ old('name') }}" 
               placeholder="Ingresa tu nombre completo"
               required 
               autocomplete="name" 
               autofocus>
    </div>

    <!-- Correo Electrónico -->
    <div class="form-group">
        <label for="email">Correo Electrónico</label>
        <input id="email" 
               type="email" 
               class="form-control @error('email') is-invalid @enderror" 
               name="email" 
               value="{{ old('email') }}" 
               placeholder="ejemplo@correo.com"
               required 
               autocomplete="email">
    </div>

    <!-- Contraseña -->
    <div class="form-group">
        <label for="password">Contraseña</label>
        <input id="password" 
               type="password" 
               class="form-control @error('password') is-invalid @enderror" 
               name="password" 
               placeholder="Crea una contraseña segura"
               required 
               autocomplete="new-password">
    </div>

    <!-- Confirmar Contraseña -->
    <div class="form-group">
        <label for="password-confirm">Confirmar Contraseña</label>
        <input id="password-confirm" 
               type="password" 
               class="form-control" 
               name="password_confirmation" 
               placeholder="Repite tu contraseña"
               required 
               autocomplete="new-password">
    </div>

    <!-- Botón -->
    <div class="form-group">
        <button type="submit" class="btn-submit">
            Registrar
        </button>
    </div>
</form>

<!-- Caja con enlace para iniciar sesión -->
<div class="register-box">
    <span>¿Ya tienes una cuenta?</span>
    <a href="{{ route('login') }}">Iniciar Sesión</a>
</div>
@endsection
