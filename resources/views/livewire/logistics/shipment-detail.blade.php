<div class="max-w-6xl mx-auto pb-20">
    <div class="flex justify-between items-start mb-10">
        <div>
            <a href="{{ route('logistics.shipments.index') }}" class="text-slate-400 font-bold text-xs uppercase tracking-widest hover:text-slate-600 mb-2 block">← Volver a lista</a>
            <h2 class="text-3xl font-black text-slate-800 uppercase tracking-tighter">
                Manifiesto: <span class="text-blue-600">{{ $shipment->manifest_number }}</span>
            </h2>
            <div class="mt-2 flex items-center space-x-4">
                <span class="text-slate-500 font-bold text-sm uppercase">{{ $shipment->carrier_name }}</span>
                <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-[10px] font-black uppercase tracking-widest">{{ $shipment->status }}</span>
            </div>
        </div>

        <div class="flex space-x-2">
            @if($shipment->status == 'draft')
                <button wire:click="updateStatus('in_transit')" class="bg-yellow-400 text-yellow-900 px-6 py-2 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-yellow-500 transition">Despachar (In Transit)</button>
            @elseif($shipment->status == 'in_transit')
                <button wire:click="updateStatus('arrived')" class="bg-green-600 text-white px-6 py-2 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-green-700 transition">Confirmar Arribo</button>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- Main List -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-[40px] shadow-xl border border-slate-100 overflow-hidden">
                <div class="p-8 border-b bg-slate-50 flex justify-between items-center">
                    <h3 class="font-black text-slate-800 uppercase tracking-tight">Paquetes en este envío</h3>
                    <span class="bg-blue-600 text-white px-3 py-1 rounded-lg text-xs font-black">{{ $shipment->packages->count() }}</span>
                </div>
                <table class="min-w-full divide-y divide-slate-100">
                    <tbody class="divide-y divide-slate-100">
                        @forelse($shipment->packages as $pkg)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-8 py-5">
                                    <p class="text-sm font-black text-slate-900">{{ $pkg->tracking_number }}</p>
                                    <p class="text-[10px] text-slate-400 font-bold">{{ $pkg->customer->user->name }} ({{ $pkg->customer->box_number }})</p>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    @if($shipment->status == 'draft')
                                        <button wire:click="removePackage({{ $pkg->id }})" class="text-red-400 hover:text-red-600 transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-8 py-20 text-center text-slate-400 italic">No hay paquetes asignados a este manifiesto.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Packages Sidebar -->
        @if($shipment->status == 'draft')
            <div class="space-y-6">
                <div class="bg-slate-900 p-8 rounded-[40px] shadow-2xl text-white">
                    <h3 class="text-lg font-black uppercase tracking-tight mb-6">Añadir Carga</h3>
                    <div class="mb-6">
                        <input type="text" wire:model.live="search_package" placeholder="Buscar por tracking o casillero..."
                               class="w-full bg-white/10 border-transparent rounded-2xl p-4 text-sm font-bold focus:bg-white focus:text-slate-900 transition outline-none">
                    </div>

                    <div class="space-y-4">
                        @foreach($availablePackages as $apkg)
                            <div class="bg-white/5 p-4 rounded-2xl border border-white/10 flex justify-between items-center group hover:bg-white/10 transition">
                                <div>
                                    <p class="text-xs font-black">{{ $apkg->tracking_number }}</p>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $apkg->customer->box_number }}</p>
                                </div>
                                <button wire:click="addPackage({{ $apkg->id }})" class="bg-blue-600 p-2 rounded-xl opacity-0 group-hover:opacity-100 transition shadow-lg">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"></path></svg>
                                </button>
                            </div>
                        @endforeach
                        @if($availablePackages->isEmpty() && $search_package)
                            <p class="text-center text-[10px] text-slate-500 italic uppercase font-bold tracking-widest">No se encontraron resultados</p>
                        @endif
                    </div>
                </div>

                <div class="bg-white p-8 rounded-[40px] border border-slate-200">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4">Ayuda</p>
                    <p class="text-xs text-slate-500 leading-relaxed font-medium">
                        Agrega paquetes que estén en estado "Recibido" o "Pre-alertado". Al despachar el manifiesto, todos los paquetes cambiarán automáticamente a "En Tránsito".
                    </p>
                </div>
            </div>
        @else
            <div class="bg-white p-8 rounded-[40px] border border-slate-200 text-center">
                <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <h4 class="font-black text-slate-800 uppercase tracking-tight">Manifiesto Cerrado</h4>
                <p class="text-xs text-slate-500 mt-2">Este embarque ya no puede ser modificado porque su estado es <strong>{{ $shipment->status }}</strong>.</p>
            </div>
        @endif
    </div>
</div>
