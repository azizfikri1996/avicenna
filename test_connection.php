<?php
echo "<h2>🔍 Testing Database Connection...</h2>";
echo "<hr>";

$dbname = 'alazka_studio';

// Test 1: Password kosong (XAMPP default)
echo "<h3>Test 1: Password Kosong</h3>";
$conn1 = new mysqli('localhost', 'root', '');
if ($conn1->connect_error) {
    echo "❌ GAGAL dengan password kosong<br>";
    echo "Error: " . $conn1->connect_error . "<br>";
} else {
    echo "✅ <strong>BERHASIL dengan password kosong!</strong><br>";
    echo "→ Setting config.php: <code>DB_PASS = ''</code><br>";
    
    // Cek database
    $result = $conn1->query("SHOW DATABASES LIKE '$dbname'");
    if ($result && $result->num_rows > 0) {
        echo "✅ Database '$dbname' sudah ada!<br>";
    } else {
        echo "⚠️ Database '$dbname' belum ada. Buat dulu di phpMyAdmin!<br>";
    }
    $conn1->close();
}
echo "<hr>";

// Test 2: Password 'root' (MAMP default)
echo "<h3>Test 2: Password 'root'</h3>";
$conn2 = new mysqli('localhost', 'root', 'root');
if ($conn2->connect_error) {
    echo "❌ GAGAL dengan password 'root'<br>";
    echo "Error: " . $conn2->connect_error . "<br>";
} else {
    echo "✅ <strong>BERHASIL dengan password 'root'!</strong><br>";
    echo "→ Setting config.php: <code>DB_PASS = 'root'</code><br>";
    
    // Cek database
    $result = $conn2->query("SHOW DATABASES LIKE '$dbname'");
    if ($result && $result->num_rows > 0) {
        echo "✅ Database '$dbname' sudah ada!<br>";
    } else {
        echo "⚠️ Database '$dbname' belum ada. Buat dulu di phpMyAdmin!<br>";
    }
    $conn2->close();
}
echo "<hr>";

echo "<h3>📋 Kesimpulan:</h3>";
echo "Gunakan setting yang bertanda ✅ BERHASIL di file config.php Anda!";
?>
