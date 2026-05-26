<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-100">
        <div class="p-8 md:p-12">
            <div class="text-center mb-10">
                <h1 class="text-3xl font-black text-blue-600 tracking-tighter uppercase">{{ config('app.name') }}</h1>
                <p class="text-slate-500 font-bold text-xs uppercase tracking-widest mt-2">Acceso al Portal</p>
            </div>

            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf

                @if ($errors->any())
                    <div class="bg-red-50 text-red-600 p-4 rounded-2xl text-xs font-bold border border-red-100">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Correo Electrónico</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full bg-slate-50 border-transparent rounded-2xl p-4 font-bold focus:bg-white focus:ring-4 focus:ring-blue-100 focus:border-blue-400 transition outline-none">
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Contraseña</label>
                    <input type="password" name="password" required
                           class="w-full bg-slate-50 border-transparent rounded-2xl p-4 font-bold focus:bg-white focus:ring-4 focus:ring-blue-100 focus:border-blue-400 transition outline-none">
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-xs font-bold text-slate-500">Recordarme</span>
                    </label>
                    <a href="#" class="text-xs font-bold text-blue-600 hover:underline">¿Olvidaste tu contraseña?</a>
                </div>

                <button type="submit"
                        class="w-full bg-blue-600 text-white py-5 rounded-3xl text-sm font-black uppercase tracking-widest shadow-lg shadow-blue-200 hover:bg-blue-700 hover:-translate-y-1 transition transform">
                    Entrar al Sistema
                </button>
            </form>

            <div class="mt-10 pt-8 border-t border-slate-100 text-center text-xs">
                <p class="text-slate-400 font-bold">¿No tienes una cuenta?</p>
                <a href="{{ route('register') }}" class="text-blue-600 font-black uppercase tracking-widest mt-2 inline-block hover:underline">Regístrate gratis aquí</a>
            </div>
        </div>
    </div>
</body>
</html>
