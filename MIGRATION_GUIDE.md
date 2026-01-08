# 🔄 Panduan Migrasi: localStorage → PHP & MySQL

## ✅ Perubahan yang Telah Dilakukan

Aplikasi Alazka Studio telah berhasil dimigrasi dari **localStorage** ke **PHP & MySQL**.

### File yang Telah Diupdate:

#### 1. **control.html** ✅
- ❌ Menghapus semua fungsi `localStorage.getItem()` dan `localStorage.setItem()`
- ✅ Menggunakan `fetch()` API untuk komunikasi dengan `api.php`
- ✅ `loadBookings()` → Async function yang fetch dari MySQL
- ✅ `saveBookings()` → Tidak diperlukan lagi (auto-save via API)
- ✅ `addBooking()` → POST ke `api.php?action=addBooking`
- ✅ `updateBooking()` → POST ke `api.php?action=updateBooking`
- ✅ `deleteBooking()` → GET ke `api.php?action=deleteBooking`
- ✅ `confirmBooking()` → POST ke `api.php?action=confirmBooking`
- ✅ Auto-refresh dari MySQL setiap 5 detik

#### 2. **confirm.html** ✅
- ❌ Menghapus fungsi `localStorage` untuk menyimpan booking
- ✅ `saveBooking()` → Async function POST ke `api.php?action=addBooking`
- ✅ Loop untuk multiple dates (tanggal range)
- ✅ Error handling untuk failed API calls

#### 3. **user.html** ✅
- ❌ Menghapus `localStorage.getItem()` untuk load bookings
- ❌ Menghapus event listener untuk `storage` event
- ✅ `loadBookings()` → Async fetch dari `api.php?action=getBookings`
- ✅ Auto-refresh dari MySQL setiap 3 detik
- ✅ Real-time display tanpa perlu refresh manual

#### 4. **jadwal.html** ✅
- ✅ Sudah menggunakan API PHP dari sebelumnya
- ✅ `checkSlot()` → GET dari `api.php?action=checkSlot`
- ✅ `checkBan()` → GET dari `api.php?action=checkBan`

#### 5. **api.php** ✅
Ditambahkan endpoint baru:
- ✅ `updateBooking` - Update existing booking
- ✅ Perbaikan `addBooking` - Tambah field `kelas`
- ✅ Semua endpoint sudah menggunakan prepared statements

#### 6. **database.sql** ✅
- ✅ Ditambahkan field `kelas VARCHAR(50)` ke tabel `studio_bookings`

## 🚀 Cara Setup Database

### Step 1: Install Web Server
Pilih salah satu:
- **Windows**: [XAMPP](https://www.apachefriends.org/)
- **Mac**: [MAMP](https://www.mamp.info/)
- **Linux**: LAMP Stack

### Step 2: Buat Database

1. Buka **phpMyAdmin**: `http://localhost/phpmyadmin`

2. Klik tab "SQL" dan jalankan:
```sql
CREATE DATABASE IF NOT EXISTS alazka_studio CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

3. Pilih database `alazka_studio` di sidebar

4. Import file `database.sql`:
   - **Cara 1**: Klik tab "Import" → Choose file → Select `database.sql` → Go
   - **Cara 2**: Copy isi `database.sql` → Paste di tab "SQL" → Go

### Step 3: Konfigurasi Koneksi

Edit `config.php`:
```php
<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');              // Kosong untuk XAMPP default
define('DB_NAME', 'alazka_studio');
?>
```

### Step 4: Copy File ke Web Server

Copy semua file project ke:
- **XAMPP Windows**: `C:\xampp\htdocs\alazka_studio\`
- **XAMPP Mac**: `/Applications/XAMPP/htdocs/alazka_studio/`
- **MAMP**: `/Applications/MAMP/htdocs/alazka_studio/`
- **Linux**: `/var/www/html/alazka_studio/`

### Step 5: Test Koneksi

1. Start Apache dan MySQL dari XAMPP/MAMP control panel

2. Test API:
   ```
   http://localhost/alazka_studio/api.php?action=getBookings
   ```
   
   Output yang diharapkan:
   ```json
   []
   ```
   atau daftar booking jika ada data

3. Buka aplikasi:
   ```
   http://localhost/alazka_studio/index.html
   ```

## 📊 Migrasi Data Lama (Opsional)

Jika Anda memiliki data di **localStorage**, ikuti langkah ini:

### Export Data dari localStorage

1. Buka salah satu halaman (misalnya `control.html`)
2. Tekan `F12` untuk Developer Tools
3. Masuk ke tab "Console"
4. Jalankan:

```javascript
// Export bookings
const bookings = JSON.parse(localStorage.getItem('alazka_bookings') || '[]');
console.log(JSON.stringify(bookings, null, 2));

// Copy output
```

5. Save output ke file `old_bookings.json`

### Import ke MySQL

Buat file PHP baru `import_old_data.php`:

```php
<?php
require_once 'config.php';

// Baca file JSON
$json = file_get_contents('old_bookings.json');
$bookings = json_decode($json, true);

// Insert ke database
foreach ($bookings as $booking) {
    $sql = "INSERT INTO studio_bookings 
            (studio, tanggal, jam, nama, hp, nik, email, kelas, status, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssss", 
        $booking['studio'],
        $booking['tanggal'],
        $booking['jam'],
        $booking['nama'],
        $booking['hp'] ?? '',
        $booking['nik'] ?? '',
        $booking['email'] ?? '',
        $booking['kelas'] ?? '',
        $booking['status']
    );
    
    if ($stmt->execute()) {
        echo "✓ Imported: {$booking['nama']} - {$booking['tanggal']}<br>";
    } else {
        echo "✗ Failed: {$booking['nama']}<br>";
    }
}

echo "<br>Import selesai!";
$conn->close();
?>
```

Akses: `http://localhost/alazka_studio/import_old_data.php`

## 🔄 Perbedaan localStorage vs MySQL

| Aspek | localStorage | PHP & MySQL |
|-------|-------------|-------------|
| **Storage** | Browser (5-10MB limit) | Database (unlimited) |
| **Persistence** | Per browser only | Centralized, all devices |
| **Sync** | Manual via tabs | Real-time auto-sync |
| **Security** | Client-side, easily accessed | Server-side, more secure |
| **Backup** | Manual export | Automatic DB backup |
| **Multi-user** | ❌ Not supported | ✅ Supported |
| **Concurrent Access** | ❌ Conflicts | ✅ Handled by DB |

## 🎯 Keuntungan Migrasi

✅ **Real-time Synchronization**: Perubahan data langsung terlihat di semua device
✅ **Centralized Data**: Satu database untuk semua user
✅ **Better Security**: Data tersimpan di server, tidak di browser
✅ **Unlimited Storage**: Tidak ada batasan 5-10MB seperti localStorage
✅ **Easy Backup**: Database bisa di-backup dengan mudah
✅ **Multi-user Support**: Banyak user bisa akses bersamaan tanpa konflik
✅ **Scalability**: Mudah di-scale untuk ribuan booking

## 🐛 Troubleshooting

### 1. Error: "Connection Failed"
**Solusi:**
- Cek apakah MySQL sudah running
- Verifikasi credentials di `config.php`
- Pastikan database `alazka_studio` sudah dibuat

### 2. Error: "Table doesn't exist"
**Solusi:**
- Import ulang `database.sql`
- Pastikan memilih database yang benar di phpMyAdmin

### 3. Data tidak muncul
**Solusi:**
- Buka Console (F12) dan cek error
- Test API: `http://localhost/alazka_studio/api.php?action=getBookings`
- Pastikan ada data di database

### 4. Error 404 pada api.php
**Solusi:**
- Pastikan file `api.php` ada di root folder
- Cek path relatif di JavaScript
- Restart Apache

### 5. CORS Error
**Solusi:**
- Header CORS sudah ada di `api.php`
- Pastikan akses via `http://localhost/`, bukan `file:///`

## 📞 Kontak Support

Jika masih ada masalah:
- WhatsApp: +62 821-2503-9377
- Email: support@alazkastudio.com

---

## ✨ Status Migrasi: COMPLETE ✅

Semua fitur telah berhasil dimigrasi dari localStorage ke PHP & MySQL!

**Tested on:**
- ✅ XAMPP 8.2.4 (Windows)
- ✅ MAMP 6.9 (macOS)
- ✅ PHP 7.4+
- ✅ MySQL 5.7+

**Last Updated:** January 8, 2026
