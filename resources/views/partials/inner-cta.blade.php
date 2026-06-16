<section class="inner-page-section inner-page-section--cta">
    <div class="inner-container">
        <div class="inner-cta">
            <h2>{{ $ctaTitle ?? 'Ready to connect with THLIN?' }}</h2>
            <p>{{ $ctaText ?? 'Contact our team to learn more about our digital health information tools and partnership support.' }}</p>
            <div class="inner-cta-actions">
                <a href="{{ route('contact') }}" class="btn btn-light">{{ $ctaPrimary ?? 'Contact Us' }}</a>
                <a href="{{ $ctaSecondaryUrl ?? route('pages.show', ['section' => 'products', 'page' => 'healthline']) }}" class="btn btn-outline-light">{{ $ctaSecondary ?? 'Explore Products & Services' }}</a>
            </div>
        </div>
    </div>
</section>
