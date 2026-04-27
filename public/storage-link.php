<?php
/**
 * Storage Link Creation Script
 * 
 * Creates symbolic link from public/storage to storage/app/public
 * Run this AFTER running migrations
 * 
 * IMPORTANT: Delete this file after running!
 */

echo "<h1>Storage Link Setup</h1>";
echo "<pre>";

$basePath = dirname(__DIR__);

// Check if storage/app/public exists
if (!is_dir($basePath . '/storage/app/public')) {
    echo "📁 Creating storage/app/public directory...\n";
    mkdir($basePath . '/storage/app/public', 0755, true);
    file_put_contents($basePath . '/storage/app/public/.gitignore', "*\n!.gitignore\n");
    echo "✅ Directory created\n\n";
}

try {
    define('LARAVEL_START', microtime(true));

    require $basePath . '/vendor/autoload.php';

    $app = require_once $basePath . '/bootstrap/app.php';

    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

    echo "🔗 Creating storage link...\n\n";

    // Check if link already exists
    $linkPath = $basePath . '/public/storage';
    if (file_exists($linkPath)) {
        if (is_link($linkPath)) {
            echo "ℹ️  Storage link already exists\n";
            echo "   Removing old link...\n";
            unlink($linkPath);
        } else {
            echo "⚠️  Warning: public/storage exists but is not a symlink\n";
            echo "   You may need to delete it manually via File Manager\n\n";
        }
    }

    $status = $kernel->call('storage:link');

    echo "\n";
    echo "✅ STORAGE LINK CREATED SUCCESSFULLY!\n\n";
    
    // Verify the link
    if (is_link($linkPath)) {
        echo "✓ Symlink verified at: public/storage\n";
        echo "✓ Points to: storage/app/public\n\n";
    } else {
        echo "⚠️  Warning: Could not verify symlink\n";
        echo "   You may need to create it manually\n\n";
    }
    
    echo "NEXT STEPS:\n";
    echo "1. Run create-admin.php to create admin user\n";
    echo "2. DELETE all these PHP files for security!\n\n";

} catch (\Exception $e) {
    echo "❌ ERROR OCCURRED:\n\n";
    echo "Error: " . $e->getMessage() . "\n\n";
    
    if (strpos($e->getMessage(), 'symlink') !== false) {
        echo "Symlink creation failed. This is common on some shared hosting.\n\n";
        echo "MANUAL WORKAROUND:\n";
        echo "1. In File Manager, go to public/ folder\n";
        echo "2. Create a folder named 'storage'\n";
        echo "3. Copy all contents from storage/app/public/ to public/storage/\n";
        echo "4. When you upload images, they'll go to storage/app/public/\n";
        echo "5. You'll need to manually copy them to public/storage/ to display\n\n";
        echo "OR ask your hosting provider to enable symlink support.\n\n";
    }
}

echo "<strong style='color: red;'>⚠️  IMPORTANT: Delete this file (storage-link.php) immediately after running!</strong>\n";
echo "</pre>";
?>
