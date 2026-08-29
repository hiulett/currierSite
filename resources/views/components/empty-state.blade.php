@props(['message' => 'No hay datos para mostrar.', 'icon' => 'inbox'])

<div class="text-center py-12">
    <i data-feather="{{ $icon }}" class="mb-3 text-slate-400" style="width: 48px; height: 48px; opacity: 0.2;"></i>
    <p class="text-sm text-slate-500">{{ $message }}</p>
</div>
