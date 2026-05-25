<?php

namespace App\Livewire\Logistics;

use Livewire\Component;
use App\Models\Locker;
use Livewire\WithPagination;
use App\Traits\WithSorting;

class LockerList extends Component
{
    use WithPagination, WithSorting;

    public $search = '';
    public $filter_status = '';
    public $code, $status = 'available', $length, $width, $height, $max_weight;

    protected $queryString = [
        'search' => ['except' => ''],
        'filter_status' => ['except' => ''],
    ];

    protected $rules = [
        'code' => 'required|unique:lockers,code',
        'status' => 'required',
        'length' => 'nullable|numeric',
        'width' => 'nullable|numeric',
        'height' => 'nullable|numeric',
        'max_weight' => 'nullable|numeric',
    ];

    public function createLocker()
    {
        $this->validate();

        Locker::create([
            'tenant_id' => session('tenant_id'),
            'code' => $this->code,
            'status' => $this->status,
            'length' => $this->length,
            'width' => $this->width,
            'height' => $this->height,
            'max_weight' => $this->max_weight,
        ]);

        $this->reset(['code', 'status', 'length', 'width', 'height', 'max_weight']);
        $this->dispatch('locker-saved');
        session()->flash('message', 'Casillero creado correctamente.');
    }

    public function render()
    {
        $lockers = Locker::with('customer.user')
            ->where('code', 'like', '%' . $this->search . '%');

        if ($this->filter_status) {
            $lockers->where('status', $this->filter_status);
        }

        $lockers = $this->applySorting($lockers)->paginate(10);

        $stats = [
            'total_lockers' => Locker::count(),
            'available' => Locker::where('status', 'available')->count(),
            'occupied' => Locker::where('status', 'occupied')->count(),
            'maintenance' => Locker::where('status', 'maintenance')->count(),
        ];

        return view('livewire.logistics.locker-list', [
            'lockers' => $lockers,
            'stats' => $stats
        ])->layout('components.layouts.app');
    }
}
