<?php

namespace Database\Seeders;

use App\Models\Property;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $propertyData = [
            'user_id'            => 3,
            'property_type'      => 'multi_unit',
            'property_name'      => 'Property A',
            'property_address'   => 'Dhaka',
            'number_of_units'    => 2,
            'documents'          => [
                'https://images.unsplash.com/photo-1560518883-ce09059eeffa?q=80&w=1073&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'https://images.unsplash.com/photo-1632400990400-416d5460f337?q=80&w=918&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            ],
            'security_contact'   => '018111111111',
            'additional_details' => 'Property A details description.',
        ];
        Property::create($propertyData);
    }
}
