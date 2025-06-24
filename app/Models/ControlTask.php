<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ControlTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'control_line_id',
        'title',
        'description',
        'task_type',
        'truck_template_id',
        'template_reference_number',
        'sort_order',
        'is_required',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'template_reference_number' => 'integer',

    ];

    public function controlLine()
    {
        return $this->belongsTo(ControlLine::class);
    }

    public function completions()
    {
        return $this->hasMany(TaskCompletion::class);
    }

    public function startCompletion()
    {
        return $this->hasOne(TaskCompletion::class)->where('check_type', 'start');
    }

    public function exitCompletion()
    {
        return $this->hasOne(TaskCompletion::class)->where('check_type', 'exit');
    }

    public function getTaskTypeColorAttribute()
    {
        return match($this->task_type) {
            'check' => 'bg-blue-100 text-blue-800',
            'inspect' => 'bg-yellow-100 text-yellow-800',
            'document' => 'bg-green-100 text-green-800',
            'report' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
      // Add this relationship
      public function truckTemplate(): BelongsTo
      {
          return $this->belongsTo(TruckTemplate::class, 'truck_template_id');
      }
  
      // Helper method to get template reference info
      public function getTemplateReferenceAttribute()
      {
          if ($this->truck_template_id && $this->template_reference_number) {
              return [
                  'template' => $this->truckTemplate,
                  'point_number' => $this->template_reference_number
              ];
          }
          return null;
      }
}