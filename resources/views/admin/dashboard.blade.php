@extends('admin.layout')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Overview of website content and CMS activity.')

@section('content')
    <div class="dashboard-grid">
        <a href="{{ route('admin.pages.index') }}" class="dashboard-stat-card dashboard-stat-card--link">
            <span class="stat-label">Total Pages</span>
            <strong>{{ $totalPages ?? 0 }}</strong>
            <p>All published and draft CMS pages</p>
        </a>

        <div class="dashboard-stat-card">
            <span class="stat-label">Published Pages</span>
            <strong>{{ $publishedPages ?? 0 }}</strong>
            <p>Pages visible on the website</p>
        </div>

        <div class="dashboard-stat-card">
            <span class="stat-label">Draft Pages</span>
            <strong>{{ $draftPages ?? 0 }}</strong>
            <p>Saved but not yet published</p>
        </div>

        <div class="dashboard-stat-card">
            <span class="stat-label">Uploaded Files</span>
            <strong>{{ $uploadedFiles ?? 0 }}</strong>
            <p>PDFs and documents in the media library</p>
        </div>

        <div class="dashboard-stat-card">
            <span class="stat-label">News Posts</span>
            <strong>{{ $newsCount ?? 0 }}</strong>
            <p>News and announcements</p>
        </div>

        <div class="dashboard-stat-card">
            <span class="stat-label">Careers</span>
            <strong>{{ $careerCount ?? 0 }}</strong>
            <p>Current job postings</p>
        </div>
    </div>

    <div class="dashboard-actions">
        <div class="admin-card">
            <h2>Quick Actions</h2>
            <p>Create and manage the most common website content.</p>

            <div class="quick-action-grid">
                <a href="{{ route('admin.pages.index') }}" class="quick-action-card">
                    <strong>Edit Pages</strong>
                    <span>Update existing website pages and custom content.</span>
                </a>

                <a href="{{ route('admin.pages.create') }}" class="quick-action-card">
                    <strong>Add Custom Page</strong>
                    <span>Create a new page inside the website template.</span>
                </a>

                <a href="{{ route('admin.media.create') }}" class="quick-action-card">
                    <strong>Upload PDF</strong>
                    <span>Add Annual Reports or documents.</span>
                </a>

                <a href="{{ route('admin.news.create') }}" class="quick-action-card">
                    <strong>Add News</strong>
                    <span>Publish an update or announcement.</span>
                </a>

                <a href="{{ route('home') }}" target="_blank" rel="noopener" class="quick-action-card">
                    <strong>View Website</strong>
                    <span>Open the public THLIN website.</span>
                </a>
            </div>
        </div>

        <div class="admin-card">
            <h2>Editing Help</h2>
            <p>
                To edit public page text directly, open the website while logged in and use inline editing where available.
                For full page editing with the content editor, use <strong>Edit This Page</strong> on the website or choose a page below.
            </p>

            <a href="{{ route('home') }}" target="_blank" rel="noopener" class="btn btn-primary">
                Open Website
            </a>
        </div>
    </div>

    @if ($recentPages->isNotEmpty())
        <div class="admin-card admin-card--spaced-top">
            <h2>Edit your pages</h2>
            <p>Recently updated pages — open any page to edit content, headings, and settings.</p>

            <div class="admin-page-edit-list">
                @foreach ($recentPages as $recentPage)
                    <div class="admin-page-edit-item">
                        <div>
                            <strong>{{ $recentPage->title }}</strong>
                            <span>{{ $recentPage->full_url }}</span>
                        </div>
                        <div class="admin-row-actions">
                            @if ($recentPage->status === 'published')
                                <a href="{{ $recentPage->url() }}" target="_blank" rel="noopener" class="btn btn-light btn-sm">View</a>
                            @endif
                            <a href="{{ route('admin.pages.edit', $recentPage) }}" class="btn btn-primary btn-sm">Edit</a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="form-actions" style="margin-top: 20px; padding-top: 0;">
                <a href="{{ route('admin.pages.index') }}" class="btn btn-outline">View all pages</a>
            </div>
        </div>
    @endif
@endsection
