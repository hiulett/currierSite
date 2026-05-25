<x-guest-layout>
    <div class="text-center mt-2 mb-4">
        <h2 class="fw-black text-dark uppercase tracking-tight">{{ __('Iniciar Sesión') }}</h2>
        <p class="lead">
            Bienvenido de vuelta al sistema
        </p>
    </div>

    @if (session('status'))
        <div class="alert alert-success mb-3" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label" for="email">{{ __('Correo Electrónico') }}</label>
            <input id="email" class="form-control form-control-lg @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="tu@email.com" />
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label" for="password">{{ __('Contraseña') }}</label>
            <input id="password" class="form-control form-control-lg @error('password') is-invalid @enderror" type="password" name="password" required autocomplete="current-password" placeholder="Ingresa tu contraseña" />
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="mt-1 text-end">
                @if (Route::has('password.request'))
                    <a class="small text-muted" href="{{ route('password.request') }}">
                        {{ __('¿Olvidaste tu contraseña?') }}
                    </a>
                @endif
            </div>
        </div>

        <div class="mb-3">
            <div class="form-check align-items-center">
                <input id="remember_me" type="checkbox" class="form-check-input" name="remember" checked>
                <label class="form-check-label text-small" for="remember_me">{{ __('Recordarme en este equipo') }}</label>
            </div>
        </div>

        <div class="d-grid gap-2 mt-4">
            <button type="submit" class="btn btn-lg btn-primary">
                {{ __('Entrar al Sistema') }}
            </button>
        </div>

        @if (Route::has('register'))
            <div class="text-center mt-3">
                <span class="text-muted small">¿No tienes cuenta?</span> <a href="{{ route('register') }}" class="small font-bold">Regístrate aquí</a>
            </div>
        @endif
    </form>
</x-guest-layout>
