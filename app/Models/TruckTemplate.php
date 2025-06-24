<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TruckTemplate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'view_type',
        'truck_type',
        'number_points',
        'description',
        'image_path',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'number_points' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relationship with user who created the template
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the full URL for the template image
     */
    public function getImageUrlAttribute()
    {
        return $this->image_path ? asset('storage/' . $this->image_path) : null;
    }

    /**
     * Scope for active templates
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for specific view type
     */
    public function scopeViewType($query, $viewType)
    {
        return $query->where('view_type', $viewType);
    }

    /**
     * Scope for specific truck type
     */
    public function scopeTruckType($query, $truckType)
    {
        return $query->where('truck_type', $truckType)->orWhereNull('truck_type');
    }

    /**
     * Get available view types
     */
    public static function getViewTypes()
    {
        return [
            'front' => 'Front View',
            'back' => 'Back View',
            'left' => 'Left Side',
            'right' => 'Right Side',
            'top' => 'Top View',
            'interior' => 'Interior',
        ];
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        // When deleting, also delete the image file
        static::deleting(function ($template) {
            if ($template->image_path && \Storage::disk('public')->exists($template->image_path)) {
                \Storage::disk('public')->delete($template->image_path);
            }
        });
    }

    /**
 * Get templates for AJAX requests (for control creation and checks)
 */
public function getTemplates(Request $request)
{
    $query = TruckTemplate::where('is_active', true);
    
    // Filter by view type if specified
    if ($request->has('view_type') && $request->view_type) {
        $query->where('view_type', $request->view_type);
    }
    
    // Filter by truck type if specified
    if ($request->has('truck_type') && $request->truck_type) {
        $query->where(function($q) use ($request) {
            $q->where('truck_type', $request->truck_type)
              ->orWhereNull('truck_type');
        });
    }
    
    // Get templates with basic info needed for display
    $templates = $query->select([
        'id',
        'name',
        'view_type',
        'truck_type',
        'image_path',
        'number_points',
        'description'
    ])
    ->orderBy('view_type')
    ->orderBy('name')
    ->get();
    
    // Transform for API response
    $templates->transform(function ($template) {
        return [
            'id' => $template->id,
            'name' => $template->name,
            'view_type' => $template->view_type,
            'truck_type' => $template->truck_type,
            'image_path' => $template->image_path,
            'image_url' => $template->image_url, // Full URL
            'number_points' => $template->number_points,
            'description' => $template->description,
        ];
    });
    
    return response()->json($templates);
}

// Alternative simpler version if you don't need filtering
public function getTemplatesSimple()
{
    $templates = TruckTemplate::where('is_active', true)
        ->select('id', 'name', 'view_type', 'image_path', 'number_points')
        ->orderBy('view_type')
        ->orderBy('name')
        ->get();
    
    return response()->json($templates);
}

// For getting a specific template by ID
public function getTemplate($id)
{
    $template = TruckTemplate::where('is_active', true)
        ->where('id', $id)
        ->select('id', 'name', 'view_type', 'image_path', 'number_points', 'description')
        ->first();
    
    if (!$template) {
        return response()->json(['error' => 'Template not found'], 404);
    }
    
    return response()->json([
        'id' => $template->id,
        'name' => $template->name,
        'view_type' => $template->view_type,
        'image_path' => $template->image_path,
        'image_url' => $template->image_url,
        'number_points' => $template->number_points,
        'description' => $template->description,
    ]);
}
public function controlTasks(): HasMany
{
    return $this->hasMany(ControlTask::class, 'truck_template_id');
}
}