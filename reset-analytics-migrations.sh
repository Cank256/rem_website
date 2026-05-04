#!/bin/bash

# Reset Analytics Migrations Script
# This script cleans up partial analytics migrations and prepares for a fresh migration

echo "=========================================="
echo "Analytics Migration Reset Script"
echo "=========================================="
echo ""

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo "❌ Error: artisan file not found. Please run this script from the Laravel root directory."
    exit 1
fi

echo "⚠️  WARNING: This will drop analytics tables and reset migration records."
echo ""
read -p "Are you sure you want to continue? (yes/no): " confirm

if [ "$confirm" != "yes" ]; then
    echo "Aborted."
    exit 0
fi

echo ""
echo "🔍 Checking database connection..."
php artisan tinker --execute="echo 'Database: ' . DB::getDriverName() . PHP_EOL;"

echo ""
echo "🗑️  Dropping analytics tables and cleaning migration records..."

php artisan tinker --execute="
\$driver = DB::getDriverName();

// Disable foreign key checks for MySQL
if (\$driver === 'mysql') {
    DB::statement('SET FOREIGN_KEY_CHECKS=0');
}

// Drop analytics tables
try {
    DB::statement('DROP TABLE IF EXISTS analytics_events');
    echo '✓ Dropped analytics_events' . PHP_EOL;
} catch (Exception \$e) {
    echo '✗ Could not drop analytics_events: ' . \$e->getMessage() . PHP_EOL;
}

try {
    DB::statement('DROP TABLE IF EXISTS page_views');
    echo '✓ Dropped page_views' . PHP_EOL;
} catch (Exception \$e) {
    echo '✗ Could not drop page_views: ' . \$e->getMessage() . PHP_EOL;
}

try {
    DB::statement('DROP TABLE IF EXISTS visitor_sessions');
    echo '✓ Dropped visitor_sessions' . PHP_EOL;
} catch (Exception \$e) {
    echo '✗ Could not drop visitor_sessions: ' . \$e->getMessage() . PHP_EOL;
}

// Re-enable foreign key checks for MySQL
if (\$driver === 'mysql') {
    DB::statement('SET FOREIGN_KEY_CHECKS=1');
}

// Remove migration records
\$deleted = DB::table('migrations')
    ->whereIn('migration', [
        '2026_05_04_105845_create_visitor_sessions_table',
        '2026_05_04_105900_create_page_views_table',
        '2026_05_04_105910_create_analytics_events_table',
        '2026_05_04_105832_create_page_views_table',
        '2026_05_04_105902_create_analytics_events_table'
    ])
    ->delete();
    
echo '✓ Removed ' . \$deleted . ' migration records' . PHP_EOL;
"

echo ""
echo "✅ Cleanup complete!"
echo ""
echo "📋 Next steps:"
echo "   1. Run: php artisan migrate"
echo "   2. Run: php artisan optimize:clear"
echo "   3. Verify: php artisan migrate:status"
echo ""
