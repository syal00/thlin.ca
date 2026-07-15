@php
    /** @var \App\Models\NewsPost $post */
    $newsImageUrl = $post->image
        ? (str_starts_with($post->image, 'http://') || str_starts_with($post->image, 'https://')
            ? $post->image
            : asset('storage/'.$post->image))
        : null;
@endphp
<li class="news-item">
    <div class="t-card-media">
        @if ($newsImageUrl)
            <img src="{{ $newsImageUrl }}" alt="">
        @else
            <div class="t-card-placeholder">News photo</div>
        @endif
    </div>
    <h2>
        <a href="{{ $post->url() }}">
            <span @include('partials.inline-edit-attrs', ['model' => 'news', 'id' => $post->id, 'field' => 'title', 'type' => 'text'])>{{ $post->title }}</span>
        </a>
    </h2>
    @if ($post->published_at)
        <p class="news-meta">{{ $post->published_at->format('F j, Y') }}@if ($post->location) &middot; {{ $post->location }}@endif</p>
    @endif
    <p @include('partials.inline-edit-attrs', ['model' => 'news', 'id' => $post->id, 'field' => 'excerpt', 'type' => 'richtext'])>{!! $post->excerpt !!}</p>
    <a href="{{ $post->url() }}">Read more</a>
</li>
