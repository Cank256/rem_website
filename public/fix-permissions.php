<?php
/**
 * Fix Permissions Script
 * 
 * Fixes file permissions for Laravel on shared hosting
 * Run this if you get 403 Forbidden errors
 * 
 * IMPORTANT: Delete this file after running!
 */

echo "<h1>Permission Fix for Shared Hosting</h1>";
echo "<pre>";

$basePath = dirname(__DIR__);

echo "🔧 Fixing permissions...\n\n";

// Directories that need write permissions
$writableDirs = [
    'storage',
    'storage/app',
    'storage/app/public',
    'storage/framework',
    'storage/framework/cache',
    'storage/framework/cache/data',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
    'bootstrap/cache',
];

$fixed = 0;
$errors = 0;

foreach ($writableDirs as $dir) {
    $fullPath = $basePath . '/' . $dir;
    
    if (!is_dir($fullPath)) {
        echo "⚠️  Creating: $dir\n";
        if (mkdir($fullPath, 0755, true)) {
            echo "   ✓ Created\n";
        } else {
            echo "   ✗ Failed to create\n";
            $errors++;
        }
    }
    
    echo "📁 $dir\n";
    
    // Try to set permissions
    if (@chmod($fullPath, 0755)) {
        echo "   ✓ Set to 755\n";
        $fixed++;
    } else {
        echo "   ⚠️  Could not change (may already be correct)\n";
    }
}

echo "\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📊 SUMMARY\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
echo "Directories checked: " . count($writableDirs) . "\n";
echo "Permissions set: $fixed\n";
echo "Errors: $errors\n\n";

// Check current permissions
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📋 CURRENT PERMISSIONS\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

foreach ($writableDirs as $dir) {
    $fullPath = $basePath . '/' . $dir;
    if (is_dir($fullPath)) {
        $perms = substr(sprintf('%o', fileperms($fullPath)), -4);
        $writable = is_writable($fullPath) ? '✓ Writable' : '✗ Not writable';
        echo "$dir: $perms ($writable)\n";
    }
}

echo "\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🔍 DIAGNOSTICS\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

// Check PHP version
echo "PHP Version: " . phpversion() . "\n";

// Check if mod_rewrite is enabled
if (function_exists('apache_get_modules')) {
    $modules = apache_get_modules();
    $modRewrite = in_array('mod_rewrite', $modules) ? '✓ Enabled' : '✗ Disabled';
    echo "mod_rewrite: $modRewrite\n";
} else {
    echo "mod_rewrite: Cannot detect (likely enabled)\n";
}

// Check .htaccess
$htaccessPath = __DIR__ . '/.htaccess';
if (file_exists($htaccessPath)) {
    echo ".htaccess: ✓ Exists\n";
    $perms = substr(sprintf('%o', fileperms($htaccessPath)), -4);
    echo ".htaccess permissions: $perms\n";
} else {
    echo ".htaccess: ✗ Missing!\n";
}

// Check index.php
$indexPath = __DIR__ . '/index.php';
if (file_exists($indexPath)) {
    echo "index.php: ✓ Exists\n";
    $perms = substr(sprintf('%o', fileperms($indexPath)), -4);
    echo "index.php permissions: $perms\n";
} else {
    echo "index.php: ✗ Missing!\n";
}

echo "\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🎯 NEXT STEPS\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

if ($errors > 0) {
    echo "⚠️  Some directories could not be created.\n";
    echo "   Please create them manually via File Manager:\n\n";
    foreach ($writableDirs as $dir) {
        $fullPath = $basePath . '/' . $dir;
        if (!is_dir($fullPath)) {
            echo "   - $dir\n";
        }
    }
    echo "\n";
}

echo "1. If you still get 403 errors, check these in cPanel:\n";
echo "   - File Manager > Select all folders > Permissions > 755\n";
echo "   - File Manager > Select all files > Permissions > 644\n";
echo "   - storage/ and bootstrap/cache/ folders > 755\n\n";

echo "2. Make sure your domain points to the 'public' folder:\n";
echo "   - In cPanel > Domains > Document Root should be: /public_html/public\n";
echo "   - OR your files should be in public_html/ (not a subfolder)\n\n";

echo "3. Check .htaccess file exists in public/ folder\n\n";

echo "4. Try clearing browser cache and cookies\n\n";

echo "5. Check error logs in cPanel for more details\n\n";

echo "<strong style='color: red;'>⚠️  DELETE this file (fix-permissions.php) after running!</strong>\n";
echo "</pre>";
?>
