<div>
    <header class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Manajemen User</flux:heading>
            <flux:subheading>Kelola pengguna dan role mereka</flux:subheading>
        </div>

        <flux:button wire:click="create" variant="primary" icon="plus">
            Tambah User
        </flux:button>
    </header>

    @if (session()->has('message'))
        <flux:callout variant="success" icon="check-circle" heading="{{ session('message') }}" class="mb-6" />
    @endif

    <div class="mb-6">
        <flux:input wire:model.live.debounce.300ms="search" placeholder="Cari user..." icon="magnifying-glass" />
    </div>

    <div class="border border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Username</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Dibuat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Aksi</th>
                </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-900 divide-y divide-zinc-200 dark:divide-zinc-700">
                @forelse ($this->users as $user)
                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">{{ $user->username }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">{{ $user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                            @foreach ($user->roles as $role)
                                <flux:badge variant="primary" size="sm" class="mr-1">
                                    {{ $role->name }}
                                </flux:badge>
                            @endforeach
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <flux:button wire:click="edit({{ $user->id }})" size="sm" variant="ghost" icon="pencil" />
                                <flux:modal.trigger name="delete-user-{{ $user->id }}">
                                    <flux:button size="sm" variant="danger" icon="trash" />
                                </flux:modal.trigger>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-zinc-500 dark:text-zinc-400">
                            Tidak ada user ditemukan
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

    <!-- Modal Form -->
    <flux:modal wire:model.self="showModal" name="user-form" class="min-w-2xl max-w-3xl" wire:close="resetForm">
        <form wire:submit.prevent="save">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">
                        {{ $editingUserId ? 'Edit User' : 'Tambah User Baru' }}
                    </flux:heading>
                    <flux:text class="mt-2">
                        {{ $editingUserId ? 'Ubah informasi user dan role yang dipilih.' : 'Buat user baru dengan role yang sesuai.' }}
                    </flux:text>
                </div>

                <flux:field>
                    <flux:label>Nama</flux:label>
                    <flux:input wire:model="name" placeholder="Masukkan nama..." />
                    <flux:error name="name" />
                </flux:field>

                <flux:field>
                    <flux:label>Username</flux:label>
                    <flux:input wire:model="username" placeholder="Masukkan username..." />
                    <flux:error name="username" />
                </flux:field>

                <flux:field>
                    <flux:label>Email</flux:label>
                    <flux:input wire:model="email" type="email" placeholder="Masukkan email..." />
                    <flux:error name="email" />
                </flux:field>

                <flux:field>
                    @if($editingUserId)
                        <flux:label badge="Kosongkan jika tidak ingin mengubah">
                            Password
                        </flux:label>
                    @else
                        <flux:label>
                            Password
                        </flux:label>
                    @endif
                    <flux:input wire:model="password" type="password" placeholder="Masukkan password..." />
                    <flux:error name="password" />
                </flux:field>

                <flux:field>
                    <flux:label>Role</flux:label>
                    <div class="space-y-2 max-h-32 overflow-y-auto border border-zinc-200 dark:border-zinc-700 rounded-lg p-3">
                        @foreach ($this->roles as $role)
                            <flux:checkbox
                                wire:model="selectedRoles"
                                value="{{ $role->name }}"
                                :label="$role->name"
                            />
                        @endforeach
                    </div>
                    <flux:error name="selectedRoles" />
                    <flux:description>
                        Pilih role yang akan dimiliki oleh user ini
                    </flux:description>
                </flux:field>

                <div class="flex gap-2">
                    <flux:spacer />

                    <flux:modal.close>
                        <flux:button variant="ghost">Batal</flux:button>
                    </flux:modal.close>

                    <flux:button type="submit" variant="primary">
                        {{ $editingUserId ? 'Update' : 'Simpan' }}
                    </flux:button>
                </div>
            </div>
        </form>
    </flux:modal>

    <!-- Delete Confirmation Modals -->
    @foreach ($this->users as $user)
        <flux:modal name="delete-user-{{ $user->id }}" class="min-w-[22rem]">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Hapus User?</flux:heading>
                    <flux:text class="mt-2">
                        <p>Anda akan menghapus user "{{ $user->name }}" ({{ $user->email }}).</p>
                        <p>Tindakan ini tidak dapat dibatalkan.</p>
                    </flux:text>
                </div>

                <div class="flex gap-2">
                    <flux:spacer />

                    <flux:modal.close>
                        <flux:button variant="ghost">Batal</flux:button>
                    </flux:modal.close>

                    <flux:button wire:click="delete({{ $user->id }})" variant="danger">
                        Hapus User
                    </flux:button>
                </div>
            </div>
        </flux:modal>
    @endforeach
</div>
