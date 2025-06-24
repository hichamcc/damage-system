<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Control Lines (Main control entries)
        Schema::create('control_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('truck_id')->constrained()->onDelete('cascade');
            $table->foreignId('assigned_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
            $table->timestamp('assigned_at');
            $table->timestamp('start_check_at')->nullable();
            $table->timestamp('exit_check_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Control Tasks (Tasks within each control line)
        Schema::create('control_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('control_line_id')->constrained()->onDelete('cascade');
            $table->string('title'); // e.g., "Registration papers in truck", "Check damages outside"
            $table->text('description')->nullable();
            $table->enum('task_type', ['check', 'inspect', 'document', 'report'])->default('check');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_required')->default(true);
            $table->timestamps();
        });

        // Task Completions (When users complete tasks during START/EXIT)
        Schema::create('task_completions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('control_task_id')->constrained()->onDelete('cascade');
            $table->foreignId('control_line_id')->constrained()->onDelete('cascade');
            $table->foreignId('completed_by')->constrained('users')->onDelete('cascade');
            $table->enum('check_type', ['start', 'exit']);
            $table->enum('status', ['ok', 'issue', 'missing', 'damaged'])->default('ok');
            $table->text('notes')->nullable();
            $table->json('attachments')->nullable(); // Photos, documents
            $table->timestamp('completed_at');
            $table->timestamps();
        });

        // Damage Reports (Issues found during checks)
        Schema::create('damage_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('control_line_id')->constrained()->onDelete('cascade');
            $table->foreignId('control_task_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('truck_id')->constrained()->onDelete('cascade');
            $table->foreignId('reported_by')->constrained('users')->onDelete('cascade');
            $table->string('damage_location'); // outside, inside, engine, documents, etc.
            $table->text('damage_description');
            $table->enum('severity', ['minor', 'major', 'critical'])->default('minor');
            $table->enum('status', ['reported', 'in_repair', 'fixed', 'ignored'])->default('reported');
            $table->json('damage_photos')->nullable();
            $table->date('repair_date')->nullable();
            $table->date('fixed_date')->nullable();
            $table->text('repair_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('damage_reports');
        Schema::dropIfExists('task_completions');
        Schema::dropIfExists('control_tasks');
        Schema::dropIfExists('control_lines');
    }
};