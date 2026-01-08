-- Database Schema for Alazka Studio
-- Database: alazkasch_alazka_studio
-- Pilih database 'alazkasch_alazka_studio' di phpMyAdmin sebelum import

-- CREATE DATABASE IF NOT EXISTS alazkasch_alazka_studio CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE alazkasch_alazka_studio;

-- Tabel untuk booking studio
CREATE TABLE IF NOT EXISTS studio_bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    studio VARCHAR(50) NOT NULL,
    tanggal DATE NOT NULL,
    jam VARCHAR(20) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    hp VARCHAR(20) NOT NULL,
    nik VARCHAR(20),
    email VARCHAR(100),
    kelas VARCHAR(50),
    status VARCHAR(20) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_studio_date (studio, tanggal),
    INDEX idx_status (status),
    INDEX idx_tanggal (tanggal)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel untuk daftar ban user
CREATE TABLE IF NOT EXISTS ban_list (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nik VARCHAR(20) UNIQUE NOT NULL,
    nama VARCHAR(100) NOT NULL,
    reason TEXT,
    banned_until DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_nik (nik),
    INDEX idx_banned_until (banned_until)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data contoh (opsional, bisa dihapus)
INSERT INTO studio_bookings (studio, tanggal, jam, nama, hp, nik, email, status) VALUES
('Studio 1', '2026-01-07', '09:00-11:00', 'John Doe', '081234567890', '1234567890123456', 'john@example.com', 'confirmed'),
('Studio 2', '2026-01-07', '13:00-15:00', 'Jane Smith', '082345678901', '2345678901234567', 'jane@example.com', 'confirmed'),
('Studio 3', '2026-01-08', '10:00-12:00', 'Bob Wilson', '083456789012', '3456789012345678', 'bob@example.com', 'pending');
