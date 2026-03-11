<?php

namespace Database\Seeders;

use App\Models\Payment;
use Illuminate\Database\Seeder;

use function Symfony\Component\Clock\now;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentData = [
            [
                "user_id"        => 4,
                "property_id"    => 1,
                "total_amount"   => "505.50",
                "payment_date"   => now(),
                "payment_method" => "cash",
                "account_name"   => null,
                "account_number" => null,
                "bank_name"      => null,
                "branch_name"    => null,
                "swift_code"     => null,
            ],
            [
                "user_id"        => 4,
                "property_id"    => 1,
                "total_amount"   => "505.50",
                "payment_date"   => now(),
                "payment_method" => "cash",
                "account_name"   => null,
                "account_number" => null,
                "bank_name"      => null,
                "branch_name"    => null,
                "swift_code"     => null,
            ],
            [
                "user_id"        => 3,
                "property_id"    => 1,
                "total_amount"   => "505.50",
                "payment_date"   => now(),
                "payment_method" => "cash",
                "account_name"   => null,
                "account_number" => null,
                "bank_name"      => null,
                "branch_name"    => null,
                "swift_code"     => null,
            ],

        ];

        foreach ($paymentData as $index => $data) {
            $data['invoice_number'] = 'INV-' . date('Ymd') . '-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT);
            Payment::create($data);
        }
    }
}
