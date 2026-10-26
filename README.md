# İZODEKOR ERP — Codex-ready initial application

**Status:** working *application-source starter*, not production-ready ERP. Includes real Laravel controllers, migrations, transaction-safe inventory services, user login and server-rendered dashboard. **Not connected to Amazon/Shopify/carriers yet.** It must be bootstrapped with the official Laravel 13 app skeleton and tested with Composer dependencies.

## Use in Codex
1. Unzip this repository and open it as the local folder in Codex, **or** create a new private GitHub repository named `izodekor-erp`, upload these files, and select that repository in Codex.
2. Ask Codex: **the contents of `CODEX_BASLANGIC_PROMPT.md`**
3. Review the changes/tests before the first deployment.

## Install (Linux / macOS / Codex terminal)
```bash
bash bin/bootstrap.sh
cp .env.example .env
# edit DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD
php artisan key:generate
php artisan migrate
php artisan erp:create-admin
php artisan serve
```

## Install on Windows (PowerShell)
```powershell
.\bin\bootstrap.ps1
Copy-Item .env.example .env
php artisan key:generate
php artisan migrate
php artisan erp:create-admin
php artisan serve
```

Both scripts require PHP 8.3+, Composer 2.x and network package download access. `bootstrap` downloads the **official Laravel 13 project skeleton** into a temp folder, retaining this repository's custom files, then runs Composer to install dependencies. This is deliberate: it avoids shipping a stale or incomplete framework skeleton.

Configure `SESSION_DRIVER=database`, `QUEUE_CONNECTION=database`, then run Laravel's `php artisan make:queue-table` only if Laravel's included migration does not already provide it. For cPanel cron:
```text
* * * * * /path/to/php /home/USER/izodekor-erp/artisan schedule:run > /dev/null 2>&1
```
Continuous workers are recommended on a VPS. Production `APP_DEBUG=false`; point the site's document root to `.../public`, never to the repository root. Build/test on staging, not in the production document root.

## Admin initialization
`php artisan erp:create-admin` prompts for email, name and password in the secure terminal (no default password). No public signup route.

## What exists now
- Login, logout, basic auth middleware
- Dashboard with live database counts, no fabricated sales metrics
- Role + assigned warehouse data restrictions
- Warehouse, product and warehouse-staff user creation by Super Admin
- Stock adjustment with a recorded movement and transaction locking
- Order list and detailed order view; safe stock reservation service
- Daily/weekly/monthly sales totals by country/channel
- Automated sample feature tests (require Laravel dependencies and test database)

## Not finished
Carrier API, printable labels, tracking sync, webhook security, live marketplace ingestion, full VAT reconciliation, refunds, automated stock publication, advanced RBAC, auditing, full SPA front-end. See `docs/ROADMAP.md`.

## License & security
Private internal application. Never commit `.env`, supplier credentials, carrier tokens or real customer data. Do not publish the repository publicly.
