<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pages')->truncate();

        $now = Carbon::now();

        $pages = [
            ['parent_slug' => null, 'slug' => 'home', 'title' => 'Home', 'content' => 'Welcome to the homepage'],
            ['parent_slug' => 'home', 'slug' => 'about-us', 'title' => 'About Us', 'content' => 'Information about our company'],
            ['parent_slug' => 'about-us', 'slug' => 'team', 'title' => 'Our Team', 'content' => 'Meet our amazing team'],
            ['parent_slug' => 'team', 'slug' => 'john-doe', 'title' => 'John Doe', 'content' => 'CEO of the company'],
            ['parent_slug' => 'team', 'slug' => 'jane-smith', 'title' => 'Jane Smith', 'content' => 'CTO of the company'],
            ['parent_slug' => 'home', 'slug' => 'services', 'title' => 'Our Services', 'content' => 'Details of what we offer'],
            ['parent_slug' => 'services', 'slug' => 'web-development', 'title' => 'Web Development', 'content' => 'We build amazing websites'],
            ['parent_slug' => 'services', 'slug' => 'mobile-apps', 'title' => 'Mobile Apps', 'content' => 'We develop Android and iOS apps'],
            ['parent_slug' => 'services', 'slug' => 'consulting', 'title' => 'Consulting', 'content' => 'Business and tech consulting'],
            ['parent_slug' => 'web-development', 'slug' => 'frontend', 'title' => 'Frontend Development', 'content' => 'React, Vue, and Angular'],
            ['parent_slug' => 'web-development', 'slug' => 'backend', 'title' => 'Backend Development', 'content' => 'Laravel, Node.js, Django'],
            ['parent_slug' => 'home', 'slug' => 'contact', 'title' => 'Contact Us', 'content' => 'Get in touch with us'],
            ['parent_slug' => 'contact', 'slug' => 'support', 'title' => 'Support', 'content' => 'Customer support and FAQs'],
            ['parent_slug' => 'contact', 'slug' => 'sales', 'title' => 'Sales Inquiry', 'content' => 'Talk to our sales team'],
            ['parent_slug' => null, 'slug' => 'blog', 'title' => 'Blog', 'content' => 'Read our latest news'],
            ['parent_slug' => 'blog', 'slug' => 'tech', 'title' => 'Tech Articles', 'content' => 'Technology-related articles'],
            ['parent_slug' => 'blog', 'slug' => 'business', 'title' => 'Business Insights', 'content' => 'Business strategies and market trends'],
            ['parent_slug' => 'tech', 'slug' => 'ai', 'title' => 'AI Trends', 'content' => 'Latest in artificial intelligence'],
            ['parent_slug' => 'tech', 'slug' => 'cybersecurity', 'title' => 'Cybersecurity', 'content' => 'Best security practices'],
            ['parent_slug' => 'business', 'slug' => 'startups', 'title' => 'Startups', 'content' => 'Tips for new businesses']
        ];

        $insertedPages = [];

        foreach ($pages as $page) {
            $parentId = null;
            if ($page['parent_slug']) {
                $parentId = $insertedPages[$page['parent_slug']] ?? null;
            }

            $pageData = [
                'parent_id' => $parentId,
                'slug' => $page['slug'],
                'title' => $page['title'],
                'content' => $page['content'],
                'created_at' => $now,
                'updated_at' => $now
            ];

            $pageId = DB::table('pages')->insertGetId($pageData);

            // Store inserted page ID to resolve parent-child relationships dynamically
            $insertedPages[$page['slug']] = $pageId;
        }
    }
}
