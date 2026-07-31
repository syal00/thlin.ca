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

    function getSelectedImg(editor) {
        var node = editor.selection.getNode();

        if (node && node.nodeName === 'IMG') {
            return node;
        }

        return editor.dom.getParent(node, 'img');
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

    function moveImageBlock(editor, direction) {
        var img = getSelectedImg(editor);

        if (!img) {
            return;
        }

        var block = getMovableBlock(editor, img);
        var parent = block && block.parentNode;

        if (!parent) {
            return;
        }

        if (direction === 'up') {
            var previous = block.previousElementSibling;

            if (!previous) {
                editor.notificationManager.open({
                    text: 'Image is already at the top of this section.',
                    type: 'info',
                    timeout: 2500,
                });
                return;
            }

            parent.insertBefore(block, previous);
        } else {
            var next = block.nextElementSibling;

            if (!next) {
                editor.notificationManager.open({
                    text: 'Image is already at the bottom of this section.',
                    type: 'info',
                    timeout: 2500,
                });
                return;
            }

            parent.insertBefore(next, block);
        }

        editor.selection.select(img);
        editor.nodeChanged();
        editor.undoManager.add();
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
        editor.ui.registry.addButton('thlinMoveUp', {
            text: '↑',
            tooltip: 'Move image up',
            onAction: function () { moveImageBlock(editor, 'up'); },
        });
        editor.ui.registry.addButton('thlinMoveDown', {
            text: '↓',
            tooltip: 'Move image down',
            onAction: function () { moveImageBlock(editor, 'down'); },
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
        registerImagePlacementButtons(editor);

        editor.ui.registry.addContextToolbar('thlinImagePlacementToolbar', {
            predicate: function (node) {
                return node.nodeName === 'IMG' || Boolean(editor.dom.getParent(node, 'img'));
            },
            items: 'thlinImgLeft thlinImgCenter thlinImgRight thlinImgFull | thlinMoveUp thlinMoveDown | thlinReplaceImg imageedit thlinRemoveImg',
            position: 'node',
            scope: 'node',
        });

        editor.ui.registry.addButton('imageedit', {
            icon: 'edit-image',
            tooltip: 'Edit / crop image',
            onAction: function () {
                var node = editor.selection.getNode();
                if (node && node.nodeName === 'IMG') {
                    open(editor, node);
                } else {
                    editor.notificationManager.open({
                        text: 'Select an image first.',
                        type: 'info',
                        timeout: 3000,
                    });
                }
            },
            onSetup: function (api) {
                var handler = function () {
                    var node = editor.selection.getNode();
                    api.setEnabled(Boolean(node && node.nodeName === 'IMG'));
                };
                editor.on('NodeChange', handler);
                handler();
                return function () {
                    editor.off('NodeChange', handler);
                };
            },
        });

        editor.on('dblclick', function (event) {
            if (event.target && event.target.nodeName === 'IMG') {
                open(editor, event.target);
            }
        });

        editor.on('click', function (event) {
            if (event.target && event.target.nodeName === 'IMG') {
                editor.selection.select(event.target);
            }
        });
    }

    return { attach: attach };
})();
</script>
@endonce
