document.addEventListener('click', function (event) {
    const button = event.target.closest('.copy-link-btn');

    if (!button) {
        return;
    }

    const link = button.getAttribute('data-copy');

    if (!link || !navigator.clipboard || !navigator.clipboard.writeText) {
        return;
    }

    navigator.clipboard.writeText(link).then(function () {
        const originalText = button.textContent;
        button.textContent = 'Copied!';

        setTimeout(function () {
            button.textContent = originalText;
        }, 1600);
    });
});