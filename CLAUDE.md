# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Laravel Livewire CRUD starter kit built with enterprise-level security and performance in mind. The application uses **Flux UI components** and **Spatie Laravel Permission** for comprehensive role & permission management.

## Development Commands

### Development Environment

```bash
# Start full development environment (server, queue, logs, vite)
composer run dev

# Individual services
php artisan serve
php artisan queue:listen --tries=1
php artisan pail --timeout=0
npm run dev
```

### Testing

```bash
# Run all tests with config clear
composer run test

# Run specific test suites
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit

# Run single test file
php artisan test tests/Feature/DashboardTest.php
```

### Asset Building

```bash
# Development build with hot reloading
npm run dev

# Production build
npm run build
```

### Code Quality

```bash
# Format code using Laravel Pint
./vendor/bin/pint

# Run Pint with specific configuration
./vendor/bin/pint --config=pint.json
```

### Database & Seeding

```bash
# Fresh migration with seeding
php artisan migrate:fresh --seed

# Run specific seeder
php artisan db:seed --class=RolesAndPermissionsSeeder
```

## Architecture Overview

### Core Stack

- **Laravel 12.x** with **PHP 8.2+**
- **Livewire Volt 1.7** for single-file components
- **Flux UI 2.1** premium component library
- **Spatie Laravel Permission** for RBAC
- **PestPHP** for testing
- **Vite** with **Tailwind CSS 4.x**

### Key Directories

```bash
app/Livewire/           # Livewire components
├── Actions/            # Action components (Logout)
├── Administrator/      # Admin CRUD components
└── Dashboard.php       # Main dashboard component

resources/views/
├── livewire/          # Component views
├── flux/              # Flux UI component overrides
└── components/        # Blade components

resources/js/          # JavaScript assets
resources/css/         # Tailwind CSS with Flux theming
```

### Database Structure

- **Users table** with roles & permissions (Spatie package)
- **Cache/Jobs/Sessions** using database driver
- **SQLite** as default database (easily switchable to MySQL/PostgreSQL)
- **In-memory SQLite** for testing

### Authentication & Authorization

- **Spatie Laravel Permission** integration
- **Role-based middleware**: `role:Super Admin`
- **Route protection** with `auth`, `verified` middleware
- **Blade directives**: `@role('Super Admin')` for template-level access

### Default Credentials

After running `php artisan migrate:fresh --seed`:
- Email: `zachranraze@recodex.id`
- Password: `admin123`
- Role: Super Admin

### Component Architecture

- **Dashboard Component**: Main dashboard with simple interface
- **Administrator Components**: Full CRUD for Users and Roles with modal forms
- **Form Objects**: Livewire v3 form abstraction for maintainability
- **Computed Properties**: Database queries cached within component lifecycle

## Development Notes

- Use the flux (resources/views/flux) component overrides in the created views
- Before generating code, study docs/LARAVEL-BEST-PRACTICES.md and docs/LIVEWIRE-BEST-PRACTICES.md and apply them to the generated code
- The generated code must always be consistent with the existing code
- Refer to docs/FLUX-UI-MODAL.md for modal implementation patterns
- Use docs/SPATIE-LARAVEL-PERMISSION.md for role and permission management guidance

## Performance Optimization Guidelines

### Livewire Performance

- **Computed Properties**: Use `#[Computed]` for database queries to cache within component lifecycle
- **Event Listeners**: Prefer over polling for real-time updates
- **Primitive Types**: Pass strings/integers instead of objects to components
- **Component Nesting**: Keep at maximum 1 level deep
- **Form Objects**: Always use Livewire v3 form abstraction

### Frontend Performance

- **Vite HMR**: Fast development with hot module replacement
- **Tailwind Purging**: Automatic unused CSS removal
- **Asset Compilation**: Use `npm run build` for production

## Testing Strategy

### Test Structure

- **Feature Tests**: Full component functionality testing
- **Unit Tests**: Individual method/class testing  
- **Database**: In-memory SQLite for fast testing
- **Coverage**: Comprehensive test coverage for core features

### Test Categories

```bash
tests/Feature/
├── Auth/              # Authentication flow tests
├── Administrator/     # Admin CRUD functionality
├── Dashboard/         # Dashboard component tests
└── Settings/          # User settings tests
```

## Key Configuration Files

- **phpunit.xml**: Testing with in-memory SQLite
- **vite.config.js**: Build configuration with Tailwind plugin
- **composer.json**: Development scripts and dependencies
- **package.json**: Frontend build scripts
- **docs/**: Laravel and Livewire best practices documentation

## Docker Support

The project includes Docker configuration for containerized development:

```bash
# Build and run containers
docker-compose up -d --build

# Install dependencies inside PHP container
docker-compose exec php composer install
docker-compose exec php npm install

# Run migrations and seeding
docker-compose exec php php artisan migrate:fresh --seed
```

Application will be available at `http://localhost` when using Docker.