<div class="max-w-6xl mx-auto pb-20">
    <div class="mb-10 flex justify-between items-end">
        <div>
            <h2 class="text-3xl font-black text-slate-800 uppercase tracking-tighter">Módulo de Cobranza</h2>
            <p class="text-slate-500 font-medium">Listado de clientes con saldos pendientes y facturas vencidas.</p>
        </div>
        <div class="bg-red-500 text-white px-8 py-4 rounded-3xl shadow-xl shadow-red-200">
            <p class="text-[10px] font-black uppercase opacity-70">Cartera Total Pendiente</p>
            <p class="text-3xl font-black">${{ number_format($total_debt, 2) }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
        <div class="bg-white p-8 rounded-[40px] shadow-sm border border-slate-200 flex items-center space-x-6">
            <div class="w-14 h-14 bg-red-100 text-red-600 rounded-2xl flex items-center justify-center font-black">!</div>
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Facturas Vencidas</p>
                <p class="text-2xl font-black text-slate-800">{{ $overdue_count }}</p>
            </div>
        </div>
        <div class="bg-slate-900 p-8 rounded-[40px] shadow-sm border border-slate-800 flex items-center space-x-6 text-white">
            <div class="w-14 h-14 bg-blue-600 text-white rounded-2xl flex items-center justify-center font-black">@</div>
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-500">Recordatorios</p>
                <button class="text-sm font-black text-blue-400 uppercase tracking-widest hover:underline">Enviar masivo</button>
            </div>
        </div>
    </div>

    <div class="card bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="min-w-full divide-y divide-slate-100">
            <thead class="bg-slate-50 text-[10px] font-black uppercase tracking-widest text-slate-400">
                <tr>
                    <th class="px-8 py-4 text-left">Cliente</th>
                    <th class="px-8 py-4 text-right">Saldo Pendiente</th>
                    <th class="px-8 py-4 text-center">Estado</th>
                    <th class="px-8 py-4 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @foreach($debtors as $d)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-8 py-5">
                            <p class="font-black text-slate-900">{{ $d->user->name }}</p>
                            <p class="text-[10px] text-blue-600 font-bold uppercase">{{ $d->box_number }}</p>
                        </td>
                        <td class="px-8 py-5 text-right font-black text-red-500 text-lg">
                            ${{ number_format($d->balance, 2) }}
                        </td>
                        <td class="px-8 py-5 text-center">
                            <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-[10px] font-black uppercase tracking-widest">En Mora</span>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <div class="flex justify-end space-x-2">
                                <button class="bg-blue-50 text-blue-600 p-2 rounded-lg hover:bg-blue-600 hover:text-white transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002-2z"></path></svg>
                                </button>
                                <a href="{{ route('billing.statement', $d->id) }}" class="bg-slate-100 text-slate-600 p-2 rounded-lg hover:bg-slate-900 hover:text-white transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
