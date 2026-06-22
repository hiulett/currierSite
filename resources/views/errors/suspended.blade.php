<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicio Suspendido - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .suspended-card { max-width: 500px; border-radius: 2rem; border: none; box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
        .icon-box { width: 80px; height: 80px; background: #fff1f0; color: #ff4d4f; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 2rem; font-size: 2.5rem; }
    </style>
</head>
<body>
    <div class="container text-center">
        <div class="card suspended-card p-5 mx-auto">
            <div class="icon-box">
                ⚠️
            </div>
            <h1 class="fw-black text-dark mb-3">SERVICIO SUSPENDIDO</h1>
            <p class="text-muted mb-4">
                El acceso a esta plataforma ha sido temporalmente inhabilitado por el administrador del sistema.
            </p>
            <div class="alert alert-warning py-3 small mb-4">
                <strong>Motivos comunes:</strong><br>
                Periodo de suscripción vencido, mantenimiento programado o incumplimiento de términos de servicio.
            </div>
            <p class="small text-secondary mb-0">
                Si es el dueño de la empresa, por favor contacte a soporte técnico de <strong>{{ config('app.name') }}</strong> para regularizar su situación.
            </p>
            <div class="mt-4 pt-3 border-top">
                <a href="mailto:{{ \App\Models\AppSetting::get('support_email', 'soporte@' . request()->getHost()) }}" class="btn btn-dark px-4 rounded-pill fw-bold">CONTACTAR SOPORTE</a>
            </div>
        </div>
        <p class="mt-4 text-muted small">&copy; {{ date('Y') }} {{ config('app.name') }}</p>
    </div>
</body>
</html>
