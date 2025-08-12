# GSM Theme Clone (PHP 8.1+, No Composer)

A lightweight PHP MVC web app with Bootstrap 5 for admin and user panels, and a web-based installer at `/install`.

## Requirements
- PHP 8.1+
- MySQL 5.7+/MariaDB 10+
- Extensions: mysqli, pdo_mysql, curl, openssl, json, mbstring, zip, gd, fileinfo
- Apache with mod_rewrite (for pretty URLs)

## Quick Install on cPanel
1. Upload the ZIP to your domain root (e.g., `public_html`).
2. Extract the ZIP.
3. Ensure `public/.htaccess` is present and your document root points to `public` (or move contents of `public` to the root if needed).
4. Visit `https://yourdomain.com/install`.
5. Follow the wizard:
   - Environment check
   - License agreement
   - Database credentials
   - Site name, URL, admin email & password
   - Auto-generate `config.php`
   - Import `database.sql`
   - Create admin user
   - Auto-rename `/install`

If you skip the installer, default admin is `admin@example.com / Password@123` (update after first login). Copy `config.php.sample` to `config.php` and edit.

## Cron Jobs (cPanel > Cron Jobs)
Use the following examples, adjusting paths and PHP binary as needed.

- Hourly API sync:
```
/usr/bin/php -q /home/USER/public_html/cron/sync_apis.php
```
- Every 2 min order updates:
```
/usr/bin/php -q /home/USER/public_html/cron/update_orders.php
```
- Daily subscription checks:
```
/usr/bin/php -q /home/USER/public_html/cron/subscriptions_check.php
```
- Hourly failed job alerts:
```
/usr/bin/php -q /home/USER/public_html/cron/failed_jobs_alert.php
```

Alternatively, you can trigger via HTTP with a secret:
```
https://yourdomain.com/cron/sync_apis.php?secret=YOUR_CRON_SECRET
```

## SMTP (PHPMailer)
Place PHPMailer sources under `lib/PHPMailer/src/{PHPMailer.php,SMTP.php,Exception.php}` to enable SMTP. Otherwise the app will fallback to PHP `mail()`.

## Security
- CSRF tokens on all POST forms
- Prepared statements via PDO
- Basic XSS escaping via `View::e()`
- Optional TOTP 2FA foundation

## Development
- No Composer required
- MVC structure under `app/`
- Routes in `app/routes.php`

## License
See `license.txt`. This project is provided as-is.