<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControlTemplateTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'control_template_id',
        'title',
        'description',
        'task_type',
        'sort_order',
        'is_required',
        'truck_template_id',
        'template_reference_number',
    ];

    protected $casts = [
        'is_required' => 'boolean',
    ];

    public function template()
    {
        return $this->belongsTo(ControlTemplate::class, 'control_template_id');
    }

    public function truckTemplate()
    {
        return $this->belongsTo(TruckTemplate::class, 'truck_template_id');
    }
}