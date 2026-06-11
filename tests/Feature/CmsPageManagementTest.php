<?php

namespace Tests\Feature;

use App\Models\Page;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CmsPageManagementTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_guest_cannot_access_admin_pages(): void
    {
        $this->get(route('admin.pages.index'))->assertRedirect(route('admin.login'));
    }

    public function test_authenticated_admin_can_access_admin_pages(): void
    {
        $admin = User::firstOrFail();

        $this->actingAs($admin)
            ->get(route('admin.pages.index'))
            ->assertOk();
    }

    public function test_published_custom_page_loads_publicly(): void
    {
        $page = Page::create([
            'title' => 'Privacy Policy',
            'slug' => 'privacy-policy',
            'section' => 'custom',
            'template' => 'standard',
            'page_type' => 'custom',
            'status' => 'published',
            'is_published' => true,
            'published_at' => now(),
            'body' => '<p>Privacy policy content</p>',
        ]);

        $this->get(route('custom-pages.show', $page->slug))->assertOk();
    }

    public function test_draft_custom_page_returns_not_found_publicly(): void
    {
        $page = Page::create([
            'title' => 'Draft Page',
            'slug' => 'draft-page',
            'section' => 'custom',
            'template' => 'standard',
            'page_type' => 'custom',
            'status' => 'draft',
            'is_published' => false,
            'body' => '<p>Draft content</p>',
        ]);

        $this->get(route('custom-pages.show', $page->slug))->assertNotFound();
    }

    public function test_guest_cannot_access_admin_media(): void
    {
        $this->get(route('admin.media.index'))->assertRedirect(route('admin.login'));
    }

    public function test_published_child_custom_page_loads_at_nested_url(): void
    {
        $parent = Page::published()->where('slug', 'products-services')->firstOrFail();

        $page = Page::create([
            'title' => 'Testing',
            'slug' => 'cms-testing-page',
            'parent_id' => $parent->id,
            'section' => 'custom',
            'template' => 'standard',
            'page_type' => 'custom',
            'status' => 'published',
            'is_published' => true,
            'published_at' => now(),
            'body' => '<p>Nested test page</p>',
        ]);

        $this->get(route('custom-pages.child.show', [$parent->slug, $page->slug]))->assertOk();
    }

    public function test_draft_child_custom_page_returns_not_found(): void
    {
        $parent = Page::published()->where('slug', 'about')->firstOrFail();

        $page = Page::create([
            'title' => 'Draft Child',
            'slug' => 'draft-child-page',
            'parent_id' => $parent->id,
            'section' => 'custom',
            'template' => 'standard',
            'page_type' => 'custom',
            'status' => 'draft',
            'is_published' => false,
            'body' => '<p>Draft child</p>',
        ]);

        $this->get(route('custom-pages.child.show', [$parent->slug, $page->slug]))->assertNotFound();
    }

    public function test_built_in_landing_pages_use_simple_full_url(): void
    {
        $about = Page::published()->where('slug', 'about')->firstOrFail();
        $productsServices = Page::published()->where('slug', 'products-services')->firstOrFail();

        $this->assertSame('/about', $about->full_url);
        $this->assertSame('/products-services', $productsServices->full_url);
        $this->assertNull($about->parent_id);
    }

    public function test_custom_child_page_uses_parent_slug_in_full_url(): void
    {
        $parent = Page::published()->where('slug', 'products-services')->firstOrFail();

        $page = Page::create([
            'title' => 'Testing',
            'slug' => 'testing',
            'parent_id' => $parent->id,
            'section' => 'custom',
            'template' => 'standard',
            'page_type' => 'custom',
            'status' => 'published',
            'is_published' => true,
            'published_at' => now(),
            'body' => '<p>Test</p>',
        ]);

        $this->assertSame('/products-services/testing', $page->full_url);
    }
}
