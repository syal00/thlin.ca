document.addEventListener('DOMContentLoaded', function () {
    const counters = document.querySelectorAll('.count-up');

    function animateCounter(counter) {
        const target = parseInt(counter.dataset.count, 10);
        const duration = 1200;
        const startTime = performance.now();

        function update(currentTime) {
            const progress = Math.min((currentTime - startTime) / duration, 1);
            const value = Math.floor(progress * target);

            counter.textContent = value.toLocaleString();

            if (progress < 1) {
                requestAnimationFrame(update);
            } else {
                counter.textContent = target.toLocaleString();
            }
        }

        requestAnimationFrame(update);
    }

    if (!('IntersectionObserver' in window)) {
        counters.forEach(animateCounter);
        return;
    }

    const observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.35,
    });

    counters.forEach(function (counter) {
        observer.observe(counter);
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const animatedItems = document.querySelectorAll('[data-animate]');

    if (!('IntersectionObserver' in window)) {
        animatedItems.forEach(item => item.classList.add('is-visible'));
        return;
    }

    const observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.15,
    });

    animatedItems.forEach(function (item) {
        observer.observe(item);
    });
});
