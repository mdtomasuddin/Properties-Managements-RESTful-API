<?php
namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResidentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('residents')->insert([
            [
                'user_id'                      => 3,
                'property_id'                  => 1,
                'unit_flat_number'             => '1A',
                'name'                         => 'Resident 1',
                'invitation_code'              => '123456',
                'phone'                        => '01822221111',
                'email'                        => 'resident1@example.com',
                'address'                      => 'Dhaka, Bangladesh',
                'payment_frequency'            => 'monthly',
                'auto_rent_reminder'           => true,
                'monthly_rent'                 => 1000.00,
                'fixed_monthly_recurring_cost' => 50.00,
                'due_amount'                   => 0.00,
                'advance_paid_amount'          => 500.00,
                'start_date'                   => Carbon::now()->toDateString(),
                'end_date'                     => Carbon::now()->addYear()->toDateString(),
                'agreement_file'               => json_encode([]),
                'tenant_file'                  => json_encode([]),
                'sms_invitation'               => 'Welcome to your new home! Your rent is due monthly.',
                'invitation_code'              => '123456',
                'status'                       => 'active',
                'created_at'                   => now(),
                'updated_at'                   => now(),
            ],
            [
                'user_id'                      => 3,
                'property_id'                  => 1,
                'unit_flat_number'             => '1B',
                'name'                         => 'azizul Islam',
                'invitation_code'              => '123456',
                'phone'                        => '01822222222',
                'email'                        => 'azizul@gmail.com',
                'address'                      => 'Dhaka, Bangladesh',
                'payment_frequency'            => 'monthly',
                'auto_rent_reminder'           => true,
                'monthly_rent'                 => 1000.00,
                'fixed_monthly_recurring_cost' => 50.00,
                'due_amount'                   => 0.00,
                'advance_paid_amount'          => 100.00,
                'start_date'                   => Carbon::now()->toDateString(),
                'end_date'                     => Carbon::now()->addYear()->toDateString(),
                'agreement_file'               => json_encode([]),
                'tenant_file'                  => json_encode([]),
                'sms_invitation'               => 'Welcome to your new home! Your rent is due monthly.',
                'invitation_code'              => '123456',
                'status'                       => 'active',
                'created_at'                   => now(),
                'updated_at'                   => now(),
            ],
            [
                'user_id'                      => 3,
                'property_id'                  => 1,
                'unit_flat_number'             => '1C',
                'name'                         => 'Rakibul Islam',
                'invitation_code'              => '123456',
                'phone'                        => '01833333333',
                'email'                        => 'rakibul@gmail.com',
                'address'                      => 'Dhaka, Bangladesh',
                'payment_frequency'            => 'monthly',
                'auto_rent_reminder'           => true,
                'monthly_rent'                 => 500.00,
                'fixed_monthly_recurring_cost' => 50.00,
                'due_amount'                   => 0.00,
                'advance_paid_amount'          => 100.00,
                'start_date'                   => Carbon::now()->toDateString(),
                'end_date'                     => Carbon::now()->addYear()->toDateString(),
                'agreement_file'               => json_encode([]),
                'tenant_file'                  => json_encode([]),
                'sms_invitation'               => 'Welcome to your new home! Your rent is due monthly.',
                'invitation_code'              => '123456',
                'status'                       => 'active',
                'created_at'                   => now(),
                'updated_at'                   => now(),
            ]
        ]);
    }
}
