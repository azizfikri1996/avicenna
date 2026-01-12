-- Create students table for NIS to Name mapping
-- Run this SQL in phpMyAdmin for database: alazkasch_alazka_studio

CREATE TABLE IF NOT EXISTS `students` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nis` varchar(50) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `kelas` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nis` (`nis`),
  KEY `idx_nis` (`nis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample data
INSERT INTO `students` (`nis`, `nama`, `kelas`) VALUES
('0123456789', 'Muhammad Aziz Fikri', 'SMA 12A');

-- Add more students as needed:
-- INSERT INTO `students` (`nis`, `nama`, `kelas`) VALUES
-- ('9876543210', 'Nama Siswa Lain', 'SMP 9.1'),
-- ('1112223334', 'Nama Siswa Berikutnya', 'SD 6A');
