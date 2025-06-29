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
                    @if($damage->damage_photos && count($damage->getPhotoUrls()) > 0)
                        <div class="mt-2">
                            <button type="button" 
                                    onclick="showDamagePhotos({{ json_encode($damage->getPhotoUrls()) }}, '{{ addslashes($damage->damage_location ?? 'Unknown Location') }}')"
                                    class="text-xs text-blue-600 hover:text-blue-800 underline cursor-pointer transition-colors">
                                📸 {{ count($damage->getPhotoUrls()) }} photo(s) available - Click to view
                            </button>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        
  
    </div>
@endif

<!-- Simple Damage Photos Modal -->
<div id="damagePhotosModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-75 flex items-center justify-center">
    <div class="bg-white rounded-lg max-w-4xl w-full mx-4 max-h-[90vh] overflow-hidden">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-4 border-b">
            <h3 id="modalTitle" class="text-lg font-medium text-gray-900">Damage Photos</h3>
            <button onclick="closeDamagePhotosModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="p-4">
            <!-- Photo Display -->
            <div class="relative mb-4">
                <div id="photoContainer" class="flex justify-center">
                    <img id="currentPhoto" src="" alt="Damage photo" class="max-w-full max-h-96 object-contain rounded-lg shadow-lg">
                </div>
                
                <!-- Navigation arrows -->
                <button id="prevBtn" onclick="prevPhoto()" 
                        class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75 transition-opacity hidden">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                
                <button id="nextBtn" onclick="nextPhoto()" 
                        class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75 transition-opacity hidden">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Photo counter -->
            <div id="photoCounter" class="text-center mb-4 text-sm text-gray-500 hidden"></div>
            
            <!-- Thumbnail navigation -->
            <div id="thumbnailContainer" class="flex justify-center space-x-2 overflow-x-auto hidden"></div>
        </div>
        
        <!-- Modal Footer -->
        <div class="bg-gray-50 px-4 py-3 flex justify-end">
            <button onclick="closeDamagePhotosModal()" 
                    class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Close
            </button>
        </div>
    </div>
</div>

<script>
let currentPhotos = [];
let currentPhotoIndex = 0;

function showDamagePhotos(photos, location) {
    // Extract URLs from photo objects if they have url property, otherwise use them directly
    currentPhotos = photos.map(photo => {
        return typeof photo === 'object' && photo.url ? photo.url : photo;
    });
    currentPhotoIndex = 0;
    
    // Set modal title
    document.getElementById('modalTitle').textContent = 'Damage Photos - ' + location;
    
    // Show the modal
    document.getElementById('damagePhotosModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden'; // Prevent body scroll
    
    // Display first photo
    displayCurrentPhoto();
    
    // Setup navigation if multiple photos
    if (currentPhotos.length > 1) {
        setupMultiplePhotos();
    } else {
        hideNavigationElements();
    }
}

function closeDamagePhotosModal() {
    document.getElementById('damagePhotosModal').classList.add('hidden');
    document.body.style.overflow = ''; // Restore body scroll
}

function displayCurrentPhoto() {
    const img = document.getElementById('currentPhoto');
    img.src = currentPhotos[currentPhotoIndex];
    img.alt = 'Damage photo ' + (currentPhotoIndex + 1);
}

function setupMultiplePhotos() {
    // Show navigation buttons
    document.getElementById('prevBtn').classList.remove('hidden');
    document.getElementById('nextBtn').classList.remove('hidden');
    
    // Show photo counter
    const counter = document.getElementById('photoCounter');
    counter.classList.remove('hidden');
    updatePhotoCounter();
    
    // Setup thumbnails
    setupThumbnails();
}

function hideNavigationElements() {
    document.getElementById('prevBtn').classList.add('hidden');
    document.getElementById('nextBtn').classList.add('hidden');
    document.getElementById('photoCounter').classList.add('hidden');
    document.getElementById('thumbnailContainer').classList.add('hidden');
}

function updatePhotoCounter() {
    const counter = document.getElementById('photoCounter');
    counter.textContent = (currentPhotoIndex + 1) + ' of ' + currentPhotos.length;
}

function setupThumbnails() {
    const container = document.getElementById('thumbnailContainer');
    container.innerHTML = '';
    container.classList.remove('hidden');
    
    currentPhotos.forEach((photo, index) => {
        const thumb = document.createElement('button');
        thumb.className = 'flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden transition-opacity border-2 ' + 
                         (index === currentPhotoIndex ? 'border-blue-500' : 'border-transparent opacity-70 hover:opacity-100');
        thumb.onclick = () => goToPhoto(index);
        
        const img = document.createElement('img');
        img.src = photo;
        img.alt = 'Thumbnail ' + (index + 1);
        img.className = 'w-full h-full object-cover';
        
        thumb.appendChild(img);
        container.appendChild(thumb);
    });
}

function goToPhoto(index) {
    currentPhotoIndex = index;
    displayCurrentPhoto();
    updatePhotoCounter();
    updateThumbnailSelection();
}

function nextPhoto() {
    currentPhotoIndex = (currentPhotoIndex + 1) % currentPhotos.length;
    displayCurrentPhoto();
    updatePhotoCounter();
    updateThumbnailSelection();
}

function prevPhoto() {
    currentPhotoIndex = currentPhotoIndex === 0 ? currentPhotos.length - 1 : currentPhotoIndex - 1;
    displayCurrentPhoto();
    updatePhotoCounter();
    updateThumbnailSelection();
}

function updateThumbnailSelection() {
    const thumbnails = document.getElementById('thumbnailContainer').children;
    for (let i = 0; i < thumbnails.length; i++) {
        if (i === currentPhotoIndex) {
            thumbnails[i].className = thumbnails[i].className.replace('border-transparent opacity-70', 'border-blue-500');
        } else {
            thumbnails[i].className = thumbnails[i].className.replace('border-blue-500', 'border-transparent opacity-70');
        }
    }
}

// Close modal when clicking outside
document.getElementById('damagePhotosModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDamagePhotosModal();
    }
});

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    const modal = document.getElementById('damagePhotosModal');
    if (!modal.classList.contains('hidden')) {
        if (e.key === 'Escape') {
            closeDamagePhotosModal();
        } else if (e.key === 'ArrowLeft' && currentPhotos.length > 1) {
            prevPhoto();
        } else if (e.key === 'ArrowRight' && currentPhotos.length > 1) {
            nextPhoto();
        }
    }
});

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