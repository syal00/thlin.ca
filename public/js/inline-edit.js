document.addEventListener('DOMContentLoaded', function () {
    const adminBar = document.querySelector('[data-inline-edit-bar]');
    const enableButton = document.getElementById('enable-inline-editing');
    const disableButton = document.getElementById('disable-inline-editing');
    const statusMessage = document.querySelector('[data-inline-edit-status]');
    const existingToast = document.querySelector('[data-inline-toast]');
    const imageInput = document.querySelector('[data-inline-image-input]');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const routes = window.inlineEditRoutes || {};
    const updateUrl = routes.update || '/admin/inline-update';
    const tinymceConfig = window.inlineEditTinyMce || { baseUrl: '/vendor/tinymce' };

    const defaultStatusMessage = 'You are viewing the website as an administrator.';
    const enabledStatusMessage = 'Inline editing is enabled. Click highlighted content to edit.';
    const MOBILE_TOOLBAR_BREAKPOINT = 768;

    const editableSelector = '[data-inline-edit="true"], [data-editable="true"]';
    const imageSelector = '[data-editable-image="true"]';
    const safeControlSelector = [
        '[data-inline-edit-bar]',
        '.inline-edit-toolbar',
        '[data-inline-edit-safe]',
        '.tox',
        '.tox-tinymce-inline',
        '.tox-tinymce-aux',
        '.tox-toolbar-overlord',
    ].join(', ');

    let editMode = false;
    let activeElement = null;
    let originalValue = '';
    let toolbar = null;
    let pendingImageElement = null;
    let activeTinyMceEditor = null;
    let inlineEditDirty = false;

    function isSafeControl(target) {
        return Boolean(target.closest(safeControlSelector));
    }

    function isRichField(el) {
        const type = (el.dataset.type || 'text').toLowerCase();
        return type === 'richtext' || type === 'textarea';
    }

    function markDirty() {
        inlineEditDirty = true;
    }

    function clearDirty() {
        inlineEditDirty = false;
    }

    function positionInlineToolbar(toolbarEl, element) {
        if (!toolbarEl || !element) return;

        if (window.innerWidth <= MOBILE_TOOLBAR_BREAKPOINT) {
            toolbarEl.style.position = 'fixed';
            toolbarEl.style.top = 'auto';
            toolbarEl.style.bottom = '14px';
            toolbarEl.style.left = '12px';
            toolbarEl.style.right = '12px';
            toolbarEl.style.zIndex = '999999';
            return;
        }

        const rect = element.getBoundingClientRect();
        const toolbarRect = toolbarEl.getBoundingClientRect();
        const gap = 14;
        const safeTop = 72;
        const safePadding = 16;

        let top = rect.top - toolbarRect.height - gap;
        let left = rect.left;

        if (top < safeTop) {
            top = rect.bottom + gap;
        }

        if (left + toolbarRect.width > window.innerWidth - safePadding) {
            left = window.innerWidth - toolbarRect.width - safePadding;
        }

        if (left < safePadding) {
            left = safePadding;
        }

        toolbarEl.style.position = 'fixed';
        toolbarEl.style.top = `${top}px`;
        toolbarEl.style.left = `${left}px`;
        toolbarEl.style.right = 'auto';
        toolbarEl.style.bottom = 'auto';
        toolbarEl.style.zIndex = '999999';
    }

    function repositionActiveToolbar() {
        if (toolbar && activeElement && !activeTinyMceEditor) {
            positionInlineToolbar(toolbar, activeElement);
        }

        if (activeTinyMceEditor && activeElement) {
            const toxToolbar = document.querySelector('.tox-tinymce-inline');
            if (toxToolbar) {
                positionInlineToolbar(toxToolbar, activeElement);
            }
        }
    }

    function getEditableValue(el) {
        return isRichField(el) ? el.innerHTML.trim() : el.innerText.trim();
    }

    function setEditableValue(el, val) {
        if (isRichField(el)) {
            el.innerHTML = normalizeRichHtmlForElement(el, val);
        } else {
            el.innerText = val;
        }
    }

    function normalizeRichHtmlForElement(element, html) {
        let value = (html || '').trim();
        const tag = element.tagName.toLowerCase();
        const inlineOnlyTags = ['span', 'strong', 'a', 'h1', 'h2', 'h3', 'h4'];

        if (inlineOnlyTags.includes(tag) || tag === 'p') {
            value = value.replace(/^<p>([\s\S]*)<\/p>$/i, '$1').trim();
        }

        return value;
    }

    function getTinyMceRootBlock(element) {
        const tag = element.tagName.toLowerCase();
        if (['p', 'h1', 'h2', 'h3', 'h4', 'strong', 'span', 'a'].includes(tag)) {
            return false;
        }

        return 'p';
    }

    function showInlineToast(message, type) {
        type = type || 'success';

        if (existingToast) {
            existingToast.textContent = message;
            existingToast.className = 'inline-edit-toast ' + type;
            existingToast.hidden = false;
            clearTimeout(showInlineToast.timer);
            showInlineToast.timer = setTimeout(function () { existingToast.hidden = true; }, 2800);
            return;
        }

        const toast = document.createElement('div');
        toast.className = 'inline-edit-toast ' + type;
        toast.textContent = message;
        toast.setAttribute('role', 'status');
        toast.setAttribute('aria-live', 'polite');
        document.body.appendChild(toast);

        setTimeout(function () {
            toast.remove();
        }, 2800);
    }

    function toggleButtonVisibility(enabled) {
        if (enableButton) {
            enableButton.hidden = enabled;
            enableButton.setAttribute('aria-pressed', enabled ? 'true' : 'false');
        }
        if (disableButton) {
            disableButton.hidden = !enabled;
            disableButton.setAttribute('aria-pressed', enabled ? 'true' : 'false');
        }
        adminBar?.classList.toggle('is-editing', enabled);
    }

    function enableInlineEditing() {
        editMode = true;
        document.body.classList.add('inline-edit-enabled', 'inline-edit-mode');
        localStorage.setItem('inlineEditMode', 'enabled');
        toggleButtonVisibility(true);
        if (statusMessage) statusMessage.textContent = enabledStatusMessage;
    }

    function disableInlineEditing() {
        editMode = false;
        document.body.classList.remove('inline-edit-enabled', 'inline-edit-mode');
        localStorage.setItem('inlineEditMode', 'disabled');
        toggleButtonVisibility(false);
        cancelEditing();
        clearDirty();
        if (statusMessage) statusMessage.textContent = defaultStatusMessage;
    }

    enableButton?.addEventListener('click', function (e) { e.preventDefault(); enableInlineEditing(); });
    disableButton?.addEventListener('click', function (e) { e.preventDefault(); disableInlineEditing(); });

    document.addEventListener('click', function (event) {
        if (!document.body.classList.contains('inline-edit-enabled')) return;

        const editable = event.target.closest(editableSelector);
        if (editable) {
            if (editable.closest('a.home-quick-card')) {
                return;
            }

            event.preventDefault();
            event.stopPropagation();
            if (editMode && editable !== activeElement) startInlineEdit(editable);
            return;
        }
    }, true);

    document.addEventListener('click', function (event) {
        if (!editMode || isSafeControl(event.target)) return;

        const editable = event.target.closest(editableSelector);
        if (editable) return;

        const imageTarget = event.target.closest(imageSelector);
        if (imageTarget && imageInput) {
            event.preventDefault();
            event.stopPropagation();
            pendingImageElement = imageTarget;
            imageInput.value = '';
            imageInput.click();
            return;
        }

        if (activeElement && !activeTinyMceEditor && toolbar && !toolbar.contains(event.target) && !activeElement.contains(event.target)) {
            cancelEditing();
        }
    }, true);

    document.addEventListener('click', function (event) {
        if (!editMode || isSafeControl(event.target)) return;
        if (event.target.closest(editableSelector) || event.target.closest(imageSelector)) return;

        const cardLink = event.target.closest('a.home-quick-card');
        if (cardLink) {
            return;
        }

        const interactive = event.target.closest('a, button, input[type="submit"], summary');
        if (interactive) { event.preventDefault(); event.stopPropagation(); }
    }, true);

    document.addEventListener('keydown', function (event) {
        const modKey = event.ctrlKey || event.metaKey;

        if (modKey && event.key.toLowerCase() === 's') {
            if (activeElement) {
                event.preventDefault();
                if (activeTinyMceEditor) {
                    saveTinyMceInlineEdit(activeElement, activeTinyMceEditor);
                } else {
                    saveInlineEdit(activeElement);
                }
            }
            return;
        }

        if (event.key === 'Escape') {
            if (activeElement) { event.preventDefault(); cancelEditing(); return; }
            if (editMode) { event.preventDefault(); disableInlineEditing(); }
            return;
        }

        if (!activeTinyMceEditor) {
            if (!activeElement) return;
            if (event.key === 'Enter' && !isRichField(activeElement) && !event.shiftKey) {
                event.preventDefault();
                saveInlineEdit(activeElement);
            }
            return;
        }

        if (modKey && ['b', 'i', 'u'].includes(event.key.toLowerCase())) {
            event.preventDefault();
            const cmd = { b: 'Bold', i: 'Italic', u: 'Underline' }[event.key.toLowerCase()];
            activeTinyMceEditor.execCommand(cmd);
        }
    });

    window.addEventListener('scroll', repositionActiveToolbar, true);
    window.addEventListener('resize', repositionActiveToolbar);

    window.addEventListener('beforeunload', function (e) {
        if (inlineEditDirty) {
            e.preventDefault();
            e.returnValue = '';
        }
    });

    imageInput?.addEventListener('change', async function () {
        if (!pendingImageElement || !imageInput.files?.length || !routes.uploadImage) return;
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
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                body: formData,
            });
            const data = await response.json();
            if (!response.ok || !data.success) throw new Error(data.message || 'Image upload failed.');
            if (element.tagName === 'IMG') {
                element.src = data.url;
            } else {
                element.innerHTML = '<img src="' + data.url + '" alt="" data-editable-image="true" data-model="' + element.dataset.model + '" data-id="' + element.dataset.id + '" data-field="' + element.dataset.field + '">';
            }
            showInlineToast('Image updated successfully');
        } catch (error) {
            showInlineToast(error.message || 'Image upload failed.', 'error');
        } finally {
            pendingImageElement = null;
            imageInput.value = '';
        }
    });

    function startInlineEdit(element) {
        cancelEditing();
        activeElement = element;
        originalValue = getEditableValue(element);
        element.dataset.originalValue = originalValue;
        element.dataset.editing = 'true';
        element.classList.add('inline-editing-active', 'is-inline-editing');
        clearDirty();

        if (isRichField(element)) {
            startTinyMceInlineEdit(element);
            return;
        }

        element.setAttribute('contenteditable', 'true');
        element.addEventListener('input', markDirty);
        element.focus();
        const range = document.createRange();
        range.selectNodeContents(element);
        range.collapse(false);
        const sel = window.getSelection();
        sel.removeAllRanges();
        sel.addRange(range);
        showInlineToolbar(element);
    }

    function startTinyMceInlineEdit(element) {
        if (typeof tinymce === 'undefined') {
            window.alert('Rich text editor is not loaded. Please refresh the page.');
            finishEditingCleanup(element);
            return;
        }

        if (!element.id) {
            element.id = 'inline-edit-' + Date.now();
        }

        tinymce.init({
            target: element,
            inline: true,
            menubar: false,
            branding: false,
            promotion: false,
            license_key: 'gpl',
            base_url: tinymceConfig.baseUrl,
            suffix: '.min',
            toolbar_mode: 'sliding',
            fixed_toolbar_container: false,
            plugins: 'link lists table code autoresize wordcount charmap searchreplace visualblocks fullscreen',
            toolbar: 'undo redo | blocks fontsize | bold italic underline strikethrough | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link table charmap | removeformat code fullscreen | saveinline cancelinline',
            fontsize_formats: '12px 14px 16px 18px 20px 24px 28px 32px',
            block_formats: 'Paragraph=p; Heading 2=h2; Heading 3=h3; Heading 4=h4',
            link_default_target: '_blank',
            forced_root_block: getTinyMceRootBlock(element),
            valid_elements: 'p[style],br,strong/b,em/i,u,s,strike,del,span[style],h2[style],h3[style],h4[style],ul,ol,li,a[href|target|rel],table,thead,tbody,tr,th[style],td[style]',
            extended_valid_elements: 'span[style],p[style],h2[style],h3[style],h4[style],th[style],td[style]',
            setup: function (editor) {
                editor.ui.registry.addButton('saveinline', {
                    text: 'Save',
                    onAction: function () {
                        saveTinyMceInlineEdit(element, editor);
                    },
                });

                editor.ui.registry.addButton('cancelinline', {
                    text: 'Cancel',
                    onAction: function () {
                        cancelTinyMceInlineEdit(element, editor);
                    },
                });

                function positionTinyToolbar() {
                    const toxToolbar = document.querySelector('.tox-tinymce-inline');
                    if (toxToolbar) {
                        positionInlineToolbar(toxToolbar, element);
                    }
                }

                editor.on('init focus', function () {
                    activeTinyMceEditor = editor;
                    element.classList.add('is-inline-editing');
                    editor.focus();
                    setTimeout(positionTinyToolbar, 80);
                });

                editor.on('NodeChange keyup click change', function () {
                    markDirty();
                    positionTinyToolbar();
                });
            },
        });
    }

    function setTinySaveState(editor, saving) {
        const container = editor.getContainer();
        const saveBtn = container?.querySelector('[aria-label="Save"], [title="Save"]');
        if (saveBtn) {
            saveBtn.disabled = saving;
            saveBtn.setAttribute('aria-disabled', saving ? 'true' : 'false');
        }
    }

    function saveTinyMceInlineEdit(element, editor) {
        const value = normalizeRichHtmlForElement(element, editor.getContent());

        if (value === '' && originalValue !== '' && !window.confirm('Save empty text? This will remove the current content.')) {
            return;
        }

        setTinySaveState(editor, true);

        persistInlineValue(element, value, 'richtext')
            .then(function () {
                editor.save();
                editor.remove();
                activeTinyMceEditor = null;
                element.innerHTML = value;
                finishEditingCleanup(element);
                clearDirty();
                showInlineToast('Saved successfully');
            })
            .catch(function (error) {
                console.error(error);
                showInlineToast(error.message || 'Could not save changes', 'error');
            })
            .finally(function () {
                setTinySaveState(editor, false);
            });
    }

    function cancelTinyMceInlineEdit(element, editor) {
        element.innerHTML = element.dataset.originalValue || originalValue;
        editor.remove();
        activeTinyMceEditor = null;
        finishEditingCleanup(element);
        clearDirty();
    }

    function showInlineToolbar(element) {
        removeInlineToolbar();
        toolbar = document.createElement('div');
        toolbar.className = 'inline-edit-toolbar';
        toolbar.setAttribute('role', 'toolbar');
        toolbar.setAttribute('aria-label', 'Simple text editing');
        toolbar.innerHTML = '<button type="button" class="save">Save</button><button type="button" class="cancel">Cancel</button>';
        document.body.appendChild(toolbar);
        requestAnimationFrame(function () {
            positionInlineToolbar(toolbar, element);
        });
        toolbar.querySelector('.save').addEventListener('click', function (e) { e.preventDefault(); e.stopPropagation(); saveInlineEdit(element); });
        toolbar.querySelector('.cancel').addEventListener('click', function (e) { e.preventDefault(); e.stopPropagation(); cancelEditing(); });
    }

    function removeInlineToolbar() {
        toolbar?.remove();
        toolbar = null;
    }

    function finishEditingCleanup(element) {
        element.removeEventListener('input', markDirty);
        element.removeAttribute('contenteditable');
        element.dataset.editing = 'false';
        element.classList.remove('inline-editing-active', 'is-inline-editing');
        delete element.dataset.originalValue;
        if (activeElement === element) activeElement = null;
        removeInlineToolbar();
    }

    function cancelEditing() {
        if (activeTinyMceEditor && activeElement) {
            cancelTinyMceInlineEdit(activeElement, activeTinyMceEditor);
            return;
        }

        if (!activeElement) {
            removeInlineToolbar();
            return;
        }

        setEditableValue(activeElement, originalValue);
        finishEditingCleanup(activeElement);
        clearDirty();
    }

    async function saveInlineEdit(element) {
        const target = element || activeElement;
        if (!target || activeTinyMceEditor) return;

        const saveBtn = toolbar?.querySelector('.save');
        if (saveBtn) { saveBtn.disabled = true; saveBtn.textContent = 'Saving...'; }

        const value = getEditableValue(target);
        if (value === '' && originalValue !== '' && !window.confirm('Save empty text? This will remove the current content.')) {
            if (saveBtn) { saveBtn.disabled = false; saveBtn.textContent = 'Save'; }
            return;
        }

        try {
            await persistInlineValue(target, value, 'text');
            originalValue = value;
            finishEditingCleanup(target);
            clearDirty();
            showInlineToast('Saved successfully');
        } catch (error) {
            showInlineToast(error.message || 'Could not save changes', 'error');
        } finally {
            if (saveBtn) { saveBtn.disabled = false; saveBtn.textContent = 'Save'; }
        }
    }

    function persistInlineValue(target, value, type) {
        const model = target.dataset.model || 'page';
        const field = target.dataset.field;
        const key = target.dataset.key;
        const id = target.dataset.id;

        if (!field || (!key && !id)) {
            return Promise.reject(new Error('This text cannot be saved because it is missing CMS information.'));
        }

        const payload = { model, field, value, type };
        if (key) payload.key = key;
        else payload.id = Number(id);

        return fetch(updateUrl, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify(payload),
        })
            .then(function (response) {
                return response.json().then(function (data) {
                    if (!response.ok || !data.success) {
                        throw new Error(data.message || 'Save failed.');
                    }
                    return data;
                });
            });
    }

    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('preview') === '1' || urlParams.get('edit') === '0') {
        localStorage.setItem('inlineEditMode', 'disabled');
        toggleButtonVisibility(false);
    } else if (urlParams.get('edit') === '1' || localStorage.getItem('inlineEditMode') === 'enabled') {
        enableInlineEditing();
    } else {
        toggleButtonVisibility(false);
    }
});
