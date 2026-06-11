<form method="post" action="{{ $action }}" class="admin-form">
    @csrf

    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="admin-card admin-card--spaced">
        <h2>Page Details</h2>

        <div class="form-group">
            <label for="title">Page Title</label>
            <input type="text" id="title" name="title" value="{{ old('title', $page->title) }}" required>
            @error('title')
                <small class="form-error">{{ $message }}</small>
            @enderror
        </div>

        @if ($page->isCustom() || ! $page->exists)
            <div class="form-group">
                <label for="parent_id">Parent Page</label>
                <select id="parent_id" name="parent_id">
                    <option value="">No parent page</option>
                    @foreach ($parentPages as $parentPage)
                        <option value="{{ $parentPage->id }}" data-url="{{ $parentPage->full_url }}"
                            @selected(old('parent_id', $page->parent_id) == $parentPage->id)>
                            {{ $parentPage->title }} — {{ $parentPage->full_url }}
                        </option>
                    @endforeach
                </select>
                <small>
                    Choose a parent page if this page should appear inside an existing section.
                    Example: choose Products &amp; Services to create /products-services/testing.
                </small>
                @error('parent_id')
                    <small class="form-error">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="slug">Page URL</label>
                <div class="input-prefix-group">
                    <span class="input-prefix">/</span>
                    <input type="text" id="slug" name="slug" value="{{ old('slug', $page->slug) }}" placeholder="annual-reports">
                </div>
                <small>Use a short page URL like testing or annual-reports.</small>
                <div class="form-text mt-2">
                    Final URL Preview:
                    <strong id="final-url-preview">/{{ old('slug', $page->slug ?: 'your-page-url') }}</strong>
                </div>
                @error('slug')
                    <small class="form-error">{{ $message }}</small>
                @enderror
            </div>
        @else
            <div class="form-group">
                <label>Page URL</label>
                <p><code>{{ $page->full_url }}</code></p>
                <small>Built-in page URLs are fixed to protect the site layout.</small>
            </div>
        @endif

        <div class="form-group">
            <label for="hero_title">Hero Title</label>
            <input type="text" id="hero_title" name="hero_title" value="{{ old('hero_title', $page->hero_title) }}">
            @error('hero_title')
                <small class="form-error">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="hero_subtitle">Hero Subtitle</label>
            <textarea id="hero_subtitle" name="hero_subtitle" rows="2">{{ old('hero_subtitle', $page->hero_subtitle) }}</textarea>
            @error('hero_subtitle')
                <small class="form-error">{{ $message }}</small>
            @enderror
        </div>

        @if ($page->isBuiltIn() && $page->exists)
            <div class="form-group">
                <label for="excerpt">Summary Text</label>
                <textarea id="excerpt" name="excerpt" rows="3">{{ old('excerpt', $page->excerpt) }}</textarea>
                <small>Used in search results and page summaries.</small>
                @error('excerpt')
                    <small class="form-error">{{ $message }}</small>
                @enderror
            </div>
        @endif

        <div class="form-group">
            <label for="page-content-editor">Page Content</label>
            <textarea id="page-content-editor" name="body" rows="12">{{ old('body', $page->body) }}</textarea>
            @error('body')
                <small class="form-error">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="meta_description">Meta Description</label>
            <textarea id="meta_description" name="meta_description" rows="2">{{ old('meta_description', $page->meta_description) }}</textarea>
            @error('meta_description')
                <small class="form-error">{{ $message }}</small>
            @enderror
        </div>
    </div>

    @if ($page->isCustom() || ! $page->exists)
        <div class="admin-card admin-card--spaced">
            <h2>Publishing and Navigation</h2>

            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="show_in_navigation" value="1" @checked(old('show_in_navigation', $page->show_in_navigation))>
                    Show this page in website navigation
                </label>
                <small>If enabled, this page appears under its parent section in the website menu.</small>
            </div>

            <div class="form-group">
                <label for="navigation_label">Navigation Label</label>
                <input type="text" id="navigation_label" name="navigation_label" value="{{ old('navigation_label', $page->navigation_label) }}" placeholder="Annual Reports">
            </div>

            <div class="form-group">
                <label for="sort_order">Sort Order</label>
                <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $page->sort_order ?? 0) }}">
            </div>

            <p class="admin-help">Draft pages are not visible to website visitors.</p>
        </div>
    @endif

    <div class="admin-card admin-card--spaced">
        <h2>Quick Internal Links</h2>
        <p class="admin-help">Copy these links and paste them inside the page content editor.</p>

        <div class="admin-link-grid">
            @foreach ($publishedPages as $linkPage)
                <div class="admin-link-item">
                    <span>{{ $linkPage->title }}</span>
                    <code>{{ $linkPage->full_url }}</code>
                </div>
            @endforeach
        </div>

        <p style="margin-top: 1rem;">
            <a href="{{ route('admin.media.index') }}" target="_blank" rel="noopener" class="btn btn-sm">Open Uploaded Files</a>
        </p>
    </div>

    <div class="admin-form-actions">
        @if ($page->isCustom() || ! $page->exists)
            <button type="submit" name="action" value="draft" class="btn">Save as Draft</button>
            <button type="submit" name="action" value="publish" class="btn btn-primary">Publish Page</button>
        @else
            <button type="submit" class="btn btn-primary">Save Changes</button>
        @endif

        <a href="{{ route('admin.pages.index') }}" class="btn">Cancel</a>
    </div>
</form>

@push('scripts')
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#page-content-editor',
        height: 500,
        menubar: false,
        plugins: 'lists link image table code autoresize',
        toolbar: 'undo redo | blocks | bold italic | bullist numlist blockquote | link image table | code',
        branding: false,
        automatic_uploads: true,
        relative_urls: false,
        remove_script_host: false,
        convert_urls: true,
        content_style: 'body { font-family: Arial, sans-serif; font-size: 16px; }',
        images_upload_handler: function (blobInfo) {
            return new Promise(function (resolve, reject) {
                const formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());

                fetch('{{ route('admin.editor.upload-image') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                })
                .then(function (response) { return response.json(); })
                .then(function (result) {
                    if (result.location) {
                        resolve(result.location);
                    } else {
                        reject('Image upload failed.');
                    }
                })
                .catch(function () {
                    reject('Image upload failed.');
                });
            });
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        const parentSelect = document.querySelector('select[name="parent_id"]');
        const slugInput = document.querySelector('input[name="slug"]');
        const preview = document.getElementById('final-url-preview');

        function cleanSlug(value) {
            return value
                .toLowerCase()
                .trim()
                .replace(/^\/+|\/+$/g, '')
                .replace(/\s+/g, '-')
                .replace(/[^a-z0-9\-]/g, '');
        }

        function updatePreview() {
            if (!slugInput || !preview) {
                return;
            }

            const slug = cleanSlug(slugInput.value || 'your-page-url');
            let parentUrl = '';

            if (parentSelect && parentSelect.selectedOptions.length) {
                parentUrl = parentSelect.selectedOptions[0].dataset.url || '';
            }

            parentUrl = parentUrl.replace(/^\/+|\/+$/g, '');

            if (parentUrl) {
                preview.textContent = '/' + parentUrl + '/' + slug;
            } else {
                preview.textContent = '/' + slug;
            }
        }

        if (parentSelect) {
            parentSelect.addEventListener('change', updatePreview);
        }

        if (slugInput) {
            slugInput.addEventListener('input', updatePreview);
        }

        updatePreview();
    });
</script>
@endpush
