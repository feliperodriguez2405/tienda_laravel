@extends('layouts.form')

@section('content')
<div class="login-container">
    <a href="{{ route('login') }}" class="back-arrow"><i class="bi bi-arrow-left"></i></a>

    <div class="login-header">
        <img src="{{ asset('images/djenny.png') }}" alt="Logo de D'Jenny">
        <h2>{{ __('Restablecer Contraseña') }}</h2>
    </div>

    {{-- Mostrar mensaje de éxito si el enlace fue enviado --}}
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    {{-- Mostrar errores de validación si los hay --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0" style="list-style: none; padding-left: 0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="form-group">
            <label for="email">{{ __('Correo Electrónico') }}</label>
            <input id="email" 
                   type="email" 
                   class="form-control @error('email') is-invalid @enderror" 
                   name="email" 
                   value="{{ old('email') }}" 
                   placeholder="ejemplo@correo.com"
                   required 
                   autocomplete="email" 
                   autofocus>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <button type="submit" class="btn-submit">
                {{ __('Enviar Enlace de Restablecimiento') }}
            </button>
        </div>
    </form>

    <div class="register-box">
        <span>{{ __('¿Recuerdas tu contraseña?') }}</span>
        <a href="{{ route('login') }}">{{ __('Iniciar Sesión') }}</a>
    </div>
</div>
@endsection