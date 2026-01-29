<?php

namespace Database\Seeders;

use App\Models\Property;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        $properties = [
            [
                'user_id'            => 3,
                'property_type'      => 'multi_unit',
                'property_name'      => 'Skyline Apartments',
                'property_address'   => 'Gulshan 2, Dhaka',
                'number_of_units'    => 24,
                'documents'          => [
                    'https://images.unsplash.com/photo-1560518883-ce09059eeffa',
                    'https://images.unsplash.com/photo-1632400990400-416d5460f337',
                ],
                'security_contact'   => '018111111111',
                'additional_details' => 'Luxury multi-unit complex with 24/7 security and rooftop access.',
            ],
            [
                'user_id'            => 3,
                'property_type'      => 'multi_unit',
                'property_name'      => 'Green Valley Residency',
                'property_address'   => 'Uttara Sector 4, Dhaka',
                'number_of_units'    => 12,
                'documents'          => [
                    'https://images.unsplash.com/photo-1560518883-ce09059eeffa',
                    'https://images.unsplash.com/photo-1632400990400-416d5460f337',
                ],
                'security_contact'   => '018222222222',
                'additional_details' => 'Family-oriented apartment building near the main park.',
            ],
            [
                'user_id'            => 3,
                'property_type'      => 'single_unit',
                'property_name'      => 'Lakeview Villa',
                'property_address'   => 'Banani, Dhaka',
                'number_of_units'    => 4,
                'documents'          => [
                    'https://images.unsplash.com/photo-1560518883-ce09059eeffa',
                    'https://images.unsplash.com/photo-1632400990400-416d5460f337',
                ],
                'security_contact'   => '018333333333',
                'additional_details' => 'Private single-unit villa with a lake view.',
            ],
        ];

        foreach ($properties as $property) {
            Property::updateOrCreate(
                ['property_name' => $property['property_name']],
                $property
            );
        }
    }
}
