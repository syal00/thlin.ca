@php
    /** @var \App\Models\PortfolioItem $item */
@endphp
<article class="portfolio-card reveal-on-scroll">
    @if ($item->url)
        <a href="{{ $item->url }}" class="portfolio-card-media" target="_blank" rel="noopener">
    @endif

    @if ($item->image)
        <img
            src="{{ $item->imageUrl() }}"
            alt="{{ $item->title }}"
            data-editable-image="true"
            data-model="portfolio"
            data-id="{{ $item->id }}"
            data-field="image"
        >
    @else
        <div
            class="portfolio-placeholder"
            data-editable-image="true"
            data-model="portfolio"
            data-id="{{ $item->id }}"
            data-field="image"
        >Project image</div>
    @endif

    @if ($item->url)
        </a>
    @endif

    <div class="portfolio-card-body">
        <h3 @include('partials.inline-edit-attrs', ['model' => 'portfolio', 'id' => $item->id, 'field' => 'title', 'type' => 'text'])>{{ $item->title }}</h3>

        <p @include('partials.inline-edit-attrs', ['model' => 'portfolio', 'id' => $item->id, 'field' => 'excerpt', 'type' => 'richtext'])>{!! $item->excerpt !!}</p>

        @if ($item->url)
            <a href="{{ $item->url }}" class="portfolio-card-action" target="_blank" rel="noopener">View project</a>
        @endif
    </div>
</article>
