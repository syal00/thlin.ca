<?php

namespace Tests\Unit;

use App\Support\CmsEditorContent;
use PHPUnit\Framework\TestCase;

class CmsEditorContentTest extends TestCase
{
    public function test_it_normalizes_storage_urls_to_root_relative_paths(): void
    {
        $html = '<p>Chart</p>'
            .'<img src="http://thlin.test/storage/media/chart.png" alt="Chart">'
            .'<a href="https://thlin.ca.test/storage/files/report.pdf">PDF</a>';

        $prepared = CmsEditorContent::prepareForEditor($html);

        $this->assertStringContainsString('src="/storage/media/chart.png"', $prepared);
        $this->assertStringContainsString('href="/storage/files/report.pdf"', $prepared);
        $this->assertStringNotContainsString('thlin.test', $prepared);
        $this->assertStringNotContainsString('thlin.ca.test', $prepared);
    }

    public function test_it_preserves_sections_tables_and_custom_html_structure(): void
    {
        $html = <<<'HTML'
<section class="content-section content-section--media">
<figure class="media-frame-wrap"><img src="/images/pages/service-image-placeholder.png" alt="Service Image"></figure>
</section>
<table><tr><td>Cell</td></tr></table>
HTML;

        $prepared = CmsEditorContent::prepareForEditor($html);

        $this->assertStringContainsString('<section class="content-section content-section--media">', $prepared);
        $this->assertStringContainsString('<table><tr><td>Cell</td></tr></table>', $prepared);
        $this->assertStringContainsString('/images/pages/service-image-placeholder.png', $prepared);
    }

    public function test_it_leaves_external_urls_unchanged(): void
    {
        $html = '<iframe src="https://www.youtube.com/embed/example"></iframe>';

        $prepared = CmsEditorContent::prepareForEditor($html);

        $this->assertSame($html, $prepared);
    }
}
