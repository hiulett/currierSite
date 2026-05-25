<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Logística
            ['name' => 'logistics.receive', 'label' => 'Recibir Paquetes', 'group' => 'Logística'],
            ['name' => 'logistics.inventory', 'label' => 'Ver Inventario', 'group' => 'Logística'],
            ['name' => 'logistics.repack', 'label' => 'Reempaque', 'group' => 'Logística'],
            ['name' => 'logistics.shipments', 'label' => 'Gestionar Embarques', 'group' => 'Logística'],
            ['name' => 'logistics.delivery', 'label' => 'Última Milla', 'group' => 'Logística'],
            ['name' => 'logistics.counter', 'label' => 'Despacho (Counter)', 'group' => 'Logística'],
            ['name' => 'logistics.reports', 'label' => 'Ver Reportes', 'group' => 'Logística'],

            // CRM
            ['name' => 'customers.view', 'label' => 'Ver Clientes', 'group' => 'Clientes'],
            ['name' => 'customers.manage', 'label' => 'Administrar Clientes', 'group' => 'Clientes'],
            ['name' => 'tickets.manage', 'label' => 'Gestionar Tickets de Soporte', 'group' => 'Soporte'],

            // Facturación
            ['name' => 'billing.view', 'label' => 'Ver Facturación', 'group' => 'Facturación'],
            ['name' => 'billing.manage', 'label' => 'Crear/Editar Facturas', 'group' => 'Facturación'],

            // Configuración
            ['name' => 'settings.brand', 'label' => 'Gestionar Marca', 'group' => 'Configuración'],
            ['name' => 'settings.general', 'label' => 'Ajustes Generales', 'group' => 'Configuración'],
            ['name' => 'settings.users', 'label' => 'Administrar Usuarios/Roles', 'group' => 'Configuración'],
        ];

        foreach ($permissions as $p) {
            Permission::updateOrCreate(['name' => $p['name']], $p);
        }
    }
}
