<x-guest-layout>
    <div class="text-center mt-2 mb-4">
        <h2 class="fw-black text-dark uppercase tracking-tight">{{ __('Nueva Contraseña') }}</h2>
        <p class="lead">
            Crea una clave segura para tu cuenta
        </p>
    </div>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="mb-3">
            <label class="form-label" for="email">{{ __('Correo Electrónico') }}</label>
            <input id="email" class="form-control form-control-lg @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus readonly />
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label" for="password">{{ __('Nueva Contraseña') }}</label>
            <input id="password" class="form-control form-control-lg @error('password') is-invalid @enderror" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label" for="password_confirmation">{{ __('Confirmar Nueva Contraseña') }}</label>
            <input id="password_confirmation" class="form-control form-control-lg" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
        </div>

        <div class="d-grid gap-2 mt-4">
            <button type="submit" class="btn btn-lg btn-primary fw-bold text-uppercase">
                {{ __('Restablecer Contraseña') }}
            </button>
        </div>
    </form>
</x-guest-layout>
