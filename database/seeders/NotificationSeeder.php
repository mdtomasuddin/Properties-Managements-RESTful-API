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
        // $notifications = [

        //     //Maintenance Type Notification 
        //     [
        //         'id'              => Str::uuid()->toString(),
        //         'type'            => 'App\\Notifications\\MaintenanceNotification',
        //         'notifiable_type' => 'App\\Models\\User',
        //         'notifiable_id'   => 3,
        //         'data'            => [
        //             'maintenance_id' => 1,
        //             'type'           => 'maintenance',
        //             'title'          => 'New Maintenance Request for-Skyline Apartments',
        //             'body'           => 'A new maintenance request has been created for-Name: Resident 1. Description: Water Leakage issue reported.',
        //         ],
        //         'read_at'         => null,
        //         'created_at'      => now(),
        //         'updated_at'      => now(),
        //     ],
        //     [
        //         'id'              => Str::uuid()->toString(),
        //         'type'            => 'App\\Notifications\\MaintenanceNotification',
        //         'notifiable_type' => 'App\\Models\\User',
        //         'notifiable_id'   => 3,
        //         'data'            => [
        //             'maintenance_id' => 2,
        //             'type'           => 'maintenance',
        //             'title'          => 'Electrical Issue - Skyline Apartments',
        //             'body'           => 'Living room outlets are not providing power.',
        //         ],
        //         'read_at'         => null,
        //         'created_at'      => now(),
        //         'updated_at'      => now(),
        //     ],

        //     //Emergency Type Notification 
        //     [
        //         'id'              => Str::uuid()->toString(),
        //         'type'            => 'App\\Notifications\\EmergencyNotification',
        //         'notifiable_type' => 'App\\Models\\User',
        //         'notifiable_id'   => 3,
        //         'data'            => [
        //             'emergency_id' => 1,
        //             'type'           => 'emergency',
        //             'title'          => 'New Emergency Request for-Skyline Apartments',
        //             'body'           => 'A new emergency request has been created for-Name: Resident 1. Description: Water Leakage issue reported.',
        //         ],
        //         'read_at'         => null,
        //         'created_at'      => now(),
        //         'updated_at'      => now(),
        //     ],
        //     [
        //         'id'              => Str::uuid()->toString(),
        //         'type'            => 'App\\Notifications\\EmergencyNotification',
        //         'notifiable_type' => 'App\\Models\\User',
        //         'notifiable_id'   => 3,
        //         'data'            => [
        //             'emergency_id' => 2,
        //             'type'           => 'emergency',
        //             'title'          => 'Electrical Issue - Skyline Apartments',
        //             'body'           => 'Living room outlets are not providing power.',
        //         ],
        //         'read_at'         => null,
        //         'created_at'      => now(),
        //         'updated_at'      => now(),
        //     ]
        // ];


        // //Notification Seeder
        // foreach ($notifications as $notification) {
        //     Notification::create($notification);
        // }
    }
}
