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
            nav.querySelectorAll('.t-nav-dropdown.is-open, .nav-dropdown.is-open').forEach((dropdown) => {
                dropdown.classList.remove('is-open');
            });
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

        // Close the mobile menu when a submenu or plain link is chosen —
        // not when tapping a section heading that expands inline.
        nav.querySelectorAll('a').forEach((link) => {
            link.addEventListener('click', (event) => {
                if (!mq.matches) {
                    return;
                }

                const dropdown = link.closest('.t-nav-dropdown, .nav-dropdown');
                const menu = dropdown?.querySelector(':scope > .t-nav-dropdown-menu, :scope > .nav-dropdown-menu');

                if (menu && link === dropdown?.querySelector(':scope > a.t-nav-link, :scope > a.nav-link, :scope > button.t-nav-link, :scope > button.nav-link')) {
                    return;
                }

                closeNav();
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
    const dropdowns = Array.from(document.querySelectorAll('.t-nav-dropdown, .nav-dropdown'));
    const mobileMq = window.matchMedia('(max-width: 767px)');

    if (!dropdowns.length) {
        return;
    }

    function menuFor(dropdown) {
        return dropdown.querySelector(':scope > .t-nav-dropdown-menu, :scope > .nav-dropdown-menu');
    }

    function triggerFor(dropdown) {
        return dropdown.querySelector('[data-nav-dropdown-trigger]')
            || dropdown.querySelector(':scope > a.t-nav-link, :scope > a.nav-link, :scope > button.t-nav-link, :scope > button.nav-link');
    }

    function closeDropdown(dropdown) {
        const trigger = triggerFor(dropdown);
        dropdown.classList.remove('is-open');
        trigger?.setAttribute('aria-expanded', 'false');
    }

    function closeAllDropdowns() {
        dropdowns.forEach((dropdown) => closeDropdown(dropdown));
    }

    function openDropdown(dropdown) {
        dropdowns.forEach((item) => {
            if (item !== dropdown) {
                closeDropdown(item);
            }
        });

        const trigger = triggerFor(dropdown);
        dropdown.classList.add('is-open');
        trigger?.setAttribute('aria-expanded', 'true');
    }

    function toggleDropdown(dropdown) {
        const isOpen = dropdown.classList.contains('is-open');

        dropdowns.forEach((item) => {
            if (item !== dropdown) {
                closeDropdown(item);
            }
        });

        if (isOpen) {
            closeDropdown(dropdown);
        } else {
            openDropdown(dropdown);
        }
    }

    dropdowns.forEach((dropdown) => {
        const trigger = triggerFor(dropdown);
        const menu = menuFor(dropdown);
        let desktopLeaveTimer = null;

        if (!trigger || !menu) {
            return;
        }

        if (!trigger.hasAttribute('aria-haspopup')) {
            trigger.setAttribute('aria-haspopup', 'true');
        }

        if (!trigger.hasAttribute('aria-expanded')) {
            trigger.setAttribute('aria-expanded', 'false');
        }

        trigger.addEventListener('click', (event) => {
            if (!mobileMq.matches) {
                return;
            }

            event.preventDefault();
            toggleDropdown(dropdown);
        });

        dropdown.addEventListener('mouseenter', () => {
            if (mobileMq.matches) {
                return;
            }

            if (desktopLeaveTimer) {
                clearTimeout(desktopLeaveTimer);
                desktopLeaveTimer = null;
            }

            openDropdown(dropdown);
        });

        dropdown.addEventListener('mouseleave', () => {
            if (mobileMq.matches) {
                return;
            }

            desktopLeaveTimer = window.setTimeout(() => {
                closeDropdown(dropdown);
                desktopLeaveTimer = null;
            }, 120);
        });

        trigger.addEventListener('focus', () => {
            if (mobileMq.matches) {
                return;
            }

            openDropdown(dropdown);
        });
    });

    mobileMq.addEventListener('change', () => {
        closeAllDropdowns();
    });

    document.addEventListener('keydown', (event) => {
        if (event.key !== 'Escape') {
            return;
        }

        closeAllDropdowns();
    });

    document.addEventListener('click', (event) => {
        if (mobileMq.matches) {
            dropdowns.forEach((dropdown) => {
                if (dropdown.classList.contains('is-open') && !dropdown.contains(event.target)) {
                    closeDropdown(dropdown);
                }
            });
            return;
        }

        const clickedInsideNav = event.target.closest('.t-nav-dropdown, .nav-dropdown');

        if (!clickedInsideNav) {
            closeAllDropdowns();
        }
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
