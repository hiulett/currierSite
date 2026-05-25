<div>
    <div class="row mb-3">
        <div class="col-12 text-center py-5">
            <div class="card shadow-sm border-0 mx-auto" style="max-width: 500px;">
                <div class="card-body p-5">
                    <div class="stat text-danger bg-danger-light mx-auto mb-4" style="width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i data-feather="user-x" style="width: 40px; height: 40px;"></i>
                    </div>
                    <h3 class="fw-black text-dark uppercase mb-3">{{ $title }}</h3>
                    <p class="text-muted mb-4">{{ $message }}</p>
                    <a href="{{ route('dashboard') }}" class="btn btn-primary fw-bold text-uppercase">Volver al Panel Principal</a>
                </div>
            </div>
        </div>
    </div>
</div>
