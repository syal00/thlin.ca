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
                'body' => <<<'HTML'
<p>thehealthline.ca Information Network is a non-profit information company that is committed to making health and social service system navigation easier for everyone in Ontario. We connect patients and caregivers to health care providers, empower collaboration among health care professionals, and help planners enhance the health care system.</p>
<p>Our core services include:</p>
<ul>
<li>data collection</li>
<li>information management</li>
<li>website development</li>
</ul>
<p>Located in London with workers connecting remotely throughout the province, thehealthline.ca is a team-based, collaborative company that strives to provide a positive and inclusive work environment. We are innovators in data collection, maintenance, and sharing.</p>
<p><strong>Position</strong></p>
<p>We are looking for an experienced individual for a full-time permanent position to manage the network and office systems. The IT Administrator will maintain development and production web hosting environments as well as provide IT services for day-to-day office operations. While this position is primarily compatible with remote work, <strong>we require someone in proximity to the London area who can work with our office and data-centre hardware as needed</strong>. The candidate must be able to work independently, reporting to the Director of Technology.</p>
<h3>Responsibilities</h3>
<ul>
<li>Respond to and resolve network issues within specific time frames as required in our Service Level Agreement with clients</li>
<li>Troubleshoot and resolve project-based and operational network and hardware issues</li>
<li>Ensure network reliability outside business hours (using external website monitoring tools)</li>
<li>Support day-to-day office operations of remote employee devices and cloud services</li>
<li>Provide documentation of processes including outage reports and root cause analysis</li>
<li>Troubleshoot, configure, and resolve network and hardware issues</li>
<li>Work independently to establish and follow cybersecurity best practices</li>
</ul>
<h3>Qualifications</h3>
<ul>
<li>5 years of experience in Network Support or IT Administration</li>
<li>Must be able to support the following hardware and applications:
<ul>
<li>Windows Server and IIS web services</li>
<li>Microsoft SQL Server</li>
<li>Networking and VPN configuration</li>
<li>Virtualization with VMware</li>
<li>Office 365 and Azure</li>
</ul>
</li>
<li>Microsoft Server administration and CISCO Networking certifications are an asset</li>
</ul>
<p>THLIN is committed to employment equity, welcomes diversity in the workplace, and encourages applications from all qualified applicants. Recruitment-related accommodations for persons with disabilities are available upon request.</p>
<p>Applicants must be eligible to work in Canada and reside in Ontario.</p>
<p>Please apply by forwarding a current resume and cover letter to thehealthline.ca Information Network at <a href="mailto:admin@thehealthline.ca">admin@thehealthline.ca</a>.</p>
HTML,
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
                'body' => <<<'HTML'
<p>thehealthline.ca Information Network is a non-profit information company that is committed to making health and social service system navigation easier for everyone in Ontario. We connect patients and caregivers to health care providers, empower collaboration among health care professionals, and help planners enhance the health care system.</p>
<p>Our core services include:</p>
<ul>
<li>data collection</li>
<li>information management</li>
<li>website development</li>
</ul>
<p>Located in London with workers connecting remotely throughout the province, thehealthline.ca is a team-based, collaborative company that strives to provide a positive and inclusive work environment.</p>
<p><strong>Position</strong></p>
<p>We are looking for a self-motivated, meticulous person to fill a Data Specialist position. This is a great opportunity for anyone interested in information maintenance and management. Our ideal candidate has a passion for detail work, problem solving, and shares a dedication to our core values: accessibility, collaboration, innovation, quality, and inclusion.</p>
<h3>Responsibilities</h3>
<ul>
<li>Maintaining data (adhering to quality standards, editing and classifying data)</li>
<li>Collecting, writing, editing, and organizing information for online use</li>
<li>Indexing data using different classification schemes</li>
<li>Reviewing and consulting on French translations</li>
<li>Translating information into French as needed</li>
<li>Communicating effectively and professionally with colleagues and external stakeholders</li>
</ul>
<h3>Qualifications</h3>
<ul>
<li>Post-secondary degree/diploma in related field such as Information Science, Health Information or applicable work experience</li>
<li>Bilingual, able to understand, interpret, edit and create data records in English and French</li>
<li>Relevant experience in database administration and web content development, with a working knowledge of indexing systems</li>
<li>Excellent communication and relationship building skills, including personable phone manner</li>
<li>Well-developed writing and editing skills, with strong attention to detail</li>
<li>The ability to work with an interdisciplinary team</li>
<li>Self-motivated and able to grasp new concepts quickly</li>
<li>Familiarity with the local health care system and the field of information and referral (such as a certificate from an accredited I&amp;R program) is an asset</li>
</ul>
<p>THLIN is committed to employment equity, welcomes diversity in the workplace, and encourages applications from all qualified applicants. Recruitment-related accommodations for persons with disabilities are available upon request.</p>
<p>Applicants must be eligible to work in Canada and reside in Ontario.</p>
<p>Please apply by forwarding a current resume and cover letter to thehealthline.ca Information Network at <a href="mailto:admin@thehealthline.ca">admin@thehealthline.ca</a>.</p>
<p>For careers in the health care sector, please visit <a href="https://www.southwesthealthline.ca/healthCareerNetwork/index.aspx" target="_blank" rel="noopener">Health Careers</a> on <a href="https://www.southwesthealthline.ca/" target="_blank" rel="noopener">southwesthealthline.ca</a>.</p>
HTML,
                'is_active' => true,
            ]
        );
    }
}
