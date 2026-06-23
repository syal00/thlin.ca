@props([
    'selector',
    'height' => 420,
])

@push('scripts')
<script src="{{ asset('vendor/tinymce/tinymce.min.js') }}?v={{ @filemtime(public_path('vendor/tinymce/tinymce.min.js')) ?: '1' }}"></script>
<script>
    tinymce.init({
        selector: @json($selector),
        license_key: 'gpl',
        base_url: @json(asset('vendor/tinymce')),
        suffix: '.min',
        height: {{ (int) $height }},
        menubar: false,
        plugins: 'lists link image table code preview autoresize',
        toolbar: 'undo redo | blocks | bold italic | bullist numlist | link image table | removeformat | code preview',
        branding: false,
        promotion: false,
        automatic_uploads: true,
        relative_urls: false,
        convert_urls: true,
        content_style: 'body { font-family: Arial, Helvetica, sans-serif; font-size: 16px; line-height: 1.7; color: #2C2C2A; }',
        images_upload_handler: function (blobInfo) {
            return new Promise(function (resolve, reject) {
                const formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());

                fetch(@json(route('admin.editor.upload-image')), {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': @json(csrf_token()) },
                    body: formData,
                })
                .then(function (response) { return response.json(); })
                .then(function (result) {
                    if (result.location) {
                        resolve(result.location);
                    } else {
                        reject(result.message || 'Upload failed');
                    }
                })
                .catch(function () { reject('Upload failed'); });
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
