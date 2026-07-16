document.addEventListener('DOMContentLoaded', function () {
    const backToTop = document.getElementById('backToTop');

    if (!backToTop) {
        return;
    }

    function updateBackToTopVisibility() {
        if (window.scrollY > 300) {
            backToTop.classList.add('is-visible');
        } else {
            backToTop.classList.remove('is-visible');
        }
    }

    window.addEventListener('scroll', updateBackToTopVisibility, { passive: true });
    updateBackToTopVisibility();

    backToTop.addEventListener('click', function () {
        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        window.scrollTo({
            top: 0,
            behavior: reduceMotion ? 'auto' : 'smooth'
        });
    });
});
