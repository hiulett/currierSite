<?php

namespace App\Livewire\Builder;

use Livewire\Component;

class PageList extends Component
{
    public function render()
    {
        return view('livewire.builder.page-list')->layout('components.layouts.app');
    }
}
