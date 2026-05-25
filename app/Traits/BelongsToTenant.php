<?php

namespace App\Traits;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Session;

trait BelongsToTenant
{
    protected static function bootBelongsToTenant()
    {
        static::creating(function ($model) {
            if (session()->has('tenant_id')) {
                $model->tenant_id = session()->get('tenant_id');
            }
        });

        static::addGlobalScope('tenant', function (Builder $builder) {
            if (session()->has('tenant_id')) {
                $table = $builder->getModel()->getTable();

                // If we are filtering Users or Roles, we must allow those with tenant_id = null
                // Users: To allow SuperAdmins. Roles: To allow System/Global roles.
                if ($builder->getModel() instanceof \App\Models\User || $builder->getModel() instanceof \App\Models\Role) {
                    $builder->where(function($query) use ($table) {
                        $query->where($table . '.tenant_id', session()->get('tenant_id'))
                              ->orWhereNull($table . '.tenant_id');
                    });
                } else {
                    $builder->where($table . '.tenant_id', session()->get('tenant_id'));
                }
            }
        });
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
