#!/usr/bin/env bash
# =============================================================================
# Church Website — Create / Update System Admin
# Usage:
#   bash create-admin.sh              # interactive prompts
#   bash create-admin.sh --update     # update an existing user's password
#   bash create-admin.sh --list       # list all existing admin users
# =============================================================================

set -e

# ── Colours ───────────────────────────────────────────────────────────────────
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
CYAN='\033[0;36m'
BOLD='\033[1m'
DIM='\033[2m'
NC='\033[0m'

# ── Helpers ───────────────────────────────────────────────────────────────────
info()    { echo -e "${BLUE}${BOLD}[INFO]${NC}  $1"; }
success() { echo -e "${GREEN}${BOLD}[OK]${NC}    $1"; }
warn()    { echo -e "${YELLOW}${BOLD}[WARN]${NC}  $1"; }
error()   { echo -e "${RED}${BOLD}[ERROR]${NC} $1"; exit 1; }
dim()     { echo -e "${DIM}$1${NC}"; }

# ── Parse flags ───────────────────────────────────────────────────────────────
MODE="create"   # create | update | list

for arg in "$@"; do
    case $arg in
        --update) MODE="update" ;;
        --list)   MODE="list"   ;;
        --help|-h)
            echo ""
            echo -e "${BOLD}Usage:${NC}"
            echo "  bash create-admin.sh            Create a new admin user"
            echo "  bash create-admin.sh --update   Update an existing user's password"
            echo "  bash create-admin.sh --list     List all users in the database"
            echo ""
            exit 0
            ;;
        *) warn "Unknown flag: $arg" ;;
    esac
done

# ── Banner ────────────────────────────────────────────────────────────────────
echo ""
echo -e "${BOLD}============================================${NC}"
echo -e "${BOLD}   Church Website — Admin User Manager${NC}"
echo -e "${BOLD}============================================${NC}"
echo ""

# ── Preflight checks ──────────────────────────────────────────────────────────
command -v php >/dev/null 2>&1      || error "PHP is not installed."
[ -f "artisan" ]                    || error "Run this script from the project root (where artisan lives)."
[ -f ".env" ]                       || error ".env file not found. Run setup.sh first."
[ -f "vendor/autoload.php" ]        || error "vendor/ not found. Run: composer install"

APP_URL=$(grep "^APP_URL=" .env | cut -d '=' -f2 | tr -d '"' | tr -d "'")
APP_URL="${APP_URL:-http://localhost:8000}"

# ── Password generator ────────────────────────────────────────────────────────
generate_password() {
    # 16-char password: letters + digits + safe symbols
    LC_ALL=C tr -dc 'A-Za-z0-9!@#$%^&*' </dev/urandom | head -c 16
    echo
}

# ── Email validator ───────────────────────────────────────────────────────────
validate_email() {
    [[ "$1" =~ ^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$ ]]
}

# ── Password strength check ───────────────────────────────────────────────────
validate_password() {
    local pw="$1"
    local ok=true

    [ ${#pw} -ge 8 ]                    || { warn "Password must be at least 8 characters."; ok=false; }
    [[ "$pw" =~ [A-Z] ]]                || { warn "Password must contain at least one uppercase letter."; ok=false; }
    [[ "$pw" =~ [a-z] ]]                || { warn "Password must contain at least one lowercase letter."; ok=false; }
    [[ "$pw" =~ [0-9] ]]                || { warn "Password must contain at least one number."; ok=false; }

    [ "$ok" = true ]
}

# ── Inline PHP runner ─────────────────────────────────────────────────────────
run_php() {
    php -r "
        define('LARAVEL_START', microtime(true));
        require 'vendor/autoload.php';
        \$app = require 'bootstrap/app.php';
        \$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
        $1
    "
}

# =============================================================================
# MODE: list
# =============================================================================
if [ "$MODE" = "list" ]; then
    info "Fetching users from the database..."
    echo ""

    run_php "
        \$users = \App\Models\User::orderBy('created_at')->get(['id','name','email','email_verified_at','created_at']);
        if (\$users->isEmpty()) {
            echo \"  No users found.\n\";
        } else {
            printf(\"  %-4s %-25s %-35s %-12s %s\n\", 'ID', 'Name', 'Email', 'Verified', 'Created');
            echo '  ' . str_repeat('-', 95) . \"\n\";
            foreach (\$users as \$u) {
                printf(
                    \"  %-4s %-25s %-35s %-12s %s\n\",
                    \$u->id,
                    substr(\$u->name, 0, 24),
                    substr(\$u->email, 0, 34),
                    \$u->email_verified_at ? 'Yes' : 'No',
                    \$u->created_at->format('Y-m-d')
                );
            }
        }
        echo \"\n\";
    "
    exit 0
fi

# =============================================================================
# MODE: update — change an existing user's password
# =============================================================================
if [ "$MODE" = "update" ]; then
    echo -e "${CYAN}${BOLD}Update existing user password${NC}"
    echo ""

    # Show current users first
    info "Current users:"
    run_php "
        \$users = \App\Models\User::orderBy('id')->get(['id','name','email']);
        foreach (\$users as \$u) {
            echo \"  [{$u->id}] {$u->name} <{$u->email}>\n\";
        }
        echo \"\n\";
    "

    # Prompt for email
    while true; do
        read -rp "$(echo -e "${BOLD}Email of user to update:${NC} ")" UPDATE_EMAIL
        if validate_email "$UPDATE_EMAIL"; then
            break
        fi
        warn "Invalid email format. Try again."
    done

    # Check user exists
    USER_EXISTS=$(run_php "
        echo \App\Models\User::where('email', '$UPDATE_EMAIL')->exists() ? 'yes' : 'no';
    ")

    if [ "$USER_EXISTS" != "yes" ]; then
        error "No user found with email: $UPDATE_EMAIL"
    fi

    echo ""
    echo -e "${BOLD}New password${NC} ${DIM}(leave blank to auto-generate)${NC}"

    while true; do
        read -rsp "$(echo -e "${BOLD}New password:${NC} ")" NEW_PASSWORD
        echo ""

        if [ -z "$NEW_PASSWORD" ]; then
            NEW_PASSWORD=$(generate_password)
            warn "Auto-generated password: ${BOLD}$NEW_PASSWORD${NC}"
            break
        fi

        read -rsp "$(echo -e "${BOLD}Confirm password:${NC} ")" CONFIRM_PASSWORD
        echo ""

        if [ "$NEW_PASSWORD" != "$CONFIRM_PASSWORD" ]; then
            warn "Passwords do not match. Try again."
            continue
        fi

        if validate_password "$NEW_PASSWORD"; then
            break
        fi
    done

    echo ""
    info "Updating password..."

    run_php "
        \$user = \App\Models\User::where('email', '$UPDATE_EMAIL')->firstOrFail();
        \$user->update(['password' => \Illuminate\Support\Facades\Hash::make('$NEW_PASSWORD')]);
        echo \"Updated: {\$user->name} <{\$user->email}>\n\";
    "

    echo ""
    success "Password updated successfully."
    echo ""
    echo -e "  ${BOLD}Email:${NC}    $UPDATE_EMAIL"
    echo -e "  ${BOLD}Password:${NC} $NEW_PASSWORD"
    echo -e "  ${BOLD}Login:${NC}    $APP_URL/admin"
    echo ""
    exit 0
fi

# =============================================================================
# MODE: create — create a new admin user
# =============================================================================
echo -e "${CYAN}${BOLD}Create new admin user${NC}"
echo ""

# ── Name ──────────────────────────────────────────────────────────────────────
while true; do
    read -rp "$(echo -e "${BOLD}Full name:${NC} ")" ADMIN_NAME
    ADMIN_NAME="${ADMIN_NAME//\'/\'}"   # escape single quotes
    if [ -n "$ADMIN_NAME" ]; then
        break
    fi
    warn "Name cannot be empty."
done

# ── Email ─────────────────────────────────────────────────────────────────────
while true; do
    read -rp "$(echo -e "${BOLD}Email address:${NC} ")" ADMIN_EMAIL
    if ! validate_email "$ADMIN_EMAIL"; then
        warn "Invalid email format. Try again."
        continue
    fi

    # Check for duplicate
    ALREADY_EXISTS=$(run_php "
        echo \App\Models\User::where('email', '$ADMIN_EMAIL')->exists() ? 'yes' : 'no';
    ")

    if [ "$ALREADY_EXISTS" = "yes" ]; then
        warn "A user with that email already exists."
        echo ""
        read -rp "$(echo -e "  Update their password instead? ${BOLD}[y/N]${NC} ")" SWITCH
        if [[ "$SWITCH" =~ ^[Yy]$ ]]; then
            exec bash "$0" --update
        fi
        continue
    fi

    break
done

# ── Password ──────────────────────────────────────────────────────────────────
echo ""
echo -e "${BOLD}Password${NC} ${DIM}(leave blank to auto-generate a secure password)${NC}"

while true; do
    read -rsp "$(echo -e "${BOLD}Password:${NC} ")" ADMIN_PASSWORD
    echo ""

    if [ -z "$ADMIN_PASSWORD" ]; then
        ADMIN_PASSWORD=$(generate_password)
        warn "Auto-generated password: ${BOLD}$ADMIN_PASSWORD${NC}"
        break
    fi

    read -rsp "$(echo -e "${BOLD}Confirm password:${NC} ")" CONFIRM_PASSWORD
    echo ""

    if [ "$ADMIN_PASSWORD" != "$CONFIRM_PASSWORD" ]; then
        warn "Passwords do not match. Try again."
        continue
    fi

    if validate_password "$ADMIN_PASSWORD"; then
        break
    fi
done

# ── Confirm before creating ───────────────────────────────────────────────────
echo ""
echo -e "${BOLD}Review details:${NC}"
echo -e "  Name:   ${CYAN}$ADMIN_NAME${NC}"
echo -e "  Email:  ${CYAN}$ADMIN_EMAIL${NC}"
echo ""
read -rp "$(echo -e "Create this admin user? ${BOLD}[Y/n]${NC} ")" CONFIRM_CREATE
if [[ "$CONFIRM_CREATE" =~ ^[Nn]$ ]]; then
    echo ""
    warn "Cancelled. No user was created."
    echo ""
    exit 0
fi

# ── Create the user ───────────────────────────────────────────────────────────
echo ""
info "Creating admin user..."

# Escape name for PHP string safety
SAFE_NAME="${ADMIN_NAME//\\/\\\\}"
SAFE_NAME="${SAFE_NAME//\'/\\\'}"

run_php "
    \$user = \App\Models\User::create([
        'name'              => '$SAFE_NAME',
        'email'             => '$ADMIN_EMAIL',
        'password'          => \Illuminate\Support\Facades\Hash::make('$ADMIN_PASSWORD'),
        'email_verified_at' => now(),
        'role'              => 'admin',
    ]);
    
    // Assign admin role using Spatie Permission
    \$adminRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
    \$user->assignRole(\$adminRole);
    
    echo \"Created user ID: {\$user->id} with admin role\n\";
"

# ── Success summary ───────────────────────────────────────────────────────────
echo ""
echo -e "${GREEN}${BOLD}============================================${NC}"
echo -e "${GREEN}${BOLD}   Admin user created successfully!${NC}"
echo -e "${GREEN}${BOLD}============================================${NC}"
echo ""
echo -e "  ${BOLD}Name:${NC}     $ADMIN_NAME"
echo -e "  ${BOLD}Email:${NC}    $ADMIN_EMAIL"
echo -e "  ${BOLD}Password:${NC} $ADMIN_PASSWORD"
echo -e "  ${BOLD}Login:${NC}    $APP_URL/admin"
echo ""
echo -e "${YELLOW}${BOLD}  Save these credentials somewhere safe before closing this window.${NC}"
echo ""
