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
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('resident_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->boolean('auto_reminders')->default(false);
            $table->integer('start_reminder_days_before')->nullable();
            $table->date('manual_reminder_date')->nullable();
            $table->text('reminder_note')->nullable();
            $table->boolean('late_payment_fines')->default(false);
            $table->enum('reminder_schedule', ['first_reminder', 'due_date_reminder', 'overdue_notice'])->nullable();
            $table->boolean('is_active')->default(true);
            $table->enum('status', ['active', 'resolved', 'cancelled'])->default('active');
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
