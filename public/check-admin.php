<?php
/**
 * Admin Panel Diagnostic Script
 * 
 * Checks if Filament admin is properly configured
 * Run this if you get 403 Forbidden on /admin
 * 
 * IMPORTANT: Delete this file after running!
 */

echo "<h1>Filament Admin Diagnostic</h1>";
echo "<pre>";

$basePath = dirname(__DIR__);

echo "🔍 Checking Filament Admin Setup...\n\n";

// Load .env
if (!file_exists($basePath . '/.env')) {
    echo "❌ ERROR: .env file not found!\n";
    exit;
}

$envFile = file_get_contents($basePath . '/.env');
$envLines = explode("\n", $envFile);
foreach ($envLines as $line) {
    $line = trim($line);
    if (empty($line) || strpos($line, '#') === 0) continue;
    if (strpos($line, '=') !== false) {
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value, '"\'');
        putenv("$key=$value");
    }
}

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📋 ENVIRONMENT CHECK\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$appUrl = getenv('APP_URL') ?: 'Not set';
$appEnv = getenv('APP_ENV') ?: 'Not set';
$appDebug = getenv('APP_DEBUG') ?: 'false';

echo "APP_URL: $appUrl\n";
echo "APP_ENV: $appEnv\n";
echo "APP_DEBUG: $appDebug\n";
echo "PHP Version: " . phpversion() . "\n\n";

// Database connection
$host = getenv('DB_HOST') ?: 'localhost';
$database = getenv('DB_DATABASE');
$username = getenv('DB_USERNAME');
$dbPassword = getenv('DB_PASSWORD');

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🔌 DATABASE CHECK\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$database;charset=utf8mb4",
        $username,
        $dbPassword,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    echo "✅ Database connected\n";
    echo "   Database: $database\n\n";
    
    // Check users table
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "✅ Users table exists\n";
    echo "   Total users: " . $result['count'] . "\n\n";
    
    // Check if admin user exists
    $stmt = $pdo->query("SELECT id, name, email, email_verified_at FROM users LIMIT 5");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($users) > 0) {
        echo "👥 Users in database:\n\n";
        foreach ($users as $user) {
            $verified = $user['email_verified_at'] ? '✓ Verified' : '✗ Not verified';
            echo "   ID: {$user['id']}\n";
            echo "   Name: {$user['name']}\n";
            echo "   Email: {$user['email']}\n";
            echo "   Status: $verified\n\n";
        }
    } else {
        echo "⚠️  No users found in database!\n";
        echo "   Run create-admin-sql.php to create an admin user.\n\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n\n";
}

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📁 FILE STRUCTURE CHECK\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$requiredFiles = [
    'vendor/autoload.php' => 'Composer autoload',
    'bootstrap/app.php' => 'Laravel bootstrap',
    'app/Providers/Filament/AdminPanelProvider.php' => 'Filament admin provider',
    'config/app.php' => 'App config',
    'config/filament.php' => 'Filament config (optional)',
];

foreach ($requiredFiles as $file => $description) {
    $fullPath = $basePath . '/' . $file;
    if (file_exists($fullPath)) {
        echo "✅ $description\n";
        echo "   $file\n\n";
    } else {
        echo "⚠️  $description\n";
        echo "   Missing: $file\n\n";
    }
}

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🔐 PERMISSIONS CHECK\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$checkDirs = [
    'storage' => 'Storage directory',
    'storage/framework' => 'Framework cache',
    'storage/logs' => 'Log files',
    'bootstrap/cache' => 'Bootstrap cache',
];

foreach ($checkDirs as $dir => $description) {
    $fullPath = $basePath . '/' . $dir;
    if (is_dir($fullPath)) {
        $writable = is_writable($fullPath) ? '✓ Writable' : '✗ Not writable';
        $perms = substr(sprintf('%o', fileperms($fullPath)), -4);
        echo "$description: $writable (Permissions: $perms)\n";
    } else {
        echo "$description: ✗ Missing\n";
    }
}

echo "\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🌐 URL ROUTING CHECK\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

echo "Current URL: " . (isset($_SERVER['HTTP_HOST']) ? 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] : 'Unknown') . "\n";
echo "Document Root: " . (isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : 'Unknown') . "\n";
echo "Script Filename: " . (isset($_SERVER['SCRIPT_FILENAME']) ? $_SERVER['SCRIPT_FILENAME'] : 'Unknown') . "\n\n";

echo "Expected admin URL: $appUrl/admin\n";
echo "Expected login URL: $appUrl/admin/login\n\n";

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🔧 COMMON FIXES FOR 403 ERRORS\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

echo "1. FILE PERMISSIONS:\n";
echo "   - Run fix-permissions.php\n";
echo "   - Or set manually in cPanel File Manager:\n";
echo "     • Folders: 755\n";
echo "     • Files: 644\n";
echo "     • storage/ and bootstrap/cache/: 755 (writable)\n\n";

echo "2. DOCUMENT ROOT:\n";
echo "   - Your domain MUST point to the 'public' folder\n";
echo "   - In cPanel > Domains > Document Root: /public_html/public\n";
echo "   - OR move all files from 'public/' to root 'public_html/'\n\n";

echo "3. .HTACCESS FILE:\n";
echo "   - Make sure .htaccess exists in public/ folder\n";
echo "   - Check it's not corrupted\n";
echo "   - Permissions should be 644\n\n";

echo "4. MOD_REWRITE:\n";
echo "   - Must be enabled on server\n";
echo "   - Contact hosting support if not enabled\n\n";

echo "5. PHP VERSION:\n";
echo "   - Laravel 11 requires PHP 8.2+\n";
echo "   - Current: " . phpversion() . "\n";
echo "   - Change in cPanel > Select PHP Version\n\n";

echo "6. CLEAR CACHE:\n";
echo "   - Delete all files in bootstrap/cache/ (except .gitignore)\n";
echo "   - Clear browser cache and cookies\n";
echo "   - Try incognito/private browsing\n\n";

echo "7. CHECK ERROR LOGS:\n";
echo "   - cPanel > Error Logs\n";
echo "   - storage/logs/laravel.log\n";
echo "   - Look for specific error messages\n\n";

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📞 NEED MORE HELP?\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

echo "If the issue persists:\n\n";
echo "1. Enable debug mode temporarily:\n";
echo "   - Edit .env file\n";
echo "   - Set APP_DEBUG=true\n";
echo "   - Visit /admin again to see detailed error\n";
echo "   - Set back to false after fixing\n\n";

echo "2. Check the actual error:\n";
echo "   - Look at cPanel Error Logs\n";
echo "   - Check storage/logs/laravel.log\n";
echo "   - Share the error message for specific help\n\n";

echo "3. Contact hosting support:\n";
echo "   - Ask if mod_rewrite is enabled\n";
echo "   - Ask about PHP version and extensions\n";
echo "   - Ask about file permission requirements\n\n";

echo "<strong style='color: red;'>⚠️  DELETE this file (check-admin.php) after running!</strong>\n";
echo "</pre>";
?>
