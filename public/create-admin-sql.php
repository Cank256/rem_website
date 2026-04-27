<?php
/**
 * Admin User Creation Script (Direct SQL)
 * 
 * Creates admin user using direct SQL
 * Works on any shared hosting without timeouts
 * 
 * IMPORTANT: Delete this file after running!
 */

// Increase limits
ini_set('max_execution_time', 60);
ini_set('memory_limit', '128M');

echo "<h1>Admin User Creation (Direct SQL)</h1>";
echo "<pre>";

// ⚠️  CHANGE THESE VALUES BEFORE RUNNING!
$name = 'Bishop Dr. John Mark Nuwagaba';
$email = 'admin@ruralevangelical.org';  // ⚠️  CHANGE THIS TO YOUR ACTUAL EMAIL!
$password = 'ChangeThisPassword123!';  // ⚠️  CHANGE THIS TO YOUR STRONG PASSWORD!

// Validate
if ($email === 'admin@yourdomain.com') {
    echo "❌ ERROR: Please edit this file first!\n\n";
    echo "Change these lines in the file:\n";
    echo "\$email = 'your-actual-email@domain.com';\n";
    echo "\$password = 'YourStrongPassword123!';\n\n";
    echo "<strong style='color: red;'>Edit the file, save, then refresh this page.</strong>\n";
    echo "</pre>";
    exit;
}

$basePath = dirname(__DIR__);

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

// Database connection
$host = getenv('DB_HOST') ?: 'localhost';
$database = getenv('DB_DATABASE');
$username = getenv('DB_USERNAME');
$dbPassword = getenv('DB_PASSWORD');

echo "🔌 Connecting to database...\n";
echo "   Database: $database\n\n";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$database;charset=utf8mb4",
        $username,
        $dbPassword,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    echo "✅ Connected successfully\n\n";
} catch (PDOException $e) {
    echo "❌ Connection failed: " . $e->getMessage() . "\n";
    exit;
}

// Check if users table exists
try {
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() === 0) {
        echo "❌ ERROR: 'users' table does not exist!\n\n";
        echo "Please run migrate-sql.php first to create the tables.\n";
        exit;
    }
} catch (PDOException $e) {
    echo "❌ ERROR: Could not check for users table\n";
    exit;
}

echo "🔍 Checking if user already exists...\n";

// Check if user exists
$stmt = $pdo->prepare("SELECT id, email FROM users WHERE email = ?");
$stmt->execute([$email]);
$existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

if ($existingUser) {
    echo "⚠️  User with email '$email' already exists!\n\n";
    echo "User ID: " . $existingUser['id'] . "\n";
    echo "Email: " . $existingUser['email'] . "\n\n";
    echo "If you forgot the password:\n";
    echo "1. Delete the user from phpMyAdmin\n";
    echo "2. Run this script again\n\n";
    echo "OR change the email in this script to create a different admin.\n\n";
} else {
    echo "👤 Creating admin user...\n\n";
    
    // Hash password using bcrypt
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    
    // Get current timestamp
    $now = date('Y-m-d H:i:s');
    
    try {
        // Insert user
        $stmt = $pdo->prepare("
            INSERT INTO users (name, email, password, email_verified_at, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $name,
            $email,
            $hashedPassword,
            $now,
            $now,
            $now
        ]);
        
        $userId = $pdo->lastInsertId();
        
        echo "✅ ADMIN USER CREATED SUCCESSFULLY!\n\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "📋 SAVE THESE CREDENTIALS:\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        echo "User ID:  $userId\n";
        echo "Name:     $name\n";
        echo "Email:    $email\n";
        echo "Password: $password\n\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        
        $appUrl = getenv('APP_URL') ?: 'http://yourdomain.com';
        echo "🔗 Login URL: $appUrl/admin\n\n";
        
        echo "NEXT STEPS:\n";
        echo "1. Save the credentials above in a secure location\n";
        echo "2. Visit $appUrl/admin\n";
        echo "3. Login with the email and password above\n";
        echo "4. DELETE this file immediately!\n\n";
        
    } catch (PDOException $e) {
        echo "❌ ERROR creating user: " . $e->getMessage() . "\n\n";
        
        if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
            echo "This email is already in use.\n";
            echo "Please use a different email address.\n\n";
        }
    }
}

echo "<strong style='color: red;'>⚠️  IMPORTANT: Delete this file (create-admin-sql.php) immediately!</strong>\n";
echo "</pre>";
?>
