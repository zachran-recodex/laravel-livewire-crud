<?php

namespace App\Livewire;

use App\Livewire\Forms\ProductForm;
use App\Models\Product;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class ManageProducts extends Component
{
    use WithPagination;

    public ProductForm $form;

    public bool $showModal = false;

    public string $search = '';

    public string $sortField = 'created_at';

    public string $sortDirection = 'desc';

    #[Computed]
    public function products()
    {
        return Product::query()
            ->where('name', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);
    }

    public function create(): void
    {
        $this->form->reset();
        $this->showModal = true;
    }

    public function edit(Product $product): void
    {
        $this->form->setProduct($product);
        $this->showModal = true;
    }

    public function save(): void
    {
        if ($this->form->product) {
            $this->form->update();
            session()->flash('message', 'Product successfully updated!');
        } else {
            $this->form->store();
            session()->flash('message', 'Product successfully created!');
        }

        $this->showModal = false;
    }

    public function delete(Product $product): void
    {
        $product->delete();
        session()->flash('message', 'Product successfully deleted!');
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;
    }

    public function render()
    {
        return view('livewire.manage-products');
    }
}
