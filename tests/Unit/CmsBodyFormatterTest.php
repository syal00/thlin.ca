<?php

namespace Tests\Unit;

use App\Support\CmsBodyFormatter;
use PHPUnit\Framework\TestCase;

class CmsBodyFormatterTest extends TestCase
{
    public function test_it_demotes_body_headings_and_unwraps_legacy_layout_divs(): void
    {
        $html = <<<'HTML'
<section class="content-section">
<div class="container">
<div class="row">
<div class="about-us col-sm-10 col-sm-offset-1">
<h1>Our Story</h1>
<p>Founded in 2001.</p>
</div>
</div>
</div>
</section>
HTML;

        $formatted = CmsBodyFormatter::format($html);

        $this->assertStringContainsString('<h2>Our Story</h2>', $formatted);
        $this->assertStringNotContainsString('<h1>', $formatted);
        $this->assertStringNotContainsString('class="container"', $formatted);
        $this->assertStringNotContainsString('col-sm-10', $formatted);
        $this->assertStringContainsString('<p>Founded in 2001.</p>', $formatted);
    }
}
