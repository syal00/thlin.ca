<section class="thlin-final-cta home-collab-cta">
    <div class="thlin-final-cta-container">
        <div class="thlin-final-cta-card home-collab-cta-card">
            <div class="thlin-final-cta-copy">
                @include('partials.site-setting', ['key' => 'home_cta_title', 'default' => 'Interested in Collaborating?', 'tag' => 'h2', 'type' => 'text'])
                @include('partials.site-setting', ['key' => 'home_cta_text', 'default' => 'We work with partners to improve information systems and connect the people of Ontario to relevant health and community services. Let\'s talk about how we can collaborate to solve your clients\' needs and your data management needs.', 'tag' => 'p', 'type' => 'textarea'])
                <a href="mailto:{{ $thlin['contact_email'] }}" class="home-collab-email">{{ $thlin['contact_email'] }}</a>
            </div>

            <div class="thlin-final-cta-actions">
                <a href="{{ route('contact') }}" class="thlin-final-cta-btn thlin-final-cta-btn-primary">
                    Contact Us
                </a>
                <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'portfolio']) }}" class="thlin-final-cta-btn thlin-final-cta-btn-secondary">
                    View Portfolio
                </a>
            </div>
        </div>
    </div>
</section>
