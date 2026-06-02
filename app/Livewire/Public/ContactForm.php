<?php

namespace App\Livewire\Public;

use Livewire\Component;
use App\Models\Lead;
use Illuminate\Support\Facades\Log;

class ContactForm extends Component
{
    public $name;
    public $email;
    public $phone;
    public $company;
    public $message;
    public $successMessage = '';

    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email',
        'phone' => 'required|min:7',
        'company' => 'required|min:2',
    ];

    public function submit()
    {
        $this->validate();

        // Guardar el Lead en la base de datos
        Lead::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'company' => $this->company,
            'message' => $this->message,
        ]);

        Log::info("Nuevo Lead de la Landing: {$this->name} - {$this->email} - {$this->phone} - {$this->company}");

        $this->reset(['name', 'email', 'phone', 'company', 'message']);
        $this->successMessage = '¡Gracias! Hemos recibido tu solicitud. Un experto te contactará pronto.';
    }

    public function render()
    {
        return view('livewire.public.contact-form');
    }
}
