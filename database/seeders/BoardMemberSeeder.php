<?php

namespace Database\Seeders;

use App\Models\BoardMember;
use Illuminate\Database\Seeder;

class BoardMemberSeeder extends Seeder
{
    public function run(): void
    {
        $members = [
            ['name' => 'Rob Werstine', 'role' => 'Chair', 'sort_order' => 1, 'bio' => 'Physiotherapist, Advanced Practice Provider at LHSC Rapid Access Clinic for Low Back Pain, London. Adjunct teaching appointment, School of Graduate Studies, Western University. Helped create the first degree-granting Clinical Master\'s program for Physiotherapists in Orthopaedics at Western University (2007). Helped create a National Clinical Specialists Program (2012). Chaired the IFOMPT Conference in 2012. Founder of Key Clinical Skills Inc. (continuing education provider, since 2016).'],
            ['name' => 'Jane Berardini', 'role' => 'Secretary-Treasurer', 'sort_order' => 2, 'bio' => 'Retired Registered Nurse, worked in Public Health in Maternal and Child Health. Passion for youth and working with young mothers. Taught secondary school parenting and health care. Volunteers with a program supporting people who live in poverty.'],
            ['name' => 'Alan McCafferty', 'role' => 'Member', 'sort_order' => 3, 'bio' => 'Strategic Consultant, 20+ years at senior levels in start-ups and multi-national organizations. Founder of The Strategic Consulting Group. Lead consultant on international and military projects exceeding $1B. Trusted advisor for The Bridge in Kanata Youth Centre; Board Member of Saint Patrick\'s Home of Ottawa. Educated in Canada, USA and Europe in Engineering, Business Management and Information Security Management. Professional designations in Risk Management and Lean 6 Sigma.'],
            ['name' => 'Irene Wilson', 'role' => 'Member', 'sort_order' => 4, 'bio' => 'Leadership roles in healthcare, financial services, and management consulting. CPA, CMA, CHRL; undergrad in Gerontology from McMaster University; MBA from Wilfred Laurier University. Certified Master Black Belt in Lean Six Sigma. Volunteers with the Alzheimer\'s Society of Hamilton and Halton.'],
            ['name' => 'Shawn Goldmintz', 'role' => 'Member', 'sort_order' => 5, 'bio' => 'International BBA from Schulich School of Business; Juris Doctor from University of Windsor (2011). Member in good standing with the Law Society of Ontario. Founder and President of Water Babies Canada (swim school franchise). Career in commercial law specializing in franchise contracts.'],
            ['name' => 'Violetta Haveman', 'role' => 'Member', 'sort_order' => 6, 'bio' => 'Consultant, 20+ years in analytics, strategy, and process improvement. First 18 years in financial industry. Founder of Haveman Consulting. MBA from University of Fredericton; Lean Six Sigma Black Belt certified. Volunteers with non-profits and advisory committees.'],
        ];

        foreach ($members as $member) {
            BoardMember::updateOrCreate(['name' => $member['name']], $member);
        }
    }
}
