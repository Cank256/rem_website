# Migration Order Fix

## Issue
The initial analytics migrations had a foreign key constraint error because they were running in the wrong order:

```
❌ page_views (105832) - tried to reference visitor_sessions
❌ visitor_sessions (105845) - created after page_views
❌ analytics_events (105902) - references both tables
```

## Solution
Renamed the migration files to ensure correct execution order:

```
✅ visitor_sessions (105845) - creates parent table first
✅ page_views (105900) - can now reference visitor_sessions
✅ analytics_events (105910) - can reference both tables
```

## How to Apply

### If You Haven't Run Migrations Yet
Simply run:
```bash
php artisan migrate
```

### If You Got the Foreign Key Error
1. Drop the partially created tables:
```bash
# For SQLite
sqlite3 database/database.sqlite "DROP TABLE IF EXISTS analytics_events; DROP TABLE IF EXISTS visitor_sessions; DROP TABLE IF EXISTS page_views;"

# For MySQL
php artisan tinker --execute="DB::statement('DROP TABLE IF EXISTS analytics_events'); DB::statement('DROP TABLE IF EXISTS page_views'); DB::statement('DROP TABLE IF EXISTS visitor_sessions');"
```

2. Run migrations again:
```bash
php artisan migrate
```

## Verification
Check that all migrations ran successfully:
```bash
php artisan migrate:status | grep -E "(visitor_sessions|page_views|analytics_events)"
```

You should see:
```
✓ 2026_05_04_105845_create_visitor_sessions_table ......... Ran
✓ 2026_05_04_105900_create_page_views_table ............... Ran
✓ 2026_05_04_105910_create_analytics_events_table ......... Ran
```

## Commit
Fixed in commit: `ddabb68`

## Prevention
When creating migrations with foreign keys, always ensure:
1. Parent tables are created before child tables
2. Migration timestamps reflect the correct order
3. Test migrations on a fresh database before deploying
