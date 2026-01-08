# Alazka Studio - Migrasi dari Firebase ke PHP/MySQL

## Panduan Instalasi dan Konfigurasi

### 1. Persiapan Database

1. Buka **PhpMyAdmin** di browser Anda (biasanya: `http://localhost/phpmyadmin`)
2. Buat database baru atau gunakan file `database.sql` yang sudah disediakan
3. Cara menggunakan file SQL:
   - Klik tab "SQL" di PhpMyAdmin
   - Copy semua isi dari file `database.sql`
   - Paste ke text area dan klik "Go"
   - Database `alazka_studio` dan tabel-tabelnya akan otomatis dibuat

### 2. Konfigurasi Database

Edit file `config.php` dan sesuaikan dengan pengaturan database Anda:

```php
define('DB_HOST', 'localhost');      // Host database (biasanya localhost)
define('DB_USER', 'root');           // Username database
define('DB_PASS', '');               // Password database (kosong untuk XAMPP default)
define('DB_NAME', 'alazka_studio');  // Nama database
```

### 3. Struktur File

File-file yang telah dibuat/diupdate:

**File Backend (PHP):**
- `config.php` - Konfigurasi koneksi database
- `api.php` - REST API untuk semua operasi database

**File Frontend (HTML):**
- `index.html` - Halaman pilih studio (sudah tidak perlu Firebase)
- `jadwal.html` - Form booking dengan validasi PHP API
- `confirm.html` - Halaman konfirmasi booking dengan PHP API
- `user.html` - Live display studio menggunakan PHP API
- `control.html` - Control panel admin menggunakan PHP API

**File Database:**
- `database.sql` - Schema database MySQL

### 4. Menjalankan Aplikasi

1. **Pastikan XAMPP/WAMP/MAMP sudah running:**
   - Apache harus aktif
   - MySQL harus aktif

2. **Letakkan semua file di folder htdocs:**
   - Untuk XAMPP: `C:\xampp\htdocs\alazka-studio\`
   - Untuk MAMP: `/Applications/MAMP/htdocs/alazka-studio/`

3. **Akses aplikasi via browser:**
   - Halaman utama: `http://localhost/alazka-studio/index.html`
   - Control Panel: `http://localhost/alazka-studio/control.html`
   - Live Display: `http://localhost/alazka-studio/user.html`

### 5. API Endpoints

API tersedia di `api.php` dengan parameter `action`:

- `getBookings` - Ambil semua booking (optional: &date=YYYY-MM-DD)
- `addBooking` - Tambah booking baru (POST)
- `checkSlot` - Cek ketersediaan slot
- `checkBan` - Cek apakah user dibanned
- `confirmBooking` - Konfirmasi booking (POST)
- `deleteBooking` - Hapus booking
- `getStats` - Ambil statistik
- `getBanList` - Ambil daftar user yang dibanned
- `addBan` - Tambah user ke ban list (POST)
- `removeBan` - Hapus user dari ban list

Contoh penggunaan:
```
GET: http://localhost/alazka-studio/api.php?action=getBookings
GET: http://localhost/alazka-studio/api.php?action=checkSlot&studio=Studio%201&tanggal=2026-01-07&jam=09:00-10:00
POST: http://localhost/alazka-studio/api.php?action=addBooking
```

### 6. Tabel Database

**Tabel: studio_bookings**
- `id` - Auto increment primary key
- `studio` - Nama studio
- `tanggal` - Tanggal booking (DATE)
- `jam` - Jam booking (VARCHAR)
- `nama` - Nama pemesan
- `hp` - Nomor HP
- `nik` - NIK pemesan
- `email` - Email pemesan
- `status` - Status booking (pending/confirmed/cancelled/completed)
- `created_at` - Timestamp pembuatan
- `updated_at` - Timestamp update terakhir

**Tabel: ban_list**
- `id` - Auto increment primary key
- `nik` - NIK yang dibanned (UNIQUE)
- `nama` - Nama yang dibanned
- `reason` - Alasan ban
- `banned_until` - Sampai kapan dibanned (DATETIME)
- `created_at` - Timestamp pembuatan

### 7. Perubahan dari Firebase

**Yang berubah:**
- ❌ Tidak perlu Firebase SDK lagi
- ❌ Tidak perlu Firebase Configuration
- ❌ Tidak perlu Internet untuk database
- ✅ Semua data disimpan di MySQL lokal
- ✅ Akses data lebih cepat (lokal)
- ✅ Bisa dikelola dengan PhpMyAdmin
- ✅ Support query SQL kompleks

**Yang tetap sama:**
- ✅ Interface/tampilan tidak berubah
- ✅ Fitur-fitur tetap sama
- ✅ Flow aplikasi tetap sama

### 8. Troubleshooting

**Error: "Database connection failed"**
- Pastikan MySQL sudah running di XAMPP/WAMP
- Cek konfigurasi di `config.php`
- Pastikan database `alazka_studio` sudah dibuat

**Error: "Cannot read properties"**
- Pastikan semua file berada di folder yang sama
- Cek console browser (F12) untuk detail error

**Data tidak muncul:**
- Buka PhpMyAdmin dan cek apakah ada data di tabel `studio_bookings`
- Cek Network tab di browser (F12) untuk melihat response API

**CORS Error:**
- Pastikan semua file diakses via `http://localhost`
- Jangan buka file dengan `file://` protocol

### 9. Tips Penggunaan

1. **Backup Database:**
   - Export database secara berkala via PhpMyAdmin
   - Simpan file SQL di tempat aman

2. **Testing:**
   - Gunakan control panel untuk test menambah/edit data
   - Cek live display untuk melihat perubahan real-time

3. **Development:**
   - Gunakan browser console (F12) untuk debug
   - Cek Network tab untuk melihat API response

### 10. Keamanan (Untuk Production)

Jika ingin deploy ke server production:

1. **Update config.php:**
   - Ganti password database
   - Jangan gunakan user 'root'

2. **Tambahkan .htaccess:**
   ```apache
   # Protect config.php
   <Files "config.php">
       Order Allow,Deny
       Deny from all
   </Files>
   ```

3. **Enable HTTPS:**
   - Gunakan SSL certificate
   - Update API calls ke HTTPS

4. **Input Validation:**
   - API sudah menggunakan prepared statements (aman dari SQL injection)
   - Tambahkan validasi tambahan jika perlu

---

## Kontak

Jika ada pertanyaan atau masalah, hubungi admin studio.

**Selamat menggunakan sistem baru! 🎉**
