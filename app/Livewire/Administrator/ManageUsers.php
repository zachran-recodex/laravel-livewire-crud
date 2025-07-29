<?php

namespace App\Livewire\Administrator;

use App\Livewire\Forms\UserForm;
use App\Services\UserService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class ManageUsers extends Component
{
    use WithPagination;

    public UserForm $form;

    public string $search = '';

    public bool $showModal = false;

    public bool $showDeleteModal = false;

    public ?int $userToDelete = null;

    public string $modalTitle = '';

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function boot(UserService $userService): void
    {
        $this->userService = $userService;
    }

    protected UserService $userService;

    public function mount(): void
    {
        $this->resetForm();
    }

    #[Computed]
    public function users()
    {
        if ($this->search) {
            return $this->userService->searchUsers($this->search, 10);
        }

        return $this->userService->getUsersWithRoles(10);
    }

    #[Computed]
    public function roles()
    {
        return $this->userService->getAllRoles();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->resetForm();
        $this->modalTitle = 'Tambah User Baru';
        $this->showModal = true;
    }

    public function edit(int $userId): void
    {
        try {
            $user = $this->userService->findUserById($userId);
            $this->form->setUser($user);
            $this->modalTitle = 'Edit User';
            $this->showModal = true;
        } catch (\Exception $e) {
            session()->flash('error', 'User tidak ditemukan.');
        }
    }

    public function save(): void
    {
        try {
            if ($this->isEditMode()) {
                $this->updateUser();
            } else {
                $this->createUser();
            }

            $this->closeModal();
            $this->dispatch('user-saved');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    public function confirmDelete(int $userId): void
    {
        $this->userToDelete = $userId;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        if (! $this->userToDelete) {
            return;
        }

        try {
            $user = $this->userService->findUserById($this->userToDelete);
            $this->userService->deleteUser($user);

            session()->flash('message', 'User berhasil dihapus!');
            $this->closeDeleteModal();
            $this->dispatch('user-deleted');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus user: '.$e->getMessage());
        }
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetErrorBag();
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->userToDelete = null;
    }

    #[On('user-saved')]
    public function handleUserSaved(): void
    {
        // Optional: refresh users list or show notification
        $this->resetPage();
    }

    #[On('user-deleted')]
    public function handleUserDeleted(): void
    {
        // Optional: refresh users list or show notification
        $this->resetPage();
    }

    public function render(): View
    {
        return view('livewire.administrator.manage-users');
    }

    private function isEditMode(): bool
    {
        return ! is_null($this->form->user);
    }

    private function createUser(): void
    {
        $user = $this->userService->createUser(
            $this->form->getUserData(),
            $this->form->selectedRoles
        );

        session()->flash('message', 'User berhasil dibuat!');
    }

    private function updateUser(): void
    {
        $user = $this->userService->updateUser(
            $this->form->user,
            $this->form->getUserData(),
            $this->form->selectedRoles
        );

        session()->flash('message', 'User berhasil diperbarui!');
    }

    private function resetForm(): void
    {
        $this->form->reset();
        $this->modalTitle = '';
    }
}
