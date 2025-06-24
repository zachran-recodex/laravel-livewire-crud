# Penguin UI - Carousel Component

A comprehensive carousel component built with **Tailwind CSS** and **Alpine.js** for displaying multiple images in a visually appealing format. Perfect for showcasing products, services, portfolios, and more.

## Prerequisites

- **Alpine.js v3** is required for functionality
- Some advanced features may require additional Alpine plugins (such as focus)

---

## 🎠 Carousel Variants

### 1. Default Carousel

A basic carousel component with images and navigation controls. Controls have background colors by default for visibility - you can remove backgrounds and keep only icons if they have sufficient contrast.

```html
<div x-data="{
    slides: [
        {
            imgSrc: 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-1.webp',
            imgAlt: 'Vibrant abstract painting with swirling blue and light pink hues on a canvas.',
        },
        {
            imgSrc: 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-2.webp',
            imgAlt: 'Vibrant abstract painting with swirling red, yellow, and pink hues on a canvas.',
        },
        {
            imgSrc: 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-3.webp',
            imgAlt: 'Vibrant abstract painting with swirling blue and purple hues on a canvas.',
        },
    ],
    currentSlideIndex: 1,
    previous() {
        if (this.currentSlideIndex > 1) {
            this.currentSlideIndex = this.currentSlideIndex - 1
        } else {
            // If it's the first slide, go to the last slide
            this.currentSlideIndex = this.slides.length
        }
    },
    next() {
        if (this.currentSlideIndex < this.slides.length) {
            this.currentSlideIndex = this.currentSlideIndex + 1
        } else {
            // If it's the last slide, go to the first slide
            this.currentSlideIndex = 1
        }
    },
}" class="relative w-full overflow-hidden">

    <!-- Previous Button -->
    <button type="button" 
            class="absolute left-5 top-1/2 z-20 flex rounded-full -translate-y-1/2 items-center justify-center bg-white/40 p-2 text-neutral-600 transition hover:bg-white/60 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black active:outline-offset-0 dark:bg-neutral-950/40 dark:text-neutral-300 dark:hover:bg-neutral-950/60 dark:focus-visible:outline-white" 
            aria-label="previous slide" 
            x-on:click="previous()">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="3" class="size-5 md:size-6 pr-0.5" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
        </svg>
    </button>

    <!-- Next Button -->
    <button type="button" 
            class="absolute right-5 top-1/2 z-20 flex rounded-full -translate-y-1/2 items-center justify-center bg-white/40 p-2 text-neutral-600 transition hover:bg-white/60 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black active:outline-offset-0 dark:bg-neutral-950/40 dark:text-neutral-300 dark:hover:bg-neutral-950/60 dark:focus-visible:outline-white" 
            aria-label="next slide" 
            x-on:click="next()">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="3" class="size-5 md:size-6 pl-0.5" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
        </svg>
    </button>

    <!-- Slides Container -->
    <!-- Change min-h-[50svh] to your preferred height -->
    <div class="relative min-h-[50svh] w-full">
        <template x-for="(slide, index) in slides">
            <div x-show="currentSlideIndex == index + 1" class="absolute inset-0" x-transition.opacity.duration.1000ms>
                <img class="absolute w-full h-full inset-0 object-cover text-neutral-600 dark:text-neutral-300" 
                     x-bind:src="slide.imgSrc" 
                     x-bind:alt="slide.imgAlt" />
            </div>
        </template>
    </div>

    <!-- Indicators -->
    <div class="absolute rounded-sm bottom-3 md:bottom-5 left-1/2 z-20 flex -translate-x-1/2 gap-4 md:gap-3 bg-white/75 px-1.5 py-1 md:px-2 dark:bg-neutral-950/75" 
         role="group" 
         aria-label="slides">
        <template x-for="(slide, index) in slides">
            <button class="size-2 rounded-full transition bg-neutral-600 dark:bg-neutral-300" 
                    x-on:click="currentSlideIndex = index + 1" 
                    x-bind:class="[currentSlideIndex === index + 1 ? 'bg-neutral-600 dark:bg-neutral-300' : 'bg-neutral-600/50 dark:bg-neutral-300/50']" 
                    x-bind:aria-label="'slide ' + (index + 1)">
            </button>
        </template>
    </div>
</div>
```

---

### 2. Carousel with Text

Features images, titles, and descriptions with overlay text.

```html
<div x-data="{
    slides: [
        {
            imgSrc: 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-1.webp',
            imgAlt: 'Vibrant abstract painting with swirling blue and light pink hues on a canvas.',
            title: 'Front end developers',
            description: 'The architects of the digital world, constantly battling against their mortal enemy – browser compatibility.',
        },
        {
            imgSrc: 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-2.webp',
            imgAlt: 'Vibrant abstract painting with swirling red, yellow, and pink hues on a canvas.',
            title: 'Back end developers',
            description: 'Because not all superheroes wear capes, some wear headphones and stare at terminal screens',
        },
        {
            imgSrc: 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-3.webp',
            imgAlt: 'Vibrant abstract painting with swirling blue and purple hues on a canvas.',
            title: 'Full stack developers',
            description: 'Where &quot;burnout&quot; is just a fancy term for &quot;Tuesday&quot;.'
        },
    ],
    currentSlideIndex: 1,
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
}" class="relative w-full overflow-hidden">

    <!-- Navigation Buttons (same as default) -->
    <!-- ... previous and next buttons ... -->

    <!-- Slides with Text Overlay -->
    <div class="relative min-h-[50svh] w-full">
        <template x-for="(slide, index) in slides">
            <div x-cloak x-show="currentSlideIndex == index + 1" class="absolute inset-0" x-transition.opacity.duration.1000ms>
                
                <!-- Text Overlay -->
                <div class="lg:px-32 lg:py-14 absolute inset-0 z-10 flex flex-col items-center justify-end gap-2 bg-linear-to-t from-neutral-950/85 to-transparent px-16 py-12 text-center">
                    <h3 class="w-full lg:w-[80%] text-balance text-2xl lg:text-3xl font-bold text-white" 
                        x-text="slide.title" 
                        x-bind:aria-describedby="'slide' + (index + 1) + 'Description'">
                    </h3>
                    <p class="lg:w-1/2 w-full text-pretty text-sm text-neutral-300" 
                       x-text="slide.description" 
                       x-bind:id="'slide' + (index + 1) + 'Description'">
                    </p>
                </div>

                <img class="absolute w-full h-full inset-0 object-cover text-neutral-600 dark:text-neutral-300" 
                     x-bind:src="slide.imgSrc" 
                     x-bind:alt="slide.imgAlt" />
            </div>
        </template>
    </div>
    
    <!-- Indicators without background -->
    <div class="absolute rounded-sm bottom-3 md:bottom-5 left-1/2 z-20 flex -translate-x-1/2 gap-4 md:gap-3 px-1.5 py-1 md:px-2" 
         role="group" 
         aria-label="slides">
        <template x-for="(slide, index) in slides">
            <button class="size-2 rounded-full transition" 
                    x-on:click="currentSlideIndex = index + 1" 
                    x-bind:class="[currentSlideIndex === index + 1 ? 'bg-neutral-300' : 'bg-neutral-300/50']" 
                    x-bind:aria-label="'slide ' + (index + 1)">
            </button>
        </template>
    </div>
</div>
```

---

### 3. Carousel with CTA Button

Includes a call-to-action button on each slide.

```html
<div x-data="{
    slides: [
        {
            imgSrc: 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-1.webp',
            imgAlt: 'Vibrant abstract painting with swirling blue and light pink hues on a canvas.',
            title: 'Front end developers',
            description: 'The architects of the digital world, constantly battling against their mortal enemy – browser compatibility.',
            ctaUrl: 'https://example.com',
            ctaText: 'Become a Developer',
        },
        // ... more slides
    ],
    currentSlideIndex: 1,
    // ... navigation functions
}" class="relative w-full overflow-hidden">

    <!-- Navigation and slides similar to text version -->
    
    <!-- Text Overlay with CTA -->
    <div class="lg:px-32 lg:py-14 absolute inset-0 z-10 flex flex-col items-center justify-end gap-2 bg-linear-to-t from-neutral-950/85 to-transparent px-16 py-12 text-center">
        <h3 class="w-full lg:w-[80%] text-balance text-2xl lg:text-3xl font-bold text-white" 
            x-text="slide.title">
        </h3>
        <p class="lg:w-1/2 w-full text-pretty text-sm text-neutral-300" 
           x-text="slide.description">
        </p>
        <button type="button" 
                x-cloak 
                x-show="slide.ctaUrl !== null" 
                class="mt-2 whitespace-nowrap rounded-sm border border-white bg-transparent px-4 py-2 text-center text-xs font-medium tracking-wide text-white transition hover:opacity-75 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-onSurfaceDarkStrong active:opacity-100 active:outline-offset-0 md:text-sm" 
                x-text="slide.ctaText">
        </button>
    </div>
</div>
```

---

### 4. Carousel with Autoplay

Features automatic slide progression with pause/play controls.

```html
<div x-data="{
    // Time between slides in milliseconds
    autoplayIntervalTime: 4000,
    slides: [
        // ... slide data
    ],
    currentSlideIndex: 1,
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
    
    // Updates interval time
    setAutoplayInterval(newIntervalTime) {
        clearInterval(this.autoplayInterval)
        this.autoplayIntervalTime = newIntervalTime
        this.autoplay()
    },
}" x-init="autoplay" class="relative w-full overflow-hidden">

    <!-- Slides -->
    <!-- ... slide content ... -->
    
    <!-- Pause/Play Button -->
    <button type="button" 
            class="absolute bottom-5 right-5 z-20 rounded-full text-neutral-300 opacity-50 transition hover:opacity-80 focus-visible:opacity-80 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white active:outline-offset-0" 
            aria-label="pause carousel" 
            x-on:click="(isPaused = !isPaused), setAutoplayInterval(autoplayIntervalTime)" 
            x-bind:aria-pressed="isPaused">
        
        <!-- Play Icon -->
        <svg x-cloak x-show="isPaused" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-7">
            <path fill-rule="evenodd" d="M2 10a8 8 0 1 1 16 0 8 8 0 0 1-16 0Zm6.39-2.908a.75.75 0 0 1 .766.027l3.5 2.25a.75.75 0 0 1 0 1.262l-3.5 2.25A.75.75 0 0 1 8 12.25v-4.5a.75.75 0 0 1 .39-.658Z" clip-rule="evenodd">
        </svg>
        
        <!-- Pause Icon -->
        <svg x-cloak x-show="!isPaused" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-7">
            <path fill-rule="evenodd" d="M2 10a8 8 0 1 1 16 0 8 8 0 0 1-16 0Zm5-2.25A.75.75 0 0 1 7.75 7h.5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-.75.75h-.5a.75.75 0 0 1-.75-.75v-4.5Zm4 0a.75.75 0 0 1 .75-.75h.5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-.75.75h-.5a.75.75 0 0 1-.75-.75v-4.5Z" clip-rule="evenodd">
        </svg>
    </button>
</div>
```

---

### 5. Fixed Aspect Ratio Carousel

Perfect for displaying images without cropping, maintaining consistent aspect ratios.

```html
<div x-data="{
    // ... carousel data and functions
}" class="relative w-full overflow-hidden">

    <!-- Navigation buttons -->
    <!-- ... -->

    <!-- Slides with Fixed Aspect Ratio -->
    <!-- Change aspect-3/1 to match your images aspect ratio -->
    <div class="relative aspect-3/1 w-full">
        <template x-for="(slide, index) in slides">
            <div x-cloak x-show="currentSlideIndex == index + 1" class="absolute inset-0" x-transition.opacity.duration.700ms>
                <img class="absolute w-full h-full inset-0 object-cover text-neutral-600 dark:text-neutral-300" 
                     x-bind:src="slide.imgSrc" 
                     x-bind:alt="slide.imgAlt" />
            </div>
        </template>
    </div>
</div>
```

---

### 6. Card Carousel

Ideal for ecommerce websites displaying multiple product images within a card layout.

```html
<article class="group flex rounded-sm max-w-sm flex-col overflow-hidden border border-neutral-300 bg-neutral-50 text-neutral-600 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300">
    
    <!-- Carousel Section -->
    <div class="h-48 md:h-64 overflow-hidden">
        <div x-data="{
            slides: [
                {
                    imgSrc: 'https://penguinui.s3.amazonaws.com/component-assets/carousel/nft-1.webp',
                    imgAlt: 'An illustration of a cyberpunk-style ape wearing a hoodie and futuristic headphones.',
                },
                // ... more slides
            ],
            // ... carousel functions
        }" class="relative w-full overflow-hidden">
            
            <!-- Carousel content -->
            <!-- ... navigation and slides ... -->
        </div>
    </div>
    
    <!-- Card Content -->
    <div class="flex flex-col gap-4 p-6">
        <div class="flex flex-col md:flex-row gap-4 md:gap-12 justify-between">
            <div class="flex flex-col">
                <h3 class="text-lg lg:text-xl font-bold text-neutral-900 dark:text-white">BioHazardApe #343</h3>
            </div>
            <span class="w-fit bg-neutral-800 h-fit rounded-sm px-2 py-1 text-xs font-medium text-white dark:bg-neutral-300 dark:text-black">
                0.45 ETH
            </span>
        </div>
        
        <p class="mb-2 text-pretty text-sm">
            by <a href="#" class="text-black dark:text-white">@apeMakers</a><br/><br/>
            BioHazardApe NFT showcases a captivating collection of digital artworks inspired by the wild essence of apes.
        </p>
        
        <button type="button" class="flex items-center justify-center gap-2 border border-black whitespace-nowrap bg-black px-4 py-2 text-center text-sm font-medium tracking-wide text-neutral-100 transition hover:opacity-75 rounded-sm">
            I Must Have It
        </button>
    </div>
</article>
```

---

### 7. Touch-Enabled Carousel

Features swipe gesture support for mobile devices.

```html
<div x-data="{
    slides: [
        // ... slide data
    ],
    currentSlideIndex: 1,
    touchStartX: null,
    touchEndX: null,
    swipeThreshold: 50,
    
    // ... navigation functions
    
    handleTouchStart(event) {
        this.touchStartX = event.touches[0].clientX
    },
    
    handleTouchMove(event) {
        this.touchEndX = event.touches[0].clientX
    },
    
    handleTouchEnd() {
        if (this.touchEndX) {
            if (this.touchStartX - this.touchEndX > this.swipeThreshold) {
                this.next()
            }
            if (this.touchStartX - this.touchEndX < -this.swipeThreshold) {
                this.previous()
            }
            this.touchStartX = null
            this.touchEndX = null
        }
    },
}" class="relative w-full overflow-hidden">

    <!-- Navigation buttons -->
    <!-- ... -->

    <!-- Touch-enabled slides -->
    <div class="relative min-h-[50svh] w-full" 
         x-on:touchstart="handleTouchStart($event)" 
         x-on:touchmove="handleTouchMove($event)" 
         x-on:touchend="handleTouchEnd()">
        <template x-for="(slide, index) in slides">
            <div x-show="currentSlideIndex == index + 1" class="absolute inset-0" x-transition.opacity.duration.700ms>
                <img class="absolute w-full h-full inset-0 object-cover text-neutral-600 dark:text-neutral-300" 
                     x-bind:src="slide.imgSrc" 
                     x-bind:alt="slide.imgAlt" />
            </div>
        </template>
    </div>
</div>
```

---

## 📊 Data Properties

### Core Properties

| Property            | Type     | Description                                |
|---------------------|----------|--------------------------------------------|
| `slides`            | Array    | List of slide data objects                 |
| `currentSlideIndex` | Number   | Index of currently displayed slide         |
| `next()`            | Function | Navigate to next slide (loops to first)    |
| `previous()`        | Function | Navigate to previous slide (loops to last) |

### Autoplay Properties

| Property                    | Type     | Description                        |
|-----------------------------|----------|------------------------------------|
| `autoplayIntervalTime`      | Number   | Time between slides (milliseconds) |
| `isPaused`                  | Boolean  | Autoplay pause state               |
| `autoplayInterval`          | Number   | Interval ID for autoplay           |
| `autoplay()`                | Function | Start autoplay functionality       |
| `setAutoplayInterval(time)` | Function | Update autoplay interval           |

### Touch Properties

| Property             | Type     | Description                     |
|----------------------|----------|---------------------------------|
| `touchStartX`        | Number   | Touch start X coordinate        |
| `touchEndX`          | Number   | Touch end X coordinate          |
| `swipeThreshold`     | Number   | Minimum swipe distance (pixels) |
| `handleTouchStart()` | Function | Handle touch start event        |
| `handleTouchMove()`  | Function | Handle touch move event         |
| `handleTouchEnd()`   | Function | Handle touch end event          |

---

## ⌨️ Keyboard Navigation

| Key                   | Action                       |
|-----------------------|------------------------------|
| **Tab**               | Focus next carousel element  |
| **Space** / **Enter** | Select focused carousel item |

---

## 🎨 Customization Tips

1. **Height Adjustment**: Change `min-h-[50svh]` to your preferred height
2. **Aspect Ratio**: Use `aspect-3/1` or similar for fixed ratios
3. **Transition Speed**: Modify `x-transition.opacity.duration.1000ms`
4. **Autoplay Timing**: Adjust `autoplayIntervalTime` value
5. **Swipe Sensitivity**: Change `swipeThreshold` for touch controls

---

## ♿ Accessibility Features

- ARIA labels for screen readers
- Keyboard navigation support
- Focus management
- Semantic HTML structure
- Alternative text for images

---

*Note: Some images and text content are AI-generated placeholders for demonstration purposes and may contain inconsistencies.*
