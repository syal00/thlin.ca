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
                'image' => 'images/news/sean-wong.png',
                'excerpt' => 'thehealthline.ca Information Network (THLIN) today announced the appointment of Sean Wong as Executive Director. Sean is an exciting addition to the THLIN team, as he brings over 20 years of experience developing vision and leading teams to create and execute strategy to help some of the most vulnerable people in Canada and around the world. Sean comes to THLIN from the Ottawa Mission Foundation, where he led that organization in supporting the life-changing programs offered at the Ottawa Mission.',
                'body' => <<<'HTML'
<p>Sean's drive to make a profound and meaningful difference in this world is evident in the types of organizations he chooses to work for — the Salvation Army (Executive Director), University Hospitals Kingston Foundation (Director of Development), and Oxfam Canada (Director of Fundraising & Marketing).</p>
<blockquote><p>"We are excited to have Sean take on this role," said Glen Kearns, Board Chair. "The Board looks forward to the new perspectives that Sean will bring as he helps THLIN continue to deliver world class health and social services system navigation solutions to the people of Ontario."</p></blockquote>
HTML,
                'is_published' => true,
            ]
        );
    }
}
