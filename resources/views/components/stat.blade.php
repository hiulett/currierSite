@props(['value' => '', 'label' => '', 'icon' => 'box', 'color' => 'primary', 'extraClass' => ''])

@php
    $colors = ['primary', 'info', 'danger', 'warning', 'success', 'secondary'];
    $color = in_array($color, $colors, true) ? $color : 'primary';
@endphp

<div class="col-12 col-sm-6 col-xl-3 d-flex {{ $extraClass }}">
    <div class="card flex-fill border-0 shadow-sm rounded-4 overflow-hidden hover-lift transition-all">
        <div class="card-body p-4">
            <div class="d-flex align-items-start">
                <div class="flex-grow-1">
                    <h3 class="mb-1 fw-bold text-dark">{{ $value }}</h3>
                    <p class="mb-0 text-uppercase font-bold xsmall text-muted">{{ $label }}</p>
                </div>
                <div class="stat bg-{{ $color }}-light text-{{ $color }}">
                    <i class="align-middle" data-feather="{{ $icon }}"></i>
                </div>
            </div>
        </div>
    </div>
</div>
