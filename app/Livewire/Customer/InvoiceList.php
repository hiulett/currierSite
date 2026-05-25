<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Invoice;
use App\Models\Customer;
use Livewire\WithPagination;
use App\Traits\WithSorting;

class InvoiceList extends Component
{
    use WithPagination, WithSorting;

    public function render()
    {
        $customer = auth()->user()->customer;

        if (!$customer) {
            return view('livewire.customer.dashboard-error', [
                'title' => 'Perfil no encontrado',
                'message' => 'No tienes un perfil de cliente asociado para ver facturas.'
            ])->layout('components.customer-layout');
        }

        return view('livewire.customer.invoice-list', [
            'invoices' => $this->applySorting(Invoice::where('customer_id', $customer->id))->paginate(10)
        ])->layout('components.customer-layout');
    }
}
