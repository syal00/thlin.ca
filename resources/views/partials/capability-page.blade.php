{{-- Main content: single column prose, no sidebar/card. Hero is rendered by
     the parent view (pages/show.blade.php @section('hero')) so this partial
     only needs the body. --}}
<section class="t-prose">
    <div class="t-container">
        <div class="t-prose-content service-rich-content capability-page-body" @include('partials.inline-edit-attrs', ['model' => 'page', 'id' => $page->id, 'field' => 'body', 'type' => 'richtext'])>
            @include('partials.cms-body', ['html' => $page->body])
        </div>

        @include('partials.page-updated', ['page' => $page])
    </div>
</section>

{{-- Note: the source site (thlin.ca) does not have Service Features, Why It
     Matters, or Related Services sections on any product/partner detail page —
     each page ends right after its body content and "Learn More" links, which
     already come through in the CMS body above. Intentionally not adding
     invented sections here to match the source structure exactly. --}}
