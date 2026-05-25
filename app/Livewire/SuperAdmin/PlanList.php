<?php

namespace App\Livewire\SuperAdmin;

use Livewire\Component;
use App\Models\Plan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use App\Traits\WithSorting;

class PlanList extends Component
{
    use WithPagination, WithSorting;

    public $planId;
    public $isEditing = false;

    public $name, $price, $price_annual, $price_5year, $limit_users, $limit_packages_month;
    public $has_website_builder = false;
    public $has_api_access = false;
    public $is_featured = false;

    public $selected_features = [];
    public $available_modules = [
        'Logística PRO (Recepción, Scanner, Etiquetas)',
        'Inventario Avanzado (Búsqueda por Cédula/Tracking)',
        'Módulo de Reempaque y Consolidación',
        'Gestión de Embarques y Manifiestos',
        'Última Milla (POD, Fotos y Geofencing GPS)',
        'Despacho en Oficina con Alerta de Deuda',
        'Facturación Automática (Stripe & PayPal)',
        'Portal del Cliente White Label',
        'WhatsApp IA Agent & Tickets de Soporte',
        'Identidad Visual (Logo y Colores Propios)',
        'Widgets de Rastreo para sitios externos',
        'Soporte Multilenguaje (i18n)',
    ];

    public function edit($id)
    {
        $plan = Plan::findOrFail($id);
        $this->planId = $plan->id;
        $this->name = $plan->name;
        $this->price = $plan->price;
        $this->price_annual = $plan->price_annual;
        $this->price_5year = $plan->price_5year;
        $this->limit_users = $plan->limit_users;
        $this->limit_packages_month = $plan->limit_packages_month;
        $this->has_website_builder = $plan->has_website_builder;
        $this->has_api_access = $plan->has_api_access;
        $this->is_featured = $plan->is_featured;
        $this->selected_features = $plan->features_json ?? [];
        $this->isEditing = true;
    }

    public function cancelEdit()
    {
        $this->reset(['name', 'price', 'price_annual', 'price_5year', 'limit_users', 'limit_packages_month', 'has_website_builder', 'has_api_access', 'is_featured', 'selected_features']);
        $this->isEditing = false;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'price_annual' => 'nullable|numeric|min:0',
            'price_5year' => 'nullable|numeric|min:0',
        ]);

        // Normalize numeric values to avoid "MathException: Unable to cast value to a decimal"
        $price = $this->price ?: 0;
        $price_annual = !empty($this->price_annual) ? $this->price_annual : null;
        $price_5year = !empty($this->price_5year) ? $this->price_5year : null;

        $data = [
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'price' => $price,
            'price_annual' => $price_annual,
            'price_5year' => $price_5year,
            'limit_users' => $this->limit_users ?: 0,
            'limit_packages_month' => $this->limit_packages_month ?: 0,
            'has_website_builder' => (bool) $this->has_website_builder,
            'has_api_access' => (bool) $this->has_api_access,
            'is_featured' => (bool) $this->is_featured,
            'features_json' => $this->selected_features,
        ];

        if ($this->isEditing) {
            Plan::find($this->planId)->update($data);
            session()->flash('message', 'Plan actualizado correctamente.');
        } else {
            Plan::create($data);
            session()->flash('message', 'Nuevo plan creado correctamente.');
        }

        $this->reset(['name', 'price', 'price_annual', 'price_5year', 'limit_users', 'limit_packages_month', 'has_website_builder', 'has_api_access', 'is_featured', 'selected_features']);
        $this->isEditing = false;
    }

    public function toggleStatus($id)
    {
        $plan = Plan::findOrFail($id);

        // Use direct DB update or force null on empty decimal fields to avoid cast exception
        DB::table('plans')->where('id', $id)->update([
            'is_active' => !$plan->is_active,
            'updated_at' => now()
        ]);

        session()->flash('message', "El plan {$plan->name} ha sido actualizado.");
    }

    public function render()
    {
        return view('livewire.super-admin.plan-list', [
            'plans' => $this->applySorting(Plan::query())->paginate(10)
        ])->layout('components.super-admin-layout');
    }
}
