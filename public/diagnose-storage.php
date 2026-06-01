<?php
/**
 * Complete Storage Diagnostic Tool
 * DELETE AFTER USE!
 */

header('Content-Type: text/html; charset=utf-8');

?>
<!DOCTYPE html>
<html>
<head>
    <title>Storage Diagnostics</title>
    <style>
        body { font-family: sans-serif; max-width: 1000px; margin: 20px auto; padding: 20px; background: #f5f5f5; }
        .container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 3px solid #4CAF50; padding-bottom: 10px; }
        h2 { color: #555; margin-top: 30px; }
        .success { color: green; background: #e8f5e9; padding: 10px; border-radius: 5px; margin: 10px 0; }
        .error { color: red; background: #ffebee; padding: 10px; border-radius: 5px; margin: 10px 0; }
        .warning { color: #f57c00; background: #fff3e0; padding: 10px; border-radius: 5px; margin: 10px 0; }
        .info { color: #1976d2; background: #e3f2fd; padding: 10px; border-radius: 5px; margin: 10px 0; }
        pre { background: #f5f5f5; padding: 15px; border-radius: 5px; overflow-x: auto; border-left: 4px solid #4CAF50; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f5f5f5; font-weight: bold; }
        .delete-warning { color: #f44336; background: #ffebee; padding: 20px; border-radius: 5px; margin-top: 30px; font-weight: bold; border: 2px solid #f44336; }
        .image-test { max-width: 200px; border: 2px solid #ddd; margin: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Storage Diagnostics</h1>
        
        <?php
        $publicPath = __DIR__;
        $rootPath = dirname(__DIR__);
        $storagePath = $rootPath . '/storage/app/public';
        $linkPath = $publicPath . '/storage';
        $galleryPath = $storagePath . '/gallery-images';
        
        echo "<h2>📁 Path Information</h2>";
        echo "<table>";
        echo "<tr><th>Path</th><th>Value</th><th>Exists</th></tr>";
        echo "<tr><td>Root</td><td><code>$rootPath</code></td><td>" . (is_dir($rootPath) ? '✅' : '❌') . "</td></tr>";
        echo "<tr><td>Public</td><td><code>$publicPath</code></td><td>" . (is_dir($publicPath) ? '✅' : '❌') . "</td></tr>";
        echo "<tr><td>Storage</td><td><code>$storagePath</code></td><td>" . (is_dir($storagePath) ? '✅' : '❌') . "</td></tr>";
        echo "<tr><td>Symlink</td><td><code>$linkPath</code></td><td>" . (file_exists($linkPath) ? '✅' : '❌') . "</td></tr>";
        echo "<tr><td>Gallery Images</td><td><code>$galleryPath</code></td><td>" . (is_dir($galleryPath) ? '✅' : '❌') . "</td></tr>";
        echo "</table>";
        
        echo "<h2>🔗 Symlink Status</h2>";
        if (file_exists($linkPath)) {
            if (is_link($linkPath)) {
                $target = readlink($linkPath);
                $realTarget = realpath($linkPath);
                echo "<div class='success'>✅ Symlink exists</div>";
                echo "<table>";
                echo "<tr><th>Property</th><th>Value</th></tr>";
                echo "<tr><td>Target</td><td><code>$target</code></td></tr>";
                echo "<tr><td>Real Path</td><td><code>$realTarget</code></td></tr>";
                echo "<tr><td>Expected</td><td><code>$storagePath</code></td></tr>";
                echo "<tr><td>Correct</td><td>" . ($realTarget === realpath($storagePath) ? '✅ Yes' : '❌ No') . "</td></tr>";
                echo "</table>";
            } else {
                echo "<div class='error'>❌ 'storage' exists but is NOT a symlink (it's a " . filetype($linkPath) . ")</div>";
                echo "<div class='warning'>⚠️ You need to delete this and create a proper symlink</div>";
            }
        } else {
            echo "<div class='error'>❌ Symlink does not exist at: <code>$linkPath</code></div>";
            echo "<div class='warning'>⚠️ Run fix-storage-link.php or 'php artisan storage:link'</div>";
        }
        
        echo "<h2>📸 Gallery Images</h2>";
        if (is_dir($galleryPath)) {
            $images = glob($galleryPath . '/*');
            $imageFiles = array_filter($images, function($file) {
                return is_file($file) && preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $file);
            });
            
            echo "<div class='info'>Found " . count($imageFiles) . " image files</div>";
            
            if (count($imageFiles) > 0) {
                echo "<table>";
                echo "<tr><th>Filename</th><th>Size</th><th>Permissions</th><th>Web Path</th><th>Accessible</th></tr>";
                
                foreach (array_slice($imageFiles, 0, 10) as $image) {
                    $filename = basename($image);
                    $size = filesize($image);
                    $perms = substr(sprintf('%o', fileperms($image)), -4);
                    $webPath = "/storage/gallery-images/$filename";
                    $fullUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$webPath";
                    
                    // Check if accessible
                    $accessible = file_exists($linkPath . '/gallery-images/' . $filename);
                    
                    echo "<tr>";
                    echo "<td><code>$filename</code></td>";
                    echo "<td>" . number_format($size / 1024, 2) . " KB</td>";
                    echo "<td><code>$perms</code></td>";
                    echo "<td><a href='$webPath' target='_blank'>$webPath</a></td>";
                    echo "<td>" . ($accessible ? '✅' : '❌') . "</td>";
                    echo "</tr>";
                }
                
                if (count($imageFiles) > 10) {
                    echo "<tr><td colspan='5'><em>... and " . (count($imageFiles) - 10) . " more</em></td></tr>";
                }
                echo "</table>";
                
                // Show first image as test
                if (file_exists($linkPath)) {
                    $testImage = basename($imageFiles[0]);
                    echo "<h3>🖼️ Image Display Test</h3>";
                    echo "<p>If the symlink is working, you should see the image below:</p>";
                    echo "<img src='/storage/gallery-images/$testImage' class='image-test' alt='Test Image' onerror=\"this.style.border='2px solid red'; this.alt='❌ Failed to load';\">";
                    echo "<p><small>Image path: <code>/storage/gallery-images/$testImage</code></small></p>";
                }
            } else {
                echo "<div class='warning'>⚠️ No image files found in gallery-images directory</div>";
            }
        } else {
            echo "<div class='error'>❌ Gallery images directory does not exist</div>";
        }
        
        echo "<h2>🔧 Permissions</h2>";
        echo "<table>";
        echo "<tr><th>Path</th><th>Permissions</th><th>Writable</th></tr>";
        
        $checkPaths = [
            'storage/app' => $rootPath . '/storage/app',
            'storage/app/public' => $storagePath,
            'gallery-images' => $galleryPath,
            'public/storage' => $linkPath,
        ];
        
        foreach ($checkPaths as $label => $path) {
            if (file_exists($path)) {
                $perms = substr(sprintf('%o', fileperms($path)), -4);
                $writable = is_writable($path);
                echo "<tr>";
                echo "<td>$label</td>";
                echo "<td><code>$perms</code></td>";
                echo "<td>" . ($writable ? '✅ Yes' : '❌ No') . "</td>";
                echo "</tr>";
            }
        }
        echo "</table>";
        
        echo "<h2>⚙️ PHP Configuration</h2>";
        echo "<table>";
        echo "<tr><th>Setting</th><th>Value</th></tr>";
        echo "<tr><td>symlink() available</td><td>" . (function_exists('symlink') ? '✅ Yes' : '❌ No') . "</td></tr>";
        echo "<tr><td>open_basedir</td><td><code>" . (ini_get('open_basedir') ?: 'Not set') . "</code></td></tr>";
        echo "<tr><td>safe_mode</td><td><code>" . (ini_get('safe_mode') ? 'On' : 'Off') . "</code></td></tr>";
        echo "</table>";
        
        echo "<h2>💡 Recommendations</h2>";
        
        if (!file_exists($linkPath)) {
            echo "<div class='error'>";
            echo "<strong>❌ Symlink Missing</strong><br>";
            echo "Run one of these solutions:<br>";
            echo "<ol>";
            echo "<li>Visit <a href='fix-storage-link.php'>fix-storage-link.php</a> to create it automatically</li>";
            echo "<li>SSH into server and run: <code>php artisan storage:link</code></li>";
            echo "<li>Contact hosting support to create the symlink</li>";
            echo "</ol>";
            echo "</div>";
        } elseif (!is_link($linkPath)) {
            echo "<div class='error'>";
            echo "<strong>❌ Wrong Type</strong><br>";
            echo "The 'storage' path exists but is not a symlink. Delete it and recreate as a symlink.";
            echo "</div>";
        } else {
            $realTarget = realpath($linkPath);
            if ($realTarget !== realpath($storagePath)) {
                echo "<div class='error'>";
                echo "<strong>❌ Wrong Target</strong><br>";
                echo "Symlink points to wrong location. Delete and recreate it.";
                echo "</div>";
            } else {
                echo "<div class='success'>";
                echo "<strong>✅ Symlink Configured Correctly</strong><br>";
                echo "If images still don't show, check:<br>";
                echo "<ul>";
                echo "<li>File permissions (should be 644 for files, 755 for directories)</li>";
                echo "<li>Browser cache (hard refresh with Ctrl+F5)</li>";
                echo "<li>Laravel cache: run <code>php artisan config:clear</code></li>";
                echo "</ul>";
                echo "</div>";
            }
        }
        ?>
        
        <div class="delete-warning">
            🔒 <strong>SECURITY WARNING:</strong> Delete this file (diagnose-storage.php) after checking!
        </div>
    </div>
</body>
</html>
