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

    public function test_section_built_in_pages_use_section_prefixed_full_url(): void
    {
        $healthline = Page::published()->where('slug', 'healthline')->firstOrFail();
        $aboutUs = Page::published()->where('slug', 'us')->firstOrFail();

        $this->assertSame('/products/healthline', $healthline->full_url);
        $this->assertSame('/about/us', $aboutUs->full_url);
    }

    public function test_authenticated_admin_can_edit_existing_page(): void
    {
        $admin = User::firstOrFail();
        $page = Page::published()->where('slug', 'home')->firstOrFail();

        $this->actingAs($admin)
            ->get(route('admin.pages.edit', $page))
            ->assertOk()
            ->assertSee('Main Page Content');

        $this->actingAs($admin)
            ->put(route('admin.pages.update', $page), [
                'title' => $page->title,
                'hero_title' => 'Updated hero heading',
                'hero_subtitle' => $page->hero_subtitle,
                'body' => $page->body,
                'meta_description' => $page->meta_description,
            ])
            ->assertRedirect(route('admin.pages.index'))
            ->assertSessionHas('success');

        $this->assertSame('Updated hero heading', $page->fresh()->hero_title);
        $this->assertSame($admin->id, $page->fresh()->updated_by);
    }

    public function test_authenticated_admin_is_recorded_as_custom_page_creator(): void
    {
        $admin = User::firstOrFail();

        $this->actingAs($admin)
            ->post(route('admin.pages.store'), [
                'title' => 'Attribution Test',
                'slug' => 'attribution-test',
                'action' => 'draft',
            ])
            ->assertRedirect(route('admin.pages.index'));

        $page = Page::where('slug', 'attribution-test')->firstOrFail();

        $this->assertSame($admin->id, $page->created_by);
        $this->assertSame($admin->id, $page->updated_by);
    }

    public function test_custom_page_edit_form_shows_saved_values(): void
    {
        $admin = User::firstOrFail();
        $parent = Page::published()->where('slug', 'about')->firstOrFail();

        $page = Page::create([
            'title' => 'Annual Reports',
            'slug' => 'annual-reports-edit-test',
            'parent_id' => $parent->id,
            'section' => 'custom',
            'template' => 'standard',
            'page_type' => 'custom',
            'status' => 'published',
            'is_published' => true,
            'published_at' => now(),
            'hero_title' => 'Reports Hero',
            'hero_subtitle' => 'Intro text for reports',
            'body' => '<p>Existing report content</p>',
            'meta_description' => 'Reports meta description',
            'show_in_navigation' => true,
            'navigation_label' => 'Reports Menu',
            'sort_order' => 4,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.pages.edit', $page))
            ->assertOk()
            ->assertSee('value="Annual Reports"', false)
            ->assertSee('value="annual-reports-edit-test"', false)
            ->assertSee('value="Reports Hero"', false)
            ->assertSee('Intro text for reports', false)
            ->assertSee('<p>Existing report content</p>', false)
            ->assertSee('Reports meta description', false)
            ->assertSee('value="Reports Menu"', false)
            ->assertSee('value="4"', false);
    }

    public function test_custom_page_update_preserves_unchanged_fields(): void
    {
        $admin = User::firstOrFail();

        $page = Page::create([
            'title' => 'Policy Page',
            'slug' => 'policy-page-edit-test',
            'section' => 'custom',
            'template' => 'standard',
            'page_type' => 'custom',
            'status' => 'draft',
            'is_published' => false,
            'hero_title' => 'Policy Hero',
            'hero_subtitle' => 'Policy intro',
            'body' => '<p>Original policy body</p>',
            'meta_description' => 'Policy meta',
            'show_in_navigation' => true,
            'navigation_label' => 'Policies',
            'sort_order' => 7,
        ]);

        $this->actingAs($admin)
            ->put(route('admin.pages.update', $page), [
                'title' => 'Policy Page Updated',
                'slug' => $page->slug,
                'hero_title' => $page->hero_title,
                'hero_subtitle' => $page->hero_subtitle,
                'body' => $page->body,
                'meta_description' => $page->meta_description,
                'show_in_navigation' => '1',
                'navigation_label' => $page->navigation_label,
                'sort_order' => $page->sort_order,
                'action' => 'save',
            ])
            ->assertRedirect(route('admin.pages.index'));

        $page->refresh();

        $this->assertSame('Policy Page Updated', $page->title);
        $this->assertSame('draft', $page->status);
        $this->assertSame('<p>Original policy body</p>', $page->body);
        $this->assertSame('Policy Hero', $page->hero_title);
        $this->assertSame('Policy intro', $page->hero_subtitle);
        $this->assertSame('Policy meta', $page->meta_description);
        $this->assertTrue($page->show_in_navigation);
        $this->assertSame('Policies', $page->navigation_label);
        $this->assertSame(7, $page->sort_order);
    }

    public function test_custom_page_update_does_not_wipe_body_when_empty_submit(): void
    {
        $admin = User::firstOrFail();

        $page = Page::create([
            'title' => 'Keep Body Page',
            'slug' => 'keep-body-page-test',
            'section' => 'custom',
            'template' => 'standard',
            'page_type' => 'custom',
            'status' => 'published',
            'is_published' => true,
            'published_at' => now(),
            'body' => '<p>Important saved content</p>',
        ]);

        $this->actingAs($admin)
            ->put(route('admin.pages.update', $page), [
                'title' => 'Keep Body Page',
                'slug' => $page->slug,
                'hero_title' => 'New heading only',
                'hero_subtitle' => null,
                'body' => '',
                'meta_description' => null,
                'action' => 'save',
            ])
            ->assertRedirect(route('admin.pages.index'));

        $page->refresh();

        $this->assertSame('New heading only', $page->hero_title);
        $this->assertSame('<p>Important saved content</p>', $page->body);
    }

    public function test_public_home_shares_cms_page_for_admin_edit_link(): void
    {
        $admin = User::firstOrFail();

        $this->actingAs($admin)
            ->get(route('home'))
            ->assertOk()
            ->assertSee('Edit This Page');
    }
}
