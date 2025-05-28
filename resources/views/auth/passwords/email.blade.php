@extends('layouts.form')

@section('content')
<a href="{{ route('login') }}" class="back-arrow"><i class="bi bi-arrow-left"></i></a>

<div class="login-header">
    <img src="{{ asset('images/djenny.png') }}" alt="Logo de D'Jenny">
    <h2>Restablecer Contraseña</h2>
</div>

@if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert">
        <ul class="mb-0" style="list-style: none; padding-left: 0;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('password.email') }}">
    @csrf

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
               autocomplete="email" 
               autofocus>
    </div>

    <!-- Botón -->
    <div class="form-group">
        <button type="submit" class="btn-submit">
            Enviar Enlace de Restablecimiento
        </button>
    </div>
</form>

<!-- Caja con enlace para volver -->
<div class="register-box">
    <span>¿Recuerdas tu contraseña?</span>
    <a href="{{ route('login') }}">Iniciar Sesión</a>
</div>
@endsection

