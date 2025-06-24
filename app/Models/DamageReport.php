<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class DamageReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'control_line_id',
        'control_task_id',
        'truck_id',
        'reported_by',
        'damage_location',
        'damage_description',
        'severity',
        'status',
        'damage_area',
        'damage_photos',
        'repair_date',
        'fixed_date',
        'repair_notes',
    ];

    protected $casts = [
        'damage_photos' => 'array',
        'repair_date' => 'date',
        'fixed_date' => 'date',
    ];

    public function controlLine()
    {
        return $this->belongsTo(ControlLine::class);
    }

    public function controlTask()
    {
        return $this->belongsTo(ControlTask::class);
    }

    public function truck()
    {
        return $this->belongsTo(Truck::class);
    }

    public function reportedBy()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function getSeverityColorAttribute()
    {
        return match($this->severity) {
            'minor' => 'bg-yellow-100 text-yellow-800',
            'major' => 'bg-orange-100 text-orange-800',
            'critical' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'reported' => 'bg-blue-100 text-blue-800',
            'in_repair' => 'bg-yellow-100 text-yellow-800',
            'fixed' => 'bg-green-100 text-green-800',
            'ignored' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getPhotoUrls()
    {
        if (!$this->damage_photos) {
            return [];
        }

        return collect($this->damage_photos)->map(function ($photo) {
            return [
                'name' => $photo['name'],
                'url' => Storage::url($photo['path']),
            ];
        })->toArray();
    }

       // New helper methods for damage area
       public function getDamageAreaDisplayAttribute()
       {
           if (empty($this->damage_area)) {
               return 'Area not specified';
           }
   
           return "Area(s): " . $this->damage_area;
       }
   
       public function hasSpecificAreaAttribute()
       {
           return !empty($this->damage_area);
       }
   
       // Parse damage area into array for processing
       public function getParsedDamageAreasAttribute()
       {
           if (empty($this->damage_area)) {
               return [];
           }
   
           // Parse "1, 3-5, 8" into [1, 3, 4, 5, 8]
           $areas = [];
           $parts = explode(',', $this->damage_area);
           
           foreach ($parts as $part) {
               $part = trim($part);
               if (strpos($part, '-') !== false) {
                   // Handle ranges like "3-5"
                   list($start, $end) = explode('-', $part);
                   for ($i = (int)$start; $i <= (int)$end; $i++) {
                       $areas[] = $i;
                   }
               } else {
                   // Handle single numbers
                   $areas[] = (int)$part;
               }
           }
   
           return array_unique(array_filter($areas));
       }
}