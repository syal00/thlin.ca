{{--
    Shared "Edit image" tool for TinyMCE fields.

    TinyMCE's open-source build no longer ships crop/rotate tooling (that
    moved to a paid plugin), so this wires up Cropper.js as a lightweight
    replacement: any <img> inserted (via paste, drag-drop, or the toolbar
    upload dialog) can be re-opened for cropping, rotating, flipping, and
    resizing. The result is uploaded through the same
    `images_upload_handler` the editor already uses, so no extra server
    endpoint is needed.

    Include this once per page, then call `ThlinImageEditor.attach(editor)`
    from a TinyMCE `setup` callback.
--}}
@once
<link rel="stylesheet" href="{{ asset('vendor/cropperjs/cropper.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/image-editor.css') }}?v={{ @filemtime(public_path('css/image-editor.css')) ?: '1' }}">
<script src="{{ asset('vendor/cropperjs/cropper.min.js') }}"></script>
<script>
window.ThlinImageEditor = (function () {
    var modal, cropper, activeEditor, activeImg, sourceImg, statusEl;
    var lockAspect = true;

    function el(selector) {
        return modal.querySelector(selector);
    }

    function setStatus(text, isError) {
        statusEl.textContent = text || '';
        statusEl.classList.toggle('is-error', Boolean(isError));
    }

    function ensureModal() {
        if (modal) {
            return;
        }

        modal = document.createElement('div');
        modal.className = 'thlin-image-editor-overlay';
        modal.setAttribute('role', 'dialog');
        modal.setAttribute('aria-modal', 'true');
        modal.setAttribute('aria-label', 'Edit image');
        modal.innerHTML =
            '<div class="thlin-image-editor">' +
                '<div class="thlin-image-editor-header">' +
                    '<h3>Edit image</h3>' +
                    '<button type="button" class="thlin-image-editor-close" data-action="cancel" aria-label="Close">&times;</button>' +
                '</div>' +
                '<div class="thlin-image-editor-body">' +
                    '<div class="thlin-image-editor-canvas-wrap">' +
                        '<img class="thlin-image-editor-source" alt="">' +
                    '</div>' +
                    '<div class="thlin-image-editor-controls">' +
                        '<div class="thlin-image-editor-group">' +
                            '<button type="button" data-action="rotate-left" title="Rotate left">&#8634;</button>' +
                            '<button type="button" data-action="rotate-right" title="Rotate right">&#8635;</button>' +
                            '<button type="button" data-action="flip-h" title="Flip horizontal">&#8596;</button>' +
                            '<button type="button" data-action="flip-v" title="Flip vertical">&#8597;</button>' +
                            '<button type="button" data-action="reset" title="Reset">Reset</button>' +
                        '</div>' +
                        '<div class="thlin-image-editor-group thlin-image-editor-aspect-presets">' +
                            '<span>Crop shape</span>' +
                            '<button type="button" data-aspect="free">Free</button>' +
                            '<button type="button" data-aspect="1">Square</button>' +
                            '<button type="button" data-aspect="1.7778">16:9</button>' +
                            '<button type="button" data-aspect="1.3333">4:3</button>' +
                        '</div>' +
                        '<div class="thlin-image-editor-group thlin-image-editor-dims">' +
                            '<label>Width<input type="number" min="1" data-role="width"></label>' +
                            '<label>Height<input type="number" min="1" data-role="height"></label>' +
                            '<label class="thlin-image-editor-checkbox"><input type="checkbox" data-role="aspect" checked> Lock aspect ratio</label>' +
                        '</div>' +
                        '<p class="thlin-image-editor-status" data-role="status"></p>' +
                    '</div>' +
                '</div>' +
                '<div class="thlin-image-editor-footer">' +
                    '<button type="button" class="btn btn-light" data-action="cancel">Cancel</button>' +
                    '<button type="button" class="btn btn-primary" data-action="apply">Apply</button>' +
                '</div>' +
            '</div>';

        document.body.appendChild(modal);

        sourceImg = el('.thlin-image-editor-source');
        statusEl = el('[data-role="status"]');

        modal.addEventListener('click', function (event) {
            var action = event.target.getAttribute('data-action');
            var aspect = event.target.getAttribute('data-aspect');

            if (event.target === modal || action === 'cancel') {
                close();
                return;
            }

            if (!cropper) {
                return;
            }

            switch (action) {
                case 'rotate-left':
                    cropper.rotate(-90);
                    break;
                case 'rotate-right':
                    cropper.rotate(90);
                    break;
                case 'flip-h':
                    cropper.scaleX(-cropper.getData().scaleX || -1);
                    break;
                case 'flip-v':
                    cropper.scaleY(-cropper.getData().scaleY || -1);
                    break;
                case 'reset':
                    cropper.reset();
                    break;
                case 'apply':
                    apply();
                    break;
            }

            if (aspect) {
                cropper.setAspectRatio(aspect === 'free' ? NaN : parseFloat(aspect));
            }
        });

        el('[data-role="width"]').addEventListener('input', function (event) {
            syncDimension('width', event.target.value);
        });
        el('[data-role="height"]').addEventListener('input', function (event) {
            syncDimension('height', event.target.value);
        });
        el('[data-role="aspect"]').addEventListener('change', function (event) {
            lockAspect = event.target.checked;
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && modal.classList.contains('is-open')) {
                close();
            }
        });
    }

    function syncDimension(dimension, value) {
        if (!cropper) {
            return;
        }

        var num = parseInt(value, 10);
        if (!num || num <= 0) {
            return;
        }

        var data = cropper.getData();
        var next = {};

        if (dimension === 'width') {
            next.width = num;
            if (lockAspect && data.height) {
                next.height = Math.round(num * (data.height / data.width));
            }
        } else {
            next.height = num;
            if (lockAspect && data.width) {
                next.width = Math.round(num * (data.width / data.height));
            }
        }

        cropper.setData(next);
        refreshDimensionInputs();
    }

    function refreshDimensionInputs() {
        if (!cropper) {
            return;
        }
        var data = cropper.getData();
        el('[data-role="width"]').value = Math.round(data.width) || '';
        el('[data-role="height"]').value = Math.round(data.height) || '';
    }

    function open(editor, imgNode) {
        ensureModal();
        activeEditor = editor;
        activeImg = imgNode;
        setStatus('');

        var src = imgNode.getAttribute('src');
        sourceImg.setAttribute('crossorigin', 'anonymous');
        sourceImg.src = src;

        modal.classList.add('is-open');

        var start = function () {
            if (cropper) {
                cropper.destroy();
            }
            cropper = new Cropper(sourceImg, {
                viewMode: 1,
                autoCropArea: 1,
                background: false,
                responsive: true,
                ready: function () {
                    refreshDimensionInputs();
                },
                crop: function () {
                    refreshDimensionInputs();
                },
            });
        };

        if (sourceImg.complete) {
            start();
        } else {
            sourceImg.onload = start;
        }
    }

    function close() {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        if (modal) {
            modal.classList.remove('is-open');
        }
        activeEditor = null;
        activeImg = null;
    }

    function apply() {
        if (!cropper || !activeEditor || !activeImg) {
            return;
        }

        setStatus('Saving…');

        var canvas = cropper.getCroppedCanvas({
            imageSmoothingQuality: 'high',
        });

        if (!canvas) {
            setStatus('Could not read this image (it may be blocked by CORS).', true);
            return;
        }

        canvas.toBlob(function (blob) {
            if (!blob) {
                setStatus('Could not export the edited image.', true);
                return;
            }

            var editor = activeEditor;
            var imgNode = activeImg;
            var id = 'thlinimg' + new Date().getTime();

            var reader = new FileReader();
            reader.onload = function () {
                var base64 = reader.result.split(',')[1];
                var blobInfo = editor.editorUpload.blobCache.create(id, blob, base64);
                editor.editorUpload.blobCache.add(blobInfo);

                imgNode.setAttribute('src', blobInfo.blobUri());
                imgNode.setAttribute('width', canvas.width);
                imgNode.setAttribute('height', canvas.height);
                imgNode.removeAttribute('data-mce-src');

                editor.undoManager.add();

                editor.uploadImages(function () {
                    setStatus('');
                    close();
                });
            };
            reader.readAsDataURL(blob);
        }, 'image/png');
    }

    var ALIGN_CLASSES = ['thlin-img-left', 'thlin-img-center', 'thlin-img-right', 'thlin-img-full'];

    function isImgInEditor(editor, img) {
        return Boolean(img && editor.getBody && editor.getBody().contains(img));
    }

    function getSelectedImgFromNode(editor, node) {
        if (!node) {
            return null;
        }

        if (node.nodeName === 'IMG') {
            return node;
        }

        return editor.dom.getParent(node, 'img');
    }

    function rememberImage(editor, img) {
        if (isImgInEditor(editor, img)) {
            editor._thlinActiveImage = img;
        }
    }

    function getSelectedImg(editor) {
        if (isImgInEditor(editor, editor._thlinActiveImage)) {
            return editor._thlinActiveImage;
        }

        var fromSelection = getSelectedImgFromNode(editor, editor.selection.getNode());

        if (fromSelection) {
            rememberImage(editor, fromSelection);
            return fromSelection;
        }

        var marked = editor.getBody().querySelector('img[data-mce-selected="1"]');

        if (marked) {
            rememberImage(editor, marked);
            return marked;
        }

        return null;
    }

    function rememberActiveImage(editor) {
        editor._thlinActiveImage = null;

        editor.on('ObjectSelected', function (event) {
            if (event.target && event.target.nodeName === 'IMG') {
                rememberImage(editor, event.target);
            }
        });

        editor.on('click', function (event) {
            if (event.target && event.target.nodeName === 'IMG') {
                rememberImage(editor, event.target);
            }
        });

        editor.on('NodeChange', function () {
            var img = getSelectedImgFromNode(editor, editor.selection.getNode());

            if (img) {
                rememberImage(editor, img);
            }
        });
    }

    function finishImageMove(editor, img) {
        if (!isImgInEditor(editor, img)) {
            return;
        }

        editor.focus();
        editor.selection.select(img);
        rememberImage(editor, img);
        editor.nodeChanged();
        editor.fire('change');
    }

    function notifyImageMoveLimit(editor, message) {
        editor.notificationManager.open({
            text: message,
            type: 'info',
            timeout: 2500,
        });
    }

    function isIgnorableElement(element) {
        if (!element || element.nodeType !== 1) {
            return true;
        }

        if (element.getAttribute('data-mce-bogus') === 'all') {
            return true;
        }

        if (element.tagName === 'P') {
            var text = (element.textContent || '').replace(/\u00a0/g, ' ').trim();

            return text === '' && element.querySelectorAll('img').length === 0;
        }

        return false;
    }

    function getElementSibling(element, direction) {
        var sibling = direction === 'prev'
            ? element.previousElementSibling
            : element.nextElementSibling;

        while (isIgnorableElement(sibling)) {
            sibling = direction === 'prev'
                ? sibling.previousElementSibling
                : sibling.nextElementSibling;
        }

        return sibling || null;
    }

    function getMeaningfulSibling(node, direction) {
        var sibling = direction === 'prev' ? node.previousSibling : node.nextSibling;

        while (sibling) {
            if (sibling.nodeType === 3) {
                if (sibling.textContent.trim() !== '') {
                    return sibling;
                }
            } else if (sibling.nodeType === 1 && !isIgnorableElement(sibling)) {
                return sibling;
            }

            sibling = direction === 'prev' ? sibling.previousSibling : sibling.nextSibling;
        }

        return null;
    }

    function getMovableBlock(editor, img) {
        if (!img) {
            return null;
        }

        var selectors = [
            'section.content-section',
            'figure',
            '.media-frame-wrap',
            '.media-frame',
            'p',
        ];

        for (var i = 0; i < selectors.length; i++) {
            var match = editor.dom.getParent(img, selectors[i]);

            if (match && match !== editor.getBody()) {
                return match;
            }
        }

        return img;
    }

    var draggingBlock = null;
    var dropMarker = null;

    function getDropCandidates(block) {
        if (!block || !block.parentNode) {
            return [];
        }

        return Array.from(block.parentNode.children).filter(function (child) {
            return child !== block
                && !(child.classList && child.classList.contains('thlin-img-drop-marker'))
                && child.getAttribute('data-mce-bogus') !== 'all';
        });
    }

    function findDropTarget(editor, clientY, block) {
        var candidates = getDropCandidates(block);
        var container = block.parentNode;
        var body = editor.getBody();

        while (candidates.length === 0 && container && container !== body) {
            block = container;
            container = block.parentNode;
            candidates = getDropCandidates(block);
        }

        if (candidates.length === 0) {
            candidates = Array.from(body.children).filter(function (child) {
                return child !== block
                    && !(child.classList && child.classList.contains('thlin-img-drop-marker'))
                    && child.getAttribute('data-mce-bogus') !== 'all';
            });
        }

        var best = null;
        var bestDistance = Infinity;

        candidates.forEach(function (element) {
            var rect = element.getBoundingClientRect();

            if (!rect.height && !rect.width) {
                return;
            }

            var beforeDistance = Math.abs(clientY - rect.top);
            var afterDistance = Math.abs(clientY - rect.bottom);

            if (beforeDistance < bestDistance) {
                bestDistance = beforeDistance;
                best = { element: element, position: 'before' };
            }

            if (afterDistance < bestDistance) {
                bestDistance = afterDistance;
                best = { element: element, position: 'after' };
            }
        });

        return best;
    }

    function placeDropMarker(doc, target) {
        if (!target || !target.element || !target.element.parentNode) {
            return;
        }

        var marker = ensureDropMarker(doc);
        clearDropMarker();

        if (target.position === 'before') {
            target.element.parentNode.insertBefore(marker, target.element);
        } else if (target.element.nextSibling) {
            target.element.parentNode.insertBefore(marker, target.element.nextSibling);
        } else {
            target.element.parentNode.appendChild(marker);
        }
    }

    function ensureDropMarker(doc) {
        if (!dropMarker) {
            dropMarker = doc.createElement('div');
            dropMarker.className = 'thlin-img-drop-marker';
            dropMarker.setAttribute('contenteditable', 'false');
            dropMarker.setAttribute('data-mce-bogus', 'all');
        }

        return dropMarker;
    }

    function clearDropMarker() {
        if (dropMarker && dropMarker.parentNode) {
            dropMarker.parentNode.removeChild(dropMarker);
        }
    }

    function setImageDragMode(editor, enabled) {
        var img = getSelectedImg(editor);

        if (!img) {
            return;
        }

        if (enabled) {
            img.setAttribute('data-thlin-drag-active', '1');
            img.classList.add('thlin-img-draggable');
            editor.notificationManager.open({
                text: 'Click and drag the image to move it to another spot in the content.',
                type: 'info',
                timeout: 3500,
            });
        } else {
            img.removeAttribute('data-thlin-drag-active');
            img.classList.remove('thlin-img-draggable', 'thlin-img-dragging');
        }

        editor.nodeChanged();
    }

    function attachImageDragDrop(editor) {
        editor.on('init', function () {
            var body = editor.getBody();
            var doc = editor.getDoc();
            var dragState = {
                active: false,
                block: null,
                img: null,
                startY: 0,
                moved: false,
            };

            function finishDrag(commit) {
                doc.removeEventListener('mousemove', onPointerMove, true);
                doc.removeEventListener('mouseup', onPointerUp, true);

                if (commit && dragState.moved && dragState.block && dropMarker && dropMarker.parentNode) {
                    dropMarker.parentNode.insertBefore(dragState.block, dropMarker);

                    if (dragState.img) {
                        editor.selection.select(dragState.img);
                    }

                    editor.undoManager.add();
                    editor.nodeChanged();
                }

                if (dragState.img) {
                    dragState.img.classList.remove('thlin-img-dragging');
                }

                clearDropMarker();
                draggingBlock = null;
                dragState.active = false;
                dragState.block = null;
                dragState.img = null;
                dragState.moved = false;
            }

            function onPointerMove(event) {
                if (!dragState.active || !dragState.block) {
                    return;
                }

                if (Math.abs(event.clientY - dragState.startY) > 4) {
                    dragState.moved = true;
                }

                if (!dragState.moved) {
                    return;
                }

                event.preventDefault();
                draggingBlock = dragState.block;

                var target = findDropTarget(editor, event.clientY, dragState.block);
                placeDropMarker(doc, target);
            }

            function onPointerUp(event) {
                if (!dragState.active) {
                    return;
                }

                event.preventDefault();
                finishDrag(true);
            }

            body.addEventListener('mousedown', function (event) {
                var img = event.target;

                if (!img || img.nodeName !== 'IMG' || !img.hasAttribute('data-thlin-drag-active')) {
                    return;
                }

                event.preventDefault();
                event.stopPropagation();

                dragState.active = true;
                dragState.img = img;
                dragState.block = getMovableBlock(editor, img);
                dragState.startY = event.clientY;
                dragState.moved = false;
                draggingBlock = dragState.block;

                img.classList.add('thlin-img-dragging');

                doc.addEventListener('mousemove', onPointerMove, true);
                doc.addEventListener('mouseup', onPointerUp, true);
            });

            editor.on('remove', function () {
                finishDrag(false);
            });
        });
    }

    function moveImageBlock(editor, direction) {
        var img = getSelectedImg(editor);

        if (!img) {
            notifyImageMoveLimit(editor, 'Select an image first.');
            return;
        }

        var moveEarlier = direction === 'up' || direction === 'left';
        var moved = false;

        editor.focus();

        editor.undoManager.transact(function () {
            var block = getMovableBlock(editor, img);
            var body = editor.getBody();

            while (block && block.parentNode) {
                var parent = block.parentNode;
                var sibling = getElementSibling(block, moveEarlier ? 'prev' : 'next');

                if (sibling) {
                    if (moveEarlier) {
                        parent.insertBefore(block, sibling);
                    } else {
                        parent.insertBefore(sibling, block);
                    }

                    moved = true;
                    break;
                }

                if (parent === body) {
                    break;
                }

                block = parent;
            }
        });

        if (!moved) {
            notifyImageMoveLimit(editor, moveEarlier
                ? 'Image is already at the top.'
                : 'Image is already at the bottom.');
            return;
        }

        finishImageMove(editor, img);
    }

    function moveImageInline(editor, direction) {
        var img = getSelectedImg(editor);

        if (!img) {
            notifyImageMoveLimit(editor, 'Select an image first.');
            return;
        }

        var moveEarlier = direction === 'left';
        var parent = img.parentNode;
        var body = editor.getBody();
        var moved = false;

        editor.focus();

        if (parent && parent !== body) {
            editor.undoManager.transact(function () {
                var sibling = getMeaningfulSibling(img, moveEarlier ? 'prev' : 'next');

                if (sibling) {
                    if (moveEarlier) {
                        parent.insertBefore(img, sibling);
                    } else {
                        parent.insertBefore(sibling, img);
                    }

                    moved = true;
                }
            });
        }

        if (moved) {
            finishImageMove(editor, img);
            return;
        }

        moveImageBlock(editor, moveEarlier ? 'up' : 'down');
    }

    function setImageAlignment(editor, className) {
        var img = getSelectedImg(editor);

        if (!img) {
            return;
        }

        ALIGN_CLASSES.forEach(function (name) {
            editor.dom.removeClass(img, name);
        });

        if (className) {
            editor.dom.addClass(img, className);
        }

        editor.dom.setStyles(img, {
            float: '',
            display: '',
            margin: '',
            width: '',
        });

        editor.selection.select(img);
        editor.nodeChanged();
        editor.undoManager.add();
    }

    function replaceSelectedImage(editor) {
        var img = getSelectedImg(editor);

        if (!img) {
            return;
        }

        var input = document.createElement('input');
        input.type = 'file';
        input.accept = 'image/jpeg,image/png,image/webp,image/gif';
        input.onchange = function () {
            var file = input.files && input.files[0];

            if (!file || typeof ThlinEditorUpload !== 'function') {
                return;
            }

            var reader = new FileReader();
            reader.onload = function () {
                var base64 = reader.result.split(',')[1];
                var id = 'thlinreplace' + Date.now();
                var blobInfo = editor.editorUpload.blobCache.create(id, file, base64);
                editor.editorUpload.blobCache.add(blobInfo);

                ThlinEditorUpload(blobInfo)
                    .then(function (url) {
                        editor.dom.setAttrib(img, 'src', url);
                        img.removeAttribute('data-mce-src');
                        img.removeAttribute('width');
                        img.removeAttribute('height');
                        editor.selection.select(img);
                        editor.nodeChanged();
                        editor.undoManager.add();
                    })
                    .catch(function (message) {
                        editor.notificationManager.open({
                            text: message || 'Image upload failed.',
                            type: 'error',
                            timeout: 4000,
                        });
                    });
            };
            reader.readAsDataURL(file);
        };
        input.click();
    }

    function removeSelectedImage(editor) {
        var img = getSelectedImg(editor);

        if (!img) {
            return;
        }

        var block = getMovableBlock(editor, img);

        if (block && block !== img && block.querySelectorAll('img').length === 1 && block.textContent.trim() === '') {
            editor.dom.remove(block);
        } else {
            editor.dom.remove(img);
        }

        editor.nodeChanged();
        editor.undoManager.add();
    }

    function registerImagePlacementButtons(editor) {
        editor.ui.registry.addButton('thlinImgLeft', {
            icon: 'align-left',
            tooltip: 'Align image left',
            onAction: function () { setImageAlignment(editor, 'thlin-img-left'); },
        });
        editor.ui.registry.addButton('thlinImgCenter', {
            icon: 'align-center',
            tooltip: 'Center image',
            onAction: function () { setImageAlignment(editor, 'thlin-img-center'); },
        });
        editor.ui.registry.addButton('thlinImgRight', {
            icon: 'align-right',
            tooltip: 'Align image right',
            onAction: function () { setImageAlignment(editor, 'thlin-img-right'); },
        });
        editor.ui.registry.addButton('thlinImgFull', {
            text: 'Full',
            tooltip: 'Full width image',
            onAction: function () { setImageAlignment(editor, 'thlin-img-full'); },
        });
        editor.ui.registry.addToggleButton('thlinDragMove', {
            icon: 'drag',
            tooltip: 'Drag to reposition',
            onAction: function () {
                var img = getSelectedImg(editor);

                if (!img) {
                    return;
                }

                setImageDragMode(editor, !img.hasAttribute('data-thlin-drag-active'));
            },
            onSetup: function (api) {
                var handler = function () {
                    var img = getSelectedImg(editor);
                    api.setEnabled(Boolean(img));
                    api.setActive(Boolean(img && img.hasAttribute('data-thlin-drag-active')));
                };

                editor.on('NodeChange', handler);
                handler();

                return function () {
                    editor.off('NodeChange', handler);
                };
            },
        });
        editor.ui.registry.addButton('thlinMoveLeft', {
            icon: 'chevron-left',
            tooltip: 'Move left',
            onAction: function () {
                editor.focus();
                moveImageInline(editor, 'left');
            },
        });
        editor.ui.registry.addButton('thlinMoveUp', {
            icon: 'chevron-up',
            tooltip: 'Move up',
            onAction: function () {
                editor.focus();
                moveImageBlock(editor, 'up');
            },
        });
        editor.ui.registry.addButton('thlinMoveDown', {
            icon: 'chevron-down',
            tooltip: 'Move down',
            onAction: function () {
                editor.focus();
                moveImageBlock(editor, 'down');
            },
        });
        editor.ui.registry.addButton('thlinMoveRight', {
            icon: 'chevron-right',
            tooltip: 'Move right',
            onAction: function () {
                editor.focus();
                moveImageInline(editor, 'right');
            },
        });
        editor.ui.registry.addButton('thlinReplaceImg', {
            icon: 'image',
            tooltip: 'Replace image',
            onAction: function () { replaceSelectedImage(editor); },
        });
        editor.ui.registry.addButton('thlinRemoveImg', {
            icon: 'remove',
            tooltip: 'Remove image',
            onAction: function () { removeSelectedImage(editor); },
        });
    }

    function attach(editor) {
        editor.ui.registry.addButton('imageedit', {
            icon: 'edit-image',
            tooltip: 'Edit / crop image',
            onAction: function () {
                var img = getSelectedImg(editor);

                if (img) {
                    open(editor, img);
                    return;
                }

                editor.notificationManager.open({
                    text: 'Select an image first.',
                    type: 'info',
                    timeout: 3000,
                });
            },
        });

        registerImagePlacementButtons(editor);
        rememberActiveImage(editor);
        attachImageDragDrop(editor);

        editor.on('dblclick', function (event) {
            if (event.target && event.target.nodeName === 'IMG') {
                open(editor, event.target);
            }
        });

        editor.on('click', function (event) {
            if (event.target && event.target.nodeName === 'IMG') {
                editor.selection.select(event.target);
                rememberImage(editor, event.target);
            }
        });
    }

    return { attach: attach };
})();
</script>
@endonce
