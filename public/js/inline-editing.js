document.addEventListener('DOMContentLoaded', () => {
    const editBar = document.querySelector('[data-inline-edit-bar]');
    if (!editBar) {
        return;
    }

    const enableBtn = editBar.querySelector('[data-edit-enable]');
    const disableBtn = editBar.querySelector('[data-edit-disable]');
    const toast = editBar.querySelector('[data-inline-toast]');
    const imageInput = document.querySelector('[data-inline-image-input]');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    const routes = window.inlineEditRoutes || {};

    let editMode = false;
    let activeElement = null;
    let originalValue = '';
    let toolbar = null;
    let pendingImageElement = null;

    const editableSelector = '[data-editable="true"], [data-inline-edit="true"]';
    const imageSelector = '[data-editable-image="true"]';

    enableBtn?.addEventListener('click', () => setEditMode(true));
    disableBtn?.addEventListener('click', () => setEditMode(false));

    document.addEventListener('click', (event) => {
        if (!editMode) {
            return;
        }

        const editable = event.target.closest(editableSelector);
        if (editable) {
            event.preventDefault();
            event.stopPropagation();

            if (editable === activeElement) {
                return;
            }

            startTextEditing(editable);
            return;
        }

        const imageTarget = event.target.closest(imageSelector);
        if (imageTarget) {
            event.preventDefault();
            event.stopPropagation();
            pendingImageElement = imageTarget;
            imageInput.value = '';
            imageInput.click();
            return;
        }

        if (activeElement && toolbar && !toolbar.contains(event.target) && !activeElement.contains(event.target)) {
            cancelEditing();
        }
    }, true);

    document.addEventListener('click', (event) => {
        if (!editMode) {
            return;
        }

        const link = event.target.closest('a');
        if (link && !event.target.closest(editableSelector) && !event.target.closest(imageSelector)) {
            event.preventDefault();
            event.stopPropagation();
        }
    }, true);

    document.addEventListener('keydown', (event) => {
        if (!activeElement) {
            return;
        }

        if (event.key === 'Escape') {
            event.preventDefault();
            cancelEditing();
            return;
        }

        if (event.key === 'Enter' && activeElement.dataset.type !== 'textarea' && !event.shiftKey) {
            event.preventDefault();
            saveEditing();
        }
    });

    imageInput?.addEventListener('change', async () => {
        if (!pendingImageElement || !imageInput.files?.length) {
            return;
        }

        const element = pendingImageElement;
        const formData = new FormData();
        formData.append('model', element.dataset.model);
        formData.append('id', element.dataset.id);
        formData.append('field', element.dataset.field);
        formData.append('image', imageInput.files[0]);
        formData.append('_token', csrfToken);

        try {
            const response = await fetch(routes.uploadImage, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: formData,
            });

            const data = await response.json();
            if (!response.ok || !data.success) {
                throw new Error(data.message || 'Image upload failed.');
            }

            if (element.tagName === 'IMG') {
                element.src = data.url;
            } else {
                element.innerHTML = `<img src="${data.url}" alt="" data-editable-image="true" data-model="${element.dataset.model}" data-id="${element.dataset.id}" data-field="${element.dataset.field}">`;
            }

            showToast('Image updated successfully.', 'success');
        } catch (error) {
            showToast(error.message || 'Image upload failed.', 'error');
        } finally {
            pendingImageElement = null;
            imageInput.value = '';
        }
    });

    function setEditMode(enabled) {
        editMode = enabled;
        document.body.classList.toggle('inline-edit-enabled', enabled);
        document.body.classList.toggle('inline-edit-mode', enabled);

        if (enableBtn) {
            enableBtn.hidden = enabled;
        }

        if (disableBtn) {
            disableBtn.hidden = !enabled;
        }

        if (!enabled) {
            cancelEditing();
            localStorage.removeItem('inlineEditMode');
        }
    }

    function startTextEditing(element) {
        cancelEditing();

        activeElement = element;
        originalValue = isRichField(element) ? element.innerHTML.trim() : element.textContent.trim();
        element.setAttribute('contenteditable', 'true');
        element.classList.add('inline-editing-active');
        element.focus();

        toolbar = document.createElement('div');
        toolbar.className = 'inline-edit-toolbar';
        toolbar.innerHTML = `
            <button type="button" class="save" data-inline-save>Save</button>
            <button type="button" class="cancel" data-inline-cancel>Cancel</button>
        `;

        document.body.appendChild(toolbar);
        positionToolbar(element, toolbar);

        toolbar.querySelector('[data-inline-save]').addEventListener('click', () => saveEditing());
        toolbar.querySelector('[data-inline-cancel]').addEventListener('click', () => cancelEditing());
    }

    async function saveEditing() {
        if (!activeElement) {
            return;
        }

        const value = isRichField(activeElement)
            ? activeElement.innerHTML.trim()
            : activeElement.textContent.trim();

        if (value === '' && originalValue !== '' && !window.confirm('Save empty text? This will remove the current content.')) {
            return;
        }

        try {
            const response = await fetch(routes.update, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    model: activeElement.dataset.model,
                    id: Number(activeElement.dataset.id),
                    field: activeElement.dataset.field,
                    value,
                }),
            });

            const data = await response.json();
            if (!response.ok || !data.success) {
                throw new Error(data.message || 'Save failed.');
            }

            originalValue = value;
            finishEditing();
            showToast('Content saved successfully.', 'success');
        } catch (error) {
            showToast(error.message || 'Save failed.', 'error');
        }
    }

    function cancelEditing() {
        if (!activeElement) {
            return;
        }

        if (isRichField(activeElement)) {
            activeElement.innerHTML = originalValue;
        } else {
            activeElement.textContent = originalValue;
        }

        finishEditing();
    }

    function finishEditing() {
        if (activeElement) {
            activeElement.removeAttribute('contenteditable');
            activeElement.classList.remove('inline-editing-active');
            activeElement = null;
        }

        if (toolbar) {
            toolbar.remove();
            toolbar = null;
        }
    }

    function isRichField(element) {
        return element.dataset.field === 'body' || element.dataset.type === 'textarea';
    }

    function positionToolbar(element, bar) {
        const rect = element.getBoundingClientRect();
        bar.style.position = 'absolute';
        bar.style.top = `${window.scrollY + rect.bottom + 8}px`;
        bar.style.left = `${Math.max(12, window.scrollX + rect.left)}px`;
    }

    function showToast(message, type) {
        if (!toast) {
            return;
        }

        toast.textContent = message;
        toast.className = `inline-edit-toast inline-edit-toast--${type}`;
        toast.hidden = false;

        window.clearTimeout(showToast.timer);
        showToast.timer = window.setTimeout(() => {
            toast.hidden = true;
        }, 3200);
    }

    const urlParams = new URLSearchParams(window.location.search);

    if (urlParams.get('edit') === '1') {
        localStorage.setItem('inlineEditMode', 'enabled');
    }

    if (urlParams.get('edit') === '1' || localStorage.getItem('inlineEditMode') === 'enabled') {
        setEditMode(true);
    }
});
