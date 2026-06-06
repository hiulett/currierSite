<?php

namespace App\Traits;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

trait BelongsToTenant
{
    /**
     * Flag to prevent infinite recursion during scope resolution.
     */
    private static $isResolvingTenant = false;

    protected static function bootBelongsToTenant()
    {
        static::creating(function ($model) {
            if (!$model->tenant_id) {
                // Use session directly to avoid auth() overhead during creation
                $model->tenant_id = session('tenant_id');
            }
        });

        static::addGlobalScope('tenant', function (Builder $builder) {
            // 1. Guard against infinite recursion
            if (self::$isResolvingTenant || app()->runningInConsole()) {
                return;
            }

            self::$isResolvingTenant = true;

            try {
                // 2. Identify current Tenant ID
                // We trust the session ID provided by IdentifyTenant middleware
                $tenantId = session('tenant_id');

                // Impersonation priority
                if (session()->has('impersonate_tenant_id')) {
                    $tenantId = session('impersonate_tenant_id');
                }

                // 3. SuperAdmin Bypass
                // Check if user is root to see everything
                if (session('is_superadmin') === true && !session()->has('impersonate_tenant_id')) {
                    return;
                }

                // 4. Apply filter
                if ($tenantId) {
                    $table = $builder->getModel()->getTable();

                    // For Users/Roles, allow tenant specific OR null (global) records
                    if ($builder->getModel() instanceof \App\Models\User || $builder->getModel() instanceof \App\Models\Role) {
                        $builder->where(function($query) use ($table, $tenantId) {
                            $query->where($table . '.tenant_id', $tenantId)
                                  ->orWhereNull($table . '.tenant_id');
                        });
                    } else {
                        $builder->where($table . '.tenant_id', $tenantId);
                    }
                } else {
                    // No tenant context: show nothing for security (except User model for auth)
                    if (!($builder->getModel() instanceof \App\Models\User)) {
                        $builder->whereRaw('1 = 0');
                    }
                }
            } finally {
                self::$isResolvingTenant = false;
            }
        });
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
