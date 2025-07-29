<?php

namespace App\Livewire;

use App\Models\Service;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ManageServices extends Component
{
    use WithPagination, WithFileUploads;

    public $title = '';
    public $description = '';
    public $image = '';
    public $currentImage = ''; // For storing current image in edit mode
    public $features = [];
    public $newFeature = '';
    public $order = 0;
    public $is_active = true;
    public $editingServiceId = null;
    public $showModal = false;
    public $search = '';

    public function rules(): array
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'features' => 'array',
            'features.*' => 'string|max:255',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ];

        if ($this->editingServiceId) {
            $rules['image'] = 'nullable|image|max:2048';
        } else {
            $rules['image'] = 'required|image|max:2048';
        }

        return $rules;
    }

    #[Computed]
    public function services()
    {
        return Service::when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->orderBy('order')
            ->paginate(10);
    }

    public function create()
    {
        $this->reset(['title', 'description', 'image', 'features', 'newFeature', 'order', 'is_active', 'editingServiceId']);
        $this->order = $this->getNextOrder();
        $this->is_active = true;
        $this->showModal = true;
    }

    public function edit($serviceId)
    {
        $service = Service::findOrFail($serviceId);
        $this->editingServiceId = $service->id;
        $this->title = $service->title;
        $this->description = $service->description;
        $this->image = null; // Reset image for file upload
        $this->currentImage = $service->image; // Store current image
        $this->features = $service->features ?? [];
        $this->order = $service->order;
        $this->is_active = $service->is_active;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $serviceData = [
            'title' => $this->title,
            'description' => $this->description,
            'features' => $this->features,
            'order' => $this->order,
            'is_active' => $this->is_active,
        ];

        // Handle image upload
        if ($this->image) {
            $serviceData['image'] = $this->image->store('services', 'public');
        }

        if ($this->editingServiceId) {
            $service = Service::findOrFail($this->editingServiceId);

            // Delete old image if new one is uploaded
            if ($this->image && $service->image) {
                Storage::disk('public')->delete($service->image);
            }

            $service->update($serviceData);
            $message = 'Service successfully updated!';
        } else {
            Service::create($serviceData);
            $message = 'Service successfully created!';
        }

        $this->reset(['title', 'description', 'image', 'currentImage', 'features', 'newFeature', 'order', 'is_active', 'editingServiceId']);
        $this->showModal = false;

        session()->flash('message', $message);
    }

    public function delete($serviceId)
    {
        $service = Service::findOrFail($serviceId);

        // Delete image file if exists
        if ($service->image) {
            Storage::disk('public')->delete($service->image);
        }

        $service->delete();
        session()->flash('message', 'Service successfully deleted!');

        $this->modal("delete-service-{$serviceId}")->close();
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

    public function resetForm()
    {
        $this->reset(['title', 'description', 'image', 'currentImage', 'features', 'newFeature', 'order', 'is_active', 'editingServiceId']);
        $this->resetValidation();
    }

    private function getNextOrder(): int
    {
        return Service::max('order') + 1;
    }

    public function render()
    {
        return view('livewire.manage-services');
    }
}
