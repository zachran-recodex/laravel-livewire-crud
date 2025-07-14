<?php

namespace App\Livewire\Administrator;

use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;

class ActivityLog extends Component
{
    use WithPagination;

    public $search = '';
    public $filterCauser = '';
    public $filterLogName = '';
    public $filterEvent = '';
    public $filterDateFrom = '';
    public $filterDateTo = '';

    #[Computed]
    public function activities()
    {
        return Activity::with(['causer', 'subject'])
            ->when($this->search, function ($query) {
                $query->where('description', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterCauser, function ($query) {
                $query->whereHasMorph('causer', ['App\Models\User'], function ($query) {
                    $query->where('name', 'like', '%' . $this->filterCauser . '%');
                });
            })
            ->when($this->filterLogName, function ($query) {
                $query->where('log_name', $this->filterLogName);
            })
            ->when($this->filterEvent, function ($query) {
                $query->where('event', $this->filterEvent);
            })
            ->when($this->filterDateFrom, function ($query) {
                $query->whereDate('created_at', '>=', $this->filterDateFrom);
            })
            ->when($this->filterDateTo, function ($query) {
                $query->whereDate('created_at', '<=', $this->filterDateTo);
            })
            ->latest()
            ->paginate(15);
    }

    #[Computed]
    public function logNames()
    {
        return Activity::distinct()->pluck('log_name')->filter();
    }

    #[Computed]
    public function events()
    {
        return Activity::distinct()->pluck('event')->filter();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'filterCauser', 'filterLogName', 'filterEvent', 'filterDateFrom', 'filterDateTo']);
        $this->resetPage();
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['search', 'filterCauser', 'filterLogName', 'filterEvent', 'filterDateFrom', 'filterDateTo'])) {
            $this->resetPage();
        }
    }

    public function render()
    {
        return view('livewire.administrator.activity-log');
    }
}