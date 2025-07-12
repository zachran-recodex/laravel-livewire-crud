# Laravel Livewire CRUD Starter Kit

![Laravel Livewire CRUD Starter Kit](https://via.placeholder.com/1200x600?text=Laravel+Livewire+CRUD+Starter+Kit)

Laravel Livewire CRUD Starter Kit adalah fondasi yang kuat untuk membangun aplikasi web dengan cepat, berfokus pada keamanan tingkat enterprise dan performa optimal. Proyek ini mengintegrasikan **Flux UI components** untuk antarmuka pengguna yang modern dan **Spatie Laravel Permission** untuk manajemen peran dan izin yang komprehensif.

## Fitur Utama

*   **Laravel 12.x** dengan **PHP 8.2+**: Fondasi PHP modern dan stabil.
*   **Livewire Volt 1.7**: Komponen Livewire dalam satu file untuk pengembangan yang efisien.
*   **Flux UI 2.1**: Pustaka komponen premium untuk UI yang indah dan responsif.
*   **Spatie Laravel Permission**: Sistem otorisasi berbasis peran dan izin yang kuat.
*   **PestPHP**: Framework pengujian yang elegan dan cepat.
*   **Vite** dengan **Tailwind CSS 4.x**: Tooling frontend modern untuk aset yang dioptimalkan.
*   **Manajemen Pengguna, Peran, dan Izin**: CRUD lengkap untuk administrasi.
*   **Manajemen Produk**: Contoh modul CRUD untuk produk.
*   **Dashboard Interaktif**: Dengan analitik dan integrasi Chart.js.
*   **Form Objects**: Abstraksi formulir Livewire v3 untuk maintainability.
*   **Computed Properties**: Optimasi performa Livewire dengan caching query database.

## Persyaratan Sistem

Pastikan Anda memiliki perangkat lunak berikut terinstal di sistem Anda:

*   PHP >= 8.2
*   Composer
*   Node.js >= 18
*   Bun (direkomendasikan untuk manajemen paket frontend)
*   MySQL, PostgreSQL, atau SQLite (untuk database)
*   **Docker dan Docker Compose (opsional, direkomendasikan untuk lingkungan pengembangan yang konsisten)**

## Panduan Instalasi

Ikuti langkah-langkah ini untuk menyiapkan proyek di lingkungan lokal Anda:

1.  **Kloning Repositori:**
    ```bash
    git clone https://github.com/zachran-recodex/laravel-livewire-crud.git
    cd laravel-livewire-crud
    ```

2.  **Konfigurasi Environment:**
    Salin file `.env.example` ke `.env` dan sesuaikan konfigurasi database serta pengaturan lainnya.
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

### Menggunakan Docker (Direkomendasikan)

Untuk lingkungan pengembangan yang konsisten dan terisolasi, Anda dapat menggunakan Docker dan Docker Compose:

1.  **Bangun dan Jalankan Kontainer:**
    ```bash
    docker-compose up -d --build
    ```
    Ini akan membangun image Docker, membuat kontainer untuk Nginx, PHP-FPM, dan MySQL, lalu menjalankannya di latar belakang.

2.  **Instal Dependensi PHP (di dalam kontainer PHP):**
    ```bash
    docker-compose exec php composer install
    ```

3.  **Instal Dependensi JavaScript (di dalam kontainer PHP):**
    ```bash
    docker-compose exec php bun install
    ```
    *Atau jika Anda menggunakan npm:*
    ```bash
    docker-compose exec php npm install
    ```

4.  **Jalankan Migrasi dan Seeding (di dalam kontainer PHP):**
    ```bash
    docker-compose exec php php artisan migrate:fresh --seed
    ```
    *Kredensial Admin Default (setelah seeding):*
    *   Email: `admin@example.com`
    *   Password: `password`

5.  **Akses Aplikasi:**
    Aplikasi akan tersedia di `http://localhost`.

### Instalasi Manual (Tanpa Docker)

Jika Anda tidak ingin menggunakan Docker, ikuti langkah-langkah ini:

1.  **Instal Dependensi PHP:**
    ```bash
    composer install
    ```

2.  **Instal Dependensi JavaScript (menggunakan Bun):**
    ```bash
    bun install
    ```
    *Atau jika Anda menggunakan npm:*
    ```bash
    npm install
    ```

3.  **Konfigurasi Database:**
    Edit file `.env` untuk mengkonfigurasi koneksi database Anda.

4.  **Jalankan Migrasi dan Seeding:**
    ```bash
    php artisan migrate:fresh --seed
    ```
    *Kredensial Admin Default (setelah seeding):*
    *   Email: `admin@example.com`
    *   Password: `password`

## Menjalankan Aplikasi

Untuk memulai server pengembangan dan layanan terkait:

*   **Mulai Lingkungan Pengembangan Penuh (Server, Antrean, Log, Vite):**
    ```bash
    composer run dev
    ```

*   **Layanan Individual:**
    ```bash
    php artisan serve         # Server HTTP
    php artisan queue:listen --tries=1 # Pendengar antrean
    php artisan pail --timeout=0 # Pemantau log
    npm run dev               # Server Vite untuk hot reloading aset
    ```

## Pengujian

Proyek ini menggunakan PestPHP untuk pengujian.

*   **Jalankan Semua Tes:**
    ```bash
    composer run test
    ```

*   **Jalankan Suite Tes Spesifik:**
    ```bash
    php artisan test --testsuite=Feature
    php artisan test --testsuite=Unit
    ```

*   **Jalankan File Tes Tunggal:**
    ```bash
    php artisan test tests/Feature/DashboardTest.php
    ```

## Membangun Aset

*   **Pengembangan (dengan Hot Reloading):**
    ```bash
    npm run dev
    ```

*   **Produksi (Optimized Build):**
    ```bash
    npm run build
    ```

## Kualitas Kode

*   **Format Kode (menggunakan Laravel Pint):**
    ```bash
    ./vendor/bin/pint
    ```

## Struktur Direktori Utama

*   `app/Livewire/`: Berisi semua komponen Livewire, termasuk komponen admin dan dashboard.
*   `resources/views/`: File Blade untuk tampilan, termasuk override komponen Flux UI.
*   `resources/js/`: Kode JavaScript, termasuk integrasi Chart.js.
*   `resources/css/`: File CSS, terutama Tailwind CSS.
*   `database/migrations/`: Skema database.
*   `database/seeders/`: Data awal untuk database.
*   `tests/`: Semua file pengujian (Feature dan Unit).
*   `docs/`: Dokumentasi tambahan dan praktik terbaik.

## Lisensi

Proyek ini dilisensikan di bawah Lisensi MIT. Lihat file `LICENSE` untuk detail lebih lanjut.
