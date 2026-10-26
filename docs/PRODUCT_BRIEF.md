# Ürün Özeti
İZODEKOR ERP, Amazon Almanya/Fransa/İspanya/İngiltere, Shopify ve teknik olarak desteklenen Idealo siparişlerini tek panelde toplayan özel şirket yazılımıdır. Depo ve kullanıcı bazlı erişim, global SKU eşleme, doğru stok rezervasyonu, toplu barkodlu sipariş hazırlama, otomatik kargo etiketi üretimi, tracking senkronizasyonu ve ülke/kanal/ürün bazlı net satış-kârlılık raporlaması hedeflenir.
İş akışı: pazaryeri siparişi -> idempotent kayıt -> SKU eşleştirmesi -> uygun depo -> atomik stok rezervasyonu -> topla/paketle -> taşıyıcı API -> etiket ve tracking -> satış kanalına bildirim -> rapor.
Canlı bağlantılar API izinleri alınmadan açılmamalıdır.
