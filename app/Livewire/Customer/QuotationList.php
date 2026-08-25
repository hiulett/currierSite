<?php

namespace App\Livewire\Customer;

use App\Models\Quotation;
use App\Traits\WithSorting;
use Livewire\Component;
use Livewire\WithPagination;

class QuotationList extends Component
{
    use WithPagination, WithSorting;

    public function render()
    {
        $customer = auth()->user()->customer;

        if (! $customer) {
            return view('livewire.customer.dashboard-error', [
                'title' => 'Perfil no encontrado',
                'message' => 'No tienes un perfil de cliente asociado para ver cotizaciones.',
            ])->layout('components.customer-layout');
        }

        return view('livewire.customer.quotation-list', [
            'quotations' => $this->applySorting(Quotation::where('customer_id', $customer->id)->with(['items', 'tenant']))->paginate(10),
        ])->layout('components.customer-layout');
    }
}
