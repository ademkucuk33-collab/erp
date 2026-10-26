# cPanel readiness checklist / deployment planning

**Do not upload this unverified source to the live domain as-is.** First bootstrap, test and security-review on localhost/staging.

Required provider checks:
1. PHP 8.3+ CLI and web SAPI version, `pdo_mysql`, `openssl`, `mbstring`, `xml`, `ctype`, `fileinfo`, `tokenizer`, `intl` and `zip`.
2. Composer, SSH/Terminal access, sufficient PHP memory limit, HTTP outbound TLS to API providers, MySQL version.
3. Laravel app files in a nonpublic directory and **document root bound to `public/`**. Never move the whole project into `public_html`.
4. Managed SSL/TLS, secure cookies (`SESSION_SECURE_COOKIE=true`), `APP_ENV=production`, `APP_DEBUG=false`.
5. Cron Jobs / queue process feasibility; if worker longevity and concurrency unavailable, migrate to VPS.
6. Daily offsite encrypted database+label backup and a documented restore test.
7. Staging domain and isolated DB; no live credentials in dev; least-privilege MySQL login.
8. Server log retention/rotation, PHP-FPM permissions, storage writable, cache dir writable; never 0777.
9. Security testing, 2FA, rate limits, audit logging, sensitive field access controls before launch.

On VPS: move subdomain A/AAAA record to new hosting, configure Nginx document root `/var/www/izodekor-erp/public`, PHP-FPM, MySQL, Redis, persistent workers, Laravel scheduler and TLS. Plan rollback; do not change DNS without approved maintenance.

For shared cPanel Laravel cron: `* * * * * /path/to/php /home/USER/izodekor-erp/artisan schedule:run >/dev/null 2>&1` after verifying the actual paths; queue jobs separately.
