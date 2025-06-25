@extends('components.layouts.app')

@section('title', 'Damage Reports')

@section('content')
<div class="p-6">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-medium text-gray-900">Damage Reports Management</h3>
                <p class="text-sm text-gray-600">Monitor and manage all damage reports across all controls</p>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-600">{{ $stats['total'] }}</div>
                    <div class="text-sm text-gray-600">Total Reports</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $stats['reported'] }}</div>
                    <div class="text-sm text-gray-600">Newly Reported</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-yellow-600">{{ $stats['in_repair'] }}</div>
                    <div class="text-sm text-gray-600">In Repair</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $stats['fixed'] }}</div>
                    <div class="text-sm text-gray-600">Fixed</div>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="p-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        <option value="">All Status</option>
                        <option value="reported" {{ request('status') == 'reported' ? 'selected' : '' }}>Reported</option>
                        <option value="in_repair" {{ request('status') == 'in_repair' ? 'selected' : '' }}>In Repair</option>
                        <option value="fixed" {{ request('status') == 'fixed' ? 'selected' : '' }}>Fixed</option>
                        <option value="ignored" {{ request('status') == 'ignored' ? 'selected' : '' }}>Ignored</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Severity</label>
                    <select name="severity" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        <option value="">All Severities</option>
                        <option value="minor" {{ request('severity') == 'minor' ? 'selected' : '' }}>Minor</option>
                        <option value="major" {{ request('severity') == 'major' ? 'selected' : '' }}>Major</option>
                        <option value="critical" {{ request('severity') == 'critical' ? 'selected' : '' }}>Critical</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Truck</label>
                    <select name="truck_id" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        <option value="">All Trucks</option>
                        @foreach(\App\Models\Truck::all() as $truck)
                            <option value="{{ $truck->id }}" {{ request('truck_id') == $truck->id ? 'selected' : '' }}>
                                {{ $truck->license_plate }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date From</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date To</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md">
                        Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Damage Reports Table -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h4 class="text-md font-medium text-gray-900">Damage Reports ({{ $damages->total() }})</h4>
        </div>

        @if($damages->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Damage Info</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Truck & Control</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status & Severity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reported</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($damages as $damage)
                            <tr class="hover:bg-gray-50">
                                <!-- Damage Info -->
                                <td class="px-6 py-4">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0">
                                            @if($damage->damage_photos && count($damage->getPhotoUrls()) > 0)
                                                <div class="w-12 h-12 rounded-lg overflow-hidden border border-gray-200">
                                                    <img src="{{ $damage->getPhotoUrls()[0]['url'] }}" 
                                                         alt="Damage photo"
                                                         class="w-full h-full object-cover cursor-pointer hover:scale-105 transition-transform"
                                                         onclick="openImagePreview('{{ $damage->getPhotoUrls()[0]['url'] }}', 'Damage Photo')">
                                                </div>
                                            @else
                                                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $damage->damage_location ?? 'Damage Report' }}
                                            </div>
                                            <div class="text-sm text-gray-600 mt-1">
                                                {{ Str::limit($damage->damage_description, 80) }}
                                            </div>
                                            @if($damage->damage_area)
                                                <div class="text-xs text-blue-600 mt-1">
                                                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    </svg>
                                                    Area: {{ $damage->damage_area }}
                                                </div>
                                            @endif
                                            @if($damage->damage_photos && count($damage->getPhotoUrls()) > 1)
                                                <div class="text-xs text-gray-500 mt-1">
                                                    +{{ count($damage->getPhotoUrls()) - 1 }} more photos
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <!-- Truck & Control -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $damage->truck->license_plate }}</div>
                                    <div class="text-sm text-gray-500">{{ $damage->truck->make }} {{ $damage->truck->model }}</div>
                                    @if($damage->controlLine)
                                        <div class="text-xs text-blue-600 mt-1">
                                            <a href="{{ route('admin.control.show', $damage->controlLine) }}" class="hover:underline">
                                                Control #{{ $damage->controlLine->id }}
                                            </a>
                                        </div>
                                    @endif
                                    @if($damage->controlTask)
                                        <div class="text-xs text-gray-500 mt-1">
                                            Task: {{ Str::limit($damage->controlTask->title, 30) }}
                                        </div>
                                    @endif
                                </td>

                                <!-- Status & Severity -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="space-y-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $damage->status_color }}">
                                            @if($damage->status === 'reported')
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                                </svg>
                                            @elseif($damage->status === 'in_repair')
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                                                </svg>
                                            @elseif($damage->status === 'fixed')
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                            @endif
                                            {{ ucfirst(str_replace('_', ' ', $damage->status)) }}
                                        </span>
                                        <div>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $damage->severity_color }}">
                                                @if($damage->severity === 'critical')
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                    </svg>
                                                @elseif($damage->severity === 'major')
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                                    </svg>
                                                @else
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 011 1v4a1 1 0 11-2 0V6a1 1 0 011-1z" clip-rule="evenodd"/>
                                                    </svg>
                                                @endif
                                                {{ ucfirst($damage->severity) }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Reported -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div>{{ $damage->created_at->format('M j, Y') }}</div>
                                    <div class="text-xs text-gray-400">{{ $damage->created_at->format('g:i A') }}</div>
                                    <div class="text-xs text-gray-600 mt-1">by {{ $damage->reportedBy->name }}</div>
                                    @if($damage->fixed_date)
                                        <div class="text-xs text-green-600 mt-1">
                                            Fixed: {{ $damage->fixed_date->format('M j, Y') }}
                                        </div>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.damages.show', $damage) }}" 
                                           class="text-blue-600 hover:text-blue-900">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        
                                        @if($damage->status !== 'fixed')
                                            <div class="relative" x-data="{ open: false }">
                                                <button @click="open = !open" class="text-gray-600 hover:text-gray-900">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                                    </svg>
                                                </button>
                                                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 border border-gray-200">
                                                    @if($damage->status === 'reported')
                                                        <form method="POST" action="{{ route('admin.damages.update-status', $damage) }}" class="block">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status" value="in_repair">
                                                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                                Mark as In Repair
                                                            </button>
                                                        </form>
                                                    @endif
                                                        <button onclick="openFixedModal({{ $damage->id }})" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                            Mark as Fixed
                                                        </button>

                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($damages->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $damages->appends(request()->query())->links() }}
                </div>
            @endif
        @else
            <div class="px-6 py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No damage reports found</h3>
                <p class="mt-1 text-sm text-gray-500">No damage reports match your current filters.</p>
            </div>
        @endif
    </div>
</div>

<!-- Fixed Modal -->
<div id="fixedModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Mark Damage as Fixed</h3>
            <form id="fixedForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fixed Date</label>
                    <input type="date" name="fixed_date" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md" 
                           value="{{ date('Y-m-d') }}">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Repair Notes</label>
                    <textarea name="repair_notes" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md"
                              placeholder="Optional repair details..."></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeFixedModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        Mark as Fixed
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
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
                    <button onclick="document.body.removeChild(this.closest('.fixed'))" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="text-center">
                    <img src="${imageSrc}" alt="${title}" class="max-w-full max-h-96 mx-auto rounded-lg shadow-lg">
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
    }

    function openFixedModal(damageId) {
        const modal = document.getElementById('fixedModal');
        const form = document.getElementById('fixedForm');
        form.action = `/admin/damages/${damageId}/fixed`;
        modal.classList.remove('hidden');
    }

    function closeFixedModal() {
        document.getElementById('fixedModal').classList.add('hidden');
    }
</script>
@endsection