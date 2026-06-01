<?php
/**
 * Fix Storage Permissions for Shared Hosting
 * This fixes 403 errors on gallery images
 * DELETE THIS FILE AFTER USE!
 */

// Security check - uncomment and add your IP
// $allowed_ips = ['YOUR.IP.ADDRESS.HERE'];
// if (!in_array($_SERVER['REMOTE_ADDR'], $allowed_ips)) {
//     die('Access denied');
// }

set_time_limit(300); // 5 minutes

$rootPath = dirname(__DIR__);
$storagePath = $rootPath . '/storage/app/public';
$galleryPath = $storagePath . '/gallery-images';

?>
<!DOCTYPE html>
<html>
<head>
    <title>Fix Storage Permissions</title>
    <style>
        body { font-family: sans-serif; max-width: 900px; margin: 50px auto; padding: 20px; }
        .success { color: green; background: #e8f5e9; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .error { color: red; background: #ffebee; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .info { color: #1976d2; background: #e3f2fd; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .warning { color: #f57c00; background: #fff3e0; padding: 15px; border-radius: 5px; margin: 20px 0; font-weight: bold; }
        pre { background: #f5f5f5; padding: 10px; border-radius: 5px; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f5f5f5; font-weight: bold; }
        .fixed { color: green; }
        .failed { color: red; }
    </style>
</head>
<body>
    <h1>🔧 Fix Storage Permissions</h1>
    
    <?php
    echo "<div class='info'>";
    echo "<strong>Target Paths:</strong><br>";
    echo "Storage: <code>$storagePath</code><br>";
    echo "Gallery: <code>$galleryPath</code><br>";
    echo "</div>";
    
    if (!is_dir($galleryPath)) {
        echo "<div class='error'>❌ Gallery directory does not exist: $galleryPath</div>";
        exit;
    }
    
    echo "<h2>📁 Fixing Directory Permissions</h2>";
    
    $directories = [
        $storagePath,
        $galleryPath,
    ];
    
    echo "<table>";
    echo "<tr><th>Directory</th><th>Old Perms</th><th>New Perms</th><th>Status</th></tr>";
    
    foreach ($directories as $dir) {
        if (is_dir($dir)) {
            $oldPerms = substr(sprintf('%o', fileperms($dir)), -4);
            $result = @chmod($dir, 0755);
            $newPerms = substr(sprintf('%o', fileperms($dir)), -4);
            
            echo "<tr>";
            echo "<td><code>" . basename($dir) . "</code></td>";
            echo "<td><code>$oldPerms</code></td>";
            echo "<td><code>$newPerms</code></td>";
            echo "<td class='" . ($result ? 'fixed' : 'failed') . "'>" . ($result ? '✅ Fixed' : '❌ Failed') . "</td>";
            echo "</tr>";
        }
    }
    echo "</table>";
    
    echo "<h2>🖼️ Fixing Image File Permissions</h2>";
    
    $images = glob($galleryPath . '/*');
    $imageFiles = array_filter($images, 'is_file');
    
    if (count($imageFiles) === 0) {
        echo "<div class='info'>No files found in gallery directory</div>";
    } else {
        echo "<div class='info'>Found " . count($imageFiles) . " files to fix</div>";
        
        echo "<table>";
        echo "<tr><th>Filename</th><th>Old Perms</th><th>New Perms</th><th>Status</th></tr>";
        
        $fixed = 0;
        $failed = 0;
        
        foreach ($imageFiles as $file) {
            $filename = basename($file);
            $oldPerms = substr(sprintf('%o', fileperms($file)), -4);
            $result = @chmod($file, 0644);
            $newPerms = substr(sprintf('%o', fileperms($file)), -4);
            
            if ($result) {
                $fixed++;
            } else {
                $failed++;
            }
            
            // Only show first 20 files in table
            if (($fixed + $failed) <= 20) {
                echo "<tr>";
                echo "<td><code>$filename</code></td>";
                echo "<td><code>$oldPerms</code></td>";
                echo "<td><code>$newPerms</code></td>";
                echo "<td class='" . ($result ? 'fixed' : 'failed') . "'>" . ($result ? '✅' : '❌') . "</td>";
                echo "</tr>";
            }
        }
        
        if (count($imageFiles) > 20) {
            echo "<tr><td colspan='4'><em>... and " . (count($imageFiles) - 20) . " more files</em></td></tr>";
        }
        
        echo "</table>";
        
        echo "<div class='success'>";
        echo "<strong>Summary:</strong><br>";
        echo "✅ Fixed: $fixed files<br>";
        if ($failed > 0) {
            echo "❌ Failed: $failed files<br>";
        }
        echo "</div>";
    }
    
    echo "<h2>🧪 Testing Image Access</h2>";
    
    if (count($imageFiles) > 0) {
        $testFile = $imageFiles[0];
        $testFilename = basename($testFile);
        $testUrl = "/storage/gallery-images/$testFilename";
        
        echo "<div class='info'>";
        echo "Test image: <code>$testFilename</code><br>";
        echo "URL: <a href='$testUrl' target='_blank'>$testUrl</a><br>";
        echo "</div>";
        
        echo "<p>If permissions are fixed correctly, you should see the image below:</p>";
        echo "<img src='$testUrl' style='max-width: 300px; border: 2px solid #ddd; margin: 10px;' alt='Test Image' onerror=\"this.style.border='2px solid red'; this.alt='❌ Still failing - see solutions below';\">";
    }
    
    echo "<h2>💡 If Images Still Show 403 Error</h2>";
    
    echo "<div class='warning'>";
    echo "<strong>Additional Solutions:</strong><br><br>";
    
    echo "<strong>1. Check .htaccess in storage directory</strong><br>";
    $htaccessPath = $storagePath . '/.htaccess';
    if (file_exists($htaccessPath)) {
        echo "⚠️ Found .htaccess in storage directory - this might be blocking access<br>";
        echo "Location: <code>$htaccessPath</code><br>";
        echo "Consider deleting or checking its contents<br><br>";
    } else {
        echo "✅ No .htaccess found in storage (good)<br><br>";
    }
    
    echo "<strong>2. Check gallery-images .htaccess</strong><br>";
    $galleryHtaccess = $galleryPath . '/.htaccess';
    if (file_exists($galleryHtaccess)) {
        echo "⚠️ Found .htaccess in gallery-images - this might be blocking access<br>";
        echo "Location: <code>$galleryHtaccess</code><br>";
        echo "Consider deleting it<br><br>";
    } else {
        echo "✅ No .htaccess found in gallery-images (good)<br><br>";
    }
    
    echo "<strong>3. Server Configuration</strong><br>";
    echo "Some servers have additional restrictions. Contact your hosting support and ask them to:<br>";
    echo "<ul>";
    echo "<li>Allow access to <code>storage/app/public/gallery-images</code> directory</li>";
    echo "<li>Check if there are any Apache/Nginx rules blocking access</li>";
    echo "<li>Verify that symlinks are allowed and working</li>";
    echo "</ul>";
    
    echo "<strong>4. Alternative: Create .htaccess to allow access</strong><br>";
    echo "If the above doesn't work, you can create a .htaccess file in gallery-images:<br>";
    echo "<pre>Options +FollowSymLinks\nRequire all granted</pre>";
    echo "</div>";
    
    // Offer to create .htaccess
    if (isset($_GET['create_htaccess'])) {
        $htaccessContent = "Options +FollowSymLinks\nRequire all granted\n";
        $htaccessFile = $galleryPath . '/.htaccess';
        
        if (file_put_contents($htaccessFile, $htaccessContent)) {
            echo "<div class='success'>✅ Created .htaccess in gallery-images directory. Try accessing images now.</div>";
        } else {
            echo "<div class='error'>❌ Failed to create .htaccess file</div>";
        }
    } else {
        echo "<div class='info'>";
        echo "<a href='?create_htaccess=1' style='display: inline-block; padding: 10px 20px; background: #4CAF50; color: white; text-decoration: none; border-radius: 5px;'>Create .htaccess to Allow Access</a>";
        echo "</div>";
    }
    
    echo "<div class='warning' style='margin-top: 30px;'>";
    echo "🔒 <strong>SECURITY:</strong> Delete this file (fix-permissions.php) after use!";
    echo "</div>";
    ?>
</body>
</html>
