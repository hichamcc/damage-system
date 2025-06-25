<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class FileUploadService
{
    /**
     * Upload task photos
     */
    public function uploadTaskPhotos(array $photos, int $controlLineId, int $taskId): array
    {
        $uploadedPaths = [];
        
        foreach ($photos as $photo) {
            if ($photo instanceof UploadedFile) {
                $uploadedPaths[] = $this->uploadTaskPhoto($photo, $controlLineId, $taskId);
            }
        }
        
        return $uploadedPaths;
    }

    /**
     * Upload single task photo
     */
    public function uploadTaskPhoto(UploadedFile $photo, int $controlLineId, int $taskId): string
    {
        // Generate unique filename
        $filename = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
        
        // Define path
        $path = "task-photos/{$controlLineId}/{$taskId}";
        
        // Resize and optimize image
        //keep aspect ratio if image not a square
    

        $image = Image::read($photo)
        ->scaleDown(width: 1200)
        ->toJpeg(85);

        
        // Store the optimized image
        $fullPath = $path . '/' . $filename;
        Storage::disk('public')->put($fullPath, $image);
        
        return $fullPath;
    }

    /**
     * Upload damage photos
     */
    public function uploadDamagePhotos(array $photos, int $controlLineId): array
    {
        $uploadedPhotos = [];
        
        foreach ($photos as $photo) {
            if ($photo instanceof UploadedFile) {
                $uploadedPhotos[] = $this->uploadDamagePhoto($photo, $controlLineId);
            }
        }
        
        return $uploadedPhotos;
    }

    /**
     * Upload single damage photo
     */
    public function uploadDamagePhoto(UploadedFile $photo, int $controlLineId): array
    {
        // Generate unique filename
        $filename = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
        
        // Define path
        $path = "damage-photos/{$controlLineId}";
        
        // Resize and optimize image
        $image = Image::make($photo)
            ->resize(1200, 1200, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->encode('jpg', 85);
        
        // Store the optimized image
        $fullPath = $path . '/' . $filename;
        Storage::disk('public')->put($fullPath, $image);
        
        return [
            'path' => $fullPath,
            'original_name' => $photo->getClientOriginalName(),
            'size' => $image->filesize(),
            'uploaded_at' => now()->toISOString(),
        ];
    }

    /**
     * Delete photos from storage
     */
    public function deletePhotos(array $photoPaths): bool
    {
        try {
            foreach ($photoPaths as $path) {
                if (is_array($path) && isset($path['path'])) {
                    Storage::disk('public')->delete($path['path']);
                } else {
                    Storage::disk('public')->delete($path);
                }
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get photo URL
     */
    public function getPhotoUrl(string $path): string
    {
        return Storage::url($path);
    }

    /**
     * Validate photo
     */
    public function validatePhoto(UploadedFile $photo): bool
    {
        // Check file size (10MB max)
        if ($photo->getSize() > 10 * 1024 * 1024) {
            return false;
        }

        // Check mime type
        $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!in_array($photo->getMimeType(), $allowedMimes)) {
            return false;
        }

        return true;
    }

    /**
     * Get optimized storage path for control line
     */
    public function getControlLineStoragePath(int $controlLineId): string
    {
        return "controls/{$controlLineId}";
    }

    /**
     * Clean up old photos (for maintenance)
     */
    public function cleanupOldPhotos(int $daysOld = 30): int
    {
        $deletedCount = 0;
        $cutoffDate = now()->subDays($daysOld);
        
        // This would require additional tracking of file creation dates
        // Implementation depends on your cleanup requirements
        
        return $deletedCount;
    }
}