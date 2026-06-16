@extends('admin.layout')

@section('title', 'Quick Website Edits')
@section('page_title', 'Quick Website Edits')
@section('page_subtitle', 'Edit headings and short text on the live website. Use Pages in the CMS for full content and settings.')

@section('content')
    <div class="admin-card admin-card--spaced">
        <h2>How inline editing works</h2>
        <ol class="admin-steps-list">
            <li>Open the public website while logged in as an admin.</li>
            <li>Click <strong>Enable Inline Editing</strong> in the admin bar.</li>
            <li>Click highlighted text (orange dashed outline).</li>
            <li>Make your change and click <strong>Save</strong>.</li>
            <li>Use <strong>Edit This Page in CMS</strong> for full page content, files, and settings.</li>
        </ol>
        <div class="form-actions">
            <a href="{{ route('home') }}" target="_blank" rel="noopener" class="btn btn-primary">Open Website</a>
        </div>
    </div>

    <div class="admin-grid-two">
        <div class="admin-card">
            <h2>What you can edit inline</h2>
            <ul class="admin-inline-list">
                <li>Page headings and intro text</li>
                <li>Homepage title and summary</li>
                <li>Portfolio project titles and descriptions</li>
                <li>News post titles and excerpts</li>
                <li>Board member names, roles, and bios</li>
                <li>Selected portfolio images</li>
            </ul>
        </div>

        <div class="admin-card">
            <h2>Edit in CMS Pages instead</h2>
            <ul class="admin-inline-list">
                <li>Full page body content (TinyMCE)</li>
                <li>Page URL and parent page</li>
                <li>Draft / publish status</li>
                <li>Website menu settings</li>
                <li>PDF and file uploads</li>
                <li>Navigation and page structure</li>
            </ul>
            <div class="form-actions">
                <a href="{{ route('admin.pages.index') }}" class="btn btn-light">Go to Pages</a>
            </div>
        </div>
    </div>

    @php
        $publishedPages = \App\Models\Page::published()->orderBy('title')->get();
    @endphp

    <div class="admin-table-card">
        <div class="admin-table-card-head">
            <h2>Published pages — open and edit inline</h2>
            <p class="admin-table-card-subtitle">Headings and intro text on these pages support inline editing when enabled on the public site.</p>
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
                                    <a href="{{ $publishedPage->url() }}" target="_blank" rel="noopener" class="btn btn-light btn-sm">Open page</a>
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
@endsection
