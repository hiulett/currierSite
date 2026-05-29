<?php

namespace App\Livewire\Builder;

use Livewire\Component;
use App\Models\Tenant;
use App\Models\LoyaltyLevel;

class LoyaltySettings extends Component
{
    public $points_per_pound;
    public $loyalty_enabled;

    // Level form fields
    public $level_id;
    public $name;
    public $min_points;
    public $multiplier = 1.0;
    public $color = '#4f46e5';
    public $icon = 'star';

    public $is_editing = false;

    public function mount()
    {
        $tenant = Tenant::find(session('tenant_id'));
        $settings = $tenant->settings_json ?? [];

        $this->points_per_pound = $settings['points_per_pound'] ?? 1;
        $this->loyalty_enabled = $settings['loyalty_enabled'] ?? true;
    }

    public function saveGeneral()
    {
        $tenant = Tenant::find(session('tenant_id'));
        $settings = $tenant->settings_json ?? [];

        $settings['points_per_pound'] = $this->points_per_pound;
        $settings['loyalty_enabled'] = $this->loyalty_enabled;

        $tenant->update(['settings_json' => $settings]);
        session()->flash('message', 'Configuración de lealtad actualizada.');
    }

    public function editLevel($id)
    {
        $level = LoyaltyLevel::find($id);
        $this->level_id = $level->id;
        $this->name = $level->name;
        $this->min_points = $level->min_points;
        $this->multiplier = $level->multiplier;
        $this->color = $level->color;
        $this->icon = $level->icon;
        $this->is_editing = true;
    }

    public function saveLevel()
    {
        $this->validate([
            'name' => 'required|string',
            'min_points' => 'required|integer|min:0',
            'multiplier' => 'required|numeric|min:1',
        ]);

        if ($this->level_id) {
            $level = LoyaltyLevel::find($this->level_id);
            $level->update([
                'name' => $this->name,
                'min_points' => $this->min_points,
                'multiplier' => $this->multiplier,
                'color' => $this->color,
                'icon' => $this->icon,
            ]);
        } else {
            LoyaltyLevel::create([
                'tenant_id' => session('tenant_id'),
                'name' => $this->name,
                'min_points' => $this->min_points,
                'multiplier' => $this->multiplier,
                'color' => $this->color,
                'icon' => $this->icon,
            ]);
        }

        $this->resetForm();
        session()->flash('message', 'Nivel guardado correctamente.');
    }

    public function deleteLevel($id)
    {
        LoyaltyLevel::find($id)->delete();
        session()->flash('message', 'Nivel eliminado.');
    }

    public function resetForm()
    {
        $this->reset(['level_id', 'name', 'min_points', 'multiplier', 'color', 'icon', 'is_editing']);
    }

    public function render()
    {
        $levels = LoyaltyLevel::orderBy('min_points', 'asc')->get();

        return view('livewire.builder.loyalty-settings', [
            'levels' => $levels
        ])->layout('components.layouts.app');
    }
}
