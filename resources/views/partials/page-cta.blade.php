<section class="home-cta-premium">
    <div class="section-container">
        <div class="cta-box">
            <div>
                <span class="section-kicker">{{ $ctaEyebrow ?? 'Get Started' }}</span>
                <h2>{{ $ctaTitle ?? 'Ready to connect with THLIN?' }}</h2>
                <p>{{ $ctaText ?? 'Contact our team to learn more about our digital health information tools and partnership support.' }}</p>
            </div>

            <div class="cta-actions">
                <a href="{{ $ctaPrimaryUrl ?? route('contact') }}" class="btn btn-light">{{ $ctaPrimary ?? 'Contact Us' }}</a>
                <a href="{{ $ctaSecondaryUrl ?? route('pages.show', ['section' => 'products', 'page' => 'healthline']) }}" class="btn btn-outline-light">{{ $ctaSecondary ?? 'Explore Products & Services' }}</a>
            </div>
        </div>
    </div>
</section>
