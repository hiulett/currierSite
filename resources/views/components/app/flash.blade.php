<div>
    @php
        $flashes = [
            'message' => ['type' => 'success', 'icon' => 'check-circle'],
            'success' => ['type' => 'success', 'icon' => 'check-circle'],
            'error'   => ['type' => 'danger',  'icon' => 'alert-octagon'],
            'warning' => ['type' => 'warning', 'icon' => 'alert-triangle'],
            'info'    => ['type' => 'info',    'icon' => 'info'],
        ];
    @endphp

    @foreach ($flashes as $key => $config)
        @if (session()->has($key))
            <div class="alert alert-{{ $config['type'] }} alert-dismissible fade show shadow-sm mb-4 border-0 rounded-xl" role="alert">
                <div class="d-flex align-items-center">
                    <i data-feather="{{ $config['icon'] }}" class="me-2"></i>
                    <div>{{ session($key) }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif
    @endforeach
</div>
