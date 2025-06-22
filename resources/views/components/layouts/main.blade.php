<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <title>{{ $title ?? 'Flite Charter - Your Instant Air Transport Solutions' }}</title>
        <meta name="description" content="Premium private jet charter services that prioritize speed, discretion, and comfort. Your instant air transport solutions for executives and premium travelers.">

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=montserrat:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-secondary bg-white antialiased">
        <!-- Navigation -->
        <nav id="navbar" class="fixed w-full z-50 transition-all duration-300" style="background-color: transparent;">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <div class="flex-shrink-0">
                        <a href="/" class="flex items-center">
                            <img src="/images/logo.png" alt="Flite Charter - Your Instant Air Transport Solutions" class="h-12 w-auto">
                        </a>
                    </div>

                    <!-- Desktop Navigation -->
                    <div class="hidden md:block">
                        <div class="ml-10 flex items-baseline space-x-8">
                            <a href="#charter" class="text-white hover:text-[var(--flite-gold)] px-3 py-2 text-sm font-medium transition-colors">Charter</a>
                            <a href="#fleet" class="text-white hover:text-[var(--flite-gold)] px-3 py-2 text-sm font-medium transition-colors">Fleet</a>
                            <a href="#services" class="text-white hover:text-[var(--flite-gold)] px-3 py-2 text-sm font-medium transition-colors">Services</a>
                            <a href="#about" class="text-white hover:text-[var(--flite-gold)] px-3 py-2 text-sm font-medium transition-colors">About</a>
                            <a href="#contact" class="text-white hover:text-[var(--flite-gold)] px-3 py-2 text-sm font-medium transition-colors">Contact</a>
                        </div>
                    </div>

                    <!-- CTA Button -->
                    <div class="hidden md:block">
                        <a href="#quote" class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-md text-white hover:bg-opacity-90 transition-colors" style="background-color: var(--flite-gold);">
                            Get Quote
                        </a>
                    </div>

                    <!-- Mobile menu button -->
                    <div class="md:hidden">
                        <button type="button" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-[var(--flite-gold)] hover:bg-white/10 focus:outline-none">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main>
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="bg-white text-gray-700 border-t border-gray-200">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <!-- Company Info -->
                    <div class="col-span-1 md:col-span-2">
                        <div class="font-primary font-bold text-xl mb-2" style="color: var(--flite-emerald);">
                            FLITE CHARTER
                        </div>
                        <div class="text-sm text-gray-500 mb-4">
                            YOUR INSTANT AIR TRANSPORT SOLUTIONS
                        </div>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            Premium private jet charter services that prioritize speed, discretion, and comfort.
                            Empowering executives and premium travelers with full control over their travel experience.
                        </p>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h3 class="text-sm font-semibold uppercase tracking-wider mb-4" style="color: var(--flite-emerald);">Services</h3>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-gray-600 hover:text-[var(--flite-emerald)] text-sm transition-colors">Private Charter</a></li>
                            <li><a href="#" class="text-gray-600 hover:text-[var(--flite-emerald)] text-sm transition-colors">Corporate Travel</a></li>
                            <li><a href="#" class="text-gray-600 hover:text-[var(--flite-emerald)] text-sm transition-colors">Group Charter</a></li>
                            <li><a href="#" class="text-gray-600 hover:text-[var(--flite-emerald)] text-sm transition-colors">Aircraft Management</a></li>
                        </ul>
                    </div>

                    <!-- Contact -->
                    <div>
                        <h3 class="text-sm font-semibold uppercase tracking-wider mb-4" style="color: var(--flite-emerald);">Contact</h3>
                        <ul class="space-y-2">
                            <li class="text-gray-600 text-sm">24/7 Charter Hotline</li>
                            <li><a href="tel:+1234567890" class="hover:text-[var(--flite-gold)] text-sm transition-colors" style="color: var(--flite-emerald);">+1 (234) 567-8900</a></li>
                            <li><a href="mailto:charter@flitecharter.com" class="text-gray-600 hover:text-[var(--flite-emerald)] text-sm transition-colors">charter@flitecharter.com</a></li>
                        </ul>
                    </div>
                </div>

                <div class="mt-8 pt-8 border-t border-gray-200">
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <p class="text-gray-600 text-sm">
                            © {{ date('Y') }} Flite Charter. All rights reserved.
                        </p>
                        <div class="mt-4 md:mt-0 flex space-x-6">
                            <a href="#" class="text-gray-600 hover:text-[var(--flite-emerald)] text-sm transition-colors">Privacy Policy</a>
                            <a href="#" class="text-gray-600 hover:text-[var(--flite-emerald)] text-sm transition-colors">Terms of Service</a>
                            <a href="#" class="text-gray-600 hover:text-[var(--flite-emerald)] text-sm transition-colors">Safety</a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

        @fluxScripts
    </body>
</html>
