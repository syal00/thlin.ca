<?php

namespace Tests\Feature;

use App\Models\Page;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InlineEditingTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_admin_can_inline_update_page_hero_title(): void
    {
        $admin = User::firstOrFail();
        $page = Page::published()->where('slug', 'healthline')->firstOrFail();

        $this->actingAs($admin)
            ->patchJson(route('admin.inline-update'), [
                'model' => 'page',
                'id' => $page->id,
                'field' => 'hero_title',
                'value' => 'Updated Hero Heading',
            ])
            ->assertOk()
            ->assertJson(['success' => true]);

        $this->assertSame('Updated Hero Heading', $page->fresh()->hero_title);
    }

    public function test_admin_can_inline_update_page_body_with_safe_html(): void
    {
        $admin = User::firstOrFail();
        $page = Page::published()->where('slug', 'us')->firstOrFail();

        $this->actingAs($admin)
            ->patchJson(route('admin.inline-update'), [
                'model' => 'page',
                'id' => $page->id,
                'field' => 'body',
                'value' => '<p>Updated body</p><script>alert(1)</script>',
            ])
            ->assertOk();

        $this->assertStringContainsString('<p>Updated body</p>', (string) $page->fresh()->body);
        $this->assertStringNotContainsString('<script>', (string) $page->fresh()->body);
    }

    public function test_admin_can_inline_update_site_setting_by_key(): void
    {
        $admin = User::firstOrFail();

        $this->actingAs($admin)
            ->patchJson(route('admin.inline-update'), [
                'model' => 'sitesetting',
                'key' => 'footer_description',
                'field' => 'value',
                'value' => 'Updated footer description text.',
            ])
            ->assertOk();

        $this->assertSame('Updated footer description text.', SiteSetting::getValue('footer_description'));
    }

    public function test_admin_can_inline_update_navigation_label(): void
    {
        $admin = User::firstOrFail();
        $page = Page::published()->where('slug', 'home')->firstOrFail();

        $this->actingAs($admin)
            ->patchJson(route('admin.inline-update'), [
                'model' => 'page',
                'id' => $page->id,
                'field' => 'navigation_label',
                'value' => 'Homepage',
            ])
            ->assertOk();

        $this->assertSame('Homepage', $page->fresh()->navigation_label);
    }

    public function test_inline_update_rejects_disallowed_fields(): void
    {
        $admin = User::firstOrFail();
        $page = Page::published()->where('slug', 'home')->firstOrFail();

        $this->actingAs($admin)
            ->patchJson(route('admin.inline-update'), [
                'model' => 'page',
                'id' => $page->id,
                'field' => 'slug',
                'value' => 'hacked-slug',
            ])
            ->assertForbidden();
    }

    public function test_inline_update_strips_script_tags_from_rich_text(): void
    {
        $admin = User::firstOrFail();
        $page = Page::published()->where('slug', 'home')->firstOrFail();

        $this->actingAs($admin)
            ->patchJson(route('admin.inline-update'), [
                'model' => 'page',
                'id' => $page->id,
                'field' => 'excerpt',
                'type' => 'richtext',
                'value' => '<p>Safe text</p><script>alert(1)</script>',
            ])
            ->assertOk();

        $this->assertStringContainsString('Safe text', (string) $page->fresh()->excerpt);
        $this->assertStringNotContainsString('<script>', (string) $page->fresh()->excerpt);
    }

    public function test_admin_can_inline_update_excerpt_with_safe_rich_text(): void
    {
        $admin = User::firstOrFail();
        $page = Page::published()->where('slug', 'home')->firstOrFail();

        $this->actingAs($admin)
            ->patchJson(route('admin.inline-update'), [
                'model' => 'page',
                'id' => $page->id,
                'field' => 'excerpt',
                'type' => 'richtext',
                'value' => '<p><strong>Bold</strong> intro</p>',
            ])
            ->assertOk();

        $this->assertStringContainsString('<strong>Bold</strong>', (string) $page->fresh()->excerpt);
    }

    public function test_inline_update_strips_script_tags_from_plain_text(): void
    {
        $admin = User::firstOrFail();
        $page = Page::published()->where('slug', 'home')->firstOrFail();

        $this->actingAs($admin)
            ->patchJson(route('admin.inline-update'), [
                'model' => 'page',
                'id' => $page->id,
                'field' => 'hero_title',
                'type' => 'text',
                'value' => 'Safe text<script>alert(1)</script>',
            ])
            ->assertOk();

        $this->assertSame('Safe text', $page->fresh()->hero_title);
    }

    public function test_guest_cannot_inline_update(): void
    {
        $page = Page::published()->where('slug', 'home')->firstOrFail();

        $this->patchJson(route('admin.inline-update'), [
            'model' => 'page',
            'id' => $page->id,
            'field' => 'title',
            'value' => 'Hacked',
        ])->assertRedirect();
    }

    public function test_inline_update_rejects_unknown_site_setting_key(): void
    {
        $admin = User::firstOrFail();

        $this->actingAs($admin)
            ->patchJson(route('admin.inline-update'), [
                'model' => 'sitesetting',
                'key' => 'made_up_setting_key',
                'field' => 'value',
                'value' => 'Should not save',
            ])
            ->assertForbidden();
    }
}
