<?php

namespace Database\Seeders;

use App\Models\PaymentRequest;
use Illuminate\Database\Seeder;

class PaymentRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentRequests = [
            [
                "property_id" => 1,
                "user_id"     => 3,
                "resident_id" => 4,
                "category_id" => 22, // Utilities
                "amount"      => 150.00,
                "due_date"    => '2024-06-10',
                "status"      => 'due',
                "description" => "Electricity and Water bill for May 2024.",
            ],
            [
                "property_id" => 1,
                "user_id"     => 3,
                "resident_id" => 4,
                "category_id" => 23, // Maintenance
                "amount"      => 75.50,
                "due_date"    => '2024-06-05',
                "status"      => 'due',
                "description" => "A/C servicing and filter replacement fee.",
            ],
            [
                "property_id" => 1,
                "user_id"     => 3,
                "resident_id" => 4,
                "category_id" => 24, // Fine/Penalty
                "amount"      => 50.00,
                "due_date"    => '2024-06-01',
                "status"      => 'due',
                "description" => "Late trash disposal penalty.",
            ],
            [
                "property_id" => 1,
                "user_id"     => 3,
                "resident_id" => 4,
                "category_id" => 25, // Service Charge
                "amount"      => 200.00,
                "due_date"    => '2024-06-15',
                "status"      => 'due',
                "description" => "Monthly building security and lift maintenance charge.",
            ],
            [
                "property_id" => 1,
                "user_id"     => 3,
                "resident_id" => 4,
                "category_id" => 26, // Cleaning
                "amount"      => 30.00,
                "due_date"    => '2024-06-12',
                "status"      => 'due',
                "description" => "External window cleaning service.",
            ],
        ];

        foreach ($paymentRequests as $request) {
            PaymentRequest::create($request);
        }
    }
}
