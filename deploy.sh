#!/usr/bin/env bash
#
# FlySeas Travels — Server deployment (Hostinger / cPanel / shared hosting)
#
# Workflow on the server:
#   ./deploy.sh                git pull + composer + migrate + cache (safe)
#   ./deploy.sh --seed         same + run seeders
#   ./deploy.sh --fresh        WIPE DB and reseed (asks confirmation)
#   ./deploy.sh --no-pull      skip git pull (already up-to-date)
#   ./deploy.sh --with-dev     install dev composer packages too
#
# Frontend assets (Vite build) must be built LOCALLY first and committed
# to git (public/build/*) since shared hosts don't have Node.
#
# Override binaries with env vars:
#   PHP_BIN=/opt/alt/php84/usr/bin/php COMPOSER_BIN=/usr/local/bin/composer ./deploy.sh
# ----------------------------------------------------------------------

set -euo pipefail
cd "$(dirname "$0")"

# ─── Colours ────────────────────────────────────────────────────────────
RED='\033[0;31m'; GREEN='\033[0;32m'; YELLOW='\033[1;33m'
BLUE='\033[0;34m'; CYAN='\033[0;36m'; BOLD='\033[1m'; NC='\033[0m'

step()  { echo -e "\n${BLUE}${BOLD}▸ $1${NC}"; }
ok()    { echo -e "  ${GREEN}✓${NC} $1"; }
warn()  { echo -e "  ${YELLOW}⚠${NC} $1"; }
err()   { echo -e "  ${RED}✗${NC} $1"; }
fatal() { err "$1"; exit 1; }

# ─── Auto-detect PHP 8.2+ binary ────────────────────────────────────────
detect_php() {
    local candidates=(
        /opt/alt/php84/usr/bin/php
        /opt/alt/php83/usr/bin/php
        /opt/alt/php82/usr/bin/php
        /opt/cpanel/ea-php84/root/usr/bin/php
        /opt/cpanel/ea-php83/root/usr/bin/php
        /opt/cpanel/ea-php82/root/usr/bin/php
        /usr/local/bin/php
        /usr/bin/php
        php
    )
    for c in "${candidates[@]}"; do
        if command -v "$c" >/dev/null 2>&1; then
            if "$c" -r 'exit(version_compare(PHP_VERSION,"8.2.0","<")?1:0);' >/dev/null 2>&1; then
                echo "$c"
                return 0
            fi
        fi
    done
    return 1
}

detect_composer() {
    local candidates=(
        /usr/local/bin/composer
        /usr/bin/composer
        composer
    )
    for c in "${candidates[@]}"; do
        if command -v "$c" >/dev/null 2>&1; then
            echo "$c"; return 0
        fi
    done
    if [[ -f composer.phar ]]; then
        echo "composer.phar"; return 0
    fi
    return 1
}

PHP_BIN="${PHP_BIN:-$(detect_php || true)}"
COMPOSER_BIN="${COMPOSER_BIN:-$(detect_composer || true)}"

# ─── Flags ──────────────────────────────────────────────────────────────
DO_SEED=false
DO_FRESH=false
SKIP_GIT=false
WITH_DEV=false

for arg in "$@"; do
    case "$arg" in
        --seed)      DO_SEED=true ;;
        --fresh)     DO_FRESH=true ;;
        --no-pull)   SKIP_GIT=true ;;
        --with-dev)  WITH_DEV=true ;;
        -h|--help)
            grep -E "^# (  | )" "$0" | sed 's/^# //'
            exit 0
            ;;
        *) fatal "Unknown flag: $arg (try --help)" ;;
    esac
done

echo -e "${CYAN}${BOLD}"
echo "  ╔═══════════════════════════════════════════╗"
echo "  ║   FlySeas Travels — Server Deployment     ║"
echo "  ╚═══════════════════════════════════════════╝"
echo -e "${NC}"

# ─── 1. Pre-flight ──────────────────────────────────────────────────────
step "Checking environment"

[[ -n "$PHP_BIN"      ]] || fatal "PHP 8.2+ not found. Set PHP_BIN=/path/to/php to override."
[[ -n "$COMPOSER_BIN" ]] || fatal "Composer not found. Set COMPOSER_BIN=/path/to/composer to override."

PHP_VER=$("$PHP_BIN" -r 'echo PHP_VERSION;')
ok "PHP ${PHP_VER}  (${PHP_BIN})"
ok "Composer       (${COMPOSER_BIN})"

# Quick PHP extension check
for ext in pdo_mysql mbstring openssl tokenizer xml ctype json; do
    "$PHP_BIN" -m | grep -qi "^${ext}$" || warn "PHP extension '${ext}' not loaded"
done

# ─── 2. Git pull ────────────────────────────────────────────────────────
if [[ "$SKIP_GIT" != true ]]; then
    if [[ -d .git ]]; then
        step "Pulling latest code from git"
        git fetch --quiet
        BEHIND=$(git rev-list --count HEAD..@{u} 2>/dev/null || echo 0)
        if [[ "$BEHIND" -gt 0 ]]; then
            git pull --ff-only
            ok "Pulled ${BEHIND} new commit(s)"
        else
            ok "Already up-to-date"
        fi
    else
        warn "Not a git repository — skipping pull"
    fi
fi

# ─── 3. Environment file ────────────────────────────────────────────────
step "Verifying .env"

if [[ ! -f .env ]]; then
    if [[ -f .env.example ]]; then
        cp .env.example .env
        ok ".env created from .env.example"
        warn "Edit .env now with your DB credentials, then re-run"
        exit 0
    else
        fatal ".env missing and .env.example not found"
    fi
fi

if ! grep -qE '^APP_KEY=base64:' .env; then
    "$PHP_BIN" artisan key:generate --force --ansi >/dev/null
    ok "APP_KEY generated"
else
    ok "APP_KEY already set"
fi

# ─── 4. Composer install ────────────────────────────────────────────────
step "Installing PHP dependencies"

COMPOSER_FLAGS=(--optimize-autoloader --no-interaction --prefer-dist --no-progress)
if [[ "$WITH_DEV" == true ]]; then
    ok "Including dev packages (--with-dev)"
else
    COMPOSER_FLAGS+=(--no-dev)
fi

# Composer may be a phar that needs PHP, or a binary
if [[ "$COMPOSER_BIN" == *.phar ]]; then
    "$PHP_BIN" "$COMPOSER_BIN" install "${COMPOSER_FLAGS[@]}" 2>&1 | tail -5
else
    "$PHP_BIN" "$COMPOSER_BIN" install "${COMPOSER_FLAGS[@]}" 2>&1 | tail -5 \
        || "$COMPOSER_BIN" install "${COMPOSER_FLAGS[@]}" 2>&1 | tail -5
fi
ok "Composer install complete"

# ─── 5. Storage / permissions ───────────────────────────────────────────
step "Preparing writable directories"
mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache
chmod -R 775 storage bootstrap/cache 2>/dev/null || true
ok "Directories ready"

if [[ ! -L public/storage ]]; then
    "$PHP_BIN" artisan storage:link 2>&1 | tail -1 || warn "Could not create storage symlink"
fi

# ─── 6. Migrations ──────────────────────────────────────────────────────
if [[ "$DO_FRESH" == true ]]; then
    DB_NAME=$(grep -E "^DB_DATABASE=" .env | head -1 | cut -d'=' -f2- | tr -d '"' | tr -d "'")
    step "Wiping database '${DB_NAME}' and reseeding"
    echo -ne "  ${YELLOW}This will DROP ALL TABLES.${NC} Continue? [y/N] "
    read -r CONFIRM
    if [[ "${CONFIRM,,}" != "y" && "${CONFIRM,,}" != "yes" ]]; then
        warn "Aborted by user"
        exit 0
    fi
    "$PHP_BIN" artisan migrate:fresh --seed --force
    ok "Database wiped and seeded"
else
    step "Running pending migrations"
    "$PHP_BIN" artisan migrate --force --no-interaction
    ok "Migrations up to date"

    if [[ "$DO_SEED" == true ]]; then
        step "Running seeders"
        "$PHP_BIN" artisan db:seed --force --no-interaction
        ok "Seeders complete"
    fi
fi

# ─── 7. Cache rebuild ───────────────────────────────────────────────────
step "Rebuilding Laravel caches"
"$PHP_BIN" artisan optimize:clear >/dev/null
"$PHP_BIN" artisan config:cache    >/dev/null && ok "Config cached"
"$PHP_BIN" artisan route:cache     >/dev/null && ok "Routes cached"
"$PHP_BIN" artisan view:cache      >/dev/null && ok "Views cached"
"$PHP_BIN" artisan event:cache     >/dev/null 2>&1 && ok "Events cached" || true

# ─── 8. Verify Vite manifest ────────────────────────────────────────────
if [[ -f public/build/manifest.json ]]; then
    ok "Vite build assets present"
else
    warn "public/build/manifest.json missing — run 'npm run build' locally and commit public/build/*"
fi

# ─── Done ───────────────────────────────────────────────────────────────
echo
echo -e "${GREEN}${BOLD}╔═══════════════════════════════════════════╗${NC}"
echo -e "${GREEN}${BOLD}║   ✓ Deployment complete                   ║${NC}"
echo -e "${GREEN}${BOLD}╚═══════════════════════════════════════════╝${NC}"
echo
APP_URL=$(grep -E "^APP_URL=" .env | head -1 | cut -d'=' -f2- | tr -d '"' | tr -d "'")
[[ -n "$APP_URL" ]] && echo -e "  Site:        ${CYAN}${APP_URL}${NC}"
[[ -n "$APP_URL" ]] && echo -e "  Admin login: ${CYAN}${APP_URL}/admin/login${NC}"
echo
