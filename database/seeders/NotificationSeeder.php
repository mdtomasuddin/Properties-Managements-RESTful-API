<?php

namespace Database\Seeders;

use App\Models\Notification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $notifications = [

            /**
             * Maintenance Notifications Seed -> Starting
             */
            // [
            //     'id'              => Str::uuid()->toString(),
            //     'type'            => 'App\\Notifications\\MaintenanceNotification',
            //     'notifiable_type' => 'App\\Models\\User',
            //     'notifiable_id'   => 3,
            //     'data'            => [
            //         'maintenance_id' => 1,
            //         'type'           => 'maintenance',
            //         'title'          => 'New Maintenance Request for-Water Leakage - Skyline Apartments',
            //         'body'           => 'A new maintenance request has been created for-Major leak under the kitchen sink affecting the cabinets',
            //     ],
            //     'read_at'         => null,
            //     'created_at'      => now(),
            //     'updated_at'      => now(),
            // ],
            // [
            //     'id'              => Str::uuid()->toString(),
            //     'type'            => 'App\\Notifications\\MaintenanceNotification',
            //     'notifiable_type' => 'App\\Models\\User',
            //     'notifiable_id'   => 3,
            //     'data'            => [
            //         'maintenance_id' => 2,
            //         'type'           => 'maintenance',
            //         'title'          => 'Electrical Issue - Skyline Apartments',
            //         'body'           => 'Living room outlets are not providing power.',
            //     ],
            //     'read_at'         => null,
            //     'created_at'      => now(),
            //     'updated_at'      => now(),
            // ],
            // [
            //     'id'              => Str::uuid()->toString(),
            //     'type'            => 'App\\Notifications\\MaintenanceNotification',
            //     'notifiable_type' => 'App\\Models\\User',
            //     'notifiable_id'   => 3,
            //     'data'            => [
            //         'maintenance_id' => 3,
            //         'type'           => 'maintenance',
            //         'title'          => 'HVAC Issue - Skyline Apartments',
            //         'body'           => 'AC is blowing warm air even when set to 18°C.',
            //     ],
            //     'read_at'         => null,
            //     'created_at'      => now(),
            //     'updated_at'      => now(),
            // ],
            // [
            //     'id'              => Str::uuid()->toString(),
            //     'type'            => 'App\\Notifications\\MaintenanceNotification',
            //     'notifiable_type' => 'App\\Models\\User',
            //     'notifiable_id'   => 3,
            //     'data'            => [
            //         'maintenance_id' => 4,
            //         'type'           => 'maintenance',
            //         'title'          => 'Cleaning Issue - Skyline Apartments',
            //         'body'           => 'Common area hallway requires deep cleaning. Please assign a cleaner.',
            //     ],
            //     'read_at'         => null,
            //     'created_at'      => now(),
            //     'updated_at'      => now(),
            // ],
            // [
            //     'id'              => Str::uuid()->toString(),
            //     'type'            => 'App\\Notifications\\MaintenanceNotification',
            //     'notifiable_type' => 'App\\Models\\User',
            //     'notifiable_id'   => 3,
            //     'data'            => [
            //         'maintenance_id' => 5,
            //         'type'           => 'maintenance',
            //         'title'          => 'Locks/Keys - Skyline Apartments',
            //         'body'           => 'Front door smart lock battery is dead. Please assign a locksmith.',
            //     ],
            //     'read_at'         => null,
            //     'created_at'      => now(),
            //     'updated_at'      => now(),
            // ],
            // [
            //     'id'              => Str::uuid()->toString(),
            //     'type'            => 'App\\Notifications\\MaintenanceNotification',
            //     'notifiable_type' => 'App\\Models\\User',
            //     'notifiable_id'   => 3,
            //     'data'            => [
            //         'maintenance_id' => 6,
            //         'type'           => 'maintenance',
            //         'title'          => 'Wall Damage - Skyline Apartments',
            //         'body'           => 'Wall is damaged. Please assign a contractor for repair.',
            //     ],
            //     'read_at'         => null,
            //     'created_at'      => now(),
            //     'updated_at'      => now(),
            // ],

            /***
             * Emergency Notification Seeder->
             */

            [
                'id'              => Str::uuid()->toString(),
                'type'            => 'App\\Notifications\\EmergencyNotification',
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id'   => 3,
                'data'            => [
                    "emergency_id" => 1,
                    "category"     => 10,
                    "type"         => "emergency",
                    "title"        => "🚨Alert-=>Security Alert. Location=>dhaka at Skyline Apartments",
                    "body"         => "Notices-Recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ip",
                    "location"     => "dhaka",
                ],
                'read_at'         => null,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'id'              => Str::uuid()->toString(),
                'type'            => 'App\\Notifications\\EmergencyNotification',
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id'   => 3,
                'data'            => [
                    'emergency_id' => 2,
                    'type'         => 'emergency',
                    'title'        => '🚨Alert-: Earthquake. Location: Skyline Apartments. Dhaka',
                    'body'         => 'Notices-Earthquake. Minor tremors felt; checking structural integrity.',
                    "location"     => "dhaka",
                ],
                'read_at'         => null,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'id'              => Str::uuid()->toString(),
                'type'            => 'App\\Notifications\\EmergencyNotification',
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id'   => 3,
                'data'            => [
                    'emergency_id' => 3,
                    'type'         => 'emergency',
                    'title'        => '🚨Alert-: Security. Location: Skyline Apartments. Main Gate',
                    'body'         => 'Notices-Security. Unauthorized individual attempting to enter premises.',
                    "location"     => "dhaka",
                ],
                'read_at'         => null,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'id'              => Str::uuid()->toString(),
                'type'            => 'App\\Notifications\\EmergencyNotification',
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id'   => 3,
                'data'            => [
                    'emergency_id' => 4,
                    'type'         => 'emergency',
                    'title'        => '🚨Alert-: Gas Leak. Location: Skyline Apartments. Basement',
                    'body'         => 'Notices-Utility. Main gas line leak detected; immediate shutoff required.',
                    "location"     => "dhaka",
                ],
                'read_at'         => null,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],

        ];

        //Notification Seeder
        foreach ($notifications as $notification) {
            Notification::create($notification);
        }
    }
}
