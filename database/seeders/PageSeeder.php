<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pages')->truncate();

        $pages = [
            ['parent_id' => null, 'slug' => 'page-1', 'title' => 'Page 1', 'content' => 'Page 1 Content','created_at' => date("Y-m-d H:i:s"),'updated_at' => date("Y-m-d H:i:s")],
            ['parent_id' => 1, 'slug' => 'page-2', 'title' => 'Page 2', 'content' => 'Page 2 Content','created_at' => date("Y-m-d H:i:s"),'updated_at' => date("Y-m-d H:i:s")],
            ['parent_id' => 2, 'slug' => 'page-1-child', 'title' => 'Page 1 Child', 'content' => 'Another Content','created_at' => date("Y-m-d H:i:s"),'updated_at' => date("Y-m-d H:i:s")],
            ['parent_id' => 2, 'slug' => 'page-3', 'title' => 'Page 3', 'content' => 'Page 3 Content','created_at' => date("Y-m-d H:i:s"),'updated_at' => date("Y-m-d H:i:s")],
            ['parent_id' => 4, 'slug' => 'page-4', 'title' => 'Page 4', 'content' => 'Page 4 Content','created_at' => date("Y-m-d H:i:s"),'updated_at' => date("Y-m-d H:i:s")],
            ['parent_id' => 4, 'slug' => 'page-5', 'title' => 'Page 5', 'content' => 'Nested Page 5','created_at' => date("Y-m-d H:i:s"),'updated_at' => date("Y-m-d H:i:s")],
            ['parent_id' => null, 'slug' => 'page-5', 'title' => 'Page 5', 'content' => 'Root Page 5','created_at' => date("Y-m-d H:i:s"),'updated_at' => date("Y-m-d H:i:s")]
        ];
        
        DB::table('pages')->insert($pages);

    }
}
