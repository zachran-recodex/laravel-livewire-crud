<x-layouts.main title="Welcome to Laravel">
    <!-- Hero Section -->
    <div class="text-center py-12">
        <flux:heading size="xl" class="text-zinc-900 dark:text-white mb-4">
            Welcome to Laravel
        </flux:heading>
        <flux:subheading class="text-zinc-600 dark:text-zinc-400 mb-8 max-w-2xl mx-auto">
            Build modern web applications with Laravel, Livewire, and Flux UI components.
            This is a CRUD application starter with best practices built-in.
        </flux:subheading>

        <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
            <flux:button variant="primary" href="#features">
                <flux:icon.rocket-launch class="size-4" />
                Get Started
            </flux:button>
            <flux:button variant="ghost" href="https://laravel.com/docs" target="_blank">
                <flux:icon.book-open class="size-4" />
                Documentation
            </flux:button>
        </div>

        <!-- AI Credit Badge -->
        <div class="inline-flex items-center px-4 py-2 bg-white dark:bg-zinc-900 rounded-full shadow-sm border border-orange-200 dark:border-zinc-600">
            <flux:icon.sparkles class="size-4 mr-2" style="color: rgb(201, 100, 66);" />
            <flux:text class="text-sm font-medium" style="color: rgb(201, 100, 66);">
                Crafted with Claude AI
            </flux:text>
        </div>
    </div>

    <!-- Features Section -->
    <div id="features" class="py-16">
        <div class="text-center mb-12">
            <flux:heading size="lg" class="text-zinc-900 dark:text-white mb-4">
                Features
            </flux:heading>
            <flux:subheading class="text-zinc-600 dark:text-zinc-400">
                Everything you need to build modern web applications
            </flux:subheading>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Laravel Feature -->
            <div class="bg-white dark:bg-zinc-900    p-6 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700">
                <div class="flex items-center mb-4">
                    <div class="bg-red-100 dark:bg-red-900/20 p-3 rounded-lg mr-4">
                        <flux:icon.code-bracket class="size-6 text-red-600 dark:text-red-400" />
                    </div>
                    <flux:heading size="base" class="text-zinc-900 dark:text-white">
                        Laravel Framework
                    </flux:heading>
                </div>
                <flux:text class="text-zinc-600 dark:text-zinc-400">
                    Built on Laravel 12 with modern PHP practices, following the best practices documentation included.
                </flux:text>
            </div>

            <!-- Livewire Feature -->
            <div class="bg-white dark:bg-zinc-900 p-6 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700">
                <div class="flex items-center mb-4">
                    <div class="bg-purple-100 dark:bg-purple-900/20 p-3 rounded-lg mr-4">
                        <flux:icon.bolt class="size-6 text-purple-600 dark:text-purple-400" />
                    </div>
                    <flux:heading size="base" class="text-zinc-900 dark:text-white">
                        Livewire 3
                    </flux:heading>
                </div>
                <flux:text class="text-zinc-600 dark:text-zinc-400">
                    Dynamic interfaces without JavaScript complexity. Includes comprehensive best practices guide.
                </flux:text>
            </div>

            <!-- Flux UI Feature -->
            <div class="bg-white dark:bg-zinc-900 p-6 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700">
                <div class="flex items-center mb-4">
                    <div class="bg-blue-100 dark:bg-blue-900/20 p-3 rounded-lg mr-4">
                        <flux:icon.sparkles class="size-6 text-blue-600 dark:text-blue-400" />
                    </div>
                    <flux:heading size="base" class="text-zinc-900 dark:text-white">
                        Flux UI
                    </flux:heading>
                </div>
                <flux:text class="text-zinc-600 dark:text-zinc-400">
                    Beautiful, accessible UI components built specifically for Laravel and Livewire applications.
                </flux:text>
            </div>

            <!-- Authentication Feature -->
            <div class="bg-white dark:bg-zinc-900 p-6 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700">
                <div class="flex items-center mb-4">
                    <div class="bg-green-100 dark:bg-green-900/20 p-3 rounded-lg mr-4">
                        <flux:icon.shield-check class="size-6 text-green-600 dark:text-green-400" />
                    </div>
                    <flux:heading size="base" class="text-zinc-900 dark:text-white">
                        Authentication
                    </flux:heading>
                </div>
                <flux:text class="text-zinc-600 dark:text-zinc-400">
                    Complete authentication system with roles and permissions using Spatie Laravel Permission.
                </flux:text>
            </div>

            <!-- Testing Feature -->
            <div class="bg-white dark:bg-zinc-900 p-6 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700">
                <div class="flex items-center mb-4">
                    <div class="bg-yellow-100 dark:bg-yellow-900/20 p-3 rounded-lg mr-4">
                        <flux:icon.bug-ant class="size-6 text-yellow-600 dark:text-yellow-400" />
                    </div>
                    <flux:heading size="base" class="text-zinc-900 dark:text-white">
                        Testing with Pest
                    </flux:heading>
                </div>
                <flux:text class="text-zinc-600 dark:text-zinc-400">
                    Pre-configured with Pest PHP for elegant testing with a focus on simplicity and readability.
                </flux:text>
            </div>

            <!-- Documentation Feature -->
            <div class="bg-white dark:bg-zinc-900 p-6 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700">
                <div class="flex items-center mb-4">
                    <div class="bg-indigo-100 dark:bg-indigo-900/20 p-3 rounded-lg mr-4">
                        <flux:icon.document-text class="size-6 text-indigo-600 dark:text-indigo-400" />
                    </div>
                    <flux:heading size="base" class="text-zinc-900 dark:text-white">
                        Best Practices
                    </flux:heading>
                </div>
                <flux:text class="text-zinc-600 dark:text-zinc-400">
                    Comprehensive documentation covering Laravel, Livewire, and Spatie Permission best practices.
                </flux:text>
            </div>
        </div>
    </div>

    <!-- Quick Start Section -->
    <div class="py-16 bg-zinc-50 dark:bg-zinc-900/50 rounded-xl">
        <div class="text-center mb-8">
            <flux:heading size="lg" class="text-zinc-900 dark:text-white mb-4">
                Quick Start
            </flux:heading>
            <flux:subheading class="text-zinc-600 dark:text-zinc-400">
                Get up and running in minutes
            </flux:subheading>
        </div>

        <div class="max-w-4xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="bg-white dark:bg-zinc-900 p-4 rounded-lg shadow-sm mb-4 inline-block">
                        <flux:icon.cog class="size-8 text-zinc-600 dark:text-zinc-400" />
                    </div>
                    <flux:heading size="sm" class="text-zinc-900 dark:text-white mb-2">
                        1. Configure
                    </flux:heading>
                    <flux:text class="text-zinc-600 dark:text-zinc-400">
                        Set up your database and environment variables
                    </flux:text>
                </div>

                <div class="text-center">
                    <div class="bg-white dark:bg-zinc-900 p-4 rounded-lg shadow-sm mb-4 inline-block">
                        <flux:icon.play class="size-8 text-zinc-600 dark:text-zinc-400" />
                    </div>
                    <flux:heading size="sm" class="text-zinc-900 dark:text-white mb-2">
                        2. Migrate
                    </flux:heading>
                    <flux:text class="text-zinc-600 dark:text-zinc-400">
                        Run migrations and seed your database
                    </flux:text>
                </div>

                <div class="text-center">
                    <div class="bg-white dark:bg-zinc-900 p-4 rounded-lg shadow-sm mb-4 inline-block">
                        <flux:icon.rocket-launch class="size-8 text-zinc-600 dark:text-zinc-400" />
                    </div>
                    <flux:heading size="sm" class="text-zinc-900 dark:text-white mb-2">
                        3. Build
                    </flux:heading>
                    <flux:text class="text-zinc-600 dark:text-zinc-400">
                        Start building your amazing application
                    </flux:text>
                </div>
            </div>
        </div>
    </div>

    <!-- Links Section -->
    <div class="py-16">
        <div class="text-center mb-8">
            <flux:heading size="lg" class="text-zinc-900 dark:text-white mb-4">
                Useful Links
            </flux:heading>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <flux:link href="https://laravel.com/docs" target="_blank" class="block p-6 bg-white dark:bg-zinc-900 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center mb-2">
                    <flux:icon.book-open class="size-5 text-red-600 dark:text-red-400 mr-2" />
                    <flux:text class="font-medium text-zinc-900 dark:text-white">
                        Laravel Docs
                    </flux:text>
                </div>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                    Official Laravel documentation
                </flux:text>
            </flux:link>

            <flux:link href="https://livewire.laravel.com" target="_blank" class="block p-6 bg-white dark:bg-zinc-900 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center mb-2">
                    <flux:icon.bolt class="size-5 text-purple-600 dark:text-purple-400 mr-2" />
                    <flux:text class="font-medium text-zinc-900 dark:text-white">
                        Livewire Docs
                    </flux:text>
                </div>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                    Livewire documentation
                </flux:text>
            </flux:link>

            <flux:link href="https://fluxui.dev" target="_blank" class="block p-6 bg-white dark:bg-zinc-900 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center mb-2">
                    <flux:icon.sparkles class="size-5 text-blue-600 dark:text-blue-400 mr-2" />
                    <flux:text class="font-medium text-zinc-900 dark:text-white">
                        Flux UI
                    </flux:text>
                </div>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                    Flux UI component library
                </flux:text>
            </flux:link>

            <flux:link href="https://spatie.be/docs/laravel-permission" target="_blank" class="block p-6 bg-white dark:bg-zinc-900 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center mb-2">
                    <flux:icon.shield-check class="size-5 text-green-600 dark:text-green-400 mr-2" />
                    <flux:text class="font-medium text-zinc-900 dark:text-white">
                        Permission Docs
                    </flux:text>
                </div>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                    Spatie Permission package
                </flux:text>
            </flux:link>
        </div>
    </div>

</x-layouts.main>
