<?php

namespace Database\Seeders;

use App\Models\Career;
use Illuminate\Database\Seeder;

class CareerSeeder extends Seeder
{
    public function run(): void
    {
        Career::updateOrCreate(
            ['slug' => 'it-administrator'],
            [
                'title' => 'IT Administrator',
                'location' => 'London, ON (remote with proximity to London required)',
                'employment_type' => 'Full-time permanent',
                'posted_at' => '2025-12-11',
                'closes_at' => '2026-01-09',
                'body' => '<p><strong>Organization:</strong> thehealthline.ca Information Network (non-profit)</p><h3>Responsibilities</h3><ul><li>Network and hardware issue resolution</li><li>SLA compliance and cybersecurity</li><li>Office IT support and documentation</li></ul><h3>Qualifications</h3><ul><li>5+ years Network Support or IT Administration</li><li>Windows Server/IIS, Microsoft SQL Server, VPN, VMware, Office 365/Azure</li><li>CISCO/Microsoft certifications an asset</li></ul><p>Apply: <a href="mailto:admin@thehealthline.ca">admin@thehealthline.ca</a></p>',
                'is_active' => true,
            ]
        );

        Career::updateOrCreate(
            ['slug' => 'bilingual-data-specialist'],
            [
                'title' => 'Bilingual Data Specialist',
                'location' => 'London, ON / remote throughout province',
                'employment_type' => 'Full-time',
                'posted_at' => '2025-12-11',
                'closes_at' => '2025-12-19',
                'body' => '<p><strong>Organization:</strong> thehealthline.ca Information Network (non-profit)</p><h3>Responsibilities</h3><ul><li>Data maintenance, collection, writing, editing, and organizing for online use</li><li>Indexing and French translation/review</li></ul><h3>Qualifications</h3><ul><li>Post-secondary in Information Science or Health Information</li><li>Bilingual (English/French); database and web content experience</li><li>I&amp;R certificate an asset</li></ul><p>Apply: <a href="mailto:admin@thehealthline.ca">admin@thehealthline.ca</a></p>',
                'is_active' => true,
            ]
        );
    }
}
