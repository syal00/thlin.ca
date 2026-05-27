<?php

namespace Database\Seeders;

use App\Models\NewsPost;
use Illuminate\Database\Seeder;

class NewsPostSeeder extends Seeder
{
    public function run(): void
    {
        NewsPost::updateOrCreate(
            ['slug' => 'sean-wong'],
            [
                'title' => 'THL Information Network Welcomes Sean Wong',
                'published_at' => '2021-03-01',
                'location' => 'London, ON',
                'excerpt' => 'thehealthline.ca Information Network (THLIN) today announced the appointment of Sean Wong as Executive Director. Sean is an exciting addition to the THLIN team, as he brings over 20 years of experience developing vision and leading teams to create and execute strategy to help some of the most vulnerable people in Canada and around the world. Sean comes to THLIN from the Ottawa Mission...',
                'body' => '<p>thehealthline.ca Information Network (THLIN) today announced the appointment of Sean Wong as Executive Director.</p><p>Sean is an exciting addition to the THLIN team, as he brings over 20 years of experience developing vision and leading teams to create and execute strategy to help some of the most vulnerable people in Canada and around the world. Sean comes to THLIN from the Ottawa Mission, where he served in senior leadership roles.</p>',
                'is_published' => true,
            ]
        );
    }
}
