<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Payment;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            FAQSeeder::class,
            PropertySeeder::class,
            CategorySeeder::class, //live time use
            DocumentSeeder::class,
            PlanSeeder::class,
            ResidentSeeder::class,
            MaintenanceSeeder::class,
            EmergencySeeder::class,
            AnnouncementSeeder::class,
            PaymentRequestSeeder::class,
            SecurityContactSeeder::class,
            RuleRegulationSeeder::class,
            PaymentReminderSeeder::class,
            PaymentSeeder::class
        ]);
    }
}
