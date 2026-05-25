<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Ticket;
use App\Models\TicketMessage;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use App\Traits\WithSorting;

class TicketList extends Component
{
    use WithPagination, WithSorting;

    public $subject;
    public $message;
    public $priority = 'low';

    public function createTicket()
    {
        $this->validate([
            'subject' => 'required|string|min:5',
            'message' => 'required|string|min:10',
            'priority' => 'required|in:low,medium,high',
        ]);

        $customer = auth()->user()->customer;

        DB::transaction(function() use ($customer) {
            $ticket = Ticket::create([
                'tenant_id' => $customer->tenant_id,
                'customer_id' => $customer->id,
                'subject' => $this->subject,
                'priority' => $this->priority,
                'status' => 'open',
            ]);

            TicketMessage::create([
                'ticket_id' => $ticket->id,
                'user_id' => auth()->id(),
                'message' => $this->message,
            ]);
        });

        $this->reset(['subject', 'message', 'priority']);
        $this->dispatch('ticket-created');
        session()->flash('message', 'Ticket creado exitosamente. Pronto nos comunicaremos contigo.');
    }

    public function render()
    {
        $customer = auth()->user()->customer;

        if (!$customer) {
            return view('livewire.customer.dashboard-error', [
                'title' => 'Perfil no encontrado',
                'message' => 'No tienes un perfil de cliente asociado para gestionar tickets.'
            ])->layout('components.customer-layout');
        }

        $tickets = $this->applySorting(Ticket::where('customer_id', $customer->id))
            ->paginate(10);

        return view('livewire.customer.ticket-list', [
            'tickets' => $tickets
        ])->layout('components.customer-layout');
    }
}
