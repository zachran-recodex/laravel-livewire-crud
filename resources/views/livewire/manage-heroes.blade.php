<div>
    <header class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Hero Management</flux:heading>
            <flux:subheading>Manage heroes for the website</flux:subheading>
        </div>

        <flux:button wire:click="create" variant="primary" icon="plus">
            Create
        </flux:button>
    </header>

    @if (session()->has('message'))
        <flux:callout variant="success" icon="check-circle" heading="{{ session('message') }}" class="mb-6" />
    @endif

    <div class="mb-6">
        <flux:input wire:model.live.debounce.300ms="search" placeholder="Search heroes..." icon="magnifying-glass" />
    </div>

    <div class="border border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Image</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Order</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Actions</th>
                </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-900 divide-y divide-zinc-200 dark:divide-zinc-700">
                @forelse ($this->heroes as $hero)
                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $hero->title }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                            @if($hero->image)
                                <img src="{{ Storage::url('images/' . $hero->image) }}" alt="{{ $hero->title }}" class="h-12 w-12 object-cover rounded-lg">
                            @else
                                <span class="text-zinc-400">No image</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">{{ $hero->order }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                            @if($hero->is_active)
                                <flux:badge color="green" size="sm">
                                    Active
                                </flux:badge>
                            @else
                                <flux:badge color="red" size="sm">
                                    Inactive
                                </flux:badge>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <flux:button wire:click="edit({{ $hero->id }})" size="sm" variant="primary" color="blue" icon="pencil" />
                                <flux:modal.trigger name="delete-hero-{{ $hero->id }}">
                                    <flux:button size="sm" variant="primary" color="red" icon="trash" />
                                </flux:modal.trigger>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-zinc-500 dark:text-zinc-400">
                            No heroes found
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $this->heroes->links('custom.pagination') }}
    </div>

    <!-- Modal Form -->
    <flux:modal wire:model.self="showModal" name="hero-form" class="min-w-2xl max-w-3xl" wire:close="resetForm">
        <form wire:submit.prevent="save">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">
                        {{ $editingHeroId ? 'Edit Hero' : 'Add New Hero' }}
                    </flux:heading>
                    <flux:text class="mt-2">
                        {{ $editingHeroId ? 'Modify hero information.' : 'Create a new hero for the website.' }}
                    </flux:text>
                </div>

                <flux:field>
                    <flux:label>Title</flux:label>
                    <flux:input wire:model="title" placeholder="Enter hero title..." />
                    <flux:error name="title" />
                </flux:field>

                <flux:field>
                    <flux:label>Image</flux:label>
                    <flux:input wire:model="image" type="file" accept="image/*" />
                    <flux:error name="image" />
                    <flux:description>
                        Upload an image file for the hero
                    </flux:description>

                    <!-- Image Preview -->
                    <div class="mt-4">
                        @if($image && is_object($image))
                            <!-- Preview new uploaded image -->
                            <div class="space-y-2">
                                <flux:subheading>New Image Preview:</flux:subheading>
                                <img src="{{ $image->temporaryUrl() }}" alt="New image preview" class="h-32 w-auto object-cover rounded-lg border">
                            </div>
                        @elseif($editingHeroId && $currentImage)
                            <!-- Show current image in edit mode -->
                            <div class="space-y-2">
                                <flux:subheading>Current Image:</flux:subheading>
                                <img src="{{ Storage::url('images/' . $currentImage) }}" alt="Current image" class="h-32 w-auto object-cover rounded-lg border">
                            </div>
                        @endif
                    </div>
                </flux:field>

                <flux:field>
                    <flux:label>Order</flux:label>
                    <flux:input wire:model="order" type="number" min="0" placeholder="Enter order..." />
                    <flux:error name="order" />
                    <flux:description>
                        Display order for hero (lower numbers appear first)
                    </flux:description>
                </flux:field>

                <flux:field>
                    <flux:label>Status</flux:label>
                    <flux:checkbox wire:model="is_active" label="Active" />
                    <flux:error name="is_active" />
                    <flux:description>
                        Check if you want the hero to be displayed on the website
                    </flux:description>
                </flux:field>

                <div class="flex gap-2">
                    <flux:spacer />

                    <flux:modal.close>
                        <flux:button variant="ghost">Cancel</flux:button>
                    </flux:modal.close>

                    <flux:button type="submit" variant="primary">
                        {{ $editingHeroId ? 'Update' : 'Save' }}
                    </flux:button>
                </div>
            </div>
        </form>
    </flux:modal>

    <!-- Delete Confirmation Modals -->
    @foreach ($this->heroes as $hero)
        <flux:modal name="delete-hero-{{ $hero->id }}" class="min-w-[22rem]">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Delete Hero?</flux:heading>
                    <flux:text class="mt-2">
                        <p>You are about to delete hero "{{ $hero->title }}".</p>
                        <p>This action cannot be undone.</p>
                    </flux:text>
                </div>

                <div class="flex gap-2">
                    <flux:spacer />

                    <flux:modal.close>
                        <flux:button variant="ghost">Cancel</flux:button>
                    </flux:modal.close>

                    <flux:button wire:click="delete({{ $hero->id }})" variant="danger">
                        Delete
                    </flux:button>
                </div>
            </div>
        </flux:modal>
    @endforeach
</div>
