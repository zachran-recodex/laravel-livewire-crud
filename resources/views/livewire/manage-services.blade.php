<div>
    <header class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Service Management</flux:heading>
            <flux:subheading>Manage services for the website</flux:subheading>
        </div>

        <flux:button wire:click="create" variant="primary" icon="plus">
            Create
        </flux:button>
    </header>

    @if (session()->has('message'))
        <flux:callout variant="success" icon="check-circle" heading="{{ session('message') }}" class="mb-6" />
    @endif

    <div class="mb-6">
        <flux:input wire:model.live.debounce.300ms="search" placeholder="Search services..." icon="magnifying-glass" />
    </div>

    <div class="border border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>

                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Image</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Features</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Order</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Actions</th>
                </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-900 divide-y divide-zinc-200 dark:divide-zinc-700">
                @forelse ($this->services as $service)
                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $service->title }}</td>
                        <td class="px-6 py-4 text-sm text-zinc-500 dark:text-zinc-400 max-w-xs">
                            <div class="truncate">{{ $service->description }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                            @if($service->image)
                                <img src="{{ Storage::url($service->image) }}" alt="{{ $service->title }}" class="h-12 w-12 object-cover rounded-lg">
                            @else
                                <span class="text-zinc-400">No image</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-zinc-500 dark:text-zinc-400">
                            <div class="max-w-xs">
                                @if($service->features && count($service->features) > 0)
                                    <div class="flex flex-wrap gap-1">
                                        @foreach(array_slice($service->features, 0, 2) as $feature)
                                            <flux:badge color="blue" size="sm">{{ $feature }}</flux:badge>
                                        @endforeach
                                        @if(count($service->features) > 2)
                                            <flux:badge color="zinc" size="sm">+{{ count($service->features) - 2 }} more</flux:badge>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-zinc-400">No features</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">{{ $service->order }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                            @if($service->is_active)
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
                                <flux:button wire:click="edit({{ $service->id }})" size="sm" variant="primary" color="blue" icon="pencil" />
                                <flux:modal.trigger name="delete-service-{{ $service->id }}">
                                    <flux:button size="sm" variant="primary" color="red" icon="trash" />
                                </flux:modal.trigger>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-zinc-500 dark:text-zinc-400">
                            No services found
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $this->services->links('custom.pagination') }}
    </div>

    <!-- Modal Form -->
    <flux:modal wire:model.self="showModal" name="service-form" class="min-w-2xl max-w-4xl" wire:close="resetForm">
        <form wire:submit.prevent="save">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">
                        {{ $editingServiceId ? 'Edit Service' : 'Add New Service' }}
                    </flux:heading>
                    <flux:text class="mt-2">
                        {{ $editingServiceId ? 'Modify service information.' : 'Create a new service for the website.' }}
                    </flux:text>
                </div>

                <flux:field>
                    <flux:label>Title</flux:label>
                    <flux:input wire:model="title" placeholder="Enter service title..." />
                    <flux:error name="title" />
                </flux:field>

                <flux:field>
                    <flux:label>Description</flux:label>
                    <flux:textarea wire:model="description" placeholder="Enter service description..." rows="4" />
                    <flux:error name="description" />
                    <flux:description>
                        Describe what this service offers
                    </flux:description>
                </flux:field>

                <flux:field>
                    <flux:label>Image</flux:label>
                    <flux:input wire:model="image" type="file" accept="image/*" />
                    <flux:error name="image" />
                    <flux:description>
                        Upload an image file for the service
                    </flux:description>

                    <!-- Image Preview -->
                    <div class="mt-4">
                        @if($image && is_object($image))
                            <!-- Preview new uploaded image -->
                            <div class="space-y-2">
                                <flux:subheading>New Image Preview:</flux:subheading>
                                <img src="{{ $image->temporaryUrl() }}" alt="New image preview" class="h-32 w-auto object-cover rounded-lg border">
                            </div>
                        @elseif($editingServiceId && $currentImage)
                            <!-- Show current image in edit mode -->
                            <div class="space-y-2">
                                <flux:subheading>Current Image:</flux:subheading>
                                <img src="{{ Storage::url($currentImage) }}" alt="Current image" class="h-32 w-auto object-cover rounded-lg border">
                            </div>
                        @endif
                    </div>
                </flux:field>

                <flux:field>
                    <flux:label>Features</flux:label>
                    <div class="space-y-3">
                        <!-- Add new feature -->
                        <div class="flex gap-2">
                            <flux:input wire:model="newFeature" placeholder="Enter a feature..." class="flex-1" />
                            <flux:button wire:click="addFeature" type="button" variant="primary" icon="plus">Add</flux:button>
                        </div>

                        <!-- Display current features -->
                        @if(count($features) > 0)
                            <div class="space-y-2">
                                @foreach($features as $index => $feature)
                                    <div class="flex items-center gap-2 p-2 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                                        <span class="flex-1 text-sm">{{ $feature }}</span>
                                        <flux:button wire:click="removeFeature({{ $index }})" type="button" size="sm" variant="danger" icon="trash" />
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <flux:error name="features" />
                    <flux:description>
                        Add key features or benefits of this service
                    </flux:description>
                </flux:field>

                <flux:field>
                    <flux:label>Order</flux:label>
                    <flux:input wire:model="order" type="number" min="0" placeholder="Enter order..." />
                    <flux:error name="order" />
                    <flux:description>
                        Display order for service (lower numbers appear first)
                    </flux:description>
                </flux:field>

                <flux:field>
                    <flux:label>Status</flux:label>
                    <flux:checkbox wire:model="is_active" label="Active" />
                    <flux:error name="is_active" />
                    <flux:description>
                        Check if you want the service to be displayed on the website
                    </flux:description>
                </flux:field>

                <div class="flex gap-2">
                    <flux:spacer />

                    <flux:modal.close>
                        <flux:button variant="ghost">Cancel</flux:button>
                    </flux:modal.close>

                    <flux:button type="submit" variant="primary">
                        {{ $editingServiceId ? 'Update' : 'Save' }}
                    </flux:button>
                </div>
            </div>
        </form>
    </flux:modal>

    <!-- Delete Confirmation Modals -->
    @foreach ($this->services as $service)
        <flux:modal name="delete-service-{{ $service->id }}" class="min-w-[22rem]">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Delete Service?</flux:heading>
                    <flux:text class="mt-2">
                        <p>You are about to delete service "{{ $service->title }}".</p>
                        <p>This action cannot be undone.</p>
                    </flux:text>
                </div>

                <div class="flex gap-2">
                    <flux:spacer />

                    <flux:modal.close>
                        <flux:button variant="ghost">Cancel</flux:button>
                    </flux:modal.close>

                    <flux:button wire:click="delete({{ $service->id }})" variant="danger">
                        Delete
                    </flux:button>
                </div>
            </div>
        </flux:modal>
    @endforeach
</div>
