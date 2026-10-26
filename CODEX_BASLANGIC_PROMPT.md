# Codex'e gönderilecek ilk görev

Bu depo İZODEKOR ERP'nin başlangıç kaynak kodudur. `AGENTS.md`, `README.md` ve `docs` dosyalarını önce oku. Üretim adresi https://erp.izodekor.com.tr; hosting cPanel paylaşımlı ve gereksinimleri henüz doğrulanmadı.

1. Laravel 13 (PHP 8.3+) resmi uygulama iskeletini `bin/bootstrap.sh` (Windows'ta `bin/bootstrap.ps1`) ile tamamla.
2. Eksik application overlay kaynaklarını incele, migration ve model uyumunu düzelt. MySQL ile migration, artisan route:list ve testleri çalıştır.
3. Admin girişi, depo bazlı personel erişimi, SKU, stok hareketleri ve atomik sipariş rezervasyonunu tamamla; otomatik testler yaz.
4. Mevcut arayüz için veri kaynağı gerçekten veritabanı olsun; sahte satış verisi koyma.
5. Shopify webhook HMAC doğrulaması, olay tekilleştirme, queue, sipariş-SKU eşleme ve fulfillment gönderimi için testli entegrasyon geliştir.
6. Kargo etiketini ikinci kez satın almayı önleyen shipment/label state machine geliştir. İlk adaptör ShipStation, sonra DHL/DPD/Shippo/Sendcloud.
7. Amazon DE/FR/ES/UK SP-API yetkileri, restricted data erişimi ve sipariş/fulfillment akışını hazırlayıp resmi API üzerinde doğrula.
8. Raporlarda ülke, kanal, hafta/ay, ürün/SKU, komisyon, maliyet ve iadeleri destekle.
9. `docs/ROADMAP.md` içine gerçekleştirilen testleri, engelleri ve sonraki işleri yaz.

Test edilmemiş veya API yetkisi olmayan fonksiyonlara 'tamamlandı' deme. Gerçek müşteri bilgisi, üretim anahtarı ve .env depoya konulmayacak. Kullanıcı onayı olmadan canlı ortama dağıtım yapma.
