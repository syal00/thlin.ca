<div class="admin-table-card">
    <div class="admin-table-card-head">
        <h2>Published pages — open and edit inline</h2>
        <p class="admin-table-card-subtitle">Headings, body content, and site settings on these pages support inline editing when enabled on the public site.</p>
    </div>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Page</th>
                    <th>Public URL</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($publishedPages as $publishedPage)
                    <tr>
                        <td>{{ $publishedPage->title }}</td>
                        <td><code>{{ $publishedPage->full_url }}</code></td>
                        <td>
                            <div class="admin-row-actions">
                                <a href="{{ $publishedPage->url() }}?edit=1" target="_blank" rel="noopener" class="btn btn-light btn-sm" data-admin-open-editor>Open editor</a>
                                <a href="{{ route('admin.pages.edit', $publishedPage) }}" class="btn btn-primary btn-sm">Edit in CMS</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="admin-table-empty">No published pages found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
