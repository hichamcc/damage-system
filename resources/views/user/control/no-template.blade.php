@extends('components.layouts.app')

@section('title', 'No Active Template')

@section('content')
<div class="p-6">
    <div class="max-w-2xl mx-auto">
        <!-- Header Section -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-8 text-center">
                <!-- Icon -->
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-yellow-100 mb-4">
                    <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>

                <!-- Title -->
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Active Control Template</h3>
                
                <!-- Description -->
                <p class="text-gray-600 mb-6">
                    There is currently no active control template available for creating new vehicle inspections. 
                    You cannot create new control checks at this time.
                </p>

                <!-- Details -->
                <div class="bg-yellow-50 rounded-lg p-4 mb-6 text-left">
                    <h4 class="text-sm font-medium text-yellow-800 mb-2">What does this mean?</h4>
                    <ul class="text-sm text-yellow-700 space-y-1">
                        <li>• No control template has been set as "active" by administrators</li>
                        <li>• New vehicle inspections cannot be created until a template is activated</li>
                        <li>• Existing control checks can still be completed normally</li>
                    </ul>
                </div>

                <!-- Actions -->
                <div class="space-y-3">
                    <p class="text-sm text-gray-600">
                        Please contact your administrator to activate a control template, or check back later.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                        <a href="{{ route('user.control.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition duration-150 ease-in-out">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Refresh Dashboard
                        </a>
                        
                        <a href="{{ route('dashboard') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-md transition duration-150 ease-in-out">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            Go to Main Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Help Section -->
        <div class="bg-gray-50 rounded-lg p-6 mt-6">
            <h4 class="text-sm font-medium text-gray-900 mb-3 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Need Help?
            </h4>
            <div class="text-sm text-gray-600 space-y-2">
                <p>If you're experiencing this issue:</p>
                <ul class="list-disc pl-5 space-y-1">
                    <li>Contact your system administrator</li>
                    <li>Check if there are any system announcements</li>
                    <li>Try refreshing the page in a few minutes</li>
                </ul>
            </div>
        </div>

        <!-- Recent Activity (if any) -->
        <div class="bg-white rounded-lg shadow mt-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h4 class="text-lg font-medium text-gray-900">Your Recent Activity</h4>
            </div>
            
            <div class="p-6">
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5l7-7 7 7M9 20h6m-7 4h7m0 0v5a2 2 0 002 2h14a2 2 0 002-2v-5M5 12a2 2 0 012-2h10a2 2 0 012 2v5a2 2 0 01-2 2H7a2 2 0 01-2-2v-5z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No recent activity</h3>
                    <p class="mt-1 text-sm text-gray-500">Your control activities will appear here once you create some inspections.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Auto-refresh script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-refresh every 30 seconds to check for active template
        let refreshInterval;
        
        function startAutoRefresh() {
            refreshInterval = setInterval(function() {
                // Check if template is now available
                fetch('{{ route("user.api.active-template") }}')
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.id) {
                            // Template is now available, redirect to dashboard
                            window.location.href = '{{ route("user.control.index") }}';
                        }
                    })
                    .catch(error => {
                        console.log('Checking for active template...');
                    });
            }, 30000); // Check every 30 seconds
        }
        
        // Start auto-refresh
        startAutoRefresh();
        
        // Stop auto-refresh when page is hidden
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                clearInterval(refreshInterval);
            } else {
                startAutoRefresh();
            }
        });
        
        // Clean up on page unload
        window.addEventListener('beforeunload', function() {
            clearInterval(refreshInterval);
        });
    });
</script>
@endsection