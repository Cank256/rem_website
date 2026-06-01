# Church Website Scripts

Utility scripts for setup, maintenance, and deployment.

## Directory Structure

```
scripts/
├── setup/              # Initial setup scripts
├── maintenance/        # Maintenance and troubleshooting scripts
├── deployment/         # Deployment scripts
└── church              # Main CLI tool (use this!)
```

## Quick Start

### Using the CLI Tool (Recommended)

```bash
# Show all available commands
./scripts/church

# Setup new project
./scripts/church setup

# Create admin user
./scripts/church admin:create

# Fix permissions
./scripts/church fix:permissions

# Deploy compression feature
./scripts/church deploy:compression
```

## Individual Scripts

### Setup Scripts

#### 1. setup.sh
Full project setup script for fresh installs and re-installs.

```bash
# Basic setup
bash scripts/setup/setup.sh

# Fresh install (drops database - local only!)
bash scripts/setup/setup.sh --fresh

# Production setup
bash scripts/setup/setup.sh --prod

# Skip npm (if already built)
bash scripts/setup/setup.sh --no-npm
```

**Features:**
- Checks PHP version (8.2+)
- Installs Composer dependencies
- Creates .env file
- Generates app key
- Creates storage symlink
- Runs migrations
- Builds frontend assets
- Caches for production

#### 2. create-admin.sh
Create or update admin users.

```bash
# Create new admin user (interactive)
bash scripts/setup/create-admin.sh

# Update existing user password
bash scripts/setup/create-admin.sh --update

# List all users
bash scripts/setup/create-admin.sh --list
```

**Features:**
- Interactive prompts
- Email validation
- Password strength checking
- Auto-generate secure passwords
- Assigns admin role
- Spatie Permission integration

### Maintenance Scripts

#### 3. fix-permissions.sh
Fix file permissions for gallery images.

```bash
# Fix permissions
bash scripts/maintenance/fix-permissions.sh

# Fix permissions and create .htaccess
bash scripts/maintenance/fix-permissions.sh --create-htaccess
```

**Fixes:**
- Directory permissions (755)
- File permissions (644)
- Creates .htaccess if needed
- Checks for blocking files

#### 4. reset-analytics-migrations.sh
Reset analytics migrations (for troubleshooting).

```bash
bash scripts/maintenance/reset-analytics-migrations.sh
```

**Use when:**
- Analytics migrations failed
- Tables in wrong order
- Need fresh analytics start

**Warning:** Drops all analytics data!

### Deployment Scripts

#### 5. deploy-compression.sh
Deploy image compression feature to production.

```bash
bash scripts/deployment/deploy-compression.sh
```

**Steps:**
1. Installs Composer dependencies
2. Clears caches
3. Checks GD extension
4. Creates/sets gallery directory
5. Tests Intervention Image

## Common Tasks

### Initial Setup (New Installation)

```bash
# Clone repository
git clone <repo-url> church-website
cd church-website

# Run setup
bash scripts/setup/setup.sh

# Create admin user
bash scripts/setup/create-admin.sh

# Start development server
php artisan serve
```

### Production Deployment

```bash
# Pull latest code
git pull origin main

# Run production setup
bash scripts/setup/setup.sh --prod

# Deploy compression (if needed)
bash scripts/deployment/deploy-compression.sh

# Done!
```

### Troubleshooting

```bash
# Fix image permissions
bash scripts/maintenance/fix-permissions.sh

# Reset analytics (if migrations failed)
bash scripts/maintenance/reset-analytics-migrations.sh

# Re-run setup with fresh database
bash scripts/setup/setup.sh --fresh
```

### Update Admin Password

```bash
# Interactive password update
bash scripts/setup/create-admin.sh --update

# Follow prompts to select user and new password
```

## Script Requirements

### All Scripts
- Bash shell
- PHP 8.2+
- Project root access

### Setup Scripts
- Composer
- Node.js 18+ and npm
- Write access to .env

### Maintenance Scripts
- SSH access (for production)
- Write access to storage/

### Deployment Scripts
- Composer
- GD extension (for compression)

## Tips

### Make Scripts Executable

```bash
chmod +x scripts/**/*.sh
chmod +x scripts/church
```

### Create Aliases

Add to `.bashrc` or `.zshrc`:

```bash
alias church-setup='bash scripts/setup/setup.sh'
alias church-admin='bash scripts/setup/create-admin.sh'
alias church-fix='bash scripts/maintenance/fix-permissions.sh'
```

### Run from Anywhere

```bash
# From project root
./scripts/church setup

# From any directory
cd /path/to/church-website && ./scripts/church setup
```

## Safety Notes

### Production Safety
- Never run `--fresh` flag in production
- Always backup database before maintenance scripts
- Test scripts in staging first

### Script Safety
- All scripts check for `artisan` file
- Scripts exit on errors (`set -e`)
- Confirmation prompts for destructive actions

## Troubleshooting Scripts

### Script Won't Run
```bash
# Make executable
chmod +x scripts/setup/setup.sh

# Check bash path
which bash

# Run explicitly
bash scripts/setup/setup.sh
```

### Permission Denied
```bash
# Fix script permissions
chmod +x scripts/**/*.sh

# Or run with bash
bash scripts/setup/setup.sh
```

### PHP Not Found
```bash
# Check PHP installation
which php
php --version

# Or specify PHP path
/usr/bin/php artisan --version
```

## Support

For issues with scripts:
1. Check `storage/logs/laravel.log`
2. Run with verbose output: `bash -x script.sh`
3. Verify requirements (PHP, Composer, Node)
4. See main docs: `docs/TROUBLESHOOTING.md`

---

**All scripts are safe to run multiple times (idempotent)**
