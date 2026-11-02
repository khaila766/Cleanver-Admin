# CLEANVER ADMIN — Product & Inventory Management

Aplikasi PHP Native + MySQL untuk praktikum Komputasi Awan. Fitur: dashboard, CRUD produk, CRUD kategori, pencatatan stok masuk/keluar, dan riwayat stok.

## 1. Menjalankan di XAMPP
1. Ekstrak folder `Cleanver-Admin` ke `C:\xampp\htdocs\`.
2. Jalankan Apache dan MySQL dari XAMPP Control Panel.
3. Buka `http://localhost/phpmyadmin`.
4. Pilih tab **Import**, pilih file `database.sql`, lalu klik **Import**.
5. Pastikan konfigurasi database di `config/database.php` sesuai:
   - host: `127.0.0.1`
   - port: `3306`
   - database: `cleanver_admin`
   - username: `root`
   - password: kosong (default XAMPP).
6. Buka `http://localhost/Cleanver-Admin/`.

## 2. Struktur database
- `categories`: data kategori.
- `products`: data produk dan stok terkini.
- `stock_transactions`: riwayat stok masuk/keluar.

## 3. Pengujian CRUD
- Produk: tambah, tampilkan/cari, edit, hapus.
- Kategori: tambah, tampilkan, edit, hapus.
- Stok: catat stok masuk/keluar. Sistem menolak stok keluar jika stok tidak cukup.
- Riwayat: lihat semua aktivitas dan filter jenis transaksi.

## 4. Deploy Azure App Service
Modul praktikum meminta PHP Native dan Azure App Service. Sebelum deploy:
1. Siapkan database MySQL yang dapat diakses dari Azure App Service (contoh: layanan MySQL terkelola/host database yang mengizinkan koneksi dari App Service). Database lokal XAMPP tidak otomatis dapat diakses oleh Azure.
2. Buat database dan import `database.sql` pada server MySQL tersebut.
3. Atur environment variables pada App Service > Settings > Environment variables:
   - `DB_HOST` = hostname server MySQL
   - `DB_PORT` = port MySQL (umumnya 3306)
   - `DB_NAME` = nama database
   - `DB_USER` = username database
   - `DB_PASS` = password database
4. Push seluruh isi folder ini ke root repository GitHub (file `index.php` harus berada di root).
5. Buat Web App Azure dengan Publish: Code, Runtime: PHP 8.x, OS: Linux, lalu hubungkan GitHub melalui Deployment Center sesuai modul.
6. Setelah deployment berhasil, buka Default domain dari Overview.

> Catatan: Azure App Service dan database adalah dua layanan berbeda. Jangan memasukkan kredensial database ke repository GitHub. Gunakan App Settings / environment variables. Pilihan layanan database dan konfigurasi firewall/SSL bergantung pada provider database yang digunakan.

## 5. Catatan keamanan dan batasan
- Form POST menggunakan token CSRF dan query PDO prepared statements.
- Ini merupakan prototipe praktikum, belum memiliki autentikasi/login admin, upload gambar, atau sistem pembayaran.
- Untuk penggunaan produksi, tambahkan autentikasi, pembatasan akses, backup, HTTPS, dan pengelolaan rahasia yang lebih ketat.
