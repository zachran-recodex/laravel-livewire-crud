<?php

namespace Tests\Feature\Administrator;

use App\Livewire\Administrator\ManageRoles;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RoleManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create admin user for testing
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_can_render_role_management_component()
    {
        Livewire::test(ManageRoles::class)
            ->assertStatus(200);
    }

    public function test_can_create_new_role()
    {
        Livewire::test(ManageRoles::class)
            ->set('name', 'test-role')
            ->call('save')
            ->assertHasNoErrors()
            ->assertSet('name', '')
            ->assertSet('showModal', false);

        $this->assertDatabaseHas('roles', [
            'name' => 'test-role'
        ]);
    }

    public function test_can_edit_existing_role()
    {
        $role = Role::create(['name' => 'original-role']);

        Livewire::test(ManageRoles::class)
            ->call('edit', $role->id)
            ->assertSet('editingRoleId', $role->id)
            ->assertSet('name', 'original-role')
            ->assertSet('showModal', true)
            ->set('name', 'updated-role')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('roles', [
            'id' => $role->id,
            'name' => 'updated-role'
        ]);
    }

    public function test_can_delete_role()
    {
        $role = Role::create(['name' => 'delete-role']);

        Livewire::test(ManageRoles::class)
            ->call('delete', $role->id)
;

        $this->assertDatabaseMissing('roles', [
            'id' => $role->id
        ]);
    }

    public function test_validates_required_name_field()
    {
        Livewire::test(ManageRoles::class)
            ->set('name', '')
            ->call('save')
            ->assertHasErrors(['name' => 'required']);
    }

    public function test_validates_unique_role_name()
    {
        Role::create(['name' => 'existing-role']);

        Livewire::test(ManageRoles::class)
            ->set('name', 'existing-role')
            ->call('save')
            ->assertHasErrors(['name' => 'unique']);
    }

    public function test_can_reset_form()
    {
        Livewire::test(ManageRoles::class)
            ->set('name', 'test-role')
            ->set('editingRoleId', 1)
            ->call('resetForm')
            ->assertSet('name', '')
            ->assertSet('editingRoleId', null);
    }

    public function test_can_open_create_modal()
    {
        Livewire::test(ManageRoles::class)
            ->call('create')
            ->assertSet('name', '')
            ->assertSet('editingRoleId', null)
            ->assertSet('showModal', true);
    }

    public function test_roles_are_paginated()
    {
        // Create more than 10 roles
        for ($i = 1; $i <= 15; $i++) {
            Role::create(['name' => "role-{$i}"]);
        }

        $component = Livewire::test(ManageRoles::class);
        $roles = $component->get('roles');

        $this->assertEquals(10, $roles->perPage());
        $this->assertEquals(15, $roles->total());
    }


}
