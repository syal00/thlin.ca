document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.getElementById('helpWidgetToggle');
    const panel = document.getElementById('helpWidgetPanel');
    const close = document.getElementById('helpWidgetClose');

    function openWidget() {
        panel.classList.add('is-open');
        panel.setAttribute('aria-hidden', 'false');
    }

    function closeWidget() {
        panel.classList.remove('is-open');
        panel.setAttribute('aria-hidden', 'true');
    }

    toggle?.addEventListener('click', function () {
        if (panel.classList.contains('is-open')) {
            closeWidget();
        } else {
            openWidget();
        }
    });

    close?.addEventListener('click', closeWidget);

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeWidget();
        }
    });
});
