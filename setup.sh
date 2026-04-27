#!/usr/bin/env bash
# =============================================================================
# Church Website — Project Setup Script
# Handles fresh installs and re-installs for the Laravel + Inertia + Filament stack
# Usage:  bash setup.sh [--fresh] [--no-npm] [--prod]
#   --fresh    Drop and re-run all migrations (destructive — local only)
#   --no-npm   Skip npm install / build steps
#   --prod     Install production dependencies only (no dev packages)
# =============================================================================

set -e  # Exit immediately on any error

# ── Colours ──────────────────────────────────────────────────────────────────
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
BOLD='\033[1m'
NC='\033[0m' # No Colour

# ── Helpers ───────────────────────────────────────────────────────────────────
info()    { echo -e "${BLUE}${BOLD}[INFO]${NC}  $1"; }
success() { echo -e "${GREEN}${BOLD}[OK]${NC}    $1"; }
warn()    { echo -e "${YELLOW}${BOLD}[WARN]${NC}  $1"; }
error()   { echo -e "${RED}${BOLD}[ERROR]${NC} $1"; exit 1; }

# ── Parse flags ───────────────────────────────────────────────────────────────
FRESH=false
SKIP_NPM=false
PROD=false

for arg in "$@"; do
    case $arg in
        --fresh)   FRESH=true ;;
        --no-npm)  SKIP_NPM=true ;;
        --prod)    PROD=true ;;
        *)         warn "Unknown flag: $arg" ;;
    esac
done

# ── Banner ────────────────────────────────────────────────────────────────────
echo ""
echo -e "${BOLD}============================================${NC}"
echo -e "${BOLD}   Church Website — Setup Script${NC}"
echo -e "${BOLD}============================================${NC}"
echo ""

# ── 1. Check required tools ───────────────────────────────────────────────────
info "Checking required tools..."

command -v php      >/dev/null 2>&1 || error "PHP is not installed. Install PHP 8.2+."
command -v composer >/dev/null 2>&1 || error "Composer is not installed. Visit https://getcomposer.org"

PHP_VERSION=$(php -r "echo PHP_MAJOR_VERSION.'.'.PHP_MINOR_VERSION;")
REQUIRED="8.2"

if [ "$(printf '%s\n' "$REQUIRED" "$PHP_VERSION" | sort -V | head -n1)" != "$REQUIRED" ]; then
    error "PHP $REQUIRED+ is required. You have PHP $PHP_VERSION."
fi

success "PHP $PHP_VERSION detected"
success "Composer detected ($(composer --version --no-ansi 2>&1 | head -1))"

if [ "$SKIP_NPM" = false ]; then
    command -v node >/dev/null 2>&1 || error "Node.js is not installed. Visit https://nodejs.org"
    command -v npm  >/dev/null 2>&1 || error "npm is not installed."
    success "Node $(node -v) / npm $(npm -v) detected"
fi

echo ""

# ── 2. Composer install ───────────────────────────────────────────────────────
info "Installing PHP dependencies via Composer..."

if [ "$PROD" = true ]; then
    composer install --no-dev --optimize-autoloader --no-interaction
else
    composer install --optimize-autoloader --no-interaction
fi

success "Composer dependencies installed"
echo ""

# ── 3. Environment file ───────────────────────────────────────────────────────
if [ ! -f ".env" ]; then
    info "No .env file found — copying from .env.example..."
    cp .env.example .env
    success ".env file created"
else
    success ".env file already exists — skipping"
fi
echo ""

# ── 4. Application key ────────────────────────────────────────────────────────
APP_KEY=$(grep "^APP_KEY=" .env | cut -d '=' -f2)

if [ -z "$APP_KEY" ]; then
    info "Generating application key..."
    php artisan key:generate --ansi
    success "Application key generated"
else
    success "Application key already set — skipping"
fi
echo ""

# ── 5. Storage symlink ────────────────────────────────────────────────────────
info "Creating storage symlink..."
php artisan storage:link --quiet 2>/dev/null && success "Storage symlink created" || success "Storage symlink already exists"
echo ""

# ── 6. Database setup ─────────────────────────────────────────────────────────
DB_CONNECTION=$(grep "^DB_CONNECTION=" .env | cut -d '=' -f2)

if [ "$DB_CONNECTION" = "sqlite" ]; then
    DB_FILE="database/database.sqlite"
    if [ ! -f "$DB_FILE" ]; then
        info "Creating SQLite database file..."
        touch "$DB_FILE"
        success "SQLite database created at $DB_FILE"
    else
        success "SQLite database file already exists"
    fi
fi

if [ "$FRESH" = true ]; then
    warn "--fresh flag detected: dropping all tables and re-running migrations..."
    if [ "$PROD" = true ]; then
        error "--fresh cannot be used with --prod (too dangerous in production)"
    fi
    php artisan migrate:fresh --seed --ansi
    success "Fresh migration and seeding complete"
else
    info "Running database migrations..."
    php artisan migrate --ansi
    success "Migrations complete"
fi
echo ""

# ── 7. Filament assets ────────────────────────────────────────────────────────
info "Publishing Filament assets..."
php artisan filament:upgrade --quiet
success "Filament assets published"
echo ""

# ── 8. npm install + build ────────────────────────────────────────────────────
if [ "$SKIP_NPM" = false ]; then
    info "Installing Node dependencies..."
    npm install --silent
    success "Node dependencies installed"

    if [ "$PROD" = true ]; then
        info "Building frontend assets for production..."
        npm run build
    else
        info "Building frontend assets..."
        npm run build
    fi
    success "Frontend assets built"
    echo ""
fi

# ── 9. Cache (production only) ────────────────────────────────────────────────
if [ "$PROD" = true ]; then
    info "Caching config, routes, and views for production..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    success "Production caches warmed"
else
    info "Clearing caches for development..."
    php artisan optimize:clear --quiet
    success "Caches cleared"
fi
echo ""

# ── Done ──────────────────────────────────────────────────────────────────────
echo -e "${GREEN}${BOLD}============================================${NC}"
echo -e "${GREEN}${BOLD}   Setup complete!${NC}"
echo -e "${GREEN}${BOLD}============================================${NC}"
echo ""
echo -e "  ${BOLD}Run the dev server:${NC}  composer run dev"
echo -e "  ${BOLD}Admin panel:${NC}         http://localhost:8000/admin"
echo -e "  ${BOLD}Live page:${NC}           http://localhost:8000/live"
echo ""
