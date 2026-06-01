<?php
/**
 * Migrate Gallery Images from storage/app/public to public/gallery-images
 * Run this ONCE after deploying the new configuration
 * DELETE THIS FILE AFTER USE!
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

echo "🔄 Migrating Gallery Images\n";
echo "============================\n\n";

$oldPath = storage_path('app/public/gallery-images');
$newPath = public_path('gallery-images');

// Create new directory if it doesn't exist
if (!File::exists($newPath)) {
    File::makeDirectory($newPath, 0755, true);
    echo "✅ Created directory: $newPath\n\n";
}

// Check if old directory exists
if (!File::exists($oldPath)) {
    echo "ℹ️  No images found in old location: $oldPath\n";
    echo "Nothing to migrate.\n";
    exit(0);
}

// Get all images from database
$images = DB::table('gallery_images')->get();

echo "📊 Found " . count($images) . " image records in database\n\n";

$migrated = 0;
$skipped = 0;
$errors = 0;

foreach ($images as $image) {
    $oldImagePath = $image->image_path;
    
    // Check if path starts with storage/ or gallery-images/
    if (str_starts_with($oldImagePath, 'storage/gallery-images/')) {
        $filename = basename($oldImagePath);
    } elseif (str_starts_with($oldImagePath, 'gallery-images/')) {
        $filename = basename($oldImagePath);
    } else {
        $filename = basename($oldImagePath);
    }
    
    $oldFullPath = $oldPath . '/' . $filename;
    $newFullPath = $newPath . '/' . $filename;
    $newDbPath = 'gallery-images/' . $filename;
    
    // Check if file exists in old location
    if (!File::exists($oldFullPath)) {
        echo "⚠️  File not found: $filename (skipping)\n";
        $skipped++;
        continue;
    }
    
    // Check if file already exists in new location
    if (File::exists($newFullPath)) {
        echo "ℹ️  Already exists: $filename (updating DB only)\n";
        DB::table('gallery_images')
            ->where('id', $image->id)
            ->update(['image_path' => $newDbPath]);
        $migrated++;
        continue;
    }
    
    // Copy file to new location
    try {
        File::copy($oldFullPath, $newFullPath);
        chmod($newFullPath, 0644);
        
        // Update database
        DB::table('gallery_images')
            ->where('id', $image->id)
            ->update(['image_path' => $newDbPath]);
        
        echo "✅ Migrated: $filename\n";
        $migrated++;
    } catch (Exception $e) {
        echo "❌ Error migrating $filename: " . $e->getMessage() . "\n";
        $errors++;
    }
}

echo "\n============================\n";
echo "📊 Migration Summary:\n";
echo "✅ Migrated: $migrated\n";
echo "⚠️  Skipped: $skipped\n";
echo "❌ Errors: $errors\n\n";

if ($migrated > 0) {
    echo "✅ Migration complete!\n";
    echo "ℹ️  Old images are still in: $oldPath\n";
    echo "ℹ️  You can delete them after verifying everything works.\n\n";
}

echo "🔒 IMPORTANT: Delete this file (migrate-images.php) after use!\n";
