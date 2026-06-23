@extends('admin.layout')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Overview of website content and CMS activity.')

@section('content')
    <section class="dash-welcome" aria-label="Welcome">
        <div class="dash-welcome-copy">
            <p class="dash-welcome-eyebrow">Welcome back</p>
            <h2>Hi {{ auth()->user()->name ?: 'there' }}, your site is ready to edit.</h2>
            <p>Update pages, publish news, review messages, and manage files from one workspace.</p>
        </div>
        <div class="dash-welcome-actions">
            <a href="{{ url('/?edit=1') }}" target="_blank" rel="noopener" class="btn btn-light">Open live editor</a>
            <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">Create custom page</a>
        </div>
    </section>

    <div class="dashboard-grid">
        <a href="{{ route('admin.pages.index') }}" class="dashboard-stat-card dashboard-stat-card--link dashboard-stat-card--blue">
            <span class="dashboard-stat-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            </span>
            <div>
                <span class="stat-label">Total Pages</span>
                <strong>{{ $totalPages ?? 0 }}</strong>
                <p>Published and draft CMS pages</p>
            </div>
        </a>

        <div class="dashboard-stat-card dashboard-stat-card--green">
            <span class="dashboard-stat-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </span>
            <div>
                <span class="stat-label">Published</span>
                <strong>{{ $publishedPages ?? 0 }}</strong>
                <p>Live on the public website</p>
            </div>
        </div>

        <div class="dashboard-stat-card dashboard-stat-card--amber">
            <span class="dashboard-stat-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
            </span>
            <div>
                <span class="stat-label">Drafts</span>
                <strong>{{ $draftPages ?? 0 }}</strong>
                <p>Saved but not published yet</p>
            </div>
        </div>

        <a href="{{ route('admin.messages.index') }}" class="dashboard-stat-card dashboard-stat-card--link dashboard-stat-card--violet">
            <span class="dashboard-stat-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            </span>
            <div>
                <span class="stat-label">Messages</span>
                <strong>{{ $messageCount ?? 0 }}</strong>
                <p>Contact form submissions</p>
            </div>
        </a>

        <a href="{{ route('admin.news.index') }}" class="dashboard-stat-card dashboard-stat-card--link dashboard-stat-card--blue">
            <span class="dashboard-stat-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/></svg>
            </span>
            <div>
                <span class="stat-label">News Posts</span>
                <strong>{{ $newsCount ?? 0 }}</strong>
                <p>Announcements and updates</p>
            </div>
        </a>

        <a href="{{ route('admin.media.index') }}" class="dashboard-stat-card dashboard-stat-card--link dashboard-stat-card--slate">
            <span class="dashboard-stat-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
            </span>
            <div>
                <span class="stat-label">Uploaded Files</span>
                <strong>{{ $uploadedFiles ?? 0 }}</strong>
                <p>PDFs and media library assets</p>
            </div>
        </a>
    </div>

    <div class="dashboard-layout">
        <div class="dashboard-layout-main">
            <div class="admin-card">
                <div class="admin-card-head">
                    <div>
                        <h2>Quick actions</h2>
                        <p>Jump straight into the tasks you use most.</p>
                    </div>
                </div>

                <div class="quick-action-grid">
                    <a href="{{ route('admin.pages.index') }}" class="quick-action-card">
                        <span class="quick-action-icon" aria-hidden="true">01</span>
                        <strong>Edit pages</strong>
                        <span>Update built-in and custom website content.</span>
                    </a>

                    <a href="{{ route('admin.pages.create') }}" class="quick-action-card">
                        <span class="quick-action-icon" aria-hidden="true">02</span>
                        <strong>Add custom page</strong>
                        <span>Create a new page in the site template.</span>
                    </a>

                    <a href="{{ route('admin.media.create') }}" class="quick-action-card">
                        <span class="quick-action-icon" aria-hidden="true">03</span>
                        <strong>Upload PDF</strong>
                        <span>Add annual reports or downloadable files.</span>
                    </a>

                    <a href="{{ route('admin.news.create') }}" class="quick-action-card">
                        <span class="quick-action-icon" aria-hidden="true">04</span>
                        <strong>Publish news</strong>
                        <span>Share an update or announcement.</span>
                    </a>

                    <a href="{{ route('admin.careers.index') }}" class="quick-action-card">
                        <span class="quick-action-icon" aria-hidden="true">05</span>
                        <strong>Manage careers</strong>
                        <span>{{ $careerCount ?? 0 }} active job {{ ($careerCount ?? 0) === 1 ? 'posting' : 'postings' }}.</span>
                    </a>

                    <a href="{{ route('admin.inline-editing') }}" class="quick-action-card">
                        <span class="quick-action-icon" aria-hidden="true">06</span>
                        <strong>Inline editing guide</strong>
                        <span>Learn how to edit text directly on the site.</span>
                    </a>
                </div>
            </div>

            @if ($recentPages->isNotEmpty())
                <div class="admin-card admin-card--spaced-top">
                    <div class="admin-card-head">
                        <div>
                            <h2>Recently updated pages</h2>
                            <p>Open any page to edit content, headings, and SEO settings.</p>
                        </div>
                        <a href="{{ route('admin.pages.index') }}" class="btn btn-outline btn-sm">View all</a>
                    </div>

                    <div class="admin-page-edit-list">
                        @foreach ($recentPages as $recentPage)
                            <div class="admin-page-edit-item">
                                <div class="admin-page-edit-meta">
                                    <strong>{{ $recentPage->title }}</strong>
                                    <span>{{ $recentPage->full_url }}</span>
                                    <time datetime="{{ $recentPage->updated_at?->toIso8601String() }}">
                                        Updated {{ $recentPage->updated_at?->diffForHumans() }}
                                    </time>
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
                </div>
            @endif
        </div>

        <aside class="dashboard-layout-aside">
            <div class="admin-card admin-card--tip">
                <h2>Editing on the live site</h2>
                <p>
                    While signed in, open the public website and click editable text where inline editing is enabled.
                    For full page control, use <strong>Edit This Page</strong> or open a page from the list.
                </p>
                <ul class="admin-tip-list">
                    <li>Inline edits save instantly for supported fields.</li>
                    <li>Use the page editor for layout, SEO, and rich content.</li>
                    <li>Preview changes before publishing draft pages.</li>
                </ul>
                <a href="{{ url('/?edit=1') }}" target="_blank" rel="noopener" class="btn btn-primary">
                    Open website editor
                </a>
            </div>

            <div class="admin-card admin-card--compact">
                <h2>Content snapshot</h2>
                <dl class="admin-snapshot-list">
                    <div>
                        <dt>Careers</dt>
                        <dd>{{ $careerCount ?? 0 }}</dd>
                    </div>
                    <div>
                        <dt>News posts</dt>
                        <dd>{{ $newsCount ?? 0 }}</dd>
                    </div>
                    <div>
                        <dt>Messages</dt>
                        <dd>{{ $messageCount ?? 0 }}</dd>
                    </div>
                    <div>
                        <dt>Draft pages</dt>
                        <dd>{{ $draftPages ?? 0 }}</dd>
                    </div>
                </dl>
            </div>
        </aside>
    </div>
@endsection
