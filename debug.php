<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>🔍 Debug Database Connection</h2>";
echo "<hr>";

// Test 1: Cek setting
echo "<h3>1️⃣ Setting Database:</h3>";
echo "Host: localhost<br>";
echo "User: root<br>";
echo "Password: [testing...]<br>";
echo "Database: alazka_studio<br>";
echo "<hr>";

// Test 2: Koneksi tanpa select database
echo "<h3>2️⃣ Test Koneksi ke MySQL:</h3>";
$conn = new mysqli('localhost', 'root', '');
if ($conn->connect_error) {
    echo "❌ GAGAL konek ke MySQL<br>";
    echo "Error: " . $conn->connect_error . "<br>";
    echo "<strong>Solusi:</strong> Pastikan XAMPP MySQL sudah running!<br>";
} else {
    echo "✅ Berhasil konek ke MySQL<br>";
}
echo "<hr>";

// Test 3: Cek apakah database ada
echo "<h3>3️⃣ Cek Database 'alazka_studio':</h3>";
if ($conn->connect_error) {
    echo "⚠️ Skip test (koneksi gagal)<br>";
} else {
    $result = $conn->query("SHOW DATABASES LIKE 'alazka_studio'");
    if ($result && $result->num_rows > 0) {
        echo "✅ Database 'alazka_studio' <strong>SUDAH ADA</strong><br>";
        
        // Cek tabel
        $conn->select_db('alazka_studio');
        $tables = $conn->query("SHOW TABLES");
        if ($tables && $tables->num_rows > 0) {
            echo "✅ Tabel yang ada:<br>";
            while ($row = $tables->fetch_array()) {
                echo "   - " . $row[0] . "<br>";
            }
        } else {
            echo "⚠️ Database ada tapi TIDAK ADA TABEL!<br>";
            echo "<strong>Solusi:</strong> Jalankan SQL untuk buat tabel!<br>";
        }
    } else {
        echo "❌ Database 'alazka_studio' <strong>BELUM ADA</strong><br>";
        echo "<strong>Solusi:</strong> Buat database dulu di phpMyAdmin!<br>";
    }
}
echo "<hr>";

// Test 4: Koneksi langsung ke database
echo "<h3>4️⃣ Test Koneksi Langsung ke Database:</h3>";
$conn2 = new mysqli('localhost', 'root', '', 'alazka_studio');
if ($conn2->connect_error) {
    echo "❌ GAGAL konek ke database 'alazka_studio'<br>";
    echo "Error: " . $conn2->connect_error . "<br>";
    echo "<strong>Ini penyebab error di aplikasi Anda!</strong><br>";
} else {
    echo "✅ Berhasil konek ke database 'alazka_studio'<br>";
    echo "<strong>Koneksi OK! Seharusnya aplikasi bisa jalan.</strong><br>";
}
echo "<hr>";

// Test 5: Test insert data
echo "<h3>5️⃣ Test Insert Data ke Tabel:</h3>";
if ($conn2->connect_error) {
    echo "⚠️ Skip test (koneksi gagal)<br>";
} else {
    $test_sql = "INSERT INTO studio_bookings (studio, tanggal, jam, nama, hp, nik, email, kelas, status) 
                 VALUES ('Test Studio', '2026-01-10', '10:00-12:00', 'Test User', '08123456789', '1234567890', 'test@test.com', 'Test', 'pending')";
    
    if ($conn2->query($test_sql)) {
        echo "✅ <strong>BERHASIL INSERT DATA!</strong><br>";
        echo "Data test berhasil masuk ke database.<br>";
        echo "ID: " . $conn2->insert_id . "<br>";
        
        // Hapus data test
        $conn2->query("DELETE FROM studio_bookings WHERE nama = 'Test User'");
        echo "Data test sudah dihapus.<br>";
    } else {
        echo "❌ GAGAL INSERT DATA<br>";
        echo "Error: " . $conn2->error . "<br>";
    }
}

echo "<hr>";
echo "<h3>📋 Kesimpulan:</h3>";
echo "Jika semua test ✅, aplikasi seharusnya jalan.<br>";
echo "Jika ada ❌, ikuti solusi yang diberikan.<br>";

$conn->close();
$conn2->close();
?>
