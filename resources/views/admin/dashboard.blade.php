@extends('components.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="p-6">
    <!-- Welcome Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Admin Dashboard</h1>
        <p class="text-gray-600">Overview of truck control system activities and metrics</p>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Controls -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['total_controls'] }}</div>
                    <div class="text-sm text-gray-600">Total Controls</div>
                    <div class="text-xs text-green-600 mt-1">
                        +{{ $stats['controls_today'] }} today
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Controls -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['active_controls'] }}</div>
                    <div class="text-sm text-gray-600">Active Controls</div>
                    <div class="text-xs text-gray-500 mt-1">
                        {{ $stats['pending_start'] }} pending start
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Trucks -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2v-2a2 2 0 00-2-2H8V7z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['total_trucks'] }}</div>
                    <div class="text-sm text-gray-600">Total Trucks</div>
                    <div class="text-xs text-blue-600 mt-1">
                        {{ $stats['trucks_with_active_controls'] }} with active controls
                    </div>
                </div>
            </div>
        </div>

        <!-- Damage Reports -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['total_damages'] }}</div>
                    <div class="text-sm text-gray-600">Damage Reports</div>
                    <div class="text-xs text-red-600 mt-1">
                        {{ $stats['pending_damages'] }} need attention
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Recent Controls -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Recent Control Activities</h3>
                <a href="{{ route('admin.control.index') }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                    View All →
                </a>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($recent_controls as $control)
                    <div class="p-6 hover:bg-gray-50">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center justify-center w-10 h-10 {{ $control->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }} rounded-full text-sm font-medium">
                                    @if($control->status === 'completed')
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                        </svg>
                                    @endif
                                </span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $control->truck->license_plate }} - {{ $control->controlTemplate->name }}
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            Assigned to {{ $control->assignedUser->name }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ $control->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $control->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ ucfirst($control->status) }}
                                        </span>
                                        <a href="{{ route('admin.control.show', $control) }}" class="text-blue-600 hover:text-blue-900">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                                @if($control->damageReports->count() > 0)
                                    <div class="mt-2">
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-red-100 text-red-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ $control->damageReports->count() }} damage(s)
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-500">
                        <p>No recent control activities</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Right Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Quick Actions</h3>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('admin.control-templates.index') }}" 
                       class="flex items-center w-full px-4 py-3 text-left text-sm bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="text-blue-900">Manage Templates</span>
                    </a>
                    <a href="{{ route('admin.trucks.index') }}" 
                       class="flex items-center w-full px-4 py-3 text-left text-sm bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2v-2a2 2 0 00-2-2H8V7z"/>
                        </svg>
                        <span class="text-green-900">Manage Trucks</span>
                    </a>
                    <a href="{{ route('admin.damages.index') }}" 
                       class="flex items-center w-full px-4 py-3 text-left text-sm bg-red-50 hover:bg-red-100 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        <span class="text-red-900">View Damages</span>
                    </a>
                    <a href="{{ route('admin.users.index') }}" 
                       class="flex items-center w-full px-4 py-3 text-left text-sm bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                        <span class="text-purple-900">Manage Users</span>
                    </a>
                </div>
            </div>

            <!-- System Status -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">System Status</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Active Templates</span>
                        <span class="text-sm font-medium text-gray-900">{{ $stats['active_templates'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Active Users</span>
                        <span class="text-sm font-medium text-gray-900">{{ $stats['active_users'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Completion Rate</span>
                        <span class="text-sm font-medium text-green-600">{{ $stats['completion_rate'] }}%</span>
                    </div>
                   
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Analytics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Controls Over Time -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Controls Over Time (Last 7 Days)</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($daily_stats as $day)
                        <div class="flex items-center">
                            <div class="w-20 text-sm text-gray-600">{{ $day['date'] }}</div>
                            <div class="flex-1 mx-4">
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $day['percentage'] }}%"></div>
                                </div>
                            </div>
                            <div class="w-12 text-sm text-gray-900 text-right">{{ $day['count'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Damage Severity Distribution -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Damage Severity Distribution</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($damage_stats as $severity => $count)
                        @php
                            $percentage = $stats['total_damages'] > 0 ? round(($count / $stats['total_damages']) * 100) : 0;
                            $colorClass = match($severity) {
                                'critical' => 'bg-red-500',
                                'major' => 'bg-orange-500',
                                'minor' => 'bg-yellow-500',
                                default => 'bg-gray-500'
                            };
                        @endphp
                        <div class="flex items-center">
                            <div class="w-20 text-sm text-gray-600 capitalize">{{ $severity }}</div>
                            <div class="flex-1 mx-4">
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="{{ $colorClass }} h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                            <div class="w-16 text-sm text-gray-900 text-right">{{ $count }} ({{ $percentage }}%)</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Damage Reports -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900">Recent Damage Reports</h3>
            <a href="{{ route('admin.damages.index') }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                View All →
            </a>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($recent_damages as $damage)
                <div class="p-6 hover:bg-gray-50">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            @if($damage->damage_photos && count($damage->getPhotoUrls()) > 0)
                                <div class="w-12 h-12 rounded-lg overflow-hidden border border-gray-200">
                                    <img src="{{ $damage->getPhotoUrls()[0]['url'] }}" 
                                         alt="Damage photo"
                                         class="w-full h-full object-cover">
                                </div>
                            @else
                                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $damage->truck->license_plate }} - {{ $damage->damage_location ?? 'Damage Report' }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        {{ Str::limit($damage->damage_description, 60) }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        Reported by {{ $damage->reportedBy->name }} {{ $damage->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $damage->severity_color }}">
                                        {{ ucfirst($damage->severity) }}
                                    </span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $damage->status_color }}">
                                        {{ ucfirst(str_replace('_', ' ', $damage->status)) }}
                                    </span>
                                    <a href="{{ route('admin.damages.show', $damage) }}" class="text-blue-600 hover:text-blue-900">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-gray-500">
                    <p>No recent damage reports</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection