<?php

namespace App\Livewire\Forms;

use App\Models\Product;
use Livewire\Attributes\Rule;
use Livewire\Form;

class ProductForm extends Form
{
    public ?Product $product = null;

    #[Rule(['required', 'string', 'max:255'])]
    public string $name = '';

    #[Rule(['nullable', 'string'])]
    public string $description = '';

    #[Rule(['required', 'integer', 'min:0'])]
    public int $price = 0;

    #[Rule(['required', 'integer', 'min:0'])]
    public int $stock = 0;

    public function setProduct(Product $product): void
    {
        $this->product = $product;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->price = $product->price;
        $this->stock = $product->stock;
    }

    public function store(): void
    {
        $this->validate();

        Product::create($this->all());

        $this->reset();
    }

    public function update(): void
    {
        $this->validate();

        $this->product->update($this->all());

        $this->reset();
    }
}
