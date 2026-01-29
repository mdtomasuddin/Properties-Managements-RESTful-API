<?php

namespace Database\Seeders;

use App\Models\SecurityContact;
use Illuminate\Database\Seeder;

class SecurityContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $securityContacts = [
            [
                "property_id" => 1,
                "category_id" => 27, // 24/7 Security Desk
                "phone"       => "01875675723",
                "description" => "Main entrance security desk available 24/7 for guest check-ins and general inquiries.",
                "user_id"     => 3,
            ],
            [
                "property_id" => 1,
                "category_id" => 28, // Emergency Response Team
                "phone"       => "01999888777",
                "description" => "Immediate response team for fire, medical, or critical security breaches.",
                "user_id"     => 3,
            ],
            [
                "property_id" => 1,
                "category_id" => 29, // 24/7 Security Desk (Alt)
                "phone"       => "01777666555",
                "description" => "Secondary security line for the North Gate and back-office coordination.",
                "user_id"     => 3,
            ],
            [
                "property_id" => 1,
                "category_id" => 30, // CCTV Surveillance
                "phone"       => "01555444333",
                "description" => "Monitoring room for reporting suspicious activity or requesting footage review.",
                "user_id"     => 3,
            ],
        ];

        foreach ($securityContacts as $contact) {
            SecurityContact::create($contact);
        }
    }
}
