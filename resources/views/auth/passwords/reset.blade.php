@extends('layouts.form')

@section('content')
<div class="login-container">
    <a href="{{ route('login') }}" class="back-arrow">←</a>
    <div class="login-header">
        <img src="{{ asset('images/djenny.png') }}" alt="Logo de D'Jenny">
        <h2>{{ __('Restablecer Contraseña') }}</h2>
    </div>

    {{-- Mostrar mensaje de éxito si la contraseña fue restablecida --}}
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

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group">
            <label for="email">{{ __('Correo Electrónico') }}</label>
            <input id="email" 
                   type="email" 
                   class="form-control @error('email') is-invalid @enderror" 
                   name="email" 
                   value="{{ $email ?? old('email') }}" 
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
            <label for="password">{{ __('Nueva Contraseña') }}</label>
            <input id="password" 
                   type="password" 
                   class="form-control @error('password') is-invalid @enderror" 
                   name="password" 
                   required 
                   autocomplete="new-password">
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password-confirm">{{ __('Confirmar Nueva Contraseña') }}</label>
            <input id="password-confirm" 
                   type="password" 
                   class="form-control" 
                   name="password_confirmation" 
                   required 
                   autocomplete="new-password">
        </div>

        <button type="submit" class="btn-submit">{{ __('Restablecer Contraseña') }}</button>
    </form>
</div>
@endsection