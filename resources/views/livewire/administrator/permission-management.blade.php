<div>
    <header class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Permission Management</flux:heading>
            <flux:subheading>Manage permissions in the system</flux:subheading>
        </div>

        <flux:button wire:click="create" variant="primary" icon="plus">
            Add Permission
        </flux:button>
    </header>

    @if (session()->has('message'))
        <flux:callout variant="success" icon="check-circle" heading="{{ session('message') }}" class="mb-6" />
    @endif

    <div class="mb-6">
        <flux:input wire:model.live.debounce.300ms="search" placeholder="Search permissions..." icon="magnifying-glass" />
    </div>

    <div class="border border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Permission Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Guard</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Created</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Actions</th>
                </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-900 divide-y divide-zinc-200 dark:divide-zinc-700">
                @forelse ($this->permissions as $permission)
                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $permission->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                            <flux:badge variant="secondary" size="sm">
                                {{ $permission->guard_name }}
                            </flux:badge>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                            {{ $permission->created_at->format('d F Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <flux:button wire:click="edit({{ $permission->id }})" size="sm" variant="primary" color="blue" icon="pencil" />
                                <flux:modal.trigger name="delete-permission-{{ $permission->id }}">
                                    <flux:button size="sm" variant="primary" color="red" icon="trash" />
                                </flux:modal.trigger>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-zinc-500 dark:text-zinc-400">
                            No permissions found
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $this->permissions->links('custom.pagination') }}
    </div>

    <!-- Modal Form -->
    <flux:modal wire:model.self="showModal" name="permission-form" class="min-w-2xl max-w-3xl" wire:close="resetForm">
        <form wire:submit.prevent="save">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">
                        {{ $editingPermissionId ? 'Edit Permission' : 'Add New Permission' }}
                    </flux:heading>
                    <flux:text class="mt-2">
                        {{ $editingPermissionId ? 'Modify the selected permission details.' : 'Create a new permission for the system.' }}
                    </flux:text>
                </div>

                <flux:field>
                    <flux:label>Permission Name</flux:label>
                    <flux:input wire:model="name" placeholder="Example: create-posts, edit-users" />
                    <flux:error name="name" />
                    <flux:description>
                        Use kebab-case format like "create-posts" or "view-users"
                    </flux:description>
                </flux:field>

                <div class="flex gap-2">
                    <flux:spacer />

                    <flux:modal.close>
                        <flux:button variant="ghost">Cancel</flux:button>
                    </flux:modal.close>

                    <flux:button type="submit" variant="primary">
                        {{ $editingPermissionId ? 'Update' : 'Save' }}
                    </flux:button>
                </div>
            </div>
        </form>
    </flux:modal>

    <!-- Delete Confirmation Modals -->
    @foreach ($this->permissions as $permission)
        <flux:modal name="delete-permission-{{ $permission->id }}" class="min-w-[22rem]">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Delete Permission?</flux:heading>
                    <flux:text class="mt-2">
                        <p>You are about to delete permission "{{ $permission->name }}".</p>
                        <p>This action cannot be undone.</p>
                    </flux:text>
                </div>

                <div class="flex gap-2">
                    <flux:spacer />

                    <flux:modal.close>
                        <flux:button variant="ghost">Cancel</flux:button>
                    </flux:modal.close>

                    <flux:button wire:click="delete({{ $permission->id }})" variant="danger">
                        Delete Permission
                    </flux:button>
                </div>
            </div>
        </flux:modal>
    @endforeach
</div>
