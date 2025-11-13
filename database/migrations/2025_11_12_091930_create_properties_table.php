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
        Schema::create('properties', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->enum('property_type', ['multi_unit', 'single_unit'])->default('single_unit');
            $table->string('property_name')->nullable();
            $table->string('property_address')->nullable();
            $table->integer('number_of_units')->default(1);
            $table->json('documents')->nullable();
            $table->string('security_contact')->nullable();
            $table->text('additional_details')->nullable();
            $table->enum('status', ['active', 'inactive', 'available', 'occupied', 'maintenance'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
