<section class="t-cta">
    <div class="t-container">
        <div class="t-cta-card">
            <div>
                @include('partials.site-setting', ['key' => 'home_cta_title', 'default' => 'Interested in Collaborating?', 'tag' => 'h2', 'type' => 'text'])
                @include('partials.site-setting', ['key' => 'home_cta_text', 'default' => 'We work with partners to improve information systems and connect the people of Ontario to relevant health and community services. Let\'s talk about how we can collaborate to solve your clients\' needs and your data management needs.', 'tag' => 'p', 'type' => 'textarea'])
                <a href="mailto:{{ $thlin['contact_email'] }}" class="home-collab-email">{{ $thlin['contact_email'] }}</a>
            </div>

            <div class="t-cta-actions">
                <a href="{{ route('contact') }}" class="t-btn t-btn-light">
                    Contact Us
                </a>
                <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'portfolio']) }}" class="t-btn t-btn-outline">
                    View Portfolio
                </a>
            </div>
        </div>
    </div>
</section>
