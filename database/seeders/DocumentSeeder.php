<?php
namespace Database\Seeders;

use App\Models\Document;
use Illuminate\Database\Seeder;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $documents = [
            [
                'user_id'     => 1,
                'property_id' => 1,
                'category_id' => 21,
                'name'        => 'Lease Agreement',
                'file'        => 'https://images.unsplash.com/photo-1693045181198-531b47a82ed8?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTZ8fHBkZiUyMGZpbGV8ZW58MHx8MHx8fDA%3D',

            ],
            [
                'user_id'     => 1,
                'property_id' => 1,
                'category_id' => 19,
                'name'        => 'Maintenance Report',
                'file'        => 'https://images.unsplash.com/photo-1693045181198-531b47a82ed8?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTZ8fHBkZiUyMGZpbGV8ZW58MHx8MHx8fDA%3D',

            ],
            [
                'user_id'     => 1,
                'property_id' => 1,
                'category_id' => 21,
                'name'        => 'Property Image',
                'file'        => 'https://images.unsplash.com/photo-1693045181198-531b47a82ed8?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTZ8fHBkZiUyMGZpbGV8ZW58MHx8MHx8fDA%3D',

            ],
            [
                'user_id'     => 1,
                'property_id' => 1,
                'category_id' => 1,
                'name'        => 'Inspection Report',
                'file'        => 'https://images.unsplash.com/photo-1693045181198-531b47a82ed8?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTZ8fHBkZiUyMGZpbGV8ZW58MHx8MHx8fDA%3D',

            ],
            [
                'user_id'     => 1,
                'property_id' => 1,
                'category_id' => 20,
                'name'        => 'Tenant Agreement',
                'file'        => 'https://images.unsplash.com/photo-1693045181198-531b47a82ed8?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTZ8fHBkZiUyMGZpbGV8ZW58MHx8MHx8fDA%3D',

            ],
        ];

        foreach ($documents as $doc) {
            Document::create($doc);
        }
    }
}
