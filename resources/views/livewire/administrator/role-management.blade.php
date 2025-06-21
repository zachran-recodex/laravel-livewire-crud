<div>
    <header class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Manajemen Role</flux:heading>
            <flux:subheading>Kelola role dan permission mereka</flux:subheading>
        </div>

        <flux:button wire:click="create" variant="primary" icon="plus">
            Tambah Role
        </flux:button>
    </header>

    @if (session()->has('message'))
        <flux:callout variant="success" icon="check-circle" heading="{{ session('message') }}" class="mb-6" />
    @endif

    <div class="mb-6">
        <flux:input wire:model.live.debounce.300ms="search" placeholder="Cari role..." icon="magnifying-glass" />
    </div>

    <div class="border border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Nama Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Permission</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Dibuat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Aksi</th>
                </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-900 divide-y divide-zinc-200 dark:divide-zinc-700">
                @forelse ($this->roles as $role)
                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $role->name }}</td>
                        <td class="px-6 py-4 text-sm text-zinc-500 dark:text-zinc-400">
                            <div class="flex flex-wrap gap-1">
                                @foreach ($role->permissions as $permission)
                                    <flux:badge variant="outline" size="sm">
                                        {{ $permission->name }}
                                    </flux:badge>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">{{ $role->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <flux:button wire:click="edit({{ $role->id }})" size="sm" variant="ghost" icon="pencil" />
                                <flux:modal.trigger name="delete-role-{{ $role->id }}">
                                    <flux:button size="sm" variant="danger" icon="trash" />
                                </flux:modal.trigger>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-zinc-500 dark:text-zinc-400">
                            Tidak ada role ditemukan
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $this->roles->links('custom.pagination') }}
    </div>

    <!-- Modal Form -->
    <flux:modal wire:model.self="showModal" name="role-form" class="min-w-2xl max-w-3xl" wire:close="resetForm">
        <form wire:submit.prevent="save">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">
                        {{ $editingRoleId ? 'Edit Role' : 'Tambah Role Baru' }}
                    </flux:heading>
                    <flux:text class="mt-2">
                        {{ $editingRoleId ? 'Ubah detail role dan permission yang dipilih.' : 'Buat role baru dengan permission yang sesuai.' }}
                    </flux:text>
                </div>

                <flux:field>
                    <flux:label>Nama Role</flux:label>
                    <flux:input wire:model="name" placeholder="Masukkan nama role..." />
                    <flux:error name="name" />
                </flux:field>

                <flux:field>
                    <flux:label>Permission</flux:label>
                    <div class="space-y-2 max-h-40 overflow-y-auto border border-zinc-200 dark:border-zinc-700 rounded-lg p-3">
                        @foreach ($this->permissions as $permission)
                            <flux:checkbox
                                wire:model="selectedPermissions"
                                value="{{ $permission->name }}"
                                :label="$permission->name"
                            />
                        @endforeach
                    </div>
                    <flux:error name="selectedPermissions" />
                    <flux:description>
                        Pilih permission yang akan dimiliki oleh role ini
                    </flux:description>
                </flux:field>

                <div class="flex gap-2">
                    <flux:spacer />

                    <flux:modal.close>
                        <flux:button variant="ghost">Batal</flux:button>
                    </flux:modal.close>

                    <flux:button type="submit" variant="primary">
                        {{ $editingRoleId ? 'Update' : 'Simpan' }}
                    </flux:button>
                </div>
            </div>
        </form>
    </flux:modal>

    <!-- Delete Confirmation Modals -->
    @foreach ($this->roles as $role)
        <flux:modal name="delete-role-{{ $role->id }}" class="min-w-[22rem]">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Hapus Role?</flux:heading>
                    <flux:text class="mt-2">
                        <p>Anda akan menghapus role "{{ $role->name }}".</p>
                        <p>Tindakan ini tidak dapat dibatalkan.</p>
                    </flux:text>
                </div>

                <div class="flex gap-2">
                    <flux:spacer />

                    <flux:modal.close>
                        <flux:button variant="ghost">Batal</flux:button>
                    </flux:modal.close>

                    <flux:button wire:click="delete({{ $role->id }})" variant="danger">
                        Hapus Role
                    </flux:button>
                </div>
            </div>
        </flux:modal>
    @endforeach
</div>
