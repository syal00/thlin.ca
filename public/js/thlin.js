document.addEventListener('DOMContentLoaded', () => {
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
