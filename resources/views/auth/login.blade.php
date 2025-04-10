@extends('layouts.welcome')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card login-card">
                <div class="card-header">{{ __('Iniciar Sesión') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Correo electrónico -->
                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Dirección de Correo Electrónico') }}</label>
                            <div class="col-md-6">
                                <input id="email" 
                                       type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       required 
                                       autocomplete="email" 
                                       autofocus>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Contraseña -->
                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Contraseña') }}</label>
                            <div class="col-md-6">
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
                        </div>

                        <!-- Recordarme -->
                        <div class="row mb-3 d-flex justify-content-center">
                            <div class="col-md-6">
                                <div class="form-check d-flex align-items-center justify-content-center">
                                    <input class="form-check-input me-2" 
                                           type="checkbox" 
                                           name="remember" 
                                           id="remember" 
                                           {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">
                                        {{ __('Recuérdame') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="row mb-0 d-flex justify-content-center">
                            <div class="col-md-6 d-flex justify-content-center gap-3">
                                <button type="submit" class="btn btn-primary login-btn">
                                    {{ __('Iniciar Sesión') }}
                                </button>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="btn btn-secondary register-btn">
                                        {{ __('Registrarse') }}
                                    </a>
                                @endif
                            </div>
                        </div>

                        <!-- Enlace de Contraseña Olvidada -->
                        @if (Route::has('password.request'))
                            <div class="row mt-3 d-flex justify-content-center">
                                <div class="col-md-6 text-center">
                                    <a class="btn btn-link forgot-link" href="{{ route('password.request') }}">
                                        {{ __('¿Olvidaste tu Contraseña?') }}
                                    </a>
                                </div>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Estilos mínimos con animaciones discretas */
    .login-card {
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s ease-in-out;
    }

    .login-card:hover {
        transform: translateY(-2px);
    }

    .card-header {
        font-weight: 600;
        text-align: center;
        padding: 1rem;
    }

    .card-body {
        padding: 1.5rem;
    }

    .form-control {
        transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }

    .form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 5px rgba(128, 189, 255, 0.5);
    }

    .login-btn, .register-btn {
        padding: 0.5rem 1.5rem;
        transition: background-color 0.2s ease-in-out, transform 0.2s ease-in-out;
    }

    .login-btn:hover, .register-btn:hover {
        transform: scale(1.02);
    }

    .forgot-link {
        padding: 0.5rem;
        transition: color 0.2s ease-in-out;
    }

    .forgot-link:hover {
        color: #0056b3;
        text-decoration: underline;
    }

    .register-btn {
        background-color: #6c757d; /* Gris por defecto para el botón de registro */
        color: white;
    }

    .register-btn:hover {
        background-color: #5a6268; /* Un gris más oscuro al pasar el mouse */
    }
</style>
@endsection
