# Integration implementation specification (NOT CONNECTED)

| Provider | Intended API | Required behavior | Gate |
|---|---|---|---|
| Shopify | GraphQL Admin + webhooks | verify HMAC, order create/update, fulfillment tracking writeback, sku/inventory mapping | shop authentication and scopes |
| Amazon DE/FR/ES/UK | Official SP-API | authorize regional marketplace ids, orders, buyer/shipping PII only with permissions, FBM shipment acknowledgement, feeds/reconciliation | app authorization and restricted data role where needed |
| Idealo | Checkout order interface where supported | checkout order intake; if referral to Shopify treat Shopify as order source | contract/API scope verification |
| ShipStation | Official API V2 | create labels, compare services, tracking sync, void/cancel as supported | approved carrier and billing account |
| Shippo | Official API | carrier/rate/label/tracking | approved account |
| Sendcloud | Official shipping API | parcels/labels/tracking/webhooks | approved account |
| DHL, DPD | official carrier contracts/API | direct label creation only where enabled | account and country-specific contract |

Canonical order ingestion: (platform, channel_account_id, external_order_id) unique. Canonical SKU mapping: (channel_account_id, external_sku) unique. Source tracking idempotent and verify signatures before persistence. Delay acknowledging shipment until label + warehouse confirmation. Service failures trigger queue retry, never generate second billable label blindly. Amazon FBA orders must not be fulfilled through seller warehouse flows.

Do not treat the same sale as a second order when Idealo referral leads to a Shopify checkout.
