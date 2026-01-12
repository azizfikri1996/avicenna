<?php
// Simple test to check if POST works
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];
$input = file_get_contents('php://input');
$data = json_decode($input, true);

echo json_encode([
    'success' => true,
    'method' => $method,
    'received_data' => $data,
    'raw_input' => $input,
    'timestamp' => date('Y-m-d H:i:s')
]);
?>
