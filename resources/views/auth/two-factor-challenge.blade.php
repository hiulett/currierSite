<x-guest-layout>
    <div x-data="{ recovery: false }">
        <div class="text-center mt-2 mb-4">
            <h2 class="fw-black text-dark uppercase tracking-tight">{{ __('Doble Factor') }}</h2>

            <p class="lead" x-show="! recovery">
                {{ __('Por favor, confirma el acceso a tu cuenta ingresando el código de autenticación proporcionado por tu aplicación de autenticador.') }}
            </p>

            <p class="lead" x-show="recovery" style="display: none;">
                {{ __('Por favor, confirma el acceso a tu cuenta ingresando uno de tus códigos de recuperación de emergencia.') }}
            </p>
        </div>

        <form method="POST" action="{{ route('two-factor.login') }}">
            @csrf

            <div class="mb-3" x-show="! recovery">
                <label class="form-label" for="code">{{ __('Código de Autenticación') }}</label>
                <input id="code" class="form-control form-control-lg" type="text" name="code" autofocus x-ref="code" autocomplete="one-time-code" />
            </div>

            <div class="mb-3" x-show="recovery" style="display: none;">
                <label class="form-label" for="recovery_code">{{ __('Código de Recuperación') }}</label>
                <input id="recovery_code" class="form-control form-control-lg" type="text" name="recovery_code" x-ref="recovery_code" autocomplete="one-time-code" />
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <button type="button" class="btn btn-link text-muted small p-0 text-decoration-none"
                                x-show="! recovery"
                                x-on:click="
                                    recovery = true;
                                    $nextTick(() => { $refs.recovery_code.focus() })
                                ">
                    {{ __('Usar un código de recuperación') }}
                </button>

                <button type="button" class="btn btn-link text-muted small p-0 text-decoration-none"
                                x-show="recovery"
                                x-on:click="
                                    recovery = false;
                                    $nextTick(() => { $refs.code.focus() })
                                "
                                style="display: none;">
                    {{ __('Usar un código de autenticador') }}
                </button>
            </div>

            <div class="d-grid gap-2 mt-4">
                <button type="submit" class="btn btn-lg btn-primary fw-bold text-uppercase">
                    {{ __('Entrar') }}
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
