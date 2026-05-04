#!/usr/bin/env bash
#
# FlySeas Travels — One-command deployment
#
#   ./deploy.sh              install + build + migrate + optimise (safe — keeps data)
#   ./deploy.sh --seed       same as above, then run seeders
#   ./deploy.sh --fresh      WIPES DB and reseeds (dangerous; asks confirmation)
#   ./deploy.sh --start      run everything and start the server at the end
#   ./deploy.sh --fresh --start
#
# Compatible with macOS and Linux.
# ----------------------------------------------------------------------

set -euo pipefail

# Move to script directory regardless of where it's run from
cd "$(dirname "$0")"

# ─── Colours ────────────────────────────────────────────────────────────
RED='\033[0;31m'; GREEN='\033[0;32m'; YELLOW='\033[1;33m'
BLUE='\033[0;34m'; CYAN='\033[0;36m'; BOLD='\033[1m'; NC='\033[0m'

step()  { echo -e "\n${BLUE}${BOLD}▸ $1${NC}"; }
ok()    { echo -e "  ${GREEN}✓${NC} $1"; }
warn()  { echo -e "  ${YELLOW}⚠${NC} $1"; }
err()   { echo -e "  ${RED}✗${NC} $1"; }
fatal() { err "$1"; exit 1; }

# ─── Flags ──────────────────────────────────────────────────────────────
DO_SEED=false
DO_FRESH=false
DO_START=false
SERVE_HOST="${SERVE_HOST:-0.0.0.0}"
SERVE_PORT="${SERVE_PORT:-8000}"

for arg in "$@"; do
    case "$arg" in
        --seed)   DO_SEED=true ;;
        --fresh)  DO_FRESH=true ;;
        --start)  DO_START=true ;;
        -h|--help)
            grep -E "^# (  | )" "$0" | sed 's/^# //'
            exit 0
            ;;
        *) fatal "Unknown flag: $arg (try --help)" ;;
    esac
done

echo -e "${CYAN}${BOLD}"
echo "  ╔═══════════════════════════════════════════╗"
echo "  ║   FlySeas Travels — Deployment Script     ║"
echo "  ╚═══════════════════════════════════════════╝"
echo -e "${NC}"

# ─── 1. Pre-flight ──────────────────────────────────────────────────────
step "Checking system dependencies"

command -v php       >/dev/null 2>&1 || fatal "PHP not found. Install PHP 8.2+ (brew install php on macOS)"
command -v composer  >/dev/null 2>&1 || fatal "Composer not found. Install Composer"
command -v node      >/dev/null 2>&1 || fatal "Node not found. Install Node 18+ (brew install node)"
command -v npm       >/dev/null 2>&1 || fatal "npm not found"

PHP_VER=$(php -r 'echo PHP_VERSION;')
NODE_VER=$(node --version)
ok "PHP ${PHP_VER}"
ok "Composer $(composer --version 2>/dev/null | awk '{print $3}')"
ok "Node ${NODE_VER}"

# Quick PHP version sanity (8.2+)
if php -r 'exit(version_compare(PHP_VERSION, "8.2.0", "<") ? 1 : 0);' ; then :; else
    warn "PHP ${PHP_VER} is below 8.2. Laravel 12 needs 8.2+ — install with: brew install php"
fi

# ─── 2. Environment ─────────────────────────────────────────────────────
step "Setting up .env"

if [[ ! -f .env ]]; then
    if [[ -f .env.example ]]; then
        cp .env.example .env
        ok ".env created from .env.example"
    else
        fatal ".env.example not found — cannot bootstrap environment"
    fi
fi

# Generate APP_KEY if blank
if ! grep -qE '^APP_KEY=base64:' .env ; then
    php artisan key:generate --force --ansi >/dev/null
    ok "Application key generated"
else
    ok "APP_KEY already set"
fi

# Read DB config from .env
get_env() { grep -E "^$1=" .env | head -1 | cut -d'=' -f2- | tr -d '"' | tr -d "'"; }
DB_CONN=$(get_env DB_CONNECTION)
DB_NAME=$(get_env DB_DATABASE)
DB_HOST=$(get_env DB_HOST)
DB_USER=$(get_env DB_USERNAME)
DB_PASS=$(get_env DB_PASSWORD)
DB_PORT=$(get_env DB_PORT)

ok "DB driver: ${DB_CONN:-sqlite}"

# ─── 3. Database ────────────────────────────────────────────────────────
step "Preparing database"

case "${DB_CONN:-sqlite}" in
    mysql)
        if command -v mysql >/dev/null 2>&1; then
            MYSQL_CMD=(mysql -h "${DB_HOST:-127.0.0.1}" -P "${DB_PORT:-3306}" -u "${DB_USER:-root}")
            [[ -n "${DB_PASS:-}" ]] && MYSQL_CMD+=(-p"${DB_PASS}")
            if "${MYSQL_CMD[@]}" -e "SELECT 1" >/dev/null 2>&1; then
                "${MYSQL_CMD[@]}" -e "CREATE DATABASE IF NOT EXISTS \`${DB_NAME}\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
                ok "MySQL database '${DB_NAME}' ready"
            else
                warn "Could not connect to MySQL with .env credentials — make sure server is running"
                warn "Try: brew services start mysql"
            fi
        else
            warn "mysql CLI not installed; create '${DB_NAME}' manually"
        fi
        ;;
    sqlite)
        mkdir -p database
        SQLITE_FILE="database/database.sqlite"
        [[ -f "$SQLITE_FILE" ]] || touch "$SQLITE_FILE"
        ok "SQLite file: ${SQLITE_FILE}"
        ;;
    *)
        warn "Unrecognised DB_CONNECTION '${DB_CONN}' — skipping DB creation"
        ;;
esac

# ─── 4. Composer ────────────────────────────────────────────────────────
step "Installing PHP dependencies (production mode)"
composer install \
    --optimize-autoloader \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --no-progress 2>&1 | grep -E "(Installing|Generating|Loading)" | tail -3 || true
ok "Composer install complete"

# ─── 5. Frontend ────────────────────────────────────────────────────────
step "Building frontend assets"
if [[ -f package-lock.json ]]; then
    npm ci --prefer-offline --no-audit --no-fund 2>&1 | tail -3 || npm install --no-audit --no-fund
else
    npm install --no-audit --no-fund 2>&1 | tail -3
fi
ok "npm packages installed"

npm run build
ok "Vite production build complete"

# ─── 6. Storage / permissions ───────────────────────────────────────────
step "Preparing storage and cache directories"
mkdir -p storage/framework/{cache,sessions,views,testing} storage/logs bootstrap/cache
chmod -R 775 storage bootstrap/cache 2>/dev/null || true
ok "Directories ensured"

if [[ ! -L public/storage ]]; then
    php artisan storage:link 2>&1 | tail -1 || true
    ok "Storage symlink created"
else
    ok "Storage symlink exists"
fi

# ─── 7. Migrations ──────────────────────────────────────────────────────
if [[ "$DO_FRESH" == "true" ]]; then
    step "Wiping database and reseeding"
    echo -ne "  ${YELLOW}This will DROP all tables in '${DB_NAME}'.${NC} Continue? [y/N] "
    read -r CONFIRM
    if [[ "${CONFIRM,,}" != "y" && "${CONFIRM,,}" != "yes" ]]; then
        warn "Aborted by user"
        exit 0
    fi
    php artisan migrate:fresh --seed --force
    ok "Database wiped and seeded"
else
    step "Running pending migrations"
    php artisan migrate --force --no-interaction
    ok "Migrations up to date"

    if [[ "$DO_SEED" == "true" ]]; then
        step "Running seeders"
        php artisan db:seed --force --no-interaction
        ok "Seeders complete"
    fi
fi

# ─── 8. Optimisation ────────────────────────────────────────────────────
step "Optimising Laravel for production"
php artisan optimize:clear >/dev/null
php artisan config:cache   >/dev/null && ok "Config cached"
php artisan route:cache    >/dev/null && ok "Routes cached"
php artisan view:cache     >/dev/null && ok "Views cached"
php artisan event:cache    >/dev/null 2>&1 && ok "Events cached" || true

# ─── Done ───────────────────────────────────────────────────────────────
echo
echo -e "${GREEN}${BOLD}╔═══════════════════════════════════════════╗${NC}"
echo -e "${GREEN}${BOLD}║   ✓ Deployment complete                   ║${NC}"
echo -e "${GREEN}${BOLD}╚═══════════════════════════════════════════╝${NC}"
echo

if [[ "$DO_START" == "true" ]]; then
    step "Starting server on http://${SERVE_HOST}:${SERVE_PORT}"
    echo
    echo -e "  ${CYAN}Public site:${NC} http://localhost:${SERVE_PORT}"
    echo -e "  ${CYAN}Admin login:${NC} http://localhost:${SERVE_PORT}/admin/login"
    echo -e "  ${CYAN}Default admin:${NC} admin@flyseastravels.com / flyseas2026"
    echo
    echo -e "  ${YELLOW}Press Ctrl+C to stop the server${NC}"
    echo
    exec php artisan serve --host="${SERVE_HOST}" --port="${SERVE_PORT}"
else
    echo -e "  ${BOLD}Next steps:${NC}"
    echo
    echo -e "  ${CYAN}Start the dev server:${NC}"
    echo "    php artisan serve"
    echo
    echo -e "  ${CYAN}Or run this script with --start to launch immediately:${NC}"
    echo "    ./deploy.sh --start"
    echo
    echo -e "  ${CYAN}URLs:${NC}"
    echo "    Public site:  http://localhost:8000"
    echo "    Admin login:  http://localhost:8000/admin/login"
    echo "    Default:      admin@flyseastravels.com / flyseas2026"
    echo
    echo -e "  ${CYAN}Production hosting:${NC}"
    echo "    Point your web server (nginx/apache) docroot at: $(pwd)/public"
    echo "    Run php-fpm with PHP ${PHP_VER}"
    echo "    Set APP_ENV=production and APP_DEBUG=false in .env"
    echo
fi
