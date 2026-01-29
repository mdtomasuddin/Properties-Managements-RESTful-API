<?php
namespace Database\Seeders;

use App\Models\Announcement;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $announcementData = [
            [
                "property_id" => 1,
                "user_id"     => 3,
                "category_id" => 1, // General Notice
                "title"       => "Community Monthly Meeting",
                "message"     => "Join us this Friday at the community hall for our monthly progress discussion.",
                "send_to"     => "all_resident",
                "is_draft"    => false,
                "attachments" => [
                    "https://plus.unsplash.com/premium_photo-1689609950112-d66095626efb?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
                ],
                "status"      => "published",
            ],
            [
                "property_id" => 1,
                "user_id"     => 3,
                "category_id" => 2, // Utility Outage
                "title"       => "Scheduled Water Maintenance",
                "message"     => "Water supply will be suspended for 2 hours tomorrow morning for tank cleaning.",
                "send_to"     => "all_resident",
                "is_draft"    => false,
                "attachments" => [
                    "https://plus.unsplash.com/premium_photo-1689609950112-d66095626efb?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
                ],
                "status"      => "published",
            ],
            [
                "property_id" => 1,
                "user_id"     => 3,
                "category_id" => 3, // Maintenance Work
                "title"       => "Elevator Servicing",
                "message"     => "The main elevator will be under maintenance from 10:00 AM to 2:00 PM.",
                "send_to"     => "all_resident",
                "is_draft"    => false,
                "attachments" => [
                    "https://plus.unsplash.com/premium_photo-1689609950112-d66095626efb?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
                ],
                "status"      => "published",
            ],
            [
                "property_id" => 1,
                "user_id"     => 3,
                "category_id" => 4, // Property Event
                "title"       => "Rooftop BBQ Night",
                "message"     => "Don't forget to register for the upcoming BBQ night on the rooftop garden!",
                "send_to"     => "all_resident",
                "is_draft"    => true,
                "attachments" => [
                    "https://plus.unsplash.com/premium_photo-1689609950112-d66095626efb?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
                ],
                "status"      => "draft",
            ],
            [
                "property_id" => 1,
                "user_id"     => 3,
                "category_id" => 5, // Policy Update
                "title"       => "New Parking Regulations",
                "message"     => "Please review the updated parking policy effective from the 1st of next month.",
                "send_to"     => "all_resident",
                "is_draft"    => true,
                "attachments" => [
                    "https://plus.unsplash.com/premium_photo-1689609950112-d66095626efb?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
                ],
                "status"      => "draft",
            ],
            [
                "property_id" => 1,
                "user_id"     => 3,
                "category_id" => 6, // Emergency Notice
                "title"       => "Fire Drill Notification",
                "message"     => "URGENT: Mandatory fire drill will take place tomorrow at 11:00 AM sharp.",
                "send_to"     => "all_resident",
                "is_draft"    => false,
                "attachments" => [
                    "https://plus.unsplash.com/premium_photo-1689609950112-d66095626efb?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
                ],
                "status"      => "published",
            ],
            [
                "property_id" => 1,
                "user_id"     => 3,
                "category_id" => 1, // General Notice
                "title"       => "Pool Maintenance Schedule",
                "message"     => "The community pool will be closed for maintenance next Monday and Tuesday.",
                "send_to"     => "all_resident",
                "is_draft"    => false,
                "attachments" => [
                    "https://plus.unsplash.com/premium_photo-1689609950112-d66095626efb?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
                ],
                "status"      => "published",
            ],
        ];

        foreach ($announcementData as $data) {
            Announcement::create($data);
        }
    }
}
