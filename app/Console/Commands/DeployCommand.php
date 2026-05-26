<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class DeployCommand extends Command
{
    protected $signature = 'app:deploy';
    protected $description = 'Prepara la aplicación para producción de forma segura';

    public function handle()
    {
        $this->info('🚀 Iniciando despliegue resiliente...');

        // 1. Ejecutar Migraciones
        $this->info('--- Ejecutando Migraciones ---');
        Artisan::call('migrate', ['--force' => true]);
        $this->line(Artisan::output());

        // 2. Ejecutar Seeder Inteligente
        $this->info('--- Ejecutando Seeding Inteligente ---');
        Artisan::call('db:seed', ['--class' => 'LogiSaaSSeeder', '--force' => true]);
        $this->line(Artisan::output());

        // 3. Limpiar y cachear configuración
        $this->info('--- Optimizando Sistema ---');
        Artisan::call('optimize:clear');
        Artisan::call('config:cache');
        Artisan::call('route:cache');
        Artisan::call('view:cache');

        $this->info('✅ Despliegue completado con éxito.');
        return 0;
    }
}
