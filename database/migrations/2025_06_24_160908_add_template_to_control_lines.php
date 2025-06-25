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
        Schema::table('control_lines', function (Blueprint $table) {
            $table->unsignedBigInteger('control_template_id')->nullable()->after('truck_id');
            $table->foreign('control_template_id')->references('id')->on('control_templates');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('control_lines', function (Blueprint $table) {
            //
        });
    }
};
