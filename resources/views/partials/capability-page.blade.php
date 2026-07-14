{{-- Simple full-bleed hero: title only, no card, no buttons, no breadcrumb clutter --}}
<section class="simple-hero">
    <div class="container simple-hero-inner">
        <h1
            data-editable="true"
            data-model="page"
            data-id="{{ $page->id }}"
            data-field="title"
        >{{ $page->title }}</h1>
    </div>
</section>

{{-- Main content: single column, no sidebar --}}
<section class="simple-section simple-section--light">
    <div class="container simple-prose">
        <div
            class="service-rich-content capability-page-body"
            data-editable="true"
            data-model="page"
            data-id="{{ $page->id }}"
            data-field="body"
        >
            @include('partials.cms-body', ['html' => $page->body])
        </div>

        @if ($page->updated_at)
            <p class="simple-updated">
                Last updated: {{ $page->updated_at->format('F j, Y') }}
            </p>
        @endif
    </div>
</section>

{{-- Note: the source site (thlin.ca) does not have Service Features, Why It
     Matters, or Related Services sections on any product/partner detail page —
     each page ends right after its body content and "Learn More" links, which
     already come through in the CMS body above. Intentionally not adding
     invented sections here to match the source structure exactly. --}}
