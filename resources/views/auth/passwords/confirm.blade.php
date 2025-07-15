@extends('layouts.form')

@section('content')
<div class="login-container">
    <div class="login-header">
        <img src="{{ asset('images/djenny.png') }}" alt="Logo de D'Jenny">
        <h2>Confirmar Contraseña</h2>
    </div>

    <p>Por favor, confirme su contraseña antes de continuar.</p>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0" style="list-style: none; padding-left: 0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="form-group">
            <label for="password">Contraseña</label>
            <input id="password" 
                   type="password" 
                   class="form-control @error('password') is-invalid @enderror" 
                   name="password" 
                   required 
                   autocomplete="current-password">
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <button type="submit" class="btn-submit">
                Confirmar Contraseña
            </button>
            @if (Route::has('password.request'))
                <a class="btn btn-link" href="{{ route('password.request') }}">
                    ¿Olvidaste tu Contraseña?
                </a>
            @endif
        </div>
    </form>
</div>
@endsection