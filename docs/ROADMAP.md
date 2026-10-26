# Roadmap / Honest Implementation Status

## Phase 1 (source files supplied; verify after installing Laravel)
- [x] Multi-depot DB schema and data structures
- [x] Safe transactional inventory service for adjust/reserve/release/fulfill
- [x] Login/logout and initial super-admin CLI command
- [x] Real dashboard counts and sales report DB queries
- [x] Role+warehouse scoped order screens
- [x] Basic warehouse/product CRUD (create/list), creation of warehouse-specific staff
- [ ] Framework dependency install and end-to-end runtime verification in a Laravel environment
- [ ] Comprehensive tests for role authorization, quantity concurrency, negative stock, race conditions
- [ ] Full audit trail and 2FA
- [ ] Edit/archive product + warehouses, product/warehouse multiple assignments for staff

## Phase 2
- [ ] Official Shopify connector with verified webhook signatures + versioned GraphQL API
- [ ] Ingest orders idempotently with full order/item taxes, discounts and refund state
- [ ] Amazon SP-API with correct marketplace IDs, application authorization + PII restrictions
- [ ] SKU mapping screen and stock push to each channel
- [ ] Reliable background jobs, retry, dead-letter, sync health screen

## Phase 3
- [ ] Pick-and-pack scan UI, package dimensioning
- [ ] ShipStation V2 adapter, real label purchase and print, tracking feedback
- [ ] Additional Shippo, Sendcloud, DHL/DPD adapter selection by rate/country
- [ ] Partial shipments + returns + refunds

## Phase 4
- [ ] Vue 3 + Inertia UI, multilingual settings, role-specific dashboard
- [ ] Financial reconciliation with VAT, COGS, shipping and marketplace fees
- [ ] Reports per SKU, country, channel, period; CSV/PDF/Excel exports
- [ ] GDPR retention/data subject requests and audited access

## Ready-for-live gate
No live operations until official sandbox checks, complete CI tests, backup restore test, proper access controls, idempotent shipment labels, DPIA/GDPR review, and secure hosting verified.
