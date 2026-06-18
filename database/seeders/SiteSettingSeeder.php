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
            ['key' => 'cta_title', 'value' => 'Ready to connect with THLIN?', 'type' => 'text', 'group' => 'cta'],
            ['key' => 'cta_text', 'value' => 'Contact our team to learn more about our digital health information tools and partnership support.', 'type' => 'textarea', 'group' => 'cta'],
            ['key' => 'cta_primary_label', 'value' => 'Contact Us', 'type' => 'text', 'group' => 'cta'],
            ['key' => 'cta_secondary_label', 'value' => 'Explore Products & Services', 'type' => 'text', 'group' => 'cta'],

            // Home — Quick Access
            ['key' => 'home_quick_access_title', 'value' => 'How can we help you?', 'type' => 'text', 'group' => 'home'],
            ['key' => 'home_quick_access_subtitle', 'value' => 'Choose the path that best matches your needs and quickly access THLIN information, tools, and services.', 'type' => 'textarea', 'group' => 'home'],

            ['key' => 'home_help_card_1_title', 'value' => 'Patients & Families', 'type' => 'text', 'group' => 'home'],
            ['key' => 'home_help_card_1_text', 'value' => 'Find trusted health and community service information that is easier to understand and access.', 'type' => 'textarea', 'group' => 'home'],
            ['key' => 'home_help_card_1_link', 'value' => 'Find services', 'type' => 'text', 'group' => 'home'],

            ['key' => 'home_help_card_2_title', 'value' => 'Health & Social Service Providers', 'type' => 'text', 'group' => 'home'],
            ['key' => 'home_help_card_2_text', 'value' => 'Connect people to programs, resources, and local service information.', 'type' => 'textarea', 'group' => 'home'],
            ['key' => 'home_help_card_2_link', 'value' => 'Support navigation', 'type' => 'text', 'group' => 'home'],

            ['key' => 'home_help_card_3_title', 'value' => 'Partner Organizations', 'type' => 'text', 'group' => 'home'],
            ['key' => 'home_help_card_3_text', 'value' => 'Work with THLIN to build digital tools that support better access to information.', 'type' => 'textarea', 'group' => 'home'],
            ['key' => 'home_help_card_3_link', 'value' => 'Partner with us', 'type' => 'text', 'group' => 'home'],

            ['key' => 'home_help_card_4_title', 'value' => 'Community Members', 'type' => 'text', 'group' => 'home'],
            ['key' => 'home_help_card_4_text', 'value' => 'Explore online tools designed to make health and community information easier to find.', 'type' => 'textarea', 'group' => 'home'],
            ['key' => 'home_help_card_4_link', 'value' => 'Explore tools', 'type' => 'text', 'group' => 'home'],

            // Home — About
            ['key' => 'home_about_title', 'value' => 'Making health and community information easier to access.', 'type' => 'text', 'group' => 'home'],
            ['key' => 'home_about_text_1', 'value' => 'THLIN develops and supports digital information tools that help people navigate health and social services. We work with partners to organize service information clearly, improve access, and support better system navigation.', 'type' => 'textarea', 'group' => 'home'],
            ['key' => 'home_about_text_2', 'value' => 'Our work is focused on trusted information, usable online tools, and practical support for organizations serving communities across Ontario.', 'type' => 'textarea', 'group' => 'home'],
            ['key' => 'home_about_button_label', 'value' => 'Learn About THLIN', 'type' => 'text', 'group' => 'home'],
            ['key' => 'home_about_panel_kicker', 'value' => 'thehealthline.ca', 'type' => 'text', 'group' => 'home'],
            ['key' => 'home_about_panel_title', 'value' => 'Ontario’s health service directory', 'type' => 'text', 'group' => 'home'],
            ['key' => 'home_about_panel_text', 'value' => 'A trusted online directory helping people find home care, community support, health care, and social service resources.', 'type' => 'textarea', 'group' => 'home'],

            // Home — Products
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
            ['key' => 'home_portfolio_title', 'value' => 'Projects that support better access to information.', 'type' => 'text', 'group' => 'home'],
            ['key' => 'home_portfolio_subtitle', 'value' => 'Explore examples of THLIN’s digital work with healthcare and community partners.', 'type' => 'textarea', 'group' => 'home'],
            ['key' => 'home_cta_title', 'value' => 'Ready to connect with THLIN?', 'type' => 'text', 'group' => 'home'],
            ['key' => 'home_cta_text', 'value' => 'Contact our team to learn more about our digital health information tools and partnership support.', 'type' => 'textarea', 'group' => 'home'],

            // Contact
            ['key' => 'contact_form_title', 'value' => 'Send us a message', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_form_subtitle', 'value' => 'Tell us how we can help. We\'ll get back to you as soon as possible.', 'type' => 'textarea', 'group' => 'contact'],
            ['key' => 'contact_office_heading', 'value' => 'Head Office', 'type' => 'text', 'group' => 'contact'],
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
