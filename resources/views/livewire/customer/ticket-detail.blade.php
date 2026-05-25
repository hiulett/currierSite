<div class="max-w-4xl mx-auto pb-20">
    <div class="mb-10">
        <a href="{{ route('customer.tickets.index') }}" class="text-slate-400 font-bold text-xs uppercase tracking-widest hover:text-slate-600 mb-2 block">← Volver a Soporte</a>
        <h2 class="text-3xl font-black text-slate-800 uppercase tracking-tighter">{{ $ticket->subject }}</h2>
        <div class="mt-2 flex items-center space-x-4">
            <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-[10px] font-black uppercase tracking-widest">{{ $ticket->status }}</span>
            <span class="text-slate-400 font-bold text-xs uppercase">Ticket #{{ $ticket->id }}</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <div class="lg:col-span-2 space-y-6">
            <!-- Messages Thread -->
            <div class="space-y-6">
                @foreach($ticket->messages as $msg)
                    <div class="flex {{ $msg->user_id == auth()->id() ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[80%] rounded-[30px] p-6 {{ $msg->user_id == auth()->id() ? 'bg-blue-600 text-white rounded-tr-none shadow-blue-200' : 'bg-white text-slate-700 rounded-tl-none border border-slate-100' }} shadow-xl">
                            <p class="text-sm leading-relaxed">{{ $msg->message }}</p>
                            <p class="text-[9px] font-bold uppercase tracking-widest mt-4 {{ $msg->user_id == auth()->id() ? 'text-blue-100' : 'text-slate-400' }}">
                                {{ $msg->user->name }} • {{ $msg->created_at->format('H:i') }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Reply Form -->
            @if($ticket->status != 'closed')
                <div class="mt-10 bg-white p-8 rounded-[40px] shadow-2xl border border-slate-100">
                    <form wire:submit.prevent="sendMessage">
                        <textarea wire:model="message" rows="3" placeholder="Escribe tu mensaje aquí..."
                                  class="w-full bg-slate-50 border-transparent rounded-3xl p-6 font-medium focus:bg-white focus:ring-4 focus:ring-blue-100 focus:border-blue-400 transition outline-none"></textarea>
                        <div class="mt-4 flex justify-end">
                            <button type="submit" class="bg-slate-900 text-white px-8 py-3 rounded-2xl font-black uppercase tracking-widest text-xs shadow-lg hover:bg-slate-800 transition">
                                Responder
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        </div>

        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white p-8 rounded-[40px] shadow-xl border border-slate-100">
                <h3 class="text-xs font-black uppercase tracking-widest text-slate-400 mb-6 border-b pb-4">Detalles</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Prioridad</p>
                        <p class="text-sm font-black text-slate-800 uppercase">{{ $ticket->priority }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Creado</p>
                        <p class="text-sm font-black text-slate-800">{{ $ticket->created_at->format('d M, Y') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-blue-50 p-8 rounded-[40px] border border-blue-100">
                <p class="text-[10px] font-black uppercase tracking-widest text-blue-400 mb-4">Información</p>
                <p class="text-xs text-blue-800 leading-relaxed font-medium">
                    Nuestro equipo de soporte revisará tu solicitud lo antes posible. Recibirás una notificación cuando respondamos.
                </p>
            </div>
        </div>
    </div>
</div>
