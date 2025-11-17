<?php
namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $categories = [
            /**
             * section name =>announcement
             * type fixed and section name fixed.
             */
            [
                'user_id' => 1,
                'name'    => 'General Notice',
                'type'    => 'notice',
                'section' => 'announcement',
            ],
            [
                'user_id' => 1,
                'name'    => 'Utility Outage',
                'type'    => 'outage',
                'section' => 'announcement',
            ],
            [
                'user_id' => 1,
                'name'    => 'Maintenance Work',
                'type'    => 'maintenance',
                'section' => 'announcement',
            ],
            [
                'user_id' => 1,
                'name'    => 'Property Event',
                'type'    => 'event',
                'section' => 'announcement',
            ],
            [
                'user_id' => 1,
                'name'    => 'Policy Update',
                'type'    => 'policy',
                'section' => 'announcement',
            ],
            [
                'user_id' => 1,
                'name'    => 'Emergency Notice',
                'type'    => 'emergency',
                'section' => 'announcement',
            ],

            /**
             * Emergency Section
             * Emergency 6 category.
             */
            [
                'user_id' => 1,
                'name'    => 'Fire Emergency',
                'type'    => 'fire',
                'section' => 'emergency',
            ],
            [
                'user_id' => 1,
                'name'    => 'Earthquake Alert',
                'type'    => 'earthquake',
                'section' => 'emergency',
            ],
            [
                'user_id' => 1,
                'name'    => 'Medical Emergency',
                'type'    => 'medical',
                'section' => 'emergency',
            ],
            [
                'user_id' => 1,
                'name'    => 'Security Alert',
                'type'    => 'security',
                'section' => 'emergency',
            ],
            [
                'user_id' => 1,
                'name'    => 'Utility Emergency',
                'type'    => 'utility',
                'section' => 'emergency',
            ],
            [
                'user_id' => 1,
                'name'    => 'Custom Emergency',
                'type'    => 'custom',
                'section' => 'emergency',
            ],

            /**
             * Maintenance Section
             * maintenance 6 category.
             */
            [
                'user_id' => 1,
                'name'    => 'Water Leakage',
                'type'    => 'water_leakage',
                'section' => 'maintenance',
            ],
            [
                'user_id' => 1,
                'name'    => 'Electrical Issue',
                'type'    => 'electrical',
                'section' => 'maintenance',
            ],
            [
                'user_id' => 1,
                'name'    => 'HVAC',
                'type'    => 'hvac',
                'section' => 'maintenance',
            ],
            [
                'user_id' => 1,
                'name'    => 'Cleaning Issue',
                'type'    => 'cleaning_issue',
                'section' => 'maintenance',
            ],
            [
                'user_id' => 1,
                'name'    => 'Locks / Keys',
                'type'    => 'locks_keys',
                'section' => 'maintenance',
            ],
            [
                'user_id' => 1,
                'name'    => 'Wall Damage',
                'type'    => 'wall_damage',
                'section' => 'maintenance',
            ],

            /**
             * Document Section
             * Document 3 category.
             */
            [
                'user_id' => 1,
                'name'    => 'Rules & Regulation',
                'type'    => 'rules_regulation',
                'section' => 'document',
            ],
            [
                'user_id' => 1,
                'name'    => 'Lease Agreements',
                'type'    => 'lease_agreement',
                'section' => 'document',
            ],
            [
                'user_id' => 1,
                'name'    => 'Payment Documents',
                'type'    => 'payment_documents',
                'section' => 'document',
            ],
            /**
             * Payment Section
             */
            [
                'user_id' => 1,
                'name'    => 'Utilities',
                'type'    => 'utilities',
                'section' => 'payment',
            ],
            [
                'user_id' => 1,
                'name'    => 'Maintenance',
                'type'    => 'maintenance',
                'section' => 'payment',
            ],
            [
                'user_id' => 1,
                'name'    => 'Fine/Penalty',
                'type'    => 'fine_penalty',
                'section' => 'payment',
            ],
            [
                'user_id' => 1,
                'name'    => 'Service Charge',
                'type'    => 'service_charge',
                'section' => 'payment',
            ],
            [
                'user_id' => 1,
                'name'    => 'Cleaning',
                'type'    => 'cleaning',
                'section' => 'payment',
            ],

        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
