<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TaskCompletion extends Model
{
    use HasFactory;

    protected $fillable = [
        'control_task_id',
        'control_line_id',
        'completed_by',
        'check_type',
        'status',
        'damage_area',
        'notes',
        'attachments',
        'completed_at',
    ];

    protected $casts = [
        'attachments' => 'array',
        'completed_at' => 'datetime',
    ];

    public function controlTask()
    {
        return $this->belongsTo(ControlTask::class);
    }

    public function controlLine()
    {
        return $this->belongsTo(ControlLine::class);
    }

    public function completedBy()
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'ok' => 'bg-green-100 text-green-800',
            'issue' => 'bg-yellow-100 text-yellow-800',
            'missing' => 'bg-orange-100 text-orange-800',
            'damaged' => 'bg-red-100 text-red-800',
            'same_as_start' => 'bg-blue-100 text-blue-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getAttachmentUrls()
    {
        if (!$this->attachments) {
            return [];
        }

        return collect($this->attachments)->map(function ($attachment) {
            return [
                'name' => $attachment['name'],
                'path' => $attachment['path'],
                'url' => Storage::url($attachment['path']),
                'type' => $attachment['type'] ?? 'unknown',
            ];
        })->toArray();
    }

    public function getDamageAreaDisplayAttribute()
    {
        if (empty($this->damage_area)) {
            return null;
        }

        return "Area(s): " . $this->damage_area;
    }

     // Check if this completion has damage
     public function hasDamageAttribute()
     {
         return in_array($this->status, ['issue', 'damaged', 'missing', 'same_as_start']);
     }
     public function task()
     {
         return $this->belongsTo(ControlTask::class, 'control_task_id');
     }
}