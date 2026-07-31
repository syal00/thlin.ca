{{-- Shared TinyMCE image upload handler for CMS admin editors. --}}
<script>
window.ThlinEditorUpload = function (blobInfo) {
    return new Promise(function (resolve, reject) {
        var formData = new FormData();
        formData.append('file', blobInfo.blob(), blobInfo.filename());

        var csrfMeta = document.querySelector('meta[name="csrf-token"]');
        var csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : @json(csrf_token());

        fetch(@json(route('admin.editor.upload-image')), {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: formData,
        })
            .then(function (response) {
                return response.text().then(function (text) {
                    var result = {};

                    try {
                        result = JSON.parse(text);
                    } catch (error) {
                        result = {};
                    }

                    return {
                        ok: response.ok,
                        status: response.status,
                        result: result,
                    };
                });
            })
            .then(function (payload) {
                if (payload.ok && payload.result.location) {
                    resolve(payload.result.location);
                    return;
                }

                if (payload.status === 419) {
                    reject('Your session expired. Refresh this page and try again.');
                    return;
                }

                reject(
                    payload.result.message
                    || ('Upload failed (HTTP ' + payload.status + '). Please try again.')
                );
            })
            .catch(function () {
                reject('Image upload failed. Check your connection and try again.');
            });
    });
};
</script>
