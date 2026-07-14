<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Navigation
            ['key' => 'nav_products_label', 'value' => 'Products & Services', 'type' => 'text', 'group' => 'navigation'],
            ['key' => 'nav_partners_label', 'value' => 'Partners', 'type' => 'text', 'group' => 'navigation'],
            ['key' => 'nav_about_label', 'value' => 'About', 'type' => 'text', 'group' => 'navigation'],
            ['key' => 'nav_resources_label', 'value' => 'Resources', 'type' => 'text', 'group' => 'navigation'],
            ['key' => 'nav_cta_label', 'value' => 'Contact Us', 'type' => 'text', 'group' => 'navigation'],

            // Footer
            ['key' => 'footer_description', 'value' => 'THLIN helps people and organizations access trusted health and community service information through practical digital tools.', 'type' => 'textarea', 'group' => 'footer'],
            ['key' => 'footer_quick_links_heading', 'value' => 'Quick Links', 'type' => 'text', 'group' => 'footer'],
            ['key' => 'footer_services_heading', 'value' => 'Services', 'type' => 'text', 'group' => 'footer'],
            ['key' => 'footer_connect_heading', 'value' => 'Connect', 'type' => 'text', 'group' => 'footer'],
            ['key' => 'footer_copyright', 'value' => 'thehealthline.ca Information Network. All rights reserved.', 'type' => 'text', 'group' => 'footer'],
            ['key' => 'footer_cta_link_label', 'value' => 'Get in touch', 'type' => 'text', 'group' => 'footer'],

            // Global CTA
            ['key' => 'cta_eyebrow', 'value' => 'Get Started', 'type' => 'text', 'group' => 'cta'],
            ['key' => 'cta_title', 'value' => 'Interested in Collaborating?', 'type' => 'text', 'group' => 'cta'],
            ['key' => 'cta_text', 'value' => 'We work with partners to improve information systems and connect the people of Ontario to relevant health and community services. Let\'s talk about how we can collaborate to solve your clients\' needs and your data management needs.', 'type' => 'textarea', 'group' => 'cta'],
            ['key' => 'cta_primary_label', 'value' => 'Contact Us', 'type' => 'text', 'group' => 'cta'],
            ['key' => 'cta_secondary_label', 'value' => 'Explore Products & Services', 'type' => 'text', 'group' => 'cta'],

            // Home — Quick Access (live site: 3 cards)
            ['key' => 'home_quick_card_1_title', 'value' => 'Products & Services', 'type' => 'text', 'group' => 'home'],
            ['key' => 'home_quick_card_1_text', 'value' => 'We build websites, collaboration tools and information portals that meet our clients\' needs.', 'type' => 'textarea', 'group' => 'home'],
            ['key' => 'home_quick_card_2_title', 'value' => 'Tools', 'type' => 'text', 'group' => 'home'],
            ['key' => 'home_quick_card_2_text', 'value' => 'We can work with health care professionals, social service providers, municipalities and OHTs.', 'type' => 'textarea', 'group' => 'home'],
            ['key' => 'home_quick_card_3_title', 'value' => 'Portfolio', 'type' => 'text', 'group' => 'home'],
            ['key' => 'home_quick_card_3_text', 'value' => 'Check out some examples of our latest projects!', 'type' => 'textarea', 'group' => 'home'],

            // Home — thehealthline.ca
            ['key' => 'home_healthline_title', 'value' => 'thehealthline.ca', 'type' => 'text', 'group' => 'home'],
            ['key' => 'home_healthline_text', 'value' => 'An authoritative health service directory that makes navigating the health care system easier. With 47,000 detailed records for home, community, primary, acute and long-term care services, Our online service directory is the most widely used, online system navigation tool in Ontario.', 'type' => 'textarea', 'group' => 'home'],

            // Home — Products & Services (4-card grid)
            ['key' => 'home_products_title', 'value' => 'Digital tools built for easier system navigation.', 'type' => 'text', 'group' => 'home'],
            ['key' => 'home_products_subtitle', 'value' => 'We help organizations present information clearly and build online tools that are practical, accessible, and easy to maintain.', 'type' => 'textarea', 'group' => 'home'],
            ['key' => 'home_products_card_1_title', 'value' => 'Digital service directories', 'type' => 'text', 'group' => 'home'],
            ['key' => 'home_products_card_1_text', 'value' => 'Organized directories that help people find health, social, and community services faster.', 'type' => 'textarea', 'group' => 'home'],
            ['key' => 'home_products_card_2_title', 'value' => 'Community information tools', 'type' => 'text', 'group' => 'home'],
            ['key' => 'home_products_card_2_text', 'value' => 'Searchable online tools designed around real user needs and local community resources.', 'type' => 'textarea', 'group' => 'home'],
            ['key' => 'home_products_card_3_title', 'value' => 'Website and portal development', 'type' => 'text', 'group' => 'home'],
            ['key' => 'home_products_card_3_text', 'value' => 'Professional websites and portals that support content management and partner communication.', 'type' => 'textarea', 'group' => 'home'],
            ['key' => 'home_products_card_4_title', 'value' => 'Data and content support', 'type' => 'text', 'group' => 'home'],
            ['key' => 'home_products_card_4_text', 'value' => 'Support for keeping information accurate, structured, searchable, and useful for users.', 'type' => 'textarea', 'group' => 'home'],

            // Home — Portfolio & CTA
            ['key' => 'home_portfolio_title', 'value' => 'Building Tools to Support our Communities', 'type' => 'text', 'group' => 'home'],
            ['key' => 'home_portfolio_subtitle', 'value' => 'Mapping the mosaic of services available within your community and presenting the information effectively, takes careful work. We can help. Whether you\'re enhancing an existing community information tool or building patient-centred websites, featuring tools to help find condition-specific information, our tailored sites are built to meet user needs. Simple, easy to use and information-rich.', 'type' => 'textarea', 'group' => 'home'],
            ['key' => 'home_cta_title', 'value' => 'Interested in Collaborating?', 'type' => 'text', 'group' => 'home'],
            ['key' => 'home_cta_text', 'value' => 'We work with partners to improve information systems and connect the people of Ontario to relevant health and community services. Let\'s talk about how we can collaborate to solve your clients\' needs and your data management needs.', 'type' => 'textarea', 'group' => 'home'],

            // Contact
            ['key' => 'contact_form_title', 'value' => 'Send us a message', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_form_subtitle', 'value' => 'Tell us how we can help. We\'ll get back to you as soon as possible.', 'type' => 'textarea', 'group' => 'contact'],
            ['key' => 'contact_office_heading', 'value' => 'Head Office', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_person_name', 'value' => 'Sean Wong', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_person_title', 'value' => 'Executive Director', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_person_intro', 'value' => 'For partnership inquiries, media requests, or general questions about THLIN services.', 'type' => 'textarea', 'group' => 'contact'],
            ['key' => 'contact_address', 'value' => '201 King St, London, ON N6C 1C9', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_phone', 'value' => '519-660-5910', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_email', 'value' => 'admin@thehealthline.ca', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_note_title', 'value' => 'Working with THLIN?', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_note_text', 'value' => 'Contact us about partnerships, digital tools, service directories, portals, and information management support.', 'type' => 'textarea', 'group' => 'contact'],

            // Portfolio
            ['key' => 'portfolio_cta_eyebrow', 'value' => 'Get Started', 'type' => 'text', 'group' => 'portfolio'],
            ['key' => 'portfolio_cta_title', 'value' => 'Interested in Collaborating?', 'type' => 'text', 'group' => 'portfolio'],
            ['key' => 'portfolio_cta_text', 'value' => 'We work with partners to improve information systems and connect the people of Ontario to relevant health and community services.', 'type' => 'textarea', 'group' => 'portfolio'],
            ['key' => 'portfolio_cta_primary_label', 'value' => 'Contact Us', 'type' => 'text', 'group' => 'portfolio'],
            ['key' => 'portfolio_cta_secondary_label', 'value' => 'Explore Products & Services', 'type' => 'text', 'group' => 'portfolio'],
            ['key' => 'portfolio_featured_title', 'value' => 'Highlighted Projects', 'type' => 'text', 'group' => 'portfolio'],
            ['key' => 'portfolio_past_title', 'value' => 'Past Projects', 'type' => 'text', 'group' => 'portfolio'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                [
                    'value' => $setting['value'],
                    'type' => $setting['type'],
                    'group' => $setting['group'],
                ]
            );
        }
    }
}
