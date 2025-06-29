@php
$damageReports = $damageReports ?? collect();
@endphp

@if($damageReports->isNotEmpty())
    <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
        <div class="flex items-center mb-3">
            <svg class="w-5 h-5 text-yellow-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <h4 class="text-lg font-medium text-yellow-800">
                Previous Damage Reports ({{ $damageReports->count() }})
            </h4>
        </div>
        
        <p class="text-sm text-yellow-700 mb-4">
            This truck has existing damage reports that are still open. Please be aware of these when conducting your inspection.
        </p>

        <div class="space-y-3 max-h-64 overflow-y-auto">
            @foreach($damageReports as $damage)
                <div class="bg-white border border-yellow-300 rounded-lg p-3">
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex-1">
                            <h5 class="font-medium text-gray-900">{{ $damage->damage_location ?? 'Unknown Location' }}</h5>
                            <p class="text-sm text-gray-600 mt-1">{{ $damage->damage_description ?? 'No description available' }}</p>
                            
                            @if(isset($damage->template_area) && $damage->template_area)
                                <div class="mt-2">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                        </svg>
                                        Area: {{ $damage->template_area }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="flex flex-col items-end space-y-1">
                            <!-- Status Badge -->
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                @if(($damage->status ?? '') === 'reported') bg-red-100 text-red-800
                                @elseif(($damage->status ?? '') === 'in_repair') bg-yellow-100 text-yellow-800
                                @else bg-green-100 text-green-800
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $damage->status ?? 'unknown')) }}
                            </span>
                            
                            <!-- Severity Badge -->
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                @if(($damage->severity ?? '') === 'critical') bg-red-100 text-red-800
                                @elseif(($damage->severity ?? '') === 'major') bg-orange-100 text-orange-800
                                @else bg-yellow-100 text-yellow-800
                                @endif">
                                {{ ucfirst($damage->severity ?? 'minor') }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Reporter and Date Info -->
                    <div class="flex justify-between items-center text-xs text-gray-500 mt-2 pt-2 border-t border-gray-200">
                        <span>
                            Reported by: {{ optional($damage->reportedBy)->name ?? 'Unknown' }}
                        </span>
                        <span>
                            {{ optional($damage->created_at)->format('M d, Y H:i') ?? 'Unknown date' }}
                        </span>
                    </div>
                    
                    <!-- Photos Preview (if any) -->
                    @if(isset($damage->damage_photos) && is_array($damage->damage_photos) && count($damage->damage_photos) > 0)
                        <div class="mt-2">
                            <button type="button" 
                                    onclick="showDamagePhotos({{ json_encode($damage->damage_photos) }}, '{{ $damage->damage_location ?? 'Unknown Location' }}')"
                                    class="text-xs text-blue-600 hover:text-blue-800 underline cursor-pointer transition-colors">
                                📸 {{ count($damage->damage_photos) }} photo(s) available - Click to view
                            </button>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        
        <!-- Expandable section for more details -->
        <div class="mt-3 pt-3 border-t border-yellow-300">
            <button type="button" onclick="toggleDamageDetails()" 
                    class="text-sm text-yellow-700 hover:text-yellow-900 font-medium">
                <span id="toggle-text">Show More Details</span>
                <svg id="toggle-icon" class="w-4 h-4 inline ml-1 transform transition-transform" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </button>
            
            <div id="damage-details" class="hidden mt-3 text-sm text-yellow-700">
                <p><strong>Tips for inspection:</strong></p>
                <ul class="list-disc list-inside mt-1 space-y-1">
                    <li>Check if existing damage has worsened</li>
                    <li>Look for new damage in the same areas</li>
                    <li>Note if any repairs have been made</li>
                    <li>Take photos of current condition for comparison</li>
                </ul>
            </div>
        </div>
    </div>
@endif

<!-- Damage Photos Modal -->
<div x-data="{ 
    showModal: false, 
    photos: [], 
    location: '',
    currentPhotoIndex: 0,
    showPhoto(photos, location) {
        this.photos = photos;
        this.location = location;
        this.currentPhotoIndex = 0;
        this.showModal = true;
    },
    nextPhoto() {
        this.currentPhotoIndex = (this.currentPhotoIndex + 1) % this.photos.length;
    },
    prevPhoto() {
        this.currentPhotoIndex = this.currentPhotoIndex === 0 ? this.photos.length - 1 : this.currentPhotoIndex - 1;
    }
}" 
x-show="showModal" 
x-transition:enter="transition ease-out duration-300"
x-transition:enter-start="opacity-0"
x-transition:enter-end="opacity-100"
x-transition:leave="transition ease-in duration-200"
x-transition:leave-start="opacity-100"
x-transition:leave-end="opacity-0"
class="fixed inset-0 z-50 overflow-y-auto"
style="display: none;">
    
    <!-- Modal Background -->
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
        <!-- Backdrop -->
        <div x-show="showModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-500 bg-opacity-75" 
             @click="showModal = false"></div>

        <!-- Modal Content -->
        <div x-show="showModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            
            <!-- Modal Header -->
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" x-text="'Damage Photos - ' + location"></h3>
                    <button @click="showModal = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Photo Display -->
                <div class="relative">
                    <template x-for="(photo, index) in photos" :key="index">
                        <div x-show="currentPhotoIndex === index" class="flex justify-center">
                            <img :src="photo" 
                                 :alt="'Damage photo ' + (index + 1)"
                                 class="max-w-full max-h-96 object-contain rounded-lg shadow-lg">
                        </div>
                    </template>
                    
                    <!-- Navigation arrows (show only if more than 1 photo) -->
                    <template x-if="photos.length > 1">
                        <div>
                            <!-- Previous button -->
                            <button @click="prevPhoto()" 
                                    class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75 transition-opacity">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>
                            
                            <!-- Next button -->
                            <button @click="nextPhoto()" 
                                    class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75 transition-opacity">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </div>
                    </template>
                </div>
                
                <!-- Photo counter -->
                <div x-show="photos.length > 1" class="text-center mt-4 text-sm text-gray-500">
                    <span x-text="(currentPhotoIndex + 1) + ' of ' + photos.length"></span>
                </div>
                
                <!-- Thumbnail navigation (show only if more than 1 photo) -->
                <template x-if="photos.length > 1">
                    <div class="flex justify-center mt-4 space-x-2 overflow-x-auto">
                        <template x-for="(photo, index) in photos" :key="index">
                            <button @click="currentPhotoIndex = index" 
                                    :class="currentPhotoIndex === index ? 'ring-2 ring-blue-500' : 'opacity-70 hover:opacity-100'"
                                    class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden transition-opacity">
                                <img :src="photo" 
                                     :alt="'Thumbnail ' + (index + 1)"
                                     class="w-full h-full object-cover">
                            </button>
                        </template>
                    </div>
                </template>
            </div>
            
            <!-- Modal Footer -->
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button @click="showModal = false" 
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Global function to trigger the modal
function showDamagePhotos(photos, location) {
    // Find the Alpine.js component and call its method
    const modalComponent = document.querySelector('[x-data*="showModal"]').__x.$data;
    modalComponent.showPhoto(photos, location);
}

function toggleDamageDetails() {
    const details = document.getElementById('damage-details');
    const toggleText = document.getElementById('toggle-text');
    const toggleIcon = document.getElementById('toggle-icon');
    
    if (details.classList.contains('hidden')) {
        details.classList.remove('hidden');
        toggleText.textContent = 'Show Less Details';
        toggleIcon.classList.add('rotate-180');
    } else {
        details.classList.add('hidden');
        toggleText.textContent = 'Show More Details';
        toggleIcon.classList.remove('rotate-180');
    }
}
</script>