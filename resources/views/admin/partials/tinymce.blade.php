@props([
    'selector',
    'height' => 420,
])

@include('admin.partials.image-editor')
@push('scripts')
@include('admin.partials.tinymce-script')
@include('admin.partials.tinymce-upload')
<script>
    tinymce.init({
        selector: @json($selector),
        license_key: 'gpl',
        base_url: @json(asset('vendor/tinymce')),
        suffix: '.min',
        height: {{ (int) $height }},
        menubar: false,
        plugins: 'lists link image media table code preview autoresize quickbars charmap emoticons searchreplace fullscreen help',
        toolbar: 'undo redo | blocks | bold italic | bullist numlist | link image imageedit media table | removeformat | code preview',
        quickbars_image_toolbar: 'thlinImgLeft thlinImgCenter thlinImgRight thlinImgFull | thlinMoveUp thlinMoveDown | thlinReplaceImg imageedit thlinRemoveImg',
        quickbars_selection_toolbar: false,
        object_resizing: true,
        resize_img_proportional: true,
        contextmenu: 'link image table',
        branding: false,
        promotion: false,
        automatic_uploads: true,
        paste_data_images: true,
        document_base_url: @json(rtrim(url('/'), '/').'/'),
        relative_urls: true,
        remove_script_host: true,
        convert_urls: false,
        verify_html: false,
        image_advtab: true,
        image_caption: true,
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
            + '.thlin-img-full { display: block; width: 100%; height: auto; margin: 12px 0; }',
        setup: function (editor) {
            ThlinImageEditor.attach(editor);
        },
        images_upload_handler: ThlinEditorUpload,
    });

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('form').forEach(function (form) {
            form.addEventListener('submit', function () {
                if (typeof tinymce !== 'undefined') {
                    tinymce.triggerSave();
                }
            });
        });
    });
</script>
@endpush
