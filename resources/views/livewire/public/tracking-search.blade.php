<div>
    <div class="max-w-3xl mx-auto py-20 px-4">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-black text-slate-800 mb-4 uppercase">Rastreo de Paquetes</h1>
            <p class="text-slate-500 text-lg">Ingresa tu número de tracking para conocer el estado actual de tu envío.</p>
        </div>

        <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 p-2 flex items-center mb-12 focus-within:ring-4 focus-within:ring-blue-100 transition">
            <input type="text" wire:model.defer="search_tracking" placeholder="Ej: 1Z9999999999999999"
                   class="flex-1 bg-transparent border-none p-6 text-xl font-bold focus:ring-0 placeholder-slate-300 uppercase">
            <button wire:click="search" class="bg-blue-600 text-white px-10 py-5 rounded-2xl font-black uppercase tracking-widest hover:bg-blue-700 transition shadow-lg shadow-blue-200">
                Rastrear
            </button>
        </div>

        @if($searched)
            @if($package || $external_data)
                <div class="bg-slate-50 rounded-3xl p-8 border border-slate-200 animate-fade-in">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10 pb-6 border-b border-slate-200">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tracking Number</p>
                            <h3 class="text-2xl font-black text-slate-800">{{ $package ? $package->tracking_number : ($external_data['data']['tracking'] ?? $search_tracking) }}</h3>
                        </div>
                        <div class="text-right">
                            @if($package)
                                @php
                                    $statusStyles = [
                                        'prealert' => 'bg-yellow-100 text-yellow-700',
                                        'received' => 'bg-blue-100 text-blue-700',
                                        'in_transit' => 'bg-purple-100 text-purple-700',
                                        'delivered' => 'bg-green-100 text-green-700',
                                    ][$package->status] ?? 'bg-slate-200 text-slate-700';
                                @endphp
                                <span class="px-6 py-2 rounded-full text-xs font-black uppercase tracking-widest shadow-sm {{ $statusStyles }}">
                                    {{ $package->status }}
                                </span>
                            @else
                                <span class="px-6 py-2 rounded-full text-xs font-black uppercase tracking-widest shadow-sm bg-blue-600 text-white">
                                    {{ $external_data['data']['status'] ?? 'EN SISTEMA EXTERNO' }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div class="space-y-8 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-slate-300 before:to-transparent">

                        @if($package)
                            @forelse($package->trackingEvents as $index => $event)
                                <!-- Local Event -->
                                <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group {{ $index == 0 ? 'is-active' : '' }}">
                                    <div class="flex items-center justify-center w-10 h-10 rounded-full border border-white {{ $index == 0 ? 'bg-blue-600 text-white' : 'bg-slate-300 text-slate-500' }} shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2">
                                        @if($index == 0)
                                            <svg class="fill-current" viewBox="0 0 12 10" width="12" height="10">
                                                <path fill-rule="nonzero" d="M1 5.485l3.297 3.297L11 1.942"></path>
                                            </svg>
                                        @else
                                            <div class="h-2 w-2 rounded-full bg-white"></div>
                                        @endif
                                    </div>
                                    <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] p-4 rounded border border-slate-200 bg-white shadow-sm hover:shadow-md transition">
                                        <div class="flex items-center justify-between space-x-2 mb-1">
                                            <div class="font-black text-slate-900 uppercase text-[10px] tracking-widest">{{ str_replace('_', ' ', $event->status) }}</div>
                                            <time class="font-bold {{ $index == 0 ? 'text-blue-600' : 'text-slate-400' }} text-[10px]">{{ $event->created_at->format('d M, Y H:i') }}</time>
                                        </div>
                                        @if($event->location)
                                            <div class="text-[10px] font-bold text-slate-400 uppercase mb-2">
                                                <svg class="inline w-3 h-3 me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                                {{ $event->location }}
                                            </div>
                                        @endif
                                        <div class="text-slate-500 text-xs leading-relaxed">
                                            {{ $event->notes ?? 'Estado del paquete actualizado en sistema.' }}
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-10 bg-white rounded-3xl border border-dashed border-slate-200">
                                    <p class="text-slate-400 italic">No hay historial detallado disponible.</p>
                                </div>
                            @endforelse
                        @elseif($external_data && isset($external_data['data']['history']))
                            @foreach($external_data['data']['history'] as $index => $history)
                                <!-- External Event -->
                                <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group {{ $index == 0 ? 'is-active' : '' }}">
                                    <div class="flex items-center justify-center w-10 h-10 rounded-full border border-white {{ $index == 0 ? 'bg-blue-600 text-white' : 'bg-slate-300 text-slate-500' }} shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2">
                                        @if($index == 0)
                                            <svg class="fill-current" viewBox="0 0 12 10" width="12" height="10">
                                                <path fill-rule="nonzero" d="M1 5.485l3.297 3.297L11 1.942"></path>
                                            </svg>
                                        @else
                                            <div class="h-2 w-2 rounded-full bg-white"></div>
                                        @endif
                                    </div>
                                    <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] p-4 rounded border border-slate-200 bg-white shadow-sm hover:shadow-md transition">
                                        <div class="flex items-center justify-between space-x-2 mb-1">
                                            <div class="font-black text-slate-900 uppercase text-[10px] tracking-widest">{{ $history['status'] ?? 'ACTUALIZACIÓN' }}</div>
                                            <time class="font-bold {{ $index == 0 ? 'text-blue-600' : 'text-slate-400' }} text-[10px]">{{ $history['date'] ?? 'N/A' }}</time>
                                        </div>
                                        @if(isset($history['location']))
                                            <div class="text-[10px] font-bold text-slate-400 uppercase mb-2">
                                                <svg class="inline w-3 h-3 me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                                {{ $history['location'] }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-10 bg-white rounded-3xl border border-dashed border-slate-200">
                                <p class="text-slate-400 italic">No hay historial disponible para este tracking.</p>
                            </div>
                        @endif

                    </div>
                </div>
            @else
                <div class="bg-red-50 border-2 border-dashed border-red-200 rounded-3xl p-12 text-center animate-shake">
                    <div class="bg-red-100 h-20 w-20 rounded-full flex items-center justify-center mx-auto mb-6 text-red-600">
                        <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-red-900">Tracking no encontrado</h3>
                    <p class="text-red-700 mt-2">No pudimos encontrar información para el número <span class="font-black">"{{ $search_tracking }}"</span>. Por favor, verifica que el número sea correcto.</p>
                </div>
            @endif
        @endif
    </div>
</div>
