# Deploy ke cPanel dengan Terminal

Panduan lengkap untuk deploy aplikasi Laravel ke cPanel shared hosting yang memiliki akses Terminal.

## Persiapan

### 1. Persiapan File Lokal
```bash
# Build asset production
npm run build

# Clear cache dan optimize
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan optimize
```

### 2. Compress File Project
```bash
# Buat archive tanpa folder berikut:
# - node_modules/
# - .git/
# - storage/logs/
# - storage/framework/cache/
# - storage/framework/sessions/
# - storage/framework/views/

zip -r project.zip . -x "node_modules/*" ".git/*" "storage/logs/*" "storage/framework/cache/*" "storage/framework/sessions/*" "storage/framework/views/*"
```

## Langkah Deploy

### 1. Upload File ke cPanel
1. Login ke cPanel
2. Buka **File Manager**
3. Navigasi ke folder `public_html`
4. Upload file `project.zip`
5. Extract file zip

### 2. Konfigurasi Struktur Folder
```
public_html/
├── laravelapp/          # Folder aplikasi Laravel (kecuali public)
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── resources/
│   ├── routes/
│   ├── storage/
│   ├── vendor/
│   ├── .env
│   ├── artisan
│   └── composer.json
├── index.php            # Dari folder public Laravel
├── .htaccess           # Dari folder public Laravel
└── assets/             # CSS, JS dari folder public Laravel
```

### 3. Edit File index.php
Edit file `public_html/index.php`:
```php
<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Ubah path ke folder aplikasi
if (file_exists($maintenance = __DIR__.'/laravelapp/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Ubah path autoload
require __DIR__.'/laravelapp/vendor/autoload.php';

// Ubah path bootstrap
$app = require_once __DIR__.'/laravelapp/bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
```

### 4. Konfigurasi Environment (.env)
Edit file `public_html/laravelapp/.env`:
```env
APP_NAME="FliteCharter"
APP_ENV=production
APP_KEY=base64:your-app-key-here
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database (gunakan kredensial dari cPanel)
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

# Cache & Session
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Mail (opsional)
MAIL_MAILER=smtp
MAIL_HOST=mail.yourdomain.com
MAIL_PORT=587
MAIL_USERNAME=noreply@yourdomain.com
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"
```

### 5. Setup Database
1. Buka **MySQL Databases** di cPanel
2. Buat database baru
3. Buat user database
4. Assign user ke database dengan full privileges
5. Update kredensial di file `.env`

### 6. Jalankan Migration
Buka **Terminal** di cPanel dan jalankan:
```bash
cd public_html/laravelapp

# Install dependencies jika belum (opsional)
composer install --no-dev --optimize-autoloader

# Generate APP_KEY jika belum ada
php artisan key:generate --force

# Jalankan migration dan seeding
php artisan migrate --force
php artisan db:seed --force

# Create storage link
php artisan storage:link
```

### 7. Set Permissions
Gunakan **Terminal** di cPanel untuk set permission:
```bash
cd public_html/laravelapp

# Set permission untuk storage dan bootstrap/cache
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/

# Set permission untuk file tertentu
chmod 644 .env
find storage -type f -exec chmod 644 {} \;
find bootstrap/cache -type f -exec chmod 644 {} \;
```

## Optimisasi Production

### 1. Cache Konfigurasi
Gunakan **Terminal** di cPanel untuk optimisasi:
```bash
cd public_html/laravelapp

# Clear semua cache terlebih dahulu
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Cache untuk production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Optimize composer autoloader
composer dump-autoload --optimize --no-dev
```

### 2. File .htaccess
Pastikan file `.htaccess` di `public_html/` sudah benar:
```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

### 3. PHP Configuration
Buat file `.htaccess` di folder `laravelapp/` untuk keamanan:
```apache
# Deny access to all files in this directory
<Files "*">
    Order Deny,Allow
    Deny from all
</Files>
```

## Troubleshooting dengan Terminal

### Error 500
Gunakan **Terminal** di cPanel untuk debugging:
```bash
cd public_html/laravelapp

# Cek log error terbaru
tail -f storage/logs/laravel.log

# Cek status aplikasi
php artisan about

# Generate APP_KEY jika belum ada
php artisan key:generate --force

# Clear dan rebuild cache
php artisan optimize:clear
php artisan optimize
```

### File Not Found
```bash
# Cek permission dan struktur folder
ls -la public_html/
ls -la public_html/laravelapp/

# Cek ownership file
ls -la public_html/laravelapp/.env
```

### Database Connection Error
```bash
cd public_html/laravelapp

# Test koneksi database
php artisan migrate:status

# Cek konfigurasi database
php artisan config:show database

# Test koneksi dengan tinker
php artisan tinker
# Dalam tinker: DB::connection()->getPdo();
```

### Asset/CSS/JS Tidak Load
```bash
cd public_html/laravelapp

# Recreate storage link
php artisan storage:link

# Cek file asset yang dihasilkan
ls -la public/build/

# Jika perlu rebuild asset
npm run build
```

### Performance Issues
```bash
cd public_html/laravelapp

# Cek performa dengan debug
php artisan route:list
php artisan config:show app

# Monitor query database
php artisan db:monitor --databases=mysql
```

## Maintenance Mode

Via **Terminal** di cPanel, untuk mengaktifkan maintenance mode:
```bash
cd public_html/laravelapp
php artisan down --message="Sedang maintenance" --retry=60
```

Untuk menonaktifkan:
```bash
cd public_html/laravelapp
php artisan up
```

## Update Aplikasi

1. Backup database dan file
2. Upload file baru (timpa yang lama)
3. Jalankan migration jika ada perubahan schema
4. Clear cache dan optimize

Via **Terminal** di cPanel:
```bash
cd public_html/laravelapp
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Keamanan

1. **Jangan expose folder aplikasi** - pastikan hanya folder public yang accessible
2. **Gunakan HTTPS** - aktifkan SSL certificate
3. **Update reguler** - selalu update Laravel dan dependencies
4. **Monitor logs** - cek error logs secara berkala
5. **Backup rutin** - backup database dan file aplikasi

## Catatan Penting untuk cPanel dengan Terminal

- **Keuntungan Terminal di cPanel:**
  - Dapat menjalankan perintah Artisan langsung
  - Debugging lebih mudah dengan real-time logging
  - Optimisasi cache bisa dilakukan langsung
  - Install dependencies via Composer

- **Persyaratan Hosting:**
  - PHP 8.2 atau lebih tinggi
  - Extension PHP: BCMath, Ctype, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML
  - Akses Terminal/SSH
  - Composer tersedia di server

- **Best Practices:**
  - Selalu backup sebelum deploy
  - Gunakan environment production yang benar
  - Monitor log aplikasi secara berkala
  - Gunakan cache untuk optimisasi performa
  - Set queue driver sesuai kebutuhan (sync/database)

## Perintah Cepat untuk Maintenance

```bash
# Quick deploy after upload
cd public_html/laravelapp
composer install --no-dev --optimize-autoloader
php artisan key:generate --force
php artisan migrate --force
php artisan optimize

# Quick troubleshoot
php artisan about
tail -f storage/logs/laravel.log
php artisan config:show database

# Quick cleanup
php artisan optimize:clear
php artisan optimize
```