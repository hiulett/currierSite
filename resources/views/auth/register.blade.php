<x-guest-layout>
    <div class="text-center mt-2 mb-4">
        <h2 class="fw-black text-dark uppercase tracking-tight">{{ __('Crear Cuenta') }}</h2>
        <p class="lead">
            Únete a la red logística más rápida
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label" for="name">{{ __('Nombre Completo') }}</label>
            <input id="name" class="form-control form-control-lg @error('name') is-invalid @enderror" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Ej: Juan Pérez" />
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label" for="email">{{ __('Correo Electrónico') }}</label>
            <input id="email" class="form-control form-control-lg @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="tu@email.com" />
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label" for="password">{{ __('Contraseña') }}</label>
                <input id="password" class="form-control form-control-lg @error('password') is-invalid @enderror" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label" for="password_confirmation">{{ __('Confirmar') }}</label>
                <input id="password_confirmation" class="form-control form-control-lg" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
            </div>
        </div>

        <div class="mb-3">
            <div class="form-check align-items-center">
                <input id="terms" type="checkbox" class="form-check-input" name="terms" required>
                <label class="form-check-label text-small text-muted" for="terms">
                    {{ __('Acepto los') }} <a href="#">{{ __('Términos y Condiciones') }}</a>
                </label>
            </div>
        </div>

        <div class="d-grid gap-2 mt-4">
            <button type="submit" class="btn btn-lg btn-primary fw-bold text-uppercase">
                {{ __('Empezar Ahora') }}
            </button>
        </div>

        <div class="text-center mt-3">
            <span class="text-muted small">{{ __('¿Ya tienes una cuenta?') }}</span>
            <a href="{{ route('login') }}" class="small font-bold text-primary">{{ __('Inicia sesión aquí') }}</a>
        </div>
    </form>
</x-guest-layout>
