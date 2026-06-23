(function () {
    const shell = document.querySelector('.admin-shell');
    const toggle = document.querySelector('[data-admin-nav-toggle]');
    const backdrop = document.querySelector('[data-admin-nav-backdrop]');

    if (!shell || !toggle) {
        return;
    }

    const closeNav = () => shell.classList.remove('admin-shell--nav-open');

    toggle.addEventListener('click', () => {
        shell.classList.toggle('admin-shell--nav-open');
    });

    backdrop?.addEventListener('click', closeNav);

    shell.querySelectorAll('.admin-nav-link').forEach((link) => {
        link.addEventListener('click', () => {
            if (window.matchMedia('(max-width: 980px)').matches) {
                closeNav();
            }
        });
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeNav();
        }
    });
})();
