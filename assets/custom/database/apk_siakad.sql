-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 26, 2021 at 05:23 AM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 7.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `apk_siakad`
--

-- --------------------------------------------------------

--
-- Table structure for table `image_slider`
--

CREATE TABLE `image_slider` (
  `id_slider` int(11) NOT NULL,
  `gambar` varchar(16) NOT NULL,
  `is_aktif` enum('Y','N') NOT NULL,
  `sort` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `image_slider`
--

INSERT INTO `image_slider` (`id_slider`, `gambar`, `is_aktif`, `sort`) VALUES
(7, '1616686221.png', 'N', NULL),
(8, '1616685917.jpg', 'Y', 1616689375);

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_pelajaran`
--

CREATE TABLE `jadwal_pelajaran` (
  `jadwal_pelajaran_id` int(11) NOT NULL,
  `id_tahun_pelajaran` int(11) NOT NULL,
  `id_kelas` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL COMMENT 'guru',
  `id_mata_pelajaran` int(11) NOT NULL,
  `hari` int(11) DEFAULT NULL,
  `mulai` int(11) DEFAULT NULL,
  `selesai` int(11) DEFAULT NULL,
  `sort` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `jadwal_pelajaran`
--

INSERT INTO `jadwal_pelajaran` (`jadwal_pelajaran_id`, `id_tahun_pelajaran`, `id_kelas`, `id_user`, `id_mata_pelajaran`, `hari`, `mulai`, `selesai`, `sort`) VALUES
(164, 15, 1, NULL, 1, NULL, NULL, NULL, NULL),
(165, 15, 1, NULL, 22, NULL, NULL, NULL, NULL),
(166, 15, 1, NULL, 23, NULL, NULL, NULL, NULL),
(167, 15, 1, 14, 24, 2, 1, 19, 700);

-- --------------------------------------------------------

--
-- Table structure for table `jam_pelajaran`
--

CREATE TABLE `jam_pelajaran` (
  `jam_pelajaran_id` int(11) NOT NULL,
  `jam_pelajaran` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `jam_pelajaran`
--

INSERT INTO `jam_pelajaran` (`jam_pelajaran_id`, `jam_pelajaran`) VALUES
(1, '7:00'),
(7, '7:40'),
(19, '8:20'),
(20, '9:00'),
(23, '9:40'),
(37, '10:10'),
(43, '10:50'),
(44, '11:30');

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `kelas_id` int(11) NOT NULL,
  `nama_kelas` varchar(32) NOT NULL,
  `id_tingkat_kelas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`kelas_id`, `nama_kelas`, `id_tingkat_kelas`) VALUES
(1, 'VII', 1),
(2, 'VIII', 2),
(3, 'IX', 3);

-- --------------------------------------------------------

--
-- Table structure for table `lulusan`
--

CREATE TABLE `lulusan` (
  `id_lulusan` int(11) NOT NULL,
  `id_siswa` int(11) NOT NULL,
  `id_tahun_pelajaran` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `lulusan`
--

INSERT INTO `lulusan` (`id_lulusan`, `id_siswa`, `id_tahun_pelajaran`) VALUES
(6, 5, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `mata_pelajaran`
--

CREATE TABLE `mata_pelajaran` (
  `mapel_id` int(11) NOT NULL,
  `kode_mapel` varchar(16) DEFAULT NULL,
  `nama_mapel` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `mata_pelajaran`
--

INSERT INTO `mata_pelajaran` (`mapel_id`, `kode_mapel`, `nama_mapel`) VALUES
(1, 'MTK', 'Matematika'),
(22, 'INDO', 'Bahasa Indonesia'),
(23, 'IPA', 'Ilmu Pengetahuan Alam'),
(24, 'IPS', 'Ilmu Pengetahuan Sosial');

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
(2, 'Pengaturan', 'fa-cogs', NULL, '1616691000'),
(3, 'Keluar', 'fa-sign-out', 'logout', '1616691005'),
(4, 'Pengguna', 'fa-users', NULL, '1608632454'),
(5, 'Master', 'fa-folder-open', NULL, '1608632453'),
(14, 'Jadwal Pelajaran', 'fa-calendar', 'schedules', '1608632455'),
(30, 'Laporan', 'fa-bar-chart', NULL, '1616041148');

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
  `tanggal` date NOT NULL,
  `id_jadwal_pelajaran` int(11) NOT NULL,
  `id_user` int(11) NOT NULL COMMENT 'siswa',
  `semester` int(1) DEFAULT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `presensi`
--

INSERT INTO `presensi` (`presensi_id`, `tanggal`, `id_jadwal_pelajaran`, `id_user`, `semester`, `status`) VALUES
(6, '2021-03-22', 167, 15, 2, 1),
(7, '2021-03-23', 167, 15, 2, 1),
(9, '2021-03-24', 167, 18, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `siswa_id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_kelas` int(11) DEFAULT NULL,
  `is_aktif` int(2) NOT NULL,
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
(5, 18, NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sub_menu`
--

CREATE TABLE `sub_menu` (
  `sub_menu_id` int(11) NOT NULL,
  `sub_menu` varchar(32) NOT NULL,
  `url` varchar(64) NOT NULL,
  `sort` varchar(32) DEFAULT NULL,
  `menu_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sub_menu`
--

INSERT INTO `sub_menu` (`sub_menu_id`, `sub_menu`, `url`, `sort`, `menu_id`) VALUES
(2, 'Menu', 'setting/menu', '1608471097', 2),
(3, 'Administrator', 'user/administration', '1604645387', 4),
(4, 'Guru', 'user/teacher', '1604645703', 4),
(5, 'Siswa', 'user/student', '1604648880', 4),
(9, 'Kelas', 'master/classes', '1608902074', 5),
(10, 'Mata Pelajaran', 'master/subjects', '1608902075', 5),
(13, 'Jam Pelajaran', 'master/hours', '1608902075', 5),
(15, 'Tahun Pelajaran', 'master/years', '1608902076', 5),
(18, 'Wali Kelas', 'master/homeroom', '1608902077', 5),
(19, 'Tingkat Kelas', 'master/levels', '1608902072', 5),
(26, 'Pengumuman', 'setting/announcement', '1616040640', 2),
(27, 'Lainnya', 'setting/other', '1616721912', 2),
(28, 'Presensi Siswa', 'report/presence', '1616041206', 30),
(29, 'Slide Gambar', 'setting/slider', '1616721908', 2);

-- --------------------------------------------------------

--
-- Table structure for table `tahun_pelajaran`
--

CREATE TABLE `tahun_pelajaran` (
  `tahun_pelajaran_id` int(11) NOT NULL,
  `tahun_pelajaran` varchar(64) NOT NULL,
  `semester` int(1) DEFAULT NULL,
  `is_aktif` enum('Y','N') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tahun_pelajaran`
--

INSERT INTO `tahun_pelajaran` (`tahun_pelajaran_id`, `tahun_pelajaran`, `semester`, `is_aktif`) VALUES
(14, '2019/2020', NULL, 'N'),
(15, '2020/2021', 2, 'Y'),
(16, '2021/2022', NULL, 'N');

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
(1, '7 (Tujuh)'),
(2, '8 (Delapan)'),
(3, '9 (Sembilan)');

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
  `gender` enum('L','P') NOT NULL COMMENT 'jenis_kelamin',
  `tempat_lahir` varchar(64) DEFAULT NULL,
  `tanggal_lahir` date NOT NULL,
  `profile_pic` varchar(32) DEFAULT NULL COMMENT 'foto_profile',
  `phone` varchar(16) DEFAULT NULL COMMENT 'no_handphone',
  `agama` enum('Islam','Kristen','Hindu','Buddha','Konghucu') DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `last_active` datetime DEFAULT NULL,
  `user_type_id` int(11) NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `no_induk`, `email`, `password`, `full_name`, `gender`, `tempat_lahir`, `tanggal_lahir`, `profile_pic`, `phone`, `agama`, `alamat`, `last_active`, `user_type_id`, `date_created`) VALUES
(1, NULL, 'alamsyah@gmail.com', '92429d82a41e930486c6de5ebda9602d55c39986', 'Alamsyah Firdaus', 'L', 'Tasikmalaya', '1998-07-31', NULL, '+6289693839624', 'Islam', NULL, '2021-03-26 10:17:47', 1, '2020-11-09 11:25:19'),
(13, NULL, 'admin@siakad.com', '8860288524a9fa4c16a5ebb6a11e83677bd99354', 'Admin', 'L', 'Tasikmalaya', '2021-03-18', NULL, '+628123456789', 'Islam', NULL, NULL, 1, '2021-03-18 13:57:08'),
(14, '123456789', NULL, '92429d82a41e930486c6de5ebda9602d55c39986', 'Guru', 'L', 'Tasikmalaya', '2021-03-18', NULL, '+628123456781', 'Islam', NULL, '2021-03-26 10:28:12', 2, '2021-03-18 13:59:54'),
(15, '987654321', NULL, '92429d82a41e930486c6de5ebda9602d55c39986', 'Siswa1', 'L', 'Tasikmalaya', '2021-04-01', NULL, NULL, 'Islam', NULL, '2021-03-26 10:12:33', 3, '2021-03-18 14:01:33'),
(18, '987654322', NULL, '92429d82a41e930486c6de5ebda9602d55c39986', 'Siswa1', 'L', 'Garut', '2021-03-23', NULL, NULL, 'Islam', NULL, NULL, 3, '2021-03-23 19:47:36');

-- --------------------------------------------------------

--
-- Table structure for table `user_access`
--

CREATE TABLE `user_access` (
  `user_access_id` int(11) NOT NULL,
  `user_type_id` int(11) NOT NULL,
  `sub_menu_id` int(11) NOT NULL
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
(7, 1, 19),
(8, 1, 3),
(9, 1, 4),
(10, 1, 5),
(25, 1, 26),
(26, 1, 29),
(27, 1, 28);

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
(3, 'Siswa');

-- --------------------------------------------------------

--
-- Table structure for table `wali_kelas`
--

CREATE TABLE `wali_kelas` (
  `wali_kelas_id` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL COMMENT 'guru',
  `id_tahun_pelajaran` int(11) NOT NULL,
  `id_kelas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `wali_kelas`
--

INSERT INTO `wali_kelas` (`wali_kelas_id`, `id_user`, `id_tahun_pelajaran`, `id_kelas`) VALUES
(89, 14, 15, 1),
(90, NULL, 15, 2),
(99, NULL, 15, 3);

--
-- Indexes for dumped tables
--

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
-- Indexes for table `lulusan`
--
ALTER TABLE `lulusan`
  ADD PRIMARY KEY (`id_lulusan`),
  ADD KEY `id_siswa` (`id_siswa`),
  ADD KEY `id_tahun_pelajaran` (`id_tahun_pelajaran`);

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
  ADD KEY `id_user` (`id_user`);

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
-- AUTO_INCREMENT for table `image_slider`
--
ALTER TABLE `image_slider`
  MODIFY `id_slider` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `jadwal_pelajaran`
--
ALTER TABLE `jadwal_pelajaran`
  MODIFY `jadwal_pelajaran_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=168;

--
-- AUTO_INCREMENT for table `jam_pelajaran`
--
ALTER TABLE `jam_pelajaran`
  MODIFY `jam_pelajaran_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `kelas_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `lulusan`
--
ALTER TABLE `lulusan`
  MODIFY `id_lulusan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `mata_pelajaran`
--
ALTER TABLE `mata_pelajaran`
  MODIFY `mapel_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `menu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

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
  MODIFY `presensi_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `siswa_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `sub_menu`
--
ALTER TABLE `sub_menu`
  MODIFY `sub_menu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `tahun_pelajaran`
--
ALTER TABLE `tahun_pelajaran`
  MODIFY `tahun_pelajaran_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tingkat_kelas`
--
ALTER TABLE `tingkat_kelas`
  MODIFY `tingkat_kelas_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `user_access`
--
ALTER TABLE `user_access`
  MODIFY `user_access_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `user_type`
--
ALTER TABLE `user_type`
  MODIFY `user_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `wali_kelas`
--
ALTER TABLE `wali_kelas`
  MODIFY `wali_kelas_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- Constraints for dumped tables
--

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
-- Constraints for table `lulusan`
--
ALTER TABLE `lulusan`
  ADD CONSTRAINT `lulusan_ibfk_1` FOREIGN KEY (`id_tahun_pelajaran`) REFERENCES `tahun_pelajaran` (`tahun_pelajaran_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `lulusan_ibfk_2` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`siswa_id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
  ADD CONSTRAINT `presensi_ibfk_2` FOREIGN KEY (`id_jadwal_pelajaran`) REFERENCES `jadwal_pelajaran` (`jadwal_pelajaran_id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
