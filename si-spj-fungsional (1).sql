-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 21, 2024 at 02:09 AM
-- Server version: 8.0.30
-- PHP Version: 8.2.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `si-spj-fungsional`
--

-- --------------------------------------------------------

--
-- Table structure for table `aktivitas`
--

CREATE TABLE `aktivitas` (
  `id` bigint UNSIGNED NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `tempat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `penyelenggara` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `subkegiatan_id` bigint UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `nominal` bigint NOT NULL DEFAULT '0',
  `acc_kabag` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum',
  `acc_pptk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `aktivitas`
--

INSERT INTO `aktivitas` (`id`, `tanggal_mulai`, `tanggal_selesai`, `tempat`, `penyelenggara`, `keterangan`, `subkegiatan_id`, `deleted_at`, `created_at`, `updated_at`, `nominal`, `acc_kabag`, `acc_pptk`) VALUES
(6, '2024-08-07', '2024-08-07', 'Kec. Kolaka', 'PEMDA', 'Dalam rangka monitoring dan evaluasi terhadap progres pembangunan infrastruktur BUMD Air Minum di wilayah Kec. Kolaka.', 33, NULL, '2024-08-04 09:12:53', '2024-08-04 20:14:12', 1000000, 'Dikonfirmasi', 'Dikonfirmasi'),
(7, '2024-08-05', NULL, 'Kantor', 'BPSDA', 'Belanja Alat Tulis Kertas', 3, NULL, '2024-08-04 18:34:25', '2024-08-04 20:12:53', 50000, 'Dikonfirmasi', 'Dikonfirmasi'),
(8, '2024-08-05', NULL, 'Kantor', 'BPSDA', 'Belanja Kertas dan Cover', 4, NULL, '2024-08-04 18:35:38', '2024-08-04 18:35:38', 75000, 'belum', 'belum'),
(9, '2024-08-06', NULL, 'Kantor', 'BPSDA', 'Belanja Alat Tulis Kantor', 3, NULL, '2024-08-04 18:36:50', '2024-08-04 20:14:45', 20000, 'belum', 'Dikonfirmasi'),
(11, '2024-08-09', '2024-08-10', 'Kec. Kolaka', 'PEMDA', 'SEMINAR', 44, NULL, '2024-08-05 15:13:37', '2024-08-05 15:13:37', 500000, 'belum', 'belum'),
(12, '2024-08-12', '2024-08-13', 'Kendari', 'BPSDA Kendari', 'Seminar BUMD', 32, NULL, '2024-08-05 15:16:02', '2024-08-05 15:16:02', 2100000, 'belum', 'belum');

-- --------------------------------------------------------

--
-- Table structure for table `aktivitas_pegawais`
--

CREATE TABLE `aktivitas_pegawais` (
  `id` bigint UNSIGNED NOT NULL,
  `aktivitas_id` bigint UNSIGNED NOT NULL,
  `pegawai_id` bigint UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `aktivitas_pegawais`
--

INSERT INTO `aktivitas_pegawais` (`id`, `aktivitas_id`, `pegawai_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(3, 6, 2, NULL, '2024-08-04 09:12:53', '2024-08-04 09:12:53'),
(4, 6, 1, NULL, '2024-08-04 09:12:53', '2024-08-04 09:12:53'),
(6, 11, 3, NULL, '2024-08-05 15:13:37', '2024-08-05 15:13:37'),
(7, 12, 3, NULL, '2024-08-05 15:16:02', '2024-08-05 15:16:02'),
(8, 12, 2, NULL, '2024-08-05 15:16:02', '2024-08-05 15:16:02');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dinasluars`
--

CREATE TABLE `dinasluars` (
  `id` bigint UNSIGNED NOT NULL,
  `aktivitas_id` bigint UNSIGNED NOT NULL,
  `pegawai_id` bigint UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `bulan` int NOT NULL,
  `tahun` int NOT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dinasluars`
--

INSERT INTO `dinasluars` (`id`, `aktivitas_id`, `pegawai_id`, `tanggal`, `bulan`, `tahun`, `catatan`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 6, 2, '2024-08-07', 8, 2024, 'DL', NULL, '2024-08-04 09:12:53', '2024-08-04 09:12:53'),
(2, 6, 1, '2024-08-07', 8, 2024, 'DL', NULL, '2024-08-04 09:12:53', '2024-08-04 09:12:53'),
(3, 11, 3, '2024-08-09', 8, 2024, 'DL', NULL, '2024-08-05 15:13:37', '2024-08-05 15:13:37'),
(4, 11, 3, '2024-08-10', 8, 2024, 'DL', NULL, '2024-08-05 15:13:37', '2024-08-05 15:13:37'),
(5, 12, 3, '2024-08-12', 8, 2024, 'DL', NULL, '2024-08-05 15:16:02', '2024-08-05 15:16:02'),
(6, 12, 3, '2024-08-13', 8, 2024, 'DL', NULL, '2024-08-05 15:16:02', '2024-08-05 15:16:02'),
(7, 12, 2, '2024-08-12', 8, 2024, 'DL', NULL, '2024-08-05 15:16:02', '2024-08-05 15:16:02'),
(8, 12, 2, '2024-08-13', 8, 2024, 'DL', NULL, '2024-08-05 15:16:02', '2024-08-05 15:16:02');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kalenders`
--

CREATE TABLE `kalenders` (
  `id` bigint UNSIGNED NOT NULL,
  `tahun` int NOT NULL,
  `bulan` int NOT NULL,
  `tanggal_libur` date NOT NULL,
  `keterangan_libur` text COLLATE utf8mb4_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `pegawai_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kalenders`
--

INSERT INTO `kalenders` (`id`, `tahun`, `bulan`, `tanggal_libur`, `keterangan_libur`, `deleted_at`, `created_at`, `updated_at`, `pegawai_id`) VALUES
(1, 2024, 8, '2024-08-01', 'hari minggu', '2024-08-01 16:52:14', '2024-08-01 16:43:58', '2024-08-01 16:52:14', NULL),
(2, 2024, 8, '2024-08-28', 'Hari minggu', '2024-08-01 16:54:20', '2024-08-01 16:52:30', '2024-08-01 16:54:20', NULL),
(3, 2024, 8, '2024-08-04', 'hari minggu', NULL, '2024-08-01 16:54:29', '2024-08-01 16:54:29', NULL),
(4, 2024, 8, '2024-08-11', 'hari minggu', NULL, '2024-08-01 16:54:37', '2024-08-01 16:54:37', NULL),
(5, 2024, 8, '2024-08-18', 'hari minggu', '2024-08-01 16:58:39', '2024-08-01 16:54:45', '2024-08-01 16:58:39', NULL),
(6, 2024, 8, '2024-08-25', 'hari minggu', NULL, '2024-08-01 16:54:54', '2024-08-01 16:54:54', NULL),
(7, 2024, 7, '2024-07-07', 'hari minggu', NULL, '2024-08-01 16:55:42', '2024-08-01 16:55:42', NULL),
(8, 2024, 7, '2024-07-14', 'hari minggu', NULL, '2024-08-01 16:55:52', '2024-08-01 16:55:52', NULL),
(9, 2024, 7, '2024-07-21', 'hari minggu', NULL, '2024-08-01 16:55:59', '2024-08-01 16:55:59', NULL),
(10, 2024, 7, '2024-07-28', 'hari minggu', NULL, '2024-08-01 16:56:07', '2024-08-01 16:56:07', NULL),
(11, 2024, 9, '2024-09-01', 'hari minggu', NULL, '2024-08-01 16:59:19', '2024-08-01 16:59:19', NULL),
(12, 2024, 8, '2024-08-17', 'HUT RI ke-79 ', NULL, '2024-08-03 03:37:00', '2024-08-03 03:37:00', NULL),
(13, 2024, 8, '2024-08-10', 'Hari Sabtu', NULL, '2024-08-03 03:48:02', '2024-08-03 03:48:02', NULL),
(14, 2024, 8, '2024-08-24', 'Hari Sabtu', NULL, '2024-08-03 03:48:06', '2024-08-03 03:48:06', NULL),
(15, 2024, 8, '2024-08-31', 'Hari Sabtu', NULL, '2024-08-03 03:48:11', '2024-08-03 03:48:11', NULL),
(16, 2024, 8, '2024-08-03', 'Hari Sabtu', NULL, '2024-08-03 03:48:16', '2024-08-03 03:48:16', NULL),
(17, 2024, 8, '2024-08-18', 'Hari Sabtu', NULL, '2024-08-03 03:48:22', '2024-08-03 03:48:22', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `kegiatans`
--

CREATE TABLE `kegiatans` (
  `id` bigint UNSIGNED NOT NULL,
  `kode_rekening_kegiatan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_kegiatan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subprogram_id` bigint UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kegiatans`
--

INSERT INTO `kegiatans` (`id`, `kode_rekening_kegiatan`, `nama_kegiatan`, `subprogram_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(2, '12313', 'scasdsad', 1, '2024-08-02 07:42:17', NULL, '2024-08-02 07:42:17'),
(3, '1232131', 'asdasdasd', 9, '2024-08-02 07:41:17', NULL, '2024-08-02 07:41:17'),
(4, '4.01.01.2.02.03.5.1.01.03.07.0001', 'Belanja Honorarium Penanggungjawaban Pengelola Keuangan', 8, '2024-08-02 07:54:32', '2024-08-02 07:36:10', '2024-08-02 07:54:32'),
(5, '4.01.01.2.02.03.5.1.02.01.01.0024', 'Belanja Alat/Bahan untuk Kegiatan Kantor-Alat Tulis Kantor', 8, '2024-08-02 07:44:54', '2024-08-02 07:36:10', '2024-08-02 07:44:54'),
(6, '4.01.01.2.02.03.5.1.02.01.01.0025', 'Belanja Alat/Bahan untuk Kegiatan Kantor- Kertas dan Cover', 8, '2024-08-02 07:44:25', '2024-08-02 07:36:10', '2024-08-02 07:44:25'),
(7, '4.01.01.2.02.03.5.1.02.01.01.0026', 'Belanja Alat/Bahan untuk Kegiatan Kantor- Bahan Cetak', 8, '2024-08-02 07:43:32', '2024-08-02 07:36:10', '2024-08-02 07:43:32'),
(8, '4.01.01.2.02.03', 'Pelaksanaan Penatausahaan dan Pengujian/Verifikasi Keuangan SKPD\n', 1, '2024-08-02 07:53:41', '2024-08-02 07:45:56', '2024-08-02 07:53:41'),
(9, '4.01.01.2.05', 'Administrasi Kepegawaian Perangkat Daerah\n', 1, '2024-08-02 07:48:38', '2024-08-02 07:45:56', '2024-08-02 07:48:38'),
(10, '4.01.01.2.05.11', 'Bimbingan Teknis Implementasi Peraturan Perundang-Undangan\n', 2, '2024-08-02 07:53:35', '2024-08-02 07:48:09', '2024-08-02 07:53:35'),
(11, '4.01.01.2.02.03', 'Pelaksanaan Penatausahaan dan Pengujian/Verifikasi Keuangan SKPD\n', 1, NULL, '2024-08-02 07:54:57', '2024-08-02 07:54:57'),
(12, '4.01.01.2.05.11', 'Bimbingan Teknis Implementasi Peraturan Perundang-Undangan\n', 2, NULL, '2024-08-02 07:56:00', '2024-08-02 07:56:00'),
(13, '4.01.01.2.06.04', 'Penyediaan Bahan Logistik Kantor\n', 5, NULL, '2024-08-02 08:00:36', '2024-08-02 08:00:36'),
(14, '4.01.01.2.06.08', 'Fasilitasi Kunjungan Tamu\n', 5, NULL, '2024-08-02 08:00:36', '2024-08-02 08:00:36'),
(15, '4.01.01.2.06.09', 'Penyelenggaraan Rapat Koordinasi dan Konsultasi SKPD\n', 5, NULL, '2024-08-02 08:00:36', '2024-08-02 08:00:36'),
(16, '4.01.01.2.08.03', 'Penyediaan Jasa Peralatan dan Perlengkapan Kantor\n', 6, NULL, '2024-08-02 08:02:05', '2024-08-02 08:02:05'),
(17, '4.01.01.2.08.04', 'Penyediaan Jasa Pelayanan Umum Kantor\n', 6, NULL, '2024-08-02 08:02:05', '2024-08-02 08:02:05'),
(18, '4.01.01.2.09.02', 'Penyediaan Jasa Pemeliharaan, Biaya Pemeliharaan, Pajak, dan Perizinan Kendaraan Dinas Operasional atau Lapangan\n', 7, NULL, '2024-08-02 08:04:26', '2024-08-02 08:04:26'),
(19, '4.01.01.2.09.06', 'Pemeliharaan Peralatan dan Mesin Lainnya\n', 7, NULL, '2024-08-02 08:04:26', '2024-08-02 08:04:26'),
(20, '4.01.03.2.01.01', 'Koordinasi, Sinkronisasi, Monitoring dan Evaluasi Kebijakan Pengelolaan BUMD dan BLUD\n', 8, NULL, '2024-08-02 08:05:24', '2024-08-02 08:05:24'),
(21, '4.01.03.2.01.02', 'Pengendalian dan Distribusi Perekonomian\n', 8, NULL, '2024-08-02 08:05:24', '2024-08-02 08:05:24'),
(22, '4.01.03.2.01.03', 'Perencanaan dan Pengawasan Ekonomi Mikro kecil\n', 8, NULL, '2024-08-02 08:05:24', '2024-08-02 08:05:24'),
(23, '4.01.03.2.04.01', 'Koordinasi, Sinkronisasi, dan Evaluasi Kebijakan Pertanian, Kehutanan, Kelautan, dan Perikanan\n', 9, NULL, '2024-08-02 08:06:32', '2024-08-02 08:06:32'),
(24, '4.01.03.2.04.02', 'Koordinasi, Sinkronisasi, dan Evaluasi Kebijakan Pertambangan dan Lingkungan Hidup\n', 9, NULL, '2024-08-02 08:06:32', '2024-08-02 08:06:32'),
(25, '4.01.03.2.04.03', 'Koordinasi, Sinkronisasi, dan Evaluasi Kebijakan Energi Dan Air\n', 9, NULL, '2024-08-02 08:06:32', '2024-08-02 08:06:32');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_07_29_082725_add_role_to_users_table', 1),
(5, '2024_08_01_033126_create_pegawais_table', 1),
(6, '2024_08_01_033139_create_programs_table', 1),
(7, '2024_08_01_033150_create_subprograms_table', 1),
(8, '2024_08_01_033202_create_kegiatans_table', 1),
(9, '2024_08_01_033216_create_subkegiatans_table', 1),
(10, '2024_08_01_033228_create_aktivitas_table', 1),
(11, '2024_08_01_033245_create_kalenders_table', 1),
(12, '2024_08_01_082303_create_aktivitas_pegawais_table', 1),
(13, '2024_08_03_144301_add_nominal_to_aktivitas_table', 2),
(14, '2024_08_04_164613_add_pegawai_id_to_kalenders_table', 3),
(15, '2024_08_04_165339_create_dinasluars_table', 4),
(16, '2024_08_04_192329_add_acc_kabag_pptk_to_aktivitas_table', 5);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pegawais`
--

CREATE TABLE `pegawais` (
  `id` bigint UNSIGNED NOT NULL,
  `nip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jabatan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `golongan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pangkat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pegawais`
--

INSERT INTO `pegawais` (`id`, `nip`, `nama`, `jabatan`, `golongan`, `pangkat`, `user_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '196812092002121004', 'Rudi Hermawan Hendi, ST', 'Plt. Kabag', 'III/D', NULL, 22, NULL, '2024-08-01 17:29:57', '2024-08-01 17:29:57'),
(2, '23123123123', 'Abd. Rajab Rahman,SH', 'Analis Kebijakan', 'III/C', NULL, 23, NULL, '2024-08-04 00:44:59', '2024-08-04 00:44:59'),
(3, '197804062007012016', 'Suriani,S.Hut', 'Bendahara Pengeluaran Pembantu', 'III/B', NULL, 24, NULL, '2024-08-04 20:23:39', '2024-08-04 20:23:39');

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `id` bigint UNSIGNED NOT NULL,
  `kode_rekening_program` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_program` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `istilah_program` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`id`, `kode_rekening_program`, `nama_program`, `istilah_program`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '04.01.01', 'Program Penunjang Urusan Pemerintah Daerah Kabupaten/Kota', NULL, NULL, '2024-08-01 17:52:07', '2024-08-01 22:13:28'),
(2, '04.01.03', 'Program Perekonomian Dan Pembangunan', NULL, NULL, '2024-08-01 17:53:09', '2024-08-01 17:53:09'),
(3, '1231231', 'edqweqwe', 'qweqwe', '2024-08-01 17:54:52', '2024-08-01 17:54:47', '2024-08-01 17:54:52');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('7IPrh0lp0yQ3Dcd0Wh9kVtYp6vEsfOHEd4sG8PIW', 23, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiT2ExNENhSzFnZlFBV3JpbkQ4Mk44bVN2VG5kVVFDRXp5Nk5rcGNkZCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjIzO30=', 1722870976),
('BfJKUWszvn6tmO0cx9cRbJA562TnojgM2V5Wbvws', 24, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTW5SbVNQYmNkbmdhbkQzeWhMYWt2Y0FRVm9hNE1BUFlObTk4UlA2aCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI0O30=', 1722870964),
('o2DZsQaI3bV8BqiT7tSt6Z3RHq4C9ZAwwnlNp54J', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQ1BWVmhYR2RIVjdoWUlCbUJxd2JpN3JuWTdIdW50cXVkVXhGM1N6QyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fX0=', 1722871190),
('P80Zln1cOHjlQUYmYwNBgr8AIC2D6qCg3DMKYA3T', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiR21IeVpjS2d1aFc4WlBQc3VwelZWc1BSZzJwSzhoQ0tINVBBUG5FSSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9rYWxlbmRlciI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1722871151);

-- --------------------------------------------------------

--
-- Table structure for table `subkegiatans`
--

CREATE TABLE `subkegiatans` (
  `id` bigint UNSIGNED NOT NULL,
  `kode_rekening_subkegiatan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_subkegiatan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `anggaran` bigint NOT NULL,
  `kegiatan_id` bigint UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subkegiatans`
--

INSERT INTO `subkegiatans` (`id`, `kode_rekening_subkegiatan`, `nama_subkegiatan`, `anggaran`, `kegiatan_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '4.01.01.2.02.03.5.1.01.03.07.0001', 'Belanja Honorarium Penanggungjawaban Pengelola Keuangan', 63720000, 11, NULL, '2024-08-02 21:42:49', '2024-08-02 22:17:08'),
(2, '4.01.01.2.02.03.5.1.01.03.07.0001', 'Belanja Honorarium Penanggungjawaban Pengelola Keuangan', 64, 11, '2024-08-02 21:45:08', '2024-08-02 21:43:25', '2024-08-02 21:45:08'),
(3, '4.01.01.2.02.03.5.1.02.01.01.0024', 'Belanja Alat/Bahan untuk Kegiatan Kantor-Alat Tulis Kantor\n', 127500, 11, NULL, '2024-08-02 22:17:29', '2024-08-02 22:17:29'),
(4, '4.01.01.2.02.03.5.1.02.01.01.0025', 'Belanja Alat/Bahan untuk Kegiatan Kantor- Kertas dan Cover\n', 174000, 11, NULL, '2024-08-02 22:17:45', '2024-08-02 22:17:45'),
(5, '4.01.01.2.02.03.5.1.02.01.01.0026', 'Belanja Alat/Bahan untuk Kegiatan Kantor- Bahan Cetak\n', 162500, 11, NULL, '2024-08-02 22:18:00', '2024-08-02 22:18:00'),
(6, '4.01.01.2.05.11.5.1.02.02.01.0066', 'Belanja Registrasi/Keanggotaan\n', 25000000, 12, NULL, '2024-08-02 22:27:42', '2024-08-02 22:27:42'),
(7, '4.01.01.2.06.04.5.1.02.01.01.0024', 'Belanja Alat/Bahan untuk Kegiatan Kantor-Alat Tulis Kantor\n', 1622400, 13, NULL, '2024-08-02 23:33:59', '2024-08-02 23:33:59'),
(8, '4.01.01.2.06.04.5.1.02.01.01.0025', 'Belanja Alat/Bahan untuk Kegiatan Kantor- Kertas dan Cover\n', 3230300, 13, NULL, '2024-08-02 23:36:07', '2024-08-02 23:36:07'),
(9, '4.01.01.2.06.04.5.1.02.01.01.0026', 'Belanja Alat/Bahan untuk Kegiatan Kantor- Bahan Cetak\n', 1465000, 13, NULL, '2024-08-02 23:36:28', '2024-08-02 23:36:28'),
(10, '4.01.01.2.06.04.5.1.02.01.01.0029', 'Belanja Alat/Bahan untuk Kegiatan Kantor-Bahan Komputer\n', 3417000, 13, NULL, '2024-08-02 23:36:45', '2024-08-02 23:36:45'),
(11, '4.01.01.2.06.08.5.1.02.01.01.0053', 'Belanja Makanan dan Minuman Jamuan Tamu\n', 12005000, 14, NULL, '2024-08-02 23:37:18', '2024-08-02 23:37:18'),
(12, '4.01.01.2.06.09.5.1.02.01.01.0024', 'Belanja Alat/Bahan untuk Kegiatan Kantor-Alat Tulis Kantor\n', 200500, 15, NULL, '2024-08-02 23:37:46', '2024-08-02 23:37:46'),
(13, '4.01.01.2.06.09.5.1.02.01.01.0025', 'Belanja Alat/Bahan untuk Kegiatan Kantor- Kertas dan Cover\n', 199500, 15, NULL, '2024-08-02 23:38:00', '2024-08-02 23:38:00'),
(14, '4.01.01.2.06.09.5.1.02.01.01.0026', 'Belanja Alat/Bahan untuk Kegiatan Kantor- Bahan Cetak\n', 250000, 15, NULL, '2024-08-02 23:38:14', '2024-08-02 23:38:14'),
(15, '4.01.01.2.06.09.5.1.02.04.01.0001', 'Belanja Perjalanan Dinas Biasa\n', 100329000, 15, NULL, '2024-08-02 23:38:31', '2024-08-02 23:38:31'),
(16, '4.01.01.2.08.03.5.1.02.02.01.0063', 'Belanja Kawat/Faksimili/Internet/TV Berlangganan\n', 420000, 16, NULL, '2024-08-02 23:38:58', '2024-08-02 23:38:58'),
(17, '4.01.01.2.08.04.5.1.02.01.01.0024', 'Belanja Alat/Bahan untuk Kegiatan Kantor-Alat Tulis Kantor\n', 127500, 17, NULL, '2024-08-02 23:39:21', '2024-08-02 23:39:21'),
(18, '4.01.01.2.08.04.5.1.02.01.01.0025', 'Belanja Alat/Bahan untuk Kegiatan Kantor- Kertas dan Cover\n', 261000, 17, NULL, '2024-08-02 23:39:34', '2024-08-02 23:39:34'),
(19, '4.01.01.2.08.04.5.1.02.01.01.0026', 'Belanja Alat/Bahan untuk Kegiatan Kantor- Bahan Cetak\n', 251500, 17, NULL, '2024-08-02 23:39:49', '2024-08-02 23:39:49'),
(20, '4.01.01.2.08.04.5.1.02.02.01.0027', 'Belanja Jasa Tenaga Operator Komputer\n', 63600000, 17, NULL, '2024-08-02 23:40:07', '2024-08-02 23:40:07'),
(21, '4.01.01.2.09.02.5.1.02.01.01.0004', 'Belanja Bahan-Bahan Bakar dan Pelumas\n', 15431000, 18, NULL, '2024-08-02 23:40:48', '2024-08-02 23:40:48'),
(22, '4.01.01.2.09.02.5.1.02.01.01.0013', 'Belanja Suku Cadang-Suku Cadang Alat Angkutan\n', 13488000, 18, NULL, '2024-08-02 23:41:05', '2024-08-02 23:41:05'),
(23, '4.01.01.2.09.02.5.1.02.02.01.0067', 'Belanja Pembayaran Pajak, Bea, dan Perizinan\n', 5200000, 18, NULL, '2024-08-02 23:41:18', '2024-08-02 23:41:18'),
(24, '4.01.01.2.09.06.5.1.02.03.02.0121', 'Belanja Pemeliharaan Alat Kantor dan Rumah Tangga-Alat Rumah Tangga-Alat Pendingin\n', 2440000, 19, NULL, '2024-08-02 23:41:37', '2024-08-02 23:41:37'),
(25, '4.01.01.2.09.06.5.1.02.03.02.0405', 'Belanja Pemeliharaan Komputer-Komputer Unit-Personal Computer', 2190000, 19, NULL, '2024-08-02 23:42:06', '2024-08-02 23:42:06'),
(26, '4.01.01.2.09.06.5.1.02.03.02.0409', 'Belanja Pemeliharaan Komputer-Peralatan Komputer-Peralatan Personal Computer\n', 2070000, 19, NULL, '2024-08-02 23:43:26', '2024-08-02 23:43:26'),
(27, '4.01.03.2.01.01.5.1.02.01.01.0024', 'Belanja Alat/Bahan untuk Kegiatan Kantor-Alat Tulis Kantor\n', 546100, 20, NULL, '2024-08-02 23:44:04', '2024-08-02 23:44:04'),
(28, '4.01.03.2.01.01.5.1.02.01.01.0025', 'Belanja Alat/Bahan untuk Kegiatan Kantor- Kertas dan Cover\n', 1297900, 20, NULL, '2024-08-02 23:44:23', '2024-08-02 23:44:23'),
(29, '4.01.03.2.01.01.5.1.02.01.01.0026', 'Belanja Alat/Bahan untuk Kegiatan Kantor- Bahan Cetak\n', 500000, 20, NULL, '2024-08-02 23:44:36', '2024-08-02 23:44:36'),
(30, '4.01.03.2.01.01.5.1.02.01.01.0052', 'Belanja Makanan dan Minuman Rapat\n', 1629000, 20, NULL, '2024-08-02 23:44:50', '2024-08-02 23:44:50'),
(31, '4.01.03.2.01.01.5.1.02.02.01.0003', 'Honorarium Narasumber atau Pembahas, Moderator, Pembawa Acara, dan Panitia\n', 900000, 20, NULL, '2024-08-02 23:45:04', '2024-08-02 23:45:04'),
(32, '4.01.03.2.01.01.5.1.02.04.01.0001', 'Belanja Perjalanan Dinas Biasa\n', 83219000, 20, NULL, '2024-08-02 23:45:27', '2024-08-02 23:45:27'),
(33, '4.01.03.2.01.01.5.1.02.04.01.0003', 'Belanja Perjalanan Dinas Dalam Kota\n', 2100000, 20, NULL, '2024-08-02 23:45:43', '2024-08-02 23:45:43'),
(34, '4.01.03.2.01.02.5.1.02.01.01.0024', 'Belanja Alat/Bahan untuk Kegiatan Kantor-Alat Tulis Kantor\n', 1037300, 21, NULL, '2024-08-02 23:46:15', '2024-08-02 23:46:15'),
(35, '4.01.03.2.01.02.5.1.02.01.01.0025', 'Belanja Alat/Bahan untuk Kegiatan Kantor- Kertas dan Cover\n', 991700, 21, NULL, '2024-08-02 23:46:30', '2024-08-02 23:46:30'),
(36, '4.01.03.2.01.02.5.1.02.01.01.0026', 'Belanja Alat/Bahan untuk Kegiatan Kantor- Bahan Cetak\n', 933000, 21, NULL, '2024-08-02 23:46:44', '2024-08-02 23:46:44'),
(37, '4.01.03.2.01.02.5.1.02.01.01.0052', 'Belanja Makanan dan Minuman Rapat\n', 13770000, 21, NULL, '2024-08-02 23:46:59', '2024-08-02 23:46:59'),
(38, '4.01.03.2.01.02.5.1.02.02.01.0003', 'Honorarium Narasumber atau Pembahas, Moderator, Pembawa Acara, dan Panitia\n', 5400000, 21, NULL, '2024-08-02 23:47:16', '2024-08-02 23:47:16'),
(39, '4.01.03.2.01.02.5.1.02.02.01.0004', 'Honorarium Tim Pelaksana Kegiatan dan Sekretariat Tim Pelaksana Kegiatan\n', 57700000, 21, NULL, '2024-08-02 23:47:31', '2024-08-02 23:47:31'),
(40, '4.01.03.2.01.02.5.1.02.04.01.0001', 'Belanja Perjalanan Dinas Biasa\n', 101756000, 21, NULL, '2024-08-02 23:47:51', '2024-08-02 23:47:51'),
(41, '4.01.03.2.01.03.5.1.02.01.01.0024', 'Belanja Alat/Bahan untuk Kegiatan Kantor-Alat Tulis Kantor\n', 372100, 22, NULL, '2024-08-02 23:48:14', '2024-08-02 23:48:14'),
(42, '4.01.03.2.01.03.5.1.02.01.01.0025', 'Belanja Alat/Bahan untuk Kegiatan Kantor- Kertas dan Cover\n', 462800, 22, NULL, '2024-08-02 23:48:31', '2024-08-02 23:48:31'),
(43, '4.01.03.2.01.03.5.1.02.01.01.0026', 'Belanja Alat/Bahan untuk Kegiatan Kantor- Bahan Cetak\n', 237500, 22, NULL, '2024-08-02 23:48:49', '2024-08-02 23:48:49'),
(44, '4.01.03.2.01.03.5.1.02.04.01.0001', 'Belanja Perjalanan Dinas Biasa\n', 37451000, 22, NULL, '2024-08-02 23:49:09', '2024-08-02 23:49:09');

-- --------------------------------------------------------

--
-- Table structure for table `subprograms`
--

CREATE TABLE `subprograms` (
  `id` bigint UNSIGNED NOT NULL,
  `kode_rekening_subprogram` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_subprogram` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `program_id` bigint UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subprograms`
--

INSERT INTO `subprograms` (`id`, `kode_rekening_subprogram`, `nama_subprogram`, `program_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '4.01.01.2.02', 'Administrasi Keuangan Perangkat Daerah', 1, NULL, NULL, '2024-08-01 22:11:28'),
(2, '4.01.01.2.05', 'Administrasi Kepegawaian Perangkat Daerah', 1, NULL, NULL, '2024-08-01 22:11:15'),
(3, '4.01.01.2.05', 'Administrasi Keuangan Perangkat Daerah qweqwe', 1, '2024-08-01 22:42:13', '2024-08-01 22:41:53', '2024-08-01 22:42:13'),
(4, '4.01.01.2.05', 'Administrasi Keuangan Perangkat Daerah qweqwe', 1, '2024-08-01 22:42:11', '2024-08-01 22:42:08', '2024-08-01 22:42:11'),
(5, '4.01.01.2.06', 'Administrasi Umum Perangkat Daerah', 1, NULL, '2024-08-01 22:42:43', '2024-08-01 22:42:43'),
(6, '4.01.01.2.08', 'Penyediaan Jasa Penunjang Urusan Pemerintahan Daerah', 1, NULL, '2024-08-01 22:42:58', '2024-08-01 22:42:58'),
(7, '4.01.01.2.09', 'Pemeliharaan Barang Milik Daerah Penunjang Urusan Pemerintahan Daerah', 1, NULL, '2024-08-01 22:43:12', '2024-08-01 22:43:12'),
(8, '4.01.03.2.01', 'Pelaksanaan Kebijakan Perekonomian', 2, NULL, '2024-08-01 22:43:39', '2024-08-01 22:43:39'),
(9, '4.01.03.2.04', 'Pemantauan Kebijakan Sumber Daya Alam', 2, NULL, '2024-08-01 22:44:06', '2024-08-01 22:44:06'),
(10, '4.01.03.2.05', 'Pemantauan Kebijakan Sumber Daya Alam asdasd', 2, '2024-08-01 23:14:48', '2024-08-01 23:13:33', '2024-08-01 23:14:48');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role`) VALUES
(1, 'Admin', 'admin@gmail.com', '2024-08-01 16:43:27', '$2y$12$mvLCsbWCovlfiNR93iOBAOYCGGsdwed9jwm7QEkfUgSuFJ7Ocqtga', 'nIWrHnkWYP8UBJ2U43PGjwiP58fRD4JNqoiPsPBR4zFrnVdlXOIK4ugFclPH', '2024-08-01 16:43:27', '2024-08-01 16:43:27', 'admin'),
(2, 'Pak Rudi', 'kabag@gmail.com', '2024-08-01 16:43:27', '$2y$12$ym2fgJcyKZL7FP9qR9fZQeM/mMxWA4uzDOv8ybi7bEnd8eapiyUPW', 'BkKHxhcMU1ZqmUgJ5xcHM7B6NWnVyK1SJWiqjklPW8VhbUnetTbOjlIiLA7W', '2024-08-01 16:43:27', '2024-08-01 16:43:27', 'kabag'),
(3, 'Burnice Kreiger', 'murphy.mekhi@example.com', '2024-08-01 16:43:27', '$2y$12$ym2fgJcyKZL7FP9qR9fZQeM/mMxWA4uzDOv8ybi7bEnd8eapiyUPW', 'iLz2NDl9BP', '2024-08-01 16:43:27', '2024-08-01 16:43:27', 'user'),
(4, 'Hudson Kovacek', 'abayer@example.com', '2024-08-01 16:43:27', '$2y$12$ym2fgJcyKZL7FP9qR9fZQeM/mMxWA4uzDOv8ybi7bEnd8eapiyUPW', 'kypIIbPLZz', '2024-08-01 16:43:27', '2024-08-01 16:43:27', 'user'),
(5, 'Tyrique Kirlin MD', 'uhegmann@example.org', '2024-08-01 16:43:27', '$2y$12$ym2fgJcyKZL7FP9qR9fZQeM/mMxWA4uzDOv8ybi7bEnd8eapiyUPW', 'DEyZKQoldz', '2024-08-01 16:43:27', '2024-08-01 16:43:27', 'user'),
(6, 'Karson Romaguera', 'charley80@example.com', '2024-08-01 16:43:27', '$2y$12$ym2fgJcyKZL7FP9qR9fZQeM/mMxWA4uzDOv8ybi7bEnd8eapiyUPW', 'z1TL7fBczE', '2024-08-01 16:43:27', '2024-08-01 16:43:27', 'user'),
(7, 'Brown Moore', 'ischowalter@example.com', '2024-08-01 16:43:27', '$2y$12$ym2fgJcyKZL7FP9qR9fZQeM/mMxWA4uzDOv8ybi7bEnd8eapiyUPW', 'vviyW5voyW', '2024-08-01 16:43:27', '2024-08-01 16:43:27', 'user'),
(8, 'Sabryna Tromp', 'cleve.white@example.org', '2024-08-01 16:43:27', '$2y$12$ym2fgJcyKZL7FP9qR9fZQeM/mMxWA4uzDOv8ybi7bEnd8eapiyUPW', 'cpPzmXSV84', '2024-08-01 16:43:27', '2024-08-01 16:43:27', 'user'),
(9, 'Prof. Penelope Wisoky PhD', 'ykeeling@example.org', '2024-08-01 16:43:27', '$2y$12$ym2fgJcyKZL7FP9qR9fZQeM/mMxWA4uzDOv8ybi7bEnd8eapiyUPW', 'oWkjv4dPWm', '2024-08-01 16:43:27', '2024-08-01 16:43:27', 'user'),
(10, 'Mrs. Norma Kub', 'whettinger@example.com', '2024-08-01 16:43:27', '$2y$12$ym2fgJcyKZL7FP9qR9fZQeM/mMxWA4uzDOv8ybi7bEnd8eapiyUPW', 'zj7P695HTc', '2024-08-01 16:43:27', '2024-08-01 16:43:27', 'user'),
(11, 'Miss Kristy Rolfson DDS', 'cormier.travis@example.net', '2024-08-01 16:43:27', '$2y$12$ym2fgJcyKZL7FP9qR9fZQeM/mMxWA4uzDOv8ybi7bEnd8eapiyUPW', 'Ke62m5fsdx', '2024-08-01 16:43:27', '2024-08-01 16:43:27', 'user'),
(12, 'Bessie Fritsch', 'rosalia08@example.org', '2024-08-01 16:43:27', '$2y$12$ym2fgJcyKZL7FP9qR9fZQeM/mMxWA4uzDOv8ybi7bEnd8eapiyUPW', 'fawQlgiCEO', '2024-08-01 16:43:27', '2024-08-01 16:43:27', 'user'),
(13, 'Mrs. Lauryn Cole DVM', 'herman.dennis@example.org', '2024-08-01 16:43:27', '$2y$12$ym2fgJcyKZL7FP9qR9fZQeM/mMxWA4uzDOv8ybi7bEnd8eapiyUPW', 'nu8jGFStyv', '2024-08-01 16:43:27', '2024-08-01 16:43:27', 'user'),
(14, 'Ona Hoeger', 'lcorwin@example.net', '2024-08-01 16:43:27', '$2y$12$ym2fgJcyKZL7FP9qR9fZQeM/mMxWA4uzDOv8ybi7bEnd8eapiyUPW', 'D1JqtXoylZ', '2024-08-01 16:43:27', '2024-08-01 16:43:27', 'user'),
(15, 'Petra Flatley', 'schuster.perry@example.org', '2024-08-01 16:43:27', '$2y$12$ym2fgJcyKZL7FP9qR9fZQeM/mMxWA4uzDOv8ybi7bEnd8eapiyUPW', 'zxZLyvBVcN', '2024-08-01 16:43:27', '2024-08-01 16:43:27', 'user'),
(16, 'Luella Zemlak', 'fredrick.bernier@example.net', '2024-08-01 16:43:27', '$2y$12$ym2fgJcyKZL7FP9qR9fZQeM/mMxWA4uzDOv8ybi7bEnd8eapiyUPW', 'Wr3OltYgXo', '2024-08-01 16:43:27', '2024-08-01 16:43:27', 'user'),
(17, 'Myah Rolfson', 'meggie54@example.com', '2024-08-01 16:43:27', '$2y$12$ym2fgJcyKZL7FP9qR9fZQeM/mMxWA4uzDOv8ybi7bEnd8eapiyUPW', 'PAPSE1Ivyi', '2024-08-01 16:43:27', '2024-08-01 16:43:27', 'user'),
(18, 'Dave Rolfson MD', 'johns.angelica@example.net', '2024-08-01 16:43:27', '$2y$12$ym2fgJcyKZL7FP9qR9fZQeM/mMxWA4uzDOv8ybi7bEnd8eapiyUPW', 'Rse8h9WUq0', '2024-08-01 16:43:27', '2024-08-01 16:43:27', 'user'),
(19, 'Ms. Magdalena Greenholt DVM', 'chane@example.net', '2024-08-01 16:43:27', '$2y$12$ym2fgJcyKZL7FP9qR9fZQeM/mMxWA4uzDOv8ybi7bEnd8eapiyUPW', 'gxL3mxmQjP', '2024-08-01 16:43:27', '2024-08-01 16:43:27', 'user'),
(20, 'Malvina Cassin', 'mohammad.mayer@example.com', '2024-08-01 16:43:27', '$2y$12$ym2fgJcyKZL7FP9qR9fZQeM/mMxWA4uzDOv8ybi7bEnd8eapiyUPW', '8DtsMrU37N', '2024-08-01 16:43:27', '2024-08-01 16:43:27', 'user'),
(21, 'Johathan O\'Connell', 'aterry@example.org', '2024-08-01 16:43:27', '$2y$12$ym2fgJcyKZL7FP9qR9fZQeM/mMxWA4uzDOv8ybi7bEnd8eapiyUPW', 'bJgf75UtVQ', '2024-08-01 16:43:27', '2024-08-01 16:43:27', 'user'),
(22, 'Rudi Hermawan Hendi, ST', 'rudi@gmail.com', NULL, '$2y$12$uNP7m8ORWj6Rw4OOSX.t7ekOMgbJo5DH2mfvMaErVzGtxRX6U.yfC', NULL, '2024-08-01 17:29:57', '2024-08-01 17:29:57', 'kabag'),
(23, 'Abd. Rajab Rahman,SH', 'rajab@gmail.com', NULL, '$2y$12$.ZHjh8Li2BtYW.RSQ5r4.OnTNQ1GmU9UL5yOdrf1/DCDzSgAxS5hG', NULL, '2024-08-04 00:44:59', '2024-08-04 00:44:59', 'pptk'),
(24, 'Suriani,S.Hut', 'bpp@gmail.com', NULL, '$2y$12$m/XisfgQX/NcEKNhDueWRuv8NykZxjLfYkt1kG.kCl4OMDLy1noaC', NULL, '2024-08-04 20:23:39', '2024-08-04 20:23:39', 'bpp');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aktivitas`
--
ALTER TABLE `aktivitas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aktivitas_subkegiatan_id_foreign` (`subkegiatan_id`);

--
-- Indexes for table `aktivitas_pegawais`
--
ALTER TABLE `aktivitas_pegawais`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aktivitas_pegawais_aktivitas_id_foreign` (`aktivitas_id`),
  ADD KEY `aktivitas_pegawais_pegawai_id_foreign` (`pegawai_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `dinasluars`
--
ALTER TABLE `dinasluars`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dinasluars_aktivitas_id_foreign` (`aktivitas_id`),
  ADD KEY `dinasluars_pegawai_id_foreign` (`pegawai_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kalenders`
--
ALTER TABLE `kalenders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kalenders_pegawai_id_foreign` (`pegawai_id`);

--
-- Indexes for table `kegiatans`
--
ALTER TABLE `kegiatans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kegiatans_subprogram_id_foreign` (`subprogram_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pegawais`
--
ALTER TABLE `pegawais`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pegawais_user_id_foreign` (`user_id`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `subkegiatans`
--
ALTER TABLE `subkegiatans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subkegiatans_kegiatan_id_foreign` (`kegiatan_id`);

--
-- Indexes for table `subprograms`
--
ALTER TABLE `subprograms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subprograms_program_id_foreign` (`program_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aktivitas`
--
ALTER TABLE `aktivitas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `aktivitas_pegawais`
--
ALTER TABLE `aktivitas_pegawais`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `dinasluars`
--
ALTER TABLE `dinasluars`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kalenders`
--
ALTER TABLE `kalenders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `kegiatans`
--
ALTER TABLE `kegiatans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `pegawais`
--
ALTER TABLE `pegawais`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `subkegiatans`
--
ALTER TABLE `subkegiatans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `subprograms`
--
ALTER TABLE `subprograms`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `aktivitas`
--
ALTER TABLE `aktivitas`
  ADD CONSTRAINT `aktivitas_subkegiatan_id_foreign` FOREIGN KEY (`subkegiatan_id`) REFERENCES `subkegiatans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `aktivitas_pegawais`
--
ALTER TABLE `aktivitas_pegawais`
  ADD CONSTRAINT `aktivitas_pegawais_aktivitas_id_foreign` FOREIGN KEY (`aktivitas_id`) REFERENCES `aktivitas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `aktivitas_pegawais_pegawai_id_foreign` FOREIGN KEY (`pegawai_id`) REFERENCES `pegawais` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `dinasluars`
--
ALTER TABLE `dinasluars`
  ADD CONSTRAINT `dinasluars_aktivitas_id_foreign` FOREIGN KEY (`aktivitas_id`) REFERENCES `aktivitas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dinasluars_pegawai_id_foreign` FOREIGN KEY (`pegawai_id`) REFERENCES `pegawais` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kalenders`
--
ALTER TABLE `kalenders`
  ADD CONSTRAINT `kalenders_pegawai_id_foreign` FOREIGN KEY (`pegawai_id`) REFERENCES `pegawais` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kegiatans`
--
ALTER TABLE `kegiatans`
  ADD CONSTRAINT `kegiatans_subprogram_id_foreign` FOREIGN KEY (`subprogram_id`) REFERENCES `subprograms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pegawais`
--
ALTER TABLE `pegawais`
  ADD CONSTRAINT `pegawais_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subkegiatans`
--
ALTER TABLE `subkegiatans`
  ADD CONSTRAINT `subkegiatans_kegiatan_id_foreign` FOREIGN KEY (`kegiatan_id`) REFERENCES `kegiatans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subprograms`
--
ALTER TABLE `subprograms`
  ADD CONSTRAINT `subprograms_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
