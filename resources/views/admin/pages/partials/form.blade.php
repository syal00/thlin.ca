@php
    $isCustomForm = $page->isCustom() || ! $page->exists;
    $parentPageGroups = $parentPageGroups ?? [
        'main' => collect(),
        'other' => collect(),
        'custom' => collect($parentPages ?? []),
        'current' => collect(),
    ];
    $selectedParentId = old('parent_id', $page->parent_id);
    $step = 0;
@endphp

<form method="post" action="{{ $action }}" class="admin-form cms-form">
    @csrf

    @if ($method !== 'POST')
        @method($method)
    @endif

    {{-- Step 1: Basic Page Information --}}
    <div class="cms-step-card">
        <div class="cms-step-header">
            <span class="cms-step-number">{{ ++$step }}</span>
            <div>
                <h2>Basic Page Information</h2>
                <p>Start with the page name and where it should live on the website.</p>
            </div>
        </div>

        <div class="form-group">
            <label for="title">Page Name <span class="required" aria-hidden="true">*</span></label>
            <input type="text" id="title" name="title" value="{{ old('title', $page->title) }}" required>
            <small>This is the main name of the page. Example: Annual Reports.</small>
            @error('title')
                <small class="form-error">{{ $message }}</small>
            @enderror
        </div>

        @if ($isCustomForm)
            <div class="form-group">
                <label for="parent_id">Where should this page go?</label>
                <select id="parent_id" name="parent_id">
                    <option value="" data-url="" @selected($selectedParentId === null || $selectedParentId === '')>Main page / No section</option>

                    @if ($parentPageGroups['current']->isNotEmpty())
                        <optgroup label="Current Selection">
                            @foreach ($parentPageGroups['current'] as $parentPage)
                                <option value="{{ $parentPage->id }}" data-url="{{ $parentPage->full_url }}"
                                    @selected((string) $selectedParentId === (string) $parentPage->id)>
                                    {{ $parentPage->title }} — {{ $parentPage->full_url }}
                                </option>
                            @endforeach
                        </optgroup>
                    @endif

                    @if ($parentPageGroups['main']->isNotEmpty())
                        <optgroup label="Main Sections">
                            @foreach ($parentPageGroups['main'] as $parentPage)
                                <option value="{{ $parentPage->id }}" data-url="{{ $parentPage->full_url }}"
                                    @selected((string) $selectedParentId === (string) $parentPage->id)>
                                    {{ $parentPage->title }} — {{ $parentPage->full_url }}
                                </option>
                            @endforeach
                        </optgroup>
                    @endif

                    @if ($parentPageGroups['other']->isNotEmpty())
                        <optgroup label="Other Pages">
                            @foreach ($parentPageGroups['other'] as $parentPage)
                                <option value="{{ $parentPage->id }}" data-url="{{ $parentPage->full_url }}"
                                    @selected((string) $selectedParentId === (string) $parentPage->id)>
                                    {{ $parentPage->title }} — {{ $parentPage->full_url }}
                                </option>
                            @endforeach
                        </optgroup>
                    @endif

                    @if ($parentPageGroups['custom']->isNotEmpty())
                        <optgroup label="Custom Pages">
                            @foreach ($parentPageGroups['custom'] as $parentPage)
                                <option value="{{ $parentPage->id }}" data-url="{{ $parentPage->full_url }}"
                                    @selected((string) $selectedParentId === (string) $parentPage->id)>
                                    {{ $parentPage->title }} — {{ $parentPage->full_url }}
                                </option>
                            @endforeach
                        </optgroup>
                    @endif
                </select>
                <small>Choose a website section for this page. Leave it blank if this should be a main page.</small>
                @error('parent_id')
                    <small class="form-error">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="slug">Page Link <span class="required" aria-hidden="true">*</span></label>
                <div class="input-prefix-group">
                    <span class="input-prefix">/</span>
                    <input type="text" id="slug" name="slug" value="{{ old('slug', $page->slug) }}" placeholder="annual-reports" required>
                </div>
                <small>Use a short link name with no spaces. Example: annual-reports or testing.</small>
                <div class="url-preview-box">
                    <span>Page will open at</span>
                    <strong id="final-url-preview">/{{ old('slug', $page->slug ?: 'your-page-link') }}</strong>
                </div>
                @error('slug')
                    <small class="form-error">{{ $message }}</small>
                @enderror
            </div>
        @else
            <div class="form-group">
                <label>Page Link</label>
                <div class="url-preview-box url-preview-box--static">
                    <span>Page opens at</span>
                    <strong>{{ $page->full_url }}</strong>
                </div>
                <small>Built-in page links are fixed to protect the site layout.</small>
            </div>
        @endif
    </div>

    {{-- Step 2: Page Header --}}
    <div class="cms-step-card">
        <div class="cms-step-header">
            <span class="cms-step-number">{{ ++$step }}</span>
            <div>
                <h2>Page Header</h2>
                <p>The large heading and intro text shown at the top of the public page.</p>
            </div>
        </div>

        <div class="form-group">
            <label for="hero_title">
                Large Page Heading
                <span class="optional-label">Optional</span>
            </label>
            <input type="text" id="hero_title" name="hero_title" value="{{ old('hero_title', $page->hero_title) }}" placeholder="Leave blank to use the Page Name">
            <small>This appears at the top of the public page. Leave blank to use the Page Name.</small>
            @error('hero_title')
                <small class="form-error">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="hero_subtitle">
                Short Intro Text
                <span class="optional-label">Optional</span>
            </label>
            <textarea id="hero_subtitle" name="hero_subtitle" rows="2" placeholder="A short sentence for visitors">{{ old('hero_subtitle', $page->hero_subtitle) }}</textarea>
            <small>A short sentence shown under the page heading.</small>
            @error('hero_subtitle')
                <small class="form-error">{{ $message }}</small>
            @enderror
        </div>

        @if ($page->isBuiltIn() && $page->exists)
            <div class="form-group">
                <label for="excerpt">
                    Summary Text
                    <span class="optional-label">Optional</span>
                </label>
                <textarea id="excerpt" name="excerpt" rows="3">{{ old('excerpt', $page->excerpt) }}</textarea>
                <small>Used in search results and page summaries.</small>
                @error('excerpt')
                    <small class="form-error">{{ $message }}</small>
                @enderror
            </div>
        @endif
    </div>

    {{-- Step 3: Main Page Content --}}
    <div class="cms-step-card">
        <div class="cms-step-header">
            <span class="cms-step-number">{{ ++$step }}</span>
            <div>
                <h2>Main Page Content</h2>
                <p>Add the main text, headings, links, images, and PDF links for this page.</p>
            </div>
        </div>

        <p class="cms-editor-note admin-help">
            The editor runs locally on your site — no Tiny Cloud account or domain registration required.
        </p>

        <div class="form-group">
            <label for="page-content-editor">Main Page Content</label>
            <textarea id="page-content-editor" name="body" rows="12">{!! old('body', $page->body ?? '') !!}</textarea>
            <small>Add the main text, headings, links, images, and PDF links for this page.</small>
            @error('body')
                <small class="form-error">{{ $message }}</small>
            @enderror
        </div>

        <p class="cms-inline-action">
            <a href="{{ route('admin.media.index') }}" target="_blank" rel="noopener" class="btn btn-sm btn-light">
                Open PDF/File Library
            </a>
        </p>
    </div>

    {{-- Step 4: SEO Settings --}}
    <div class="cms-step-card">
        <div class="cms-step-header">
            <span class="cms-step-number">{{ ++$step }}</span>
            <div>
                <h2>SEO Settings</h2>
                <p>Control the page title, description, and search keywords.</p>
            </div>
        </div>

        <div class="form-group">
            <label for="meta_title">
                SEO Title
                <span class="optional-label">Optional</span>
            </label>
            <input type="text" id="meta_title" name="meta_title" value="{{ old('meta_title', $page->meta_title) }}" placeholder="Leave blank to use the page name">
            <small>Search engines and browser previews may use this title.</small>
            @error('meta_title')
                <small class="form-error">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="meta_description">
                SEO Description
                <span class="optional-label">Optional</span>
            </label>
            <textarea id="meta_description" name="meta_description" rows="3" placeholder="Brief description for search engines">{{ old('meta_description', $page->meta_description) }}</textarea>
            <small>A short description for search results and SEO. This is optional.</small>
            @error('meta_description')
                <small class="form-error">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="meta_keywords">
                SEO Keywords
                <span class="optional-label">Optional</span>
            </label>
            <textarea id="meta_keywords" name="meta_keywords" rows="2" placeholder="patient portals, health services, Ontario">{{ old('meta_keywords', $page->meta_keywords) }}</textarea>
            <small>Optional search keywords separated by commas.</small>
            @error('meta_keywords')
                <small class="form-error">{{ $message }}</small>
            @enderror
        </div>

        <div class="seo-preview-card">
            <span class="seo-preview-label">SEO Preview</span>
            <strong id="seo-preview-title">{{ $page->meta_title ?: $page->title }}</strong>
            <span id="seo-preview-url">{{ $page->exists ? $page->full_url : '/your-page-link' }}</span>
            <p id="seo-preview-description">{{ $page->meta_description ?: $page->hero_subtitle ?: 'Search preview description appears here.' }}</p>
        </div>
    </div>

    {{-- Step 5: Visibility Settings (custom pages only) --}}
    @if ($isCustomForm)
        <div class="cms-step-card">
            <div class="cms-step-header">
                <span class="cms-step-number">{{ ++$step }}</span>
                <div>
                    <h2>Visibility Settings</h2>
                    <p>Choose whether visitors can see this page and how it appears in the menu.</p>
                </div>
            </div>

            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="show_in_navigation" value="1" @checked((bool) old('show_in_navigation', $page->show_in_navigation))>
                    Show this page in the website menu
                </label>
                <small>Turn this on if visitors should see this page in the menu.</small>
            </div>

            <div class="form-group">
                <label for="navigation_label">
                    Menu Name
                    <span class="optional-label">Optional</span>
                </label>
                <input type="text" id="navigation_label" name="navigation_label" value="{{ old('navigation_label', $page->navigation_label) }}" placeholder="Example: Annual Reports">
                <small>This is the name shown in the menu. Leave blank to use the Page Name.</small>
            </div>

            <div class="form-group">
                <label for="sort_order">Menu Order</label>
                <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $page->sort_order ?? 0) }}">
                <small>Lower numbers appear first. Use 0 if you are not sure.</small>
            </div>

            <div class="form-group">
                <label>Current status</label>
                <p class="admin-help" style="margin: 0;">
                    @if ($page->status === 'published')
                        <span class="admin-badge admin-badge-success">Published</span>
                        This page is visible on the public website.
                    @else
                        <span class="admin-badge admin-badge-muted">Draft</span>
                        This page is saved but hidden from visitors.
                    @endif
                </p>
            </div>

            <p class="admin-help">Use <strong>Save Changes</strong> to keep the current status. Use Publish or Save Draft only when you want to change visibility.</p>
        </div>
    @endif

    {{-- Step 5: Useful Website Links --}}
    <div class="cms-step-card">
        <div class="cms-step-header">
            <span class="cms-step-number">{{ ++$step }}</span>
            <div>
                <h2>Useful Website Links</h2>
                <p>Use these links when adding buttons or links inside the page content.</p>
            </div>
        </div>

        <div class="form-group">
            <label for="link-search" class="visually-hidden">Search links</label>
            <input type="search" id="link-search" class="cms-link-search" placeholder="Search links..." autocomplete="off">
        </div>

        <div class="admin-link-grid cms-link-grid" id="cms-link-grid">
            @foreach ($publishedPages as $linkPage)
                <div class="admin-link-item cms-link-card"
                     data-link-title="{{ strtolower($linkPage->title) }}"
                     data-link-url="{{ strtolower($linkPage->full_url) }}">
                    <span class="cms-link-card-title">{{ $linkPage->title }}</span>
                    <div class="admin-link-copy">
                        <code>{{ $linkPage->full_url }}</code>
                        <button type="button" class="btn btn-sm admin-copy-link" data-copy="{{ $linkPage->full_url }}">Copy</button>
                    </div>
                </div>
            @endforeach
        </div>

        <p class="cms-no-links hidden" id="cms-no-links">No links match your search.</p>
    </div>

    <div class="cms-actions">
        <div class="cms-actions-inner">
            @if ($isCustomForm)
                <p class="cms-actions-help">Save your updates without changing visibility, or use Publish / Save Draft when needed.</p>
                <div class="cms-actions-buttons">
                    @if ($page->exists)
                        <a href="{{ route('admin.pages.preview', $page) }}" target="_blank" rel="noopener" class="admin-btn admin-btn-secondary">Preview</a>
                        <button type="submit" name="action" value="save" class="btn btn-primary">Save Changes</button>
                    @endif
                    <button type="submit" name="action" value="draft" class="btn btn-outline-secondary">Save Draft</button>
                    <button type="submit" name="action" value="publish" class="btn btn-primary">Publish to Website</button>
                    <a href="{{ route('admin.pages.index') }}" class="btn btn-light">Cancel</a>
                </div>
                @unless ($page->exists)
                    <p class="admin-help-text">Save the page first to enable preview.</p>
                @endunless
            @else
                <p class="cms-actions-help">Save your changes to update the live website content.</p>
                <div class="cms-actions-buttons">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="{{ route('admin.pages.index') }}" class="btn btn-light">Cancel</a>
                </div>
            @endif
        </div>
    </div>
</form>

@include('admin.partials.image-editor')
@push('scripts')
@include('admin.partials.tinymce-script')
<script>
    tinymce.init({
        selector: '#page-content-editor',
        license_key: 'gpl',
        base_url: @json(asset('vendor/tinymce')),
        suffix: '.min',
        height: 520,
        menubar: 'edit view insert format tools table help',
        plugins: [
            'accordion', 'advlist', 'anchor', 'autolink', 'autoresize',
            'charmap', 'code', 'codesample', 'directionality', 'emoticons', 'fullscreen',
            'help', 'image', 'importcss', 'insertdatetime', 'link', 'lists', 'media',
            'nonbreaking', 'pagebreak', 'preview', 'quickbars', 'searchreplace',
            'table', 'visualblocks', 'visualchars', 'wordcount',
        ],
        toolbar: [
            'undo redo | blocks | bold italic underline | bullist numlist',
            'link image imageedit media table | anchor charmap emoticons',
            'searchreplace | outdent indent | removeformat',
            'code preview fullscreen | help',
        ].join(' | '),
        quickbars_image_toolbar: 'imageedit alignleft aligncenter alignright',
        quickbars_selection_toolbar: false,
        branding: false,
        promotion: false,
        automatic_uploads: true,
        paste_data_images: true,
        relative_urls: false,
        remove_script_host: false,
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

                fetch('{{ route('admin.editor.upload-image') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
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
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        const cmsForm = document.querySelector('.cms-form');
        let cmsFormSubmitting = false;

        if (cmsForm) {
            cmsForm.addEventListener('submit', function (event) {
                if (cmsFormSubmitting) {
                    return;
                }

                if (typeof tinymce !== 'undefined') {
                    event.preventDefault();
                    tinymce.triggerSave();
                    cmsFormSubmitting = true;
                    cmsForm.submit();
                }
            });
        }

        const parentSelect = document.querySelector('select[name="parent_id"]');
        const slugInput = document.querySelector('input[name="slug"]');
        const preview = document.getElementById('final-url-preview');
        const seoTitleInput = document.getElementById('meta_title');
        const seoDescriptionInput = document.getElementById('meta_description');
        const seoPreviewTitle = document.getElementById('seo-preview-title');
        const seoPreviewUrl = document.getElementById('seo-preview-url');
        const seoPreviewDescription = document.getElementById('seo-preview-description');
        const linkSearch = document.getElementById('link-search');
        const linkGrid = document.getElementById('cms-link-grid');
        const noLinks = document.getElementById('cms-no-links');

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

            const slug = cleanSlug(slugInput.value || 'your-page-link');
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

            if (seoPreviewUrl) {
                seoPreviewUrl.textContent = preview.textContent;
            }

            if (seoPreviewTitle) {
                seoPreviewTitle.textContent = seoTitleInput && seoTitleInput.value.trim() ? seoTitleInput.value.trim() : (document.getElementById('title')?.value || 'Page');
            }

            if (seoPreviewDescription) {
                seoPreviewDescription.textContent = seoDescriptionInput && seoDescriptionInput.value.trim()
                    ? seoDescriptionInput.value.trim()
                    : (document.getElementById('hero_subtitle')?.value || 'Search preview description appears here.');
            }
        }

        if (parentSelect) {
            parentSelect.addEventListener('change', updatePreview);
        }

        if (slugInput) {
            slugInput.addEventListener('input', updatePreview);
        }

        if (seoTitleInput) {
            seoTitleInput.addEventListener('input', updatePreview);
        }

        if (seoDescriptionInput) {
            seoDescriptionInput.addEventListener('input', updatePreview);
        }

        updatePreview();

        if (linkSearch && linkGrid) {
            linkSearch.addEventListener('input', function () {
                const term = linkSearch.value.trim().toLowerCase();
                let visible = 0;

                linkGrid.querySelectorAll('.cms-link-card').forEach(function (card) {
                    const title = card.dataset.linkTitle || '';
                    const url = card.dataset.linkUrl || '';
                    const match = term === '' || title.includes(term) || url.includes(term);
                    card.classList.toggle('hidden', !match);
                    if (match) {
                        visible++;
                    }
                });

                if (noLinks) {
                    noLinks.classList.toggle('hidden', visible > 0);
                }
            });
        }

        document.querySelectorAll('.admin-copy-link').forEach(function (button) {
            button.addEventListener('click', function () {
                const value = button.dataset.copy || '';

                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(value);
                } else {
                    const input = document.createElement('input');
                    input.value = value;
                    document.body.appendChild(input);
                    input.select();
                    document.execCommand('copy');
                    document.body.removeChild(input);
                }

                button.textContent = 'Copied';
                setTimeout(function () {
                    button.textContent = 'Copy';
                }, 1500);
            });
        });
    });
</script>
@endpush
