@extends('layouts.form')

@section('content')
<a href="{{ url('/') }}" class="back-arrow"><i class="bi bi-arrow-left"></i></a>

<div class="login-header">
    <img src="{{ asset('images/djenny.png') }}" alt="Logo">
    <h2>Iniciar Sesión</h2>
</div>

@if(session('status'))
    <div class="alert">
        {{ session('status') }}
    </div>
@endif

<form method="POST" action="{{ route('login') }}">
    @csrf

    <!-- Correo electrónico -->
    <div class="form-group">
        <label for="email">Correo Electrónico</label>
        <input id="email" 
               type="email" 
                placeholder="ingresa tu correo electrónico"
               class="form-control @error('email') is-invalid @enderror" 
               name="email" 
               value="{{ old('email') }}" 
               required 
               autocomplete="email" 
               autofocus>
        @error('email')
            <span class="invalid-feedback" role="alert" style="color: #c62828;">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <!-- Contraseña -->
    <div class="form-group">
        <label for="password">Contraseña</label>
        <input id="password" 
               type="password" 
               placeholder="Ingresa tu contraseña"
               class="form-control @error('password') is-invalid @enderror" 
               name="password" 
               required 
               autocomplete="current-password">
        @error('password')
            <span class="invalid-feedback" role="alert" style="color: #c62828;">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <!-- Recordarme -->
    <div class="form-group" style="text-align: center;">
        <label class="form-check-label">
            <input class="form-check-input" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
            Recordarme
        </label>
    </div>

    <!-- Botón de Iniciar Sesión -->
    <div class="form-group">
        <button type="submit" class="btn-submit">
            Iniciar Sesión
        </button>
    </div>

    <!-- Enlace a contraseña olvidada -->
    @if (Route::has('password.request'))
        <div class="extra-links">
            <a href="{{ route('password.request') }}">
                ¿Olvidaste tu contraseña?
            </a>
        </div>
    @endif
</form>

<!-- Registro -->
@if (Route::has('register'))
    <div class="register-box">
        <span>¿No tienes una cuenta?</span>
        <a href="{{ route('register') }}">Registrarse</a>
    </div>
@endif
@endsection
