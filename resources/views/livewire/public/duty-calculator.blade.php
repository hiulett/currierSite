<div class="max-w-xl mx-auto py-20 px-8">
    <div class="text-center mb-10">
        <h2 class="text-4xl font-black text-slate-900 uppercase tracking-tighter">Calculadora de Aranceles</h2>
        <p class="text-slate-500 mt-2">Estima los impuestos de aduana para tus compras internacionales.</p>
    </div>

    <div class="bg-white rounded-[40px] shadow-2xl border border-slate-100 p-10">
        <form wire:submit.prevent="calculate" class="space-y-6">
            <div>
                <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Valor de la Compra (USD)</label>
                <input type="number" step="0.01" wire:model="value" placeholder="0.00"
                       class="w-full bg-slate-50 border-transparent rounded-2xl p-4 text-xl font-black focus:bg-white focus:ring-4 focus:ring-blue-100 focus:border-blue-400 transition text-right">
            </div>

            <div>
                <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Categoría del Producto</label>
                <select wire:model="category_id"
                        class="w-full bg-slate-50 border-transparent rounded-2xl p-4 font-bold focus:bg-white focus:ring-4 focus:ring-blue-100 focus:border-blue-400 transition">
                    <option value="">Seleccione categoría...</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }} ({{ $cat->percentage }}%)</option>
                    @endforeach
                </select>
            </div>

            <button type="submit"
                    class="w-full bg-slate-900 text-white py-5 rounded-3xl text-sm font-black uppercase tracking-widest shadow-xl hover:bg-slate-800 transition transform hover:-translate-y-1">
                Calcular Impuestos
            </button>
        </form>

        @if($result)
            <div class="mt-10 p-8 bg-blue-50 rounded-[30px] border border-blue-100 animate-in slide-in-from-top-4 duration-500">
                <h3 class="text-[10px] font-black uppercase tracking-widest text-blue-400 mb-6 border-b border-blue-100 pb-4">Resultado Estimado</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500 font-bold">Valor FOB:</span>
                        <span class="font-black text-slate-900">${{ number_format($result['value'], 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500 font-bold">Arancel ({{ $result['percentage'] }}%):</span>
                        <span class="font-black text-blue-600">${{ number_format($result['tax'], 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-lg border-t border-blue-100 pt-4">
                        <span class="text-slate-800 font-black uppercase tracking-tight">Costo con Impuestos:</span>
                        <span class="font-black text-blue-700">${{ number_format($result['total'], 2) }}</span>
                    </div>
                </div>
                <p class="text-[8px] text-slate-400 font-bold uppercase tracking-widest mt-6 text-center italic leading-relaxed">
                    * Este es un cálculo estimado. Los valores reales pueden variar según la normativa aduanera vigente al momento del arribo.
                </p>
            </div>
        @endif
    </div>
</div>
