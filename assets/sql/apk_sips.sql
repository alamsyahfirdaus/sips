-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 16, 2022 at 01:50 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 7.4.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `apk_sips`
--

-- --------------------------------------------------------

--
-- Table structure for table `guru_piket`
--

CREATE TABLE `guru_piket` (
  `id_guru_piket` int(11) NOT NULL,
  `id_detail_guru_piket` int(11) DEFAULT NULL,
  `id_tahun_pelajaran` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `hari` int(11) DEFAULT NULL,
  `delete_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `guru_piket`
--

INSERT INTO `guru_piket` (`id_guru_piket`, `id_detail_guru_piket`, `id_tahun_pelajaran`, `id_user`, `hari`, `delete_at`) VALUES
(1, NULL, 20, NULL, 1, NULL),
(2, NULL, 20, NULL, 2, NULL),
(3, NULL, 20, NULL, 3, NULL),
(4, NULL, 20, NULL, 4, NULL),
(5, NULL, 20, NULL, 5, NULL),
(6, NULL, 20, NULL, 6, NULL),
(7, 1, NULL, 22, NULL, NULL),
(8, 2, NULL, 26, NULL, NULL),
(9, 3, NULL, 21, NULL, NULL),
(10, 4, NULL, 20, NULL, NULL),
(13, 2, NULL, 21, NULL, NULL),
(14, 3, NULL, 20, NULL, NULL),
(15, 4, NULL, 26, NULL, NULL),
(16, 5, NULL, 20, NULL, NULL),
(17, 6, NULL, 21, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `image_slider`
--

CREATE TABLE `image_slider` (
  `id_slider` int(11) NOT NULL COMMENT '1 = Logo Sekolah',
  `gambar` varchar(16) NOT NULL,
  `is_aktif` enum('Y','N') DEFAULT NULL,
  `sort` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `image_slider`
--

INSERT INTO `image_slider` (`id_slider`, `gambar`, `is_aktif`, `sort`) VALUES
(1, '1618050330.jpg', NULL, NULL),
(15, '1627312643.jpg', 'Y', 1622624978);

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_pelajaran`
--

CREATE TABLE `jadwal_pelajaran` (
  `jadwal_pelajaran_id` int(11) NOT NULL,
  `sub_id` int(11) DEFAULT NULL,
  `id_tahun_pelajaran` int(11) NOT NULL,
  `id_kelas` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL COMMENT 'guru_mapel',
  `id_mata_pelajaran` int(11) NOT NULL,
  `hari` int(11) DEFAULT NULL,
  `mulai` int(11) DEFAULT NULL,
  `selesai` int(11) DEFAULT NULL,
  `sort` int(11) DEFAULT NULL,
  `delete_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `jadwal_pelajaran`
--

INSERT INTO `jadwal_pelajaran` (`jadwal_pelajaran_id`, `sub_id`, `id_tahun_pelajaran`, `id_kelas`, `id_user`, `id_mata_pelajaran`, `hari`, `mulai`, `selesai`, `sort`, `delete_at`) VALUES
(1, NULL, 20, 1, 22, 1, 3, 1, 19, 700, NULL),
(2, NULL, 20, 1, 26, 22, 4, 1, 19, 700, NULL),
(3, NULL, 20, 1, 20, 23, 2, 1, 19, 700, NULL),
(4, NULL, 20, 1, 21, 24, 5, 1, 19, 700, NULL),
(5, NULL, 20, 1, 22, 30, 1, 1, 19, 700, NULL),
(6, NULL, 20, 102, 22, 1, 4, 19, 23, 820, NULL),
(7, NULL, 20, 102, 22, 22, 6, 1, 19, 700, NULL),
(8, NULL, 20, 102, 21, 23, 2, 1, 19, 700, NULL),
(9, NULL, 20, 102, 20, 24, 1, 1, 19, 700, NULL),
(10, NULL, 20, 102, 26, 30, 3, 19, 23, 820, NULL),
(11, NULL, 20, 103, NULL, 1, NULL, NULL, NULL, NULL, NULL),
(12, NULL, 20, 103, NULL, 22, NULL, NULL, NULL, NULL, NULL),
(13, NULL, 20, 103, NULL, 23, NULL, NULL, NULL, NULL, NULL),
(14, NULL, 20, 103, NULL, 24, NULL, NULL, NULL, NULL, NULL),
(15, NULL, 20, 103, NULL, 30, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jam_pelajaran`
--

CREATE TABLE `jam_pelajaran` (
  `jam_pelajaran_id` int(11) NOT NULL,
  `jam_pelajaran` varchar(16) NOT NULL,
  `delete_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `jam_pelajaran`
--

INSERT INTO `jam_pelajaran` (`jam_pelajaran_id`, `jam_pelajaran`, `delete_at`) VALUES
(1, '7:00', NULL),
(7, '7:40', NULL),
(19, '8:20', NULL),
(20, '9:00', NULL),
(23, '9:40', NULL),
(56, '10:00', NULL),
(57, '10:40', NULL),
(58, '11:20', NULL),
(59, '12:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `kelas_id` int(11) NOT NULL,
  `nama_kelas` varchar(32) NOT NULL,
  `urutan_kelas` int(11) DEFAULT NULL,
  `id_tingkat_kelas` int(11) NOT NULL,
  `delete_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`kelas_id`, `nama_kelas`, `urutan_kelas`, `id_tingkat_kelas`, `delete_at`) VALUES
(1, 'VII - A', 1, 1, NULL),
(102, 'VIII - A', 1, 2, NULL),
(103, 'IX - A', 1, 3, NULL),
(104, 'VII - B', 2, 1, NULL),
(105, 'VIII - B', 2, 2, NULL),
(106, 'IX - B', 2, 3, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `list_tables`
--

CREATE TABLE `list_tables` (
  `id` int(11) NOT NULL,
  `table` varchar(32) NOT NULL,
  `title` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `list_tables`
--

INSERT INTO `list_tables` (`id`, `table`, `title`) VALUES
(1, 'kelas', 'Kelas'),
(2, 'mata_pelajaran', 'Mata Pelajaran'),
(3, 'jam_pelajaran', 'Jam Pelajaran'),
(4, 'tahun_pelajaran', 'Tahun Pelajaran'),
(5, 'wali_kelas', 'Wali Kelas'),
(6, 'user', 'Pengguna'),
(7, 'jadwal_pelajaran', 'Jadwal Pelajaran'),
(8, 'presensi', 'Presensi Siswa');

-- --------------------------------------------------------

--
-- Table structure for table `mata_pelajaran`
--

CREATE TABLE `mata_pelajaran` (
  `mapel_id` int(11) NOT NULL,
  `kode_mapel` varchar(16) DEFAULT NULL,
  `nama_mapel` varchar(64) NOT NULL,
  `delete_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `mata_pelajaran`
--

INSERT INTO `mata_pelajaran` (`mapel_id`, `kode_mapel`, `nama_mapel`, `delete_at`) VALUES
(1, 'MTK', 'Matematika', NULL),
(22, 'BIND', 'Bahasa Indonesia', NULL),
(23, 'IPA', 'Ilmu Pengetahuan Alam', NULL),
(24, 'IPS', 'Ilmu Pengetahuan Sosial', NULL),
(30, 'BING', 'Bahasa Inggris', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `menu_id` int(11) NOT NULL,
  `menu` varchar(32) NOT NULL,
  `icon` varchar(32) NOT NULL,
  `url_menu` varchar(32) DEFAULT NULL,
  `sort` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`menu_id`, `menu`, `icon`, `url_menu`, `sort`) VALUES
(1, 'Beranda', 'fa-home', 'home', '1608632448'),
(2, 'Pengaturan', 'fa-cogs', NULL, '1622634776'),
(3, 'Keluar', 'fa-sign-out', 'logout', '1622634780'),
(4, 'Pengguna', 'fa-users', NULL, '1608632454'),
(5, 'Master', 'fa-folder-open', NULL, '1608632453'),
(14, 'Jadwal Pelajaran', 'fa-calendar', 'schedules', '1608632455'),
(30, 'Laporan', 'fa-bar-chart', NULL, '1616041148'),
(33, 'Rekap Presensi Siswa', 'fa-bar-chart', 'report', '1622634686');

-- --------------------------------------------------------

--
-- Table structure for table `pengaturan`
--

CREATE TABLE `pengaturan` (
  `id_pengaturan` int(11) NOT NULL,
  `nama_pengaturan` varchar(32) DEFAULT NULL,
  `pengaturan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pengaturan`
--

INSERT INTO `pengaturan` (`id_pengaturan`, `nama_pengaturan`, `pengaturan`) VALUES
(1, 'Visi Sekolah', '<p><em><strong>Unggul Dalam Prestasi, Tangguh Dalam Kompetensi, Santun Dalam Pekerti, Dan Teguh Dalam Syariat Islam</strong></em></p>'),
(2, 'Misi Sekolah', '<p>Mewujudkan pendidikan yang menghasilkan lulusan yang cerdas dalam memahami dan menjalani nilai-nilai keimanan dan ketaqwaan terhadap Allah SWT, cerdas dalam berfikir, cerdas dalam bersikap,cerdas dalam kreatifitas;</p><p>Mewujudkan sikap penghayatan terhadap ajaran agama Islam dan budaya bangsa sehingga terbangun peserta didik yang kompeten dan berakhlak mulia;</p><p>Mewujudkan proses pembelajaran yang menggunakan perangkat kurikulum yang inovatif, efektif dan efesien;</p><p>Memberikan kesempatan kepada anak usia 13 tahun sampai 18 tahun untuk mengembangkan kepribadiannya; dan</p><p>Mewujudkan peserta didik yang mampu berbuat dan bertindak untuk dirinya maupun orang lain dan berakhlakul karimah.</p>');

-- --------------------------------------------------------

--
-- Table structure for table `pengumuman`
--

CREATE TABLE `pengumuman` (
  `id_pengumuman` int(11) NOT NULL,
  `judul` varchar(32) NOT NULL,
  `tanggal` date NOT NULL,
  `user_type_id` int(11) DEFAULT NULL,
  `gambar` varchar(16) DEFAULT NULL,
  `pengumuman` text DEFAULT NULL,
  `is_aktif` enum('Y','N') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pengumuman`
--

INSERT INTO `pengumuman` (`id_pengumuman`, `judul`, `tanggal`, `user_type_id`, `gambar`, `pengumuman`, `is_aktif`) VALUES
(1, 'Masuk Sekolah', '2021-03-21', NULL, NULL, '<p>&quot;Dalam situasi Covid-19 ini yang terpenting adalah kesehatan dan keselamatan para murid-murid kita, guru-guru kita, dan para keluarganya. Relaksasi pembukaan sekolah ini dilakukan dengan cara paling konservatif yang dapat kita lakukan,&quot;kata Nadiem melalui kanal Kemendikbud RI di YouTube seperti dilansir Tribunjabar.id dari Tribun-Timur.com.</p>', 'Y');

-- --------------------------------------------------------

--
-- Table structure for table `presensi`
--

CREATE TABLE `presensi` (
  `presensi_id` int(11) NOT NULL,
  `tanggal` date NOT NULL DEFAULT current_timestamp(),
  `id_jadwal_pelajaran` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_tapel` int(11) DEFAULT NULL COMMENT 'id_tahun_pelajaran',
  `semester` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `delete_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `presensi`
--

INSERT INTO `presensi` (`presensi_id`, `tanggal`, `id_jadwal_pelajaran`, `id_user`, `id_tapel`, `semester`, `status`, `keterangan`, `delete_at`) VALUES
(1, '2022-01-10', 5, 15, NULL, 2, 1, NULL, NULL),
(2, '2022-01-10', 5, 23, NULL, 2, 1, NULL, NULL),
(3, '2022-01-10', 5, 24, NULL, 2, 1, NULL, NULL),
(4, '2022-01-10', 5, 33, NULL, 2, 1, NULL, NULL),
(5, '2022-01-10', 5, 34, NULL, 2, 1, NULL, NULL),
(6, '2022-01-10', 5, 35, NULL, 2, 1, NULL, NULL),
(7, '2022-01-10', 5, 86, NULL, 2, 1, NULL, NULL),
(8, '2022-01-10', 5, 87, NULL, 2, 1, NULL, NULL),
(9, '2022-01-10', 5, 88, NULL, 2, 1, NULL, NULL),
(10, '2022-01-10', 5, 89, NULL, 2, 1, NULL, NULL),
(11, '2022-01-17', 5, 15, NULL, 2, 1, NULL, NULL),
(12, '2022-01-17', 5, 23, NULL, 2, 2, NULL, NULL),
(13, '2022-01-17', 5, 24, NULL, 2, 3, NULL, NULL),
(14, '2022-01-17', 5, 33, NULL, 2, 4, NULL, NULL),
(15, '2022-01-17', 5, 34, NULL, 2, 1, NULL, NULL),
(16, '2022-01-17', 5, 35, NULL, 2, 1, NULL, NULL),
(17, '2022-01-17', 5, 86, NULL, 2, 2, NULL, NULL),
(18, '2022-01-17', 5, 87, NULL, 2, 3, NULL, NULL),
(19, '2022-01-17', 5, 88, NULL, 2, 4, NULL, NULL),
(20, '2022-01-17', 5, 89, NULL, 2, 1, NULL, NULL),
(21, '2022-01-12', 1, 15, NULL, 2, 2, NULL, NULL),
(22, '2022-01-12', 1, 23, NULL, 2, 4, NULL, NULL),
(23, '2022-01-12', 1, 24, NULL, 2, 1, NULL, NULL),
(24, '2022-01-12', 1, 33, NULL, 2, 1, NULL, NULL),
(25, '2022-01-12', 1, 34, NULL, 2, 1, NULL, NULL),
(26, '2022-01-12', 1, 35, NULL, 2, 1, NULL, NULL),
(27, '2022-01-12', 1, 86, NULL, 2, 2, NULL, NULL),
(28, '2022-01-12', 1, 87, NULL, 2, 3, NULL, NULL),
(29, '2022-01-12', 1, 88, NULL, 2, 4, NULL, NULL),
(30, '2022-01-12', 1, 89, NULL, 2, 3, NULL, NULL),
(31, '2022-01-13', 6, 90, NULL, 2, 2, NULL, NULL),
(32, '2022-01-13', 6, 91, NULL, 2, 3, NULL, NULL),
(33, '2022-01-13', 6, 92, NULL, 2, 4, NULL, NULL),
(34, '2022-01-13', 6, 93, NULL, 2, 2, NULL, NULL),
(35, '2022-01-13', 6, 94, NULL, 2, 3, NULL, NULL),
(36, '2022-01-13', 6, 95, NULL, 2, 4, NULL, NULL),
(37, '2022-01-13', 6, 96, NULL, 2, 1, NULL, NULL),
(38, '2022-01-13', 6, 97, NULL, 2, 1, NULL, NULL),
(39, '2022-01-13', 6, 98, NULL, 2, 1, NULL, NULL),
(40, '2022-01-13', 6, 99, NULL, 2, 1, NULL, NULL),
(41, '2022-01-14', 4, 15, NULL, 2, 1, NULL, NULL),
(42, '2022-01-14', 4, 23, NULL, 2, 1, NULL, NULL),
(43, '2022-01-14', 4, 24, NULL, 2, 1, NULL, NULL),
(44, '2022-01-14', 4, 33, NULL, 2, 1, NULL, NULL),
(45, '2022-01-14', 4, 34, NULL, 2, 1, NULL, NULL),
(46, '2022-01-14', 4, 35, NULL, 2, 1, NULL, NULL),
(47, '2022-01-14', 4, 86, NULL, 2, 1, NULL, NULL),
(48, '2022-01-14', 4, 87, NULL, 2, 1, NULL, NULL),
(49, '2022-01-14', 4, 88, NULL, 2, 1, NULL, NULL),
(50, '2022-01-14', 4, 89, NULL, 2, 1, NULL, NULL),
(51, '2022-01-11', 3, 15, NULL, 2, 1, NULL, NULL),
(52, '2022-01-11', 3, 23, NULL, 2, 1, NULL, NULL),
(53, '2022-01-11', 3, 24, NULL, 2, 1, NULL, NULL),
(54, '2022-01-11', 3, 33, NULL, 2, 1, NULL, NULL),
(55, '2022-01-11', 3, 34, NULL, 2, 1, NULL, NULL),
(56, '2022-01-11', 3, 35, NULL, 2, 1, NULL, NULL),
(57, '2022-01-11', 3, 86, NULL, 2, 1, NULL, NULL),
(58, '2022-01-11', 3, 87, NULL, 2, 1, NULL, NULL),
(59, '2022-01-11', 3, 88, NULL, 2, 1, NULL, NULL),
(60, '2022-01-11', 3, 89, NULL, 2, 1, NULL, NULL),
(61, '2022-01-12', 10, 90, NULL, 2, 1, NULL, NULL),
(62, '2022-01-12', 10, 91, NULL, 2, 1, NULL, NULL),
(63, '2022-01-12', 10, 92, NULL, 2, 1, NULL, NULL),
(64, '2022-01-12', 10, 93, NULL, 2, 1, NULL, NULL),
(65, '2022-01-12', 10, 94, NULL, 2, 1, NULL, NULL),
(66, '2022-01-12', 10, 95, NULL, 2, 1, NULL, NULL),
(67, '2022-01-12', 10, 96, NULL, 2, 1, NULL, NULL),
(68, '2022-01-12', 10, 97, NULL, 2, 1, NULL, NULL),
(69, '2022-01-12', 10, 98, NULL, 2, 1, NULL, NULL),
(70, '2022-01-12', 10, 99, NULL, 2, 1, NULL, NULL),
(71, '2022-01-10', 9, 90, NULL, 2, 1, NULL, NULL),
(72, '2022-01-10', 9, 91, NULL, 2, 1, NULL, NULL),
(73, '2022-01-10', 9, 92, NULL, 2, 1, NULL, NULL),
(74, '2022-01-10', 9, 93, NULL, 2, 1, NULL, NULL),
(75, '2022-01-10', 9, 94, NULL, 2, 1, NULL, NULL),
(76, '2022-01-10', 9, 95, NULL, 2, 1, NULL, NULL),
(77, '2022-01-10', 9, 96, NULL, 2, 1, NULL, NULL),
(78, '2022-01-10', 9, 97, NULL, 2, 1, NULL, NULL),
(79, '2022-01-10', 9, 98, NULL, 2, 1, NULL, NULL),
(80, '2022-01-10', 9, 99, NULL, 2, 1, NULL, NULL),
(81, '2022-01-17', 9, 90, NULL, 2, 1, NULL, NULL),
(82, '2022-01-17', 9, 91, NULL, 2, 1, NULL, NULL),
(83, '2022-01-17', 9, 92, NULL, 2, 1, NULL, NULL),
(84, '2022-01-17', 9, 93, NULL, 2, 1, NULL, NULL),
(85, '2022-01-17', 9, 94, NULL, 2, 1, NULL, NULL),
(86, '2022-01-17', 9, 95, NULL, 2, 1, NULL, NULL),
(87, '2022-01-17', 9, 96, NULL, 2, 1, NULL, NULL),
(88, '2022-01-17', 9, 97, NULL, 2, 1, NULL, NULL),
(89, '2022-01-17', 9, 98, NULL, 2, 1, NULL, NULL),
(90, '2022-01-17', 9, 99, NULL, 2, 1, NULL, NULL),
(91, '2022-01-11', 8, 90, NULL, 2, 1, NULL, NULL),
(92, '2022-01-11', 8, 91, NULL, 2, 1, NULL, NULL),
(93, '2022-01-11', 8, 92, NULL, 2, 1, NULL, NULL),
(94, '2022-01-11', 8, 93, NULL, 2, 1, NULL, NULL),
(95, '2022-01-11', 8, 94, NULL, 2, 1, NULL, NULL),
(96, '2022-01-11', 8, 95, NULL, 2, 1, NULL, NULL),
(97, '2022-01-11', 8, 96, NULL, 2, 1, NULL, NULL),
(98, '2022-01-11', 8, 97, NULL, 2, 1, NULL, NULL),
(99, '2022-01-11', 8, 98, NULL, 2, 1, NULL, NULL),
(100, '2022-01-11', 8, 99, NULL, 2, 1, NULL, NULL),
(101, '2022-01-18', 8, 90, NULL, 2, 1, NULL, NULL),
(102, '2022-01-18', 8, 91, NULL, 2, 1, NULL, NULL),
(103, '2022-01-18', 8, 92, NULL, 2, 1, NULL, NULL),
(104, '2022-01-18', 8, 93, NULL, 2, 1, NULL, NULL),
(105, '2022-01-18', 8, 94, NULL, 2, 1, NULL, NULL),
(106, '2022-01-18', 8, 95, NULL, 2, 1, NULL, NULL),
(107, '2022-01-18', 8, 96, NULL, 2, 1, NULL, NULL),
(108, '2022-01-18', 8, 97, NULL, 2, 1, NULL, NULL),
(109, '2022-01-18', 8, 98, NULL, 2, 1, NULL, NULL),
(110, '2022-01-18', 8, 99, NULL, 2, 1, NULL, NULL),
(111, '2022-01-14', 7, 90, NULL, 2, 1, NULL, NULL),
(112, '2022-01-14', 7, 91, NULL, 2, 1, NULL, NULL),
(113, '2022-01-14', 7, 92, NULL, 2, 1, NULL, NULL),
(114, '2022-01-14', 7, 93, NULL, 2, 1, NULL, NULL),
(115, '2022-01-14', 7, 94, NULL, 2, 1, NULL, NULL),
(116, '2022-01-14', 7, 95, NULL, 2, 1, NULL, NULL),
(117, '2022-01-14', 7, 96, NULL, 2, 1, NULL, NULL),
(118, '2022-01-14', 7, 97, NULL, 2, 1, NULL, NULL),
(119, '2022-01-14', 7, 98, NULL, 2, 1, NULL, NULL),
(120, '2022-01-14', 7, 99, NULL, 2, 1, NULL, NULL),
(121, '2022-01-18', 3, 15, NULL, 2, 1, NULL, NULL),
(122, '2022-01-18', 3, 23, NULL, 2, 1, NULL, NULL),
(123, '2022-01-18', 3, 24, NULL, 2, 1, NULL, NULL),
(124, '2022-01-18', 3, 33, NULL, 2, 1, NULL, NULL),
(125, '2022-01-18', 3, 34, NULL, 2, 1, NULL, NULL),
(126, '2022-01-18', 3, 35, NULL, 2, 1, NULL, NULL),
(127, '2022-01-18', 3, 86, NULL, 2, 1, NULL, NULL),
(128, '2022-01-18', 3, 87, NULL, 2, 1, NULL, NULL),
(129, '2022-01-18', 3, 88, NULL, 2, 1, NULL, NULL),
(130, '2022-01-18', 3, 89, NULL, 2, 1, NULL, NULL),
(131, '2022-01-13', 2, 15, NULL, 2, 1, NULL, NULL),
(132, '2022-01-13', 2, 23, NULL, 2, 1, NULL, NULL),
(133, '2022-01-13', 2, 24, NULL, 2, 1, NULL, NULL),
(134, '2022-01-13', 2, 33, NULL, 2, 1, NULL, NULL),
(135, '2022-01-13', 2, 34, NULL, 2, 1, NULL, NULL),
(136, '2022-01-13', 2, 35, NULL, 2, 1, NULL, NULL),
(137, '2022-01-13', 2, 86, NULL, 2, 1, NULL, NULL),
(138, '2022-01-13', 2, 87, NULL, 2, 1, NULL, NULL),
(139, '2022-01-13', 2, 88, NULL, 2, 1, NULL, NULL),
(140, '2022-01-13', 2, 89, NULL, 2, 1, NULL, NULL),
(141, '2022-01-19', 1, 15, NULL, 2, 2, NULL, NULL),
(142, '2022-01-19', 1, 23, NULL, 2, 1, NULL, NULL),
(143, '2022-01-19', 1, 24, NULL, 2, 1, NULL, NULL),
(144, '2022-01-19', 1, 33, NULL, 2, 1, NULL, NULL),
(145, '2022-01-19', 1, 34, NULL, 2, 1, NULL, NULL),
(146, '2022-01-19', 1, 35, NULL, 2, 1, NULL, NULL),
(147, '2022-01-19', 1, 86, NULL, 2, 1, NULL, NULL),
(148, '2022-01-19', 1, 87, NULL, 2, 1, NULL, NULL),
(149, '2022-01-19', 1, 88, NULL, 2, 1, NULL, NULL),
(150, '2022-01-19', 1, 89, NULL, 2, 3, NULL, NULL),
(151, '2022-01-21', 7, 90, NULL, 2, 1, NULL, NULL),
(152, '2022-01-21', 7, 91, NULL, 2, 1, NULL, NULL),
(153, '2022-01-21', 7, 92, NULL, 2, 1, NULL, NULL),
(154, '2022-01-21', 7, 93, NULL, 2, 1, NULL, NULL),
(155, '2022-01-21', 7, 94, NULL, 2, 1, NULL, NULL),
(156, '2022-01-21', 7, 95, NULL, 2, 1, NULL, NULL),
(157, '2022-01-21', 7, 96, NULL, 2, 1, NULL, NULL),
(158, '2022-01-21', 7, 97, NULL, 2, 1, NULL, NULL),
(159, '2022-01-21', 7, 98, NULL, 2, 1, NULL, NULL),
(160, '2022-01-21', 7, 99, NULL, 2, 1, NULL, NULL),
(161, '2022-01-22', 7, 90, NULL, 2, 1, NULL, NULL),
(162, '2022-01-22', 7, 91, NULL, 2, 1, NULL, NULL),
(163, '2022-01-22', 7, 92, NULL, 2, 1, NULL, NULL),
(164, '2022-01-22', 7, 93, NULL, 2, 1, NULL, NULL),
(165, '2022-01-22', 7, 94, NULL, 2, 1, NULL, NULL),
(166, '2022-01-22', 7, 95, NULL, 2, 1, NULL, NULL),
(167, '2022-01-22', 7, 96, NULL, 2, 1, NULL, NULL),
(168, '2022-01-22', 7, 97, NULL, 2, 1, NULL, NULL),
(169, '2022-01-22', 7, 98, NULL, 2, 1, NULL, NULL),
(170, '2022-01-22', 7, 99, NULL, 2, 1, NULL, NULL),
(171, '2022-01-15', 7, 90, NULL, 2, 2, NULL, NULL),
(172, '2022-01-15', 7, 91, NULL, 2, 1, NULL, NULL),
(173, '2022-01-15', 7, 92, NULL, 2, 1, NULL, NULL),
(174, '2022-01-15', 7, 93, NULL, 2, 1, NULL, NULL),
(175, '2022-01-15', 7, 94, NULL, 2, 1, NULL, NULL),
(176, '2022-01-15', 7, 95, NULL, 2, 1, NULL, NULL),
(177, '2022-01-15', 7, 96, NULL, 2, 1, NULL, NULL),
(178, '2022-01-15', 7, 97, NULL, 2, 1, NULL, NULL),
(179, '2022-01-15', 7, 98, NULL, 2, 1, NULL, NULL),
(180, '2022-01-15', 7, 99, NULL, 2, 1, NULL, NULL),
(224, '2022-01-22', NULL, 15, 20, 2, 1, NULL, NULL),
(225, '2022-01-22', NULL, 23, 20, 2, 2, NULL, NULL),
(226, '2022-01-22', NULL, 24, 20, 2, 3, NULL, NULL),
(227, '2022-01-22', NULL, 33, 20, 2, 1, NULL, NULL),
(228, '2022-01-22', NULL, 34, 20, 2, 1, NULL, NULL),
(229, '2022-01-22', NULL, 35, 20, 2, 1, NULL, NULL),
(230, '2022-01-22', NULL, 86, 20, 2, 1, NULL, NULL),
(231, '2022-01-22', NULL, 87, 20, 2, 1, NULL, NULL),
(232, '2022-01-22', NULL, 88, 20, 2, 1, NULL, NULL),
(233, '2022-01-22', NULL, 89, 20, 2, 1, NULL, NULL),
(234, '2022-01-10', NULL, 15, 20, 2, 1, NULL, NULL),
(235, '2022-01-10', NULL, 23, 20, 2, 1, NULL, NULL),
(236, '2022-01-10', NULL, 24, 20, 2, 1, NULL, NULL),
(237, '2022-01-10', NULL, 33, 20, 2, 1, NULL, NULL),
(238, '2022-01-10', NULL, 34, 20, 2, 1, NULL, NULL),
(239, '2022-01-10', NULL, 35, 20, 2, 1, NULL, NULL),
(240, '2022-01-10', NULL, 86, 20, 2, 1, NULL, NULL),
(241, '2022-01-10', NULL, 87, 20, 2, 1, NULL, NULL),
(242, '2022-01-10', NULL, 88, 20, 2, 1, NULL, NULL),
(243, '2022-01-10', NULL, 89, 20, 2, 1, NULL, NULL),
(244, '2022-01-27', NULL, 15, 20, 2, 2, NULL, NULL),
(245, '2022-01-27', NULL, 23, 20, 2, 1, NULL, NULL),
(246, '2022-01-27', NULL, 24, 20, 2, 1, NULL, NULL),
(247, '2022-01-27', NULL, 33, 20, 2, 1, NULL, NULL),
(248, '2022-01-27', NULL, 34, 20, 2, 1, NULL, NULL),
(249, '2022-01-27', NULL, 35, 20, 2, 1, NULL, NULL),
(250, '2022-01-27', NULL, 86, 20, 2, 1, NULL, NULL),
(251, '2022-01-27', NULL, 87, 20, 2, 1, NULL, NULL),
(252, '2022-01-27', NULL, 88, 20, 2, 1, NULL, NULL),
(253, '2022-01-27', NULL, 89, 20, 2, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `siswa_id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_kelas` int(11) DEFAULT NULL,
  `is_aktif` int(2) NOT NULL COMMENT '1 = aktif, 2= tidak aktif, 3=lulus, 4=drop out',
  `nama_ayah` varchar(64) DEFAULT NULL,
  `pendidikan_ayah` varchar(32) DEFAULT NULL,
  `pekerjaan_ayah` varchar(32) DEFAULT NULL,
  `penghasilan_ayah` varchar(32) DEFAULT NULL,
  `nohp_ayah` varchar(16) DEFAULT NULL,
  `alamat_ayah` text DEFAULT NULL,
  `nama_ibu` varchar(64) DEFAULT NULL,
  `pendidikan_ibu` varchar(32) DEFAULT NULL,
  `pekerjaan_ibu` varchar(32) DEFAULT NULL,
  `penghasilan_ibu` varchar(32) DEFAULT NULL,
  `nohp_ibu` varchar(16) DEFAULT NULL,
  `alamat_ibu` text DEFAULT NULL,
  `nama_wali` varchar(64) DEFAULT NULL,
  `pendidikan_wali` varchar(32) DEFAULT NULL,
  `pekerjaan_wali` varchar(32) DEFAULT NULL,
  `penghasilan_wali` varchar(32) DEFAULT NULL,
  `nohp_wali` varchar(16) DEFAULT NULL,
  `alamat_wali` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`siswa_id`, `id_user`, `id_kelas`, `is_aktif`, `nama_ayah`, `pendidikan_ayah`, `pekerjaan_ayah`, `penghasilan_ayah`, `nohp_ayah`, `alamat_ayah`, `nama_ibu`, `pendidikan_ibu`, `pekerjaan_ibu`, `penghasilan_ibu`, `nohp_ibu`, `alamat_ibu`, `nama_wali`, `pendidikan_wali`, `pekerjaan_wali`, `penghasilan_wali`, `nohp_wali`, `alamat_wali`) VALUES
(4, 15, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 23, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 24, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, 33, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(11, 34, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(12, 35, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(62, 86, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(63, 87, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(64, 88, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(65, 89, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(66, 90, 102, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(67, 91, 102, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(68, 92, 102, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(69, 93, 102, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(70, 94, 102, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(71, 95, 102, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(72, 96, 102, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(73, 97, 102, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(74, 98, 102, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(75, 99, 102, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(76, 100, 103, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(77, 101, 103, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(78, 102, 103, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(79, 103, 103, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(80, 104, 103, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(81, 105, 103, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(82, 106, 103, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(83, 107, 103, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(84, 108, 103, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(85, 109, 103, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sub_menu`
--

CREATE TABLE `sub_menu` (
  `sub_menu_id` int(11) NOT NULL,
  `sub_menu` varchar(32) NOT NULL,
  `url` varchar(64) NOT NULL,
  `route` varchar(32) DEFAULT NULL,
  `sort` varchar(32) DEFAULT NULL,
  `menu_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sub_menu`
--

INSERT INTO `sub_menu` (`sub_menu_id`, `sub_menu`, `url`, `route`, `sort`, `menu_id`) VALUES
(2, 'Menu', 'setting/menu', 'menu', '1617073522', 2),
(3, 'Administrator', 'user/administration', 'administrations', '1617073506', 4),
(4, 'Guru', 'user/teacher', 'teachers', '1617073509', 4),
(5, 'Siswa', 'user/student', 'students', '1617073510', 4),
(9, 'Kelas', 'master/classes', 'classes', '1617073488', 5),
(10, 'Mata Pelajaran', 'master/subjects', 'subjects', '1617073490', 5),
(13, 'Jam Pelajaran', 'master/hours', 'hours', '1617073491', 5),
(15, 'Tahun Pelajaran', 'master/years', 'years', '1617073492', 5),
(18, 'Wali Kelas', 'master/homeroom', 'homeroomteachers', '1617073493', 5),
(19, 'Tingkat Kelas', 'master/levels', 'levels', '1617073487', 5),
(26, 'Pengumuman', 'setting/announcement', 'announcement', '1617073527', 2),
(27, 'Lainnya', 'setting/other', 'other', '1642776093', 2),
(28, 'Presensi Siswa', 'report/presence', 'presence', '1617073513', 30),
(29, 'Slide Gambar', 'setting/slider', 'slider', '1617073528', 2),
(30, 'Pemulihan', 'setting/recover', 'recover', '1632210815', 2),
(32, 'Guru Piket', 'master/picketteacher', 'picketteachers', '1642685512', 5);

-- --------------------------------------------------------

--
-- Table structure for table `tahun_pelajaran`
--

CREATE TABLE `tahun_pelajaran` (
  `tahun_pelajaran_id` int(11) NOT NULL,
  `tahun_pelajaran` varchar(64) NOT NULL,
  `semester` int(1) DEFAULT NULL,
  `is_aktif` enum('Y','N') NOT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `delete_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tahun_pelajaran`
--

INSERT INTO `tahun_pelajaran` (`tahun_pelajaran_id`, `tahun_pelajaran`, `semester`, `is_aktif`, `tanggal_mulai`, `tanggal_selesai`, `delete_at`) VALUES
(20, '2021/2022', 2, 'Y', '2022-01-10', '2022-06-25', NULL),
(45, '2022/2023', NULL, 'N', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tanggal_semester`
--

CREATE TABLE `tanggal_semester` (
  `id_tanggal_semester` int(11) NOT NULL,
  `id_tahun_pelajaran` int(11) NOT NULL,
  `id_semester` int(11) NOT NULL,
  `tanggal_semester` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tanggal_semester`
--

INSERT INTO `tanggal_semester` (`id_tanggal_semester`, `id_tahun_pelajaran`, `id_semester`, `tanggal_semester`) VALUES
(3, 20, 1, '2021-07-19/2022-01-01'),
(5, 20, 2, '2022-01-10/2022-06-25');

-- --------------------------------------------------------

--
-- Table structure for table `tingkat_kelas`
--

CREATE TABLE `tingkat_kelas` (
  `tingkat_kelas_id` int(11) NOT NULL,
  `tingkat_kelas` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tingkat_kelas`
--

INSERT INTO `tingkat_kelas` (`tingkat_kelas_id`, `tingkat_kelas`) VALUES
(1, 'VII'),
(2, 'VIII'),
(3, 'IX');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `no_induk` varchar(64) DEFAULT NULL,
  `email` varchar(64) DEFAULT NULL,
  `password` varchar(128) NOT NULL,
  `full_name` varchar(64) NOT NULL COMMENT 'nama_lengkap',
  `gender` enum('L','P') DEFAULT NULL COMMENT 'jenis_kelamin',
  `tempat_lahir` varchar(64) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `profile_pic` varchar(32) DEFAULT NULL COMMENT 'foto_profile',
  `phone` varchar(16) DEFAULT NULL COMMENT 'no_handphone',
  `agama` enum('Islam','Kristen','Hindu','Buddha','Konghucu') DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `last_active` datetime DEFAULT NULL,
  `user_type_id` int(11) NOT NULL,
  `status_guru` enum('GTY/PTY','Guru Bantu','Guru Honorer') DEFAULT NULL,
  `date_created` datetime NOT NULL,
  `delete_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `no_induk`, `email`, `password`, `full_name`, `gender`, `tempat_lahir`, `tanggal_lahir`, `profile_pic`, `phone`, `agama`, `alamat`, `last_active`, `user_type_id`, `status_guru`, `date_created`, `delete_at`) VALUES
(1, '3278053107980001', 'alamsyah.firdaus.af31@gmail.com', '85fcceafa13cbd523759a853ef5a89aeeeb78524', 'Alamsyah Firdaus', NULL, NULL, NULL, NULL, '089693839624', NULL, NULL, '2022-05-16 18:48:04', 1, NULL, '2021-05-31 11:25:19', NULL),
(15, '9876543219', 'siswa@sips.com', '6488c3cbcb469108d7ccaa0d1f3a61c9e6735843', 'Siswa', 'L', 'Tasikmalaya', '2021-07-26', NULL, '', NULL, NULL, '2022-05-16 18:47:08', 3, NULL, '2021-03-18 14:01:33', NULL),
(19, '1783207002', 'admin@sips.com', 'e4d18eacc1a0d260f2f94ebb5c4e6546d9a1e81b', 'Admin Sips', NULL, NULL, NULL, NULL, '081234567891', NULL, NULL, '2022-05-16 18:48:42', 1, NULL, '2021-04-10 15:30:17', NULL),
(20, '1234567891', NULL, '92429d82a41e930486c6de5ebda9602d55c39986', 'Guru3', 'L', 'Tasikmalaya', '2021-07-26', NULL, '081234567892', 'Islam', NULL, '2021-12-03 20:31:15', 2, 'GTY/PTY', '2021-04-10 15:31:16', NULL),
(21, '1234567892', NULL, '92429d82a41e930486c6de5ebda9602d55c39986', 'Guru2', 'L', 'Tasikmalaya', '2021-07-26', NULL, '081234567893', 'Islam', NULL, '2021-05-14 13:43:06', 2, 'GTY/PTY', '2021-04-10 15:31:50', NULL),
(22, '12345678931', 'guru@sips.com', 'cd396be33701d33324a9c41af4ba7c1eb7e464e9', 'Alamsyah Firdaus', 'L', 'Tasikmalaya', '2021-07-26', NULL, '081234567894', NULL, NULL, '2022-05-16 18:43:23', 2, 'GTY/PTY', '2021-04-10 15:32:23', NULL),
(23, '9876543218', NULL, '92429d82a41e930486c6de5ebda9602d55c39986', 'Siswa2', 'P', 'Tasikmalaya', '2021-07-26', NULL, NULL, NULL, NULL, NULL, 3, NULL, '2021-04-10 15:33:36', NULL),
(24, '9876543217', NULL, '92429d82a41e930486c6de5ebda9602d55c39986', 'Siswa3', 'L', 'Tasikmalaya', '2021-07-26', NULL, NULL, NULL, NULL, NULL, 3, NULL, '2021-04-10 15:34:20', NULL),
(26, '1234567894', NULL, '9b3b8c54bbb7cbc18673c8d8d030a05d9fdbdaf0', 'Guru1', 'L', 'Ciamis', '2021-07-14', NULL, '081234567895', NULL, NULL, NULL, 2, 'GTY/PTY', '2021-07-28 23:11:18', NULL),
(33, '9876543216', NULL, '8cc552dfd7f511dcb00778a37e31e7a88c0dcd44', 'Siswa4', 'L', 'Garut', '2021-10-01', NULL, NULL, NULL, NULL, NULL, 3, NULL, '2021-10-14 11:31:51', NULL),
(34, '9876543215', NULL, '8cc552dfd7f511dcb00778a37e31e7a88c0dcd44', 'Siswa5', 'P', 'Cilacap', '2021-10-01', NULL, NULL, NULL, NULL, NULL, 3, NULL, '2021-10-14 11:32:22', NULL),
(35, '9876543214', NULL, '8cc552dfd7f511dcb00778a37e31e7a88c0dcd44', 'Siswa6', 'P', 'Bekasi', '2021-10-01', NULL, NULL, NULL, NULL, NULL, 3, NULL, '2021-10-14 11:33:01', NULL),
(86, '2039267768', NULL, 'ded282038ce230a0f06993d24a5bddcd53f1fd86', 'Siswa7', 'P', 'Tasikmalaya', '2021-10-27', NULL, NULL, NULL, NULL, NULL, 3, NULL, '2021-10-27 17:20:30', NULL),
(87, '2057197025', NULL, 'ded282038ce230a0f06993d24a5bddcd53f1fd86', 'Siswa8', 'L', 'Tasikmalaya', '2021-10-27', NULL, NULL, NULL, NULL, NULL, 3, NULL, '2021-10-27 17:20:30', NULL),
(88, '2092752203', NULL, 'ded282038ce230a0f06993d24a5bddcd53f1fd86', 'Siswa9', 'P', 'Tasikmalaya', '2021-10-27', NULL, NULL, NULL, NULL, NULL, 3, NULL, '2021-10-27 17:20:30', NULL),
(89, '2015579599', NULL, 'ded282038ce230a0f06993d24a5bddcd53f1fd86', 'Siswa10', 'L', 'Tasikmalaya', '2021-10-27', NULL, NULL, NULL, NULL, NULL, 3, NULL, '2021-10-27 17:20:30', NULL),
(90, '2017683169', NULL, 'ded282038ce230a0f06993d24a5bddcd53f1fd86', 'Siswa11', 'P', 'Tasikmalaya', '2021-10-27', NULL, NULL, NULL, NULL, NULL, 3, NULL, '2021-10-27 17:20:30', NULL),
(91, '2051247115', NULL, 'ded282038ce230a0f06993d24a5bddcd53f1fd86', 'Siswa12', 'L', 'Tasikmalaya', '2021-10-27', NULL, NULL, NULL, NULL, NULL, 3, NULL, '2021-10-27 17:20:30', NULL),
(92, '2041649374', NULL, 'ded282038ce230a0f06993d24a5bddcd53f1fd86', 'Siswa13', 'P', 'Tasikmalaya', '2021-10-27', NULL, NULL, NULL, NULL, NULL, 3, NULL, '2021-10-27 17:20:30', NULL),
(93, '2075159924', NULL, 'ded282038ce230a0f06993d24a5bddcd53f1fd86', 'Siswa14', 'L', 'Tasikmalaya', '2021-10-27', NULL, NULL, NULL, NULL, NULL, 3, NULL, '2021-10-27 17:20:30', NULL),
(94, '2030324152', NULL, 'ded282038ce230a0f06993d24a5bddcd53f1fd86', 'Siswa15', 'P', 'Tasikmalaya', '2021-10-27', NULL, NULL, NULL, NULL, NULL, 3, NULL, '2021-10-27 17:20:30', NULL),
(95, '2016560787', NULL, 'ded282038ce230a0f06993d24a5bddcd53f1fd86', 'Siswa16', 'L', 'Tasikmalaya', '2021-10-27', NULL, NULL, NULL, NULL, NULL, 3, NULL, '2021-10-27 17:20:30', NULL),
(96, '2011345696', NULL, 'ded282038ce230a0f06993d24a5bddcd53f1fd86', 'Siswa17', 'P', 'Tasikmalaya', '2021-10-27', NULL, NULL, NULL, NULL, NULL, 3, NULL, '2021-10-27 17:20:30', NULL),
(97, '2055086202', NULL, 'ded282038ce230a0f06993d24a5bddcd53f1fd86', 'Siswa18', 'L', 'Tasikmalaya', '2021-10-27', NULL, NULL, NULL, NULL, NULL, 3, NULL, '2021-10-27 17:20:30', NULL),
(98, '2015417796', NULL, 'ded282038ce230a0f06993d24a5bddcd53f1fd86', 'Siswa19', 'P', 'Tasikmalaya', '2021-10-27', NULL, NULL, NULL, NULL, NULL, 3, NULL, '2021-10-27 17:20:30', NULL),
(99, '2014753683', NULL, 'ded282038ce230a0f06993d24a5bddcd53f1fd86', 'Siswa20', 'L', 'Tasikmalaya', '2021-10-27', NULL, NULL, NULL, NULL, NULL, 3, NULL, '2021-10-27 17:20:30', NULL),
(100, '2020472414', NULL, 'ded282038ce230a0f06993d24a5bddcd53f1fd86', 'Siswa21', 'P', 'Tasikmalaya', '2021-10-27', NULL, NULL, NULL, NULL, NULL, 3, NULL, '2021-10-27 17:20:30', NULL),
(101, '2012459009', NULL, 'ded282038ce230a0f06993d24a5bddcd53f1fd86', 'Siswa22', 'L', 'Tasikmalaya', '2021-10-27', NULL, NULL, NULL, NULL, NULL, 3, NULL, '2021-10-27 17:20:30', NULL),
(102, '2011989478', NULL, 'ded282038ce230a0f06993d24a5bddcd53f1fd86', 'Siswa23', 'P', 'Tasikmalaya', '2021-10-27', NULL, NULL, NULL, NULL, NULL, 3, NULL, '2021-10-27 17:20:30', NULL),
(103, '2017396967', NULL, 'ded282038ce230a0f06993d24a5bddcd53f1fd86', 'Siswa24', 'L', 'Tasikmalaya', '2021-10-27', NULL, NULL, NULL, NULL, NULL, 3, NULL, '2021-10-27 17:20:30', NULL),
(104, '2016460944', NULL, 'ded282038ce230a0f06993d24a5bddcd53f1fd86', 'Siswa25', 'P', 'Tasikmalaya', '2021-10-27', NULL, NULL, NULL, NULL, NULL, 3, NULL, '2021-10-27 17:20:30', NULL),
(105, '2010528624', NULL, 'ded282038ce230a0f06993d24a5bddcd53f1fd86', 'Siswa26', 'L', 'Tasikmalaya', '2021-10-27', NULL, NULL, NULL, NULL, NULL, 3, NULL, '2021-10-27 17:20:30', NULL),
(106, '2019056791', NULL, 'ded282038ce230a0f06993d24a5bddcd53f1fd86', 'Siswa27', 'P', 'Tasikmalaya', '2021-10-27', NULL, NULL, NULL, NULL, NULL, 3, NULL, '2021-10-27 17:20:30', NULL),
(107, '2079794943', NULL, 'ded282038ce230a0f06993d24a5bddcd53f1fd86', 'Siswa28', 'L', 'Tasikmalaya', '2021-10-27', NULL, NULL, NULL, NULL, NULL, 3, NULL, '2021-10-27 17:20:30', NULL),
(108, '2010849297', NULL, 'ded282038ce230a0f06993d24a5bddcd53f1fd86', 'Siswa29', 'P', 'Tasikmalaya', '2021-10-27', NULL, NULL, NULL, NULL, NULL, 3, NULL, '2021-10-27 17:20:30', NULL),
(109, '2011498094', NULL, 'ded282038ce230a0f06993d24a5bddcd53f1fd86', 'Siswa30', 'L', 'Tasikmalaya', '2021-10-27', NULL, NULL, NULL, NULL, NULL, 3, NULL, '2021-10-27 17:20:30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_access`
--

CREATE TABLE `user_access` (
  `user_access_id` int(11) NOT NULL,
  `user_type_id` int(11) NOT NULL,
  `sub_menu_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_access`
--

INSERT INTO `user_access` (`user_access_id`, `user_type_id`, `sub_menu_id`) VALUES
(2, 1, 9),
(3, 1, 10),
(4, 1, 13),
(5, 1, 15),
(6, 1, 18),
(8, 1, 3),
(9, 1, 4),
(10, 1, 5),
(38, 1, 30),
(39, 1, 2),
(40, 1, 32);

-- --------------------------------------------------------

--
-- Table structure for table `user_type`
--

CREATE TABLE `user_type` (
  `user_type_id` int(11) NOT NULL,
  `type_name` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_type`
--

INSERT INTO `user_type` (`user_type_id`, `type_name`) VALUES
(1, 'Administrator'),
(2, 'Guru'),
(3, 'Siswa'),
(4, 'Guru Piket');

-- --------------------------------------------------------

--
-- Table structure for table `wali_kelas`
--

CREATE TABLE `wali_kelas` (
  `wali_kelas_id` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_tahun_pelajaran` int(11) NOT NULL,
  `id_kelas` int(11) NOT NULL,
  `delete_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `wali_kelas`
--

INSERT INTO `wali_kelas` (`wali_kelas_id`, `id_user`, `id_tahun_pelajaran`, `id_kelas`, `delete_at`) VALUES
(155, 22, 20, 1, NULL),
(162, 21, 20, 102, NULL),
(163, 20, 20, 103, NULL),
(164, 26, 20, 104, NULL),
(165, NULL, 20, 105, NULL),
(171, NULL, 20, 106, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `guru_piket`
--
ALTER TABLE `guru_piket`
  ADD PRIMARY KEY (`id_guru_piket`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_detail_guru_piket` (`id_detail_guru_piket`),
  ADD KEY `id_tahun_pelajaran` (`id_tahun_pelajaran`);

--
-- Indexes for table `image_slider`
--
ALTER TABLE `image_slider`
  ADD PRIMARY KEY (`id_slider`);

--
-- Indexes for table `jadwal_pelajaran`
--
ALTER TABLE `jadwal_pelajaran`
  ADD PRIMARY KEY (`jadwal_pelajaran_id`),
  ADD KEY `id_tahun_pelajaran` (`id_tahun_pelajaran`),
  ADD KEY `id_kelas` (`id_kelas`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_mata_pelajaran` (`id_mata_pelajaran`);

--
-- Indexes for table `jam_pelajaran`
--
ALTER TABLE `jam_pelajaran`
  ADD PRIMARY KEY (`jam_pelajaran_id`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`kelas_id`),
  ADD KEY `id_tingkatan_kelas` (`id_tingkat_kelas`);

--
-- Indexes for table `list_tables`
--
ALTER TABLE `list_tables`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mata_pelajaran`
--
ALTER TABLE `mata_pelajaran`
  ADD PRIMARY KEY (`mapel_id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`menu_id`);

--
-- Indexes for table `pengaturan`
--
ALTER TABLE `pengaturan`
  ADD PRIMARY KEY (`id_pengaturan`);

--
-- Indexes for table `pengumuman`
--
ALTER TABLE `pengumuman`
  ADD PRIMARY KEY (`id_pengumuman`),
  ADD KEY `user_type_id` (`user_type_id`);

--
-- Indexes for table `presensi`
--
ALTER TABLE `presensi`
  ADD PRIMARY KEY (`presensi_id`),
  ADD KEY `id_jadwal_pelajaran` (`id_jadwal_pelajaran`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_tapel` (`id_tapel`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`siswa_id`),
  ADD UNIQUE KEY `nohp_ayah` (`nohp_ayah`),
  ADD UNIQUE KEY `nohp_ibu` (`nohp_ibu`),
  ADD UNIQUE KEY `nohp_wali` (`nohp_wali`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_kelas` (`id_kelas`);

--
-- Indexes for table `sub_menu`
--
ALTER TABLE `sub_menu`
  ADD PRIMARY KEY (`sub_menu_id`),
  ADD KEY `menu_id` (`menu_id`);

--
-- Indexes for table `tahun_pelajaran`
--
ALTER TABLE `tahun_pelajaran`
  ADD PRIMARY KEY (`tahun_pelajaran_id`);

--
-- Indexes for table `tanggal_semester`
--
ALTER TABLE `tanggal_semester`
  ADD PRIMARY KEY (`id_tanggal_semester`),
  ADD KEY `id_tahun_pelajaran` (`id_tahun_pelajaran`);

--
-- Indexes for table `tingkat_kelas`
--
ALTER TABLE `tingkat_kelas`
  ADD PRIMARY KEY (`tingkat_kelas_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `no_induk` (`no_induk`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD KEY `user_id` (`user_type_id`);

--
-- Indexes for table `user_access`
--
ALTER TABLE `user_access`
  ADD PRIMARY KEY (`user_access_id`),
  ADD KEY `user_type_id` (`user_type_id`),
  ADD KEY `sub_menu_id` (`sub_menu_id`);

--
-- Indexes for table `user_type`
--
ALTER TABLE `user_type`
  ADD PRIMARY KEY (`user_type_id`);

--
-- Indexes for table `wali_kelas`
--
ALTER TABLE `wali_kelas`
  ADD PRIMARY KEY (`wali_kelas_id`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_tahun_pelajaran` (`id_tahun_pelajaran`),
  ADD KEY `id_kelas` (`id_kelas`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `guru_piket`
--
ALTER TABLE `guru_piket`
  MODIFY `id_guru_piket` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `image_slider`
--
ALTER TABLE `image_slider`
  MODIFY `id_slider` int(11) NOT NULL AUTO_INCREMENT COMMENT '1 = Logo Sekolah', AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `jadwal_pelajaran`
--
ALTER TABLE `jadwal_pelajaran`
  MODIFY `jadwal_pelajaran_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `jam_pelajaran`
--
ALTER TABLE `jam_pelajaran`
  MODIFY `jam_pelajaran_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `kelas_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT for table `list_tables`
--
ALTER TABLE `list_tables`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `mata_pelajaran`
--
ALTER TABLE `mata_pelajaran`
  MODIFY `mapel_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `menu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `pengaturan`
--
ALTER TABLE `pengaturan`
  MODIFY `id_pengaturan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pengumuman`
--
ALTER TABLE `pengumuman`
  MODIFY `id_pengumuman` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `presensi`
--
ALTER TABLE `presensi`
  MODIFY `presensi_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=254;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `siswa_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `sub_menu`
--
ALTER TABLE `sub_menu`
  MODIFY `sub_menu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `tahun_pelajaran`
--
ALTER TABLE `tahun_pelajaran`
  MODIFY `tahun_pelajaran_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `tanggal_semester`
--
ALTER TABLE `tanggal_semester`
  MODIFY `id_tanggal_semester` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tingkat_kelas`
--
ALTER TABLE `tingkat_kelas`
  MODIFY `tingkat_kelas_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT for table `user_access`
--
ALTER TABLE `user_access`
  MODIFY `user_access_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `user_type`
--
ALTER TABLE `user_type`
  MODIFY `user_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `wali_kelas`
--
ALTER TABLE `wali_kelas`
  MODIFY `wali_kelas_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=172;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `guru_piket`
--
ALTER TABLE `guru_piket`
  ADD CONSTRAINT `guru_piket_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `guru_piket_ibfk_2` FOREIGN KEY (`id_tahun_pelajaran`) REFERENCES `tahun_pelajaran` (`tahun_pelajaran_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `guru_piket_ibfk_3` FOREIGN KEY (`id_detail_guru_piket`) REFERENCES `guru_piket` (`id_guru_piket`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `jadwal_pelajaran`
--
ALTER TABLE `jadwal_pelajaran`
  ADD CONSTRAINT `jadwal_pelajaran_ibfk_1` FOREIGN KEY (`id_tahun_pelajaran`) REFERENCES `tahun_pelajaran` (`tahun_pelajaran_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `jadwal_pelajaran_ibfk_2` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`kelas_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `jadwal_pelajaran_ibfk_3` FOREIGN KEY (`id_user`) REFERENCES `user` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `jadwal_pelajaran_ibfk_4` FOREIGN KEY (`id_mata_pelajaran`) REFERENCES `mata_pelajaran` (`mapel_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kelas`
--
ALTER TABLE `kelas`
  ADD CONSTRAINT `kelas_ibfk_1` FOREIGN KEY (`id_tingkat_kelas`) REFERENCES `tingkat_kelas` (`tingkat_kelas_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `pengumuman`
--
ALTER TABLE `pengumuman`
  ADD CONSTRAINT `pengumuman_ibfk_1` FOREIGN KEY (`user_type_id`) REFERENCES `user_type` (`user_type_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `presensi`
--
ALTER TABLE `presensi`
  ADD CONSTRAINT `presensi_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `presensi_ibfk_2` FOREIGN KEY (`id_jadwal_pelajaran`) REFERENCES `jadwal_pelajaran` (`jadwal_pelajaran_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `presensi_ibfk_3` FOREIGN KEY (`id_tapel`) REFERENCES `tahun_pelajaran` (`tahun_pelajaran_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `siswa`
--
ALTER TABLE `siswa`
  ADD CONSTRAINT `siswa_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `siswa_ibfk_2` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`kelas_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `sub_menu`
--
ALTER TABLE `sub_menu`
  ADD CONSTRAINT `sub_menu_ibfk_1` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`menu_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tanggal_semester`
--
ALTER TABLE `tanggal_semester`
  ADD CONSTRAINT `tanggal_semester_ibfk_1` FOREIGN KEY (`id_tahun_pelajaran`) REFERENCES `tahun_pelajaran` (`tahun_pelajaran_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`user_type_id`) REFERENCES `user_type` (`user_type_id`);

--
-- Constraints for table `user_access`
--
ALTER TABLE `user_access`
  ADD CONSTRAINT `user_access_ibfk_1` FOREIGN KEY (`user_type_id`) REFERENCES `user_type` (`user_type_id`),
  ADD CONSTRAINT `user_access_ibfk_2` FOREIGN KEY (`sub_menu_id`) REFERENCES `sub_menu` (`sub_menu_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `wali_kelas`
--
ALTER TABLE `wali_kelas`
  ADD CONSTRAINT `wali_kelas_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `wali_kelas_ibfk_2` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`kelas_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `wali_kelas_ibfk_3` FOREIGN KEY (`id_tahun_pelajaran`) REFERENCES `tahun_pelajaran` (`tahun_pelajaran_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
