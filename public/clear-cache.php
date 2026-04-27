<?php
/**
 * Cache Clearing Script
 * 
 * Clears all Laravel caches and optimizes the application
 * Run this FIRST before any other scripts
 * 
 * IMPORTANT: Delete this file after running!
 */

echo "<h1>Cache Clearing & Optimization</h1>";
echo "<pre>";

$basePath = dirname(__DIR__);

echo "🧹 Clearing all caches...\n\n";

$cleared = [];
$errors = [];

// 1. Clear bootstrap cache files
echo "1️⃣  Clearing bootstrap cache...\n";
$bootstrapCache = $basePath . '/bootstrap/cache';
if (is_dir($bootstrapCache)) {
    $files = glob($bootstrapCache . '/*.php');
    foreach ($files as $file) {
        if (basename($file) !== '.gitignore') {
            if (unlink($file)) {
                $cleared[] = 'bootstrap/cache/' . basename($file);
            } else {
                $errors[] = 'bootstrap/cache/' . basename($file);
            }
        }
    }
    echo "   ✓ Bootstrap cache cleared\n\n";
} else {
    echo "   ⚠️  Bootstrap cache directory not found\n\n";
}

// 2. Clear framework cache
echo "2️⃣  Clearing framework cache...\n";
$frameworkCache = $basePath . '/storage/framework/cache/data';
if (is_dir($frameworkCache)) {
    $files = glob($frameworkCache . '/*');
    $count = 0;
    foreach ($files as $file) {
        if (is_file($file) && basename($file) !== '.gitignore') {
            if (unlink($file)) {
                $count++;
            }
        }
    }
    echo "   ✓ Cleared $count cache files\n\n";
} else {
    echo "   ⚠️  Framework cache directory not found\n\n";
}

// 3. Clear views cache
echo "3️⃣  Clearing compiled views...\n";
$viewsCache = $basePath . '/storage/framework/views';
if (is_dir($viewsCache)) {
    $files = glob($viewsCache . '/*.php');
    $count = 0;
    foreach ($files as $file) {
        if (basename($file) !== '.gitignore') {
            if (unlink($file)) {
                $count++;
            }
        }
    }
    echo "   ✓ Cleared $count compiled views\n\n";
} else {
    echo "   ⚠️  Views cache directory not found\n\n";
}

// 4. Clear sessions
echo "4️⃣  Clearing sessions...\n";
$sessionsPath = $basePath . '/storage/framework/sessions';
if (is_dir($sessionsPath)) {
    $files = glob($sessionsPath . '/*');
    $count = 0;
    foreach ($files as $file) {
        if (is_file($file) && basename($file) !== '.gitignore') {
            if (unlink($file)) {
                $count++;
            }
        }
    }
    echo "   ✓ Cleared $count session files\n\n";
} else {
    echo "   ⚠️  Sessions directory not found\n\n";
}

// 5. Clear logs (optional - be careful)
echo "5️⃣  Checking logs...\n";
$logsPath = $basePath . '/storage/logs';
if (is_dir($logsPath)) {
    $logFiles = glob($logsPath . '/*.log');
    echo "   ℹ️  Found " . count($logFiles) . " log files\n";
    echo "   (Not deleting logs - you may want to check them)\n\n";
} else {
    echo "   ⚠️  Logs directory not found\n\n";
}

// 6. Check .env file
echo "6️⃣  Checking .env file...\n";
$envPath = $basePath . '/.env';
if (file_exists($envPath)) {
    echo "   ✓ .env file exists\n";
    
    // Check for local paths in .env
    $envContent = file_get_contents($envPath);
    if (strpos($envContent, '/Users/') !== false || strpos($envContent, 'C:\\') !== false) {
        echo "   ⚠️  WARNING: .env contains local paths!\n";
        echo "   Please check your .env file for local machine paths\n\n";
    } else {
        echo "   ✓ No local paths detected in .env\n\n";
    }
} else {
    echo "   ❌ .env file NOT FOUND!\n";
    echo "   Please create .env file before proceeding\n\n";
}

// 7. Verify directory structure
echo "7️⃣  Verifying directory structure...\n";
$requiredDirs = [
    'storage/app/public',
    'storage/framework/cache/data',
    'storage/framework/sessions',
    'storage/framework/testing',
    'storage/framework/views',
    'storage/logs',
    'bootstrap/cache',
];

$missing = [];
foreach ($requiredDirs as $dir) {
    if (!is_dir($basePath . '/' . $dir)) {
        $missing[] = $dir;
    }
}

if (empty($missing)) {
    echo "   ✓ All required directories exist\n\n";
} else {
    echo "   ⚠️  Missing directories:\n";
    foreach ($missing as $dir) {
        echo "      - $dir\n";
    }
    echo "   Run setup-storage.php to create them\n\n";
}

// Summary
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "✅ CACHE CLEARING COMPLETE!\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

if (!empty($cleared)) {
    echo "📋 Cleared files:\n";
    foreach (array_slice($cleared, 0, 10) as $file) {
        echo "   ✓ $file\n";
    }
    if (count($cleared) > 10) {
        echo "   ... and " . (count($cleared) - 10) . " more\n";
    }
    echo "\n";
}

if (!empty($errors)) {
    echo "❌ Could not clear:\n";
    foreach ($errors as $file) {
        echo "   ✗ $file\n";
    }
    echo "\n";
}

echo "NEXT STEPS:\n";
echo "1. If directories are missing, run setup-storage.php\n";
echo "2. Verify .env file has correct settings\n";
echo "3. Run migrate.php to create database tables\n";
echo "4. DELETE this file immediately!\n\n";

echo "<strong style='color: red;'>⚠️  IMPORTANT: Delete this file (clear-cache.php) immediately after running!</strong>\n";
echo "</pre>";
?>
