<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - {{ $tenantName }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --primary: {{ $primaryColor }};
            --primary-dark: {{ $primaryDark }};
        }
        .btn-primary {
            background-color: var(--primary);
        }
        .btn-primary:hover {
            background-color: var(--primary-dark);
        }
        .text-primary { color: var(--primary); }
        .auth-bg {
            background-color: {{ $bgColor }};
            {{ $bgImageUrl ? 'background-image: url(' . $bgImageUrl . '); background-size: cover; background-position: center;' : '' }}
        }
        {{ $customCss }}
    </style>
</head>
<body class="auth-bg min-h-screen flex items-center justify-center p-4">

    @if(!$tenant && !app()->environment('local'))
        {{-- Sin tenant: mostrar error --}}
        <div class="max-w-md w-full bg-white rounded-3xl shadow-2xl p-8 text-center">
            <div class="text-5xl mb-4">🔒</div>
            <h1 class="text-xl font-black text-slate-700 mb-2">Registro restringido</h1>
            <p class="text-slate-500 text-sm">
                Para registrarte debes usar el enlace oficial de tu agencia logística.
            </p>
        </div>
    @else
    <div class="max-w-xl w-full bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-100">
        <div class="p-8 md:p-12">
            <div class="text-center mb-10">
                @if($logoUrl)
                    <img src="{{ $logoUrl }}" alt="{{ $tenantName }}" class="mx-auto h-20 mb-4 object-contain">
                @else
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4" style="background-color: {{ $primaryColor }};">
                        <span class="text-white text-2xl font-black">{{ strtoupper(substr($tenantName, 0, 1)) }}</span>
                    </div>
                @endif
                <h1 class="text-3xl font-black tracking-tighter uppercase text-primary">{{ $tenantName }}</h1>
                <p class="text-slate-500 font-bold text-xs uppercase tracking-widest mt-2">Crea tu Casillero Virtual</p>
            </div>

            <form action="{{ route('register') }}" method="POST" class="space-y-6">
                @csrf

                @if ($errors->any())
                    <div class="bg-red-50 text-red-600 p-4 rounded-2xl text-xs font-bold border border-red-100">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Nombre Completo</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full bg-slate-50 border-transparent rounded-2xl p-4 font-bold focus:bg-white focus:ring-4 focus:ring-blue-100 focus:border-blue-400 transition outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Correo Electrónico</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="w-full bg-slate-50 border-transparent rounded-2xl p-4 font-bold focus:bg-white focus:ring-4 focus:ring-blue-100 focus:border-blue-400 transition outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Contraseña</label>
                        <input type="password" name="password" required
                               class="w-full bg-slate-50 border-transparent rounded-2xl p-4 font-bold focus:bg-white focus:ring-4 focus:ring-blue-100 focus:border-blue-400 transition outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Confirmar Contraseña</label>
                        <input type="password" name="password_confirmation" required
                               class="w-full bg-slate-50 border-transparent rounded-2xl p-4 font-bold focus:bg-white focus:ring-4 focus:ring-blue-100 focus:border-blue-400 transition outline-none">
                    </div>
                </div>

                <div class="bg-blue-50 p-6 rounded-2xl border border-blue-100">
                    <p class="text-[10px] font-black text-blue-400 uppercase tracking-widest mb-3">Términos de Servicio</p>
                    <p class="text-[10px] text-slate-500 leading-relaxed font-bold">
                        Al registrarte en {{ $tenantName }}, aceptas nuestras políticas de manejo de carga y privacidad. Tu número de casillero será generado automáticamente.
                    </p>
                </div>

                <button type="submit"
                        class="btn-primary w-full text-white py-5 rounded-3xl text-sm font-black uppercase tracking-widest shadow-lg hover:-translate-y-1 transition transform">
                    Crear mi cuenta gratis
                </button>
            </form>

            <div class="mt-10 pt-8 border-t border-slate-100 text-center text-xs">
                <p class="text-slate-400 font-bold">¿Ya tienes cuenta?</p>
                <a href="{{ $tenant ? $tenant->getLoginUrl() : route('login') }}" class="font-black uppercase tracking-widest mt-2 inline-block hover:underline text-primary">Inicia sesión aquí</a>
            </div>
        </div>
    </div>
    @endif
</body>
</html>
