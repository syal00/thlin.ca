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

        return self::unwrapLayoutDivs($html);
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
