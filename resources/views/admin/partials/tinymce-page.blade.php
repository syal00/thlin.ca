@props([
    'selector' => '#page-content-editor',
    'height' => 520,
    'formSelector' => '.cms-form',
])

@include('admin.partials.image-editor')
@push('scripts')
@include('admin.partials.tinymce-script')
@include('admin.partials.tinymce-upload')
<script>
    window.thlinInitTinyMce({
        selector: @json($selector),
        license_key: 'gpl',
        base_url: @json(asset('vendor/tinymce')),
        suffix: '.min',
        height: {{ (int) $height }},
        menubar: 'edit view insert format tools table help',
        plugins: [
            'advlist', 'autolink', 'autoresize', 'charmap', 'code', 'emoticons',
            'fullscreen', 'help', 'image', 'link', 'lists', 'media', 'preview',
            'quickbars', 'searchreplace', 'table', 'wordcount', 'anchor',
        ],
        toolbar: [
            'undo redo | blocks | bold italic underline | bullist numlist',
            'link image imageedit media table | anchor charmap emoticons',
            'searchreplace | outdent indent | removeformat',
            'code preview fullscreen | help',
        ].join(' | '),
        quickbars_image_toolbar: 'thlinImgLeft thlinImgCenter thlinImgRight thlinImgFull | thlinMoveUp thlinMoveDown | thlinReplaceImg imageedit thlinRemoveImg',
        quickbars_selection_toolbar: false,
        object_resizing: true,
        resize_img_proportional: true,
        branding: false,
        promotion: false,
        automatic_uploads: true,
        paste_data_images: true,
        document_base_url: @json(rtrim(url('/'), '/').'/'),
        relative_urls: true,
        remove_script_host: true,
        convert_urls: false,
        verify_html: false,
        entity_encoding: 'raw',
        image_advtab: true,
        image_caption: true,
        table_toolbar: 'tableprops tabledelete | tableinsertrowbefore tableinsertrowafter tabledeleterow | tableinsertcolbefore tableinsertcolafter tabledeletecol',
        table_appearance_options: true,
        table_advtab: true,
        table_cell_advtab: true,
        table_row_advtab: true,
        extended_valid_elements: 'section[class|style|id|aria-label],figure[class|style|id],figcaption[class|style],canvas[id|class|width|height|style],iframe[src|width|height|frameborder|allow|allowfullscreen|title|class|style|loading|referrerpolicy],video[src|controls|width|height|poster|class|style|preload],source[src|type],img[src|alt|width|height|class|style|loading|decoding|data-mce-src],div[class|style|id|role|aria-label|aria-hidden],span[class|style|aria-hidden],table[class|style|border|cellpadding|cellspacing|width],tbody[class|style],thead[class|style],tr[class|style],td[class|style|colspan|rowspan|width|height],th[class|style|colspan|rowspan|width|height]',
        emoticons_database: 'emojis',
        emoticons_database_url: @json(asset('vendor/tinymce/plugins/emoticons/js/emojis.min.js')),
        image_class_list: [
            { title: 'None', value: '' },
            { title: 'Align left', value: 'thlin-img-left' },
            { title: 'Align center', value: 'thlin-img-center' },
            { title: 'Align right', value: 'thlin-img-right' },
            { title: 'Full width', value: 'thlin-img-full' },
        ],
        content_style: 'body { font-family: Arial, Helvetica, sans-serif; font-size: 16px; line-height: 1.7; color: #2C2C2A; } '
            + '.thlin-img-left { float: left; margin: 4px 16px 12px 0; } '
            + '.thlin-img-right { float: right; margin: 4px 0 12px 16px; } '
            + '.thlin-img-center { display: block; margin: 12px auto; } '
            + '.thlin-img-full { display: block; width: 100%; height: auto; margin: 12px 0; } '
            + 'section.content-section { margin-bottom: 1.5rem; } '
            + '.media-frame-wrap, .media-frame { margin: 1rem 0; } '
            + '.media-frame img, figure img { max-width: 100%; height: auto; display: block; } '
            + 'table { border-collapse: collapse; width: 100%; margin: 1rem 0; } '
            + 'table td, table th { border: 1px solid #cbd5e1; padding: 8px; vertical-align: top; } '
            + 'iframe, video { display: block; max-width: 100%; margin: 1rem 0; } '
            + 'img { max-width: 100%; height: auto; cursor: pointer; } '
            + 'img.mce-selected, img[data-mce-selected] { outline: 3px solid #185FA5; outline-offset: 3px; }',
        setup: function (editor) {
            ThlinImageEditor.attach(editor);
        },
        images_upload_handler: ThlinEditorUpload,
    });

    document.addEventListener('DOMContentLoaded', function () {
        var cmsForm = document.querySelector(@json($formSelector));
        var editorId = @json(ltrim($selector, '#.'));
        var cmsFormSubmitting = false;

        if (! cmsForm) {
            return;
        }

        function persistActionInput(submitter) {
            if (! submitter || submitter.name !== 'action') {
                return;
            }

            var actionInput = cmsForm.querySelector('input[type="hidden"][name="action"]');

            if (! actionInput) {
                actionInput = document.createElement('input');
                actionInput.type = 'hidden';
                actionInput.name = 'action';
                cmsForm.appendChild(actionInput);
            }

            actionInput.value = submitter.value;
        }

        function finalizeSubmit(submitter) {
            var editor = typeof tinymce !== 'undefined' ? tinymce.get(editorId) : null;

            if (editor) {
                editor.save();
            } else if (typeof tinymce !== 'undefined') {
                tinymce.triggerSave();
            }

            persistActionInput(submitter);
            cmsFormSubmitting = true;

            if (typeof cmsForm.requestSubmit === 'function') {
                cmsForm.requestSubmit(submitter || undefined);
                return;
            }

            cmsForm.submit();
        }

        cmsForm.addEventListener('submit', function (event) {
            if (cmsFormSubmitting) {
                return;
            }

            var editor = typeof tinymce !== 'undefined' ? tinymce.get(editorId) : null;

            if (typeof tinymce === 'undefined' || ! editor) {
                return;
            }

            event.preventDefault();

            var submitter = event.submitter;
            var uploadSettled = false;

            function submitOnce() {
                if (uploadSettled) {
                    return;
                }

                uploadSettled = true;
                finalizeSubmit(submitter);
            }

            if (typeof editor.uploadImages === 'function') {
                var uploadPromise = editor.uploadImages(submitOnce);

                if (uploadPromise && typeof uploadPromise.catch === 'function') {
                    uploadPromise.catch(submitOnce);
                }

                window.setTimeout(submitOnce, 8000);
                return;
            }

            submitOnce();
        });
    });
</script>
@endpush
