@props(['id', 'title' => null, 'icon' => null, 'headerClass' => 'bg-primary text-white p-4', 'size' => 'lg'])

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-{{ $size }} modal-dialog-centered">
        <div class="modal-content shadow-lg border-0" style="border-radius: 1rem;">
            @if($title)
                <div class="modal-header {{ $headerClass }}">
                    <h5 class="modal-title uppercase font-black tracking-widest {{ str_contains($headerClass, 'text-white') ? 'text-white' : '' }}">
                        @if($icon)
                            <i class="align-middle me-2" data-feather="{{ $icon }}"></i>
                        @endif
                        {{ $title }}
                    </h5>
                    <button type="button" class="btn-close {{ str_contains($headerClass, 'btn-close-white') ? '' : 'btn-close-white' }}" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            @endif

            <div class="modal-body p-4 p-md-5">
                {{ $slot }}
            </div>

            @isset($footer)
                <div class="modal-footer bg-light p-4">
                    {{ $footer }}
                </div>
            @endisset
        </div>
    </div>
</div>
