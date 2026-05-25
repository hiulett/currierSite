<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Package;

class WhatsappBot extends Component
{
    public $isConnected = false;
    public $simulatedMessage = '';
    public $botReply = '';

    public function connect()
    {
        $this->isConnected = true;
        session()->flash('message', '¡Tu cuenta ha sido vinculada con WhatsApp exitosamente!');
    }

    public function sendSimulatedMessage()
    {
        $input = strtolower($this->simulatedMessage);
        $customer = auth()->user()->customer;

        if (!$customer) {
            $this->botReply = "🤖 LogiBot: Lo siento, no puedo encontrar tu perfil de cliente en el sistema.";
            return;
        }

        if (str_contains($input, 'paquete') || str_contains($input, 'donde')) {
            $count = Package::where('customer_id', $customer->id)
                ->whereIn('status', ['received', 'in_transit'])
                ->count();

            $this->botReply = "🤖 LogiBot: ¡Hola! Tienes {$count} paquetes activos. Los últimos están en estado: En Tránsito. ¿Deseas el tracking detallado?";
        } elseif (str_contains($input, 'saldo') || str_contains($input, 'debo')) {
            $this->botReply = "🤖 LogiBot: Tu saldo actual es de $15.50. Tienes 1 factura pendiente por pagar. Puedes pagarla en el portal.";
        } else {
            $this->botReply = "🤖 LogiBot: No entiendo tu consulta. Prueba preguntando por 'mis paquetes' o 'mi saldo'.";
        }

        $this->simulatedMessage = '';
    }

    public function render()
    {
        return view('livewire.customer.whatsapp-bot')->layout('components.customer-layout');
    }
}
