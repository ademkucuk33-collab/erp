# ERP geliştirme yol haritası

## Aşama 0 — Başlangıç ve kontrol
- [ ] Laravel skeleton bootstrap (Composer)
- [ ] php artisan about / migrate / route:list / test
- [ ] cPanel sunucu kabiliyeti doğrulama

## Aşama 1 — Operasyonel çekirdek
- [ ] Login, secure initial CLI admin creation and role authorization
- [ ] Users/warehouses and country scopes
- [ ] Products, unique SKUs and external SKU maps
- [ ] Atomik stok bakiyeleri ve hareketleri, sipariş rezervasyonu, iptal ve fulfillment
- [ ] Orders dashboard ve kaynak doğrulama

## Aşama 2 — Gerçek API entegrasyonları
- [ ] Shopify HMAC webhooks, order import, inventory and tracking
- [ ] Amazon SP-API orders, restricted roles, FBM shipment sync
- [ ] Idealo checkout vs redirect distinction and de-duplication

## Aşama 3 — Kargo
- [ ] Shipment state machine / provider abstraction
- [ ] ShipStation sandbox + label + tracking + duplicate guards
- [ ] Shippo, Sendcloud, DHL, DPD adapters

## Aşama 4 — Analiz
- [ ] Weekly/monthly country/channel/SKU sales
- [ ] Refunds, commissions, VAT, actual landed costs, FX
- [ ] CSV/XLSX/PDF export

## Durum
İlk kaynak kod deposuna aktarma aşamasında. Bağımlılıklar veya gerçek API'ler henüz bu GitHub branch üzerinde çalıştırılıp doğrulanmadı.
