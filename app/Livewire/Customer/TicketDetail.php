<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Ticket;
use App\Models\TicketMessage;

class TicketDetail extends Component
{
    public Ticket $ticket;
    public $message;

    public function mount(Ticket $ticket)
    {
        $customer = auth()->user()->customer;

        // Security check
        if (!$customer || $ticket->customer_id !== $customer->id) {
            abort(403);
        }
        $this->ticket = $ticket;
    }

    public function sendMessage()
    {
        $this->validate([
            'message' => 'required|string|min:2',
        ]);

        TicketMessage::create([
            'ticket_id' => $this->ticket->id,
            'user_id' => auth()->id(),
            'message' => $this->message,
        ]);

        $this->reset('message');
        $this->ticket->load('messages.user');

        // Mark as open if customer replies to a resolved ticket? maybe.
        if ($this->ticket->status === 'resolved') {
            $this->ticket->update(['status' => 'open']);
        }
    }

    public function render()
    {
        return view('livewire.customer.ticket-detail')->layout('components.customer-layout');
    }
}
