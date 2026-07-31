<?php

namespace App\Support;

class CmsEditorContent
{
    /**
     * Prepare stored HTML for the admin editor so images, tables, and embeds
     * display correctly when editing an existing page.
     */
    public static function prepareForEditor(?string $html): string
    {
        if ($html === null || trim($html) === '') {
            return '';
        }

        return self::normalizeMediaUrls($html);
    }

    public static function normalizeMediaUrls(string $html): string
    {
        $html = preg_replace_callback(
            '#\b(src|href|data-mce-src)=(["\'])(https?://[^"\']+)\2#i',
            static function (array $matches): string {
                $path = self::toRootRelativePath($matches[3]);

                return $matches[1].'='.$matches[2].$path.$matches[2];
            },
            $html
        ) ?? $html;

        return preg_replace_callback(
            '#\b(src|href|data-mce-src)=(["\'])(//[^"\']+)\2#i',
            static function (array $matches): string {
                $path = self::toRootRelativePath('https:'.$matches[3]);

                return $matches[1].'='.$matches[2].$path.$matches[2];
            },
            $html
        ) ?? $html;
    }

    private static function toRootRelativePath(string $url): string
    {
        if (str_starts_with($url, '/storage/') || str_starts_with($url, '/images/')) {
            return $url;
        }

        $parsed = parse_url($url);

        if (! is_array($parsed) || empty($parsed['path'])) {
            return $url;
        }

        $path = $parsed['path'];

        if (! str_starts_with($path, '/storage/') && ! str_starts_with($path, '/images/')) {
            return $url;
        }

        if (isset($parsed['query']) && $parsed['query'] !== '') {
            $path .= '?'.$parsed['query'];
        }

        return $path;
    }
}
