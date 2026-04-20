<?php
/**
 * Database Migration Script
 * 
 * Run this to create all database tables
 * Make sure you've run clear-cache.php and setup-storage.php first!
 * 
 * IMPORTANT: Delete this file after running!
 */

echo "<h1>Database Migration</h1>";
echo "<pre>";

$basePath = dirname(__DIR__);

// Step 1: Clear any cached config first
echo "🧹 Clearing cached configuration...\n";
$bootstrapCache = $basePath . '/bootstrap/cache';
$configCache = $bootstrapCache . '/config.php';
$routesCache = $bootstrapCache . '/routes-v7.php';
$servicesCache = $bootstrapCache . '/services.php';
$packagesCache = $bootstrapCache . '/packages.php';

$cacheFiles = [$configCache, $routesCache, $servicesCache, $packagesCache];
foreach ($cacheFiles as $file) {
    if (file_exists($file)) {
        unlink($file);
        echo "   ✓ Deleted " . basename($file) . "\n";
    }
}
echo "\n";

// Step 2: Check if storage directories exist
echo "📁 Checking storage directories...\n";
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
    echo "Please run these scripts in order:\n";
    echo "1. clear-cache.php (clears old caches)\n";
    echo "2. setup-storage.php (creates directories)\n";
    echo "3. Then come back to migrate.php\n\n";
    echo "Missing directories:\n";
    foreach ($missingDirs as $dir) {
        echo "   ✗ $dir\n";
    }
    echo "\n";
    echo "<strong style='color: red;'>Run setup-storage.php first!</strong>\n";
    echo "</pre>";
    exit;
}

echo "✅ All storage directories exist\n\n";

// Step 3: Check if .env exists
if (!file_exists($basePath . '/.env')) {
    echo "❌ ERROR: .env file not found!\n\n";
    echo "Please create .env file with your database credentials.\n";
    echo "Copy from .env.example and update the values.\n";
    echo "</pre>";
    exit;
}

echo "✅ .env file found\n\n";

// Step 4: Verify .env doesn't have local paths
$envContent = file_get_contents($basePath . '/.env');
if (strpos($envContent, '/Users/') !== false || strpos($envContent, 'C:\\') !== false) {
    echo "⚠️  WARNING: Your .env file contains local machine paths!\n";
    echo "This will cause errors. Please check your .env file.\n\n";
}

try {
    // Don't use cached config - load fresh
    putenv('APP_CONFIG_CACHE=');
    
    define('LARAVEL_START', microtime(true));

    // Register the Composer autoloader
    require $basePath . '/vendor/autoload.php';

    // Bootstrap Laravel without config cache
    $app = require_once $basePath . '/bootstrap/app.php';

    // Clear any cached config in the app
    if (file_exists($configCache)) {
        unlink($configCache);
    }

    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

    echo "🔄 Running migrations...\n\n";

    // Run migrations
    $status = $kernel->call('migrate', [
        '--force' => true,
    ]);

    echo "\n";
    echo "✅ MIGRATIONS COMPLETED SUCCESSFULLY!\n\n";
    echo "Migration status code: " . $status . "\n\n";
    
    // Show created tables
    echo "📊 Checking database tables...\n";
    try {
        $pdo = new PDO(
            'mysql:host=' . env('DB_HOST', 'localhost') . ';dbname=' . env('DB_DATABASE'),
            env('DB_USERNAME'),
            env('DB_PASSWORD')
        );
        $stmt = $pdo->query('SHOW TABLES');
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo "   Found " . count($tables) . " tables:\n";
        foreach ($tables as $table) {
            echo "   ✓ $table\n";
        }
        echo "\n";
    } catch (Exception $e) {
        echo "   (Could not list tables, but migrations completed)\n\n";
    }
    
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
    
    if (strpos($e->getMessage(), 'No such file or directory') !== false) {
        echo "This is a storage directory error.\n";
        echo "Please run clear-cache.php and setup-storage.php first!\n\n";
    }
    
    echo "Stack trace:\n";
    echo $e->getTraceAsString() . "\n\n";
}

echo "<strong style='color: red;'>⚠️  IMPORTANT: Delete this file (migrate.php) immediately after successful migration!</strong>\n";
echo "</pre>";
?>
