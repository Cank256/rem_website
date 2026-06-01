# Command Reference

Quick reference for all terminal commands.

## Setup Commands

### Initial Setup
```bash
# Navigate to project
cd church-website

# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate

# Seed database with sample data
php artisan db:seed

# Build frontend assets
npm run build

# Start development server
php artisan serve
```

## Filament Admin

### Admin User
```bash
# Create admin user (interactive)
php artisan make:filament-user

# Create Filament resource
php artisan make:filament-resource ModelName --generate
```

## YouTube Integration

### Sync Commands
```bash
# Test YouTube API connection
php artisan youtube:test-connection

# Sync live streams to sermons
php artisan youtube:sync-sermons

# Sync with options
php artisan youtube:sync-sermons --max-results=100
php artisan youtube:sync-sermons --speaker="Pastor John"
php artisan youtube:sync-sermons --channel-id=UCxxxxx --api-key=AIzaxxxxx
```

## Email Testing

```bash
# Test Resend email integration
php artisan resend:test your-email@example.com
```

## Database Commands

### Migrations
```bash
# Run all pending migrations
php artisan migrate

# Run migrations in production
php artisan migrate --force

# Rollback last migration
php artisan migrate:rollback

# Rollback specific steps
php artisan migrate:rollback --step=2

# Fresh migration (drops all tables)
php artisan migrate:fresh

# Fresh migration with seeding
php artisan migrate:fresh --seed

# Check migration status
php artisan migrate:status
```

### Database Management
```bash
# Show database information
php artisan db:show

# Show table structure
php artisan db:table users

# Wipe database (drop all tables)
php artisan db:wipe
```

### Seeding
```bash
# Run database seeder
php artisan db:seed

# Run specific seeder
php artisan db:seed --class=SermonSeeder
```

## Cache Management

### Clear Caches
```bash
# Clear all caches (one command)
php artisan optimize:clear

# Or clear individually:
php artisan cache:clear          # Application cache
php artisan config:clear         # Configuration cache
php artisan route:clear          # Route cache
php artisan view:clear          # Compiled views
php artisan event:clear         # Event cache
```

### Cache for Production
```bash
# Cache everything (production)
php artisan optimize

# Or cache individually:
php artisan config:cache        # Cache config
php artisan route:cache         # Cache routes
php artisan view:cache          # Cache views
php artisan event:cache         # Cache events
```

## Storage Commands

```bash
# Create storage symbolic link
php artisan storage:link

# Clear expired password reset tokens
php artisan auth:clear-resets
```

## Routes

```bash
# List all routes
php artisan route:list

# Filter by name
php artisan route:list --name=sermon

# Filter by method
php artisan route:list --method=GET

# Filter by URI
php artisan route:list --path=admin

# Show specific columns
php artisan route:list --columns=Method,URI,Name
```

## Tinker (Interactive Shell)

```bash
# Start tinker
php artisan tinker

# Example commands (inside tinker):
>>> User::all()
>>> Sermon::count()
>>> Event::find(1)
>>> cache()->flush()
>>> exit
```

## NPM Commands

### Development
```bash
# Development build with hot reload
npm run dev

# Watch for changes
npm run watch
```

### Production
```bash
# Production build (optimized)
npm run build

# Install dependencies
npm install

# Update all packages
npm update

# Check for outdated packages
npm outdated
```

## Composer Commands

### Dependencies
```bash
# Install all dependencies
composer install

# Install without dev dependencies (production)
composer install --no-dev --optimize-autoloader

# Update all dependencies
composer update

# Update specific package
composer update vendor/package

# Add new package
composer require vendor/package

# Add dev package
composer require --dev vendor/package

# Remove package
composer remove vendor/package
```

### Autoload
```bash
# Regenerate autoload files
composer dump-autoload

# Optimize autoload (production)
composer dump-autoload --optimize
```

### Other Composer
```bash
# Show installed packages
composer show

# Show outdated packages
composer outdated

# Check for security vulnerabilities
composer audit

# Validate composer.json
composer validate
```

## Production Deployment

### Pre-Deployment
```bash
# Install production dependencies
composer install --no-dev --optimize-autoloader

# Build frontend
npm install
npm run build

# Cache everything
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Post-Deployment
```bash
# Run migrations
php artisan migrate --force

# Clear old caches
php artisan optimize:clear

# Optimize for production
php artisan optimize
```

## Debugging

### Logs
```bash
# View logs in real-time
tail -f storage/logs/laravel.log

# View last 50 lines
tail -n 50 storage/logs/laravel.log

# Clear log file
echo "" > storage/logs/laravel.log
```

### Configuration
```bash
# Show specific config
php artisan config:show app
php artisan config:show database
php artisan config:show mail

# Show environment
php artisan env

# Show application info
php artisan about
```

## Permissions (Linux/cPanel)

```bash
# Set correct permissions
chmod -R 755 storage bootstrap/cache

# Set file permissions
find storage bootstrap/cache -type f -exec chmod 644 {} \;

# Set directory permissions
find storage bootstrap/cache -type d -exec chmod 755 {} \;

# Change owner (cPanel)
chown -R username:username storage bootstrap/cache
```

## Quick Shortcuts

### Fresh Start (Development)
```bash
php artisan migrate:fresh --seed
npm run build
php artisan optimize:clear
```

### Start Development
```bash
# Terminal 1
php artisan serve

# Terminal 2
npm run dev
```

### Deploy Updates
```bash
composer install --no-dev --optimize-autoloader
npm run build
php artisan migrate --force
php artisan optimize
```

### Emergency Cache Clear
```bash
php artisan optimize:clear
composer dump-autoload
npm run build
```

---

**For detailed usage, see: GETTING-STARTED.md, DEPLOYMENT.md, TROUBLESHOOTING.md**
