<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

// Test 1: Include config
require_once 'config.php';

// Test 2: Check connection
if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

// Test 3: Check if table exists
$tableCheck = $conn->query("SHOW TABLES LIKE 'students'");

if (!$tableCheck || $tableCheck->num_rows === 0) {
    die(json_encode(['error' => 'Students table not found']));
}

// Test 4: Get NIS from query string
$nis = isset($_GET['nis']) ? $_GET['nis'] : '';

if (empty($nis)) {
    die(json_encode(['error' => 'NIS parameter is required']));
}

// Test 5: Query database
$sql = "SELECT nis, nama, kelas FROM students WHERE nis = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die(json_encode(['error' => 'Prepare failed: ' . $conn->error]));
}

$stmt->bind_param("s", $nis);

if (!$stmt->execute()) {
    die(json_encode(['error' => 'Execute failed: ' . $stmt->error]));
}

// Bind result variables
$stmt->bind_result($result_nis, $result_nama, $result_kelas);

if ($stmt->fetch()) {
    $response = [
        'success' => true,
        'nis' => $result_nis,
        'nama' => $result_nama,
        'kelas' => $result_kelas
    ];
} else {
    $response = [
        'success' => false,
        'error' => 'Student not found',
        'nis_searched' => $nis
    ];
}

$stmt->close();
$conn->close();

echo json_encode($response);
?>
