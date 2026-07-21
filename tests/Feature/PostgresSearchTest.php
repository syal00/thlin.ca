<?php

namespace Tests\Feature;

use App\Models\NewsPost;
use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostgresSearchTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_postgres_search_is_case_insensitive(): void
    {
        $lowercase = Page::search('navigation')->pluck('id');
        $uppercase = Page::search('NAVIGATION')->pluck('id');

        $this->assertNotEmpty($lowercase);
        $this->assertSame($lowercase->all(), $uppercase->all());
    }

    public function test_postgres_search_supports_multiple_words(): void
    {
        $post = NewsPost::query()->where('slug', 'sean-wong')->firstOrFail();

        $this->assertTrue(NewsPost::search('Sean Wong')->get()->contains($post));
    }

    public function test_postgres_search_excludes_unpublished_content(): void
    {
        Page::query()->create([
            'slug' => 'private-search-result',
            'title' => 'Unlisted Quokka Material',
            'section' => 'general',
            'status' => 'draft',
            'is_published' => false,
        ]);

        $this->assertTrue(Page::search('quokka')->get()->isEmpty());
    }
}
