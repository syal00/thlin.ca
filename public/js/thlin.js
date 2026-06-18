document.addEventListener('DOMContentLoaded', () => {
    initHomepageReveal();

    initAccessibilityToolbar();
    initBackToTopButton();

    const toggle = document.querySelector('[data-nav-toggle]');
    const nav = document.querySelector('[data-main-nav]');
    const mq = window.matchMedia('(max-width: 1024px)');

    if (toggle && nav) {
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
    }
});

function initAccessibilityToolbar() {
    const toolbar = document.querySelector('[data-accessibility-toolbar]');

    if (!toolbar) {
        return;
    }

    const scaleKey = 'thlin-text-scale';
    const contrastKey = 'thlin-high-contrast';
    const scaleSteps = [100, 110, 120];
    const root = document.documentElement;

    const readStoredScale = () => {
        const storedValue = Number.parseInt(window.localStorage.getItem(scaleKey) || '100', 10);

        return scaleSteps.includes(storedValue) ? storedValue : 100;
    };

    const applyScale = (scale) => {
        if (scale === 100) {
            root.style.fontSize = '';
        } else {
            root.style.fontSize = `${scale}%`;
        }

        window.localStorage.setItem(scaleKey, String(scale));
    };

    const applyContrast = (enabled) => {
        document.body.classList.toggle('thlin-high-contrast', enabled);

        toolbar.querySelector('[data-accessibility-action="contrast"]')?.setAttribute('aria-pressed', enabled ? 'true' : 'false');
        window.localStorage.setItem(contrastKey, enabled ? '1' : '0');
    };

    let currentScale = readStoredScale();
    applyScale(currentScale);
    applyContrast(window.localStorage.getItem(contrastKey) === '1');

    toolbar.querySelectorAll('[data-accessibility-action]').forEach((button) => {
        button.addEventListener('click', () => {
            const action = button.dataset.accessibilityAction;

            if (action === 'increase') {
                currentScale = Math.min(scaleSteps[scaleSteps.length - 1], currentScale + 10);
                applyScale(currentScale);
                return;
            }

            if (action === 'decrease') {
                currentScale = Math.max(scaleSteps[0], currentScale - 10);
                applyScale(currentScale);
                return;
            }

            if (action === 'contrast') {
                applyContrast(!document.body.classList.contains('thlin-high-contrast'));
                return;
            }

            if (action === 'reset') {
                currentScale = 100;
                applyScale(currentScale);
                applyContrast(false);
            }
        });
    });
}

function initBackToTopButton() {
    const button = document.querySelector('[data-back-to-top]');

    if (!button) {
        return;
    }

    const updateVisibility = () => {
        button.classList.toggle('is-visible', window.scrollY > 400);
    };

    button.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    window.addEventListener('scroll', updateVisibility, { passive: true });
    updateVisibility();
}

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
