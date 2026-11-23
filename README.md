# Task Management System

Aplikasi manajemen tugas berbasis Laravel 13 dan Livewire 4 dengan fitur pelacakan status, prioritas, soft delete (recycle bin), dan caching statistik dashboard.

## Fitur

- **Manajemen Tugas**: Buat, perbarui, dan hapus tugas dengan modal interaktif tanpa reload halaman.
- **Status dan Prioritas**: Pelacakan status (*Pending*, *In Progress*, *Completed*) serta level prioritas (*Low*, *Medium*, *High*).
- **Tenggat Waktu**: Penjadwalan batas waktu (*due date*) untuk setiap tugas.
- **Recycle Bin (Soft Delete)**: Tugas yang dihapus masuk ke tab sampah, dapat dipulihkan (*restore*) atau dihapus permanen (*force delete*).
- **Filter dan Sorting**: Penyaringan berdasarkan status atau prioritas, serta pengurutan data berdasarkan tanggal pembuatan.
- **Dashboard Stats Cache**: Statistik ringkasan tugas tersimpan di cache per pengguna untuk performa optimal.
- **Autentikasi Pengguna**: Sistem register, login, dan logout dengan proteksi route per akun.

## Kebutuhan Sistem

- PHP 8.3 atau lebih baru
- Composer
- Node.js dan NPM
- SQLite (default) atau MySQL

## Instalasi

### 1. Klon Repositori

```bash
git clone https://github.com/melonmas/dkp-case3.git
cd dkp-case3
```

### 2. Setup Otomatis

Proyek ini sudah menyediakan skrip setup di `composer.json`:

```bash
composer run setup
```

Perintah di atas menjalankan urutan instalasi:
1. `composer install`
2. Salin `.env.example` ke `.env`
3. `php artisan key:generate`
4. `php artisan migrate --force`
5. `npm install`
6. `npm run build`

### 3. Setup Manual (Alternatif)

Jika ingin menjalankan langkah-langkah secara terpisah:

```bash
# Install dependensi PHP
composer install

# Siapkan file konfigurasi environment
cp .env.example .env

# Generate application key
php artisan key:generate

# Siapkan database SQLite dan jalankan migrasi
touch database/database.sqlite
php artisan migrate

# Install dependensi frontend dan build aset
npm install
npm run build
```

## Menjalankan Aplikasi

Jalankan server aplikasi dan Vite sekaligus:

```bash
composer run dev
```

Atau jalankan secara terpisah:

```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Vite server
npm run dev
```

Akses aplikasi melalui `http://localhost:8000`.

## Pengujian dan Kualitas Kode

Aplikasi ini dilengkapi pengujian otomatis menggunakan Pest serta pengecekan gaya kode:

```bash
# Menjalankan test suite
php artisan test

# Memeriksa code style (Pint)
composer run lint:check

# Merapikan code style secara otomatis
composer run lint

# Analisis tipe statis (PHPStan / Larastan)
composer run types:check
```

## Arsitektur dan Teknologi

- **Backend**: Laravel 13, PHP 8.3+
- **Frontend / Reaktivitas**: Livewire 4, Livewire Flux, Tailwind CSS
- **Testing**: Pest 5
- **Database**: MySQL
