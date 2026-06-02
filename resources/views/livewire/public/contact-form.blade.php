<div>
    @if($successMessage)
        <div class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-2xl mb-8 flex items-center gap-3 animate-pulse">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="font-bold">{{ $successMessage }}</span>
        </div>
    @endif

    <form wire:submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="md:col-span-2">
            <label class="block text-sm font-bold text-slate-700 mb-2">Nombre Completo</label>
            <input wire:model="name" type="text" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all @error('name') border-red-500 @enderror" placeholder="Juan Pérez">
            @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>
        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Correo Corporativo</label>
            <input wire:model="email" type="email" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all @error('email') border-red-500 @enderror" placeholder="juan@tuempresa.com">
            @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>
        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Teléfono / WhatsApp</label>
            <input wire:model="phone" type="text" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all @error('phone') border-red-500 @enderror" placeholder="+507 6000-0000">
            @error('phone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm font-bold text-slate-700 mb-2">Nombre de la Empresa</label>
            <input wire:model="company" type="text" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all @error('company') border-red-500 @enderror" placeholder="Logistics Solutions S.A.">
            @error('company') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>
        <div class="md:col-span-2 text-center mt-4">
            <button type="submit" wire:loading.attr="disabled" class="inline-flex items-center justify-center px-10 py-4 bg-blue-600 text-white font-bold rounded-full hover:bg-blue-700 shadow-xl shadow-blue-200 transition-all transform hover:-translate-y-1 w-full md:w-auto disabled:opacity-50">
                <span wire:loading.remove>Enviar Solicitud</span>
                <span wire:loading>Enviando...</span>
            </button>
        </div>
    </form>
</div>
