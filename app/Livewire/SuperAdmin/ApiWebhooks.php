<?php

namespace App\Livewire\SuperAdmin;

use Livewire\Component;

class ApiWebhooks extends Component
{
    public function render()
    {
        return view('livewire.super-admin.api-webhooks')->layout('components.super-admin-layout');
    }
}
