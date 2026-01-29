<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            // $table->interger('resident_id')->nullable();
            $table->integer('resident_id')->nullable();
            $table->boolean('auto_reminders')->default(false);
            $table->integer('start_reminder_days_before')->nullable();
            $table->date('manual_reminder_date')->nullable();
            $table->text('reminder_note')->nullable();
            $table->boolean('late_payment_fines')->default(false);
            $table->decimal('late_payment_fines_amount', 10, 2)->default(0);
            $table->boolean('first_reminder_active')->default(false);
            $table->boolean('due_date_reminder_active')->default(false);
            $table->boolean('overdue_notice_active')->default(false);
            $table->enum('status', ['active', 'inactive', 'partially_paid', 'paid', 'partially_refunded', 'refunded', 'cancelled'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_reminders');
    }
};
