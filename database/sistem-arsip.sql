-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 02, 2025 at 08:09 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sistem-arsip`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_dokumen`
--

CREATE TABLE `tb_dokumen` (
  `id_dokumen` int(11) NOT NULL,
  `instansi` varchar(100) NOT NULL,
  `tanggal` date NOT NULL,
  `kategori` varchar(30) NOT NULL,
  `nama_file` varchar(30) NOT NULL,
  `id_loker` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_dokumen`
--

INSERT INTO `tb_dokumen` (`id_dokumen`, `instansi`, `tanggal`, `kategori`, `nama_file`, `id_loker`) VALUES
(8, 'Ikatan Mahasiswa Muhammadiyah Sabilul Hasbi Teknik dan Sains', '2025-06-09', 'Yayasan', 'uploads/RD MUSYKOM XII.pdf', 'DKN-001A'),
(9, 'Pimpinan Daerah Muhammadiyah Kabupaten Cilacap', '2025-07-05', 'Yayasan', 'uploads/Soal UAS Ridho (Genap,', 'DKN-002A');

-- --------------------------------------------------------

--
-- Table structure for table `tb_instansi`
--

CREATE TABLE `tb_instansi` (
  `id_instansi` int(11) NOT NULL,
  `nama_instansi` varchar(255) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `kategori` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_instansi`
--

INSERT INTO `tb_instansi` (`id_instansi`, `nama_instansi`, `alamat`, `kategori`) VALUES
(1, 'Dinas Pendidikan dan Kebudayaan Kabupaten Cilacap', 'Jl. Kalimantan No.51, Karangbadar Kidul, Gunungsimping, Kec. Cilacap Tengah, Kabupaten Cilacap, Jawa Tengah 53211', 'Dinas Pendidikan'),
(2, 'Cabang Dinas Pendidikan Wilayah X Provinsi Jawa Tengah', 'Karangjengkol, Sokanegara, Kec. Purwokerto Tim., Kabupaten Banyumas, Jawa Tengah 53115', 'Dinas Pendidikan'),
(3, 'Pemerintah Kabupaten Cilacap', 'Jl. MT. Haryono No.29, Prenca, Donan, Kec. Cilacap Tengah, Kabupaten Cilacap, Jawa Tengah 53213', 'Instansi Pemerintah'),
(4, 'Pimpinan Daerah Muhammadiyah Kabupaten Cilacap', 'Kandang Macan, Tegalreja, Kec. Cilacap Sel., Kabupaten Cilacap, Jawa Tengah 53213', 'Instansi Pemerintah'),
(5, 'Pimpinan Cabang Muhammadiyah Sampang', 'Jl. Tugu Barat No. 24 Sampang Cilacap', 'Yayasan'),
(6, 'Pimpinan Cabang Aisiyah Sampang', 'Jl. Tugu Barat No. 24 Sampang Cilacap', 'Yayasan');

-- --------------------------------------------------------

--
-- Table structure for table `tb_kode`
--

CREATE TABLE `tb_kode` (
  `id_kode` int(11) NOT NULL,
  `kode_surat` varchar(30) NOT NULL,
  `pokok_kode` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_kode`
--

INSERT INTO `tb_kode` (`id_kode`, `kode_surat`, `pokok_kode`) VALUES
(7, 'A', 'Umum dan Tata Usaha'),
(8, 'B', 'Organisasi'),
(9, 'C', 'Keuangan, Perlengkapan/Perbekalan');

-- --------------------------------------------------------

--
-- Table structure for table `tb_loker`
--

CREATE TABLE `tb_loker` (
  `id_loker` varchar(30) NOT NULL,
  `loker` varchar(30) NOT NULL,
  `kategori_loker` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_loker`
--

INSERT INTO `tb_loker` (`id_loker`, `loker`, `kategori_loker`) VALUES
('2', 'DKN-001A', 'Loker Arsip Dokumen'),
('3', 'SM-001A', 'Loker Surat Masuk'),
('4', 'SK-001A', 'Loker Surat Keluar'),
('5', 'DKN-002A', 'Loker Arsip Dokumen');

-- --------------------------------------------------------

--
-- Table structure for table `tb_masuk`
--

CREATE TABLE `tb_masuk` (
  `id_masuk` int(11) NOT NULL,
  `nomor` varchar(30) NOT NULL,
  `instansi` varchar(255) NOT NULL,
  `tanggal` date NOT NULL,
  `kategori` varchar(30) NOT NULL,
  `hal` varchar(255) NOT NULL,
  `nama_file` varchar(30) NOT NULL,
  `id_loker` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_masuk`
--

INSERT INTO `tb_masuk` (`id_masuk`, `nomor`, `instansi`, `tanggal`, `kategori`, `hal`, `nama_file`, `id_loker`) VALUES
(8, '22/A-6/Panpel/V/2025', 'Dinas Pendidikan wilayah X Provinsi Jawa Tengah', '2025-09-30', 'Dinas Pendidikan', 'Pengangkatan Kepala Sekolah', 'uploads/Arsip - SMK.pdf', 'SM-001A');

-- --------------------------------------------------------

--
-- Table structure for table `tb_perihal`
--

CREATE TABLE `tb_perihal` (
  `id_perihal` int(11) NOT NULL,
  `tentang` varchar(255) NOT NULL,
  `judul` varchar(50) NOT NULL,
  `kategori` varchar(30) NOT NULL,
  `pembuka` varchar(500) NOT NULL,
  `isi` varchar(500) NOT NULL,
  `penutup` varchar(500) NOT NULL,
  `memperhatikan` varchar(500) NOT NULL,
  `menimbang` varchar(500) NOT NULL,
  `mengingat` varchar(500) NOT NULL,
  `menetapkan_2` varchar(500) NOT NULL,
  `menetapkan_3` varchar(500) NOT NULL,
  `menetapkan_4` varchar(500) NOT NULL,
  `menetapkan_5` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_perihal`
--

INSERT INTO `tb_perihal` (`id_perihal`, `tentang`, `judul`, `kategori`, `pembuka`, `isi`, `penutup`, `memperhatikan`, `menimbang`, `mengingat`, `menetapkan_2`, `menetapkan_3`, `menetapkan_4`, `menetapkan_5`) VALUES
(3, 'Penugasan Guru/Karyawan SMK Muhammadiyah Sampang Kabupaten Cilacap', 'keputusan Guru dan Karyawan', 'sk', 'Keputusan Kepala Sekolah Menengah Kejuruan (SMK) Muhammadiyah Sampang Kabupaten Cilacap :', '', 'Surat keputusan ini disampaikan kepada yang bersangkutan untuk diketahui dan diperhatikan/ dipergunakan sebagaimana mestinya.', '1. Surat Keputusan Kepala Sekolah SMK Muhammadiyah Sampang Kabupaten Cilacap prihal tugas/bekerja.\r\n2. Usulan Pimpinan Cabang Muhammadiyah Sampang Kabupaten Cilacap prihal penataan administrasi kepegawaian sekolah.', '1. Bahwa untuk kelancaran kegiatan Pendidikan dan Pengajaran di sekolah Muhammadiyah, diperlukan Tenaga pengajar dan tenaga kependidikan.\r\n2. Bahwa berdasarkan wawancara dan penilaian kepala sekolah, atas nama yang tersebut dibawah ini dipandang cakap dan memenuhi syarat sebagai pengajar/pendidik/tenaga kependidikan di SMK Muhammadiyah Sampang.', '1. Undang - undang no. 20 Tahun 2003 tentang Sikdiknas\r\n2. Permendikbud nomor 15 tahun 2018 tentang Pemenuhan Beban Kerja Guru, Kepala Sekolah dan Pengawas Sekolah.\r\n3. AD/ART Persyarikatan Muhammadiyah.\r\n4. Qoidah Pendidikan Dasar dan Menengah Muhammadiyah bab VII pasal 26 dan 27.', 'Selama melaksanakan tugas, kepadanya diwajibkan mentaati peraturan yang berlaku, baik yang berasal dari Pemerintah maupun penyelenggara/pengelola sekolah.', 'Selama melaksanakan tugas, kepadanya diberikan nafkah/honorarium yang besarnya ditentukan oleh Penyelenggara sekolah.', 'Surat keputusan ini berlaku sejak tanggal mulai bekerja sampai dengan diterbitkan surat keputusan yang baru, dengan ketentuan bilamana terdapat kekeliruan/ perubahan akan ditinjau kembali.', ''),
(8, 'Pendirian Perpustakaan', 'Keputusan Pendirian Perpustakaan', 'sk', 'Keputusan Kepala Sekolah Menengah Kejuruan (SMK) Muhammadiyah Sampang Kabupaten Cilacap :', '', 'Surat Keputusan ini berlaku mulai sejak tanggal ditetapkan.', '', '1. Bahwa dalam upaya meningkatkan mutu layanan Pendidikan dan pembelajaran dipandang perlu meningkatkan sumber belajar baiksecara kualitas maupun kuantitas.\r\n2. Peningkatan sumber belajar dimaksud salah satu caranya dengan mendirikan Perpustakaan Sekolah.', '1. Undang - undang no. 20 Tahun 2003 Tentang Sistem Pendidikan Nasional.\r\n2. Peraturan Menteri Pendidikan Nasional Nomor 24 tahun 2007 tentang Standar Sarana dan Prasarana SD/MI, SMP/MTs, SMA/MA/SMK.\r\n3. Peraturan Pemerintah no. 17 Tahun 2010 tentang Pedoman Pengelolaan Pendidikan.\r\nKeputusan Rapat Sekolah SMK Muhammadiyah Sampang 20 Juli 2019.', 'Guna menjamin keberlangsungan pendirian Perpustakaan tersebut ditindaklanjuti dengan penetuan tempat kedudukan, pembentukan struktur organisasi, penyusunan tata tertib kunjungan.', 'Penjamin mutu layanan dilakukan melalui pelatihan sumber daya manusia.', 'Surat Keputusan ini berlaku sejak tangal mulai bekerja sampai dengan ditebitkannya surat keputusan yang baru, dengan ketentuan bilamana terdapat kekeliruan/perubahan akan ditinjau kembali.', ''),
(9, 'Presentasi SPMB', 'Surat Tugas SPMB', 'tugas', 'Yang bertanda tangan dibawah ini Kepala SMK Muhammadiyah Sampang Kabupaten Cilacap membri tugas sepenuhnya kepada:', 'Untuk mengadakan Presentasi SPMB Tahun Pelajaran 2025/2026 kelas IX pada:', 'Demikian surat tugas ini kami buat, agar dilaksanakan dan dipergunakan sebagaimana mestinya.', '', '', '', '', '', '', ''),
(10, 'Pemberitahuan Zakat Fitrah', 'Pemberitahuan Zakat', 'pemberitahuan', 'Puji syTrkur kita panjatkan kehadirat Alloh SWT yang telah memberikan taufiq dan hidayah-Nya kepada kita semua, sholawat dan salam semoga selalu tercurah kepada junjungan kita Nabi Agung Muhammad SAW dan keluarga para sahabat serta pengikutnya sampai akhir zaman- Amiin.', 'Dengan ini kami beritahukan kepada Bapak/[bu selaku orang tua/rvali rnurid kelas X . XI dan XII bahwa dalam rangka syiar SMK Muhammadiyah Sampang kami menyelenggarakan pengumpulan dan distribusi zakat fitrah. Berdasarkan hal tersebut kami berharap siswa/siswi\r\nkelas X. XI, dan XIl dapatmenyalurkanzakat fitrahnyadi sekolah dengan ketentuan sebagai\r\nberikut:', 'Demikian pemberitahuan ini kami sampaikan atas perhatian dan kerjasamanya yang baik kami ucapkan terima kasih\" semoga putra/putri Bapak/lbu menjadi anak yang sholih / sholihah', '', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `tb_sekolah`
--

CREATE TABLE `tb_sekolah` (
  `id_sekolah` int(11) NOT NULL,
  `npsn` int(11) NOT NULL,
  `nama_sekolah` varchar(30) NOT NULL,
  `alamat` text NOT NULL,
  `telepon` varchar(20) NOT NULL,
  `nama_kepala` varchar(30) NOT NULL,
  `nip` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `logo` varchar(30) NOT NULL,
  `majelis` varchar(100) NOT NULL,
  `yayasan` varchar(100) NOT NULL,
  `kelompok` varchar(100) NOT NULL,
  `kode_pos` int(11) NOT NULL,
  `kecamatan` varchar(10) NOT NULL,
  `web` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_sekolah`
--

INSERT INTO `tb_sekolah` (`id_sekolah`, `npsn`, `nama_sekolah`, `alamat`, `telepon`, `nama_kepala`, `nip`, `email`, `logo`, `majelis`, `yayasan`, `kelompok`, `kode_pos`, `kecamatan`, `web`) VALUES
(1, 20300710, 'SMK Muhammadiyah Sampang', 'Jalan Raya Tugu Barat no. 24 Sampang - Cilacap', '(0282) 697051', 'Budi Martanto, S.S.', '1084 462', 'smkmuhammadiyahsampang@yahoo.com', 'uploads/smk.png', 'Majelis Pendidikan Dasar Menengah dan Pendidikan Non Formal', 'Pimpinan Daerah Muhammadiyah Cilacap', 'Kelompok Teknologi Manufaktur & Rekayasa, Teknologi Informasi, Bisnis Manajemen', 53273, 'Sampang', 'smkmuhammadiyahsampang.sch.id');

-- --------------------------------------------------------

--
-- Table structure for table `tb_sk`
--

CREATE TABLE `tb_sk` (
  `id_sk` int(11) NOT NULL,
  `judul` varchar(50) NOT NULL,
  `tentang` varchar(255) NOT NULL,
  `pembuka` varchar(500) NOT NULL,
  `memperhatikan` varchar(500) NOT NULL,
  `menimbang` varchar(500) NOT NULL,
  `meningat` varchar(500) NOT NULL,
  `menetapkan_2` varchar(500) NOT NULL,
  `menetapkan_3` varchar(500) NOT NULL,
  `menetapkan_4` varchar(500) NOT NULL,
  `menetapkan_5` varchar(500) NOT NULL,
  `penutup` varchar(500) NOT NULL,
  `isi` varchar(500) NOT NULL,
  `id_perihal` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `nomor` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_sppd`
--

CREATE TABLE `tb_sppd` (
  `id_keluar` int(11) NOT NULL,
  `nomor` int(11) NOT NULL,
  `nama_surat` varchar(50) NOT NULL,
  `tanggal` date NOT NULL,
  `hal` varchar(255) NOT NULL,
  `pejabat_perintah` varchar(255) NOT NULL,
  `pegawai` varchar(255) NOT NULL,
  `pangkat` varchar(30) NOT NULL,
  `jabatan` varchar(30) NOT NULL,
  `transportasi` varchar(30) NOT NULL,
  `tempat_berangkat` varchar(255) NOT NULL,
  `tempat_tujuan` varchar(255) NOT NULL,
  `lama_jalan` date NOT NULL,
  `pengikut` varchar(255) NOT NULL,
  `ang_instansi` varchar(255) NOT NULL,
  `ang_sumber` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_suket`
--

CREATE TABLE `tb_suket` (
  `id_suket` int(11) NOT NULL,
  `nomor` varchar(30) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `ttl` varchar(30) NOT NULL,
  `nomor_induk` int(11) NOT NULL,
  `nama_ortu` varchar(50) NOT NULL,
  `asal_sekolah` varchar(50) NOT NULL,
  `isi` varchar(500) NOT NULL,
  `penutup` varchar(500) NOT NULL,
  `pembuka` varchar(500) NOT NULL,
  `id_perihal` int(11) NOT NULL,
  `judul` varchar(50) NOT NULL,
  `tentang` varchar(255) NOT NULL,
  `tanggal` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_suratbiasa`
--

CREATE TABLE `tb_suratbiasa` (
  `id_surat` int(11) NOT NULL,
  `judul` varchar(50) NOT NULL,
  `pembuka` varchar(500) NOT NULL,
  `isi` varchar(500) NOT NULL,
  `penutup` varchar(500) NOT NULL,
  `tentang` varchar(255) NOT NULL,
  `tujuan` varchar(100) NOT NULL,
  `tanggal` date NOT NULL,
  `id_perihal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_tugas`
--

CREATE TABLE `tb_tugas` (
  `id_tugas` int(11) NOT NULL,
  `nomor` varchar(30) NOT NULL,
  `tanggal` date NOT NULL,
  `pegawai` varchar(255) NOT NULL,
  `jabatan` varchar(30) NOT NULL,
  `unit_kerja` varchar(30) NOT NULL,
  `waktu` varchar(30) NOT NULL,
  `tempat_tujuan` varchar(255) NOT NULL,
  `level` int(11) NOT NULL,
  `pembuka` varchar(500) NOT NULL,
  `isi` varchar(500) NOT NULL,
  `penutup` varchar(500) NOT NULL,
  `id_perihal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_users`
--

CREATE TABLE `tb_users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(30) NOT NULL,
  `nama_pengguna` varchar(30) NOT NULL,
  `nbm` int(11) NOT NULL,
  `level` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_users`
--

INSERT INTO `tb_users` (`id_user`, `username`, `password`, `nama_pengguna`, `nbm`, `level`) VALUES
(1, 'admin', 'admin', 'Apri Rianto', 111111, 'admin'),
(2, 'kepala', 'kepala', 'Budi Martanto, S.S.', 1084462, 'kepala'),
(7, 'tu', 'tu', 'Samingan', 669491, 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_dokumen`
--
ALTER TABLE `tb_dokumen`
  ADD PRIMARY KEY (`id_dokumen`);

--
-- Indexes for table `tb_instansi`
--
ALTER TABLE `tb_instansi`
  ADD PRIMARY KEY (`id_instansi`);

--
-- Indexes for table `tb_kode`
--
ALTER TABLE `tb_kode`
  ADD PRIMARY KEY (`id_kode`);

--
-- Indexes for table `tb_loker`
--
ALTER TABLE `tb_loker`
  ADD PRIMARY KEY (`id_loker`);

--
-- Indexes for table `tb_masuk`
--
ALTER TABLE `tb_masuk`
  ADD PRIMARY KEY (`id_masuk`);

--
-- Indexes for table `tb_perihal`
--
ALTER TABLE `tb_perihal`
  ADD PRIMARY KEY (`id_perihal`);

--
-- Indexes for table `tb_sekolah`
--
ALTER TABLE `tb_sekolah`
  ADD PRIMARY KEY (`id_sekolah`);

--
-- Indexes for table `tb_sk`
--
ALTER TABLE `tb_sk`
  ADD PRIMARY KEY (`id_sk`);

--
-- Indexes for table `tb_sppd`
--
ALTER TABLE `tb_sppd`
  ADD PRIMARY KEY (`id_keluar`);

--
-- Indexes for table `tb_suket`
--
ALTER TABLE `tb_suket`
  ADD PRIMARY KEY (`id_suket`);

--
-- Indexes for table `tb_suratbiasa`
--
ALTER TABLE `tb_suratbiasa`
  ADD PRIMARY KEY (`id_surat`);

--
-- Indexes for table `tb_tugas`
--
ALTER TABLE `tb_tugas`
  ADD PRIMARY KEY (`id_tugas`);

--
-- Indexes for table `tb_users`
--
ALTER TABLE `tb_users`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_dokumen`
--
ALTER TABLE `tb_dokumen`
  MODIFY `id_dokumen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tb_instansi`
--
ALTER TABLE `tb_instansi`
  MODIFY `id_instansi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tb_kode`
--
ALTER TABLE `tb_kode`
  MODIFY `id_kode` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tb_masuk`
--
ALTER TABLE `tb_masuk`
  MODIFY `id_masuk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tb_perihal`
--
ALTER TABLE `tb_perihal`
  MODIFY `id_perihal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tb_sekolah`
--
ALTER TABLE `tb_sekolah`
  MODIFY `id_sekolah` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tb_sk`
--
ALTER TABLE `tb_sk`
  MODIFY `id_sk` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_sppd`
--
ALTER TABLE `tb_sppd`
  MODIFY `id_keluar` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_suket`
--
ALTER TABLE `tb_suket`
  MODIFY `id_suket` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_suratbiasa`
--
ALTER TABLE `tb_suratbiasa`
  MODIFY `id_surat` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_tugas`
--
ALTER TABLE `tb_tugas`
  MODIFY `id_tugas` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_users`
--
ALTER TABLE `tb_users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
