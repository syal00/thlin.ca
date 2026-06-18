document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.getElementById('aiHelpToggle');
    const panel = document.getElementById('aiHelpPanel');
    const close = document.getElementById('aiHelpClose');
    const form = document.getElementById('aiHelpForm');
    const input = document.getElementById('aiHelpInput');
    const chatBox = document.getElementById('aiChatBox');
    const quickButtons = document.querySelectorAll('.ai-quick-actions button');

    function openPanel() {
        panel.classList.add('is-open');
        panel.setAttribute('aria-hidden', 'false');
        input?.focus();
    }

    function closePanel() {
        panel.classList.remove('is-open');
        panel.setAttribute('aria-hidden', 'true');
    }

    function addMessage(text, type = 'bot') {
        if (!chatBox) {
            return;
        }

        const message = document.createElement('div');
        message.className = `ai-message ai-message-${type}`;

        if (type === 'user') {
            message.textContent = text;
        } else {
            message.innerHTML = text;
        }

        chatBox.appendChild(message);
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    function buildLink(href, label) {
        return `<a href="${href}">${label}</a>`;
    }

    function getResponse(question) {
        const q = question.toLowerCase();

        if (q.includes('emergency') || q.includes('urgent') || q.includes('911') || q.includes('symptom') || q.includes('symptoms') || q.includes('pain')) {
            return 'For medical emergencies, call 911 or visit the nearest emergency department. This assistant does not provide medical advice.';
        }

        if (q.includes('patient')) {
            return `You may be looking for Patient Portals. ${buildLink('/products/patient-portals', 'Open Patient Portals')}`;
        }

        if (q.includes('provider')) {
            return `You may be looking for Provider Portals. ${buildLink('/products/provider-portals', 'Open Provider Portals')}`;
        }

        if (q.includes('support') || q.includes('training')) {
            return `You may be looking for Support &amp; Training. ${buildLink('/products/support-training', 'Open Support &amp; Training')}`;
        }

        if (q.includes('contact')) {
            return `You can contact THLIN here: ${buildLink('/contact', 'Contact THLIN')}`;
        }

        if (q.includes('service') || q.includes('find') || q.includes('search')) {
            return `You can search THLIN resources here: ${buildLink('/search', 'Find Services')}`;
        }

        if (q.includes('annual') || q.includes('report')) {
            return `You may be looking for Annual Reports. ${buildLink('/about/annual-reports', 'Open Annual Reports')}`;
        }

        return 'I can help you find THLIN services, portals, contact information, annual reports, or support resources. Try one of the quick options below.';
    }

    toggle?.addEventListener('click', function () {
        if (!panel) {
            return;
        }

        if (panel.classList.contains('is-open')) {
            closePanel();
        } else {
            openPanel();
        }
    });

    close?.addEventListener('click', closePanel);

    quickButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            const question = button.getAttribute('data-question') || button.textContent || '';
            addMessage(question, 'user');
            addMessage(getResponse(question), 'bot');
        });
    });

    form?.addEventListener('submit', function (event) {
        event.preventDefault();

        const question = input?.value.trim() || '';

        if (!question) {
            return;
        }

        addMessage(question, 'user');
        addMessage(getResponse(question), 'bot');

        if (input) {
            input.value = '';
            input.focus();
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closePanel();
        }
    });
});
