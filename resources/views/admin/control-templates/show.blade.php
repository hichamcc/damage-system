@extends('components.layouts.app')

@section('title', 'Control Template - ' . $controlTemplate->name)

@section('content')
<div class="p-6">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div>
                    <h3 class="text-xl font-semibold text-gray-900">{{ $controlTemplate->name }}</h3>
                    <p class="text-sm text-gray-600 mt-1">Control Template Details</p>
                </div>
                @if($controlTemplate->is_active)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Active Template
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        Inactive
                    </span>
                @endif
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.control-templates.edit', $controlTemplate) }}" 
                   class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-md transition duration-150 ease-in-out">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Template
                </a>
                <form action="{{ route('admin.control-templates.toggle-active', $controlTemplate) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 {{ $controlTemplate->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white text-sm font-medium rounded-md transition duration-150 ease-in-out">
                        @if($controlTemplate->is_active)
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"/>
                            </svg>
                            Deactivate
                        @else
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Activate
                        @endif
                    </button>
                </form>
                <a href="{{ route('admin.control-templates.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-md transition duration-150 ease-in-out">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Templates
                </a>
            </div>
        </div>

        <!-- Template Info Grid -->
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- Template Details -->
                <div class="lg:col-span-2 space-y-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Template Information</h4>
                        <div class="bg-gray-50 rounded-lg p-4 space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Name</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $controlTemplate->name }}</dd>
                            </div>
                            
                            @if($controlTemplate->description)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Description</dt>
                                    <dd class="text-gray-900">{{ $controlTemplate->description }}</dd>
                                </div>
                            @endif

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Total Tasks</dt>
                                    <dd class="text-2xl font-bold text-blue-600">{{ $controlTemplate->tasks->count() }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Required Tasks</dt>
                                    <dd class="text-2xl font-bold text-red-600">{{ $controlTemplate->tasks->where('is_required', true)->count() }}</dd>
                                </div>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500">Created</dt>
                                <dd class="text-gray-900">{{ $controlTemplate->created_at->format('F j, Y \a\t g:i A') }}</dd>
                                <dd class="text-sm text-gray-500">by {{ $controlTemplate->createdBy->name }}</dd>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Usage Statistics -->
                <div class="lg:col-span-2">
                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Usage Statistics</h4>
                    <div class="grid grid-cols-1 gap-4">
                        <!-- Total Controls -->
                        <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <div class="text-2xl font-bold text-blue-600">{{ $controlTemplate->controlLines->count() }}</div>
                                    <div class="text-sm text-blue-800">Total Controls Created</div>
                                </div>
                            </div>
                        </div>

                        <!-- Completed Controls -->
                        <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <div class="text-2xl font-bold text-green-600">{{ $controlTemplate->controlLines->where('status', 'completed')->count() }}</div>
                                    <div class="text-sm text-green-800">Completed Controls</div>
                                </div>
                            </div>
                        </div>

                        <!-- Active Controls -->
                        <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <div class="text-2xl font-bold text-yellow-600">{{ $controlTemplate->controlLines->where('status', 'active')->count() }}</div>
                                    <div class="text-sm text-yellow-800">Active Controls</div>
                                </div>
                            </div>
                        </div>

                        <!-- Success Rate -->
                        @if($controlTemplate->controlLines->count() > 0)
                            <div class="bg-purple-50 rounded-lg p-4 border border-purple-200">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-2xl font-bold text-purple-600">
                                            {{ round(($controlTemplate->controlLines->where('status', 'completed')->count() / $controlTemplate->controlLines->count()) * 100) }}%
                                        </div>
                                        <div class="text-sm text-purple-800">Completion Rate</div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Template Tasks -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h4 class="text-lg font-medium text-gray-900">Template Tasks ({{ $controlTemplate->tasks->count() }})</h4>
                <div class="text-sm text-gray-500">
                    {{ $controlTemplate->tasks->where('is_required', true)->count() }} required, 
                    {{ $controlTemplate->tasks->where('is_required', false)->count() }} optional
                </div>
            </div>
        </div>
        
        @if($controlTemplate->tasks->count() > 0)
            <div class="divide-y divide-gray-100">
                @foreach($controlTemplate->tasks as $task)
                    <div class="p-6 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start space-x-4">
                            <!-- Task Number -->
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                    {{ $task->sort_order }}
                                </span>
                            </div>

                            <!-- Task Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h5 class="text-lg font-medium text-gray-900 mb-2">{{ $task->title }}</h5>
                                        
                                        @if($task->description)
                                            <p class="text-gray-600 mb-3">{{ $task->description }}</p>
                                        @endif

                                        <!-- Task Meta -->
                                        <div class="flex flex-wrap items-center gap-3">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $task->is_required ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ $task->is_required ? 'Required' : 'Optional' }}
                                            </span>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 capitalize">
                                                {{ $task->task_type }}
                                            </span>
                                            
                                            @if($task->truckTemplate)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    {{ $task->truckTemplate->name }}
                                                    @if($task->template_reference_number)
                                                        - Point {{ $task->template_reference_number }}
                                                    @endif
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Template Image -->
                                    @if($task->truckTemplate)
                                        <div class="flex-shrink-0 ml-4">
                                            <img src="{{ asset('storage/' . $task->truckTemplate->image_path) }}" 
                                                 alt="{{ $task->truckTemplate->name }}"
                                                 class="w-24 h-16 object-cover rounded border border-gray-200 cursor-pointer hover:scale-105 transition-transform shadow-sm"
                                                 onclick="openImagePreview('{{ asset('storage/' . $task->truckTemplate->image_path) }}', '{{ $task->truckTemplate->name }}')">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5l7-7 7 7M9 20h6m-7 4h7m0 0v5a2 2 0 002 2h14a2 2 0 002-2v-5M5 12a2 2 0 012-2h10a2 2 0 012 2v5a2 2 0 01-2 2H7a2 2 0 01-2-2v-5z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No tasks defined</h3>
                <p class="mt-1 text-sm text-gray-500">This template doesn't have any tasks yet.</p>
                <div class="mt-6">
                    <a href="{{ route('admin.control-templates.edit', $controlTemplate) }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md">
                        Add Tasks
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Recent Control Lines -->
    @if($controlTemplate->controlLines->count() > 0)
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h4 class="text-lg font-medium text-gray-900">Recent Controls Created from This Template</h4>
                <a href="{{ route('admin.control.index') }}?template_id={{ $controlTemplate->id }}" 
                   class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                    View All Controls →
                </a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Truck</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($controlTemplate->controlLines->sortByDesc('created_at')->take(10) as $control)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $control->truck->license_plate }}</div>
                                            <div class="text-sm text-gray-500">{{ $control->truck->make }} {{ $control->truck->model }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $control->assignedUser->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $control->assignedUser->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($control->status === 'active')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                            </svg>
                                            Active
                                        </span>
                                    @elseif($control->status === 'completed')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            Completed
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ ucfirst($control->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div>{{ $control->created_at->format('M j, Y') }}</div>
                                    <div class="text-xs text-gray-400">{{ $control->created_at->format('g:i A') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('admin.control.show', $control) }}" 
                                       class="text-blue-600 hover:text-blue-900">View Details</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($controlTemplate->controlLines->count() > 10)
                <div class="px-6 py-3 bg-gray-50 text-center border-t border-gray-200">
                    <a href="{{ route('admin.control.index') }}?template_id={{ $controlTemplate->id }}" 
                       class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                        View all {{ $controlTemplate->controlLines->count() }} controls →
                    </a>
                </div>
            @endif
        </div>
    @endif
</div>

<script>
    // Image preview function
    function openImagePreview(imageSrc, title) {
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-gray-900 bg-opacity-75 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4';
        modal.onclick = function(e) {
            if (e.target === this) {
                document.body.removeChild(this);
            }
        };
        
        modal.innerHTML = `
            <div class="relative bg-white p-6 rounded-lg max-w-4xl max-h-full overflow-auto shadow-xl">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-gray-900">${title}</h3>
                    <button onclick="document.body.removeChild(this.closest('.fixed'))" class="text-gray-400 hover:text-gray-600 p-2 rounded-full hover:bg-gray-100 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <img src="${imageSrc}" alt="${title}" class="max-w-full max-h-96 mx-auto rounded-lg shadow-lg">
                <p class="text-center text-sm text-gray-600 mt-4">Reference template for inspection points</p>
            </div>
        `;
        
        document.body.appendChild(modal);
    }
</script>
@endsection