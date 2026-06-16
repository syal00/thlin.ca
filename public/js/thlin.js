document.addEventListener('DOMContentLoaded', () => {
    initHomepageReveal();

    const toggle = document.querySelector('[data-nav-toggle]');
    const nav = document.querySelector('[data-main-nav]');
    const mq = window.matchMedia('(max-width: 1024px)');

    if (!toggle || !nav) {
        return;
    }

    const closeNav = () => {
        nav.classList.remove('is-open');
        toggle.setAttribute('aria-expanded', 'false');
        document.body.classList.remove('nav-open');
        nav.querySelectorAll('.submenu-open').forEach((item) => {
            item.classList.remove('submenu-open');
        });
    };

    const openNav = () => {
        nav.classList.add('is-open');
        toggle.setAttribute('aria-expanded', 'true');
        document.body.classList.add('nav-open');
    };

    toggle.addEventListener('click', () => {
        if (nav.classList.contains('is-open')) {
            closeNav();
        } else {
            openNav();
        }
    });

    nav.querySelectorAll(':scope > ul > li').forEach((item) => {
        const submenu = item.querySelector(':scope > ul');
        const trigger = item.querySelector(':scope > a');

        if (!submenu || !trigger) {
            return;
        }

        item.classList.add('has-submenu');

        trigger.addEventListener('click', (event) => {
            if (!mq.matches) {
                return;
            }

            event.preventDefault();
            const isOpen = item.classList.contains('submenu-open');

            nav.querySelectorAll('.submenu-open').forEach((openItem) => {
                if (openItem !== item) {
                    openItem.classList.remove('submenu-open');
                }
            });

            item.classList.toggle('submenu-open', !isOpen);
        });
    });

    nav.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => {
            if (mq.matches && !link.closest('.has-submenu > a')) {
                closeNav();
            }

            if (mq.matches && link.closest('.has-submenu ul a')) {
                closeNav();
            }
        });
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeNav();
        }
    });

    mq.addEventListener('change', () => {
        closeNav();
    });

    window.addEventListener('resize', () => {
        if (!mq.matches) {
            closeNav();
        }
    });
});

function initHomepageReveal() {
    if (!document.body.classList.contains('is-home-page')) {
        return;
    }

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        document.querySelectorAll('.reveal-on-scroll').forEach((element) => {
            element.classList.add('is-revealed');
        });
        return;
    }

    const heroCard = document.querySelector('.is-home-page .hero-card');

    if (heroCard) {
        heroCard.classList.add('home-hero-enter');
    }

    const revealTargets = document.querySelectorAll('.is-home-page .reveal-on-scroll');

    if (!revealTargets.length) {
        return;
    }

    revealTargets.forEach((element) => {
        const delay = element.dataset.revealDelay;

        if (delay) {
            element.style.setProperty('--reveal-delay', delay);
        }
    });

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) {
                return;
            }

            entry.target.classList.add('is-revealed');
            observer.unobserve(entry.target);
        });
    }, {
        threshold: 0.12,
        rootMargin: '0px 0px -8% 0px',
    });

    revealTargets.forEach((element) => observer.observe(element));
}
