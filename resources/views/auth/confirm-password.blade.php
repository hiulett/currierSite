<x-guest-layout>
    <div class="text-center mt-2 mb-4">
        <h2 class="fw-black text-dark uppercase tracking-tight">{{ __('Confirmar Seguridad') }}</h2>
        <p class="lead">
            {{ __('Esta es una zona segura de la aplicación. Por favor, confirma tu contraseña antes de continuar.') }}
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label" for="password">{{ __('Contraseña') }}</label>
            <input id="password" class="form-control form-control-lg @error('password') is-invalid @enderror" type="password" name="password" required autocomplete="current-password" autofocus />
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-grid gap-2 mt-4">
            <button type="submit" class="btn btn-lg btn-primary fw-bold text-uppercase">
                {{ __('Confirmar') }}
            </button>
        </div>
    </form>
</x-guest-layout>
