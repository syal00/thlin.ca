<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            ['slug' => 'home', 'title' => 'System Navigation Made Easy', 'section' => 'home', 'template' => 'home', 'excerpt' => 'Founded in 2001, we\'re an award-winning digital health non-profit committed to connecting patients and caregivers to services, health and social services providers to other providers and health system planners to information. We\'re driven by an unrelenting commitment to simplifying system navigation by building useful and usable online tools. Becoming healthier is challenging; finding care shouldn\'t be.'],
            ['slug' => 'healthline', 'title' => 'Help Finding Health Care', 'section' => 'products', 'excerpt' => 'Ontario\'s authoritative online health and community service directory.'],
            ['slug' => 'healthchat', 'title' => 'Powering Collaboration', 'section' => 'products', 'excerpt' => 'Secure collaboration for health and social service teams.'],
            ['slug' => 'healthchat-features', 'title' => 'healthchat.ca Features', 'section' => 'products', 'excerpt' => 'Core features of the healthchat.ca platform.'],
            ['slug' => 'patient-portals', 'title' => 'Supporting Patients & Caregivers', 'section' => 'products', 'excerpt' => 'Patient-centred websites and tools.'],
            ['slug' => 'provider-portals', 'title' => 'Supporting Providers', 'section' => 'products', 'excerpt' => 'Provider-focused information portals.'],
            ['slug' => 'support-training', 'title' => 'Support & Training', 'section' => 'products', 'excerpt' => 'Training and support for THLIN tools.'],
            ['slug' => 'information-management', 'title' => 'Information Management', 'section' => 'products', 'excerpt' => 'Rigorous data management for Ontario\'s service directory.'],
            ['slug' => 'portfolio', 'title' => 'Portfolio', 'section' => 'products', 'template' => 'portfolio', 'excerpt' => 'Examples of our latest projects.'],
            ['slug' => 'resources', 'title' => 'Resources', 'section' => 'products', 'excerpt' => 'Articles and resources about service directories.'],
            ['slug' => 'service-directories', 'title' => 'Not All Service Directories Are the Same', 'section' => 'products', 'excerpt' => 'How to find health and social services quickly and effectively.'],
            ['slug' => 'health-care', 'title' => 'Tools for Health Care', 'section' => 'partners', 'excerpt' => 'Digital health solutions for providers.'],
            ['slug' => 'municipalities', 'title' => 'Tools for Municipalities', 'section' => 'partners', 'excerpt' => 'Community information solutions for municipalities.'],
            ['slug' => 'social-services', 'title' => 'Tools for Social Services', 'section' => 'partners', 'excerpt' => 'Digital tools for social services delivery.'],
            ['slug' => 'ontario-health-teams', 'title' => 'Tools for Ontario Health Teams', 'section' => 'partners', 'excerpt' => 'Tools to support OHT planning and operations.'],
            ['slug' => 'us', 'title' => 'About Us', 'section' => 'about', 'excerpt' => 'Our story since 2001.'],
            ['slug' => 'board', 'title' => 'Board of Directors', 'section' => 'about', 'template' => 'board', 'excerpt' => 'Governance of THLIN.'],
            ['slug' => 'annual-reports', 'title' => 'Annual Reports', 'section' => 'about', 'excerpt' => 'Annual reports and accountability documents.'],
            ['slug' => 'news', 'title' => 'News', 'section' => 'about', 'template' => 'news', 'excerpt' => 'News and updates from THLIN.'],
            ['slug' => 'careers', 'title' => 'Careers', 'section' => 'about', 'template' => 'careers', 'excerpt' => 'Join our team.'],
            ['slug' => 'contact', 'title' => 'Let\'s Connect', 'section' => 'contact', 'template' => 'contact', 'excerpt' => 'Contact thehealthline.ca Information Network.'],
        ];

        foreach ($pages as $index => $data) {
            $body = PageBodies::get($data['slug']);

            Page::updateOrCreate(
                ['slug' => $data['slug']],
                array_merge([
                    'meta_description' => $data['excerpt'] ?? null,
                    'body' => $body,
                    'template' => $data['template'] ?? 'standard',
                    'sort_order' => $index,
                    'is_published' => true,
                ], $data)
            );
        }
    }
}
