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
                'section' => 'emergency',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
