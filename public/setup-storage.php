<?php
/**
 * Storage Structure Setup Script
 * 
 * This script creates all necessary storage directories
 * Run this BEFORE running migrations
 * 
 * IMPORTANT: Delete this file after running!
 */

echo "<h1>Storage Structure Setup</h1>";
echo "<pre>";

$basePath = dirname(__DIR__);
$storagePath = $basePath . '/storage';

// Define all required directories
$directories = [
    'app/public',
    'framework/cache/data',
    'framework/sessions',
    'framework/testing',
    'framework/views',
    'logs',
];

$created = [];
$existed = [];
$errors = [];

foreach ($directories as $dir) {
    $fullPath = $storagePath . '/' . $dir;
    
    if (file_exists($fullPath)) {
        $existed[] = $dir;
    } else {
        if (mkdir($fullPath, 0755, true)) {
            $created[] = $dir;
            
            // Create .gitignore in each directory
            $gitignorePath = $fullPath . '/.gitignore';
            if (!file_exists($gitignorePath)) {
                file_put_contents($gitignorePath, "*\n!.gitignore\n");
            }
        } else {
            $errors[] = $dir;
        }
    }
}

echo "✅ STORAGE STRUCTURE SETUP COMPLETE\n\n";

if (!empty($created)) {
    echo "📁 Created directories:\n";
    foreach ($created as $dir) {
        echo "   ✓ storage/$dir\n";
    }
    echo "\n";
}

if (!empty($existed)) {
    echo "📂 Already existed:\n";
    foreach ($existed as $dir) {
        echo "   ✓ storage/$dir\n";
    }
    echo "\n";
}

if (!empty($errors)) {
    echo "❌ ERRORS - Could not create:\n";
    foreach ($errors as $dir) {
        echo "   ✗ storage/$dir\n";
    }
    echo "\n";
    echo "Please create these directories manually via File Manager\n";
    echo "and set permissions to 755\n\n";
}

// Check permissions
echo "🔒 Checking permissions...\n";
$storagePerms = substr(sprintf('%o', fileperms($storagePath)), -4);
echo "   storage/ : $storagePerms " . ($storagePerms >= '0755' ? '✓' : '✗ (should be 755)') . "\n";

$frameworkPath = $storagePath . '/framework';
if (file_exists($frameworkPath)) {
    $frameworkPerms = substr(sprintf('%o', fileperms($frameworkPath)), -4);
    echo "   storage/framework/ : $frameworkPerms " . ($frameworkPerms >= '0755' ? '✓' : '✗ (should be 755)') . "\n";
}

$bootstrapCachePath = $basePath . '/bootstrap/cache';
if (file_exists($bootstrapCachePath)) {
    $bootstrapPerms = substr(sprintf('%o', fileperms($bootstrapCachePath)), -4);
    echo "   bootstrap/cache/ : $bootstrapPerms " . ($bootstrapPerms >= '0755' ? '✓' : '✗ (should be 755)') . "\n";
}

echo "\n";
echo "✅ Storage structure is ready!\n\n";
echo "NEXT STEPS:\n";
echo "1. Run migrate.php to create database tables\n";
echo "2. Run storage-link.php to create storage symlink\n";
echo "3. Run create-admin.php to create admin user\n";
echo "4. DELETE all these PHP files for security!\n\n";

echo "<strong style='color: red;'>⚠️  IMPORTANT: Delete this file (setup-storage.php) immediately after running!</strong>\n";
echo "</pre>";
?>
