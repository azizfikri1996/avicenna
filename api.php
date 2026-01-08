<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Handle different API endpoints
switch($action) {
    case 'getBookings':
        getBookings($conn);
        break;
    
    case 'addBooking':
        addBooking($conn);
        break;
    
    case 'checkSlot':
        checkSlot($conn);
        break;
    
    case 'checkBan':
        checkBan($conn);
        break;
    
    case 'confirmBooking':
        confirmBooking($conn);
        break;
    
    case 'deleteBooking':
        deleteBooking($conn);
        break;
    
    case 'updateBooking':
        updateBooking($conn);
        break;
    
    case 'getStats':
        getStats($conn);
        break;
    
    case 'getBanList':
        getBanList($conn);
        break;
    
    case 'addBan':
        addBan($conn);
        break;
    
    case 'removeBan':
        removeBan($conn);
        break;
    
    default:
        echo json_encode(['error' => 'Invalid action']);
        break;
}

// Get all bookings
function getBookings($conn) {
    $date = isset($_GET['date']) ? $_GET['date'] : '';
    
    $sql = "SELECT * FROM studio_bookings";
    if ($date) {
        $sql .= " WHERE tanggal = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $date);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $result = $conn->query($sql);
    }
    
    $bookings = [];
    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
    
    echo json_encode($bookings);
}

// Add new booking
function addBooking($conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $studio = $data['studio'] ?? '';
    $tanggal = $data['tanggal'] ?? '';
    $jam = $data['jam'] ?? '';
    $nama = $data['nama'] ?? '';
    $hp = $data['hp'] ?? '';
    $nik = $data['nik'] ?? '';
    $email = $data['email'] ?? '';
    $kelas = $data['kelas'] ?? '';
    $status = $data['status'] ?? 'pending';
    
    $sql = "INSERT INTO studio_bookings (studio, tanggal, jam, nama, hp, nik, email, kelas, status, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssss", $studio, $tanggal, $jam, $nama, $hp, $nik, $email, $kelas, $status);
    
    if ($stmt->execute()) {
        $id = $conn->insert_id;
        echo json_encode(['success' => true, 'id' => $id]);
    } else {
        echo json_encode(['error' => 'Failed to add booking']);
    }
}

// Check if slot is already booked
function checkSlot($conn) {
    $studio = $_GET['studio'] ?? '';
    $tanggal = $_GET['tanggal'] ?? '';
    $jam = $_GET['jam'] ?? '';
    
    $sql = "SELECT COUNT(*) as count FROM studio_bookings 
            WHERE studio = ? AND tanggal = ? AND jam = ? AND status = 'confirmed'";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $studio, $tanggal, $jam);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    echo json_encode(['booked' => $row['count'] > 0]);
}

// Check if user is banned
function checkBan($conn) {
    $nik = $_GET['nik'] ?? '';
    
    if (!$nik) {
        echo json_encode(['banned' => false]);
        return;
    }
    
    $sql = "SELECT * FROM ban_list WHERE nik = ? AND banned_until > NOW()";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nik);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode(['banned' => true, 'until' => $row['banned_until']]);
    } else {
        echo json_encode(['banned' => false]);
    }
}

// Confirm booking
function confirmBooking($conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'] ?? 0;
    
    $sql = "UPDATE studio_bookings SET status = 'confirmed' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Failed to confirm booking']);
    }
}

// Delete booking
function deleteBooking($conn) {
    $id = $_GET['id'] ?? 0;
    
    $sql = "DELETE FROM studio_bookings WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Failed to delete booking']);
    }
}

// Update booking
function updateBooking($conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $id = $data['id'] ?? 0;
    $studio = $data['studio'] ?? '';
    $tanggal = $data['tanggal'] ?? '';
    $jam = $data['jam'] ?? '';
    $nama = $data['nama'] ?? '';
    $nik = $data['nik'] ?? '';
    $kelas = $data['kelas'] ?? '';
    $status = $data['status'] ?? 'pending';
    
    $sql = "UPDATE studio_bookings 
            SET studio = ?, tanggal = ?, jam = ?, nama = ?, nik = ?, kelas = ?, status = ?
            WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssi", $studio, $tanggal, $jam, $nama, $nik, $kelas, $status, $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Failed to update booking']);
    }
}

// Get statistics
function getStats($conn) {
    $today = date('Y-m-d');
    
    // Total bookings today
    $sql1 = "SELECT COUNT(*) as count FROM studio_bookings WHERE tanggal = ? AND status = 'confirmed'";
    $stmt1 = $conn->prepare($sql1);
    $stmt1->bind_param("s", $today);
    $stmt1->execute();
    $result1 = $stmt1->get_result();
    $todayBookings = $result1->fetch_assoc()['count'];
    
    // Total bookings all time
    $sql2 = "SELECT COUNT(*) as count FROM studio_bookings WHERE status = 'confirmed'";
    $result2 = $conn->query($sql2);
    $totalBookings = $result2->fetch_assoc()['count'];
    
    // Pending bookings
    $sql3 = "SELECT COUNT(*) as count FROM studio_bookings WHERE status = 'pending'";
    $result3 = $conn->query($sql3);
    $pendingBookings = $result3->fetch_assoc()['count'];
    
    // Active bans
    $sql4 = "SELECT COUNT(*) as count FROM ban_list WHERE banned_until > NOW()";
    $result4 = $conn->query($sql4);
    $activeBans = $result4->fetch_assoc()['count'];
    
    echo json_encode([
        'todayBookings' => $todayBookings,
        'totalBookings' => $totalBookings,
        'pendingBookings' => $pendingBookings,
        'activeBans' => $activeBans
    ]);
}

// Get ban list
function getBanList($conn) {
    $sql = "SELECT * FROM ban_list WHERE banned_until > NOW() ORDER BY banned_until DESC";
    $result = $conn->query($sql);
    
    $bans = [];
    while ($row = $result->fetch_assoc()) {
        $bans[] = $row;
    }
    
    echo json_encode($bans);
}

// Add ban
function addBan($conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $nik = $data['nik'] ?? '';
    $nama = $data['nama'] ?? '';
    $reason = $data['reason'] ?? '';
    $bannedUntil = $data['bannedUntil'] ?? '';
    
    $sql = "INSERT INTO ban_list (nik, nama, reason, banned_until, created_at) 
            VALUES (?, ?, ?, ?, NOW())
            ON DUPLICATE KEY UPDATE 
            nama = VALUES(nama), 
            reason = VALUES(reason), 
            banned_until = VALUES(banned_until)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $nik, $nama, $reason, $bannedUntil);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Failed to add ban']);
    }
}

// Remove ban
function removeBan($conn) {
    $nik = $_GET['nik'] ?? '';
    
    $sql = "DELETE FROM ban_list WHERE nik = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nik);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Failed to remove ban']);
    }
}

$conn->close();
?>
