<?php

namespace App\Livewire\Forms;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Form;

class UserForm extends Form
{
    #[Locked]
    public ?User $user = null;

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|string|max:255|alpha_dash')]
    public string $username = '';

    #[Validate('required|email|max:255')]
    public string $email = '';

    #[Validate('nullable|string|min:8')]
    public string $password = '';

    #[Validate('nullable|string|min:8|same:password')]
    public string $password_confirmation = '';

    #[Validate('array')]
    public array $selectedRoles = [];

    public function rules(): array
    {
        return (new UserRequest)->rules();
    }

    public function messages(): array
    {
        return (new UserRequest)->messages();
    }

    public function setUser(?User $user = null): void
    {
        $this->user = $user;

        if ($user) {
            $this->fill($this->extractUserData($user));
        }
    }

    public function store(): User
    {
        $this->validate();

        return User::create($this->getUserData());
    }

    public function update(): bool
    {
        $this->validate();

        return $this->user->update($this->getUserData());
    }

    public function getUserData(): array
    {
        $data = [
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
        ];

        if (! empty($this->password)) {
            $data['password'] = $this->password;
        }

        return $data;
    }

    public function reset(...$properties): void
    {
        $this->user = null;
        $this->name = '';
        $this->username = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->selectedRoles = [];

        parent::reset(...$properties);
    }

    private function extractUserData(User $user): array
    {
        return [
            'name' => $user->name,
            'username' => $user->username,
            'email' => $user->email,
            'selectedRoles' => $user->roles->pluck('name')->toArray(),
        ];
    }
}
