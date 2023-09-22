<?php

namespace Assist\ServiceManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Assist\ServiceManagement\Models\ServiceRequestType;

class ServiceRequestTypeSeeder extends Seeder
{
    public function run(): void
    {
        ServiceRequestType::factory()
            ->count(13)
            ->sequence(
                ['name' => 'Admissions'],
                ['name' => 'Advising'],
                ['name' => 'ESL'],
                ['name' => 'Financial'],
                ['name' => 'Health'],
                ['name' => 'Holds'],
                ['name' => 'International'],
                ['name' => 'Other Support'],
                ['name' => 'Student Progress'],
                ['name' => 'Recruitment'],
                ['name' => 'Technology'],
                ['name' => 'Tutoring'],
                ['name' => 'Veterans'],
            )
            ->create();
    }
}
