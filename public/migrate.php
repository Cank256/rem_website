<?php
/**
 * Database Migration Script
 * 
 * Run this to create all database tables
 * Make sure you've run setup-storage.php first!
 * 
 * IMPORTANT: Delete this file after running!
 */

echo "<h1>Database Migration</h1>";
echo "<pre>";

// Check if storage directories exist
$basePath = dirname(__DIR__);
$requiredDirs = [
    'storage/framework/views',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/logs',
    'bootstrap/cache',
];

$missingDirs = [];
foreach ($requiredDirs as $dir) {
    if (!is_dir($basePath . '/' . $dir)) {
        $missingDirs[] = $dir;
    }
}

if (!empty($missingDirs)) {
    echo "❌ ERROR: Missing required directories!\n\n";
    echo "Please run setup-storage.php first, or create these directories manually:\n\n";
    foreach ($missingDirs as $dir) {
        echo "   ✗ $dir\n";
    }
    echo "\n";
    echo "<strong style='color: red;'>Run setup-storage.php first, then come back to this script.</strong>\n";
    echo "</pre>";
    exit;
}

echo "✅ Storage directories check passed\n\n";

// Check if .env exists
if (!file_exists($basePath . '/.env')) {
    echo "❌ ERROR: .env file not found!\n\n";
    echo "Please create .env file with your database credentials.\n";
    echo "</pre>";
    exit;
}

echo "✅ .env file found\n\n";

try {
    define('LARAVEL_START', microtime(true));

    // Register the Composer autoloader
    require $basePath . '/vendor/autoload.php';

    // Bootstrap Laravel
    $app = require_once $basePath . '/bootstrap/app.php';

    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

    echo "🔄 Running migrations...\n\n";

    // Run migrations
    $status = $kernel->call('migrate', [
        '--force' => true,
    ]);

    echo "\n";
    echo "✅ MIGRATIONS COMPLETED SUCCESSFULLY!\n\n";
    echo "Migration status code: " . $status . "\n\n";
    
    echo "NEXT STEPS:\n";
    echo "1. Run storage-link.php to create storage symlink\n";
    echo "2. Run create-admin.php to create admin user\n";
    echo "3. DELETE all these PHP files for security!\n\n";

} catch (\Exception $e) {
    echo "❌ ERROR OCCURRED:\n\n";
    echo "Error: " . $e->getMessage() . "\n\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n\n";
    
    if (strpos($e->getMessage(), 'database') !== false || strpos($e->getMessage(), 'connection') !== false) {
        echo "This looks like a database connection error.\n";
        echo "Please check your .env file:\n";
        echo "- DB_HOST (usually 'localhost' or '127.0.0.1')\n";
        echo "- DB_DATABASE (your database name)\n";
        echo "- DB_USERNAME (your database user)\n";
        echo "- DB_PASSWORD (your database password)\n\n";
    }
    
    echo "Stack trace:\n";
    echo $e->getTraceAsString() . "\n\n";
}

echo "<strong style='color: red;'>⚠️  IMPORTANT: Delete this file (migrate.php) immediately after successful migration!</strong>\n";
echo "</pre>";
?>
