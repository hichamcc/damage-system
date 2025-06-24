<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Truck extends Model
{
    use HasFactory;

    protected $fillable = [
        'truck_number',
        'license_plate',
        'make',
        'model',
        'status',
        'notes',
        'attachments',
    ];

    protected $casts = [
        'attachments' => 'array',
        'mileage' => 'decimal:2',
    ];

    /**
     * Get the status badge color for display
     */
    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->status) {
            'active' => 'bg-green-100 text-green-800',
            'maintenance' => 'bg-yellow-100 text-yellow-800',
            'out_of_service' => 'bg-red-100 text-red-800',
            'retired' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get formatted status for display
     */
    public function getFormattedStatusAttribute(): string
    {
        return ucwords(str_replace('_', ' ', $this->status));
    }

    /**
     * Get attachment URLs for display
     */
    public function getAttachmentUrls(): array
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
                'size' => $attachment['size'] ?? 0,
            ];
        })->toArray();
    }

    /**
     * Scope for active trucks
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}