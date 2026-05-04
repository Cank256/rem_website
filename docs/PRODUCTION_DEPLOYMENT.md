# Production Deployment Guide for Analytics

## If You're Deploying for the First Time

Simply run:
```bash
php artisan migrate --force
```

## If You Got the "Table Already Exists" Error

This happens when migrations were partially run. Follow these steps:

### Step 1: Check Current State
```bash
php artisan migrate:status
```

### Step 2: Clean Up Partial Tables

Run this command to drop the analytics tables and reset migration records:

```bash
php artisan tinker --execute="
\$driver = DB::getDriverName();
echo 'Database: ' . \$driver . PHP_EOL;

// Disable foreign key checks for MySQL
if (\$driver === 'mysql') {
    DB::statement('SET FOREIGN_KEY_CHECKS=0');
}

// Drop analytics tables
DB::statement('DROP TABLE IF EXISTS analytics_events');
DB::statement('DROP TABLE IF EXISTS page_views');
DB::statement('DROP TABLE IF EXISTS visitor_sessions');

// Re-enable foreign key checks for MySQL
if (\$driver === 'mysql') {
    DB::statement('SET FOREIGN_KEY_CHECKS=1');
}

echo 'Tables dropped' . PHP_EOL;

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
    
echo 'Removed ' . \$deleted . ' migration records' . PHP_EOL;
echo 'Ready to migrate!' . PHP_EOL;
"
```

### Step 3: Run Migrations
```bash
php artisan migrate --force
```

### Step 4: Verify
```bash
php artisan migrate:status | grep -E "(visitor_sessions|page_views|analytics_events)"
```

You should see:
```
✓ 2026_05_04_105845_create_visitor_sessions_table ......... [X] Ran
✓ 2026_05_04_105900_create_page_views_table ............... [X] Ran
✓ 2026_05_04_105910_create_analytics_events_table ......... [X] Ran
```

### Step 5: Clear Caches
```bash
php artisan optimize:clear
```

## Alternative: Manual SQL Cleanup (MySQL)

If you prefer to use SQL directly:

```sql
-- Disable foreign key checks
SET FOREIGN_KEY_CHECKS=0;

-- Drop tables
DROP TABLE IF EXISTS analytics_events;
DROP TABLE IF EXISTS page_views;
DROP TABLE IF EXISTS visitor_sessions;

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS=1;

-- Remove migration records
DELETE FROM migrations 
WHERE migration IN (
    '2026_05_04_105845_create_visitor_sessions_table',
    '2026_05_04_105900_create_page_views_table',
    '2026_05_04_105910_create_analytics_events_table',
    '2026_05_04_105832_create_page_views_table',
    '2026_05_04_105902_create_analytics_events_table'
);
```

Then run:
```bash
php artisan migrate --force
```

## Complete Deployment Checklist

- [ ] Pull latest code: `git pull origin main`
- [ ] Install dependencies: `composer install --no-dev --optimize-autoloader`
- [ ] Clean up partial migrations (if needed)
- [ ] Run migrations: `php artisan migrate --force`
- [ ] Clear caches: `php artisan optimize:clear`
- [ ] Verify tables exist
- [ ] Test analytics tracking
- [ ] Check admin dashboard at `/admin`

## Verification Commands

### Check if tables exist:
```bash
php artisan tinker --execute="
echo 'Checking tables:' . PHP_EOL;
try { DB::table('visitor_sessions')->count(); echo '✓ visitor_sessions' . PHP_EOL; } catch (Exception \$e) { echo '✗ visitor_sessions' . PHP_EOL; }
try { DB::table('page_views')->count(); echo '✓ page_views' . PHP_EOL; } catch (Exception \$e) { echo '✗ page_views' . PHP_EOL; }
try { DB::table('analytics_events')->count(); echo '✓ analytics_events' . PHP_EOL; } catch (Exception \$e) { echo '✗ analytics_events' . PHP_EOL; }
"
```

### Test analytics tracking:
1. Visit your website
2. Accept cookies
3. Browse a few pages
4. Check admin panel: `/admin` → Analytics → Page Views
5. You should see your visits recorded

## Troubleshooting

### Error: "Table already exists"
- Follow Step 2 above to clean up partial tables

### Error: "Foreign key constraint"
- Make sure you're using the latest migration files with correct timestamps
- The order should be: visitor_sessions (105845) → page_views (105900) → analytics_events (105910)

### Error: "Migration not found"
- Make sure you've pulled the latest code
- Check that migration files exist in `database/migrations/`

### Analytics not tracking
- Check cookie consent was accepted
- Verify middleware is registered in `bootstrap/app.php`
- Check `.env` has correct database connection
- Clear cache: `php artisan optimize:clear`

## Post-Deployment

1. **Test the system:**
   - Visit website and accept cookies
   - Browse several pages
   - Check admin analytics dashboard

2. **Monitor for issues:**
   - Check Laravel logs: `storage/logs/laravel.log`
   - Monitor database size
   - Verify tracking is working

3. **Optional: Set up data cleanup:**
   - Consider adding a scheduled task to delete old analytics data
   - See `docs/ANALYTICS_README.md` for details

## Support

If you encounter issues:
1. Check `docs/MIGRATION_FIX.md` for migration order details
2. Review `docs/ANALYTICS_README.md` for full documentation
3. Check Laravel logs for error details
4. Verify database connection settings in `.env`
