# Menambahkan Modul Baru (CRUD)

Panduan ini menjelaskan langkah-langkah untuk menambahkan modul CRUD (Create, Read, Update, Delete) baru ke proyek Laravel Livewire ini, mengikuti pola yang sudah ada (misalnya, modul Produk, Pengguna, Peran, Izin).

Proyek ini menggunakan:
*   **Livewire Volt**: Untuk komponen frontend interaktif.
*   **Form Objects**: Untuk mengelola data formulir dan validasi.
*   **Flux UI**: Untuk komponen antarmuka pengguna.
*   **Spatie Laravel Permission**: Untuk otorisasi (jika modul memerlukan izin).

## Struktur Modul CRUD

Setiap modul CRUD biasanya terdiri dari komponen-komponen berikut:

*   **Model Eloquent**: Representasi tabel database.
*   **Migrasi Database**: Untuk membuat atau memodifikasi tabel database.
*   **Livewire Component**: Komponen utama yang menangani logika CRUD dan interaksi UI.
*   **Form Object**: Kelas terpisah untuk mengelola data formulir dan validasi.
*   **Blade View**: Tampilan yang terkait dengan komponen Livewire.
*   **Rute Web**: Untuk mengakses modul.
*   **(Opsional) Seeder & Factory**: Untuk mengisi data dummy.
*   **(Opsional) Tes**: Untuk memastikan fungsionalitas bekerja dengan benar.

## Langkah-langkah Menambahkan Modul Baru

Misalkan kita ingin menambahkan modul untuk mengelola `Categories`.

### 1. Buat Model dan Migrasi

Buat model Eloquent dan migrasi database yang sesuai.

```bash
php artisan make:model Category -m
```

Edit file migrasi yang baru dibuat (`database/migrations/..._create_categories_table.php`) untuk mendefinisikan skema tabel `categories`.

```php
// database/migrations/..._create_categories_table.php
Schema::create('categories', function (Blueprint $table) {
    $table->id();
    $table->string('name')->unique();
    $table->text('description')->nullable();
    $table->timestamps();
});
```

Jalankan migrasi:

```bash
php artisan migrate
```

### 2. Buat Form Object

Buat Form Object untuk mengelola data formulir `Category`.

```bash
php artisan make:livewire:form CategoryForm
```

Edit file `app/Livewire/Forms/CategoryForm.php`:

```php
<?php

namespace App\Livewire\Forms;

use App\Models\Category;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CategoryForm extends Form
{
    public ?Category $category = null;

    #[Validate('required|string|min:3|max:255|unique:categories,name')]
    public string $name = '';

    #[Validate('nullable|string|max:1000')]
    public ?string $description = null;

    public function setCategory(Category $category)
    {
        $this->category = $category;
        $this->name = $category->name;
        $this->description = $category->description;
    }

    public function store()
    {
        $this->validate();

        Category::create($this->all());

        $this->reset();
    }

    public function update()
    {
        $this->validate();

        $this->category->update($this->all());

        $this->reset();
    }
}
```

### 3. Buat Livewire Component

Buat komponen Livewire utama untuk manajemen `Category`. Ikuti pola yang ada di `app/Livewire/Administrator/ManageUsers.php` atau `app/Livewire/ManageProducts.php`.

```bash
php artisan make:livewire Administrator/ManageCategories
```

Edit file `app/Livewire/Administrator/ManageCategories.php`:

```php
<?php

namespace App\Livewire\Administrator;

use App\Livewire\Forms\CategoryForm;
use App\Models\Category;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Flux\Flow;

#[Layout('components.layouts.app')]
class ManageCategories extends Component
{
    use WithPagination;

    public CategoryForm $form;

    public bool $showCategoryModal = false;

    public string $search = '';

    public string $sortField = 'name';

    public bool $sortAsc = true;

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortAsc = !$this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }

    #[Computed]
    public function categories()
    {
        return Category::search($this->search)
            ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
            ->paginate(10);
    }

    public function create(): void
    {
        $this->form->reset();
        $this->showCategoryModal = true;
    }

    public function edit(Category $category): void
    {
        $this->form->setCategory($category);
        $this->showCategoryModal = true;
    }

    #[On('category-saved')]
    #[On('category-deleted')]
    public function render()
    {
        return view('livewire.administrator.manage-categories');
    }

    public function save(): void
    {
        if ($this->form->category) {
            $this->form->update();
            $this->dispatch('category-saved');
            Flow::success('Category updated successfully.');
        } else {
            $this->form->store();
            $this->dispatch('category-saved');
            Flow::success('Category created successfully.');
        }

        $this->showCategoryModal = false;
    }

    public function delete(Category $category): void
    {
        $category->delete();
        $this->dispatch('category-deleted');
        Flow::success('Category deleted successfully.');
    }
}
```

### 4. Buat Blade View

Buat file tampilan Blade untuk komponen Livewire Anda di `resources/views/livewire/administrator/manage-categories.blade.php`. Gunakan komponen Flux UI yang sudah ada.

```blade
{{-- resources/views/livewire/administrator/manage-categories.blade.php --}}
<x-container title="Manage Categories" flow="true">
    <x-slot:actions>
        <x-button icon="plus" wire:click="create">
            Create Category
        </x-button>
    </x-slot:actions>

    <x-input wire:model.live="search" placeholder="Search categories..." class="mb-4" />

    <x-table>
        <x-thead>
            <x-tr>
                <x-th sortable wire:click="sortBy('name')" :direction="$sortField === 'name' ? ($sortAsc ? 'asc' : 'desc') : null">Name</x-th>
                <x-th>Description</x-th>
                <x-th>Actions</x-th>
            </x-tr>
        </x-thead>
        <x-tbody>
            @forelse($this->categories as $category)
                <x-tr wire:key="{{ $category->id }}">
                    <x-td>{{ $category->name }}</x-td>
                    <x-td>{{ $category->description }}</x-td>
                    <x-td>
                        <x-button.circle icon="pencil" wire:click="edit({{ $category->id }})" />
                        <x-button.circle icon="trash" wire:click="delete({{ $category->id }})" class="ml-2" />
                    </x-td>
                </x-tr>
            @empty
                <x-tr>
                    <x-td colspan="3" class="text-center">No categories found.</x-td>
                </x-tr>
            @endforelse
        </x-tbody>
    </x-table>

    <div class="mt-4">
        {{ $this->categories->links() }}
    </div>

    <x-modal wire:model="showCategoryModal" title="{{ $this->form->category ? 'Edit Category' : 'Create Category' }}">
        <x-form wire:submit="save">
            <x-input label="Name" wire:model="form.name" />
            <x-textarea label="Description" wire:model="form.description" />

            <x-slot:actions>
                <x-button type="button" wire:click="$set('showCategoryModal', false)">Cancel</x-button>
                <x-button type="submit" primary>{{ $this->form->category ? 'Update' : 'Create' }}</x-button>
            </x-slot:actions>
        </x-form>
    </x-modal>
</x-container>
```

### 5. Tambahkan Rute

Tambahkan rute untuk modul baru Anda di `routes/web.php`.

```php
// routes/web.php
use App\Livewire\Administrator\ManageCategories;

Route::middleware(['auth', 'verified', 'role:Super Admin'])->group(function () {
    // ... rute lainnya
    Route::get('admin/categories', ManageCategories::class)->name('admin.categories');
});
```

### 6. Perbarui Navigasi (Opsional)

Jika Anda ingin modul ini muncul di sidebar navigasi, perbarui file `resources/views/components/layouts/sidebar.blade.php` atau file navigasi yang relevan.

```blade
{{-- resources/views/components/layouts/sidebar.blade.php --}}
<x-navlist.item href="{{ route('admin.categories') }}" icon="folder-open">
    Categories
</x-navlist.item>
```

### 7. Tambahkan Seeder dan Factory (Opsional)

Untuk mengisi data dummy, buat seeder dan factory.

```bash
php artisan make:factory CategoryFactory --model=Category
php artisan make:seeder CategorySeeder
```

Edit `database/factories/CategoryFactory.php`:

```php
<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
            'description' => $this->faker->sentence(),
        ];
    }
}
```

Edit `database/seeders/CategorySeeder.php`:

```php
<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::factory()->count(10)->create();
    }
}
```

Panggil seeder dari `database/seeders/DatabaseSeeder.php`:

```php
// database/seeders/DatabaseSeeder.php
public function run(): void
{
    $this->call([
        RolesAndPermissionsSeeder::class,
        UserSeeder::class,
        ProductSeeder::class,
        CategorySeeder::class, // Tambahkan ini
    ]);
}
```

Jalankan seeder:

```bash
php artisan db:seed --class=CategorySeeder
```

### 8. Tambahkan Tes (Opsional)

Buat tes fitur untuk modul baru Anda di `tests/Feature/Administrator/ManageCategoriesTest.php`.

```bash
php artisan make:test Feature/Administrator/ManageCategoriesTest
```

Pastikan untuk mengikuti pola pengujian yang ada di proyek.

## Praktik Terbaik

*   Selalu merujuk pada `docs/LARAVEL-BEST-PRACTICES.md` dan `docs/LIVEWIRE-BEST-PRACTICES.md` untuk panduan coding.
*   Gunakan komponen Flux UI yang sudah ada sebisa mungkin untuk menjaga konsistensi UI.
*   Pastikan otorisasi yang tepat diterapkan menggunakan Spatie Laravel Permission.
