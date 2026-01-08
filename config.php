<?php
// Database Configuration
// Production settings (cPanel/shared hosting)
define('DB_HOST', 'localhost');  
define('DB_USER', 'alazkasch_alazka_studio');
define('DB_PASS', 'Alazka2025');
define('DB_NAME', 'alazkasch_alazka_studio');

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]));
}

// Set charset to UTF-8
$conn->set_charset("utf8mb4");

// Timezone
date_default_timezone_set('Asia/Jakarta');
?>
