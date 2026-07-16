@php
    /** @var \App\Models\Career $job */
@endphp
<article class="job-card" id="{{ $job->slug }}">
    <h2 @include('partials.inline-edit-attrs', ['model' => 'career', 'id' => $job->id, 'field' => 'title', 'type' => 'text'])>{{ $job->title }}</h2>
    <p class="job-meta">
        <span @include('partials.inline-edit-attrs', ['model' => 'career', 'id' => $job->id, 'field' => 'location', 'type' => 'text'])>{{ $job->location ?: 'Location TBD' }}</span>
        @if ($job->employment_type)
            <span aria-hidden="true"> &middot; </span>
            <span @include('partials.inline-edit-attrs', ['model' => 'career', 'id' => $job->id, 'field' => 'employment_type', 'type' => 'text'])>{{ $job->employment_type }}</span>
        @endif
    </p>
    @if ($job->posted_at || $job->closes_at)
        <p class="job-meta">
            @if ($job->posted_at)Posted {{ $job->posted_at->format('F j, Y') }}@endif
            @if ($job->closes_at) &middot; Closes {{ $job->closes_at->format('F j, Y') }}@endif
        </p>
    @endif
    @auth
        <div class="t-prose-content" @include('partials.inline-edit-attrs', ['model' => 'career', 'id' => $job->id, 'field' => 'body', 'type' => 'richtext'])>
            @include('partials.cms-body', ['html' => $job->body])
        </div>
    @else
        <div class="t-prose-content">@include('partials.cms-body', ['html' => $job->body])</div>
    @endauth
</article>
