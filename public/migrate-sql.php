<?php
/**
 * Direct SQL Migration Script
 * 
 * Runs migrations using direct SQL
 * Use this if migrate.php and migrate-simple.php don't work
 * 
 * IMPORTANT: Delete this file after running!
 */

// Increase limits
ini_set('max_execution_time', 300);
ini_set('memory_limit', '256M');

echo "<h1>Database Migration (Direct SQL)</h1>";
echo "<pre>";

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
$password = getenv('DB_PASSWORD');

echo "🔌 Connecting to database...\n";
echo "   Host: $host\n";
echo "   Database: $database\n\n";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$database;charset=utf8mb4",
        $username,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    echo "✅ Connected successfully\n\n";
} catch (PDOException $e) {
    echo "❌ Connection failed: " . $e->getMessage() . "\n";
    exit;
}

echo "🔄 Creating tables...\n\n";

$tables = [
    'migrations' => "
        CREATE TABLE IF NOT EXISTS migrations (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255) NOT NULL,
            batch INT NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'users' => "
        CREATE TABLE IF NOT EXISTS users (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            email_verified_at TIMESTAMP NULL,
            password VARCHAR(255) NOT NULL,
            remember_token VARCHAR(100) NULL,
            created_at TIMESTAMP NULL,
            updated_at TIMESTAMP NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'password_reset_tokens' => "
        CREATE TABLE IF NOT EXISTS password_reset_tokens (
            email VARCHAR(255) PRIMARY KEY,
            token VARCHAR(255) NOT NULL,
            created_at TIMESTAMP NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'sessions' => "
        CREATE TABLE IF NOT EXISTS sessions (
            id VARCHAR(255) PRIMARY KEY,
            user_id BIGINT UNSIGNED NULL,
            ip_address VARCHAR(45) NULL,
            user_agent TEXT NULL,
            payload LONGTEXT NOT NULL,
            last_activity INT NOT NULL,
            INDEX sessions_user_id_index (user_id),
            INDEX sessions_last_activity_index (last_activity)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'cache' => "
        CREATE TABLE IF NOT EXISTS cache (
            `key` VARCHAR(255) PRIMARY KEY,
            value MEDIUMTEXT NOT NULL,
            expiration INT NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'cache_locks' => "
        CREATE TABLE IF NOT EXISTS cache_locks (
            `key` VARCHAR(255) PRIMARY KEY,
            owner VARCHAR(255) NOT NULL,
            expiration INT NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'jobs' => "
        CREATE TABLE IF NOT EXISTS jobs (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            queue VARCHAR(255) NOT NULL,
            payload LONGTEXT NOT NULL,
            attempts TINYINT UNSIGNED NOT NULL,
            reserved_at INT UNSIGNED NULL,
            available_at INT UNSIGNED NOT NULL,
            created_at INT UNSIGNED NOT NULL,
            INDEX jobs_queue_index (queue)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'job_batches' => "
        CREATE TABLE IF NOT EXISTS job_batches (
            id VARCHAR(255) PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            total_jobs INT NOT NULL,
            pending_jobs INT NOT NULL,
            failed_jobs INT NOT NULL,
            failed_job_ids LONGTEXT NOT NULL,
            options MEDIUMTEXT NULL,
            cancelled_at INT NULL,
            created_at INT NOT NULL,
            finished_at INT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'failed_jobs' => "
        CREATE TABLE IF NOT EXISTS failed_jobs (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            uuid VARCHAR(255) NOT NULL UNIQUE,
            connection TEXT NOT NULL,
            queue TEXT NOT NULL,
            payload LONGTEXT NOT NULL,
            exception LONGTEXT NOT NULL,
            failed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'sermons' => "
        CREATE TABLE IF NOT EXISTS sermons (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            slug VARCHAR(255) NOT NULL UNIQUE,
            speaker_name VARCHAR(255) NOT NULL,
            date_preached DATE NOT NULL,
            youtube_url VARCHAR(255) NULL,
            audio_url VARCHAR(255) NULL,
            description TEXT NULL,
            created_at TIMESTAMP NULL,
            updated_at TIMESTAMP NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'events' => "
        CREATE TABLE IF NOT EXISTS events (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            slug VARCHAR(255) NOT NULL UNIQUE,
            start_datetime DATETIME NOT NULL,
            end_datetime DATETIME NULL,
            location VARCHAR(255) NULL,
            description TEXT NOT NULL,
            image_path VARCHAR(255) NULL,
            created_at TIMESTAMP NULL,
            updated_at TIMESTAMP NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'blog_posts' => "
        CREATE TABLE IF NOT EXISTS blog_posts (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            slug VARCHAR(255) NOT NULL UNIQUE,
            content LONGTEXT NOT NULL,
            author_id BIGINT UNSIGNED NOT NULL,
            published_at TIMESTAMP NULL,
            created_at TIMESTAMP NULL,
            updated_at TIMESTAMP NULL,
            FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'galleries' => "
        CREATE TABLE IF NOT EXISTS galleries (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            description TEXT NULL,
            slug VARCHAR(255) NOT NULL UNIQUE,
            is_active BOOLEAN NOT NULL DEFAULT 1,
            sort_order INT NOT NULL DEFAULT 0,
            created_at TIMESTAMP NULL,
            updated_at TIMESTAMP NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    
    'gallery_images' => "
        CREATE TABLE IF NOT EXISTS gallery_images (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            gallery_id BIGINT UNSIGNED NOT NULL,
            title VARCHAR(255) NULL,
            description TEXT NULL,
            image_path VARCHAR(255) NOT NULL,
            sort_order INT NOT NULL DEFAULT 0,
            created_at TIMESTAMP NULL,
            updated_at TIMESTAMP NULL,
            FOREIGN KEY (gallery_id) REFERENCES galleries(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
];

$created = 0;
$existed = 0;
$errors = 0;

foreach ($tables as $tableName => $sql) {
    try {
        // Check if table exists
        $stmt = $pdo->query("SHOW TABLES LIKE '$tableName'");
        if ($stmt->rowCount() > 0) {
            echo "⏭️  $tableName (already exists)\n";
            $existed++;
        } else {
            $pdo->exec($sql);
            echo "✅ $tableName (created)\n";
            $created++;
        }
    } catch (PDOException $e) {
        echo "❌ $tableName (error: " . $e->getMessage() . ")\n";
        $errors++;
    }
}

echo "\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "✅ MIGRATION COMPLETE!\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

echo "📊 Summary:\n";
echo "   Created: $created tables\n";
echo "   Already existed: $existed tables\n";
echo "   Errors: $errors\n\n";

// Show all tables
echo "📋 All tables in database:\n";
$stmt = $pdo->query("SHOW TABLES");
$allTables = $stmt->fetchAll(PDO::FETCH_COLUMN);
foreach ($allTables as $table) {
    echo "   ✓ $table\n";
}
echo "\n";

echo "NEXT STEPS:\n";
echo "1. Run storage-link.php to create storage symlink\n";
echo "2. Run create-admin.php to create admin user\n";
echo "3. DELETE all these PHP files for security!\n\n";

echo "<strong style='color: red;'>⚠️  IMPORTANT: Delete this file (migrate-sql.php) immediately!</strong>\n";
echo "</pre>";
?>
