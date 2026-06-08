document.addEventListener('DOMContentLoaded', () => {
    const editBar = document.querySelector('[data-inline-edit-bar]');
    if (!editBar) {
        return;
    }

    const toggleBtn = editBar.querySelector('[data-edit-toggle]');
    const toast = editBar.querySelector('[data-inline-toast]');
    const imageInput = document.querySelector('[data-inline-image-input]');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    const routes = window.inlineEditRoutes || {};

    let editMode = false;
    let activeElement = null;
    let originalValue = '';
    let toolbar = null;
    let pendingImageElement = null;

    toggleBtn?.addEventListener('click', () => {
        editMode = !editMode;
        toggleBtn.textContent = editMode ? 'Editing ON' : 'Enable Edit Mode';
        toggleBtn.classList.toggle('is-active', editMode);
        document.body.classList.toggle('inline-edit-mode', editMode);

        if (!editMode) {
            cancelEditing();
        }
    });

    document.querySelectorAll('[data-editable="true"]').forEach((element) => {
        element.addEventListener('click', (event) => {
            if (!editMode || element === activeElement) {
                return;
            }

            event.preventDefault();
            event.stopPropagation();
            startTextEditing(element);
        });
    });

    document.querySelectorAll('[data-editable-image="true"]').forEach((element) => {
        element.addEventListener('click', (event) => {
            if (!editMode) {
                return;
            }

            event.preventDefault();
            event.stopPropagation();
            pendingImageElement = element;
            imageInput.value = '';
            imageInput.click();
        });
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
                const img = element.querySelector('img');
                if (img) {
                    bindImageElement(img);
                }
            }

            showToast('Image updated successfully.', 'success');
        } catch (error) {
            showToast(error.message || 'Image upload failed.', 'error');
        } finally {
            pendingImageElement = null;
            imageInput.value = '';
        }
    });

    document.addEventListener('click', (event) => {
        if (!activeElement || !toolbar) {
            return;
        }

        if (toolbar.contains(event.target) || activeElement.contains(event.target)) {
            return;
        }

        cancelEditing();
    });

    function bindImageElement(element) {
        element.addEventListener('click', (event) => {
            if (!editMode) {
                return;
            }

            event.preventDefault();
            event.stopPropagation();
            pendingImageElement = element;
            imageInput.value = '';
            imageInput.click();
        });
    }

    function startTextEditing(element) {
        cancelEditing();

        activeElement = element;
        originalValue = element.dataset.field === 'body' ? element.innerHTML : element.textContent.trim();
        element.setAttribute('contenteditable', 'true');
        element.classList.add('inline-editing-active');
        element.focus();

        toolbar = document.createElement('div');
        toolbar.className = 'inline-edit-toolbar';
        toolbar.innerHTML = `
            <button type="button" class="inline-edit-toolbar__btn inline-edit-toolbar__btn--save" data-inline-save>Save</button>
            <button type="button" class="inline-edit-toolbar__btn inline-edit-toolbar__btn--cancel" data-inline-cancel>Cancel</button>
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

        const value = activeElement.dataset.field === 'body'
            ? activeElement.innerHTML.trim()
            : activeElement.textContent.trim();

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

        if (activeElement.dataset.field === 'body') {
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

    function positionToolbar(element, bar) {
        const rect = element.getBoundingClientRect();
        bar.style.top = `${window.scrollY + rect.bottom + 8}px`;
        bar.style.left = `${window.scrollX + rect.left}px`;
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
        }, 3000);
    }
});
