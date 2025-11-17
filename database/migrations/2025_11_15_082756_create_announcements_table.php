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
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('title')->nullable();
            $table->text('message')->nullable();
            $table->json('attachments')->nullable(); // Photos/docs
            $table->enum('send_to', ['individual', 'all_resident'])->default('all_resident');
            $table->json('selected_resident_id')->nullable();
            $table->boolean('is_draft')->default(false);
            $table->date('publish_date')->nullable();
            $table->enum('status', ['active', 'inactive', 'published', 'draft', 'cancelled'])->default('published');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
