<?php

namespace App\Support;

class AboutBodyFormatter
{
    /**
     * Split About Us CMS body into an intro block and optional timeline.
     *
     * Paste timeline markup in admin as:
     * <ol class="org-timeline">
     *   <li class="timeline-step"><span class="timeline-year">2001</span><div class="timeline-body"><p>...</p></div></li>
     * </ol>
     *
     * Or use multiple <section class="content-section"> blocks — the first becomes
     * .about-intro; the rest are rendered as timeline steps (h2 = label/year).
     */
    public static function render(?string $html): string
    {
        if ($html === null || trim(strip_tags($html)) === '') {
            return '';
        }

        $formatted = CmsBodyFormatter::format($html);

        if (str_contains($formatted, 'org-timeline')) {
            return self::wrapIntroBeforeTimeline($formatted);
        }

        if (preg_match_all('/<section\s+class="content-section"[^>]*>.*?<\/section>/is', $formatted, $matches)) {
            $sections = $matches[0];

            if (count($sections) > 1) {
                $intro = '<div class="about-intro">'.$sections[0].'</div>';
                $timeline = self::sectionsToTimeline(array_slice($sections, 1));

                return $intro.$timeline;
            }

            if (count($sections) === 1) {
                return '<div class="about-intro">'.$sections[0].'</div>';
            }
        }

        return '<div class="about-intro">'.$formatted.'</div>';
    }

    private static function wrapIntroBeforeTimeline(string $html): string
    {
        $pos = stripos($html, '<ol class="org-timeline"');

        if ($pos === false) {
            $pos = stripos($html, "<ol class='org-timeline'");
        }

        if ($pos === false) {
            return $html;
        }

        $intro = trim(substr($html, 0, $pos));

        if ($intro === '') {
            return substr($html, $pos);
        }

        if (! str_contains($intro, 'about-intro')) {
            $intro = '<div class="about-intro">'.$intro.'</div>';
        }

        return $intro.substr($html, $pos);
    }

    /**
     * @param  array<int, string>  $sections
     */
    private static function sectionsToTimeline(array $sections): string
    {
        if ($sections === []) {
            return '';
        }

        $items = '';

        foreach ($sections as $section) {
            $label = '';
            $body = $section;

            if (preg_match('/<h2[^>]*>(.*?)<\/h2>/is', $section, $heading)) {
                $label = trim(strip_tags($heading[1]));
                $body = preg_replace('/<h2[^>]*>.*?<\/h2>/is', '', $section, 1) ?? $section;
            }

            $yearClass = preg_match('/^\d{4}\b/', $label) ? 'timeline-year' : 'timeline-label';

            $items .= '<li class="timeline-step">';
            $items .= $label !== '' ? '<span class="'.$yearClass.'">'.e($label).'</span>' : '';
            $items .= '<div class="timeline-body">'.trim($body).'</div>';
            $items .= '</li>';
        }

        return '<ol class="org-timeline" aria-label="Organization history">'.$items.'</ol>';
    }
}
