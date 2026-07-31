@php
    $structuredPages = [
        'board' => [
            'title' => 'Board member photos & bios',
            'description' => 'Director headshots, names, roles, and bios are managed separately from the intro text below. Use this panel to change photos or add/remove members.',
            'manage_route' => 'admin.board.index',
            'create_route' => 'admin.board.create',
            'create_label' => 'Add board member',
            'manage_label' => 'Manage all board members',
        ],
        'news' => [
            'title' => 'News articles',
            'description' => 'Individual news posts (title, image, body) are managed in the News section. The editor below is only for the page intro above the news list.',
            'manage_route' => 'admin.news.index',
            'create_route' => 'admin.news.create',
            'create_label' => 'Add news post',
            'manage_label' => 'Manage news posts',
        ],
        'careers' => [
            'title' => 'Job listings',
            'description' => 'Open positions are managed in Careers. The editor below is for the intro text above the job list.',
            'manage_route' => 'admin.careers.index',
            'create_route' => 'admin.careers.create',
            'create_label' => 'Add job posting',
            'manage_label' => 'Manage careers',
        ],
        'portfolio' => [
            'title' => 'Portfolio projects',
            'description' => 'Project images and descriptions are managed in Portfolio. The editor below is for the intro text above the project grid.',
            'manage_route' => 'admin.portfolio.index',
            'create_route' => 'admin.portfolio.create',
            'create_label' => 'Add portfolio item',
            'manage_label' => 'Manage portfolio',
        ],
    ];

    $structured = $structuredPages[$page->slug] ?? null;
    $boardMembers = $boardMembers ?? collect();
@endphp

@if ($page->exists && $structured)
    <div class="cms-step-card cms-step-card--highlight">
        <div class="cms-step-header">
            <span class="cms-step-number">{{ $step ?? 0 }}</span>
            <div>
                <h2>{{ $structured['title'] }}</h2>
                <p>{{ $structured['description'] }}</p>
            </div>
        </div>

        @if ($page->slug === 'board' && $boardMembers->isNotEmpty())
            <div class="cms-board-preview-grid">
                @foreach ($boardMembers as $member)
                    <div class="cms-board-preview-card">
                        @if ($member->photoUrl())
                            <img src="{{ $member->photoUrl() }}" alt="{{ $member->name }}" class="cms-board-preview-photo">
                        @else
                            <div class="cms-board-preview-photo cms-board-preview-photo--empty">No photo</div>
                        @endif
                        <div class="cms-board-preview-meta">
                            <strong>{{ $member->name }}</strong>
                            <span>{{ $member->role }}</span>
                        </div>
                        <a href="{{ route('admin.board.edit', $member) }}" class="btn btn-sm btn-primary">Edit photo &amp; bio</a>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="cms-inline-action">
            <a href="{{ route($structured['create_route']) }}" class="btn btn-sm btn-light">{{ $structured['create_label'] }}</a>
            <a href="{{ route($structured['manage_route']) }}" class="btn btn-sm btn-primary">{{ $structured['manage_label'] }}</a>
        </div>

        <p class="admin-help">
            Tip: On the live site, use <strong>Open Website Editor</strong> and click a board photo to replace it instantly, or click names and bios to edit inline.
        </p>
    </div>
@endif
