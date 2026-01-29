<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name'                 => 'admin',
                'email'                => 'admin@admin.com',
                'phone'                => null, // or add a phone number
                'password'             => Hash::make('12345678'),
                'avatar'               => null,
                'role'                 => "admin",
                'status'               => 'active',
                'reffer_code'          => '123466',
                'invitation_code'      => null,
                'is_otp_verified'      => true, // or false if not verified
                'email_verified_at'    => now(),
                'phone_verified_at'    => now(),
                'terms_and_conditions' => true,
                'created_at'           => now(),
                'updated_at'           => now(),
            ],
            [
                'name'                 => 'tomas',
                'email'                => 'mdtomasuddin1@gmail.com',
                'phone'                => null, // or add a phone number
                'password'             => Hash::make('12345678'),
                'avatar'               => null,
                'role'                 => "admin",
                'status'               => 'active',
                'reffer_code'          => '123455',
                'invitation_code'      => null,
                'is_otp_verified'      => true, // or false if not verified
                'email_verified_at'    => now(),
                'phone_verified_at'    => now(),
                'terms_and_conditions' => true,
                'created_at'           => now(),
                'updated_at'           => now(),
            ],

            [
                'name'                 => 'property_owner',
                'email'                => 'property_owner@gmail.com',
                'phone'                => '01811111111', // or add a phone number
                'password'             => Hash::make('12345678'),
                'avatar'               => null,
                'role'                 => "property_owner",
                'reffer_code'          => '123456',
                'invitation_code'      => null,
                'status'               => 'active',
                'is_otp_verified'      => true, // or false if not verified
                'email_verified_at'    => now(),
                'phone_verified_at'    => now(),
                'terms_and_conditions' => true,
                'created_at'           => now(),
                'updated_at'           => now(),
            ],
            [
                'name'                 => 'Resident 1',
                'email'                => 'resident@resident.com',
                'phone'                => '01822221111', // or add a phone number
                'password'             => Hash::make('12345678'),
                'avatar'               => null,
                'role'                 => "resident",
                'reffer_code'          => '123467',
                'invitation_code'      => '123456',
                'status'               => 'active',
                'is_otp_verified'      => true, // or false if not verified
                'email_verified_at'    => now(),
                'phone_verified_at'    => now(),
                'terms_and_conditions' => true,
                'created_at'           => now(),
                'updated_at'           => now(),
            ],
            [
                'name'                 => 'Azizul Islam',
                'email'                => 'azizul@gmail.com',
                'phone'                => '01822222222', // or add a phone number
                'password'             => Hash::make('12345678'),
                'avatar'               => null,
                'role'                 => "resident",
                'reffer_code'          => '123497',
                'invitation_code'      => '123456',
                'status'               => 'active',
                'is_otp_verified'      => true, // or false if not verified
                'email_verified_at'    => now(),
                'phone_verified_at'    => now(),
                'terms_and_conditions' => true,
                'created_at'           => now(),
                'updated_at'           => now(),
            ],
            [
                'name'                 => 'Rakibul Islam',
                'email'                => 'rakibul@gmail.com',
                'phone'                => '01833333333', // or add a phone number
                'password'             => Hash::make('12345678'),
                'avatar'               => null,
                'role'                 => "resident",
                'reffer_code'          => '123495',
                'invitation_code'      => '123456',
                'status'               => 'active',
                'is_otp_verified'      => true, // or false if not verified
                'email_verified_at'    => now(),
                'phone_verified_at'    => now(),
                'terms_and_conditions' => true,
                'created_at'           => now(),
                'updated_at'           => now(),
            ],
        ]);
    }
}
