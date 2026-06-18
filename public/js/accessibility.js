document.addEventListener('DOMContentLoaded', function () {
    const body = document.body;

    const decreaseText = document.getElementById('decreaseText');
    const increaseText = document.getElementById('increaseText');
    const toggleContrast = document.getElementById('toggleContrast');
    const resetAccessibility = document.getElementById('resetAccessibility');
    const backToTop = document.getElementById('backToTop');

    const savedTextSize = localStorage.getItem('thlinTextSize');
    const savedContrast = localStorage.getItem('thlinHighContrast');

    if (savedTextSize === 'large') {
        body.classList.add('text-large');
    }

    if (savedTextSize === 'small') {
        body.classList.add('text-small');
    }

    if (savedContrast === 'true') {
        body.classList.add('high-contrast');
    }

    increaseText?.addEventListener('click', function () {
        body.classList.remove('text-small');
        body.classList.add('text-large');
        localStorage.setItem('thlinTextSize', 'large');
    });

    decreaseText?.addEventListener('click', function () {
        body.classList.remove('text-large');
        body.classList.add('text-small');
        localStorage.setItem('thlinTextSize', 'small');
    });

    toggleContrast?.addEventListener('click', function () {
        body.classList.toggle('high-contrast');
        localStorage.setItem('thlinHighContrast', body.classList.contains('high-contrast') ? 'true' : 'false');
    });

    resetAccessibility?.addEventListener('click', function () {
        body.classList.remove('text-large', 'text-small', 'high-contrast');
        localStorage.removeItem('thlinTextSize');
        localStorage.removeItem('thlinHighContrast');
    });

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
