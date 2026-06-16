document.addEventListener('DOMContentLoaded', function () {
    const enableButton = document.getElementById('enable-inline-editing');
    const disableButton = document.getElementById('disable-inline-editing');
    const toast = document.querySelector('[data-inline-toast]');
    const imageInput = document.querySelector('[data-inline-image-input]');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const routes = window.inlineEditRoutes || {};
    const updateUrl = routes.update || '/admin/inline-update';

    let editMode = false;
    let activeElement = null;
    let originalValue = '';
    let toolbar = null;
    let pendingImageElement = null;

    const editableSelector = '[data-inline-edit="true"], [data-editable="true"]';
    const imageSelector = '[data-editable-image="true"]';

    function toggleButtonVisibility(enabled) {
        if (enableButton) {
            enableButton.hidden = enabled;
            enableButton.style.display = enabled ? 'none' : 'inline-flex';
        }

        if (disableButton) {
            disableButton.hidden = !enabled;
            disableButton.style.display = enabled ? 'inline-flex' : 'none';
        }
    }

    function enableInlineEditing() {
        editMode = true;
        document.body.classList.add('inline-edit-enabled');
        document.body.classList.add('inline-edit-mode');
        localStorage.setItem('inlineEditMode', 'enabled');
        toggleButtonVisibility(true);
    }

    function disableInlineEditing() {
        editMode = false;
        document.body.classList.remove('inline-edit-enabled');
        document.body.classList.remove('inline-edit-mode');
        localStorage.setItem('inlineEditMode', 'disabled');
        toggleButtonVisibility(false);
        cancelEditing();
    }

    if (enableButton) {
        enableButton.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();
            enableInlineEditing();
        });
    }

    if (disableButton) {
        disableButton.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();
            disableInlineEditing();
        });
    }

    document.addEventListener('click', function (event) {
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

            startInlineEdit(editable);
            return;
        }

        const imageTarget = event.target.closest(imageSelector);
        if (imageTarget && imageInput) {
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

    document.addEventListener('click', function (event) {
        if (!editMode) {
            return;
        }

        const link = event.target.closest('a');
        if (link && !event.target.closest(editableSelector) && !event.target.closest(imageSelector)) {
            event.preventDefault();
            event.stopPropagation();
        }
    }, true);

    document.addEventListener('keydown', function (event) {
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
            saveInlineEdit(activeElement);
        }
    });

    imageInput?.addEventListener('change', async function () {
        if (!pendingImageElement || !imageInput.files?.length || !routes.uploadImage) {
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
                element.innerHTML = '<img src="' + data.url + '" alt="" data-editable-image="true" data-model="' + element.dataset.model + '" data-id="' + element.dataset.id + '" data-field="' + element.dataset.field + '">';
            }

            showToast('Image updated successfully.', 'success');
        } catch (error) {
            showToast(error.message || 'Image upload failed.', 'error');
        } finally {
            pendingImageElement = null;
            imageInput.value = '';
        }
    });

    function startInlineEdit(element) {
        cancelEditing();

        activeElement = element;
        originalValue = isRichField(element) ? element.innerHTML.trim() : element.innerText.trim();
        element.dataset.editing = 'true';
        element.setAttribute('contenteditable', 'true');
        element.classList.add('inline-editing-active');
        element.focus();

        const range = document.createRange();
        range.selectNodeContents(element);
        range.collapse(false);
        const selection = window.getSelection();
        selection.removeAllRanges();
        selection.addRange(range);

        showInlineToolbar(element);
    }

    function showInlineToolbar(element) {
        removeInlineToolbar();

        toolbar = document.createElement('div');
        toolbar.className = 'inline-edit-toolbar';
        toolbar.innerHTML = '<button type="button" class="save">Save</button><button type="button" class="cancel">Cancel</button>';
        document.body.appendChild(toolbar);

        const rect = element.getBoundingClientRect();
        toolbar.style.position = 'absolute';
        toolbar.style.top = (window.scrollY + rect.bottom + 8) + 'px';
        toolbar.style.left = Math.max(12, window.scrollX + rect.left) + 'px';

        toolbar.querySelector('.save').addEventListener('click', function () {
            saveInlineEdit(element);
        });

        toolbar.querySelector('.cancel').addEventListener('click', function () {
            cancelEditing();
        });
    }

    function removeInlineToolbar() {
        if (toolbar) {
            toolbar.remove();
            toolbar = null;
        }
    }

    function cancelEditing() {
        if (!activeElement) {
            removeInlineToolbar();
            return;
        }

        if (isRichField(activeElement)) {
            activeElement.innerHTML = originalValue;
        } else {
            activeElement.innerText = originalValue;
        }

        activeElement.removeAttribute('contenteditable');
        activeElement.dataset.editing = 'false';
        activeElement.classList.remove('inline-editing-active');
        activeElement = null;
        removeInlineToolbar();
    }

    async function saveInlineEdit(element) {
        const target = element || activeElement;

        if (!target) {
            return;
        }

        const value = isRichField(target) ? target.innerHTML.trim() : target.innerText.trim();

        if (value === '' && originalValue !== '' && !window.confirm('Save empty text? This will remove the current content.')) {
            return;
        }

        const model = target.dataset.model || 'page';
        const id = target.dataset.id;
        const field = target.dataset.field;

        if (!id || !field) {
            window.alert('This text cannot be saved because it is missing CMS information.');
            return;
        }

        try {
            const response = await fetch(updateUrl, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    model: model,
                    id: Number(id),
                    field: field,
                    value: value,
                }),
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                throw new Error(data.message || 'Save failed.');
            }

            originalValue = value;
            target.removeAttribute('contenteditable');
            target.dataset.editing = 'false';
            target.classList.remove('inline-editing-active');
            activeElement = null;
            removeInlineToolbar();
            showToast('Content saved successfully.', 'success');
        } catch (error) {
            showToast(error.message || 'Could not save this change. Please try again or edit it from the CMS.', 'error');
        }
    }

    function isRichField(element) {
        return element.dataset.field === 'body' || element.dataset.type === 'textarea';
    }

    function showToast(message, type) {
        if (!toast) {
            return;
        }

        toast.textContent = message;
        toast.className = 'inline-edit-toast inline-edit-toast--' + type;
        toast.hidden = false;

        window.clearTimeout(showToast.timer);
        showToast.timer = window.setTimeout(function () {
            toast.hidden = true;
        }, 3200);
    }

    const urlParams = new URLSearchParams(window.location.search);

    if (urlParams.get('edit') === '1' || localStorage.getItem('inlineEditMode') === 'enabled') {
        enableInlineEditing();
    } else {
        toggleButtonVisibility(false);
    }
});
