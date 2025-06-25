// Navbar scroll effect
function initNavbar() {
    const navbar = document.getElementById('navbar');
    const navLinks = navbar?.querySelectorAll('a');
    const hamburgerButton = document.getElementById('sidebar-toggle');
    const mobileButton = navbar?.querySelector('.md\\:hidden button:not(#sidebar-toggle)');

    if (!navbar) return;

    function updateNavbar() {
        const scrolled = window.scrollY > 50;

        if (scrolled) {
            // Scrolled state - white background
            navbar.style.backgroundColor = 'rgba(255, 255, 255, 0.95)';
            navbar.style.backdropFilter = 'blur(10px)';
            navbar.style.borderBottom = '1px solid rgba(0, 0, 0, 0.1)';

            // Update link colors to dark
            navLinks?.forEach(link => {
                if (link.textContent !== 'Get Quote') {
                    link.classList.remove('text-white', 'hover:text-[var(--color-gold)]');
                    link.classList.add('text-gray-700', 'hover:text-[var(--color-emerald)]');
                }
            });

            // Update hamburger button
            if (hamburgerButton) {
                hamburgerButton.classList.remove('text-white', 'hover:text-[var(--color-gold)]', 'hover:bg-white/10');
                hamburgerButton.classList.add('text-gray-700', 'hover:text-[var(--color-emerald)]', 'hover:bg-gray-100');
            }

            // Update mobile button
            if (mobileButton) {
                mobileButton.classList.remove('text-white', 'hover:text-[var(--color-gold)]', 'hover:bg-white/10');
                mobileButton.classList.add('text-gray-700', 'hover:text-[var(--color-emerald)]', 'hover:bg-gray-100');
            }
        } else {
            // Top state - transparent
            navbar.style.backgroundColor = 'transparent';
            navbar.style.backdropFilter = 'none';
            navbar.style.borderBottom = 'none';

            // Update link colors to white
            navLinks?.forEach(link => {
                if (link.textContent !== 'Get Quote') {
                    link.classList.remove('text-gray-700', 'hover:text-[var(--color-emerald)]');
                    link.classList.add('text-white', 'hover:text-[var(--color-gold)]');
                }
            });

            // Update hamburger button
            if (hamburgerButton) {
                hamburgerButton.classList.remove('text-gray-700', 'hover:text-[var(--color-emerald)]', 'hover:bg-gray-100');
                hamburgerButton.classList.add('text-white', 'hover:text-[var(--color-gold)]', 'hover:bg-white/10');
            }

            // Update mobile button
            if (mobileButton) {
                mobileButton.classList.remove('text-gray-700', 'hover:text-[var(--color-emerald)]', 'hover:bg-gray-100');
                mobileButton.classList.add('text-white', 'hover:text-[var(--color-gold)]', 'hover:bg-white/10');
            }
        }
    }

    // Initial call
    updateNavbar();

    // Listen for scroll events
    window.addEventListener('scroll', updateNavbar, { passive: true });
}

// Sidebar functionality
function initSidebar() {
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebarClose = document.getElementById('sidebar-close');
    const sidebar = document.getElementById('sidebar');

    if (!sidebarToggle || !sidebar || !sidebarClose) return;

    function openSidebar() {
        sidebar.classList.remove('-translate-x-full');
        document.body.style.overflow = 'hidden';
        
        // Add blur effect to main content without overlay
        const mainContent = document.querySelector('main');
        const navbar = document.getElementById('navbar');
        const footer = document.querySelector('footer');
        
        if (mainContent) {
            mainContent.style.filter = 'blur(3px)';
            mainContent.style.transition = 'filter 0.3s ease-in-out';
        }
        if (navbar) {
            navbar.style.filter = 'blur(3px)';
            navbar.style.transition = 'filter 0.3s ease-in-out';
        }
        if (footer) {
            footer.style.filter = 'blur(3px)';
            footer.style.transition = 'filter 0.3s ease-in-out';
        }
    }

    function closeSidebar() {
        sidebar.classList.add('-translate-x-full');
        document.body.style.overflow = '';
        
        // Remove blur effect from main content
        const mainContent = document.querySelector('main');
        const navbar = document.getElementById('navbar');
        const footer = document.querySelector('footer');
        
        if (mainContent) mainContent.style.filter = '';
        if (navbar) navbar.style.filter = '';
        if (footer) footer.style.filter = '';
    }

    // Toggle sidebar when hamburger menu is clicked
    sidebarToggle.addEventListener('click', openSidebar);

    // Close sidebar when close button is clicked
    sidebarClose.addEventListener('click', closeSidebar);

    // Close sidebar when clicking outside (on the blurred content)
    document.addEventListener('click', function(e) {
        if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target) && !sidebar.classList.contains('-translate-x-full')) {
            closeSidebar();
        }
    });

    // Close sidebar when a navigation link is clicked
    const sidebarLinks = sidebar.querySelectorAll('a[href^="#"]');
    sidebarLinks.forEach(link => {
        link.addEventListener('click', closeSidebar);
    });

    // Close sidebar on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !sidebar.classList.contains('-translate-x-full')) {
            closeSidebar();
        }
    });
}

// Initialize both navbar and sidebar when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initNavbar();
    initSidebar();
});

// Reinitialize on Livewire navigation
document.addEventListener('livewire:navigated', () => {
    setTimeout(() => {
        initNavbar();
        initSidebar();
    }, 100);
});
