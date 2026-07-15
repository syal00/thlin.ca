document.addEventListener('DOMContentLoaded', function () {
    // Scaling/contrast toggle on <html>, not <body>: every font-size in the
    // new CSS system is `rem`-based off the root element, so this is what
    // actually cascades through headings/buttons/cards instead of only
    // affecting unstyled text. See public/css/base.css .t-text-large /
    // .t-text-small / .t-high-contrast.
    const root = document.documentElement;

    const decreaseText = document.getElementById('decreaseText');
    const increaseText = document.getElementById('increaseText');
    const toggleContrast = document.getElementById('toggleContrast');
    const resetAccessibility = document.getElementById('resetAccessibility');

    const savedTextSize = localStorage.getItem('thlinTextSize');
    const savedContrast = localStorage.getItem('thlinHighContrast');

    if (savedTextSize === 'large') {
        root.classList.add('t-text-large');
    }

    if (savedTextSize === 'small') {
        root.classList.add('t-text-small');
    }

    if (savedContrast === 'true') {
        root.classList.add('t-high-contrast');
    }

    increaseText?.addEventListener('click', function () {
        root.classList.remove('t-text-small');
        root.classList.add('t-text-large');
        localStorage.setItem('thlinTextSize', 'large');
    });

    decreaseText?.addEventListener('click', function () {
        root.classList.remove('t-text-large');
        root.classList.add('t-text-small');
        localStorage.setItem('thlinTextSize', 'small');
    });

    toggleContrast?.addEventListener('click', function () {
        root.classList.toggle('t-high-contrast');
        localStorage.setItem('thlinHighContrast', root.classList.contains('t-high-contrast') ? 'true' : 'false');
    });

    resetAccessibility?.addEventListener('click', function () {
        root.classList.remove('t-text-large', 't-text-small', 't-high-contrast');
        localStorage.removeItem('thlinTextSize');
        localStorage.removeItem('thlinHighContrast');
    });

    const backToTop = document.getElementById('backToTop');

    function updateBackToTopVisibility() {
        if (!backToTop) {
            return;
        }

        if (window.scrollY > 300) {
            backToTop.classList.add('is-visible');
        } else {
            backToTop.classList.remove('is-visible');
        }
    }

    window.addEventListener('scroll', updateBackToTopVisibility);
    updateBackToTopVisibility();

    backToTop?.addEventListener('click', function () {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
});
