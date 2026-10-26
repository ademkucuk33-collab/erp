# Teknik Mimari
- Application: Laravel 13 + PHP 8.3+; ilk arayüz Blade, Vue 3 + Inertia daha sonra.
- Persistence: MySQL, migration, benzersiz sipariş anahtarları ve stok kilitleme transaction'ları.
- Queue: database queue ile başlayıp Redis + Supervisor üzerinde VPS'ye taşınabilir.
- Roles: Super Admin, country manager, warehouse manager/staff, order staff, finance; tüm sorgular warehouse scope ile filtrelenir.
- Multi-channel: ChannelAdapter sözleşmesi; Amazon SP-API, Shopify Admin API ve yetkili Idealo işlemleri.
- Shipping: CarrierAdapter sözleşmesi; ShipStation ilk, Shippo/Sendcloud/DHL/DPD sonraki. Gönderi satın alma ve takip bildiriminde idempotency ve reconciliation zorunlu.
- Financials: minor unit integer amounts, VAT included/excluded, currency, historical FX, actual/estimated costs.
- Security: 2FA later, encrypted credentials, GDPR-minimal retention, log redaction, CSRF, webhook HMAC verification, audit events.
- Deployment: PHP 8.3+ / MySQL / SSL / /public docroot / cron and queue. Shared cPanel sınırlı; VPS önerilir. Never commit secrets.
