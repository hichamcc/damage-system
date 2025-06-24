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
        Schema::create('trucks', function (Blueprint $table) {
            $table->id();
            $table->string('truck_number')->unique();
            $table->string('license_plate')->unique();
            $table->string('make');
            $table->string('model');
            $table->year('year');
            $table->string('color');
            $table->string('vin')->unique()->nullable();
            $table->enum('status', ['active', 'maintenance', 'out_of_service', 'retired'])->default('active');
            $table->decimal('mileage', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->json('attachments')->nullable(); // Store file paths as JSON
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trucks');
    }
};