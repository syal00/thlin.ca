document.addEventListener('DOMContentLoaded', () => {
    initHomepageReveal();
    initStatCountUp();
    initNavDropdowns();

    const toggle = document.querySelector('[data-nav-toggle]');
    const nav = document.querySelector('[data-main-nav]');
    const mq = window.matchMedia('(max-width: 767px)');

    if (toggle && nav) {
        const closeNav = () => {
            nav.classList.remove('is-open');
            toggle.setAttribute('aria-expanded', 'false');
            document.body.classList.remove('nav-open');
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

        // Mobile menu items are plain links (no submenu toggling) — any
        // link click just navigates, so close the panel behind it.
        nav.querySelectorAll('a').forEach((link) => {
            link.addEventListener('click', () => {
                if (mq.matches) {
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

function initNavDropdowns() {
    const dropdowns = Array.from(document.querySelectorAll('[data-nav-dropdown]'));

    if (!dropdowns.length) {
        return;
    }

    // Desktop hover is an enhancement layered on top of click/keyboard —
    // touch devices (no real hover) never get stuck relying on it.
    const hoverCapable = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
    const closeTimeouts = new WeakMap();

    function closeDropdown(dropdown) {
        const trigger = dropdown.querySelector('[data-nav-dropdown-trigger]');
        dropdown.classList.remove('is-open');
        trigger?.setAttribute('aria-expanded', 'false');
    }

    function closeAllExcept(except) {
        dropdowns.forEach((dropdown) => {
            if (dropdown !== except) {
                closeDropdown(dropdown);
            }
        });
    }

    function openDropdown(dropdown) {
        closeAllExcept(dropdown);
        const trigger = dropdown.querySelector('[data-nav-dropdown-trigger]');
        dropdown.classList.add('is-open');
        trigger?.setAttribute('aria-expanded', 'true');
    }

    dropdowns.forEach((dropdown) => {
        const trigger = dropdown.querySelector('[data-nav-dropdown-trigger]');

        if (!trigger) {
            return;
        }

        trigger.addEventListener('click', () => {
            const isOpen = dropdown.classList.contains('is-open');

            if (isOpen) {
                closeDropdown(dropdown);
            } else {
                openDropdown(dropdown);
            }
        });

        if (hoverCapable) {
            // The menu box starts flush (top: 100%, see navigation.css) so
            // there's no dead zone to cross, and this grace timeout absorbs
            // any residual pointer jitter moving from trigger into menu —
            // together they stop the dropdown closing before it can be used.
            dropdown.addEventListener('mouseenter', () => {
                const pending = closeTimeouts.get(dropdown);

                if (pending) {
                    window.clearTimeout(pending);
                    closeTimeouts.delete(dropdown);
                }

                openDropdown(dropdown);
            });

            dropdown.addEventListener('mouseleave', () => {
                const timeoutId = window.setTimeout(() => {
                    closeDropdown(dropdown);
                    closeTimeouts.delete(dropdown);
                }, 250);

                closeTimeouts.set(dropdown, timeoutId);
            });
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key !== 'Escape') {
            return;
        }

        const openDropdownEl = dropdowns.find((dropdown) => dropdown.classList.contains('is-open'));

        if (!openDropdownEl) {
            return;
        }

        const trigger = openDropdownEl.querySelector('[data-nav-dropdown-trigger]');
        closeDropdown(openDropdownEl);
        trigger?.focus();
    });

    document.addEventListener('click', (event) => {
        dropdowns.forEach((dropdown) => {
            if (dropdown.classList.contains('is-open') && !dropdown.contains(event.target)) {
                closeDropdown(dropdown);
            }
        });
    });

    document.addEventListener('focusout', (event) => {
        dropdowns.forEach((dropdown) => {
            if (!dropdown.classList.contains('is-open')) {
                return;
            }

            const nextFocus = event.relatedTarget;

            if (!nextFocus || !dropdown.contains(nextFocus)) {
                closeDropdown(dropdown);
            }
        });
    });
}

function initStatCountUp() {
    const statNumbers = document.querySelectorAll('.is-home-page .stat-number');

    if (!statNumbers.length) {
        return;
    }

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        return;
    }

    const animate = (element) => {
        const raw = element.textContent.trim();
        const target = Number.parseInt(raw.replace(/[^0-9]/g, ''), 10);

        if (!Number.isFinite(target) || target <= 0) {
            return;
        }

        const duration = 1200;
        const start = performance.now();

        const step = (now) => {
            const progress = Math.min((now - start) / duration, 1);
            const eased = 1 - (1 - progress) ** 3;
            const current = Math.round(target * eased);

            element.textContent = current.toLocaleString('en-US');

            if (progress < 1) {
                window.requestAnimationFrame(step);
            } else {
                element.textContent = raw;
            }
        };

        window.requestAnimationFrame(step);
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) {
                return;
            }

            animate(entry.target);
            observer.unobserve(entry.target);
        });
    }, { threshold: 0.4 });

    statNumbers.forEach((element) => observer.observe(element));
}
