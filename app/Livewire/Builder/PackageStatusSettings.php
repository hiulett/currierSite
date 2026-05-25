<?php

namespace App\Livewire\Builder;

use Livewire\Component;
use App\Models\PackageStatus;
use App\Models\Tenant;

class PackageStatusSettings extends Component
{
    public $statuses = [];
    public $editing_id = null;
    public $name, $label, $color, $icon, $sort_order;

    public function mount()
    {
        $this->loadStatuses();
    }

    public function loadStatuses()
    {
        $tenantId = session('tenant_id');

        // Ensure defaults exist in DB if empty for this tenant
        if (PackageStatus::where('tenant_id', $tenantId)->count() === 0) {
            $defaults = PackageStatus::getDefaults();
            $order = 0;
            foreach ($defaults as $name => $data) {
                PackageStatus::create([
                    'tenant_id' => $tenantId,
                    'name' => $name,
                    'label' => $data['label'],
                    'color' => $data['color'],
                    'icon' => $data['icon'],
                    'sort_order' => $order++,
                    'is_system' => true
                ]);
            }
        }

        $this->statuses = PackageStatus::where('tenant_id', $tenantId)
            ->orderBy('sort_order')
            ->get()
            ->toArray();
    }

    public function edit($id)
    {
        $status = PackageStatus::find($id);
        $this->editing_id = $id;
        $this->name = $status->name;
        $this->label = $status->label;
        $this->color = $status->color;
        $this->icon = $status->icon;
        $this->sort_order = $status->sort_order;
    }

    public function cancel()
    {
        $this->reset(['editing_id', 'name', 'label', 'color', 'icon', 'sort_order']);
    }

    public function save()
    {
        $this->validate([
            'label' => 'required|string|max:50',
            'color' => 'required|string',
            'icon' => 'required|string',
        ]);

        $status = PackageStatus::find($this->editing_id);
        $status->update([
            'label' => $this->label,
            'color' => $this->color,
            'icon' => $this->icon,
            'sort_order' => $this->sort_order,
        ]);

        $this->cancel();
        $this->loadStatuses();
        session()->flash('message', 'Estado actualizado correctamente.');
    }

    public function render()
    {
        return view('livewire.builder.package-status-settings')
            ->layout('components.layouts.app');
    }
}
