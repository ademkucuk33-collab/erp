# Architecture and Operating Constraints

## Production target
Laravel 13 / PHP 8.3+ / MySQL 8 compatible. Starting with Blade admin frontend for cPanel compatibility and real backend first; use Vue 3 + Inertia as next enhancement. On VPS: Nginx, PHP-FPM, Redis queues, supervisor/systemd, MySQL, TLS, offsite backup.

## Current model
`users` role + warehouse_id; `warehouses`, `products`, `inventory_balances`, `stock_movements`, `channel_accounts`, `orders`, `order_items`, `stock_reservations`, `shipments`, `webhook_events`.

## Identity
No default seed admin. Provision using CLI `erp:create-admin`. Secure cookies; keep auth routes throttled. Warehouse scoping enforced server-side; audit log and fine-grained permissions will be introduced before any production deployment.

## Inventory invariants
- balance.on_hand >= 0, balance.reserved >= 0, reserved <= on_hand.
- `available = on_hand - reserved` always computed; no independently mutable available quantity.
- reserve/release/fulfill in a single DB transaction; pessimistic locks; references unique per order/line.
- no shipping label creation on failed, unmapped or insufficient-stock orders.
- external order id unique per channel account and external order id; per-provider external shipment identifiers unique.

## Money
Store minor integer values (e.g. cents) and ISO currency. Store reports per originating currency, not naive sum of EUR+GBP. Cross currency totals only with immutable snapshot FX rates. Avoid presenting estimated margin as audited net profit.

## Integration contracts to add
- `OrderSource`: fetchOrders / verifyWebhook / normalizeOrder / acknowledgeFulfillment.
- `CarrierLabelProvider`: quote / createLabel / cancelLabel / trackingStatus.
- `StockPublisher`: sendAvailableStock / pollPublicationStatus.
- Durable `integration_jobs` + idempotency keys and backoff.

## Deployment limitations
Shared cPanel: short cron worker possible, no guarantee background worker longevity, resource limits. Check PHP version, MySQL, extensions, SSH, Composer, cron, outbound HTTPS, TLS and web root /public before deployment. Use VPS on insufficient hosting. Prefer managed DB/backups and restricted IP access for admin where possible.
