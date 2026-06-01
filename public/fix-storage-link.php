<?php
/**
 * Storage Link Fixer for Shared Hosting
 * This script creates the storage symlink when you can't run artisan commands
 * DELETE THIS FILE AFTER USE!
 */

// Security check - uncomment and add your IP
// $allowed_ips = ['YOUR.IP.ADDRESS.HERE'];
// if (!in_array($_SERVER['REMOTE_ADDR'], $allowed_ips)) {
//     die('Access denied');
// }

$publicPath = __DIR__;
$storagePath = dirname(__DIR__) . '/storage/app/public';
$linkPath = $publicPath . '/storage';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Storage Link Fixer</title>
    <style>
        body { font-family: sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        .success { color: green; background: #e8f5e9; padding: 15px; border-radius: 5px; }
        .error { color: red; background: #ffebee; padding: 15px; border-radius: 5px; }
        .info { color: #1976d2; background: #e3f2fd; padding: 15px; border-radius: 5px; }
        .warning { color: #f57c00; background: #fff3e0; padding: 15px; border-radius: 5px; margin-top: 20px; font-weight: bold; }
        pre { background: #f5f5f5; padding: 10px; border-radius: 5px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>🔗 Storage Link Fixer</h1>";

echo "<div class='info'>";
echo "<strong>Paths:</strong><br>";
echo "Public path: <code>$publicPath</code><br>";
echo "Storage path: <code>$storagePath</code><br>";
echo "Link path: <code>$linkPath</code><br>";
echo "</div><br>";

// Check if storage directory exists
if (!is_dir($storagePath)) {
    echo "<div class='error'>❌ Storage directory does not exist: $storagePath</div>";
    echo "<p>Please ensure your storage/app/public directory exists.</p>";
    exit;
}

// Check if link already exists
if (file_exists($linkPath)) {
    if (is_link($linkPath)) {
        $target = readlink($linkPath);
        echo "<div class='info'>ℹ️ Symlink already exists<br>";
        echo "Current target: <code>$target</code></div>";
        
        // Check if it's pointing to the right place
        if (realpath($target) === realpath($storagePath)) {
            echo "<div class='success'>✅ Symlink is correctly configured!</div>";
            
            // Test if we can access files
            $testFiles = glob($storagePath . '/gallery-images/*');
            if ($testFiles) {
                echo "<div class='success'>✅ Found " . count($testFiles) . " files in gallery-images</div>";
                echo "<p>Try accessing an image: <a href='/storage/gallery-images/" . basename($testFiles[0]) . "' target='_blank'>/storage/gallery-images/" . basename($testFiles[0]) . "</a></p>";
            }
        } else {
            echo "<div class='error'>❌ Symlink points to wrong location!</div>";
            echo "<p>Attempting to remove and recreate...</p>";
            
            if (unlink($linkPath)) {
                echo "<div class='success'>✅ Old symlink removed</div>";
            } else {
                echo "<div class='error'>❌ Failed to remove old symlink. Please delete it manually.</div>";
                exit;
            }
        }
    } else {
        echo "<div class='error'>❌ A file or directory named 'storage' exists but it's not a symlink!</div>";
        echo "<p>Please manually remove: $linkPath</p>";
        exit;
    }
}

// Create the symlink if it doesn't exist
if (!file_exists($linkPath)) {
    echo "<p>Creating symlink...</p>";
    
    if (symlink($storagePath, $linkPath)) {
        echo "<div class='success'>✅ Symlink created successfully!</div>";
        
        // Verify it works
        if (is_link($linkPath) && readlink($linkPath) === $storagePath) {
            echo "<div class='success'>✅ Symlink verified and working!</div>";
            
            // List some files
            $testFiles = glob($storagePath . '/gallery-images/*');
            if ($testFiles) {
                echo "<div class='success'>✅ Found " . count($testFiles) . " files in gallery-images</div>";
                echo "<p>Test image access: <a href='/storage/gallery-images/" . basename($testFiles[0]) . "' target='_blank'>/storage/gallery-images/" . basename($testFiles[0]) . "</a></p>";
            }
        } else {
            echo "<div class='error'>❌ Symlink created but verification failed</div>";
        }
    } else {
        echo "<div class='error'>❌ Failed to create symlink</div>";
        echo "<p><strong>Possible reasons:</strong></p>";
        echo "<ul>";
        echo "<li>Your hosting provider doesn't allow symlinks</li>";
        echo "<li>Insufficient permissions</li>";
        echo "<li>PHP safe_mode or open_basedir restrictions</li>";
        echo "</ul>";
        
        echo "<p><strong>Alternative solution:</strong></p>";
        echo "<p>Contact your hosting support and ask them to run:</p>";
        echo "<pre>php artisan storage:link</pre>";
        echo "<p>Or manually create a symlink from your hosting control panel.</p>";
        
        echo "<p><strong>Workaround (if symlinks not supported):</strong></p>";
        echo "<p>You may need to copy files instead of symlinking. Contact support for assistance.</p>";
    }
}

echo "<div class='warning'>🔒 SECURITY: Delete this file (fix-storage-link.php) after use!</div>";

echo "</body></html>";
