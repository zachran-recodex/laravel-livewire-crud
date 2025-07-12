<?php

namespace App\Livewire\Administrator;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;

#[Layout('components.layouts.app')]
class ActivityLog extends Component
{
    use WithPagination;

    public string $search = '';
    public string $sortField = 'created_at';
    public bool $sortAsc = false;

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortAsc = !$this->sortAsc;
        } else {
            $this->sortField = $field;
            $this->sortAsc = true;
        }
    }

    public function render()
    {
        $activities = Activity::query()
            ->when($this->search, function ($query) {
                $query->where('description', 'like', '%' . $this->search . '%')
                    ->orWhere('subject_type', 'like', '%' . $this->search . '%')
                    ->orWhere('causer_type', 'like', '%' . $this->search . '%')
                    ->orWhere('log_name', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
            ->paginate(10);

        return view('livewire.administrator.activity-log', [
            'activities' => $activities,
        ]);
    }
}