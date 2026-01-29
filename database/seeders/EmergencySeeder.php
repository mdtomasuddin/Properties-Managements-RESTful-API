<?php

namespace Database\Seeders;

use App\Models\Emergency;
use Illuminate\Database\Seeder;

class EmergencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $emergencyData = [
            [
                "property_id" => 1,
                "category_id" => 7, // Fire Emergency
                "user_type"   => "resident",
                "location"    => "Block A, Floor 3",
                "description" => "Smoke detected in the hallway near the elevator.",
                "user_id"     => 4,
            ],
            [
                "property_id" => 1,
                "category_id" => 8, // Earthquake Alert
                "user_type"   => "resident",
                "location"    => "Dhaka",
                "description" => "Minor tremors felt; checking building structural integrity.",
                "user_id"     => 4,
            ],
            [
                "property_id" => 1,
                "category_id" => 9, // Medical Emergency
                "user_type"   => "resident",
                "location"    => "Apartment 402",
                "description" => "Resident requires immediate paramedic assistance.",
                "user_id"     => 4,
            ],
            [
                "property_id" => 1,
                "category_id" => 10, // Security Alert
                "user_type"   => "resident",
                "location"    => "Main Gate",
                "description" => "Unauthorized individual attempting to enter the premises.",
                "user_id"     => 4,
            ],
            [
                "property_id" => 1,
                "category_id" => 11, // Utility Emergency
                "user_type"   => "resident",
                "location"    => "Basement",
                "description" => "Main gas line leak detected; immediate shutoff required.",
                "user_id"     => 4,
            ],
            [
                "property_id" => 1,
                "category_id" => 12, // Custom Emergency
                "user_type"   => "resident",
                "location"    => "Dhaka",
                "description" => "General emergency report regarding building safety protocols.",
                "user_id"     => 4,
            ],
            //user id 3 send emergency
            [
                "property_id" => 1,
                "category_id" => 7, // Fire Emergency
                "user_type"   => "resident",
                "location"    => "Block B, Floor 2",
                "description" => "Fire alarm triggered in the kitchen area.",
                "user_id"     => 3,
            ],
            [
                "property_id" => 1,
                "category_id" => 8, // Earthquake Alert
                "user_type"   => "resident",
                "location"    => "Chittagong",
                "description" => "Strong tremors felt; evacuating the building.",
                "user_id"     => 3,
            ],
            [
                "property_id" => 1,
                "category_id" => 9, // Medical Emergency
                "user_type"   => "resident",
                "location"    => "Apartment 305",
                "description" => "Resident experiencing severe allergic reaction; needs help.",
                "user_id"     => 3,
            ],
            [
                "property_id" => 1,
                "category_id" => 10, // Security Alert
                "user_type"   => "resident",
                "location"    => "Main Gate",
                "description" => "Unauthorized individual attempting to enter the premises.",
                "user_id"     => 3,
            ]
        ];

        foreach ($emergencyData as $data) {
            Emergency::create($data);
        }
    }
}
