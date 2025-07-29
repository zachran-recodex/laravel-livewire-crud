<div>
    <header class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">User Management</flux:heading>
            <flux:subheading>Manage users and their roles</flux:subheading>
        </div>

        <div class="flex items-center gap-4">
            <!-- Search Input -->
            <flux:input 
                wire:model.live.debounce.300ms="search" 
                placeholder="Search users..." 
                icon="magnifying-glass"
                class="w-64"
            />
            
            <flux:button wire:click="create" variant="primary" icon="plus">
                Create
            </flux:button>
        </div>
    </header>

    @if (session()->has('message'))
        <flux:callout variant="success" icon="check-circle" heading="{{ session('message') }}" class="mb-6" />
    @endif

    @if (session()->has('error'))
        <flux:callout variant="danger" icon="exclamation-triangle" heading="{{ session('error') }}" class="mb-6" />
    @endif

    <div class="border border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Username</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Roles</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Created</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Actions</th>
                </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-900 divide-y divide-zinc-200 dark:divide-zinc-700">
                @forelse ($this->users as $user)
                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">{{ $user->username }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">{{ $user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                            @if($user->roles->count() > 0)
                                @foreach ($user->roles as $role)
                                    <flux:badge variant="primary" size="sm" class="mr-1">
                                        {{ $role->name }}
                                    </flux:badge>
                                @endforeach
                            @else
                                <span class="text-zinc-400 italic">No roles assigned</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                            {{ $user->created_at->format('d F Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <flux:button wire:click="edit({{ $user->id }})" size="sm" variant="primary" color="blue" icon="pencil">
                                    Edit
                                </flux:button>
                                <flux:button wire:click="confirmDelete({{ $user->id }})" size="sm" variant="primary" color="red" icon="trash">
                                    Delete
                                </flux:button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-zinc-500 dark:text-zinc-400">
                            @if($search)
                                No users found matching "{{ $search }}"
                            @else
                                No users found
                            @endif
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $this->users->links('custom.pagination') }}
    </div>

    <!-- Create/Edit Modal -->
    <flux:modal wire:model="showModal" name="user-form" class="min-w-2xl max-w-3xl">
        <form wire:submit.prevent="save">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">{{ $modalTitle }}</flux:heading>
                    <flux:text class="mt-2">
                        {{ $form->user ? 'Modify user information and selected roles.' : 'Create a new user with appropriate roles.' }}
                    </flux:text>
                </div>

                <div wire:loading.delay wire:target="save" class="flex items-center gap-2 text-blue-600">
                    <flux:icon.arrow-path class="animate-spin size-4" />
                    <span>{{ $form->user ? 'Updating...' : 'Creating...' }}</span>
                </div>

                <flux:field>
                    <flux:label>Name *</flux:label>
                    <flux:input wire:model="form.name" placeholder="Enter full name..." />
                    <flux:error name="form.name" />
                </flux:field>

                <flux:field>
                    <flux:label>Username *</flux:label>
                    <flux:input wire:model="form.username" placeholder="Enter username..." />
                    <flux:error name="form.username" />
                    <flux:description>
                        Username must be unique and can only contain letters, numbers, dashes, and underscores.
                    </flux:description>
                </flux:field>

                <flux:field>
                    <flux:label>Email *</flux:label>
                    <flux:input wire:model="form.email" type="email" placeholder="Enter email address..." />
                    <flux:error name="form.email" />
                </flux:field>

                <flux:field>
                    <flux:label>Password {{ $form->user ? '' : '*' }}</flux:label>
                    <flux:input wire:model="form.password" type="password" placeholder="Enter password..." />
                    <flux:error name="form.password" />
                    @if($form->user)
                        <flux:description>
                            Leave empty if you don't want to change the password.
                        </flux:description>
                    @else
                        <flux:description>
                            Password must be at least 8 characters long.
                        </flux:description>
                    @endif
                </flux:field>

                <flux:field>
                    <flux:label>Confirm Password</flux:label>
                    <flux:input wire:model="form.password_confirmation" type="password" placeholder="Confirm password..." />
                    <flux:error name="form.password_confirmation" />
                </flux:field>

                <flux:field>
                    <flux:label>Roles</flux:label>
                    <div class="space-y-2 max-h-32 overflow-y-auto border border-zinc-200 dark:border-zinc-700 rounded-lg p-3">
                        @foreach ($this->roles as $role)
                            <flux:checkbox
                                wire:model="form.selectedRoles"
                                value="{{ $role->name }}"
                                :label="$role->name"
                            />
                        @endforeach
                    </div>
                    <flux:error name="form.selectedRoles" />
                    <flux:description>
                        Select one or more roles for this user.
                    </flux:description>
                </flux:field>

                <div class="flex gap-2">
                    <flux:spacer />

                    <flux:button wire:click="closeModal" variant="ghost" type="button">
                        Cancel
                    </flux:button>

                    <flux:button type="submit" variant="primary" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="save">
                            {{ $form->user ? 'Update User' : 'Create User' }}
                        </span>
                        <span wire:loading wire:target="save">
                            {{ $form->user ? 'Updating...' : 'Creating...' }}
                        </span>
                    </flux:button>
                </div>
            </div>
        </form>
    </flux:modal>

    <!-- Delete Confirmation Modal -->
    <flux:modal wire:model="showDeleteModal" name="delete-confirmation" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Delete User?</flux:heading>
                <flux:text class="mt-2">
                    <p>Are you sure you want to delete this user?</p>
                    <p class="font-medium text-red-600">This action cannot be undone.</p>
                </flux:text>
            </div>

            <div wire:loading.delay wire:target="delete" class="flex items-center gap-2 text-red-600">
                <flux:icon.arrow-path class="animate-spin size-4" />
                <span>Deleting...</span>
            </div>

            <div class="flex gap-2">
                <flux:spacer />

                <flux:button wire:click="closeDeleteModal" variant="ghost" type="button">
                    Cancel
                </flux:button>

                <flux:button wire:click="delete" variant="danger" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="delete">Delete User</span>
                    <span wire:loading wire:target="delete">Deleting...</span>
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
