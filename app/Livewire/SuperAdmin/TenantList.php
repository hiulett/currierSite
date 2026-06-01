<?php

namespace App\Livewire\SuperAdmin;

use Livewire\Component;
use App\Models\Tenant;
use Livewire\WithPagination;
use App\Traits\WithSorting;

class TenantList extends Component
{
    use WithPagination, WithSorting;

    public $search = '';
    public $configuring_tenant_id = null;
    public $configuring_billing_id = null; // New
    public $features = [];

    // Billing state
    public $next_billing_at;
    public $payment_warning_active;

    protected $listeners = ['stop-impersonating' => 'stopImpersonating'];

    public function configureBilling($id)
    {
        $this->configuring_billing_id = $id;
        $tenant = Tenant::find($id);
        $this->next_billing_at = $tenant->next_billing_at ? $tenant->next_billing_at->format('Y-m-d') : null;
        $this->payment_warning_active = (bool) $tenant->payment_warning_active;
    }

    public function saveBilling()
    {
        $tenant = Tenant::find($this->configuring_billing_id);
        $tenant->update([
            'next_billing_at' => $this->next_billing_at,
            'payment_warning_active' => $this->payment_warning_active
        ]);

        $this->configuring_billing_id = null;
        session()->flash('message', 'Configuración de facturación actualizada para ' . $tenant->name);
    }

    public function stopImpersonating()
    {
        session()->forget('impersonate_tenant_id');
        return redirect()->route('super.tenants');
    }

    public function configureReports($id)
    {
        $this->configuring_tenant_id = $id;
        $tenant = Tenant::find($id);

        // Initial features state
        $this->features = $tenant->features_json ?? [
            'repack' => true,
            'whatsapp_ia' => true,
            'tickets' => true,
            'online_payments' => true
        ];
    }

    public function saveFeatures()
    {
        $tenant = Tenant::find($this->configuring_tenant_id);
        $tenant->update(['features_json' => $this->features]);

        $this->configuring_tenant_id = null;
        session()->flash('message', 'Funcionalidades actualizadas para ' . $tenant->name);
    }

    public function impersonate($tenantId)
    {
        if ($tenantId === 'stop') {
            session()->forget('impersonate_tenant_id');
            return redirect()->route('super.tenants');
        }

        session(['impersonate_tenant_id' => $tenantId]);
        return redirect()->route('dashboard');
    }

    public function closeConfig()
    {
        $this->configuring_tenant_id = null;
    }

    public function updateStatus($id, $status)
    {
        Tenant::find($id)->update(['status' => $status]);
        session()->flash('message', 'Estado actualizado correctamente.');
    }

    public function render()
    {
        $query = Tenant::where('name', 'like', '%' . $this->search . '%');

        return view('livewire.super-admin.tenant-list', [
            'tenants' => $this->applySorting($query)->paginate(10)
        ])->layout('components.super-admin-layout');
    }
}
