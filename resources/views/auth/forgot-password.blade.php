<x-guest-layout>
    <div class="text-center mt-2 mb-4">
        <h2 class="fw-black text-dark uppercase tracking-tight">{{ __('Recuperar Acceso') }}</h2>
        <p class="lead">
            {{ __('¿Olvidaste tu contraseña? No hay problema. Solo dinos tu dirección de correo electrónico y te enviaremos un enlace para restablecerla.') }}
        </p>
    </div>

    @if (session('status'))
        <div class="alert alert-success mb-4 border-0 shadow-sm" role="alert">
            <div class="alert-message">
                {{ session('status') }}
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label" for="email">{{ __('Correo Electrónico') }}</label>
            <input id="email" class="form-control form-control-lg @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="tu@email.com" />
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-grid gap-2 mt-4">
            <button type="submit" class="btn btn-lg btn-primary fw-bold text-uppercase">
                {{ __('Enviar Enlace de Recuperación') }}
            </button>
        </div>

        <div class="text-center mt-3">
            <a href="{{ route('login') }}" class="small font-bold text-muted">{{ __('Volver al inicio de sesión') }}</a>
        </div>
    </form>
</x-guest-layout>
