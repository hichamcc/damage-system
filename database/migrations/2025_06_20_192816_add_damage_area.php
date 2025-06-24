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
        // Add damage_area field to task_completions table
        Schema::table('task_completions', function (Blueprint $table) {
            $table->string('damage_area', 100)->nullable()->after('notes');
        });

        // Add damage_area field to damage_reports table
        Schema::table('damage_reports', function (Blueprint $table) {
            $table->string('damage_area', 100)->nullable()->after('damage_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_completions', function (Blueprint $table) {
            $table->dropColumn('damage_area');
        });

        Schema::table('damage_reports', function (Blueprint $table) {
            $table->dropColumn('damage_area');
        });
    }
};