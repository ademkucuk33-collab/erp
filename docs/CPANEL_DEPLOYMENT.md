# cPanel / erp.izodekor.com.tr kurulum notları

Paylaşımlı hostingde PHP >=8.3, SSH, Composer, MySQL, cron, SSL, /public document root ve outbound HTTPS doğrulanmadan üretime geçmeyin. Laravel uygulamasının .env, vendor ve storage dizinleri web kökünden erişilemez olmalı. php artisan scheduler cron örneği: `* * * * * /path/to/php /home/USER/erp/artisan schedule:run`. Queue sürekli çalışmalı; cPanel kısıtlıysa VPS'ye taşıyın. Ayrı güvenli staging DB, yedek ve rollback planı kurun. API gizli anahtarları yalnız sunucuda tutulmalı.
