<?php
/**
 * Simple Migration Script
 * 
 * Runs migrations without using Laravel's Artisan
 * Better for shared hosting with limited resources
 * 
 * IMPORTANT: Delete this file after running!
 */

// Increase limits for shared hosting
ini_set('max_execution_time', 300);
ini_set('memory_limit', '256M');

echo "<h1>Database Migration (Simple Mode)</h1>";
echo "<pre>";

$basePath = dirname(__DIR__);

// Load environment variables
if (!file_exists($basePath . '/.env')) {
    echo "❌ ERROR: .env file not found!\n";
    exit;
}

// Parse .env file
$envFile = file_get_contents($basePath . '/.env');
$envLines = explode("\n", $envFile);
foreach ($envLines as $line) {
    $line = trim($line);
    if (empty($line) || strpos($line, '#') === 0) continue;
    
    if (strpos($line, '=') !== false) {
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);
        $value = trim($value, '"\'');
        putenv("$key=$value");
        $_ENV[$key] = $value;
    }
}

echo "✅ Environment loaded\n\n";

// Database connection
$host = getenv('DB_HOST') ?: 'localhost';
$database = getenv('DB_DATABASE');
$username = getenv('DB_USERNAME');
$password = getenv('DB_PASSWORD');

echo "🔌 Connecting to database...\n";
echo "   Host: $host\n";
echo "   Database: $database\n";
echo "   Username: $username\n\n";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$database;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
    echo "✅ Database connected successfully\n\n";
} catch (PDOException $e) {
    echo "❌ Database connection failed!\n\n";
    echo "Error: " . $e->getMessage() . "\n\n";
    echo "Please check your .env file:\n";
    echo "- DB_HOST=$host\n";
    echo "- DB_DATABASE=$database\n";
    echo "- DB_USERNAME=$username\n";
    echo "- DB_PASSWORD=(hidden)\n\n";
    exit;
}

// Create migrations table if not exists
echo "📋 Setting up migrations table...\n";
$pdo->exec("
    CREATE TABLE IF NOT EXISTS migrations (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        migration VARCHAR(255) NOT NULL,
        batch INT NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
");
echo "✅ Migrations table ready\n\n";

// Get already run migrations
$stmt = $pdo->query("SELECT migration FROM migrations");
$ranMigrations = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Get migration files
$migrationsPath = $basePath . '/database/migrations';
$migrationFiles = glob($migrationsPath . '/*.php');
sort($migrationFiles);

echo "📁 Found " . count($migrationFiles) . " migration files\n\n";

// Get next batch number
$stmt = $pdo->query("SELECT MAX(batch) as max_batch FROM migrations");
$maxBatch = $stmt->fetch()['max_batch'] ?? 0;
$nextBatch = $maxBatch + 1;

$newMigrations = 0;
$skippedMigrations = 0;

echo "🔄 Running migrations...\n\n";

foreach ($migrationFiles as $file) {
    $migrationName = basename($file, '.php');
    
    // Skip if already run
    if (in_array($migrationName, $ranMigrations)) {
        echo "⏭️  Skipped: $migrationName (already run)\n";
        $skippedMigrations++;
        continue;
    }
    
    echo "▶️  Running: $migrationName\n";
    
    try {
        // Include the migration file
        require_once $file;
        
        // Get the class name from the file
        $className = implode('_', array_map('ucfirst', explode('_', substr($migrationName, 18))));
        
        // Try different class name formats
        $possibleClasses = [
            $className,
            'Create' . $className . 'Table',
            'Add' . $className,
        ];
        
        $migrationClass = null;
        foreach ($possibleClasses as $class) {
            if (class_exists($class)) {
                $migrationClass = $class;
                break;
            }
        }
        
        if (!$migrationClass) {
            // Try to find any class in the file
            $content = file_get_contents($file);
            preg_match('/class\s+(\w+)/', $content, $matches);
            if (isset($matches[1])) {
                $migrationClass = $matches[1];
            }
        }
        
        if ($migrationClass && class_exists($migrationClass)) {
            $migration = new $migrationClass;
            
            // Run the up method
            if (method_exists($migration, 'up')) {
                $migration->up();
                
                // Record in migrations table
                $stmt = $pdo->prepare("INSERT INTO migrations (migration, batch) VALUES (?, ?)");
                $stmt->execute([$migrationName, $nextBatch]);
                
                echo "   ✅ Success\n\n";
                $newMigrations++;
            } else {
                echo "   ⚠️  No 'up' method found\n\n";
            }
        } else {
            echo "   ⚠️  Could not find migration class\n\n";
        }
        
    } catch (Exception $e) {
        echo "   ❌ Error: " . $e->getMessage() . "\n\n";
        
        // Continue with other migrations
        continue;
    }
}

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "✅ MIGRATION COMPLETE!\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

echo "📊 Summary:\n";
echo "   New migrations run: $newMigrations\n";
echo "   Already run: $skippedMigrations\n";
echo "   Total migrations: " . count($migrationFiles) . "\n\n";

// Show created tables
echo "📋 Database tables:\n";
try {
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    foreach ($tables as $table) {
        echo "   ✓ $table\n";
    }
    echo "\n";
} catch (Exception $e) {
    echo "   (Could not list tables)\n\n";
}

echo "NEXT STEPS:\n";
echo "1. Run storage-link.php to create storage symlink\n";
echo "2. Run create-admin.php to create admin user\n";
echo "3. DELETE all these PHP files for security!\n\n";

echo "<strong style='color: red;'>⚠️  IMPORTANT: Delete this file (migrate-simple.php) immediately!</strong>\n";
echo "</pre>";
?>
