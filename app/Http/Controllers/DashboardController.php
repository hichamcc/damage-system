<?php

namespace App\Http\Controllers;

use App\Models\ControlLine;
use App\Models\ControlTemplate;
use App\Models\DamageReport;
use App\Models\Truck;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard
     */
    public function index(Request $request)
    {
        // Check if user is admin for admin dashboard
        if (auth()->user()->role === 'admin') {
            return $this->adminDashboard();
        }

        // Otherwise show user dashboard
        return $this->userDashboard();
    }

    /**
     * Admin Dashboard
     */
    private function adminDashboard()
    {
        // Quick Stats
        $totalControls = ControlLine::count();
        $controlsToday = ControlLine::whereDate('created_at', today())->count();
        $activeControls = ControlLine::where('status', 'active')->count();
        
        // Pending start checks (active controls without any start completions)
        $pendingStart = ControlLine::where('status', 'active')
            ->whereDoesntHave('tasks.completions', function($query) {
                $query->where('check_type', 'start');
            })->count();

        $totalTrucks = Truck::count();
        
        // Trucks with active controls
        $trucksWithActiveControls = Truck::whereHas('controlLines', function($query) {
            $query->where('status', 'active');
        })->count();

        $totalDamages = DamageReport::count();
        $pendingDamages = DamageReport::whereIn('status', ['reported', 'in_repair'])->count();

        // System Status
        $activeTemplates = ControlTemplate::where('is_active', true)->count();
        $activeUsers = User::where('role', 'user')->count();

        // Completion rate calculation
        $completedControls = ControlLine::where('status', 'completed')->count();
        $completionRate = $totalControls > 0 ? round(($completedControls / $totalControls) * 100) : 0;

   

        $stats = [
            'total_controls' => $totalControls,
            'controls_today' => $controlsToday,
            'active_controls' => $activeControls,
            'pending_start' => $pendingStart,
            'total_trucks' => $totalTrucks,
            'trucks_with_active_controls' => $trucksWithActiveControls,
            'total_damages' => $totalDamages,
            'pending_damages' => $pendingDamages,
            'active_templates' => $activeTemplates,
            'active_users' => $activeUsers,
            'completion_rate' => $completionRate,
        ];

        // Recent Controls (last 10)
        $recentControls = ControlLine::with([
            'truck', 
            'assignedUser', 
            'controlTemplate',
            'damageReports'
        ])
        ->latest()
        ->limit(10)
        ->get();

        // Recent Damage Reports (last 8)
        $recentDamages = DamageReport::with([
            'truck', 
            'reportedBy', 
            'controlLine'
        ])
        ->latest()
        ->limit(8)
        ->get();

        // Daily Stats for last 7 days
        $dailyStats = collect();
        $maxDailyCount = 1; // To avoid division by zero

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $count = ControlLine::whereDate('created_at', $date)->count();
            $maxDailyCount = max($maxDailyCount, $count);
            
            $dailyStats->push([
                'date' => $date->format('M j'),
                'count' => $count,
                'percentage' => 0 // Will calculate after we know max
            ]);
        }

        // Calculate percentages based on max count
        $dailyStats = $dailyStats->map(function ($day) use ($maxDailyCount) {
            $day['percentage'] = $maxDailyCount > 0 ? round(($day['count'] / $maxDailyCount) * 100) : 0;
            return $day;
        });

        // Damage Statistics by Severity
        $damageStats = DamageReport::select('severity', DB::raw('count(*) as count'))
            ->groupBy('severity')
            ->pluck('count', 'severity')
            ->toArray();

        // Ensure all severity levels are represented
        $damageStats = array_merge([
            'minor' => 0,
            'major' => 0,
            'critical' => 0,
        ], $damageStats);

            return view('admin.dashboard', [
                'stats' => $stats,
                'recent_controls' => $recentControls,
                'recent_damages' => $recentDamages,
                'daily_stats' => $dailyStats,
                'damage_stats' => $damageStats
            ]);
    }

    /**
     * User Dashboard - for regular users
     */
    private function userDashboard()
    {
        $user = auth()->user();

        // User's control statistics
        $userStats = [
            'total_controls' => ControlLine::where('assigned_user_id', $user->id)->count(),
            'active_controls' => ControlLine::where('assigned_user_id', $user->id)
                ->where('status', 'active')->count(),
            'completed_controls' => ControlLine::where('assigned_user_id', $user->id)
                ->where('status', 'completed')->count(),
            'pending_damages' => DamageReport::where('reported_by', $user->id)
                ->whereIn('status', ['reported', 'in_repair'])->count(),
        ];

        // User's recent controls
        $userControls = ControlLine::with([
            'truck', 
            'controlTemplate',
            'tasks.completions',
            'damageReports'
        ])
        ->where('assigned_user_id', $user->id)
        ->latest()
        ->limit(5)
        ->get();

        // Available trucks for new controls
        $availableTrucks = Truck::where('status', 'active')->get(); ;

        // Active control template
        $activeTemplate = ControlTemplate::where('is_active', true)->first();

        return view('user.dashboard', compact(
            'userStats',
            'userControls',
            'availableTrucks',
            'activeTemplate'
        ));
    }

    /**
     * Get system metrics for API calls
     */
    public function getMetrics(Request $request)
    {
        $metrics = [
            'controls' => [
                'total' => ControlLine::count(),
                'active' => ControlLine::where('status', 'active')->count(),
                'completed_today' => ControlLine::where('status', 'completed')
                    ->whereDate('updated_at', today())->count(),
            ],
            'damages' => [
                'total' => DamageReport::count(),
                'pending' => DamageReport::whereIn('status', ['reported', 'in_repair'])->count(),
                'fixed_today' => DamageReport::where('status', 'fixed')
                    ->whereDate('fixed_date', today())->count(),
            ],
            'trucks' => [
                'total' => Truck::count(),
                'in_use' => Truck::whereHas('controlLines', function($query) {
                    $query->where('status', 'active');
                })->count(),
            ]
        ];

        return response()->json($metrics);
    }

    /**
     * Get controls chart data
     */
    public function getControlsChart(Request $request)
    {
        $days = $request->get('days', 7);
        
        $data = collect();
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $count = ControlLine::whereDate('created_at', $date)->count();
            
            $data->push([
                'date' => $date->format('Y-m-d'),
                'label' => $date->format('M j'),
                'count' => $count,
            ]);
        }

        return response()->json($data);
    }

    /**
     * Get damages by status for charts
     */
    public function getDamagesChart(Request $request)
    {
        $damagesByStatus = DamageReport::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        $damagesBySeverity = DamageReport::select('severity', DB::raw('count(*) as count'))
            ->groupBy('severity')
            ->get()
            ->pluck('count', 'severity');

        return response()->json([
            'by_status' => $damagesByStatus,
            'by_severity' => $damagesBySeverity,
        ]);
    }

    /**
     * Get user performance metrics
     */
    public function getUserPerformance(Request $request)
    {
        $users = User::where('role', 'user')
            ->withCount([
                'assignedControls',
                'assignedControls as completed_controls_count' => function($query) {
                    $query->where('status', 'completed');
                },
                'reportedDamages'
            ])
            ->get()
            ->map(function($user) {
                $user->completion_rate = $user->assigned_controls_count > 0 
                    ? round(($user->completed_controls_count / $user->assigned_controls_count) * 100) 
                    : 0;
                return $user;
            });

        return response()->json($users);
    }

    /**
     * Export dashboard data
     */
    public function exportData(Request $request)
    {
        $format = $request->get('format', 'csv');
        
        // This would implement CSV/Excel export functionality
        // For now, return JSON data
        
        $data = [
            'controls' => ControlLine::with(['truck', 'assignedUser', 'controlTemplate'])->get(),
            'damages' => DamageReport::with(['truck', 'reportedBy'])->get(),
            'generated_at' => now()->toISOString(),
        ];

        return response()->json($data);
    }
}