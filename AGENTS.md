# Codex: İZODEKOR ERP engineering instructions

Read `docs/PRODUCT_BRIEF.md`, `docs/ARCHITECTURE.md`, `docs/ROADMAP.md` and `README.md` before editing. This repository is an initial ERP application overlay for the official Laravel 13 application skeleton. The existing functional parts must not be replaced by demo screens.

## Mission
Build and maintain an internal, multi-country, multi-warehouse ERP/OMS for İZODEKOR at https://erp.izodekor.com.tr. Support DE/FR/ES/UK, Amazon (SP-API), Shopify, Idealo where supported, ShipStation/Shippo/Sendcloud/DHL/DPD. Minimize handling time and prevent inventory overselling and double shipments.

## Non-negotiable rules
- Prioritize real backend, DB invariants and verified integration behavior over visual polish.
- Never claim a connector is live without official credentials, authorized scopes, and a passing sandbox / test-account integration check.
- Never expose secrets or customer addresses in application logs or version control. GDPR-aware data retention and least privilege.
- Every external webhook/event MUST be idempotent and signature-verified; use queue with exponential retries and dead letter recovery.
- Every external create-label operation MUST use idempotent identifiers, duplicate guards, external status reconciliation and safe retry, to prevent multiple paid labels.
- All warehouse queries MUST be scoped; don't rely on UI hiding data.
- Use integer minor currency units, explicit currency, line/item level tax and accurate FX rate snapshot; distinguish estimate from actual.
- Build a full audit log for mutation and important security operations.
- Introduce API connectors as interfaces with test doubles and contract tests, not fake success states.
- Prefer official docs; check current API versions and policies, especially Amazon restricted data and shipment acknowledgement.
- No unconditional new admin from web UI; `erp:create-admin` only for authorized CLI access.
- Production must never be deployed without verifying cPanel support (PHP >= 8.3, composer, document root /public, cron, queue); use VPS if insufficient.

## Existing first-cut implementation
- Login/logout with role scoping.
- Admin-created warehouses/products; stock adjustments recorded with DB transaction and idempotency key.
- Order records and stock reservation service, initial reports.
- Some features planned, not yet implemented: external API connector adapters, pick/pack scanning, label printing, Vue.js admin UI, auditing, rate limiting beyond login; see roadmap.

## Initial Codex task
1. Bootstrap the official Laravel 13 skeleton using `bin/bootstrap.sh` or `bin/bootstrap.ps1` and verify `php artisan about`.
2. Run migrations and tests; fix any integration issues with the latest Laravel 13 and current dependency versions. Do NOT just suppress failures.
3. Add factories and exhaustive authorization/inventory concurrency tests.
4. Implement Shopify connector first (verified webhook HMAC, order ingestion, mapping, fulfillment tracking submission), using a feature flag and mocks until authorized credentials exist.
5. Implement an outbound channel-agnostic shipment interface, ShipStation as first adapter. Do not auto-buy labels before explicit shipping rules and approval.
6. Update the roadmap with actual tests run, limitations, and exact commands.

## Commands
- `composer install && cp .env.example .env && php artisan key:generate`
- `php artisan migrate` (configure DB first)
- `php artisan erp:create-admin` (interactive, no hard-coded password)
- `php artisan test`
- `php artisan serve`

Use PR-sized changes and tests. Keep existing data and migrations backwards-compatible.
