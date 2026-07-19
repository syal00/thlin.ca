<?php

namespace Database\Seeders;

use App\Models\PortfolioItem;
use Illuminate\Database\Seeder;

class PortfolioSeeder extends Seeder
{
    public function run(): void
    {
        $featured = [
            ['title' => 'AES Wellness Portal', 'excerpt' => 'The Anishinabek Education System (AES) is a unique and culturally-driven education system.', 'url' => 'https://aeswellnessportal.ca/', 'featured' => true, 'sort_order' => 1],
            ['title' => 'FamilyInfo', 'excerpt' => 'Family Centres and EarlyON Programs offer London and Middlesex families access to programming, services and information.', 'url' => 'https://familyinfo.ca/', 'featured' => true, 'sort_order' => 2],
            ['title' => 'Age-Friendly Sarnia-Lambton', 'excerpt' => 'Promotes civic, economic, and social participations throughout all stages of life.', 'url' => 'https://agefriendlysarnialambton.ca/', 'featured' => true, 'sort_order' => 3],
        ];

        $past = [
            ['title' => 'Atlas London', 'excerpt' => 'Online platform for young people to manage barriers to success and make important, positive decisions.', 'url' => 'https://atlaslondon.ca/', 'featured' => false, 'sort_order' => 4],
            ['title' => 'GTA Rehab Finder', 'excerpt' => 'Provider-centred digital service directory with more than 300 detailed records across the GTA.', 'url' => 'https://gtarehabfinder.ca/', 'featured' => false, 'sort_order' => 5],
            ['title' => 'Nipissing Service Collaborative', 'excerpt' => 'Supports individuals in the Nipissing District with mental health, addictions, housing, employment, and legal needs.', 'url' => 'https://www.sngnipissing.ca/', 'featured' => false, 'sort_order' => 6],
            ['title' => 'Behavioural Supports Ontario', 'excerpt' => 'Information about behaviour change in older adults and the BSO program initiative.', 'url' => 'https://behaviouralsupportsontario.ca/', 'featured' => false, 'sort_order' => 7],
            ['title' => 'Rehabilitative Care Ontario', 'excerpt' => 'Helps Ontarians find publicly funded rehabilitative services in their area.', 'url' => 'https://rehabcareontario.ca/', 'featured' => false, 'sort_order' => 8],
            ['title' => 'SWRWCP', 'excerpt' => 'Bringing together hospitals, long-term care as well as primary care and community service providers in Ontario\'s South West. (Defunct — retained for reference only.)', 'url' => 'https://swrwoundcareprogram.ca/', 'featured' => false, 'sort_order' => 9],
        ];

        foreach (array_merge($featured, $past) as $item) {
            PortfolioItem::updateOrCreate(['title' => $item['title']], $item);
        }
    }
}
