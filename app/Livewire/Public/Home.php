<?php

namespace App\Livewire\Public;

use Livewire\Component;

class Home extends Component
{
    public function render()
    {
        // Use a basic layout or none since the view has its own container structure
        return view('livewire.public.home')->layout('components.guest-layout');
    }
}
