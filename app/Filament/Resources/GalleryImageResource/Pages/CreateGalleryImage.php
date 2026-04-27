<?php

namespace App\Filament\Resources\GalleryImageResource\Pages;

use App\Filament\Resources\GalleryImageResource;
use App\Models\GalleryImage;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateGalleryImage extends CreateRecord
{
    protected static string $resource = GalleryImageResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // If multiple images are uploaded, we'll handle them separately
        if (isset($data['image_path']) && is_array($data['image_path']) && count($data['image_path']) > 1) {
            $this->multipleImages = $data['image_path'];
            $data['image_path'] = $data['image_path'][0]; // Keep first image for main record
        }
        
        return $data;
    }

    protected function afterCreate(): void
    {
        // Create additional records for remaining images
        if (isset($this->multipleImages) && count($this->multipleImages) > 1) {
            $baseData = $this->record->toArray();
            
            foreach (array_slice($this->multipleImages, 1) as $index => $imagePath) {
                GalleryImage::create([
                    'gallery_id' => $baseData['gallery_id'],
                    'image_path' => $imagePath,
                    'title' => null,
                    'description' => null,
                    'sort_order' => $baseData['sort_order'] + $index + 1,
                ]);
            }
        }
    }

    private $multipleImages = [];
}
