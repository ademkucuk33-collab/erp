# İZODEKOR ERP

İZODEKOR için çok ülkeli, çok depolu ERP/OMS başlangıç projesi.

**Domain:** https://erp.izodekor.com.tr  
**Durum:** Geliştirme aşamasında. Canlı pazaryeri veya kargo entegrasyonu henüz yok.

## Hedef
Amazon DE / FR / ES / UK, Shopify, Idealo (desteklenen fonksiyonlar); ShipStation, Shippo, Sendcloud, DHL ve DPD. Tek Super Admin, depoya özel kullanıcılar, stok rezervasyonu, sipariş-kargo akışı ve ülke/kanal/ürün bazlı raporlama.

## Teknoloji
Laravel 13 (PHP 8.3+), MySQL, database queue; başlangıçta Blade, sonrasında Vue 3 + Inertia. cPanel geçici test ortamı olabilir; worker ve yüksek hacim için VPS önerilir.

## Codex ile başlatma
1. `AGENTS.md` ve `CODEX_BASLANGIC_PROMPT.md` dosyalarını oku.
2. Resmi Laravel 13 projesinin eksik iskeletini kur: `bash bin/bootstrap.sh`.
3. `.env.example` dosyasından `.env` üret; gerçek şifre/anahtarları yalnızca burada tut.
4. `php artisan key:generate`, `php artisan migrate`, `php artisan erp:create-admin`, `php artisan test`.
5. Geliştirme ve testleri tamamlamadan production deploy yapma.

Bu depo tamamlanmış Laravel uygulaması değildir; mevcut ERP başlangıç kodu resmi Laravel iskeletiyle birleştirilmeyi ve test edilmeyi gerektirir. 
