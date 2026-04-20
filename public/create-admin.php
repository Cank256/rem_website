<?php
/**
 * Admin User Creation Script
 * 
 * Creates the first admin user for Filament
 * Run this AFTER migrations and storage link
 * 
 * IMPORTANT: Delete this file after running!
 */

echo "<h1>Admin User Creation</h1>";
echo "<pre>";

// ⚠️  CHANGE THESE VALUES BEFORE RUNNING!
$name = 'Admin User';
$email = 'admin@yourdomain.com';  // ⚠️  CHANGE THIS!
$password = 'ChangeThisPassword123!';  // ⚠️  CHANGE THIS!

// Validate email
if ($email === 'admin@yourdomain.com') {
    echo "❌ ERROR: Please edit this file and change the email address!\n\n";
    echo "Open create-admin.php in File Manager and update:\n";
    echo "- \$email = 'your-actual-email@domain.com';\n";
    echo "- \$password = 'YourStrongPassword123!';\n\n";
    echo "<strong style='color: red;'>Edit the file, save it, then refresh this page.</strong>\n";
    echo "</pre>";
    exit;
}

try {
    $basePath = dirname(__DIR__);
    
    define('LARAVEL_START', microtime(true));

    require $basePath . '/vendor/autoload.php';

    $app = require_once $basePath . '/bootstrap/app.php';

    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    
    echo "🔍 Checking if user already exists...\n\n";

    // Check if user exists
    $existingUser = \App\Models\User::where('email', $email)->first();
    
    if ($existingUser) {
        echo "⚠️  User with email '$email' already exists!\n\n";
        echo "If you forgot the password, you can:\n";
        echo "1. Delete the existing user from database (phpMyAdmin)\n";
        echo "2. Run this script again\n\n";
        echo "OR change the email in this script to create a different admin.\n\n";
    } else {
        echo "👤 Creating admin user...\n\n";
        
        // Create admin user
        $user = \App\Models\User::create([
            'name' => $name,
            'email' => $email,
            'password' => \Illuminate\Support\Facades\Hash::make($password),
            'email_verified_at' => now(),
        ]);

        echo "✅ ADMIN USER CREATED SUCCESSFULLY!\n\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "📋 SAVE THESE CREDENTIALS:\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        echo "Name:     " . $name . "\n";
        echo "Email:    " . $email . "\n";
        echo "Password: " . $password . "\n\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        
        $appUrl = env('APP_URL', 'http://yourdomain.com');
        echo "🔗 Login URL: " . $appUrl . "/admin\n\n";
        
        echo "NEXT STEPS:\n";
        echo "1. Save the credentials above in a secure location\n";
        echo "2. Visit " . $appUrl . "/admin\n";
        echo "3. Login with the email and password above\n";
        echo "4. DELETE this file immediately!\n\n";
    }

} catch (\Exception $e) {
    echo "❌ ERROR OCCURRED:\n\n";
    echo "Error: " . $e->getMessage() . "\n\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n\n";
    
    if (strpos($e->getMessage(), 'users') !== false) {
        echo "This looks like a database table error.\n";
        echo "Make sure you've run migrate.php successfully first!\n\n";
    }
}

echo "<strong style='color: red;'>⚠️  IMPORTANT: Delete this file (create-admin.php) immediately after creating the user!</strong>\n";
echo "</pre>";
?>
