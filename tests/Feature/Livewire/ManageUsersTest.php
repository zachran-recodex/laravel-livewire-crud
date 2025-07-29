<?php

namespace Tests\Feature\Livewire;

use App\Livewire\Administrator\ManageUsers;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ManageUsersTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        // Create some basic roles for testing
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);
    }

    public function test_can_render_component(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        Livewire::actingAs($admin)
            ->test(ManageUsers::class)
            ->assertStatus(200)
            ->assertSee('User Management'); // Assuming this text exists in the view
    }

    public function test_can_create_user(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        Livewire::actingAs($admin)
            ->test(ManageUsers::class)
            ->set('form.name', 'John Doe')
            ->set('form.username', 'johndoe')
            ->set('form.email', 'john@example.com')
            ->set('form.password', 'password123')
            ->set('form.password_confirmation', 'password123')
            ->set('form.selectedRoles', ['user'])
            ->call('save')
            ->assertHasNoErrors()
            ->assertDispatched('user-saved');

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'john@example.com',
        ]);
    }

    public function test_can_edit_user(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $user = User::factory()->create([
            'name' => 'Original Name',
            'username' => 'original',
            'email' => 'original@example.com',
        ]);

        Livewire::actingAs($admin)
            ->test(ManageUsers::class)
            ->call('edit', $user->id)
            ->assertSet('form.name', 'Original Name')
            ->assertSet('form.username', 'original')
            ->assertSet('form.email', 'original@example.com')
            ->set('form.name', 'Updated Name')
            ->call('save')
            ->assertHasNoErrors()
            ->assertDispatched('user-saved');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
        ]);
    }

    public function test_can_delete_user(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $user = User::factory()->create();

        Livewire::actingAs($admin)
            ->test(ManageUsers::class)
            ->call('confirmDelete', $user->id)
            ->assertSet('userToDelete', $user->id)
            ->assertSet('showDeleteModal', true)
            ->call('delete')
            ->assertHasNoErrors()
            ->assertDispatched('user-deleted');

        $this->assertSoftDeleted('users', [
            'id' => $user->id,
        ]);
    }

    public function test_can_search_users(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        User::factory()->create(['name' => 'John Doe']);
        User::factory()->create(['name' => 'Jane Smith']);

        Livewire::actingAs($admin)
            ->test(ManageUsers::class)
            ->set('search', 'John')
            ->call('updatedSearch')
            ->assertSee('John Doe')
            ->assertDontSee('Jane Smith');
    }

    public function test_validation_rules_work(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        Livewire::actingAs($admin)
            ->test(ManageUsers::class)
            ->set('form.name', '')
            ->set('form.username', '')
            ->set('form.email', 'invalid-email')
            ->set('form.password', '123') // Too short
            ->call('save')
            ->assertHasErrors([
                'form.name',
                'form.username',
                'form.email',
                'form.password',
            ]);
    }

    public function test_cannot_create_duplicate_username(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $existingUser = User::factory()->create(['username' => 'johndoe']);

        Livewire::actingAs($admin)
            ->test(ManageUsers::class)
            ->set('form.name', 'Another John')
            ->set('form.username', 'johndoe')
            ->set('form.email', 'anotherjohn@example.com')
            ->set('form.password', 'password123')
            ->set('form.password_confirmation', 'password123')
            ->call('save')
            ->assertHasErrors(['form.username']);
    }

    public function test_can_assign_roles_to_user(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        Livewire::actingAs($admin)
            ->test(ManageUsers::class)
            ->set('form.name', 'John Doe')
            ->set('form.username', 'johndoe')
            ->set('form.email', 'john@example.com')
            ->set('form.password', 'password123')
            ->set('form.password_confirmation', 'password123')
            ->set('form.selectedRoles', ['admin', 'user'])
            ->call('save')
            ->assertHasNoErrors();

        $user = User::where('username', 'johndoe')->first();
        $this->assertTrue($user->hasRole(['admin', 'user']));
    }

    public function test_modal_state_management(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        Livewire::actingAs($admin)
            ->test(ManageUsers::class)
            ->call('create')
            ->assertSet('showModal', true)
            ->assertSet('modalTitle', 'Tambah User Baru')
            ->call('closeModal')
            ->assertSet('showModal', false);
    }
}
