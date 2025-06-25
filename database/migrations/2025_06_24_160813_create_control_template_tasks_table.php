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
        Schema::create('control_template_tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('control_template_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('task_type', ['check', 'inspect', 'document', 'report']);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_required')->default(true);
            $table->unsignedBigInteger('truck_template_id')->nullable();
            $table->integer('template_reference_number')->nullable();
            $table->timestamps();
            
            $table->foreign('control_template_id')->references('id')->on('control_templates')->onDelete('cascade');
            $table->foreign('truck_template_id')->references('id')->on('truck_templates');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('control_template_tasks');
    }
};
