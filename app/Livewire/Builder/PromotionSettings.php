<?php

namespace App\Livewire\Builder;

use Livewire\Component;
use App\Models\Promotion;
use Livewire\WithPagination;

class PromotionSettings extends Component
{
    use WithPagination;

    public $promotion_id;
    public $name;
    public $code;
    public $type = 'percentage';
    public $value;
    public $start_date;
    public $end_date;
    public $usage_limit;
    public $is_active = true;

    public $is_editing = false;

    public function resetForm()
    {
        $this->reset(['promotion_id', 'name', 'code', 'type', 'value', 'start_date', 'end_date', 'usage_limit', 'is_active', 'is_editing']);
    }

    public function edit($id)
    {
        $promo = Promotion::find($id);
        $this->promotion_id = $promo->id;
        $this->name = $promo->name;
        $this->code = $promo->code;
        $this->type = $promo->type;
        $this->value = $promo->value;
        $this->start_date = $promo->start_date?->format('Y-m-d');
        $this->end_date = $promo->end_date?->format('Y-m-d');
        $this->usage_limit = $promo->usage_limit;
        $this->is_active = $promo->is_active;
        $this->is_editing = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string',
            'code' => 'required|string|unique:promotions,code,' . $this->promotion_id,
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
        ]);

        $data = [
            'tenant_id' => session('tenant_id'),
            'name' => $this->name,
            'code' => strtoupper($this->code),
            'type' => $this->type,
            'value' => $this->value,
            'start_date' => $this->start_date ?: null,
            'end_date' => $this->end_date ?: null,
            'usage_limit' => $this->usage_limit ?: null,
            'is_active' => $this->is_active,
        ];

        if ($this->promotion_id) {
            Promotion::find($this->promotion_id)->update($data);
        } else {
            Promotion::create($data);
        }

        $this->resetForm();
        session()->flash('message', 'Promoción guardada.');
    }

    public function delete($id)
    {
        Promotion::find($id)->delete();
        session()->flash('message', 'Promoción eliminada.');
    }

    public function render()
    {
        return view('livewire.builder.promotion-settings', [
            'promotions' => Promotion::latest()->paginate(10)
        ])->layout('components.layouts.app');
    }
}
