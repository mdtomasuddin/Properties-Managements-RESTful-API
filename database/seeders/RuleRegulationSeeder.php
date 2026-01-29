<?php

namespace Database\Seeders;

use App\Models\RuleRegulation;
use Illuminate\Database\Seeder;

class RuleRegulationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ruleRegulationData = [
            [
                "property_id" => 1,
                "user_id"     => 3,
                "title"       => "Noise and Quiet Hours",
                "description" => "To ensure the comfort of all residents, quiet hours are observed from 10:00 PM to 7:00 AM daily. Please keep music and social gatherings at a respectful volume during these times.",
            ],
            [
                "property_id" => 1,
                "user_id"     => 3,
                "title"       => "Waste Disposal & Recycling",
                "description" => "Household trash must be bagged and placed in designated bins. Large furniture or hazardous materials require a separate pickup request through the management office.",
            ],
            [
                "property_id" => 1,
                "user_id"     => 3,
                "title"       => "Parking and Vehicle Safety",
                "description" => "Assigned parking spots are for registered residents only. Guest parking is limited to 24 hours. A speed limit of 10 km/h must be maintained within the property premises at all times.",
            ],
            [
                "property_id" => 1,
                "user_id"     => 3,
                "title"       => "Smoking and Tobacco Use",
                "description" => "Smoking is strictly prohibited in the property. Smoking is allowed in designated areas, but only during designated hours.",
            ],
            //property_id 2
            [
                "property_id" => 2,
                "user_id"     => 3,

                "title"       => "Noise and Quiet Hours",
                "description" => "To ensure the comfort of all residents, quiet hours are observed from 10:00 PM to 7:00 AM daily. Please keep music and social gatherings at a respectful volume during these times.",
            ],
            [
                "property_id" => 2,
                "user_id"     => 3,
                "title"       => "Waste Disposal & Recycling",
                "description" => "Household trash must be bagged and placed in designated bins. Large furniture or hazardous materials require a separate pickup request through the management office.",
            ],
            [
                "property_id" => 2,
                "user_id"     => 3,
                "title"       => "Parking and Vehicle Safety",
                "description" => "Assigned parking spots are for registered residents only. Guest parking is limited to 24 hours. A speed limit of 10 km/h must be maintained within the property premises at all times.",
            ],
            [
                "property_id" => 2,
                "user_id"     => 3,
                "title"       => "Smoking and Tobacco Use",
                "description" => "Smoking is strictly prohibited in the property. Smoking is allowed in designated areas, but only during designated hours.",
            ],
            //property_id 3
            [
                "property_id" => 3,
                "user_id"     => 3,
                "title"       => "Noise and Quiet Hours",
                "description" => "To ensure the comfort of all residents, quiet hours are observed from 10:00 PM to 7:00 AM daily. Please keep music and social gatherings at a respectful volume during these times.",
            ],
            [
                "property_id" => 3,
                "user_id"     => 3,
                "title"       => "Waste Disposal & Recycling",
                "description" => "Household trash must be bagged and placed in designated bins. Large furniture or hazardous materials require a separate pickup request through the management office.",
            ],
            [
                "property_id" => 3,
                "user_id"     => 3,
                "title"       => "Parking and Vehicle Safety",
                "description" => "Assigned parking spots are for registered residents only. Guest parking is limited to 24 hours. A speed limit of 10 km/h must be maintained within the property premises at all times.",
            ],
            [
                "property_id" => 3,
                "user_id"     => 3,
                "title"       => "Smoking and Tobacco Use",
                "description" => "Smoking is strictly prohibited in the property. Smoking is allowed in designated areas, but only during designated hours.",
            ],
        ];

        foreach ($ruleRegulationData as $data) {
            RuleRegulation::create($data);
        }
    }
}
