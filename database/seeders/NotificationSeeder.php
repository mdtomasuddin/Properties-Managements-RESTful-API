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
            [
                'id'              => Str::uuid()->toString(),
                'type'            => 'App\\Notifications\\MaintenanceNotification',
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id'   => 3,
                'data'            => [
                    'maintenance_id' => 1,
                    'type'           => 'maintenance',
                    'title'          => 'New Maintenance Request for-Water Leakage - Skyline Apartments',
                    'body'           => 'A new maintenance request has been created for-Major leak under the kitchen sink affecting the cabinets',
                ],
                'read_at'         => null,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'id'              => Str::uuid()->toString(),
                'type'            => 'App\\Notifications\\MaintenanceNotification',
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id'   => 3,
                'data'            => [
                    'maintenance_id' => 2,
                    'type'           => 'maintenance',
                    'title'          => 'Electrical Issue - Skyline Apartments',
                    'body'           => 'Living room outlets are not providing power.',
                ],
                'read_at'         => null,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'id'              => Str::uuid()->toString(),
                'type'            => 'App\\Notifications\\MaintenanceNotification',
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id'   => 3,
                'data'            => [
                    'maintenance_id' => 3,
                    'type'           => 'maintenance',
                    'title'          => 'HVAC Issue - Skyline Apartments',
                    'body'           => 'AC is blowing warm air even when set to 18°C.',
                ],
                'read_at'         => null,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'id'              => Str::uuid()->toString(),
                'type'            => 'App\\Notifications\\MaintenanceNotification',
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id'   => 3,
                'data'            => [
                    'maintenance_id' => 4,
                    'type'           => 'maintenance',
                    'title'          => 'Cleaning Issue - Skyline Apartments',
                    'body'           => 'Common area hallway requires deep cleaning. Please assign a cleaner.',
                ],
                'read_at'         => null,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'id'              => Str::uuid()->toString(),
                'type'            => 'App\\Notifications\\MaintenanceNotification',
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id'   => 3,
                'data'            => [
                    'maintenance_id' => 5,
                    'type'           => 'maintenance',
                    'title'          => 'Locks/Keys - Skyline Apartments',
                    'body'           => 'Front door smart lock battery is dead. Please assign a locksmith.',
                ],
                'read_at'         => null,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'id'              => Str::uuid()->toString(),
                'type'            => 'App\\Notifications\\MaintenanceNotification',
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id'   => 3,
                'data'            => [
                    'maintenance_id' => 6,
                    'type'           => 'maintenance',
                    'title'          => 'Wall Damage - Skyline Apartments',
                    'body'           => 'Wall is damaged. Please assign a contractor for repair.',
                ],
                'read_at'         => null,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],

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

            [
                'id'              => Str::uuid()->toString(),
                'type'            => 'App\\Notifications\\EmergencyNotification',
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id'   => 4,
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
                'notifiable_id'   => 4,
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

            /**
             * Payment Notifications Seed -> Starting
             */
            [
                'id'              => Str::uuid()->toString(),
                'type'            => 'App\\Notifications\\PaymentSendNotification',
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id'   => 3,
                'data'            => [
                    'payment_id'  => 3,
                    'type'        => 'payment',
                    'title'       => 'Payment-: at property name: Skyline Apartments.- total_amount : 505.50Taka.',
                    'body'        => 'Resident name: Resident 1 at property name: Skyline Apartments .- total_amount : 505.50Taka.',
                    'amount'      => '505.50',
                    'method'      => 'cash',
                    'resident_id' => 4,
                    'property_id' => 1,
                    'status'      => 'pending',
                ],
                'read_at'         => null,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'id'              => Str::uuid()->toString(),
                'type'            => 'App\\Notifications\\PaymentSendNotification',
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id'   => 3,
                'data'            => [
                    'payment_id'  => 4,
                    'type'        => 'payment',
                    'title'       => 'Payment-: at property name: Skyline Apartments.- total_amount : 1200.00Taka.',
                    'body'        => 'Resident name: Resident 2 at property name: Skyline Apartments .- total_amount : 1200.00Taka.',
                    'amount'      => '1200.00',
                    'method'      => 'cash',
                    'resident_id' => 5,
                    'property_id' => 1,
                    'status'      => 'paid',
                ],
                'read_at'         => null,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],

            //ending Resident Notifications Seed =============================================================================================================================================

            /**
             * Payment Request Notifications Seed -> Starting
             */
            [
                'id'              => Str::uuid()->toString(),
                'type'            => 'App\\Notifications\\PaymentRequestNotification',
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id'   => 4,
                'data'            => [
                    'payment_request_id' => 1,
                    'category'           => 23,
                    'type'               => 'payment_request',
                    'title'              => 'Payment Request-: Maintenance at Skyline Apartments.- Amount: 500.00 Due Date: 2026-02-28 00:00:00',
                    'body'               => 'Payment Maintenance Amount: 500.00Taka. Due Date: 28 Feb, 2026 - Description: ABCD',
                    'description'        => 'Payment Maintenance Amount: 500.00Taka. Due Date: 28 Feb, 2026 - Description: ABCD',
                    'amount'             => '500.00',
                    'due_date'           => now()->addDays(28),
                ],
                'read_at'         => null,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'id'              => Str::uuid()->toString(),
                'type'            => 'App\\Notifications\\PaymentRequestNotification',
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id'   => 4,
                'data'            => [
                    'payment_request_id' => 2,
                    'category'           => 24,
                    'type'               => 'payment_request',
                    'title'              => 'Payment Request-: Service Charge at Skyline Apartments.- Amount: 1200.00 Due Date: 2026-03-05 00:00:00',
                    'body'               => 'Monthly Service Charge Amount: 1200.00Taka. Due Date: 05 Mar, 2026 - Description: Monthly building maintenance fee.',
                    'description'        => 'Monthly Service Charge Amount: 1200.00Taka. Due Date: 05 Mar, 2026 - Description: Monthly building maintenance fee.',
                    'amount'             => '1200.00',
                    'due_date'           => now()->addDays(28),
                ],
                'read_at'         => null,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],

            /**
             * Announcement Notifications Seed -> Starting
             *
             */
            [
                'id'              => Str::uuid()->toString(),
                'type'            => 'App\\Notifications\\AnnouncementNotification',
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id'   => 4, // Resident User
                'data'            => [
                    'announcement_id' => 1,
                    'category'        => 4,
                    'type'            => 'announcement',
                    'title'           => 'Announcement-: Property Event at Skyline Apartments. Title: Community Meetup 2026',
                    'body'            => 'Notices-Property Event- Message: Join us for the annual community event at the rooftop garden.',
                    'message'         => 'Join us for the annual community event at the rooftop garden. Lorem Ipsum generators to spice up your designs.',
                ],
                'read_at'         => null,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'id'              => Str::uuid()->toString(),
                'type'            => 'App\\Notifications\\AnnouncementNotification',
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id'   => 4,
                'data'            => [
                    'announcement_id' => 2,
                    'category'        => 1, // General
                    'type'            => 'announcement',
                    'title'           => 'Announcement-: Maintenance Work at Skyline Apartments.',
                    'body'            => 'Notices-General- Message: Water supply will be interrupted for 2 hours tomorrow.',
                    'message'         => 'Scheduled maintenance on the main water line from 10 AM to 12 PM.',
                ],
                'read_at'         => null,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],

            /**
             * payment reminder Notifications Seed -> End
             *
             */

        ];

        //Notification Seeder
        foreach ($notifications as $notification) {
            Notification::create($notification);
        }
    }
}
