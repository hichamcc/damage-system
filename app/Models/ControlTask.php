<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControlTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'control_line_id',
        'title',
        'description',
        'task_type',
        'sort_order',
        'is_required',
        'truck_template_id',
        'template_reference_number',
        'notes',
    ];

    protected $casts = [
        'is_required' => 'boolean',
    ];

    public function controlLine()
    {
        return $this->belongsTo(ControlLine::class);
    }

    public function truckTemplate()
    {
        return $this->belongsTo(TruckTemplate::class, 'truck_template_id');
    }

    public function completions()
    {
        return $this->hasMany(TaskCompletion::class);
    }

    public function damageReports()
    {
        return $this->hasMany(DamageReport::class);
    }

    /**
     * Get start check completion
     */
    public function startCompletion()
    {
        return $this->hasOne(TaskCompletion::class)->where('check_type', 'start');
    }

    /**
     * Get exit check completion
     */
    public function exitCompletion()
    {
        return $this->hasOne(TaskCompletion::class)->where('check_type', 'exit');
    }

    /**
     * Check if task has been completed for specific check type
     */
    public function isCompletedFor($checkType)
    {
        return $this->completions()->where('check_type', $checkType)->exists();
    }

    /**
     * Get completion for specific check type
     */
    public function getCompletionFor($checkType)
    {
        return $this->completions()->where('check_type', $checkType)->first();
    }

    /**
     * Check if task is fully completed (both start and exit if needed)
     */
    public function isFullyCompleted()
    {
        $controlLine = $this->controlLine;
        
        // If control is completed, task should have at least one completion
        if ($controlLine->status === 'completed') {
            return $this->completions()->count() > 0;
        }
        
        // For active controls, check based on what's been done
        return $this->completions()->count() > 0;
    }

    /**
     * Get task progress percentage
     */
    public function getProgressPercentage()
    {
        $completions = $this->completions()->count();
        
        if ($completions === 0) {
            return 0;
        }
        
        // If we have both start and exit, 100%
        if ($completions >= 2) {
            return 100;
        }
        
        // If we have one completion, 50% (assuming both start and exit are needed)
        return 50;
    }

    /**
     * Scope for completed tasks
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for pending tasks
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Mark task as completed
     */
    public function markAsCompleted()
    {
        $this->update(['status' => 'completed']);
    }

    /**
     * Check if task has any issues reported
     */
    public function hasIssues()
    {
        return $this->completions()->whereIn('status', ['issue', 'missing', 'damaged'])->exists();
    }

    /**
     * Get latest completion
     */
    public function latestCompletion()
    {
        return $this->hasOne(TaskCompletion::class)->latest();
    }
}