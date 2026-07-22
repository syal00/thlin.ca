@props([
    'selector',
    'height' => 420,
])

@include('admin.partials.image-editor')
@push('scripts')
@include('admin.partials.tinymce-script')
<script>
    tinymce.init({
        selector: @json($selector),
        license_key: 'gpl',
        base_url: @json(asset('vendor/tinymce')),
        suffix: '.min',
        height: {{ (int) $height }},
        menubar: false,
        plugins: 'lists link image table code preview autoresize quickbars',
        toolbar: 'undo redo | blocks | bold italic | bullist numlist | link image imageedit table | removeformat | code preview',
        quickbars_image_toolbar: 'imageedit alignleft aligncenter alignright',
        quickbars_selection_toolbar: false,
        contextmenu: 'link image table',
        branding: false,
        promotion: false,
        automatic_uploads: true,
        paste_data_images: true,
        relative_urls: false,
        convert_urls: true,
        image_advtab: true,
        image_caption: true,
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
        images_upload_handler: function (blobInfo) {
            return new Promise(function (resolve, reject) {
                const formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());

                fetch(@json(route('admin.editor.upload-image')), {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': @json(csrf_token()) },
                    body: formData,
                })
                .then(function (response) {
                    return response.json().then(function (result) {
                        return { ok: response.ok, result: result };
                    });
                })
                .then(function (payload) {
                    if (payload.ok && payload.result.location) {
                        resolve(payload.result.location);
                    } else {
                        reject(payload.result.message || 'Image upload failed. Please try a JPG, PNG, or WEBP file under 5MB.');
                    }
                })
                .catch(function () {
                    reject('Image upload failed. Check your connection and try again.');
                });
            });
        },
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
