<?php

namespace App\Livewire\Logistics;

use Livewire\Component;
use App\Models\Ticket;
use Livewire\WithPagination;
use App\Traits\WithSorting;

class SupportTickets extends Component
{
    use WithPagination, WithSorting;

    public $status_filter = '';
    public $search = '';

    public function closeTicket($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->update(['status' => 'closed']);
        session()->flash('message', 'Ticket #' . $id . ' cerrado correctamente.');
    }

    public function render()
    {
        $query = Ticket::with(['customer.user', 'messages'])
            ->where(function($q) {
                $q->where('subject', 'like', '%' . $this->search . '%')
                  ->orWhereHas('customer.user', function($u) {
                      $u->where('name', 'like', '%' . $this->search . '%');
                  });
            });

        if ($this->status_filter) {
            $query->where('status', $this->status_filter);
        }

        return view('livewire.logistics.support-tickets', [
            'tickets' => $this->applySorting($query)->paginate(10)
        ])->layout('components.layouts.app');
    }
}
