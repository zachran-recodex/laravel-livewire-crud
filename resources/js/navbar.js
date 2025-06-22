// Navbar scroll effect
function initNavbar() {
    const navbar = document.getElementById('navbar');
    const navLinks = navbar?.querySelectorAll('a');
    const mobileButton = navbar?.querySelector('button');
    
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
                    link.classList.remove('text-white', 'hover:text-[var(--flite-gold)]');
                    link.classList.add('text-gray-700', 'hover:text-[var(--flite-emerald)]');
                }
            });
            
            // Update mobile button
            if (mobileButton) {
                mobileButton.classList.remove('text-white', 'hover:text-[var(--flite-gold)]', 'hover:bg-white/10');
                mobileButton.classList.add('text-gray-700', 'hover:text-[var(--flite-emerald)]', 'hover:bg-gray-100');
            }
        } else {
            // Top state - transparent
            navbar.style.backgroundColor = 'transparent';
            navbar.style.backdropFilter = 'none';
            navbar.style.borderBottom = 'none';
            
            // Update link colors to white
            navLinks?.forEach(link => {
                if (link.textContent !== 'Get Quote') {
                    link.classList.remove('text-gray-700', 'hover:text-[var(--flite-emerald)]');
                    link.classList.add('text-white', 'hover:text-[var(--flite-gold)]');
                }
            });
            
            // Update mobile button
            if (mobileButton) {
                mobileButton.classList.remove('text-gray-700', 'hover:text-[var(--flite-emerald)]', 'hover:bg-gray-100');
                mobileButton.classList.add('text-white', 'hover:text-[var(--flite-gold)]', 'hover:bg-white/10');
            }
        }
    }
    
    // Initial call
    updateNavbar();
    
    // Listen for scroll events
    window.addEventListener('scroll', updateNavbar, { passive: true });
}

// Initialize navbar when DOM is loaded
document.addEventListener('DOMContentLoaded', initNavbar);

// Reinitialize on Livewire navigation
document.addEventListener('livewire:navigated', () => {
    setTimeout(initNavbar, 100);
});