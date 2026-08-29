<div class="row mb-4">
    <div class="col-12">
        <h1 class="h3 mb-0 uppercase font-black tracking-tight text-dark">
            @if(isset($icon) && $icon)
                <i data-feather="{{ $icon }}" class="me-1" style="width: 22px; height: 22px; margin-top: -4px;"></i>
            @endif
            {{ $title }}
        </h1>
        @if(isset($subtitle) && $subtitle)
            <p class="text-muted small">{{ $subtitle }}</p>
        @endif
    </div>
</div>
