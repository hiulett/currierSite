<?php

namespace App\Livewire\Public;

use Livewire\Component;
use App\Models\TaxCategory;

class DutyCalculator extends Component
{
    public $value;
    public $category_id;
    public $result = null;

    public function calculate()
    {
        $this->validate([
            'value' => 'required|numeric|min:0',
            'category_id' => 'required|exists:tax_categories,id',
        ]);

        $category = TaxCategory::find($this->category_id);
        $tax = $this->value * ($category->percentage / 100);

        $this->result = [
            'value' => $this->value,
            'tax' => $tax,
            'total' => $this->value + $tax,
            'percentage' => $category->percentage
        ];
    }

    public function render()
    {
        $layout = request()->query('embedded') ? 'components.embedded-layout' : 'components.public-layout';
        return view('livewire.public.duty-calculator', [
            'categories' => TaxCategory::all()
        ])->layout($layout);
    }
}
