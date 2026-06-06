<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contexto no identificado - LogiSaaS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-2xl p-8 md:p-12 text-center border border-slate-100">
        <div class="w-20 h-20 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-8 shadow-inner">
            <i class="fas fa-link text-3xl"></i>
        </div>

        <h1 class="text-2xl font-black text-slate-800 mb-4 tracking-tight uppercase">Enlace no válido</h1>

        <p class="text-slate-600 mb-8 leading-relaxed font-medium">
            Has intentado acceder al sistema sin un identificador de agencia válido.
            <br><br>
            Por favor, utiliza el enlace proporcionado por tu empresa de courier o contacta a soporte si crees que esto es un error.
        </p>

        <div class="space-y-4">
            <a href="/" class="block w-full bg-blue-600 text-white py-4 rounded-2xl text-sm font-black uppercase tracking-widest shadow-lg shadow-blue-200 hover:bg-blue-700 transition">
                Volver al inicio
            </a>

            <div class="pt-4 border-t border-slate-100 mt-6">
                <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">
                    LogiSaaS Multi-Tenant Gateway
                </p>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</body>
</html>
