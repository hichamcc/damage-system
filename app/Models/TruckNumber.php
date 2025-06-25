<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TruckNumber extends Model
{
    use HasFactory;

    protected $fillable = [
        'truck_number',
    ];

    /**
     * Get the truck numbers formatted for display
     */
    public function getFormattedNumberAttribute(): string
    {
        return strtoupper($this->truck_number);
    }

    /**
     * Scope to search truck numbers
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('truck_number', 'like', '%' . $search . '%');
    }

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-format truck number before saving
        static::saving(function ($truckNumber) {
            $truckNumber->truck_number = strtoupper(trim($truckNumber->truck_number));
        });
    }
}