<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageStatus extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'name',
        'label',
        'color',
        'icon',
        'sort_order',
        'is_active',
        'is_system',
    ];

    /**
     * Get default statuses mapping if not found in DB
     */
    public static function getDefaults()
    {
        return [
            'prealert' => ['label' => 'Pre-alerta', 'color' => '#6c757d', 'icon' => 'bell'],
            'received' => ['label' => 'Recibido', 'color' => '#17a2b8', 'icon' => 'home'],
            'in_transit' => ['label' => 'En Tránsito', 'color' => '#ffc107', 'icon' => 'truck'],
            'arrived' => ['label' => 'Llegó al País', 'color' => '#28a745', 'icon' => 'flag'],
            'ready_for_pickup' => ['label' => 'Listo para Retiro', 'color' => '#3b7ddd', 'icon' => 'box'],
            'out_for_delivery' => ['label' => 'En Ruta de Entrega', 'color' => '#17a2b8', 'icon' => 'map-pin'],
            'delivered' => ['label' => 'Entregado', 'color' => '#212529', 'icon' => 'check-circle'],
            'cancelled' => ['label' => 'Cancelado', 'color' => '#dc3545', 'icon' => 'x-circle'],
            'consolidated' => ['label' => 'Consolidado', 'color' => '#6f42c1', 'icon' => 'package'],
        ];
    }
}
