<?php

use App\Http\Middleware\CheckPermission;
use App\Http\Middleware\CheckTenantFeature;
use App\Http\Middleware\ForcePasswordChange;
use App\Http\Middleware\IdentifyTenant;
use App\Http\Middleware\RequiresTenantContext;
use App\Http\Middleware\RoleRedirect;
use App\Http\Middleware\SecurityHeaders;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');

        $middleware->web(append: [
            IdentifyTenant::class,
            ForcePasswordChange::class,
            SecurityHeaders::class,
        ]);

        $middleware->alias([
            'permission' => CheckPermission::class,
            'role' => RoleRedirect::class,
            'tenant.required' => RequiresTenantContext::class,
            'tenant.feature' => CheckTenantFeature::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->report(function (Throwable $e) {
            file_put_contents('php://stderr', '['.date('Y-m-d H:i:s').'] ERROR DIAGNÓSTICO: '.$e->getMessage()."\n".$e->getTraceAsString()."\n");
        });
    })->create();
