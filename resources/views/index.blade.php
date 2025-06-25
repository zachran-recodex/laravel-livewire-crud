<x-layouts.main title="Flite Charter - Premium Private Jet Charter Services">
    <!-- Hero Carousel Section -->
    <section class="hero-carousel">
        @forelse($heroes as $hero)
            @if($loop->first)
                <div x-data="{
                slides: [
                    @foreach($heroes as $heroItem)
                    {
                        imgSrc: '{{ $heroItem->image ? Storage::url($heroItem->image) : 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2074&q=80' }}',
                        imgAlt: '{{ $heroItem->title }}',
                        title: '{{ $heroItem->title }}',
                        ctaUrl: '#fleet',
                        ctaText: 'View Our Fleet',
                    },
                    @endforeach
                ],
                currentSlideIndex: 1,
                autoplayIntervalTime: 5000,
                isPaused: false,
                autoplayInterval: null,

                previous() {
                    if (this.currentSlideIndex > 1) {
                        this.currentSlideIndex = this.currentSlideIndex - 1
                    } else {
                        this.currentSlideIndex = this.slides.length
                    }
                },

                next() {
                    if (this.currentSlideIndex < this.slides.length) {
                        this.currentSlideIndex = this.currentSlideIndex + 1
                    } else {
                        this.currentSlideIndex = 1
                    }
                },

                autoplay() {
                    this.autoplayInterval = setInterval(() => {
                        if (!this.isPaused) {
                            this.next()
                        }
                    }, this.autoplayIntervalTime)
                },

                setAutoplayInterval(newIntervalTime) {
                    clearInterval(this.autoplayInterval)
                    this.autoplayIntervalTime = newIntervalTime
                    this.autoplay()
                },

                formatTitle(title) {
                    const words = title.split(' ');
                    if (words.length <= 2) {
                        return { firstPart: '', lastPart: title };
                    }
                    const midPoint = Math.ceil(words.length / 2);
                    return {
                        firstPart: words.slice(0, midPoint).join(' '),
                        lastPart: words.slice(midPoint).join(' ')
                    };
                }
            }"
                     x-init="autoplay"
                     class="relative w-full overflow-hidden">

                    <!-- Previous Button -->
                    <button type="button"
                            class="absolute left-5 top-1/2 z-20 flex rounded-full -translate-y-1/2 items-center justify-center bg-white/30 backdrop-blur-sm p-3 text-white transition hover:bg-white/50 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white active:outline-offset-0"
                            aria-label="previous slide"
                            x-on:click="previous()">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="3" class="size-6 pr-0.5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                        </svg>
                    </button>

                    <!-- Next Button -->
                    <button type="button"
                            class="absolute right-5 top-1/2 z-20 flex rounded-full -translate-y-1/2 items-center justify-center bg-white/30 backdrop-blur-sm p-3 text-white transition hover:bg-white/50 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white active:outline-offset-0"
                            aria-label="next slide"
                            x-on:click="next()">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="3" class="size-6 pl-0.5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </button>

                    <!-- Slides Container -->
                    <div class="relative h-screen w-full">
                        <template x-for="(slide, index) in slides" :key="index">
                            <div x-show="currentSlideIndex == index + 1"
                                 class="absolute inset-0"
                                 x-transition:enter="transition-opacity duration-1000 ease-in-out"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"
                                 x-transition:leave="transition-opacity duration-1000 ease-in-out"
                                 x-transition:leave-start="opacity-100"
                                 x-transition:leave-end="opacity-0">

                                <!-- Background Image -->
                                <img class="absolute w-full h-full inset-0 object-cover"
                                     x-bind:src="slide.imgSrc"
                                     x-bind:alt="slide.imgAlt" />

                                <!-- Overlay -->
                                <div class="absolute inset-0 bg-black/40"></div>
                                <div class="absolute inset-0 bg-gradient-to-r from-black/30 to-transparent"></div>

                                <!-- Content -->
                                <div class="absolute inset-0 z-10 flex flex-col items-center justify-center text-center px-4 sm:px-6 lg:px-8">
                                    <div class="max-w-4xl mx-auto">
                                        <h1 class="font-primary text-4xl md:text-6xl lg:text-7xl font-bold leading-tight text-white mb-8"
                                            x-data="{ titleParts: formatTitle(slide.title) }">
                                            <span x-text="titleParts.firstPart"></span>
                                            <template x-if="titleParts.firstPart && titleParts.lastPart">
                                                <br>
                                            </template>
                                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-yellow-600"
                                                  x-text="titleParts.lastPart || titleParts.firstPart"></span>
                                        </h1>

                                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                                            <a x-bind:href="slide.ctaUrl"
                                               class="inline-flex items-center justify-center px-8 py-4 text-lg font-semibold border-2 border-white text-white rounded-lg hover:bg-white hover:text-gray-900 transition-all duration-300 transform hover:scale-105">
                                                <span x-text="slide.ctaText"></span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Indicators -->
                    <div class="absolute bottom-5 left-1/2 z-20 flex -translate-x-1/2 gap-3 bg-white/20 backdrop-blur-sm px-3 py-2 rounded-full"
                         role="group"
                         aria-label="slides">
                        <template x-for="(slide, index) in slides" :key="index">
                            <button class="size-3 rounded-full transition-all duration-300"
                                    x-on:click="currentSlideIndex = index + 1"
                                    x-bind:class="[currentSlideIndex === index + 1 ? 'bg-yellow-400 scale-125' : 'bg-white/50 hover:bg-white/75']"
                                    x-bind:aria-label="'slide ' + (index + 1)">
                            </button>
                        </template>
                    </div>

                    <!-- Pause/Play Button -->
                    <button type="button"
                            class="absolute bottom-5 right-5 z-20 rounded-full bg-white/20 backdrop-blur-sm p-3 text-white/70 hover:text-white hover:bg-white/30 transition-all duration-300 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white"
                            aria-label="pause carousel"
                            x-on:click="(isPaused = !isPaused), setAutoplayInterval(autoplayIntervalTime)"
                            x-bind:aria-pressed="isPaused">

                        <!-- Play Icon -->
                        <svg x-cloak x-show="isPaused" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                            <path fill-rule="evenodd" d="M2 10a8 8 0 1 1 16 0 8 8 0 0 1-16 0Zm6.39-2.908a.75.75 0 0 1 .766.027l3.5 2.25a.75.75 0 0 1 0 1.262l-3.5 2.25A.75.75 0 0 1 8 12.25v-4.5a.75.75 0 0 1 .39-.658Z" clip-rule="evenodd">
                        </svg>

                        <!-- Pause Icon -->
                        <svg x-cloak x-show="!isPaused" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                            <path fill-rule="evenodd" d="M2 10a8 8 0 1 1 16 0 8 8 0 0 1-16 0Zm5-2.25A.75.75 0 0 1 7.75 7h.5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-.75.75h-.5a.75.75 0 0 1-.75-.75v-4.5Zm4 0a.75.75 0 0 1 .75-.75h.5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-.75.75h-.5a.75.75 0 0 1-.75-.75v-4.5Z" clip-rule="evenodd">
                        </svg>
                    </button>
                </div>
            @endif
        @empty
            <!-- Message when no hero content available -->
            <div class="relative h-screen w-full bg-gradient-to-br from-gray-900 via-gray-800 to-gray-700">
                <!-- Background Pattern -->
                <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%239C92AC" fill-opacity="0.1"%3E%3Ccircle cx="30" cy="30" r="2"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-20"></div>

            <!-- Content -->
            <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-4 sm:px-6 lg:px-8">
                <div class="max-w-2xl mx-auto">
                    <!-- Icon -->
                    <div class="mb-8">
                        <svg class="w-20 h-20 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>

                    <!-- Title -->
                    <h1 class="font-primary text-4xl md:text-5xl font-bold text-white mb-4">
                        No Hero Content Available
                    </h1>

                    <!-- Description -->
                    <p class="text-xl text-gray-300 mb-8 max-w-lg mx-auto">
                        The hero section is currently being updated. Please check back later or contact us for more information.
                    </p>

                    <!-- Action Button -->
                    <div class="flex justify-center">
                        <a href="#services"
                           class="inline-flex items-center justify-center px-8 py-4 text-lg font-semibold border-2 border-white text-white rounded-lg hover:bg-white hover:text-gray-900 transition-all duration-300 transform hover:scale-105">
                            View Our Services
                        </a>
                    </div>
                </div>
            </div>
            </div>
        @endforelse
    </section>

    <!-- Services Section -->
    <section id="services">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4">
            @forelse($services as $service)
                <div class="group relative h-80 overflow-hidden border cursor-pointer">
                    <!-- Background Image -->
                    <div class="absolute inset-0 bg-cover bg-center transition-transform duration-500 group-hover:scale-110"
                         style="background-image: url('{{ $service->image ? Storage::url($service->image) : 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80' }}');">
                    </div>

                    <!-- Light Overlay for text readability -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent group-hover:from-black/70 transition-all duration-300"></div>

                    <!-- Content -->
                    <div class="absolute inset-0 flex flex-col justify-end p-6 text-white">
                        <div class="transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
                            <h3 class="font-primary text-xl font-bold mb-2 drop-shadow-lg">{{ $service->title }}</h3>

                            <!-- Arrow Icon -->
                            <div class="flex items-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <svg class="w-5 h-5" style="color: var(--color-gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                                <span class="text-sm font-medium">Learn More</span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <!-- Message when no services available -->
                <div class="col-span-4 text-center py-12">
                    <div class="bg-white p-8">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="font-primary text-2xl font-bold mb-2 text-gray-600">No Services Available</h3>
                        <p class="text-gray-500">
                            Our services are currently being updated. Please check back later or contact us for more information.
                        </p>
                    </div>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Our Fleet Section -->
    <section id="fleet" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="font-primary text-4xl font-bold mb-4" style="color: var(--color-emerald);">Our Premium Fleet</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Choose from our diverse collection of meticulously maintained aircraft, each offering unparalleled comfort, safety, and performance for your journey.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($fleets as $fleet)
                    <div class="bg-gray-50 rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                        <div class="h-64 bg-gradient-to-br from-gray-100 to-gray-200 relative overflow-hidden">
                            @if($fleet->image)
                                <img src="{{ Storage::url($fleet->image) }}" 
                                     alt="{{ $fleet->title }}"
                                     class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                            <div class="absolute top-4 right-4 bg-white bg-opacity-90 backdrop-blur px-3 py-1 rounded-full">
                                <span class="text-sm font-semibold" style="color: var(--color-emerald);">{{ $fleet->category }}</span>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="font-primary text-xl font-bold mb-3" style="color: var(--color-emerald);">{{ $fleet->title }}</h3>
                            <p class="text-gray-600 mb-4">{{ $fleet->description }}</p>

                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div class="text-center">
                                    <div class="text-2xl font-bold" style="color: var(--color-gold);">{{ $fleet->passengers }}</div>
                                    <div class="text-xs text-gray-500">Passengers</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold" style="color: var(--color-gold);">{{ number_format($fleet->range) }}</div>
                                    <div class="text-xs text-gray-500">Range (nm)</div>
                                </div>
                            </div>

                            @if($fleet->features && count($fleet->features) > 0)
                                <ul class="space-y-2 text-sm text-gray-600">
                                    @foreach($fleet->features as $feature)
                                        <li class="flex items-center">
                                            <svg class="w-4 h-4 mr-2" style="color: var(--color-gold);" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $feature }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No fleets available</h3>
                        <p class="mt-1 text-sm text-gray-500">Fleet information will be displayed here once added.</p>
                    </div>
                @endforelse
            </div>

            <!-- CTA Button -->
            <div class="text-center mt-12">
                <button type="button" class="inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-white rounded-lg hover:bg-opacity-90 transition-all duration-300 transform hover:scale-105" style="background-color: var(--color-gold);">
                    View Complete Fleet
                    <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </button>
            </div>
        </div>
    </section>

    <!-- Popular Destinations Section -->
    <section id="destinations" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="font-primary text-4xl font-bold mb-4" style="color: var(--color-emerald);">Popular Destinations</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Fly to the world's most exclusive destinations with our premium charter services. From business hubs to luxury getaways.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- New York -->
                <div class="group relative overflow-hidden rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="h-80 bg-cover bg-center relative" style="background-image: url('https://images.unsplash.com/photo-1496442226666-8d4d0e62e6e9?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80');">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                        <div class="absolute bottom-6 left-6 text-white">
                            <h3 class="font-primary text-xl font-bold mb-1">New York</h3>
                            <p class="text-sm opacity-90">Business & Finance Hub</p>
                            <div class="flex items-center mt-2 text-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                2.5 hrs from Miami
                            </div>
                        </div>
                        <div class="absolute top-4 right-4 bg-white bg-opacity-90 backdrop-blur px-3 py-1 rounded-full">
                            <span class="text-sm font-semibold" style="color: var(--color-emerald);">Popular</span>
                        </div>
                    </div>
                </div>

                <!-- London -->
                <div class="group relative overflow-hidden rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="h-80 bg-cover bg-center relative" style="background-image: url('https://images.unsplash.com/photo-1513635269975-59663e0ac1ad?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80');">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                        <div class="absolute bottom-6 left-6 text-white">
                            <h3 class="font-primary text-xl font-bold mb-1">London</h3>
                            <p class="text-sm opacity-90">European Gateway</p>
                            <div class="flex items-center mt-2 text-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                8 hrs from New York
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dubai -->
                <div class="group relative overflow-hidden rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="h-80 bg-cover bg-center relative" style="background-image: url('https://images.unsplash.com/photo-1512453979798-5ea266f8880c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80');">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                        <div class="absolute bottom-6 left-6 text-white">
                            <h3 class="font-primary text-xl font-bold mb-1">Dubai</h3>
                            <p class="text-sm opacity-90">Luxury & Business</p>
                            <div class="flex items-center mt-2 text-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                14 hrs from New York
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Monaco -->
                <div class="group relative overflow-hidden rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="h-80 bg-cover bg-center relative" style="background-image: url('https://images.unsplash.com/photo-1549068106-b024baf5062d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80');">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                        <div class="absolute bottom-6 left-6 text-white">
                            <h3 class="font-primary text-xl font-bold mb-1">Monaco</h3>
                            <p class="text-sm opacity-90">Exclusive Getaway</p>
                            <div class="flex items-center mt-2 text-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                9 hrs from New York
                            </div>
                        </div>
                        <div class="absolute top-4 right-4 bg-white bg-opacity-90 backdrop-blur px-3 py-1 rounded-full">
                            <span class="text-sm font-semibold" style="color: var(--color-gold);">VIP</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Info -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-16">
                <div class="text-center">
                    <div class="w-16 h-16 rounded-full mx-auto mb-4 flex items-center justify-center" style="background-color: var(--color-emerald);">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="font-primary text-xl font-bold mb-2" style="color: var(--color-emerald);">Global Reach</h3>
                    <p class="text-gray-600">Fly to over 5,000 airports worldwide with our extensive network and partnerships.</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 rounded-full mx-auto mb-4 flex items-center justify-center" style="background-color: var(--color-gold);">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="font-primary text-xl font-bold mb-2" style="color: var(--color-emerald);">Customs & Immigration</h3>
                    <p class="text-gray-600">Expedited customs clearance and VIP immigration services at all major destinations.</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 rounded-full mx-auto mb-4 flex items-center justify-center" style="background-color: var(--color-brown);">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="font-primary text-xl font-bold mb-2" style="color: var(--color-emerald);">Ground Services</h3>
                    <p class="text-gray-600">Luxury ground transportation and concierge services available at all destinations.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Client Testimonials Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="font-primary text-4xl font-bold mb-4" style="color: var(--color-emerald);">What Our Clients Say</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Trusted by executives, celebrities, and discerning travelers worldwide for our exceptional service and reliability.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div class="bg-gray-50 rounded-xl p-8 hover:shadow-lg transition-all duration-300">
                    <div class="flex items-center mb-4">
                        <div class="flex space-x-1">
                            <svg class="w-5 h-5" style="color: var(--color-gold);" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" style="color: var(--color-gold);" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" style="color: var(--color-gold);" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" style="color: var(--color-gold);" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" style="color: var(--color-gold);" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-6 italic">"Exceptional service from booking to landing. The crew was professional, the aircraft immaculate, and the entire experience exceeded our expectations. Flite Charter has become our exclusive aviation partner."</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center mr-4">
                            <span class="font-bold text-gray-600">JS</span>
                        </div>
                        <div>
                            <div class="font-semibold" style="color: var(--color-emerald);">James Sullivan</div>
                            <div class="text-sm text-gray-500">CEO, Sullivan Enterprises</div>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="bg-gray-50 rounded-xl p-8 hover:shadow-lg transition-all duration-300">
                    <div class="flex items-center mb-4">
                        <div class="flex space-x-1">
                            <svg class="w-5 h-5" style="color: var(--color-gold);" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" style="color: var(--color-gold);" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" style="color: var(--color-gold);" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" style="color: var(--color-gold);" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" style="color: var(--color-gold);" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-6 italic">"Time is everything in our business. Flite Charter understands that perfectly. Their punctuality, flexibility, and attention to detail make them indispensable for our executive travel needs."</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center mr-4">
                            <span class="font-bold text-gray-600">MR</span>
                        </div>
                        <div>
                            <div class="font-semibold" style="color: var(--color-emerald);">Maria Rodriguez</div>
                            <div class="text-sm text-gray-500">VP Operations, Global Ventures</div>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="bg-gray-50 rounded-xl p-8 hover:shadow-lg transition-all duration-300">
                    <div class="flex items-center mb-4">
                        <div class="flex space-x-1">
                            <svg class="w-5 h-5" style="color: var(--color-gold);" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" style="color: var(--color-gold);" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" style="color: var(--color-gold);" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" style="color: var(--color-gold);" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" style="color: var(--color-gold);" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-6 italic">"Flying with Flite Charter is like having your own private airline. The level of personalization and care they provide is unmatched. From custom catering to ground transportation, everything is seamless."</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center mr-4">
                            <span class="font-bold text-gray-600">DC</span>
                        </div>
                        <div>
                            <div class="font-semibold" style="color: var(--color-emerald);">David Chen</div>
                            <div class="text-sm text-gray-500">Managing Partner, Chen & Associates</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div>
                    <h2 class="font-primary text-4xl font-bold mb-6" style="color: var(--color-emerald);">
                        Why Choose Flite Charter?
                    </h2>
                    <p class="text-xl text-gray-600 mb-8">
                        Experience the difference with our commitment to excellence, safety, and personalized service that elevates every journey.
                    </p>

                    <div class="space-y-6">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0" style="background-color: var(--color-gold);">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-primary text-xl font-bold mb-2" style="color: var(--color-emerald);">Elite Safety Standards</h3>
                                <p class="text-gray-600">Comprehensive safety protocols and rigorous maintenance ensure the highest level of security for every flight.</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0" style="background-color: var(--color-gold);">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-primary text-xl font-bold mb-2" style="color: var(--color-emerald);">Instant Response</h3>
                                <p class="text-gray-600">Quick turnaround times with dedicated concierge service available 24/7 for immediate assistance.</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0" style="background-color: var(--color-gold);">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-primary text-xl font-bold mb-2" style="color: var(--color-emerald);">Luxury Experience</h3>
                                <p class="text-gray-600">Premium amenities, personalized service, and attention to every detail for an exceptional travel experience.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-100 rounded-2xl p-8">
                    <div class="grid grid-cols-2 gap-8">
                        <div class="text-center">
                            <div class="text-4xl font-bold mb-2" style="color: var(--color-emerald);">500+</div>
                            <div class="text-gray-600">Successful Flights</div>
                        </div>
                        <div class="text-center">
                            <div class="text-4xl font-bold mb-2" style="color: var(--color-emerald);">50+</div>
                            <div class="text-gray-600">Aircraft Available</div>
                        </div>
                        <div class="text-center">
                            <div class="text-4xl font-bold mb-2" style="color: var(--color-emerald);">24/7</div>
                            <div class="text-gray-600">Customer Support</div>
                        </div>
                        <div class="text-center">
                            <div class="text-4xl font-bold mb-2" style="color: var(--color-emerald);">100%</div>
                            <div class="text-gray-600">Safety Record</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20" style="background-color: var(--color-emerald);">
        <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
            <h2 class="font-primary text-4xl font-bold text-white mb-6">
                Ready to Experience Luxury Aviation?
            </h2>
            <p class="text-xl text-gray-200 mb-8">
                Join thousands of satisfied clients who trust Flite Charter for their premium air travel needs.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="#" class="inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-white rounded-lg hover:bg-opacity-90 transition-all duration-300 transform hover:scale-105" style="background-color: var(--color-gold);">
                    Book Your Flight Now
                    <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </a>
                <a href="tel:+1234567890" class="inline-flex items-center justify-center px-8 py-4 text-lg font-semibold border-2 border-white text-white rounded-lg hover:bg-white hover:text-[var(--color-emerald)] transition-all duration-300">
                    <svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    Call Now: +1 (234) 567-8900
                </a>
            </div>
        </div>
    </section>
</x-layouts.main>
