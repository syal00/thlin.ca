(function () {
    var storageKey = 'thlin_admin_inline_help_seen';
    var dialog = document.getElementById('admin-inline-help-dialog');
    var pendingUrl = null;

    if (!dialog) {
        return;
    }

    function hasSeenHelp() {
        try {
            return localStorage.getItem(storageKey) === '1';
        } catch (error) {
            return false;
        }
    }

    function markHelpSeen() {
        try {
            localStorage.setItem(storageKey, '1');
        } catch (error) {
            // Ignore storage failures.
        }
    }

    function openEditor(url) {
        window.open(url, '_blank', 'noopener');
    }

    function openDialog(url) {
        pendingUrl = url;
        dialog.showModal();
    }

    function closeDialog() {
        pendingUrl = null;
        dialog.close();
    }

    document.querySelectorAll('[data-admin-open-editor]').forEach(function (link) {
        link.addEventListener('click', function (event) {
            if (hasSeenHelp()) {
                return;
            }

            event.preventDefault();
            openDialog(link.href);
        });
    });

    dialog.querySelector('[data-admin-inline-help-open]')?.addEventListener('click', function () {
        markHelpSeen();
        var url = pendingUrl;
        closeDialog();

        if (url) {
            openEditor(url);
        }
    });

    dialog.querySelector('[data-admin-inline-help-dismiss]')?.addEventListener('click', function () {
        markHelpSeen();
        closeDialog();
    });

    dialog.querySelector('[data-admin-inline-help-close]')?.addEventListener('click', function () {
        closeDialog();
    });

    dialog.addEventListener('cancel', function () {
        pendingUrl = null;
    });

    var helpCard = document.querySelector('[data-admin-inline-help-card]');
    var helpCardDismiss = document.querySelector('[data-admin-inline-help-card-dismiss]');
    var helpCardStorageKey = 'thlin_admin_inline_help_card_hidden';

    function hideHelpCard() {
        if (!helpCard) {
            return;
        }

        helpCard.hidden = true;

        try {
            localStorage.setItem(helpCardStorageKey, '1');
        } catch (error) {
            // Ignore storage failures.
        }
    }

    try {
        if (localStorage.getItem(helpCardStorageKey) === '1' && helpCard) {
            helpCard.hidden = true;
        }
    } catch (error) {
        // Ignore storage failures.
    }

    helpCardDismiss?.addEventListener('click', hideHelpCard);
})();
