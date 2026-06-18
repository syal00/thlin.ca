@extends('admin.layout')

@section('title', 'Quick Website Edits')
@section('page_title', 'Quick Website Edits')
@section('page_subtitle', 'Edit headings and short text on the live website. Use Pages in the CMS for full content and settings.')

@section('content')
    <div class="admin-card admin-card--spaced">
        <h2>How inline editing works</h2>
        <p>Inline editing now supports:</p>
        <ul class="admin-inline-list">
            <li><strong>Simple text editing</strong> for headings, buttons, and navigation labels (Save / Cancel only).</li>
            <li><strong>Rich text editing</strong> for paragraphs and content areas with a floating WYSIWYG toolbar.</li>
            <li>Formatting tools including bold, italic, underline, strikethrough, lists, alignment, links, tables, colors, character map, code view, search/replace, visual blocks, and fullscreen for long content.</li>
            <li>Save / Cancel controls, keyboard shortcuts (Ctrl/Cmd+S to save, Escape to cancel), and unsaved-changes warning when leaving the page.</li>
        </ul>
        <p>For page URL, parent page, publishing, file uploads, and full page management, use the <strong>CMS Pages</strong> section.</p>
        <ol class="admin-steps-list">
            <li>Open the public website while logged in as an admin.</li>
            <li>Click <strong>Enable Inline Editing</strong> in the admin bar, or use the link below to start with editing enabled.</li>
            <li>Click highlighted text (orange dashed outline).</li>
            <li>Make your change and click <strong>Save</strong>. The toolbar stays above or below the content so it does not cover your text.</li>
            <li>Use <strong>Edit This Page in CMS</strong> for page structure, files, drafts, and navigation settings.</li>
        </ol>
        <div class="form-actions">
            <a href="{{ route('home', ['edit' => 1]) }}" target="_blank" rel="noopener" class="btn btn-primary">Open Website Editor</a>
        </div>
    </div>

    <div class="admin-grid-two">
        <div class="admin-card">
            <h2>What you can edit inline</h2>
            <ul class="admin-inline-list">
                <li><strong>Pages</strong> — hero title (simple), hero subtitle and body (rich text), navigation labels (simple)</li>
                <li><strong>Homepage</strong> — section headings (simple), paragraphs and CTA text (rich text) via site settings</li>
                <li><strong>Site settings</strong> — navigation labels, footer text, global and page CTAs, contact form and office details</li>
                <li><strong>News</strong> — post title (simple), excerpt and body (rich text)</li>
                <li><strong>Careers</strong> — job title, location, employment type (simple), job description body (rich text)</li>
                <li><strong>Board</strong> — member name and role (simple), bio (rich text)</li>
                <li><strong>Portfolio</strong> — project title (simple), excerpt (rich text), and project images</li>
            </ul>
        </div>

        <div class="admin-card">
            <h2>Edit in CMS Pages instead</h2>
            <ul class="admin-inline-list">
                <li>Page URL and parent page</li>
                <li>Draft / publish status</li>
                <li>Website menu visibility and order</li>
                <li>PDF and file uploads</li>
                <li>Navigation structure and custom page templates</li>
                <li>News publish dates, locations, and new posts</li>
                <li>Career posting dates and new job listings</li>
                <li>Board member photos and new members</li>
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
                                    <a href="{{ $publishedPage->url() }}?edit=1" target="_blank" rel="noopener" class="btn btn-light btn-sm">Open editor</a>
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
