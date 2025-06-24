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
        Schema::create('truck_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('view_type', ['front', 'back', 'left', 'right', 'top', 'interior']);
            $table->string('truck_type')->nullable(); // e.g., 'Semi-truck', 'Box truck', etc.
            $table->integer('number_points'); // Number of reference points in the image
            $table->text('description')->nullable();
            $table->string('image_path'); // Path to the template image
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['view_type', 'is_active']);
            $table->index(['truck_type', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('truck_templates');
    }
};