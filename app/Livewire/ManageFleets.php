<?php

namespace App\Livewire;

use App\Models\Fleet;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ManageFleets extends Component
{
    use WithPagination, WithFileUploads;

    public $title = '';
    public $category = '';
    public $description = '';
    public $image = '';
    public $currentImage = ''; // For storing current image in edit mode
    public $passengers = 0;
    public $range = 0;
    public $features = [];
    public $newFeature = '';
    public $order = 0;
    public $is_active = true;
    public $editingFleetId = null;
    public $showModal = false;
    public $search = '';

    public function rules(): array
    {
        $rules = [
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'description' => 'required|string',
            'passengers' => 'required|integer|min:1',
            'range' => 'required|integer|min:1',
            'features' => 'array',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ];

        if ($this->editingFleetId) {
            $rules['image'] = 'nullable|image|max:2048';
        } else {
            $rules['image'] = 'required|image|max:2048';
        }

        return $rules;
    }

    #[Computed]
    public function fleets()
    {
        return Fleet::when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('category', 'like', '%' . $this->search . '%');
            })
            ->orderBy('order')
            ->paginate(10);
    }

    public function create()
    {
        $this->reset(['title', 'category', 'description', 'image', 'currentImage', 'passengers', 'range', 'features', 'newFeature', 'order', 'is_active', 'editingFleetId']);
        $this->order = $this->getNextOrder();
        $this->is_active = true;
        $this->showModal = true;
    }

    public function edit($fleetId)
    {
        $fleet = Fleet::findOrFail($fleetId);
        $this->editingFleetId = $fleet->id;
        $this->title = $fleet->title;
        $this->category = $fleet->category;
        $this->description = $fleet->description;
        $this->image = null; // Reset image for file upload
        $this->currentImage = $fleet->image; // Store current image
        $this->passengers = $fleet->passengers;
        $this->range = $fleet->range;
        $this->features = $fleet->features ?? [];
        $this->order = $fleet->order;
        $this->is_active = $fleet->is_active;
        $this->showModal = true;
    }

    public function addFeature()
    {
        if (!empty($this->newFeature)) {
            $this->features[] = $this->newFeature;
            $this->newFeature = '';
        }
    }

    public function removeFeature($index)
    {
        unset($this->features[$index]);
        $this->features = array_values($this->features);
    }

    public function save()
    {
        $this->validate();

        $fleetData = [
            'title' => $this->title,
            'category' => $this->category,
            'description' => $this->description,
            'passengers' => $this->passengers,
            'range' => $this->range,
            'features' => $this->features,
            'order' => $this->order,
            'is_active' => $this->is_active,
        ];

        // Handle image upload
        if ($this->image) {
            $fleetData['image'] = $this->image->store('fleets', 'public');
        }

        if ($this->editingFleetId) {
            $fleet = Fleet::findOrFail($this->editingFleetId);

            // Delete old image if new one is uploaded
            if ($this->image && $fleet->image) {
                Storage::disk('public')->delete($fleet->image);
            }

            $fleet->update($fleetData);
            $message = 'Fleet successfully updated!';
        } else {
            Fleet::create($fleetData);
            $message = 'Fleet successfully created!';
        }

        $this->reset(['title', 'category', 'description', 'image', 'currentImage', 'passengers', 'range', 'features', 'newFeature', 'order', 'is_active', 'editingFleetId']);
        $this->showModal = false;

        session()->flash('message', $message);
    }

    public function delete($fleetId)
    {
        $fleet = Fleet::findOrFail($fleetId);

        // Delete image file if exists
        if ($fleet->image) {
            Storage::disk('public')->delete($fleet->image);
        }

        $fleet->delete();
        session()->flash('message', 'Fleet successfully deleted!');

        $this->modal("delete-fleet-{$fleetId}")->close();
    }

    public function resetForm()
    {
        $this->reset(['title', 'category', 'description', 'image', 'currentImage', 'passengers', 'range', 'features', 'newFeature', 'order', 'is_active', 'editingFleetId']);
        $this->resetValidation();
    }

    private function getNextOrder(): int
    {
        return Fleet::max('order') + 1;
    }

    public function render()
    {
        return view('livewire.manage-fleets');
    }
}