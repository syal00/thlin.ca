@php
    $useTinyCloud = ! config('services.tinymce.self_hosted', true) && filled(config('services.tinymce.key'));
@endphp

@if ($useTinyCloud)
<script src="https://cdn.tiny.cloud/1/{{ config('services.tinymce.key') }}/tinymce/7/tinymce.min.js" referrerpolicy="origin" onerror="window.__thlinTinyMceLoadFailed = true"></script>
@else
<script src="{{ asset('vendor/tinymce/tinymce.min.js') }}?v={{ @filemtime(public_path('vendor/tinymce/tinymce.min.js')) ?: '1' }}" onerror="window.__thlinTinyMceLoadFailed = true"></script>
@endif
<script>
window.thlinInitTinyMce = function (config) {
    var selector = config.selector;
    var target = document.querySelector(selector);

    function showLoadError() {
        if (! target || target.dataset.editorErrorShown === '1') {
            return;
        }

        target.dataset.editorErrorShown = '1';

        var notice = document.createElement('p');
        notice.className = 'form-error cms-editor-load-error';
        notice.textContent = 'Rich text editor failed to load. Hard refresh this page (Ctrl+Shift+R). If you are on the Vercel demo, redeploy after the latest fix.';
        target.parentNode.insertBefore(notice, target);
    }

    if (window.__thlinTinyMceLoadFailed || typeof tinymce === 'undefined') {
        showLoadError();
        return;
    }

    var initResult = tinymce.init(config);

    if (initResult && typeof initResult.catch === 'function') {
        initResult.catch(function () {
            showLoadError();
        });
    }
};
</script>
