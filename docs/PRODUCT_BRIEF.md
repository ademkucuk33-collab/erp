# İZODEKOR ERP Product Brief

- Domain: `erp.izodekor.com.tr`, existing DNS and shared cPanel hosting, NOT verified accessible.
- Objective: reduce human operations by centralizing order intake, stock allocation, picking/packing, carrier label generation, tracking writeback, real sales and profitability reports.
- Initial selling countries: DE/FR/ES/GB; allow unlimited country/depot expansion.
- Channels: Amazon SP-API DE/FR/ES/UK, Shopify, Idealo (distinguish referral vs direct checkout), later eBay/Kaufland/OTTO/Etsy.
- Carrier providers: ShipStation, Shippo, Sendcloud, direct DHL/DPD; choose routing provider per warehouse/country.
- Super admin manages all data, can create users; warehouse staff may see only assigned orders/warehouse. No financial cost visibility to staff.
- Metrics: weekly/monthly/yearly by country, channel, SKU, revenue, net revenue, commissions, taxes, refunds, shipping, COGS, margin, sold units, return rates.
- Automated pipeline: order intake -> dedup -> SKU mapping -> stock reserve -> warehouse pick/pack -> label -> tracking notification -> shipment status sync -> reconciled reporting.
- Multi-warehouse and stock safety is fundamental; order channel country must not necessarily match physical shipping warehouse.
- Keep Turkish as first UI language, plan English and German localization.
- Live API integrations require credentials and active testing; no API secrets yet.
