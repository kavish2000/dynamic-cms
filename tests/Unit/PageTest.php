<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PageTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\PageSeeder::class); // Seed database
    }

    /** @test */
    public function it_can_fetch_a_page_by_slug()
    {
        $page = Page::where('slug', 'home')->first();
        $this->assertNotNull($page);
        $this->assertEquals('Home', $page->title);
    }

    /** @test */
    public function it_can_fetch_child_pages_of_a_parent_page()
    {
        $parent = Page::where('slug', 'home')->first();
        $this->assertNotNull($parent);
        $this->assertGreaterThan(0, $parent->children()->count());
    }

    /** @test */
    public function it_correctly_resolves_nested_page_relationships()
    {
        $child = Page::where('slug', 'team')->first();
        $this->assertNotNull($child);
        $this->assertNotNull($child->parent);
        $this->assertEquals('About Us', $child->parent->title);
    }

    /** @test */
    public function it_correctly_fetches_all_child_pages_recursively()
    {
        $parent = Page::where('slug', 'services')->first();
        $this->assertNotNull($parent);
        $children = $parent->children()->with('children')->get();
        $this->assertNotEmpty($children);
    }

    /** @test */
    public function it_verifies_deeply_nested_page_relationships()
    {
        $deepChild = Page::where('slug', 'ai')->first();
        $this->assertNotNull($deepChild);
        $this->assertNotNull($deepChild->parent);
        $this->assertEquals('Tech Articles', $deepChild->parent->title);
        $this->assertNotNull($deepChild->parent->parent);
        $this->assertEquals('Blog', $deepChild->parent->parent->title);
    }

    /** @test */
    public function it_verifies_a_page_without_children()
    {
        $page = Page::where('slug', 'cybersecurity')->first();
        $this->assertNotNull($page);
        $this->assertCount(0, $page->children);
    }
}
