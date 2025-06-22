<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:header class="bg-white border-b border-zinc-200 dark:bg-zinc-900 dark:border-zinc-700">
            <!-- Brand -->
            <a href="{{ route('home') }}" class="flex items-center space-x-2 text-xl font-semibold text-zinc-900 dark:text-white" wire:navigate>
                <x-app-logo />
            </a>

            <flux:spacer />

            <!-- Auth Links -->
            <div class="hidden md:flex items-center space-x-4 ml-8">
                @auth
                    <flux:link href="{{ route('dashboard') }}" class="text-zinc-600 hover:text-zinc-900 dark:text-zinc-300 dark:hover:text-white" wire:navigate>
                        Dashboard
                    </flux:link>
                @else
                    <flux:link href="{{ route('login') }}" class="text-zinc-600 hover:text-zinc-900 dark:text-zinc-300 dark:hover:text-white" wire:navigate>
                        Log in
                    </flux:link>
                    @if (Route::has('register'))
                        <flux:button href="{{ route('register') }}" variant="primary" size="sm" wire:navigate>
                            Register
                        </flux:button>
                    @endif
                @endauth
            </div>

            <!-- Mobile Menu Button -->
            <flux:button variant="ghost" size="sm" class="md:hidden">
                <flux:icon.bars-3 class="size-5" />
            </flux:button>
        </flux:header>

        <flux:main class="flex-1">
            {{ $slot }}
        </flux:main>

        <!-- Footer -->
        <flux:footer class="bg-zinc-50 border-t border-zinc-200 dark:bg-zinc-900 dark:border-zinc-700 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                    <div class="text-sm text-zinc-600 dark:text-zinc-400">
                        © {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.
                    </div>
                    <div class="flex space-x-6">
                        <flux:link href="#" class="text-sm text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white">
                            Privacy Policy
                        </flux:link>
                        <flux:link href="#" class="text-sm text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white">
                            Terms of Service
                        </flux:link>
                    </div>
                </div>
            </div>
        </flux:footer>

        @fluxScripts
    </body>
</html>
