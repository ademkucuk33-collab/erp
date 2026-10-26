# Codex'e gönderilecek ilk görev

İZODEKOR ERP projesini sıfırdan tasarlamıyorsun; şu anda açık olan proje klasöründeki mevcut ilk sürüm kaynak kodunu geliştiriyorsun. İlk önce `AGENTS.md`, `README.md` ve `docs/` altındaki dosyaları oku.

Hedef domain: `https://erp.izodekor.com.tr`. Mevcut hosting cPanel/paylaşımlı, ancak sunucu gereksinimleri henüz doğrulanmadı. Laravel 13 (PHP 8.3+), MySQL, mevcut Blade arayüzü; daha sonra Vue 3 + Inertia. Birinci öncelik doğru çalışan yazılım ve testtir.

**Bu görevde:**
1. Resmi Laravel 13 iskeletini `bin/bootstrap.sh` (Windows'ta `bin/bootstrap.ps1`) kullanarak tamamla. Script başarısız olursa sorunu düzelterek ilerle.
2. Geliştirme ortamında MySQL veya test için SQLite yapılandırmasını yap; `php artisan migrate`, `php artisan route:list`, `php artisan test` çalıştır. Ortaya çıkan gerçek hataları düzelt. Çalıştırmadığın testlerin geçtiğini söyleme.
3. Admin oturumu, kullanıcı/depo yetkileri, stok giriş/çıkış ve sipariş rezervasyonu için daha fazla test yaz. Eşzamanlı stok güncellemeleri ve çift işleme senaryolarını ayrıca doğrula.
4. Mevcut Blade panelini işlevsel tut; gerçek API verisi olmadan grafiklere demo satış rakamı koyma.
5. Shopify sipariş ingest modülünün veri eşleme mimarisini kur; webhook HMAC doğrulaması, idempotent event kaydı, güvenilir queue ve SKU eşleştirme için test yaz. API yetkilendirmesi verilene kadar gerçek Shopify'dan sipariş geliyormuş gibi davranma.
6. İlk kargo sağlayıcı arabirimini ve shipment state machine tasarımını yap. Etiket satın alma/otomatik gönderim işlemleri gerçek yetkiler ve kabul testleri olmadan devreye alınmasın.
7. Tamamlanan işleri, test sonuçlarını, bloke olan konuları ve kalan aşamaları `docs/ROADMAP.md` içerisine kaydet.

**Ürün hedefi:** Amazon DE/FR/ES/UK, Shopify ve Idealo satış kanalları; ShipStation, Shippo, Sendcloud, DHL, DPD kargo; ülke/depo/kanal/hafta/ay/SKU kırılımlarında satış raporları; tek Super Admin, depoya özel personel kullanıcıları.

**Önemli:** Gerçek müşteri verilerini test ortamına koyma. API anahtarlarını istemeden API entegrasyonunu tamamlandı olarak gösterme. Paylaşımlı cPanel üretim dağıtımını sunucu kontrollerinden önce yapma.
