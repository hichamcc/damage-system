<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ControlLine;
use App\Models\TaskCompletion;
use App\Models\Truck;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'totalControls' => ControlLine::count(),
            'activeControls' => ControlLine::where('status', 'active')->count(),
            'issuesCount' => TaskCompletion::whereIn('status', ['issue', 'damaged', 'missing'])->count(),
            'totalVehicles' => Truck::count(),
            'recentControls' => ControlLine::with(['truck', 'user'])->latest()->take(5)->get(),
            'activeIssues' => TaskCompletion::with(['task', 'controlLine.truck'])
                ->whereIn('status', ['issue', 'damaged', 'missing'])->latest()->take(4)->get(),
          
        ]);
    }
}