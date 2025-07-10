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
        Schema::table('task_completions', function (Blueprint $table) {
            $table->enum('status', ['ok', 'issue', 'missing', 'damaged', 'same_as_start'])->default('ok')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_completions', function (Blueprint $table) {
            $table->enum('status', ['ok', 'issue', 'missing', 'damaged'])->default('ok')->change();
        });
    }
};
