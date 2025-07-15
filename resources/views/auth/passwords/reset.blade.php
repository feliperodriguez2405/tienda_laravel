@extends('layouts.form')

@section('content')
<div class="login-container">
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
            <label for="email">Correo Electrónico</label>
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
            <label for="password">Nueva Contraseña</label>
            <input id="password" 
                   type="password" 
                   class="form-control @error('password') is-invalid @enderror" 
                   name="password" 
                   required 
                   autocomplete="new-password">
            <small class="form-text text-muted">Ejemplo: Contraseña123 (mínimo 8 caracteres)</small>
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password-confirm">Confirmar Nueva Contraseña</label>
            <input id="password-confirm" 
                   type="password" 
                   class="form-control" 
                   name="password_confirmation" 
                   required 
                   autocomplete="new-password">
        </div>

        <button type="submit" class="btn-submit">Restablecer Contraseña</button>
    </form>
</div>
@endsection