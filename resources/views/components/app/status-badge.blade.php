@props([
    'label' => '',
    'color' => null,
    'icon' => null,
    'uppercase' => true,
])

@php
    $text = $label;
    $style = $color ? "background-color: {$color};" : '';
@endphp

<span {{ $attributes->merge(['class' => 'badge ' . ($uppercase ? 'text-uppercase' : '')]) }} style="{{ $style }} font-size: 0.6rem;">
    @if ($icon)
        <i data-feather="{{ $icon }}" style="width: 10px; height: 10px; margin-right: 4px;"></i>
    @endif
    {{ $text }}
</span>
