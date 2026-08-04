<?php

namespace App\Services;

use App\Models\BoardMember;
use App\Models\Career;
use App\Models\NewsPost;
use App\Models\Page;
use App\Models\PortfolioItem;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ThlinContentImporter
{
    private const BASE_URL = 'https://thlin.ca';

    /** @var array<string, array{slug: string, section: string, template?: string}> */
    private const PAGE_MAP = [
        '17/Help_Finding_Health_Care' => ['slug' => 'healthline', 'section' => 'products'],
        '8/Health_Chat' => ['slug' => 'healthchat', 'section' => 'products'],
        '15/Supporting_patients_caregivers' => ['slug' => 'patient-portals', 'section' => 'products'],
        '16/Supporting_providers' => ['slug' => 'provider-portals', 'section' => 'products'],
        '40/Support_Training' => ['slug' => 'support-training', 'section' => 'products'],
        '14/Information_Management' => ['slug' => 'information-management', 'section' => 'products'],
        '39/Portfolio' => ['slug' => 'portfolio', 'section' => 'products', 'template' => 'portfolio'],
        '42/Resources' => ['slug' => 'resources', 'section' => 'products'],
        '18/Tools_for_Health_Care' => ['slug' => 'health-care', 'section' => 'partners'],
        '19/Tools_for_Municipalities' => ['slug' => 'municipalities', 'section' => 'partners'],
        '23/Tools_for_Social_Services' => ['slug' => 'social-services', 'section' => 'partners'],
        '20/Tools_for_Ontario_Health_Teams' => ['slug' => 'ontario-health-teams', 'section' => 'partners'],
        '37/About_Us' => ['slug' => 'us', 'section' => 'about'],
        '35/Annual_Reports' => ['slug' => 'annual-reports', 'section' => 'about'],
        '36/News' => ['slug' => 'news', 'section' => 'about', 'template' => 'news'],
        '38/Board_of_Directors' => ['slug' => 'board', 'section' => 'about', 'template' => 'board'],
        '6/Careers' => ['slug' => 'careers', 'section' => 'about', 'template' => 'careers'],
    ];

    /** @var array<string, string> */
    private const ROUTE_MAP = [
        '17/Help_Finding_Health_Care' => '/products/healthline',
        '8/Health_Chat' => '/products/healthchat',
        '15/Supporting_patients_caregivers' => '/products/patient-portals',
        '16/Supporting_providers' => '/products/provider-portals',
        '40/Support_Training' => '/products/support-training',
        '14/Information_Management' => '/products/information-management',
        '39/Portfolio' => '/products/portfolio',
        '42/Resources' => '/products/resources',
        '18/Tools_for_Health_Care' => '/partners/health-care',
        '19/Tools_for_Municipalities' => '/partners/municipalities',
        '23/Tools_for_Social_Services' => '/partners/social-services',
        '20/Tools_for_Ontario_Health_Teams' => '/partners/ontario-health-teams',
        '37/About_Us' => '/about/us',
        '35/Annual_Reports' => '/about/annual-reports',
        '36/News' => '/about/news',
        '38/Board_of_Directors' => '/about/board',
        '6/Careers' => '/about/careers',
        '33/THL_Information_Network_Welcomes_Sean_Wong' => '/about/news/sean-wong',
    ];

    public function import(callable $log): void
    {
        $log('Importing home page…');
        $this->importHome($log);

        foreach (self::PAGE_MAP as $path => $meta) {
            $log("Importing page: {$meta['slug']}…");
            $this->importPage($path, $meta, $log);
        }

        $log('Importing board members…');
        $this->importBoard($log);

        $log('Importing news…');
        $this->importNews($log);

        $log('Importing careers…');
        $this->importCareers($log);

        $log('Importing portfolio…');
        $this->importPortfolio($log);

        $log('Importing contact page…');
        $this->importContact($log);

        $log('Syncing homepage site settings…');
        $this->importHomeSiteSettings($log);
    }

    private function importHome(callable $log): void
    {
        $html = $this->fetch('/');
        if ($html === null) {
            $log('  ⚠ Could not fetch home page');

            return;
        }

        $title = 'System Navigation Made Easy';
        $excerpt = $this->matchFirst($html, '/<h2>\s*System Navigation Made Easy\s*<\/h2>\s*<p>(.*?)<\/p>/is');

        Page::updateOrCreate(
            ['slug' => 'home'],
            [
                'title' => $title,
                'section' => 'home',
                'template' => 'home',
                'excerpt' => $excerpt,
                'meta_description' => $excerpt ? Str::limit($excerpt, 255) : null,
                'page_type' => 'built_in',
                'status' => 'published',
                'is_published' => true,
                'updated_by' => null,
            ]
        );

        $log('  ✓ Home page updated');
    }

    /**
     * @param  array{slug: string, section: string, template?: string}  $meta
     */
    private function importPage(string $path, array $meta, callable $log): void
    {
        $html = $this->fetch('/'.$path.'/');
        if ($html === null) {
            $log("  ⚠ Could not fetch /{$path}/");

            return;
        }

        $title = $this->extractPageTitle($html) ?? Str::headline(str_replace('_', ' ', basename($path)));
        $body = $this->extractContentHtml($html);
        $excerpt = $this->extractExcerpt($body) ?? $this->extractExcerptFromTitleArea($html);

        $pageValues = array_filter([
            'title' => html_entity_decode($title, ENT_QUOTES | ENT_HTML5, 'UTF-8'),
            'section' => $meta['section'],
            'template' => $meta['template'] ?? 'standard',
            'body' => $body,
            'excerpt' => $excerpt,
            'meta_description' => $excerpt ? Str::limit($excerpt, 255) : null,
            'hero_title' => html_entity_decode($title, ENT_QUOTES | ENT_HTML5, 'UTF-8'),
            'hero_subtitle' => $excerpt,
            'page_type' => 'built_in',
            'status' => 'published',
            'is_published' => true,
        ]);
        $pageValues['updated_by'] = null;

        Page::updateOrCreate(
            ['slug' => $meta['slug']],
            $pageValues
        );

        $log("  ✓ {$meta['slug']}");
    }

    private function importBoard(callable $log): void
    {
        $html = $this->fetch('/38/Board_of_Directors/');
        if ($html === null) {
            $log('  ⚠ Could not fetch board page');

            return;
        }

        if (! preg_match('/<div class="board-of-directors[^"]*">(.*?)<\/div>\s*<\/div>\s*<\/div>\s*<\/section>/is', $html, $match)) {
            $log('  ⚠ Board markup not found');

            return;
        }

        $block = $match[1];
        preg_match_all(
            '/<div class="row">\s*<div class="col-md-3">(?:<img[^>]+src="([^"]+)"[^>]*>)?\s*<\/div>\s*<div class="col-md-9">\s*<h3>(.*?)<\/h3>\s*<p>(.*?)<\/p>/is',
            $block,
            $rows,
            PREG_SET_ORDER
        );

        $order = 1;
        foreach ($rows as $row) {
            [$full, $photo, $heading, $bio] = $row;
            if (! preg_match('/^(.+?)\s*-\s*(.+)$/u', strip_tags($heading), $parts)) {
                continue;
            }

            BoardMember::updateOrCreate(
                ['name' => trim($parts[1])],
                [
                    'role' => trim($parts[2]),
                    'bio' => $this->cleanText($bio),
                    'photo' => $photo ? $this->absoluteUrl($photo) : null,
                    'sort_order' => $order++,
                ]
            );
        }

        $log('  ✓ '.($order - 1).' board members');
    }

    private function importNews(callable $log): void
    {
        $html = $this->fetch('/33/THL_Information_Network_Welcomes_Sean_Wong/');
        if ($html === null) {
            $log('  ⚠ Could not fetch news article');

            return;
        }

        $title = $this->extractPageTitle($html) ?? 'THL Information Network Welcomes Sean Wong';
        $body = $this->extractContentHtml($html);
        $body = $body ? '<div class="cms-content">'.$body.'</div>' : '';

        $listing = $this->fetch('/36/News/');
        $excerpt = null;
        if ($listing && preg_match('/<h2>THL Information Network Welcomes Sean Wong<\/h2>\s*([^<]+)/is', $listing, $m)) {
            $excerpt = $this->cleanText($m[1]);
        }

        NewsPost::updateOrCreate(
            ['slug' => 'sean-wong'],
            [
                'title' => html_entity_decode($title, ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                'published_at' => '2021-03-01',
                'location' => 'London, ON',
                'image' => 'images/news/sean-wong.png',
                'excerpt' => $excerpt ?? 'THLIN announced the appointment of Sean Wong as Executive Director.',
                'body' => $body,
                'is_published' => true,
            ]
        );

        $log('  ✓ sean-wong news post');
    }

    private function importCareers(callable $log): void
    {
        $html = $this->fetch('/6/Careers/');
        if ($html === null) {
            $log('  ⚠ Could not fetch careers page');

            return;
        }

        $content = $this->extractContentHtml($html);
        if ($content === null) {
            $log('  ⚠ Careers content not found');

            return;
        }

        $parts = preg_split('/<h2>/i', $content);
        array_shift($parts);

        $count = 0;
        foreach ($parts as $part) {
            if (! preg_match('/^([^<]+)<\/h2>(.*)$/is', $part, $m)) {
                continue;
            }

            $title = $this->cleanText($m[1]);
            $body = trim($m[2]);
            if ($title === '') {
                continue;
            }

            $slug = Str::slug($title);
            $postedAt = null;
            $closesAt = null;
            if (preg_match('/Posted:\s*([A-Za-z]+\s+\d{1,2},\s+\d{4})/i', $body, $pm)) {
                $postedAt = date('Y-m-d', strtotime($pm[1]));
            }
            if (preg_match('/Closes:\s*([A-Za-z]+\s+\d{1,2},\s+\d{4})/i', $body, $cm)) {
                $closesAt = date('Y-m-d', strtotime($cm[1]));
            }

            Career::updateOrCreate(
                ['slug' => $slug],
                [
                    'title' => $title,
                    'location' => str_contains($title, 'Bilingual')
                        ? 'London, ON / remote throughout province'
                        : 'London, ON (remote with proximity to London required)',
                    'employment_type' => 'Full-time permanent',
                    'posted_at' => $postedAt,
                    'closes_at' => $closesAt,
                    'body' => '<div class="cms-content">'.$body.'</div>',
                    'is_active' => true,
                ]
            );
            $count++;
        }

        Page::updateOrCreate(
            ['slug' => 'careers'],
            [
                'body' => '<p>thehealthline.ca Information Network is a non-profit information company committed to making health and social service system navigation easier for everyone in Ontario. Located in London with workers connecting remotely throughout the province, we are a team-based, collaborative company.</p><p>For careers in the health care sector, visit Health Careers on <a href="https://www.southwesthealthline.ca" target="_blank" rel="noopener">southwesthealthline.ca</a>.</p>',
                'updated_by' => null,
            ]
        );

        $log("  ✓ {$count} career postings");
    }

    private function importContact(callable $log): void
    {
        $html = $this->fetch('/Contact/');
        if ($html === null) {
            $log('  ⚠ Could not fetch contact page');

            return;
        }

        $title = $this->extractPageTitle($html) ?? "Let's Connect";
        $body = $this->extractContentHtml($html);

        Page::updateOrCreate(
            ['slug' => 'contact'],
            [
                'title' => html_entity_decode($title, ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                'section' => 'contact',
                'template' => 'contact',
                'body' => $body,
                'page_type' => 'built_in',
                'status' => 'published',
                'is_published' => true,
                'updated_by' => null,
            ]
        );

        $log('  ✓ contact');
    }

    private function importHomeSiteSettings(callable $log): void
    {
        $html = $this->fetch('/');
        if ($html === null) {
            $log('  ⚠ Could not fetch home page for settings');

            return;
        }

        $settings = [
            'home_quick_card_1_title' => 'Products & Services',
            'home_quick_card_1_text' => $this->matchFirst($html, '/<h3 class="blue-text">Products &amp; Services<\/h3>\s*<p>(.*?)<\/p>/is'),
            'home_quick_card_2_title' => 'Tools',
            'home_quick_card_2_text' => $this->matchFirst($html, '/<h3 class="blue-text">Tools<\/h3>\s*<p>(.*?)<\/p>/is'),
            'home_quick_card_3_title' => 'Portfolio',
            'home_quick_card_3_text' => $this->matchFirst($html, '/<h3 class="blue-text">Portfolio<\/h3>\s*<p>(.*?)<\/p>/is'),
            'home_healthline_title' => 'thehealthline.ca',
            'home_healthline_text' => $this->matchFirst($html, '/<h2>thehealthline\.ca<\/h2>\s*<p>(.*?)<\/p>/is'),
            'home_portfolio_title' => 'Building Tools to Support our Communities',
            'home_portfolio_subtitle' => $this->matchFirst($html, '/<h2 class="white-text">Building Tools to Support our Communities<\/h2>\s*<p class="white-text">(.*?)<\/p>/is'),
            'home_cta_title' => 'Interested in Collaborating?',
            'home_cta_text' => $this->matchFirst($html, '/<h2>Interested in Collaborating\?<\/h2>\s*<p>(.*?)<\/p>/is'),
            'cta_title' => 'Interested in Collaborating?',
            'cta_text' => $this->matchFirst($html, '/<h2>Interested in Collaborating\?<\/h2>\s*<p>(.*?)<\/p>/is'),
        ];

        foreach ($settings as $key => $value) {
            if ($value === null || $value === '') {
                continue;
            }

            SiteSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'type' => str_contains($key, '_text') || str_contains($key, 'subtitle') ? 'textarea' : 'text', 'group' => 'home']
            );
        }

        $log('  ✓ Homepage site settings synced');
        SiteSetting::forgetCache();
    }

    private function importPortfolio(callable $log): void
    {
        $html = $this->fetch('/39/Portfolio/');
        if ($html === null) {
            $log('  ⚠ Could not fetch portfolio page');

            return;
        }

        $content = $this->extractContentHtml($html);
        if ($content === null) {
            return;
        }

        if (preg_match('/<h1>Building Tools.*?<\/h1>\s*<p>(.*?)<\/p>/is', $content, $intro)) {
            Page::updateOrCreate(
                ['slug' => 'portfolio'],
                [
                    'body' => '<section class="content-section"><p>'.$this->cleanInlineHtml($intro[1]).'</p></section>',
                    'updated_by' => null,
                ]
            );
        }

        $featured = [
            ['title' => 'SWRWCP', 'pattern' => 'SWRWCP', 'url' => 'https://swrwoundcareprogram.ca/', 'sort' => 1],
            ['title' => 'AES Wellness Portal', 'pattern' => 'AES Wellness Portal', 'url' => 'https://aeswellnessportal.ca/', 'sort' => 2],
            ['title' => 'FamilyInfo', 'pattern' => 'FamilyInfo', 'url' => 'https://familyinfo.ca/', 'sort' => 3],
        ];

        foreach ($featured as $item) {
            $excerpt = $this->matchFirst($content, '/<h3>'.$item['pattern'].'<\/h3>\s*<p>(.*?)<\/p>/is');
            $image = $this->matchFirst($content, '/<h3>'.$item['pattern'].'<\/h3>.*?src="([^"]+)"/is');

            PortfolioItem::updateOrCreate(
                ['title' => $item['title']],
                [
                    'excerpt' => $excerpt ? $this->cleanText($excerpt) : '',
                    'url' => $item['url'],
                    'featured' => true,
                    'sort_order' => $item['sort'],
                    'image' => $image ? $this->portfolioImagePath($image) : null,
                ]
            );
        }

        $past = [
            ['title' => 'Age-Friendly Sarnia-Lambton', 'url' => 'https://agefriendlysarnialambton.ca/', 'sort' => 4],
            ['title' => 'Atlas London', 'url' => 'https://atlaslondon.ca/', 'sort' => 5],
            ['title' => 'GTA Rehab Finder', 'url' => 'https://gtarehabfinder.ca/', 'sort' => 6],
            ['title' => 'Nipissing Service Collaborative', 'url' => 'https://www.sngnipissing.ca/', 'sort' => 7],
            ['title' => 'Behavioural Supports Ontario', 'url' => 'https://behaviouralsupportsontario.ca/', 'sort' => 8],
            ['title' => 'Rehabilitative Care Ontario', 'url' => 'https://rehabcareontario.ca/', 'sort' => 9],
        ];

        foreach ($past as $item) {
            $excerpt = $this->matchFirst(
                $content,
                '/<h3>[^<]*'.preg_quote($item['title'], '/').'[^<]*<\/h3>\s*<p>(.*?)<\/p>/is'
            );
            $image = $this->matchFirst(
                $content,
                '/'.preg_quote($item['title'], '/').'.*?src="([^"]+)"/is'
            );

            PortfolioItem::updateOrCreate(
                ['title' => $item['title']],
                [
                    'excerpt' => $excerpt ? $this->cleanText($excerpt) : '',
                    'url' => $item['url'],
                    'featured' => false,
                    'sort_order' => $item['sort'],
                    'image' => $image ? $this->portfolioImagePath($image) : null,
                ]
            );
        }

        $log('  ✓ Portfolio items updated');
    }

    private function fetch(string $path): ?string
    {
        try {
            $response = Http::timeout(45)
                ->withHeaders(['User-Agent' => 'THLIN-Content-Importer/1.0'])
                ->get(rtrim(self::BASE_URL, '/').'/'.ltrim($path, '/'));

            if (! $response->successful()) {
                return null;
            }

            return $response->body();
        } catch (\Throwable) {
            return null;
        }
    }

    private function extractPageTitle(string $html): ?string
    {
        return $this->matchFirst($html, '/<h1[^>]*class="[^"]*page-title-text[^"]*"[^>]*>\s*(.*?)\s*<\/h1>/is');
    }

    private function extractExcerptFromTitleArea(string $html): ?string
    {
        return null;
    }

    private function extractContentHtml(string $html): ?string
    {
        if (! preg_match('/<section[^>]*custom-content[^>]*>(.*)<\/section>\s*(?:<footer|<\/body)/is', $html, $match)) {
            return null;
        }

        $content = $match[1];
        $content = preg_replace('/<style\b[^>]*>.*?<\/style>/is', '', $content) ?? $content;
        $content = preg_replace('/<input[^>]*type="hidden"[^>]*>/i', '', $content) ?? $content;
        $content = preg_replace('/<script\b[^>]*>.*?<\/script>/is', '', $content) ?? $content;

        $content = $this->rewriteUrls($content);
        $content = $this->normalizeContentSections($content);

        return trim($content) ?: null;
    }

    private function normalizeContentSections(string $html): string
    {
        $html = preg_replace(
            '/<div class="well[^"]*">\s*<p><em>&ldquo;(.*?)&rdquo;<\/em><\/p>\s*<p><em>-?\s*(.*?)<\/em><\/p>\s*<\/div>/is',
            '<blockquote><p>&ldquo;$1&rdquo;</p><cite>&mdash; $2</cite></blockquote>',
            $html
        ) ?? $html;

        $html = preg_replace('/<p><strong>(.*?)<\/strong><\/p>/is', '<p class="lead">$1</p>', $html) ?? $html;

        if (! str_contains($html, 'content-section')) {
            $chunks = preg_split('/(?=<h2\b)/i', $html) ?: [];
            $wrapped = [];
            foreach ($chunks as $chunk) {
                $chunk = trim($chunk);
                if ($chunk === '') {
                    continue;
                }
                $wrapped[] = '<section class="content-section">'.$chunk.'</section>';
            }
            if ($wrapped !== []) {
                $html = implode("\n", $wrapped);
            }
        }

        return $html;
    }

    private function rewriteUrls(string $html): string
    {
        $html = preg_replace_callback(
            '/\s(href|src)=["\']([^"\']+)["\']/i',
            function (array $m): string {
                $url = $m[2];

                if (str_starts_with($url, 'mailto:') || str_starts_with($url, 'tel:') || str_starts_with($url, 'http')) {
                    return ' '.$m[1].'="'.$url.'"';
                }

                if (str_starts_with($url, '/')) {
                    $path = trim($url, '/');
                    foreach (self::ROUTE_MAP as $legacy => $route) {
                        if ($path === $legacy || str_starts_with($path, $legacy.'/')) {
                            return ' '.$m[1].'="'.$route.'"';
                        }
                    }

                    if (str_starts_with($url, '/Uploads/') || str_starts_with($url, '/Content/')) {
                        return ' '.$m[1].'="'.self::BASE_URL.$url.'"';
                    }
                }

                return ' '.$m[1].'="'.$url.'"';
            },
            $html
        ) ?? $html;

        return $html;
    }

    private function extractExcerpt(?string $html): ?string
    {
        if ($html === null) {
            return null;
        }

        if (preg_match('/<p class="lead">(.*?)<\/p>/is', $html, $m)) {
            return $this->cleanText($m[1]);
        }

        if (preg_match('/<section class="content-section">\s*<p>(.*?)<\/p>/is', $html, $m)) {
            return $this->cleanText($m[1]);
        }

        return null;
    }

    private function absoluteUrl(string $path): string
    {
        if (str_starts_with($path, 'http')) {
            return $path;
        }

        return self::BASE_URL.'/'.ltrim($path, '/');
    }

    private function portfolioImagePath(string $src): ?string
    {
        if (! str_contains($src, '/Uploads/')) {
            return null;
        }

        return $this->absoluteUrl($src);
    }

    private function matchFirst(string $html, string $pattern): ?string
    {
        if (! preg_match($pattern, $html, $m)) {
            return null;
        }

        if (! isset($m[1])) {
            return null;
        }

        return trim(html_entity_decode(strip_tags($m[1]), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    }

    private function cleanText(string $html): string
    {
        $text = html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = preg_replace('/\s+/u', ' ', $text) ?? $text;

        return trim($text);
    }

    private function cleanInlineHtml(string $html): string
    {
        return trim(html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    }
}
