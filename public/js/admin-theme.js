(function () {
    var storageKey = 'thlin_admin_theme';
    var cookieName = 'thlin_admin_theme';
    var cookieMaxAge = 60 * 60 * 24 * 365;

    function readCookie(name) {
        var match = document.cookie.match(new RegExp('(?:^|;\\s*)' + name + '=([^;]*)'));
        return match ? decodeURIComponent(match[1]) : null;
    }

    function writeCookie(name, value) {
        document.cookie = name + '=' + encodeURIComponent(value) + '; path=/; max-age=' + cookieMaxAge + '; SameSite=Lax';
    }

    function getSavedTheme() {
        try {
            return localStorage.getItem(storageKey) || readCookie(cookieName);
        } catch (error) {
            return readCookie(cookieName);
        }
    }

    function persistTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);

        try {
            localStorage.setItem(storageKey, theme);
        } catch (error) {
            // Ignore storage failures; cookie remains the fallback.
        }

        writeCookie(cookieName, theme);
    }

    function getSystemTheme() {
        return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    }

    function getCurrentTheme() {
        return document.documentElement.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
    }

    function updateToggleButton(button, theme) {
        if (!button) {
            return;
        }

        var isDark = theme === 'dark';

        button.setAttribute('aria-pressed', isDark ? 'true' : 'false');
        button.setAttribute('aria-label', isDark ? 'Switch to light mode' : 'Switch to dark mode');
        button.setAttribute('title', isDark ? 'Switch to light mode' : 'Switch to dark mode');

        var sunIcon = button.querySelector('[data-theme-icon="light"]');
        var moonIcon = button.querySelector('[data-theme-icon="dark"]');

        if (sunIcon) {
            sunIcon.hidden = isDark;
        }

        if (moonIcon) {
            moonIcon.hidden = !isDark;
        }
    }

    var toggle = document.querySelector('[data-admin-theme-toggle]');
    var initialTheme = getCurrentTheme();

    updateToggleButton(toggle, initialTheme);

    toggle?.addEventListener('click', function () {
        var nextTheme = getCurrentTheme() === 'dark' ? 'light' : 'dark';
        persistTheme(nextTheme);
        updateToggleButton(toggle, nextTheme);
    });
})();
