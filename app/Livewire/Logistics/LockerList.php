<?php

namespace App\Livewire\Logistics;

use App\Models\Locker;
use App\Traits\WithSorting;
use Livewire\Component;
use Livewire\WithPagination;

class LockerList extends Component
{
    use WithPagination, WithSorting;

    public $search = '';

    public $filter_status = '';

    public $code;

    public $status = 'available';

    public $length;

    public $width;

    public $height;

    public $max_weight;

    public $editing_id = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'filter_status' => ['except' => ''],
    ];

    protected $rules = [
        'status' => 'required',
        'length' => 'nullable|numeric',
        'width' => 'nullable|numeric',
        'height' => 'nullable|numeric',
        'max_weight' => 'nullable|numeric',
    ];

    protected function validateLocker()
    {
        $this->validate(array_merge($this->rules, [
            'code' => 'required|unique:lockers,code,'.($this->editing_id ?? 'NULL'),
        ]));
    }

    public function openCreateLockerModal()
    {
        $this->reset(['code', 'status', 'length', 'width', 'height', 'max_weight', 'editing_id']);
        $this->status = 'available';
        $this->dispatch('open-locker-modal');
    }

    public function openEditModal($id)
    {
        $locker = Locker::findOrFail($id);
        $this->editing_id = $locker->id;
        $this->code = $locker->code;
        $this->status = $locker->status;
        $this->length = $locker->length;
        $this->width = $locker->width;
        $this->height = $locker->height;
        $this->max_weight = $locker->max_weight;
        $this->dispatch('open-locker-modal');
    }

    public function createLocker()
    {
        $this->validateLocker();

        Locker::create([
            'tenant_id' => session('tenant_id'),
            'code' => $this->code,
            'status' => $this->status,
            'length' => $this->length,
            'width' => $this->width,
            'height' => $this->height,
            'max_weight' => $this->max_weight,
        ]);

        $this->reset(['code', 'status', 'length', 'width', 'height', 'max_weight', 'editing_id']);
        $this->dispatch('locker-saved');
        session()->flash('message', 'Casillero creado correctamente.');
    }

    public function updateLocker()
    {
        $this->validateLocker();

        Locker::where('id', $this->editing_id)->update([
            'code' => $this->code,
            'status' => $this->status,
            'length' => $this->length,
            'width' => $this->width,
            'height' => $this->height,
            'max_weight' => $this->max_weight,
        ]);

        $this->reset(['code', 'status', 'length', 'width', 'height', 'max_weight', 'editing_id']);
        $this->dispatch('locker-saved');
        session()->flash('message', 'Casillero actualizado correctamente.');
    }

    public function render()
    {
        $lockers = Locker::with('customer.user')
            ->where('code', 'like', '%'.$this->search.'%');

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
            'stats' => $stats,
        ])->layout('components.layouts.app');
    }
}
