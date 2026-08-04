<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;

class CmsBodyFormatter
{
    public static function format(?string $html): string
    {
        if ($html === null || trim(strip_tags($html)) === '') {
            return '';
        }

        $html = preg_replace('/<\s*h1\b/i', '<h2', $html) ?? $html;
        $html = preg_replace('/<\/\s*h1\s*>/i', '</h2>', $html) ?? $html;

        $html = self::unwrapLayoutDivs($html);

        return self::sanitizeBrokenMedia($html);
    }

    private static function sanitizeBrokenMedia(string $html): string
    {
        libxml_use_internal_errors(true);

        $document = new DOMDocument('1.0', 'UTF-8');
        $document->loadHTML(
            '<?xml encoding="UTF-8"?><div id="thlin-cms-root">'.$html.'</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );

        $root = $document->getElementById('thlin-cms-root');

        if (! $root instanceof DOMElement) {
            return $html;
        }

        foreach (iterator_to_array($root->getElementsByTagName('img')) as $img) {
            if (! $img instanceof DOMElement || self::mediaSourceExists($img->getAttribute('src'))) {
                continue;
            }

            $img->parentNode?->removeChild($img);
        }

        foreach (iterator_to_array($root->getElementsByTagName('table')) as $table) {
            if (! $table instanceof DOMElement || ! self::tableIsEmpty($table)) {
                continue;
            }

            $table->parentNode?->removeChild($table);
        }

        $result = '';

        foreach ($root->childNodes as $child) {
            $result .= $document->saveHTML($child);
        }

        libxml_clear_errors();

        return trim($result);
    }

    private static function mediaSourceExists(string $src): bool
    {
        $src = trim($src);

        if ($src === '') {
            return false;
        }

        $path = parse_url($src, PHP_URL_PATH);

        if (! is_string($path) || $path === '') {
            return str_starts_with($src, 'http://') || str_starts_with($src, 'https://');
        }

        if (str_starts_with($path, '/images/') || str_starts_with($path, '/storage/')) {
            return is_file(self::publicDiskPath($path));
        }

        return true;
    }

    private static function publicDiskPath(string $webPath): string
    {
        $path = parse_url($webPath, PHP_URL_PATH);
        $relative = ltrim(is_string($path) ? $path : $webPath, '/');

        return dirname(__DIR__, 2).'/public/'.$relative;
    }

    private static function tableIsEmpty(DOMElement $table): bool
    {
        foreach ($table->getElementsByTagName('td') as $cell) {
            if (self::normalizedCellText($cell->textContent ?? '') !== '') {
                return false;
            }
        }

        foreach ($table->getElementsByTagName('th') as $cell) {
            if (self::normalizedCellText($cell->textContent ?? '') !== '') {
                return false;
            }
        }

        return true;
    }

    private static function normalizedCellText(string $text): string
    {
        $text = html_entity_decode(strip_tags($text), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = str_replace("\xc2\xa0", ' ', $text);

        return trim($text);
    }

    private static function unwrapLayoutDivs(string $html): string
    {
        libxml_use_internal_errors(true);

        $document = new DOMDocument('1.0', 'UTF-8');
        $document->loadHTML(
            '<?xml encoding="UTF-8"?><div id="thlin-cms-root">'.$html.'</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );

        $root = $document->getElementById('thlin-cms-root');

        if (! $root instanceof DOMElement) {
            return $html;
        }

        self::unwrapLayoutNodes($root);

        $result = '';

        foreach ($root->childNodes as $child) {
            $result .= $document->saveHTML($child);
        }

        libxml_clear_errors();

        return trim($result);
    }

    private static function unwrapLayoutNodes(DOMElement $parent): void
    {
        $changed = true;

        while ($changed) {
            $changed = false;

            foreach (iterator_to_array($parent->getElementsByTagName('div')) as $div) {
                if (! $div instanceof DOMElement || ! self::isLayoutOnlyDiv($div)) {
                    continue;
                }

                $divParent = $div->parentNode;

                if (! $divParent instanceof DOMNode) {
                    continue;
                }

                while ($div->firstChild) {
                    $divParent->insertBefore($div->firstChild, $div);
                }

                $divParent->removeChild($div);
                $changed = true;
            }
        }
    }

    private static function isLayoutOnlyDiv(DOMElement $div): bool
    {
        if ($div->getAttribute('id') !== '') {
            return false;
        }

        $class = trim($div->getAttribute('class'));

        if ($class === '') {
            return false;
        }

        $classes = array_filter(preg_split('/\s+/', $class) ?: []);

        if ($classes === []) {
            return false;
        }

        foreach ($classes as $single) {
            $isLayout = in_array($single, ['container', 'row', 'about-us', 'clearfix'], true)
                || preg_match('/^col-(xs|sm|md|lg|xl)(-\d+|-offset-\d+)$/', $single);

            if (! $isLayout) {
                return false;
            }
        }

        return true;
    }
}
