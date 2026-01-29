<?php

namespace Database\Seeders;

use App\Models\PaymentReminder;
use Illuminate\Database\Seeder;

class PaymentReminderSeeder extends Seeder
{
    public function run(): void
    {
        $paymentRequests = [
            [
                "property_id"                => 1,
                'resident_id'                => 4,
                "auto_reminders"             => true,
                "start_reminder_days_before" => "3",
                "manual_reminder_date"       => "2026-02-28",
                "reminder_note"              => "Please pay your electricity bill and rent by the due date.",
                "late_payment_fines"         => true,
                "first_reminder_active"      => false,
                "due_date_reminder_active"   => false,
                "overdue_notice_active"      => true,
            ],
            [
                "property_id"                => 1,
                'resident_id'                => 4,
                "auto_reminders"             => true,
                "start_reminder_days_before" => "3",
                "manual_reminder_date"       => "2026-02-28",
                "reminder_note"              => "Please pay your electricity bill and rent by the due date.",
                "late_payment_fines"         => false,
                "first_reminder_active"      => false,
                "due_date_reminder_active"   => false,
                "overdue_notice_active"      => false,
            ],

        ];

        foreach ($paymentRequests as $request) {
            PaymentReminder::create($request);
        }
    }
}
