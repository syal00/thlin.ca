@php
    $prefix = isset($settingPrefix) ? $settingPrefix.'_' : '';
@endphp

<section class="home-cta-premium">
    <div class="section-container">
        <div class="cta-box">
            <div>
                @include('partials.site-setting', [
                    'key' => $prefix.'cta_eyebrow',
                    'default' => $ctaEyebrow ?? 'Get Started',
                    'tag' => 'span',
                    'class' => 'section-kicker',
                ])
                @include('partials.site-setting', [
                    'key' => $prefix.'cta_title',
                    'default' => $ctaTitle ?? 'Ready to connect with THLIN?',
                    'tag' => 'h2',
                ])
                @include('partials.site-setting', [
                    'key' => $prefix.'cta_text',
                    'default' => $ctaText ?? 'Contact our team to learn more about our digital health information tools and partnership support.',
                    'type' => 'textarea',
                    'tag' => 'p',
                ])
            </div>

            <div class="cta-actions">
                <a href="{{ $ctaPrimaryUrl ?? route('contact') }}" class="btn btn-light">
                    @include('partials.site-setting', [
                        'key' => $prefix.'cta_primary_label',
                        'default' => $ctaPrimary ?? 'Contact Us',
                    ])
                </a>
                <a href="{{ $ctaSecondaryUrl ?? route('pages.show', ['section' => 'products', 'page' => 'healthline']) }}" class="btn btn-outline-light">
                    @include('partials.site-setting', [
                        'key' => $prefix.'cta_secondary_label',
                        'default' => $ctaSecondary ?? 'Explore Products & Services',
                    ])
                </a>
            </div>
        </div>
    </div>
</section>
