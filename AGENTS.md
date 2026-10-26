# Codex: İZODEKOR ERP engineering instructions

Read `README.md`, `docs/PRODUCT_BRIEF.md`, `docs/ARCHITECTURE.md`, `docs/ROADMAP.md` before editing. This repo contains Laravel application overlay files, not the full framework skeleton.

## Mission
Build internal multi-country, multi-warehouse ERP/OMS at https://erp.izodekor.com.tr. Support DE/FR/ES/UK; Amazon SP-API, Shopify and eligible Idealo integrations, with ShipStation/Shippo/Sendcloud/DHL/DPD shipping adapters.

## Non-negotiable rules
- Real backend, database transactions, tests and inventory consistency take priority over UI polish.
- Never commit credentials, customer data, label API tokens or .env.
- Webhooks must validate signatures, be idempotent, and use safely retried queues.
- Guard carrier label purchases against duplicate creation and reconcile unknown outcomes.
- Warehouse authorization must be enforced server-side, not only visually.
- Store integer minor currency units, currencies and FX snapshots; distinguish estimated vs actual cost.
- Do not claim live integrations without current official APIs, permissions and integration tests.
- Never automatically initialize a default admin password; use a protected CLI.
- Do not deploy to shared cPanel until PHP, SSH, Composer, cron, queue, SSL and /public docroot are verified.

## Initial work
1. Run `bin/bootstrap.sh` or `bin/bootstrap.ps1`, fix bootstrap problems.
2. Run migrations, route:list and automated tests. Fix real errors; do not silence failing tests.
3. Improve permissions and inventory tests, including concurrency/idempotency.
4. Implement Shopify signed webhook + idempotent ingestion; use mocks until authorized.
5. Implement shipping provider interface, then ShipStation adapter using test credentials.
6. Document all executed tests and remaining blockers.

Use focused pull requests; avoid direct production deployments.
