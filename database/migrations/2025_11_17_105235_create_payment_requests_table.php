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
        Schema::create('payment_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // The requester (Admin/Owner)
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->enum('send_to', ['individual', 'all_resident'])->default('all_resident');
            $table->foreignId('resident_id')->constrained('users')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->json('attachments')->nullable();
            $table->text('description')->nullable();
            $table->date('due_date')->nullable();
            $table->enum('status', ['paid', 'due', 'cancelled', 'draft', 'sent', 'partially_paid'])->default('due');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_requests');
    }
};
