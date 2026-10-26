@props([
    'web' => false,
    'href' => null,
    'icon' => 'message-circle',
    'iconClass' => '',
    'size' => '14px',
    'title' => null,
])

@php
    $useLink = $web && $href;
    $hasSlot = trim((string) $slot) !== '';
    $common = $title ? ['title' => $title] : [];
@endphp

@if ($useLink)
    <a href="{{ $href }}" target="_blank" rel="noopener"
       {{ $attributes->except(['wire:click'])->merge($common) }}>
        <i data-feather="{{ $icon }}" class="{{ trim($iconClass.' '.($hasSlot ? 'me-2' : '')) }}" style="width: {{ $size }};"></i>{{ $slot }}
    </a>
@else
    <button type="button" {{ $attributes->merge($common) }}>
        <i data-feather="{{ $icon }}" class="{{ trim($iconClass.' '.($hasSlot ? 'me-2' : '')) }}" style="width: {{ $size }};"></i>{{ $slot }}
    </button>
@endif
