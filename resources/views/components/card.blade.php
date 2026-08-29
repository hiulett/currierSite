@props(['title' => null, 'headerClass' => 'bg-white border-bottom py-3', 'bodyClass' => null, 'flush' => false])

<div {{ $attributes->merge(['class' => 'card shadow-sm mb-4']) }}>
    @if($title)
        <div class="card-header {{ $headerClass }}">
            <h5 class="card-title mb-0 uppercase font-black small {{ str_contains($headerClass, 'text-white') ? 'text-white' : 'text-dark' }}">
                {{ $title }}
            </h5>
        </div>
    @endif

    @if($flush)
        {{ $slot }}
    @else
        <div class="card-body {{ $bodyClass ?? '' }}">
            {{ $slot }}
        </div>
    @endif
</div>
