<?php

namespace App\Services;

use App\Models\UploadSession;
use App\Models\Image;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Image as ImageFacade;

class CsvImportService {
    /**
     * Generate image variants
     */
    public function generateVariants(Image $image): void {
        // Implementation of image variant generation
        $original = Storage::path($image->path);

        /* Thumbnail generation logic */
        $thumbnailPath = Storage::path('images/thumbnails/thumb_' . basename($image->path));
        ImageFacade::make($original)->resize(150, 150, function ($constraint){
            $constraint->aspectRatio();
            $constraint->upsize();
        })->save($thumbnailPath);

        /* Medium generation logic */
        $mediumPath = Storage::path('images/medium/medium_' . basename($image->path));
        ImageFacade::make($original)
                    ->resize(600, 600, function($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })->save($mediumPath);
        
        // Update image record with variant paths
        $image->update([
            'thumbnail_path' => $thumbnailPath,
            'medium_path' => $mediumPath,
        ]);
        
        
    }
}