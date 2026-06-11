@extends('admin.layout')

@section('title', 'Website Pages')

@section('content')
    <div class="admin-page-header">
        <div>
            <h1>Website Pages</h1>
            <p class="admin-help">Manage built-in pages and create custom pages for future content.</p>
        </div>

        <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">Add Custom Page</a>
    </div>

    <div class="admin-card admin-card--spaced">
        <h2>Built-in Website Pages</h2>
        <p class="admin-help">
            These pages use pre-designed layouts. You can safely update text, headings, and content sections.
        </p>

        <table class="admin-table">
            <thead>
                <tr>
                    <th>Page Title</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Page URL</th>
                    <th>Last Edited</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($builtInPages as $page)
                    <tr>
                        <td>{{ $page->title }}</td>
                        <td><span class="badge badge-blue">Built-in</span></td>
                        <td><span class="badge badge-green">Published</span></td>
                        <td><code>{{ $page->full_url }}</code></td>
                        <td>{{ $page->updated_at?->format('M d, Y') }}</td>
                        <td class="admin-actions">
                            <a href="{{ $page->url() }}" target="_blank" rel="noopener" class="btn btn-sm">View</a>
                            <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-sm btn-primary">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">No built-in pages found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="admin-card">
        <h2>Custom Pages</h2>
        <p class="admin-help">
            Create new pages using the standard website template. Use this for annual reports, policies, resources, and future content.
        </p>

        <table class="admin-table">
            <thead>
                <tr>
                    <th>Page Title</th>
                    <th>Status</th>
                    <th>Page URL</th>
                    <th>Navigation</th>
                    <th>Last Edited</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($customPages as $page)
                    <tr>
                        <td>{{ $page->title }}</td>
                        <td>
                            @if ($page->status === 'published')
                                <span class="badge badge-green">Published</span>
                            @else
                                <span class="badge badge-gray">Draft</span>
                            @endif
                        </td>
                        <td><code>{{ $page->full_url }}</code></td>
                        <td>{{ $page->show_in_navigation ? 'Shown' : 'Hidden' }}</td>
                        <td>{{ $page->updated_at?->format('M d, Y') }}</td>
                        <td class="admin-actions">
                            @if ($page->status === 'published')
                                <a href="{{ $page->url() }}" target="_blank" rel="noopener" class="btn btn-sm">View</a>

                                <form method="post" action="{{ route('admin.pages.unpublish', $page) }}" class="admin-inline-form">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm">Unpublish</button>
                                </form>
                            @else
                                <form method="post" action="{{ route('admin.pages.publish', $page) }}" class="admin-inline-form">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-primary">Publish</button>
                                </form>
                            @endif

                            <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-sm btn-primary">Edit</a>

                            <form method="post" action="{{ route('admin.pages.destroy', $page) }}" class="admin-inline-form" onsubmit="return confirm('Delete this custom page?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">No custom pages created yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
