@extends('admin.layout')

@section('title', 'Website Pages')
@section('page_title', 'Website Pages')
@section('page_subtitle', 'Edit existing pages or create new custom pages.')

@section('content')
    <div class="admin-page-actions admin-page-actions--split">
        <div class="admin-page-search">
            <label class="form-label" for="page-search">Find a page</label>
            <input type="search" id="page-search" class="form-control" placeholder="Search by page name or URL..." autocomplete="off">
        </div>
        <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">Add Custom Page</a>
    </div>

    <div class="admin-table-card" data-page-table="built-in">
        <div class="admin-table-card-head">
            <h2>Built-in website pages</h2>
            <p class="admin-table-card-subtitle">Click a page name or Edit to update content on existing website pages.</p>
        </div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Page title</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Page URL</th>
                        <th>Last edited</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($builtInPages as $page)
                        <tr data-page-row data-search="{{ strtolower($page->title.' '.$page->full_url) }}">
                            <td>
                                <a href="{{ route('admin.pages.edit', $page) }}" class="admin-page-title-link">{{ $page->title }}</a>
                            </td>
                            <td><span class="admin-badge admin-badge-blue">Built-in</span></td>
                            <td><span class="admin-badge admin-badge-success">Published</span></td>
                            <td><code>{{ $page->full_url }}</code></td>
                            <td>{{ $page->updated_at?->format('M d, Y') }}</td>
                            <td>
                                <div class="admin-row-actions">
                                    <a href="{{ $page->url() }}" target="_blank" rel="noopener" class="btn btn-light btn-sm">View</a>
                                    <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-primary btn-sm">Edit</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="admin-table-empty">No built-in pages found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="admin-table-card" data-page-table="custom">
        <div class="admin-table-card-head">
            <h2>Custom pages</h2>
            <p class="admin-table-card-subtitle">Annual reports, policies, resources, and new content.</p>
        </div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Page title</th>
                        <th>Status</th>
                        <th>Page URL</th>
                        <th>Navigation</th>
                        <th>Last edited</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customPages as $page)
                        <tr data-page-row data-search="{{ strtolower($page->title.' '.$page->full_url) }}">
                            <td>
                                <a href="{{ route('admin.pages.edit', $page) }}" class="admin-page-title-link">{{ $page->title }}</a>
                            </td>
                            <td>
                                @if ($page->status === 'published')
                                    <span class="admin-badge admin-badge-success">Published</span>
                                @else
                                    <span class="admin-badge admin-badge-muted">Draft</span>
                                @endif
                            </td>
                            <td><code>{{ $page->full_url }}</code></td>
                            <td>{{ $page->show_in_navigation ? 'Shown' : 'Hidden' }}</td>
                            <td>{{ $page->updated_at?->format('M d, Y') }}</td>
                            <td>
                                <div class="admin-row-actions">
                                    @if ($page->status === 'published')
                                        <a href="{{ $page->url() }}" target="_blank" rel="noopener" class="btn btn-light btn-sm">View</a>
                                        <form method="post" action="{{ route('admin.pages.unpublish', $page) }}" class="admin-inline-form">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-outline btn-sm">Unpublish</button>
                                        </form>
                                    @else
                                        <form method="post" action="{{ route('admin.pages.publish', $page) }}" class="admin-inline-form">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-primary btn-sm">Publish</button>
                                        </form>
                                    @endif
                                    <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-primary btn-sm">Edit</a>
                                    <form method="post" action="{{ route('admin.pages.destroy', $page) }}" class="admin-inline-form" onsubmit="return confirm('Delete this custom page?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="admin-table-empty">No custom pages created yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <p class="admin-table-empty hidden" id="page-search-empty">No pages match your search.</p>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const search = document.getElementById('page-search');
        const rows = document.querySelectorAll('[data-page-row]');
        const emptyMessage = document.getElementById('page-search-empty');

        if (!search) {
            return;
        }

        search.addEventListener('input', function () {
            const term = search.value.trim().toLowerCase();
            let visible = 0;

            rows.forEach(function (row) {
                const haystack = row.dataset.search || '';
                const match = term === '' || haystack.includes(term);
                row.style.display = match ? '' : 'none';
                if (match) {
                    visible++;
                }
            });

            if (emptyMessage) {
                emptyMessage.classList.toggle('hidden', visible > 0 || term === '');
            }
        });
    });
</script>
@endpush
