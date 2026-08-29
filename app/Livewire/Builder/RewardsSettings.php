<?php

namespace App\Livewire\Builder;

use App\Models\Reward;
use Livewire\Component;

class RewardsSettings extends Component
{
    // Form fields
    public $reward_id;

    public $name;

    public $type = 'free_pounds';

    public $points_cost;

    public $value = 0;

    public $description;

    public $image_url;

    public $icon = 'gift';

    public $is_active = true;

    public $stock;

    public $sort_order = 0;

    public $is_editing = false;

    public function editReward($id)
    {
        $reward = Reward::find($id);

        $this->reward_id = $reward->id;
        $this->name = $reward->name;
        $this->type = $reward->type;
        $this->points_cost = $reward->points_cost;
        $this->value = $reward->value;
        $this->description = $reward->description;
        $this->image_url = $reward->image_url;
        $this->icon = $reward->icon;
        $this->is_active = $reward->is_active;
        $this->stock = $reward->stock;
        $this->sort_order = $reward->sort_order;
        $this->is_editing = true;
    }

    public function saveReward()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:free_pounds,physical',
            'points_cost' => 'required|integer|min:1',
            'value' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
            'image_url' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'stock' => 'nullable|integer|min:0',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data = [
            'name' => $this->name,
            'type' => $this->type,
            'points_cost' => $this->points_cost,
            'value' => $this->value,
            'description' => $this->description,
            'image_url' => $this->image_url,
            'icon' => $this->icon,
            'is_active' => $this->is_active,
            'stock' => $this->stock === null || $this->stock === '' ? null : $this->stock,
            'sort_order' => $this->sort_order,
        ];

        if ($this->reward_id) {
            Reward::find($this->reward_id)->update($data);
        } else {
            Reward::create(array_merge(['tenant_id' => session('tenant_id')], $data));
        }

        $this->resetForm();
        session()->flash('message', 'Recompensa guardada correctamente.');
    }

    public function toggleActive($id)
    {
        $reward = Reward::find($id);
        $reward->update(['is_active' => ! $reward->is_active]);
        session()->flash('message', 'Estado de la recompensa actualizado.');
    }

    public function deleteReward($id)
    {
        Reward::find($id)->delete();
        session()->flash('message', 'Recompensa eliminada.');
    }

    public function resetForm()
    {
        $this->reset([
            'reward_id', 'name', 'type', 'points_cost', 'value', 'description',
            'image_url', 'icon', 'is_active', 'stock', 'sort_order', 'is_editing',
        ]);
        $this->type = 'free_pounds';
        $this->value = 0;
        $this->icon = 'gift';
        $this->is_active = true;
        $this->sort_order = 0;
    }

    public function render()
    {
        return view('livewire.builder.rewards-settings', [
            'rewards' => Reward::orderBy('sort_order', 'asc')->orderBy('id', 'asc')->get(),
        ])->layout('components.layouts.app');
    }
}
