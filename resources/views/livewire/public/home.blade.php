<div>
    <div class="text-center">
        <p class="text-muted mb-4">Bienvenido a su plataforma integral de logística y casillero internacional. Ingrese a su cuenta para gestionar sus paquetes.</p>

        <div class="d-grid gap-3">
            <a href="{{ route('login') }}" class="btn btn-primary btn-lg fw-black tracking-widest py-3">
                INICIAR SESIÓN
            </a>
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="btn btn-outline-dark btn-lg fw-bold py-3">
                    CREAR MI CASILLERO
                </a>
            @endif
        </div>

        <div class="mt-4 pt-4 border-top">
            <div class="d-flex justify-content-center gap-4">
                <a href="{{ route('public.calculator') }}" class="text-decoration-none text-dark small fw-black uppercase">
                    <i class="align-middle me-1" data-feather="plus-square" style="width: 14px;"></i> Calculadora
                </a>
            </div>
        </div>
    </div>
</div>
