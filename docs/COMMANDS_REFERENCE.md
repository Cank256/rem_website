# Terminal Commands Reference - Church Website

Complete list of all terminal commands used to build this Laravel church website.

## 📦 INITIAL PROJECT SETUP

### 1. Create Laravel Project
```bash
composer create-project laravel/laravel church-website "11.*" --prefer-dist
cd church-website
```

### 2. Install Laravel Breeze (Authentication with React/Inertia)
```bash
composer require laravel/breeze --dev
php artisan breeze:install react
```

### 3. Install Node Dependencies and Build
```bash
npm install
npm run build
```

---

## 🗄️ DATABASE & MODELS

### Create Models with Migrations and Factories
```bash
php artisan make:model Sermon -mf
php artisan make:model Event -mf
php artisan make:model BlogPost -mf
```

**Flags explained:**
- `-m` = Create migration
- `-f` = Create factory
- `-c` = Create controller (if needed)
- `-a` = Create all (model, migration, factory, seeder, controller, resource)

### Run Migrations
```bash
php artisan migrate
```

### Rollback Migrations
```bash
php artisan migrate:rollback
```

### Fresh Migration (Drop all tables and re-migrate)
```bash
php artisan migrate:fresh
```

### Seed Database
```bash
php artisan db:seed
```

### Fresh Migration with Seeding
```bash
php artisan migrate:fresh --seed
```

---

## 🎨 FILAMENT ADMIN PANEL

### Install Filament v3
```bash
composer require filament/filament:"^3.2"
```

### Install Filament Panels
```bash
php artisan filament:install --panels
```

### Create Admin User
```bash
php artisan make:filament-user
```

### Create Filament Resources
```bash
# Generate resource with auto-generated form and table
php artisan make:filament-resource Sermon --generate
php artisan make:filament-resource Event --generate
php artisan make:filament-resource BlogPost --generate

# Create resource without auto-generation
php artisan make:filament-resource ModelName

# Create resource with soft deletes
php artisan make:filament-resource ModelName --soft-deletes

# Create simple resource (no view/edit pages)
php artisan make:filament-resource ModelName --simple
```

### Other Filament Commands
```bash
# Create a custom page
php artisan make:filament-page PageName

# Create a widget
php artisan make:filament-widget WidgetName

# Create a custom theme
php artisan make:filament-theme

# Publish Filament config
php artisan vendor:publish --tag=filament-config
```

---

## 🎭 CONTROLLERS & ROUTES

### Create Controllers
```bash
# Basic controller
php artisan make:controller HomeController

# Resource controller (with CRUD methods)
php artisan make:controller SermonController --resource

# API resource controller
php artisan make:controller SermonController --api

# Invokable controller (single action)
php artisan make:controller ShowSermonController --invokable
```

### View Routes
```bash
# List all routes
php artisan route:list

# Filter routes by name
php artisan route:list --name=sermon

# Filter routes by method
php artisan route:list --method=GET

# Show only specific columns
php artisan route:list --columns=Method,URI,Name
```

---

## 🎨 FRONTEND & ASSETS

### Install React Player
```bash
npm install react-player
```

### Install Additional NPM Packages
```bash
# Install a package
npm install package-name

# Install as dev dependency
npm install --save-dev package-name

# Install specific version
npm install package-name@1.2.3
```

### Build Commands
```bash
# Development build with hot reload
npm run dev

# Production build (optimized)
npm run build

# Watch for changes
npm run watch
```

### Update Dependencies
```bash
# Update all packages
npm update

# Update specific package
npm update package-name

# Check for outdated packages
npm outdated
```

---

## 🔧 LARAVEL ARTISAN COMMANDS

### Application Key
```bash
# Generate application key
php artisan key:generate
```

### Cache Management
```bash
# Clear all caches
php artisan optimize:clear

# Clear specific caches
php artisan cache:clear          # Application cache
php artisan config:clear         # Configuration cache
php artisan route:clear          # Route cache
php artisan view:clear           # Compiled views cache
php artisan event:clear          # Event cache

# Cache for production
php artisan config:cache         # Cache configuration
php artisan route:cache          # Cache routes
php artisan view:cache           # Cache views
php artisan event:cache          # Cache events

# Optimize everything for production
php artisan optimize
```

### Database Commands
```bash
# Show database information
php artisan db:show

# Show table information
php artisan db:table users

# Monitor database queries
php artisan db:monitor

# Wipe database (drop all tables)
php artisan db:wipe
```

### Queue Commands
```bash
# Run queue worker
php artisan queue:work

# Run queue worker with specific connection
php artisan queue:work redis

# Process only one job
php artisan queue:work --once

# List failed jobs
php artisan queue:failed

# Retry failed job
php artisan queue:retry job-id

# Retry all failed jobs
php artisan queue:retry all

# Clear failed jobs
php artisan queue:flush
```

### Storage Commands
```bash
# Create symbolic link from public/storage to storage/app/public
php artisan storage:link

# Clear expired password reset tokens
php artisan auth:clear-resets
```

### Maintenance Mode
```bash
# Enable maintenance mode
php artisan down

# Enable with secret bypass
php artisan down --secret="bypass-token"

# Disable maintenance mode
php artisan up
```

### Make Commands (Scaffolding)
```bash
# Models
php artisan make:model ModelName
php artisan make:model ModelName -mfsc  # with migration, factory, seeder, controller

# Controllers
php artisan make:controller ControllerName
php artisan make:controller ControllerName --resource

# Migrations
php artisan make:migration create_table_name
php artisan make:migration add_column_to_table

# Seeders
php artisan make:seeder TableSeeder

# Factories
php artisan make:factory ModelFactory

# Requests (Form Requests)
php artisan make:request StoreSermonRequest

# Middleware
php artisan make:middleware CheckAge

# Policies
php artisan make:policy SermonPolicy

# Events
php artisan make:event SermonCreated

# Listeners
php artisan make:listener SendSermonNotification

# Jobs
php artisan make:job ProcessSermon

# Notifications
php artisan make:notification SermonPublished

# Mail
php artisan make:mail SermonMail

# Commands
php artisan make:command SendEmails

# Rules (Validation)
php artisan make:rule Uppercase

# Resources (API)
php artisan make:resource SermonResource

# Tests
php artisan make:test SermonTest
php artisan make:test SermonTest --unit
```

### Tinker (Interactive Shell)
```bash
# Start tinker
php artisan tinker

# Example tinker commands (inside tinker):
# User::all()
# Sermon::find(1)
# Event::create(['title' => 'Test Event', ...])
```

### Other Useful Commands
```bash
# List all artisan commands
php artisan list

# Get help for a command
php artisan help migrate

# Show application information
php artisan about

# Show environment information
php artisan env

# Inspire (get a random quote)
php artisan inspire
```

---

## 🧪 TESTING

### Run Tests
```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/SermonTest.php

# Run with coverage
php artisan test --coverage

# Run parallel tests
php artisan test --parallel
```

### PHPUnit
```bash
# Run PHPUnit directly
./vendor/bin/phpunit

# Run specific test
./vendor/bin/phpunit --filter testSermonCreation
```

---

## 📦 COMPOSER COMMANDS

### Install & Update
```bash
# Install dependencies
composer install

# Install without dev dependencies (production)
composer install --no-dev --optimize-autoloader

# Update all dependencies
composer update

# Update specific package
composer update vendor/package

# Require new package
composer require vendor/package

# Require dev package
composer require --dev vendor/package

# Remove package
composer remove vendor/package
```

### Autoload
```bash
# Regenerate autoload files
composer dump-autoload

# Optimize autoload (for production)
composer dump-autoload --optimize
```

### Other Composer Commands
```bash
# Show installed packages
composer show

# Show outdated packages
composer outdated

# Validate composer.json
composer validate

# Check for security vulnerabilities
composer audit

# Clear composer cache
composer clear-cache
```

---

## 🚀 PRODUCTION DEPLOYMENT

### Pre-Deployment
```bash
# Install dependencies (production)
composer install --no-dev --optimize-autoloader

# Build frontend assets
npm run build

# Cache everything
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize autoloader
composer dump-autoload --optimize
```

### Post-Deployment
```bash
# Run migrations
php artisan migrate --force

# Clear old caches
php artisan optimize:clear

# Re-cache for production
php artisan optimize
```

### Rollback
```bash
# Rollback last migration
php artisan migrate:rollback

# Rollback specific steps
php artisan migrate:rollback --step=2

# Clear all caches
php artisan optimize:clear
```

---

## 🔍 DEBUGGING

### Logs
```bash
# View logs in real-time
tail -f storage/logs/laravel.log

# View last 50 lines
tail -n 50 storage/logs/laravel.log

# Clear logs
echo "" > storage/logs/laravel.log
```

### Laravel Pail (Log Viewer)
```bash
# Start log viewer
php artisan pail

# Filter by level
php artisan pail --filter="error"
```

---

## 🔐 PERMISSIONS (Linux/cPanel)

### Set Proper Permissions
```bash
# Storage and cache directories
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Make writable by web server
chown -R www-data:www-data storage bootstrap/cache

# Or for cPanel (replace username)
chown -R username:username storage bootstrap/cache
```

---

## 📋 GIT COMMANDS (Version Control)

### Basic Git Commands
```bash
# Initialize repository
git init

# Add files
git add .
git add filename

# Commit changes
git commit -m "Initial commit"

# Push to remote
git push origin main

# Pull from remote
git pull origin main

# Check status
git status

# View commit history
git log

# Create branch
git checkout -b feature-name

# Switch branch
git checkout main

# Merge branch
git merge feature-name
```

---

## 🎯 QUICK REFERENCE

### Start Development
```bash
php artisan serve
npm run dev
```

### Fresh Start (Reset Everything)
```bash
php artisan migrate:fresh --seed
npm run build
php artisan optimize:clear
```

### Production Build
```bash
composer install --no-dev --optimize-autoloader
npm run build
php artisan optimize
```

### Clear Everything
```bash
php artisan optimize:clear
composer dump-autoload
npm run build
```

---

## 📝 NOTES

- Always run `php artisan optimize:clear` when things aren't working as expected
- Use `php artisan optimize` only in production
- Run `npm run build` before deploying to production
- Keep `.env` file secure and never commit it to version control
- Use `--force` flag for production migrations: `php artisan migrate --force`

---

**Last Updated**: April 19, 2026  
**Laravel Version**: 11.x  
**PHP Version**: 8.2+
