<?php
/**
 * Fix 403 Forbidden on /admin
 * 
 * Clears cached class maps so Laravel picks up the updated User model
 * with FilamentUser interface.
 * 
 * IMPORTANT: Delete this file after running!
 */

echo "<h1>Fix 403 Admin Access</h1>";
echo "<pre>";

$basePath = dirname(__DIR__);

echo "🔧 Fixing 403 Forbidden on /admin...\n\n";
echo "Root cause: User model was missing FilamentUser interface.\n";
echo "The model has been updated. Now clearing cached files so the\n";
echo "server picks up the change.\n\n";

// 1. Clear bootstrap/cache (services.php, packages.php, config.php, routes)
echo "1️⃣  Clearing bootstrap cache...\n";
$bootstrapCache = $basePath . '/bootstrap/cache';
$cacheFiles = ['services.php', 'packages.php', 'config.php', 'routes-v7.php'];
foreach ($cacheFiles as $file) {
    $path = $bootstrapCache . '/' . $file;
    if (file_exists($path)) {
        if (unlink($path)) {
            echo "   ✓ Deleted bootstrap/cache/$file\n";
        } else {
            echo "   ✗ Could not delete bootstrap/cache/$file (check permissions)\n";
        }
    } else {
        echo "   - bootstrap/cache/$file not found (already clear)\n";
    }
}
echo "\n";

// 2. Clear compiled views
echo "2️⃣  Clearing compiled views...\n";
$viewsCache = $basePath . '/storage/framework/views';
if (is_dir($viewsCache)) {
    $files = glob($viewsCache . '/*.php');
    $count = 0;
    foreach ($files as $file) {
        if (basename($file) !== '.gitignore' && unlink($file)) {
            $count++;
        }
    }
    echo "   ✓ Cleared $count compiled view files\n\n";
} else {
    echo "   - Views cache directory not found\n\n";
}

// 3. Clear framework cache
echo "3️⃣  Clearing framework cache...\n";
$frameworkCache = $basePath . '/storage/framework/cache/data';
if (is_dir($frameworkCache)) {
    $files = glob($frameworkCache . '/*');
    $count = 0;
    foreach ($files as $file) {
        if (is_file($file) && basename($file) !== '.gitignore' && unlink($file)) {
            $count++;
        }
    }
    echo "   ✓ Cleared $count cache files\n\n";
} else {
    echo "   - Framework cache directory not found\n\n";
}

// 4. Clear sessions (force fresh login)
echo "4️⃣  Clearing sessions (forces fresh login)...\n";
$sessionsPath = $basePath . '/storage/framework/sessions';
if (is_dir($sessionsPath)) {
    $files = glob($sessionsPath . '/*');
    $count = 0;
    foreach ($files as $file) {
        if (is_file($file) && basename($file) !== '.gitignore' && unlink($file)) {
            $count++;
        }
    }
    echo "   ✓ Cleared $count session files\n\n";
} else {
    echo "   - Sessions directory not found\n\n";
}

// 5. Verify User model has FilamentUser
echo "5️⃣  Verifying User model...\n";
$userModel = $basePath . '/app/Models/User.php';
if (file_exists($userModel)) {
    $content = file_get_contents($userModel);
    if (strpos($content, 'FilamentUser') !== false && strpos($content, 'canAccessPanel') !== false) {
        echo "   ✅ User model has FilamentUser interface\n";
        echo "   ✅ canAccessPanel() method present\n\n";
    } else {
        echo "   ❌ User model is MISSING FilamentUser interface!\n";
        echo "   Make sure you uploaded the latest version of app/Models/User.php\n\n";
    }
} else {
    echo "   ❌ User model file not found!\n\n";
}

// 6. Check admin user exists in DB
echo "6️⃣  Checking admin user in database...\n";
$envFile = file_get_contents($basePath . '/.env');
$envLines = explode("\n", $envFile);
foreach ($envLines as $line) {
    $line = trim($line);
    if (empty($line) || strpos($line, '#') === 0) continue;
    if (strpos($line, '=') !== false) {
        list($key, $value) = explode('=', $line, 2);
        putenv(trim($key) . '=' . trim($value, '"\''));
    }
}

try {
    $pdo = new PDO(
        "mysql:host=" . (getenv('DB_HOST') ?: 'localhost') . ";dbname=" . getenv('DB_DATABASE') . ";charset=utf8mb4",
        getenv('DB_USERNAME'),
        getenv('DB_PASSWORD'),
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $stmt = $pdo->query("SELECT id, name, email, email_verified_at FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($users) > 0) {
        echo "   ✅ Found " . count($users) . " user(s):\n\n";
        foreach ($users as $user) {
            $verified = $user['email_verified_at'] ? '✓ Verified' : '✗ Not verified';
            echo "   • {$user['name']} ({$user['email']}) — $verified\n";
        }
        echo "\n";
    } else {
        echo "   ⚠️  No users found! Run create-admin-sql.php first.\n\n";
    }
} catch (PDOException $e) {
    echo "   ❌ DB error: " . $e->getMessage() . "\n\n";
}

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "✅ DONE! Cache cleared.\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

echo "NEXT STEPS:\n";
echo "1. Delete this file immediately\n";
echo "2. Clear your browser cache / use incognito\n";
echo "3. Visit /admin/login\n";
echo "4. Login with your admin credentials\n\n";

$appUrl = getenv('APP_URL') ?: 'https://yourdomain.com';
echo "Admin login URL: $appUrl/admin/login\n\n";

echo "<strong style='color: red;'>⚠️  DELETE this file (fix-403.php) immediately after running!</strong>\n";
echo "</pre>";
?>
