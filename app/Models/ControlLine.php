<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControlLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'truck_id',
        'assigned_user_id',
        'created_by',
        'status',
        'assigned_at',
        'start_check_at',
        'exit_check_at',
        'notes',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'start_check_at' => 'datetime',
        'exit_check_at' => 'datetime',
    ];

    public function truck()
    {
        return $this->belongsTo(Truck::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function tasks()
    {
        return $this->hasMany(ControlTask::class)->orderBy('sort_order');
    }

    public function completions()
    {
        return $this->hasMany(TaskCompletion::class);
    }

    public function damageReports()
    {
        return $this->hasMany(DamageReport::class);
    }

    public function getStatusBadgeColorAttribute()
    {
        return match($this->status) {
            'active' => 'bg-blue-100 text-blue-800',
            'completed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function startCheckProgress()
    {
        $total = $this->tasks()->where('is_required', true)->count();
        $completed = $this->completions()->where('check_type', 'start')->count();
        return $total > 0 ? round(($completed / $total) * 100) : 0;
    }

    public function exitCheckProgress()
    {
        $total = $this->tasks()->where('is_required', true)->count();
        $completed = $this->completions()->where('check_type', 'exit')->count();
        return $total > 0 ? round(($completed / $total) * 100) : 0;
    }
}
