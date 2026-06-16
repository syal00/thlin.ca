<?php

namespace Tests\Feature;

use App\Models\Page;
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

    public function test_inline_update_strips_script_tags(): void
    {
        $admin = User::firstOrFail();
        $page = Page::published()->where('slug', 'home')->firstOrFail();

        $this->actingAs($admin)
            ->patchJson(route('admin.inline-update'), [
                'model' => 'page',
                'id' => $page->id,
                'field' => 'excerpt',
                'value' => 'Safe text<script>alert(1)</script>',
            ])
            ->assertOk();

        $this->assertSame('Safe text', $page->fresh()->excerpt);
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
}
