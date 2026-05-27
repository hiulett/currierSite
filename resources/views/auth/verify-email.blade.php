<x-guest-layout>
    <div class="text-center mb-4">
        <div class="mb-4">
            <div class="stat d-inline-block bg-primary-light text-primary rounded-circle p-3 shadow-sm" style="width: 80px; height: 80px;">
                <i data-feather="mail" style="width: 40px; height: 40px; stroke-width: 1.5px;"></i>
            </div>
        </div>

        <h2 class="fw-black text-dark uppercase tracking-tight mb-2">{{ __('¡Casi listo!') }}</h2>
        <p class="text-muted mx-auto" style="max-width: 320px;">
            {{ __('Hemos enviado un enlace de confirmación a tu correo. Por favor, revisa tu bandeja de entrada (y la carpeta de spam).') }}
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success alert-dismissible shadow-sm mb-4 border-0" role="alert">
            <div class="alert-message">
                <i class="align-middle me-1" data-feather="check-circle" style="width: 16px;"></i>
                {{ __('Se ha enviado un nuevo enlace de verificación.') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="mt-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <div class="d-grid">
                <button type="submit" class="btn btn-lg btn-primary fw-bold text-uppercase shadow-sm">
                    {{ __('Reenviar Correo') }}
                </button>
            </div>
        </form>

        <div class="text-center mt-4 pt-3 border-top">
            <div class="d-flex justify-content-center align-items-center gap-4">
                <a href="{{ route('customer.profile') }}" class="small text-primary font-bold text-uppercase text-decoration-none">
                    <i class="align-middle me-1" data-feather="user" style="width: 14px;"></i> {{ __('Mi Perfil') }}
                </a>

                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-link text-danger small font-bold text-uppercase p-0 text-decoration-none border-0">
                        <i class="align-middle me-1" data-feather="log-out" style="width: 14px;"></i> {{ __('Salir') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });
    </script>
    @endpush
</x-guest-layout>
