<div>
    @if($successMessage)
        <div class="bg-data-teal/10 border border-data-teal/20 text-data-teal px-6 py-4 rounded-xl mb-8 flex items-center gap-3">
            <span class="material-symbols-outlined">check_circle</span>
            <span class="font-bold">{{ $successMessage }}</span>
        </div>
    @endif

    <form wire:submit.prevent="submit" class="space-y-6">
        <div class="grid md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface">Nombre Completo</label>
                <input wire:model="name" type="text" class="w-full border-border-light rounded-lg focus:ring-secondary focus:border-secondary px-4 py-2.5 outline-none transition-all @error('name') border-error @enderror" placeholder="Ej. Juan Pérez">
                @error('name') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface">Correo Corporativo</label>
                <input wire:model="email" type="email" class="w-full border-border-light rounded-lg focus:ring-secondary focus:border-secondary px-4 py-2.5 outline-none transition-all @error('email') border-error @enderror" placeholder="nombre@empresa.com">
                @error('email') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="grid md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface">Teléfono / WhatsApp</label>
                <input wire:model="phone" type="tel" class="w-full border-border-light rounded-lg focus:ring-secondary focus:border-secondary px-4 py-2.5 outline-none transition-all @error('phone') border-error @enderror" placeholder="+507 0000-0000">
                @error('phone') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
            </div>
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface">Nombre de la Empresa</label>
                <input wire:model="company" type="text" class="w-full border-border-light rounded-lg focus:ring-secondary focus:border-secondary px-4 py-2.5 outline-none transition-all @error('company') border-error @enderror" placeholder="Tu Courier S.A.">
                @error('company') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
            </div>
        </div>
        <button type="submit" wire:loading.attr="disabled" class="w-full bg-secondary text-white py-4 rounded-lg font-label-md text-label-md hover:opacity-90 transition-opacity flex items-center justify-center gap-2">
            <span wire:loading.remove>Enviar Solicitud</span>
            <span wire:loading class="animate-spin material-symbols-outlined">progress_activity</span>
            <span wire:loading>Enviando...</span>
        </button>
        <p class="text-center font-label-sm text-label-sm text-on-surface-variant">Al enviar, aceptas nuestros Términos y Política de Privacidad.</p>
    </form>
</div>
