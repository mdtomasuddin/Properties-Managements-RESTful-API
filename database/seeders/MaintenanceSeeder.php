<?php

namespace Database\Seeders;

use App\Models\Maintenance;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MaintenanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $maintenanceData = [
            [
                "category_id" => 13, // Water Leakage
                "property_id" => 1,
                "user_id" => 4,
                "description" => "Major leak under the kitchen sink affecting the cabinets.",
                "attachments" => ["https://images.unsplash.com/photo-1595856898575-9d187bd32fd6"],
            ],
            [
                "category_id" => 15, // Electrical Issue
                "property_id" => 1,
                "user_id" => 4,
                "description" => "Living room outlets are not providing power.",
                "attachments" => ["https://images.unsplash.com/photo-1595856898575-9d187bd32fd6"],
            ],
            [
                "category_id" => 16, // HVAC
                "property_id" => 1,
                "user_id" => 4,
                "description" => "AC is blowing warm air even when set to 18°C.",
                "attachments" => ["https://images.unsplash.com/photo-1595856898575-9d187bd32fd6"],
            ],
            [
                "category_id" => 16, // Cleaning Issue
                "property_id" => 1,
                "user_id" => 4,
                "description" => "Common area hallway requires deep cleaning.",
                "attachments" => ["https://images.unsplash.com/photo-1595856898575-9d187bd32fd6"],
            ],
            [
                "category_id" => 15, // Locks/Keys
                "property_id" => 1,
                "user_id" => 4,
                "description" => "Front door smart lock battery is dead.",
                "attachments" => ["https://images.unsplash.com/photo-1595856898575-9d187bd32fd6"],
            ],
            [
                "category_id" => 18, // Wall Damage
                "property_id" => 1,
                "user_id" => 4,
                "description" => "Large crack appearing in the bedroom drywall.",
                "attachments" => ["https://images.unsplash.com/photo-1595856898575-9d187bd32fd6"],
            ]
        ];

        foreach ($maintenanceData as $data) {
            Maintenance::create($data);
        }
    }
}
