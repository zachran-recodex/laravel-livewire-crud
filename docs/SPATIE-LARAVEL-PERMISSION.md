# Laravel Permission Package Guide

A comprehensive guide for using Spatie's Laravel Permission package for managing roles and permissions in Laravel applications.

## Installation

### Prerequisites
- Check the package documentation for Laravel version compatibility
- Ensure your User models are properly configured

### Installation Steps

1. **Install via Composer**
   ```bash
   composer require spatie/laravel-permission
   ```

2. **Publish Configuration and Migration**
   ```bash
   php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
   ```

3. **Important Pre-Migration Steps**
    - **UUIDs**: If using UUIDs, check the Advanced section for required changes
    - **Teams**: Set `'teams' => true` in `config/permission.php` if using team features
    - **MySQL 8+**: Check migration files for index key length limitations
    - **Database Cache**: Install Laravel's cache migration if using `CACHE_STORE=database`

4. **Clear Config Cache**
   ```bash
   php artisan optimize:clear
   # or
   php artisan config:clear
   ```

5. **Run Migrations**
   ```bash
   php artisan migrate
   ```

6. **Add Trait to User Model**
   ```php
   use Spatie\Permission\Traits\HasRoles;

   class User extends Authenticatable
   {
       use HasRoles;
       // ...
   }
   ```

## Basic Usage

### Creating Permissions and Roles

```php
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

// Create a role
$role = Role::create(['name' => 'writer']);

// Create a permission
$permission = Permission::create(['name' => 'edit articles']);
```

### Assigning Permissions to Roles

```php
// Assign permission to role
$role->givePermissionTo($permission);
$permission->assignRole($role);

// Sync multiple permissions
$role->syncPermissions($permissions);

// Remove permission from role
$role->revokePermissionTo($permission);
$permission->removeRole($role);
```

### Working with Users

#### Get User Permissions and Roles

```php
// Get permission names
$permissionNames = $user->getPermissionNames();

// Get all permissions (direct + via roles)
$permissions = $user->getAllPermissions();
$directPermissions = $user->getDirectPermissions();
$rolePermissions = $user->getPermissionsViaRoles();

// Get role names
$roles = $user->getRoleNames();
```

#### Query Scopes

```php
// Users with specific role
$users = User::role('writer')->get();

// Users without specific role
$nonEditors = User::withoutRole('editor')->get();

// Users with specific permission
$users = User::permission('edit articles')->get();

// Users without specific permission
$users = User::withoutPermission('edit articles')->get();
```

## Direct Permissions

> **Best Practice**: Assign permissions to roles, then assign roles to users instead of direct permissions.

### Giving/Revoking Direct Permissions

```php
// Give permission to user
$user->givePermissionTo('edit articles');
$user->givePermissionTo(['edit articles', 'delete articles']);

// Revoke permission
$user->revokePermissionTo('edit articles');

// Sync permissions
$user->syncPermissions(['edit articles', 'delete articles']);
```

### Checking Direct Permissions

```php
// Using Laravel's built-in method (recommended)
$user->can('edit articles');

// Package-specific methods
$user->hasPermissionTo('edit articles');
$user->hasAnyPermission(['edit articles', 'publish articles']);
$user->hasAllPermissions(['edit articles', 'publish articles']);

// Check direct permissions only
$user->hasDirectPermission('edit articles');
$user->hasAnyDirectPermission(['create articles', 'delete articles']);
$user->hasAllDirectPermissions(['edit articles', 'delete articles']);
```

## Using Roles

### Assigning Roles to Users

```php
// Assign single role
$user->assignRole('writer');

// Assign multiple roles
$user->assignRole(['writer', 'admin']);

// Remove role
$user->removeRole('writer');

// Sync roles
$user->syncRoles(['writer', 'admin']);
```

### Checking User Roles

```php
// Check for specific role
$user->hasRole('writer');

// Check for any role from array
$user->hasAnyRole(['writer', 'reader']);

// Check for all roles
$user->hasAllRoles(['writer', 'editor']);

// Check for exact roles
$user->hasExactRoles(['writer', 'editor']);
```

### Managing Role Permissions

```php
// Give permission to role
$role->givePermissionTo('edit articles');

// Check role permission
$role->hasPermissionTo('edit articles');

// Revoke permission from role
$role->revokePermissionTo('edit articles');

// Sync role permissions
$role->syncPermissions(['edit articles', 'delete articles']);

// Get role permissions
$permissions = $role->permissions;
$permissionNames = $role->permissions->pluck('name');
```

## Enums Support (PHP 8.1+)

### Creating Enum

```php
namespace App\Enums;

enum RolesEnum: string
{
    case WRITER = 'writer';
    case EDITOR = 'editor';
    case USERMANAGER = 'user-manager';

    public function label(): string
    {
        return match ($this) {
            static::WRITER => 'Writers',
            static::EDITOR => 'Editors',
            static::USERMANAGER => 'User Managers',
        };
    }
}
```

### Using Enums

```php
// Creating roles with enums
$role = Role::findOrCreate(RolesEnum::WRITER->value, 'web');

// Authorization with enums
$user->hasPermissionTo(PermissionsEnum::VIEWPOSTS);
$user->assignRole(RolesEnum::WRITER);
$role->givePermissionTo(PermissionsEnum::EDITPOSTS);
```

## Blade Directives

### Permission Directives

```blade
{{-- Using Laravel's native @can --}}
@can('edit articles')
    <!-- User can edit articles -->
@endcan

{{-- With specific guard --}}
@can('edit articles', 'guard_name')
    <!-- Content -->
@endcan

{{-- Package-specific directive --}}
@haspermission('edit articles')
    <!-- Content -->
@endhaspermission
```

### Role Directives

```blade
{{-- Check for specific role --}}
@role('writer')
    I am a writer!
@else
    I am not a writer...
@endrole

{{-- Alternative syntax --}}
@hasrole('writer')
    I am a writer!
@endhasrole

{{-- Check for any role --}}
@hasanyrole('writer|admin')
    I am either a writer or an admin!
@endhasanyrole

{{-- Check for all roles --}}
@hasallroles('writer|admin')
    I am both a writer and an admin!
@endhasallroles

{{-- Reverse check --}}
@unlessrole('admin')
    I do not have admin role
@endunlessrole

{{-- Exact roles --}}
@hasexactroles('writer|admin')
    I have exactly these roles and nothing else!
@endhasexactroles
```

## Super-Admin Implementation

### Using Gate::before (Recommended)

```php
// In AppServiceProvider::boot() (Laravel 11)
// or AuthServiceProvider::boot() (Laravel 10-)
use Illuminate\Support\Facades\Gate;

public function boot()
{
    Gate::before(function ($user, $ability) {
        return $user->hasRole('Super Admin') ? true : null;
    });
}
```

### Using Policy::before

```php
use App\Models\User;

public function before(User $user, string $ability): ?bool
{
    if ($user->hasRole('Super Admin')) {
        return true;
    }
    
    return null;
}
```

### Using Gate::after

```php
Gate::after(function ($user, $ability) {
    return $user->hasRole('Super Admin');
});
```

## Artisan Commands

### Creating Roles and Permissions

```bash
# Create role
php artisan permission:create-role writer

# Create permission
php artisan permission:create-permission "edit articles"

# With specific guard
php artisan permission:create-role writer web
php artisan permission:create-permission "edit articles" web

# Create role with permissions
php artisan permission:create-role writer web "create articles|edit articles"

# With team support
php artisan permission:create-role --team-id=1 writer
```

### Other Commands

```bash
# Show roles and permissions
php artisan permission:show

# Reset cache
php artisan permission:cache-reset
```

## Middleware

### Using Laravel's Built-in Middleware

```php
Route::group(['middleware' => ['can:publish articles']], function () {
    // Routes
});

// Laravel 10.9+ static method
Route::group(['middleware' => [Authorize::using('publish articles')]], function () {
    // Routes
});
```

### Package Middleware Registration

**Laravel 11** (`bootstrap/app.php`):
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
        'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
        'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
    ]);
})
```

**Laravel 9-10** (`app/Http/Kernel.php`):
```php
protected $middlewareAliases = [
    'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
    'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
    'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
];
```

### Using Middleware in Routes

```php
// Single role/permission
Route::group(['middleware' => ['role:manager']], function () { /* */ });
Route::group(['middleware' => ['permission:publish articles']], function () { /* */ });

// Multiple with OR logic (pipe separated)
Route::group(['middleware' => ['role:manager|writer']], function () { /* */ });
Route::group(['middleware' => ['permission:publish articles|edit articles']], function () { /* */ });

// With specific guard
Route::group(['middleware' => ['role:manager,api']], function () { /* */ });

// Role OR permission
Route::group(['middleware' => ['role_or_permission:manager|edit articles']], function () { /* */ });
```

### Using Middleware in Controllers

**Laravel 11** (HasMiddleware interface):
```php
public static function middleware(): array
{
    return [
        'role_or_permission:manager|edit articles',
        new Middleware('role:author', only: ['index']),
        new Middleware(RoleMiddleware::using('manager'), except: ['show']),
    ];
}
```

**Laravel 10 and older** (constructor):
```php
public function __construct()
{
    $this->middleware(['role:manager','permission:publish articles|edit articles']);
    $this->middleware(['role_or_permission:manager|edit articles,api']);
}
```

## Best Practices: Roles vs Permissions

### Recommended Approach

- **Roles**: Group people by sets of permissions
- **Permissions**: Assign to roles, be granular and specific
- **Users**: Inherit permissions via roles (avoid direct permissions)
- **Application Logic**: Check permissions, not roles

### Example Structure

```php
// Good: Check permissions in views and controllers
@can('view member addresses')
@can('edit document')
$user->can('delete post')

// Avoid: Checking roles directly
// $user->hasRole('Editor') // Less flexible
```

### Summary

- Users have roles
- Roles have permissions
- Application checks permissions (not roles)
- Views, policies, controllers, middleware check permission names
- Routes check permissions or roles as needed

## Model Policies

Create policies that combine application logic with permission rules:

```php
<?php
namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    public function view(?User $user, Post $post): bool
    {
        if ($post->published) {
            return true;
        }

        if ($user === null) {
            return false;
        }

        if ($user->can('view unpublished posts')) {
            return true;
        }

        return $user->id == $post->user_id;
    }

    public function create(User $user): bool
    {
        return $user->can('create posts');
    }

    public function update(User $user, Post $post): bool
    {
        if ($user->can('edit all posts')) {
            return true;
        }

        if ($user->can('edit own posts')) {
            return $user->id == $post->user_id;
        }
    }

    public function delete(User $user, Post $post): bool
    {
        if ($user->can('delete any post')) {
            return true;
        }

        if ($user->can('delete own posts')) {
            return $user->id == $post->user_id;
        }
    }
}
```

## Database Seeding

### Seeder Example

```php
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        Permission::create(['name' => 'edit articles']);
        Permission::create(['name' => 'delete articles']);
        Permission::create(['name' => 'publish articles']);
        Permission::create(['name' => 'unpublish articles']);

        // Update cache after creating permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles and assign permissions
        $role = Role::create(['name' => 'writer']);
        $role->givePermissionTo('edit articles');

        $role = Role::create(['name' => 'moderator'])
            ->givePermissionTo(['publish articles', 'unpublish articles']);

        $role = Role::create(['name' => 'super-admin']);
        $role->givePermissionTo(Permission::all());
    }
}
```

### User Seeding with Factories

```php
// Using Factory States
public function active(): static
{
    return $this->state(fn (array $attributes) => ['status' => 1])
        ->afterCreating(function (User $user) {
            $user->assignRole('ActiveMember');
        });
}

// In Seeder
User::factory(4)->active()->create();

// Seeding multiple users with roles
User::factory()
    ->count(50)
    ->create()
    ->each(function ($user) {
        $user->assignRole('Member');
    });
```

## Performance Tips

### For Large Applications

1. **Role Assignment**: Sometimes `$permission->assignRole($role)` is faster than `$role->givePermissionTo($permission)`

2. **Creating Permissions**: For better performance on large databases:
   ```php
   $permission = Permission::make($attributes); 
   $permission->saveOrFail();
   ```

3. **Bulk Operations**: Use Eloquent's `insert()` for large datasets:
   ```php
   $permissions = collect($permissionNames)->map(function ($permission) {
       return ['name' => $permission, 'guard_name' => 'web'];
   });
   
   Permission::insert($permissions->toArray());
   ```

4. **Cache Management**: Always flush cache after direct DB operations:
   ```php
   app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
   ```

## Important Notes

- **Cache**: Package automatically manages cache when using provided methods
- **Guards**: Multiple guards are supported - specify in configuration
- **Teams**: Enable in config for multi-tenant applications
- **Database Store**: Install Laravel cache tables if using database cache
- **UUID Support**: Check advanced documentation for UUID implementation
- **MySQL 8+**: May need index key length adjustments

## Configuration

Default config file location: `config/permission.php`

View default contents at: https://github.com/spatie/laravel-permission/blob/main/config/permission.php
