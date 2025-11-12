<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FAQSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('f_a_q_s')->insert([
            [
                'question'   => 'How does TAXDAX help me find new clients?',
                'answer'     => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam et felis vel nisi consectetur aliquet. Aenean vehicula, urna nec aliquet volutpat, augue turpis suscipit lacus, sit amet sodales libero odio nec mauris. Vivamus consequat, lectus a tincidunt interdum, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam et felis vel nisi consectetur aliquet. Aenean vehicula, urna nec aliquet volutpat, augue turpis suscipit lacus, sit amet sodales libero odio nec mauris. Vivamus consequat, lectus a tincidunt interdum,',
                'status'     => 'active',
                'created_at' => '2024-09-13 03:52:56',
                'updated_at' => '2024-09-13 03:52:56',
                'deleted_at' => null,
            ],
            [
                'question'   => 'How do I manage my appointments?',
                'answer'     => 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beata',
                'status'     => 'active',
                'created_at' => '2024-09-13 03:52:56',
                'updated_at' => '2024-09-13 03:52:56',
                'deleted_at' => null,
            ],
            [
                'question'   => 'How can I update my account profile details?',
                'answer'     => 'You can update your account details by navigating to “Account Profile” under your dashboard, where you can edit your name, email, phone number, and other personal information.',
                'status'     => 'active',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'question'   => 'How do I earn reward points?',
                'answer'     => 'You can earn reward points by completing actions such as inviting referrals, uploading content, or engaging with the community.',
                'status'     => 'active',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'question'   => 'How do I upload a video?',
                'answer'     => 'To upload a video, go to the “Posts & Content” section, click on “Upload Video,” then select a file from your computer. Supported formats include MP4, AVI, and MOV.',
                'status'     => 'active',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'question'   => 'What is the maximum video size I can upload?',
                'answer'     => 'The maximum allowed video size is 200MB. For best performance, we recommend compressing your video before uploading.',
                'status'     => 'active',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],

            [
                'question'   => 'How secure is the platform for document sharing?',
                'answer'     => 'We use industry-standard encryption and access controls to ensure your documents are shared securely.',
                'status'     => 'active',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'question'   => 'Per referral earn',
                'answer'     => 'For every successful referral, you earn reward points that can be redeemed for discounts or other benefits.',
                'status'     => 'active',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'question'   => 'referral earn convert',
                'answer'     => 'For every successful referral, you earn reward points that can be redeemed for discounts or other benefits.',
                'status'     => 'active',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
        ]);
    }
}
