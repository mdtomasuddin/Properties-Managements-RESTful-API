<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name'              => 'Flexible Paid Plan',
                'offer_description' => 'per unit/month',
                'description'       => 'Scale as you grow. Pay only for units you manage.',
                'price'             => 59.00,
                'currency'          => 'BDT',
                'features'          => [
                    'Everything in Free, plus:',
                    'Unlimited properties',
                    'Add/remove units anytime — charges adjust instantly',
                    'Full dashboard with total rent receivable/collected per property',
                    'Financial breakdowns by tenant/unit',
                    'Document vault for each property',
                    'Automatic rent reminders (via SMS + in-app)',
                    'Announcements to tenants (e.g. outages, notices)',
                    'Payment requests for service charges, fines, utilities',
                    'AI insights on tenant behavior, payment history, lease status',
                    'Maintenance request tracking (with attachments)',
                    'Team access: share portfolio with assistants',
                    'Exportable financial reports (Excel/PDF)',
                ],
                'billing_interval'  => 'monthly',
                'is_recommended'    => true, // Blue dot/border usually indicates recommended
                'status'            => 'active',
            ],
        ];

        // Inserting the data into the database
        foreach ($plans as $planData) {
            Plan::create($planData);
        }
    }
}
