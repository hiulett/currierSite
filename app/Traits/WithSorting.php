<?php

namespace App\Traits;

trait WithSorting
{
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function applySorting($query)
    {
        // Handle related fields if necessary (optional improvement)
        return $query->orderBy($this->sortField, $this->sortDirection);
    }
}
