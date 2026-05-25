<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Configuración SMTP</h2>
            <p class="text-slate-500 text-sm">Personaliza el remitente de los correos que reciben tus clientes.</p>
        </div>
        <button wire:click="save" class="bg-blue-600 text-white px-8 py-2 rounded-xl font-bold shadow-lg hover:bg-blue-700 transition">
            Guardar Configuración
        </button>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-8 rounded-r-xl shadow-sm">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-white p-8 rounded-[30px] shadow-sm border border-slate-200 md:col-span-2">
            <h3 class="text-lg font-bold text-slate-800 mb-6">Servidor de Salida</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Host SMTP</label>
                    <input type="text" wire:model="mail_host" placeholder="smtp.mailtrap.io" class="w-full border-slate-200 rounded-xl focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Puerto</label>
                    <input type="text" wire:model="mail_port" placeholder="587" class="w-full border-slate-200 rounded-xl focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Encriptación</label>
                    <select wire:model="mail_encryption" class="w-full border-slate-200 rounded-xl">
                        <option value="tls">TLS</option>
                        <option value="ssl">SSL</option>
                        <option value="none">Ninguna</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Usuario / Email</label>
                    <input type="text" wire:model="mail_username" class="w-full border-slate-200 rounded-xl focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Contraseña</label>
                    <input type="password" wire:model="mail_password" class="w-full border-slate-200 rounded-xl focus:ring-blue-500">
                </div>
            </div>
        </div>

        <div class="bg-white p-8 rounded-[30px] shadow-sm border border-slate-200 md:col-span-2">
            <h3 class="text-lg font-bold text-slate-800 mb-6">Remitente (Sender)</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Email Remitente</label>
                    <input type="email" wire:model="mail_from_address" placeholder="no-reply@tuempresa.com" class="w-full border-slate-200 rounded-xl focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Nombre Remitente</label>
                    <input type="text" wire:model="mail_from_name" class="w-full border-slate-200 rounded-xl focus:ring-blue-500">
                </div>
            </div>
        </div>
    </div>

    <div class="mt-8 bg-blue-50 p-6 rounded-[30px] border border-blue-100 flex items-start space-x-4">
        <div class="text-blue-500">💡</div>
        <p class="text-xs text-blue-800 leading-relaxed font-medium">
            <strong>¿Por qué configurar esto?</strong> Por defecto, los correos se envían desde nuestro servidor general. Al configurar tu propio SMTP, tus clientes verán que los correos vienen directamente de tu marca, mejorando la confianza y la entregabilidad.
        </p>
    </div>
</div>
