<?php

namespace Tests\Feature;

use App\Models\Page;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminNavAndThemeTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_admin_sidebar_does_not_include_quick_website_edits_link(): void
    {
        $admin = User::firstOrFail();

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertDontSee('Quick Website Edits', false)
            ->assertSee('Open Website Editor', false)
            ->assertSee('data-admin-open-editor', false)
            ->assertSee('data-admin-theme-toggle', false);
    }

    public function test_inline_editing_route_remains_available(): void
    {
        $admin = User::firstOrFail();

        $this->actingAs($admin)
            ->get(route('admin.inline-editing'))
            ->assertOk()
            ->assertSee('How inline editing works', false)
            ->assertSee('Published pages — open and edit inline', false);
    }

    public function test_dashboard_includes_inline_editing_help_and_published_pages_table(): void
    {
        $admin = User::firstOrFail();
        $publishedPage = Page::published()->orderBy('title')->firstOrFail();

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('id="inline-editing-help"', false)
            ->assertSee('Published pages — open and edit inline', false)
            ->assertSee($publishedPage->title, false)
            ->assertSee($publishedPage->full_url, false);
    }

    public function test_admin_layout_sets_theme_before_styles_and_includes_toggle_script(): void
    {
        $admin = User::firstOrFail();

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee('document.documentElement.setAttribute(\'data-theme\'', false);
        $response->assertSee('aria-pressed', false);
        $response->assertSee('js/admin-theme.js', false);
    }
}
