<?php

namespace App\Livewire;

use App\Models\Hero;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ManageHeroes extends Component
{
    use WithPagination, WithFileUploads;

    public $title = '';
    public $image = '';
    public $currentImage = ''; // For storing current image in edit mode
    public $order = 0;
    public $is_active = true;
    public $editingHeroId = null;
    public $showModal = false;
    public $search = '';

    public function rules(): array
    {
        $rules = [
            'title' => 'required|string|max:255',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ];

        if ($this->editingHeroId) {
            $rules['image'] = 'nullable|image|max:2048';
        } else {
            $rules['image'] = 'required|image|max:2048';
        }

        return $rules;
    }

    #[Computed]
    public function heroes()
    {
        return Hero::when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%');
            })
            ->orderBy('order')
            ->paginate(10);
    }

    public function create()
    {
        $this->reset(['title', 'image', 'currentImage', 'order', 'is_active', 'editingHeroId']);
        $this->order = $this->getNextOrder();
        $this->is_active = true;
        $this->showModal = true;
    }

    public function edit($heroId)
    {
        $hero = Hero::findOrFail($heroId);
        $this->editingHeroId = $hero->id;
        $this->title = $hero->title;
        $this->image = null; // Reset image for file upload
        $this->currentImage = $hero->image; // Store current image
        $this->order = $hero->order;
        $this->is_active = $hero->is_active;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $heroData = [
            'title' => $this->title,
            'order' => $this->order,
            'is_active' => $this->is_active,
        ];

        // Handle image upload
        if ($this->image) {
            $imageName = time() . '_' . $this->image->getClientOriginalName();
            $this->image->storeAs('images', $imageName, 'public');
            $heroData['image'] = $imageName;
        }

        if ($this->editingHeroId) {
            $hero = Hero::findOrFail($this->editingHeroId);
            
            // Delete old image if new one is uploaded
            if ($this->image && $hero->image) {
                \Storage::disk('public')->delete('images/' . $hero->image);
            }
            
            $hero->update($heroData);
            $message = 'Hero successfully updated!';
        } else {
            Hero::create($heroData);
            $message = 'Hero successfully created!';
        }

        $this->reset(['title', 'image', 'currentImage', 'order', 'is_active', 'editingHeroId']);
        $this->showModal = false;

        session()->flash('message', $message);
    }

    public function delete($heroId)
    {
        $hero = Hero::findOrFail($heroId);
        
        // Delete image file if exists
        if ($hero->image) {
            \Storage::disk('public')->delete('images/' . $hero->image);
        }
        
        $hero->delete();
        session()->flash('message', 'Hero successfully deleted!');

        $this->modal("delete-hero-{$heroId}")->close();
    }

    public function resetForm()
    {
        $this->reset(['title', 'image', 'currentImage', 'order', 'is_active', 'editingHeroId']);
        $this->resetValidation();
    }

    private function getNextOrder(): int
    {
        return Hero::max('order') + 1;
    }

    public function render()
    {
        return view('livewire.manage-heroes');
    }
}
