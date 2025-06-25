<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControlTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function tasks()
    {
        return $this->hasMany(ControlTemplateTask::class)->orderBy('sort_order');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function controlLines()
    {
        return $this->hasMany(ControlLine::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public static function getActiveTemplate($type)
    {
        return self::where('is_active', true)->where('type', $type)->with('tasks')->first();
    }

    // Add scope for filtering by type
    public function scopeForType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Add accessor for formatted type
    public function getFormattedTypeAttribute()
    {
        return ucfirst($this->type);
    }

    // Add method to get icon
    public function getTypeIconAttribute()
    {
        return $this->type === 'truck' ? '🚛' : '🚚';
    }
}