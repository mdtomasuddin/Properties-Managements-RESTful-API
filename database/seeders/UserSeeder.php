<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'admin',
                'email' => 'admin@admin.com',
                'phone' => null, // or add a phone number
                'password' => Hash::make('12345678'),
                'avatar' => null,
                'role' => "admin",
                'status' => 'active',
                'is_otp_verified' => true, // or false if not verified
                'email_verified_at' => now(),
                'terms_and_conditions' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'tomas',
                'email' => 'mdtomasuddin1@gmail.com',
                'phone' => null, // or add a phone number
                'password' => Hash::make('12345678'),
                'avatar' => null,
                'role' => "admin",
                'status' => 'active',
                'is_otp_verified' => true, // or false if not verified
                'email_verified_at' => now(),
                'terms_and_conditions' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'resident',
                'email' => 'resident@resident.com',
                'phone' => null, // or add a phone number
                'password' => Hash::make('12345678'),
                'avatar' => null,
                'role' => "resident",
                'status' => 'active',
                'is_otp_verified' => true, // or false if not verified
                'email_verified_at' => now(),
                'terms_and_conditions' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'property_owner',
                'email' => 'property_owner@gmail.com',
                'phone' => null, // or add a phone number
                'password' => Hash::make('12345678'),
                'avatar' => null,
                'role' => "property_owner",
                'status' => 'active',
                'is_otp_verified' => true, // or false if not verified
                'email_verified_at' => now(),
                'terms_and_conditions' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],


        ]);
    }
}
