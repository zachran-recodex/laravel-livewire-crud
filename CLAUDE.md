## Development Notes
- use the flux(resources/views/flux) component in the created view
- always explain in Indonesian
- before generating code, study docs/LARAVEL-BEST-PRACTICES.md and docs/LIVEWIRE-BEST-PRACTICES.md and apply them to the generated code.
- the generated code must always be consistent with the existing code.

## JavaScript + Livewire Integration Best Practices

### Event Listeners
Always use both events for Livewire compatibility:
```javascript
document.addEventListener('DOMContentLoaded', initFunction);
document.addEventListener('livewire:navigated', () => setTimeout(initFunction, 300));
```

### Timing & Retry Mechanism
- Use setTimeout with delay (100-300ms) untuk memastikan DOM ready
- Implement retry mechanism dengan multiple delays:
```javascript
function retryInitialization() {
    [500, 1000, 2000].forEach(delay => {
        setTimeout(() => {
            if (!initialized) initFunction();
        }, delay);
    });
}
```

### Instance Management (Charts, Libraries, etc)
- Selalu destroy existing instances sebelum membuat yang baru
- Simpan instances di window object untuk akses global
- Check apakah instances sudah ada sebelum retry:
```javascript
window.instances = { chart1: null, chart2: null };

// Destroy existing
if (window.instances.chart1) {
    window.instances.chart1.destroy();
}
```

### Data Extraction
- Gunakan fallback robust: `dataset.attribute || textContent || defaultValue`
- Wrap dalam try-catch untuk error handling
- Parse JSON dengan fallback default:
```javascript
const data = JSON.parse(element.dataset.data || '{"default": "value"}');
```

### Element Detection
- Check keberadaan semua required elements sebelum inisialisasi
- Return early jika elements tidak ditemukan:
```javascript
const el1 = document.getElementById('required1');
const el2 = document.getElementById('required2');
if (!el1 || !el2) return false;
```

### File Organization
- Pisahkan logic ke file terpisah (chart.js, modal.js, etc)
- Import melalui main app.js
- Modular approach untuk maintainability

### Template Pattern
```javascript
// Global instances
window.myInstances = { item1: null, item2: null };

// Init function
function initMyFeature() {
    // Check elements
    if (!requiredElements) return false;
    
    try {
        // Destroy existing
        if (window.myInstances.item1) {
            window.myInstances.item1.destroy();
        }
        
        // Initialize new
        window.myInstances.item1 = new Library(config);
        return true;
    } catch (error) {
        console.error('Init error:', error);
        return false;
    }
}

// Event listeners
document.addEventListener('DOMContentLoaded', initMyFeature);
document.addEventListener('livewire:navigated', () => setTimeout(initMyFeature, 300));

// Retry mechanism
function retryInit() {
    [500, 1000, 2000].forEach(delay => {
        setTimeout(() => {
            if (!window.myInstances.item1) initMyFeature();
        }, delay);
    });
}

document.addEventListener('DOMContentLoaded', retryInit);
document.addEventListener('livewire:navigated', retryInit);
```
