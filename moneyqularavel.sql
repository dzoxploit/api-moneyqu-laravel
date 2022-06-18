-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 18, 2022 at 02:32 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `moneyqularavel`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `currency`
--

CREATE TABLE `currency` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_currency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_currency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi_currency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `is_delete` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `currency`
--

INSERT INTO `currency` (`id`, `kode_currency`, `nama_currency`, `deskripsi_currency`, `is_active`, `is_delete`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'IDR', 'Rupiah (Indonesian Rupiah)', 'Mata Uang Republik Indonesia', 1, 0, '2022-04-07 06:03:28', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `goals_tujuan_keuangan`
--

CREATE TABLE `goals_tujuan_keuangan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `tujuan_keuangan_id` bigint(20) UNSIGNED NOT NULL,
  `nama_goals_tujuan_keuangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nominal` double(8,2) NOT NULL,
  `is_delete` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `goals_tujuan_keuangan`
--

INSERT INTO `goals_tujuan_keuangan` (`id`, `user_id`, `tujuan_keuangan_id`, `nama_goals_tujuan_keuangan`, `nominal`, `is_delete`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, 'dapet duit dari ahmad', 20000.00, 1, '2022-04-13 02:46:06', '2022-04-13 02:46:06', NULL),
(2, 1, 1, 'dapet duit dari ahmad', 20000.00, 1, '2022-04-13 02:48:33', '2022-04-13 02:48:33', NULL),
(3, 1, 1, 'dapet duit dari ahmad', 20000.00, 0, '2022-04-13 02:54:19', '2022-04-13 02:54:19', NULL),
(4, 1, 1, 'dapet duit dari adi', 20000.00, 1, '2022-04-13 02:54:53', '2022-04-13 03:04:19', '2022-04-13 03:04:19'),
(5, 1, 4, 'dapet duit dari adi', 20000.00, 0, '2022-06-18 00:52:45', '2022-06-18 00:52:45', NULL),
(6, 1, 3, 'alhamdulillah dpt uang freelance', 200000.00, 0, '2022-06-18 01:36:53', '2022-06-18 01:36:53', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `hutang`
--

CREATE TABLE `hutang` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `nama_hutang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_telepon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deksripsi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jumlah_hutang` double(12,2) NOT NULL,
  `jumlah_hutang_dibayar` double(12,2) NOT NULL,
  `currency_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal_hutang` timestamp NULL DEFAULT NULL,
  `status_hutang` tinyint(1) NOT NULL,
  `tanggal_hutang_dibayar` timestamp NULL DEFAULT NULL,
  `is_delete` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hutang`
--

INSERT INTO `hutang` (`id`, `user_id`, `nama_hutang`, `no_telepon`, `deksripsi`, `jumlah_hutang`, `jumlah_hutang_dibayar`, `currency_id`, `tanggal_hutang`, `status_hutang`, `tanggal_hutang_dibayar`, `is_delete`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'ngutang ke khonisa', '08529817272', 'Minjam uang ke khonisa buat beli tissue magic', 15000.00, 15000.00, 1, '2022-04-12 03:18:54', 1, NULL, 0, '2022-04-11 01:47:22', '2022-04-12 22:49:48', NULL),
(2, 1, 'ngutang ke rehan', '085262525', 'Minjam uang ke rehan buat beli gacoan', 15000.00, 0.00, 1, '2022-04-10 03:19:15', 0, NULL, 1, '2022-04-11 01:50:26', '2022-04-11 01:58:11', NULL),
(3, 1, 'ngutang ke jeni', '08529817272', 'Minjam uang ke khonisa buat beli main mares', 200000.00, 200000.00, 1, '2022-04-12 03:18:54', 1, '2022-06-18 04:45:03', 0, '2022-04-11 20:49:50', '2022-06-18 04:45:03', NULL),
(4, 1, 'ngutang ke jono', '085262525', 'Minjam uang ke rehan buat beli hadiah valentine', 18000.00, 18000.00, 1, '2022-04-12 03:18:54', 1, '2022-06-18 04:35:29', 0, '2022-05-22 20:59:23', '2022-06-18 04:35:29', NULL),
(5, 1, 'hutang ke ahmad dow', '081413309326', 'buat bayar sekolah', 30000.00, 0.00, 1, '2022-05-22 17:00:00', 0, NULL, 1, '2022-05-22 21:06:32', '2022-05-22 22:34:26', '2022-05-22 22:34:26');

-- --------------------------------------------------------

--
-- Table structure for table `jenis_simpanan`
--

CREATE TABLE `jenis_simpanan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_jenis_simpanan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi_jenis_simpanan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `is_delete` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jenis_simpanan`
--

INSERT INTO `jenis_simpanan` (`id`, `nama_jenis_simpanan`, `deskripsi_jenis_simpanan`, `is_active`, `is_delete`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Deposito', 'Deposito adalah simpanan yang pencairannya hanya dapat dilakukan pada jangka waktu tertentu dan syarat-syarat tertentu. Karakteristik deposito dari bank antara lain adalah:\r\nDeposito dapat dicairkan setelah jangka waktu berakhir.\r\nDeposito yang akan jatuh', 1, 0, '2022-04-11 03:17:27', NULL, NULL),
(2, 'Reksa Dana ', 'Reksa Dana merupakan salah satu alternatif investasi bagi masyarakat pemodal, khususnya pemodal kecil dan pemodal yang tidak memiliki banyak waktu dan keahlian untuk menghitung risiko atas investasi mereka. Reksa Dana dirancang sebagai sarana untuk menghi', 1, 0, '2022-04-11 03:17:27', NULL, NULL),
(3, 'Surat Berharga Negara ', ' Surat Berharga Negara atau yang lebih dikenal sebagai SBN adalah produk investasi yang diterbitkan dan dijamin oleh pemerintah', 1, 0, '2022-04-11 03:13:16', NULL, NULL),
(4, 'Saham', 'Saham dapat didefinisikan sebagai tanda penyertaan modal seseorang atau pihak (badan usaha) dalam suatu perusahaan atau perseroan terbatas. Dengan menyertakan modal tersebut, maka pihak tersebut memiliki klaim atas pendapatan perusahaan, klaim atas asset ', 1, 0, '0000-00-00 00:00:00', NULL, NULL),
(5, 'Tabungan Bank', 'Tabungan bank konvensional', 1, 0, '0000-00-00 00:00:00', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `kategori_laporan_keuangan`
--

CREATE TABLE `kategori_laporan_keuangan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_laporan_keuangan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi_laporan_keuangan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `is_delete` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kategori_laporan_keuangan`
--

INSERT INTO `kategori_laporan_keuangan` (`id`, `nama_laporan_keuangan`, `deskripsi_laporan_keuangan`, `is_active`, `is_delete`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Harian', 'Laporan Keuangan Harian ', 1, 0, NULL, NULL, NULL),
(2, 'Mingguan', 'Laporan Keuangan Mingguan', 1, 0, NULL, NULL, NULL),
(3, 'Bulanan', 'Laporan Keuangan Bulanan', 0, 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `kategori_pemasukan`
--

CREATE TABLE `kategori_pemasukan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_pemasukan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi_pemasukan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `is_delete` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kategori_pemasukan`
--

INSERT INTO `kategori_pemasukan` (`id`, `nama_pemasukan`, `deskripsi_pemasukan`, `is_active`, `is_delete`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Pemasukan bulanan kuliah', 'uang dari orang tua', 1, 0, '2022-03-30 00:09:22', '2022-03-30 00:50:22', NULL),
(2, 'Pemasukan Gaji', 'Uang hasil pemasukan Gaji', 1, 0, NULL, NULL, NULL),
(3, 'Pemasukan investasi non simpanan', 'pemasukan investasi diluar simpanan seperti hasil dagang dll', 1, 0, NULL, NULL, NULL),
(4, 'Pemasukan Lainnya', '', 1, 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `kategori_pengeluaran`
--

CREATE TABLE `kategori_pengeluaran` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_pengeluaran` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi_pengeluaran` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `is_delete` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kategori_pengeluaran`
--

INSERT INTO `kategori_pengeluaran` (`id`, `nama_pengeluaran`, `deskripsi_pengeluaran`, `is_active`, `is_delete`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Pembayaran Hutang', 'Pembayaran Hutang', 1, 0, '2022-04-11 03:44:27', NULL, NULL),
(2, 'Pengeluaran Kebutuhan Rumah dll', 'Pengeluaran kebutuhan rumah seperti sabun shampo beli perabotan dll', 1, 0, '2022-04-11 03:44:27', NULL, NULL),
(3, 'Pengeluaran Pendidikan ', 'Pengeluaran untuk kebutuhan pendidikan seperti bayar printan tugas, bayar perlengkapan tugas kelompok dll', 0, 0, '2022-04-11 03:13:16', NULL, NULL),
(4, 'Pengeluaran Lainnya', '', 1, 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `kategori_tagihan`
--

CREATE TABLE `kategori_tagihan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_tagihan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi_tagihan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `is_delete` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kategori_tagihan`
--

INSERT INTO `kategori_tagihan` (`id`, `nama_tagihan`, `deskripsi_tagihan`, `is_active`, `is_delete`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Listrik Prabayar', 'Pembayaran Tagihan Listrik Prabayar', 1, 0, '2022-04-11 05:21:19', NULL, NULL),
(2, 'Telepon Prabayar', 'Pembayaran telepon prabayar', 1, 0, NULL, NULL, NULL),
(3, 'Tagihan Uang Kost', 'Bayaran uang kostan', 1, 0, NULL, NULL, NULL),
(4, 'Tagihan Pajak PBB', 'Pembayaran Pajak PBB Bangunan', 1, 0, NULL, NULL, NULL),
(5, 'Tagihan Kasbon', 'Bayar kasbon anda heiiiii', 0, 0, NULL, NULL, NULL),
(6, 'Tagihan Lainnya', '', 1, 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `kategori_tujuan_keuangan`
--

CREATE TABLE `kategori_tujuan_keuangan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_tujuan_keuangan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi_tujuan_keuangan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `is_delete` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kategori_tujuan_keuangan`
--

INSERT INTO `kategori_tujuan_keuangan` (`id`, `nama_tujuan_keuangan`, `deskripsi_tujuan_keuangan`, `is_active`, `is_delete`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Bayar Uang Kuliah', '', 1, 0, NULL, NULL, NULL),
(2, 'Bayar Hutang', '', 1, 0, NULL, NULL, NULL),
(3, 'Beli Barang Impian ', '', 1, 0, NULL, NULL, NULL),
(4, 'Buat Mengumpulkan DP barang / rumah', '', 1, 0, NULL, NULL, NULL),
(5, 'Buat Nyicil Uang Nikah / Lamaran ', '', 1, 0, NULL, NULL, NULL),
(6, 'Tujuan Keuangan Lainnya', '', 1, 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `laporan_keuangan`
--

CREATE TABLE `laporan_keuangan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `kategori_laporan_keuangan_id` bigint(20) UNSIGNED NOT NULL,
  `nama_laporan_keuangan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_pemasukan` double(8,2) NOT NULL,
  `total_pengeluaran` double(8,2) NOT NULL,
  `total_tabungan` double(8,2) NOT NULL,
  `total_hutang` double(8,2) NOT NULL,
  `total_piutang` double(8,2) NOT NULL,
  `total_balance` double(8,2) NOT NULL,
  `currency_id` bigint(20) UNSIGNED NOT NULL,
  `is_deleted` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2016_06_01_000001_create_oauth_auth_codes_table', 1),
(4, '2016_06_01_000002_create_oauth_access_tokens_table', 1),
(5, '2016_06_01_000003_create_oauth_refresh_tokens_table', 1),
(6, '2016_06_01_000004_create_oauth_clients_table', 1),
(7, '2016_06_01_000005_create_oauth_personal_access_clients_table', 1),
(8, '2019_08_19_000000_create_failed_jobs_table', 1),
(9, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(19, '2014_10_12_000000_create_users_table', 2),
(20, '2022_03_29_074749_create_currency_table', 3),
(22, '2022_03_29_093401_create_kategori_tujuan_keuangan_table', 5),
(24, '2022_03_29_082657_create_kategori_tagihan_table', 7),
(25, '2022_03_29_073548_create_kategori_laporan_keuangan_table', 8),
(26, '2022_03_29_082554_create_kategori_pengeluaran_table', 9),
(29, '2022_03_29_073322_create_kategori_pemasukan_table', 12),
(30, '2022_03_29_073059_create_pemasukan_table', 13),
(31, '2022_03_29_073504_create_laporan_keuangan_table', 13),
(35, '2022_03_29_082616_create_tagihan_table', 16),
(37, '2022_03_30_061416_create_admins_table', 17),
(38, '2022_03_29_083342_create_settings_table', 18),
(40, '2022_03_29_083131_create_piutang_table', 20),
(41, '2022_03_29_083104_create_hutang_table', 21),
(42, '2022_04_03_155527_add_tanggal_hutang_to_hutang', 22),
(43, '2022_04_03_160042_add_tanggal_piutang_to_piutang', 23),
(44, '2022_04_04_044329_add_status_to_tagihan', 24),
(45, '2022_04_04_044902_add_status_tagihan_lunas__to_tagihan', 25),
(46, '2022_04_05_045402_create_goals_tujuan_keuangan_table', 26),
(47, '2022_04_05_051748_add_currency_id_to_tujuan_keuangan', 27),
(48, '2022_04_05_054317_add_nominal_goals_to_tujuan_keuangan', 28),
(49, '2022_03_30_035424_create_jenis_simpanan_table', 29),
(50, '2022_03_29_082431_create_tujuan_simpanan_table', 30),
(51, '2022_03_29_082315_create_simpanan_table', 31),
(53, '2022_04_11_090743_add_jumlah_piutang_dibayar_to_piutang', 33),
(54, '2022_04_12_031508_add_jumlah_hutang_dibayar_to_hutang', 34),
(55, '2022_03_29_082513_create_pengeluaran_table', 35),
(56, '2022_05_23_141432_add_kategori_tagihan_id_to_tagihan', 36);

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_access_tokens`
--

INSERT INTO `oauth_access_tokens` (`id`, `user_id`, `client_id`, `name`, `scopes`, `revoked`, `created_at`, `updated_at`, `expires_at`) VALUES
('021d2953ef3e2aee9dcf88ca6d2a564ff26973813fa55fe13f1ec414588735675f16fa6db44cb20f', 1, 1, 'appToken', '[]', 0, '2022-05-11 22:06:48', '2022-05-11 22:06:48', '2023-05-12 05:06:48'),
('0b9441982cb02f164e62ca7bcbaa4a3f68c45d1343136fa037c0b847c35295627a138cbcbef96a92', 1, 1, 'appToken', '[]', 0, '2022-05-18 19:13:55', '2022-05-18 19:13:55', '2023-05-19 02:13:55'),
('0d920b99c93180ddac1ba15ee5eb56a8f07d53a5609bccea43cb95b64d7106b223740cc93bde99e5', 4, 1, 'appToken', '[]', 1, '2022-05-15 23:17:24', '2022-05-15 23:17:24', '2023-05-16 06:17:24'),
('0e93671e05f2c374a08e90a2b37539af9de6afd272baa6111abca72ccfebd0ded785355a41598c33', 1, 1, 'appToken', '[]', 1, '2022-03-28 01:56:40', '2022-03-28 01:56:40', '2023-03-28 08:56:40'),
('0fe35e28ec9e3a5df3f0652ee44dbf20ce252d9bb006593ebda9b41fc4bcbaaf901e6df38f6d73db', 1, 1, 'appToken', '[]', 0, '2022-05-18 02:28:53', '2022-05-18 02:28:53', '2023-05-18 09:28:53'),
('12e324ef2fa9e41d563028ecba7b06aa01a3f55debf29c7439e97fed0f1af37fe5adbf2585a7329a', 1, 1, 'appToken', '[]', 0, '2022-05-17 21:03:53', '2022-05-17 21:03:53', '2023-05-18 04:03:53'),
('132d61c0be2cdcc62d1fa3d7de847819071c6620b9ce915f4f3b5b3e80867884f8da38c4eab6eb1b', 1, 1, 'appToken', '[]', 0, '2022-04-10 21:27:40', '2022-04-10 21:27:40', '2023-04-11 04:27:40'),
('22eb66be7db7d7cb5af01e62e55c95b32e99daaead944b250a374a8437d23c2d788b4ae53d6aab71', 2, 1, 'appToken', '[]', 0, '2022-03-29 22:33:27', '2022-03-29 22:33:27', '2023-03-30 05:33:27'),
('24b50b3a4c7c3aa514453d665b01e96aa5b98ddd12f02ce6450fc88be084d4453fe5b09ec7a9ed81', 1, 1, 'appToken', '[]', 1, '2022-05-20 01:28:49', '2022-05-20 01:28:49', '2023-05-20 08:28:49'),
('257ec778408981148c765e9103c05693c20a4438375e326460ca689cb7557aafa2e6daa5a55668b5', 1, 1, 'appToken', '[]', 1, '2022-03-28 00:17:13', '2022-03-28 00:17:13', '2023-03-28 07:17:13'),
('2600117235db5fe0db7985db32470c9f49894e3d187573953253ec8b05fa98a49e3a00f965e1c444', 1, 1, 'appToken', '[]', 0, '2022-05-22 21:28:57', '2022-05-22 21:28:57', '2023-05-23 04:28:57'),
('270feda7a94bf130786383dabf5a5a46b355df41b44013a5b0c66158786149d75fb6ae88585c3ad3', 1, 1, 'appToken', '[]', 1, '2022-05-16 22:23:12', '2022-05-16 22:23:12', '2023-05-17 05:23:12'),
('2f69a1c7804118cf620954e258b103179acce2126418bceb6637036ecad983be8e205505708a421c', 1, 1, 'appToken', '[]', 0, '2022-04-10 21:26:45', '2022-04-10 21:26:45', '2023-04-11 04:26:45'),
('338f3985f703399ecc00a5d78672d90ffef1b5942ef924d3891b1d8866e3347c11a2770d1730639b', 5, 1, 'appToken', '[]', 0, '2022-05-18 18:35:15', '2022-05-18 18:35:15', '2023-05-19 01:35:15'),
('3685339206a87422598cfa6b648c0f636d3587a67e9e7a7204e54b4fe19047aa2d71bdeb852380da', 1, 1, 'appToken', '[]', 0, '2022-04-06 23:11:15', '2022-04-06 23:11:15', '2023-04-07 06:11:15'),
('387b489ea55372d81d9fd49c5bb4a6a7a7837460cc67935e05703c505a36dcb2a05a68b608587165', 1, 1, 'appToken', '[]', 0, '2022-05-11 21:27:23', '2022-05-11 21:27:23', '2023-05-12 04:27:23'),
('3be081f1cf84c3d55d5c492cb74b7b6c67f2941d8cef0c29651366a0ac310482869dfdf2c06bc1aa', 1, 1, 'appToken', '[]', 0, '2022-05-11 21:50:34', '2022-05-11 21:50:34', '2023-05-12 04:50:34'),
('3bff5b07af69ba4ebfaa920a81627140bd05b2adbe9a41c169693a90069605c334c6eb3218c29b4d', 1, 1, 'appToken', '[]', 1, '2022-05-16 23:00:59', '2022-05-16 23:00:59', '2023-05-17 06:00:59'),
('4fb74599bc1d5edf89b8d85b917cfd40f85d17dbf521d91ca08ef3c638230c47aad9fbe4c8a776b0', 1, 1, 'appToken', '[]', 0, '2022-03-28 00:12:59', '2022-03-28 00:12:59', '2023-03-28 07:12:59'),
('5236071c0569fc5282f2b4b47c92b73100b3041eb8d66a2b969a1aac4bbbdc20d66a6b97ce7712b5', 1, 1, 'appToken', '[]', 0, '2022-05-17 00:43:56', '2022-05-17 00:43:56', '2023-05-17 07:43:56'),
('574a0ecfc87339ac9be929a00cfb55bd55d295a67e041f5fe9e6fe26c26ca6e6283cbb70cb96192e', 1, 1, 'appToken', '[]', 0, '2022-05-16 21:55:38', '2022-05-16 21:55:38', '2023-05-17 04:55:38'),
('592801ff93ff205693066f2458fa94a79078c4a18b43e56ba1c7ec7f8817ca6252954b5655dd69b7', 1, 1, 'appToken', '[]', 0, '2022-04-12 19:57:21', '2022-04-12 19:57:21', '2023-04-13 02:57:21'),
('634b0a01bc37bf1a77408f3e844ccba85bda57265f34de99311f0b4b5dd06229552d6c776d1a1618', 1, 1, 'appToken', '[]', 1, '2022-03-29 22:31:40', '2022-03-29 22:31:40', '2023-03-30 05:31:40'),
('683baca752ca1cf09e6154f01d3778c2e081b2e3c09b59089110cb3b37ed4f5a424dd8046475765f', 1, 1, 'appToken', '[]', 0, '2022-05-11 21:52:39', '2022-05-11 21:52:39', '2023-05-12 04:52:39'),
('6f519e7d34f13182ecc1e74e92eb53528f23fd07700bb41ad3969b0061a8dccec404e285df154b23', 1, 1, 'appToken', '[]', 0, '2022-05-11 21:33:58', '2022-05-11 21:33:58', '2023-05-12 04:33:58'),
('71a74a5d875e52de30e7f201b5d655aa61e1067ebe9f78a418850bc6731ab75e128c69847eecde8e', 1, 1, 'appToken', '[]', 0, '2022-03-28 00:13:47', '2022-03-28 00:13:47', '2023-03-28 07:13:47'),
('72569e98ebb2786d181093126be14f13917d9e401280536a67855fbfa17dd2972a13401349479d90', 1, 1, 'appToken', '[]', 0, '2022-05-18 23:52:54', '2022-05-18 23:52:54', '2023-05-19 06:52:54'),
('7361c4c43acf8662958e36089dced7063719be47cc80a03e6030df059130f06e25ebaca7023cbd88', 1, 1, 'appToken', '[]', 0, '2022-05-11 00:04:30', '2022-05-11 00:04:30', '2023-05-11 07:04:30'),
('76130e261eb42ce309f4c30463dfc00dda19db29f8801f2a8fd72a40f9137d22a7b6439cdb3d0266', 1, 1, 'appToken', '[]', 0, '2022-05-11 00:10:42', '2022-05-11 00:10:42', '2023-05-11 07:10:42'),
('768f9480a3f4497f0afbd8f3c466ef8e424397651899582cf561a05978fe18580ec1c89bb253cd36', 1, 1, 'appToken', '[]', 1, '2022-05-16 22:52:43', '2022-05-16 22:52:43', '2023-05-17 05:52:43'),
('77fcbf18707b5b0665cca75d28e9c397792e5b2b99a660a1b529c841b7ced3fbbb8d1dc0b04095f7', 1, 1, 'appToken', '[]', 0, '2022-03-28 01:58:17', '2022-03-28 01:58:17', '2023-03-28 08:58:17'),
('7c1a8195833d1912efc461a05e878c3c89653c14c7aedf730b99820af0a10464e493ba9c5528d86c', 1, 1, 'appToken', '[]', 0, '2022-05-11 00:15:47', '2022-05-11 00:15:47', '2023-05-11 07:15:47'),
('7fbaa484d9aacaea5b6032d540ecf51a4d9c7d17c934b6b86936d9fa1694df133b0118a8e259726d', 1, 1, 'appToken', '[]', 0, '2022-05-17 21:12:20', '2022-05-17 21:12:20', '2023-05-18 04:12:20'),
('816841ff3821675e48386425095b5be73567399f2ae2dc402d8f056fc0055996a672ef2e3d00c7d1', 1, 1, 'appToken', '[]', 0, '2022-05-18 04:54:02', '2022-05-18 04:54:02', '2023-05-18 11:54:02'),
('86a9be642963c467632bb25077ea0005fbce892b0980be9e0006d4595742eb26be055b153b09f8c8', 1, 1, 'appToken', '[]', 0, '2022-05-17 22:32:37', '2022-05-17 22:32:37', '2023-05-18 05:32:37'),
('8bff3101a088c801ea04dbe16400f5105d836167362461887d25424fbba8696e9776df2b82cfd125', 1, 1, 'appToken', '[]', 0, '2022-05-11 07:16:41', '2022-05-11 07:16:41', '2023-05-11 14:16:41'),
('8c15eee414841401d27f412c3e3b1d9c18becf29ea078226f211682540ee5d1d0dbca5648ad9014e', 1, 1, 'appToken', '[]', 0, '2022-05-15 22:54:42', '2022-05-15 22:54:42', '2023-05-16 05:54:42'),
('8f8b5e929b5d71930b2ef30e9dd8657f9f3f2348bd2dcd88f925ee3fdf458595f8ed12ae709c2b4f', 1, 1, 'appToken', '[]', 1, '2022-05-20 01:28:17', '2022-05-20 01:28:17', '2023-05-20 08:28:17'),
('908dea09bbdf7bf6210f618269246b5b4b645f4b31bc7b47bfd43d36ba5b0c2ebc9e5bae7dca3d0b', 1, 1, 'appToken', '[]', 1, '2022-05-18 18:28:55', '2022-05-18 18:28:55', '2023-05-19 01:28:55'),
('924de0e4c7593d945d539bdae461a2c5933378fa0b0c8d3fcdea1c693452080067624ff04a24e806', 1, 1, 'appToken', '[]', 0, '2022-04-06 22:45:59', '2022-04-06 22:45:59', '2023-04-07 05:45:59'),
('9345568acad17e42feb971a4211532b502d574fecf76c446c99c3c50e4e611928c5b35bab1abd9c7', 1, 1, 'appToken', '[]', 1, '2022-05-16 22:17:14', '2022-05-16 22:17:14', '2023-05-17 05:17:14'),
('94abd3825ad400f130a2a31223d745b00b661e564e647363dc6ddbe4a9d6c4dacc1e758257032064', 1, 1, 'appToken', '[]', 0, '2022-05-11 21:58:49', '2022-05-11 21:58:49', '2023-05-12 04:58:49'),
('95ae9c990c7b0968f4ab6644a49bdbe310f88041af7a4944ba343776a0476159f7850d370904fdd6', 1, 1, 'appToken', '[]', 0, '2022-05-17 21:24:55', '2022-05-17 21:24:55', '2023-05-18 04:24:55'),
('9f3c55163215c1072ef9cf6d4decf4704ea3ea1aebdd2b5d2a3f7a1e498383cde8207a6194157a82', 1, 1, 'appToken', '[]', 0, '2022-06-03 03:03:38', '2022-06-03 03:03:38', '2023-06-03 10:03:38'),
('a9a2d9d4972ba9c6bf67d831981fab14740021025a06b66d584070dd432889f74dabf1607f35652e', 1, 1, 'appToken', '[]', 1, '2022-05-16 22:22:15', '2022-05-16 22:22:15', '2023-05-17 05:22:15'),
('b4cafad9a7c8c1d138faaf9062e540063befe2f171282067e51f27f96757f2775fa29039d08f83ba', 1, 1, 'appToken', '[]', 0, '2022-05-16 21:33:28', '2022-05-16 21:33:28', '2023-05-17 04:33:28'),
('b8d6e7ca38ab1d95e65021735895ca7cee4d1b05bac554aaf667aa99346c83538645960895a27005', 1, 1, 'appToken', '[]', 0, '2022-05-11 21:45:14', '2022-05-11 21:45:14', '2023-05-12 04:45:14'),
('bbc5892893b7c30aa8e85672e44de3c8bd6181cc6f566eef404b85f60f17fb4833cbb9a1cf7f8674', 1, 1, 'appToken', '[]', 0, '2022-05-16 21:42:11', '2022-05-16 21:42:11', '2023-05-17 04:42:11'),
('bdf1ae6b4352ef1418fb2e30021bfec5d696d5036aec0d60ffce9fade1cd78f7f04c76162073d8dd', 1, 1, 'appToken', '[]', 0, '2022-06-03 02:53:13', '2022-06-03 02:53:13', '2023-06-03 09:53:13'),
('bf69d8c7d1997a1e865786ac72707f86810836166887a2803bbab9974249f64a5d834f149238868e', 1, 1, 'appToken', '[]', 1, '2022-05-16 22:19:31', '2022-05-16 22:19:31', '2023-05-17 05:19:31'),
('c36b588248d2f10f91ea555e1a0e02589251a39e7695c4da12b39b314b6c5f2d1a8d4626407984ce', 1, 1, 'appToken', '[]', 0, '2022-05-11 22:12:21', '2022-05-11 22:12:21', '2023-05-12 05:12:21'),
('ca5487bfac1bc762cc0e42143071135d41cf9561365bcafff17180962909349b3a50f7cb33db3a71', 2, 1, 'appToken', '[]', 0, '2022-03-29 23:53:41', '2022-03-29 23:53:41', '2023-03-30 06:53:41'),
('ccec10aab1c13e7249071eeafeddb7e76f8c9ce69b1e24f10c3a545ce014184665b3c44c9a390bc3', 1, 1, 'appToken', '[]', 0, '2022-05-16 19:54:31', '2022-05-16 19:54:31', '2023-05-17 02:54:31'),
('d10ae2c2b94146d9b7db16c8f9392537ccfc6e35d24f5d212f15a4fe9618fd5b5f1871a9516391b0', 1, 1, 'appToken', '[]', 1, '2022-05-17 23:26:59', '2022-05-17 23:26:59', '2023-05-18 06:26:59'),
('dcb2c31bed82c36ed69c58f0053c6cbfa846070f1c11cf49f7f0ae53f924ccf45040303151f08194', 1, 1, 'appToken', '[]', 1, '2022-05-20 06:17:48', '2022-05-20 06:17:48', '2023-05-20 13:17:48'),
('dcecb424b0e07230af9f73f5127ecc9f48f7ad50efb6739d8170cf1bf8e96542bfdeec993ce84963', 1, 1, 'appToken', '[]', 1, '2022-05-19 03:22:23', '2022-05-19 03:22:23', '2023-05-19 10:22:23'),
('e0a5f3988796e340c781ae52336ffd4d4104a068f0f14fee96f89f94356b9ae83aa797a9dc3fc24f', 1, 1, 'appToken', '[]', 0, '2022-05-16 22:11:25', '2022-05-16 22:11:25', '2023-05-17 05:11:25'),
('e4eb2959c4ae0b5773576345e29e191a2d3d7caa7af4f5dd10f33171897056134c2595de1df9c765', 1, 1, 'appToken', '[]', 0, '2022-05-16 21:19:52', '2022-05-16 21:19:52', '2023-05-17 04:19:52'),
('e92bf0ca5bbb90725677c5fedf674b4711b9a7657e4056c3d7a43eae8735ba11d39c54372d9e756e', 1, 1, 'appToken', '[]', 0, '2022-05-18 02:25:39', '2022-05-18 02:25:39', '2023-05-18 09:25:39'),
('e9bda6bc80889272cbd6dbb358c97f32a0b03e330dffe260a7a70933ac326d7a1340c1450ce0d1bb', 1, 1, 'appToken', '[]', 0, '2022-06-10 02:40:08', '2022-06-10 02:40:08', '2023-06-10 09:40:08'),
('ea8efff3b08ab4f6aeb514208efc88d270ce88ff5d7187578882fa8ed970225f8fe904c8e919a6c0', 1, 1, 'appToken', '[]', 0, '2022-05-11 21:27:41', '2022-05-11 21:27:41', '2023-05-12 04:27:41'),
('ec6f7ae4c74e3fa9a8897eeefdd9e34a1e02134d96a86087ee20646e3feab71566fd06c38a207175', 2, 1, 'appToken', '[]', 1, '2022-03-29 22:35:04', '2022-03-29 22:35:04', '2023-03-30 05:35:04'),
('ef239e3d7146733e5ca40cedbcda5b18f762da10cbff33e630ca697a3474320ec50f98e36517f354', 1, 1, 'appToken', '[]', 0, '2022-04-06 23:10:27', '2022-04-06 23:10:27', '2023-04-07 06:10:27'),
('f1924ff9accae01621b4af842db0e9530354f8a0142c63cf8c118862a033b9c3a1830762a70c5826', 1, 1, 'appToken', '[]', 0, '2022-05-30 19:45:16', '2022-05-30 19:45:16', '2023-05-31 02:45:16'),
('f496d47642e61d2d64a31875a481909319b8843f7d3af4db2084d86799cc32a230301d96982f44d8', 3, 1, 'appToken', '[]', 0, '2022-05-15 23:15:07', '2022-05-15 23:15:07', '2023-05-16 06:15:07'),
('f90a2f7f63425ff5b19c760d303502004c92d33ad29e61ec38d4989e6af3d4fd32e6df21e150db78', 1, 1, 'appToken', '[]', 0, '2022-03-29 22:31:16', '2022-03-29 22:31:16', '2023-03-30 05:31:16'),
('fb329149f4a3d146a5e0884d7ec47c854277aa1808448b26eca53ddd8c366898fe530631752b704b', 1, 1, 'appToken', '[]', 0, '2022-05-20 02:05:49', '2022-05-20 02:05:49', '2023-05-20 09:05:49'),
('fb46b1bccdbefbdc775b3d26cb2fba01ea45a25e43eb43c126803e6ca89f54825c55bf8806be2d8f', 2, 1, 'appToken', '[]', 0, '2022-05-15 22:52:40', '2022-05-15 22:52:40', '2023-05-16 05:52:40'),
('ff77cbd9d96939a2fee3c8c00630713dd8f4b2a8a7d83fb4a4475769960d50ad456f775d8ed8c622', 1, 1, 'appToken', '[]', 0, '2022-05-11 22:05:34', '2022-05-11 22:05:34', '2023-05-12 05:05:34');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_clients`
--

INSERT INTO `oauth_clients` (`id`, `user_id`, `name`, `secret`, `provider`, `redirect`, `personal_access_client`, `password_client`, `revoked`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Laravel Personal Access Client', '6z8r7PxnZXlD2qEmppwbQdSkGPzM4uYwjTMjkFzO', NULL, 'http://localhost', 1, 0, 0, '2022-03-27 23:57:10', '2022-03-27 23:57:10'),
(2, NULL, 'Laravel Password Grant Client', 'OVE2hubTeEHo4VI5EQULM7qj0IapWY9RhAoKsbw7', 'users', 'http://localhost', 0, 1, 0, '2022-03-27 23:57:10', '2022-03-27 23:57:10');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

CREATE TABLE `oauth_personal_access_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_personal_access_clients`
--

INSERT INTO `oauth_personal_access_clients` (`id`, `client_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2022-03-27 23:57:10', '2022-03-27 23:57:10');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pemasukan`
--

CREATE TABLE `pemasukan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `kategori_pemasukan_id` bigint(20) UNSIGNED NOT NULL,
  `nama_pemasukan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_id` bigint(20) UNSIGNED NOT NULL,
  `jumlah_pemasukan` double(11,2) NOT NULL,
  `tanggal_pemasukan` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_transaksi_berulang` tinyint(1) NOT NULL,
  `is_delete` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pemasukan`
--

INSERT INTO `pemasukan` (`id`, `user_id`, `kategori_pemasukan_id`, `nama_pemasukan`, `currency_id`, `jumlah_pemasukan`, `tanggal_pemasukan`, `keterangan`, `status_transaksi_berulang`, `is_delete`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, 'Uang Gajian MNC', 1, 2500000.00, '2022-04-11 06:00:32', 'ok', 0, 1, '2022-04-10 21:35:23', '2022-04-10 23:00:32', '2022-04-10 23:00:32'),
(2, 1, 1, 'Uang Bulanan Dari mamak tersayang', 1, 3000000.00, '2022-05-19 10:43:30', NULL, 0, 0, '2022-04-10 21:37:06', '2022-05-19 03:43:30', NULL),
(3, 1, 1, 'Uang gaji freelance buat ayang bebeb', 1, 40000.00, '2022-05-20 07:43:01', NULL, 0, 0, '2022-05-18 18:35:03', '2022-05-20 00:43:01', NULL),
(4, 1, 1, 'pemasukan gaji', 1, 200000.00, '2022-05-18 17:00:00', 'ok', 0, 0, '2022-05-18 19:06:09', '2022-05-18 19:06:09', NULL),
(5, 1, 1, 'uang joki zahra gokil', 1, 20000.00, '2022-05-20 07:45:22', NULL, 0, 0, '2022-05-18 19:07:43', '2022-05-20 00:45:22', NULL),
(6, 1, 1, 'uang hasil ngejoki tugas', 1, 30000.00, '2022-05-20 08:31:59', NULL, 0, 0, '2022-05-18 19:10:30', '2022-05-20 01:31:59', NULL),
(7, 1, 1, 'mantap', 1, 10000.00, '2022-05-20 07:27:58', 'ok', 0, 1, '2022-05-18 19:12:02', '2022-05-20 00:27:58', '2022-05-20 00:27:58'),
(8, 1, 1, 'uang dari bebeb naranta', 1, 10000.00, '2022-05-20 12:53:41', NULL, 0, 1, '2022-05-20 00:32:04', '2022-05-20 05:53:41', '2022-05-20 05:53:41'),
(9, 1, 4, 'uang semalam', 1, 20000.00, '2022-06-03 09:19:54', 'ok', 0, 0, '2022-05-20 00:33:27', '2022-06-03 02:19:54', NULL),
(10, 1, 1, 'uang tadi pagi', 1, 30000.00, '2022-05-20 08:44:35', 'mantap', 0, 0, '2022-05-20 00:35:33', '2022-05-20 01:44:35', NULL),
(11, 1, 1, 'ok', 1, 20000.00, '2022-05-19 17:00:00', 'wadaw', 0, 0, '2022-05-20 00:37:28', '2022-05-20 00:37:28', NULL),
(12, 1, 1, 'pemasukan gokil', 1, 200000.00, '2022-05-19 17:00:00', 'ok mantap sejahtera', 0, 0, '2022-05-20 06:03:39', '2022-05-20 06:03:39', NULL),
(13, 1, 4, 'Uang gajian', 1, 20000.00, '2022-06-03 09:20:14', 'mantap', 0, 0, '2022-06-02 18:25:55', '2022-06-03 02:20:14', NULL),
(14, 1, 2, 'uang gajian pertengahan juni', 1, 200000.00, '2022-06-17 17:00:00', 'alhamdulillah uang freelance', 0, 0, '2022-06-18 00:08:25', '2022-06-18 00:08:25', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pengeluaran`
--

CREATE TABLE `pengeluaran` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `kategori_pengeluaran_id` bigint(20) UNSIGNED NOT NULL,
  `hutang_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nama_pengeluaran` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_id` bigint(20) UNSIGNED NOT NULL,
  `jumlah_pengeluaran` double(8,2) NOT NULL,
  `tanggal_pengeluaran` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_delete` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengeluaran`
--

INSERT INTO `pengeluaran` (`id`, `user_id`, `kategori_pengeluaran_id`, `hutang_id`, `nama_pengeluaran`, `currency_id`, `jumlah_pengeluaran`, `tanggal_pengeluaran`, `keterangan`, `is_delete`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, 1, 'bayar hutang khonisa', 1, 15000.00, '2022-05-23 06:34:01', 'ok', 0, '2022-04-12 22:49:48', '2022-04-12 22:49:48', NULL),
(2, 1, 1, 4, 'bayar Hutang jono', 1, 18000.00, '2022-06-18 03:44:27', 'ok', 0, '2022-06-18 04:35:29', '2022-06-18 04:35:29', NULL),
(3, 1, 1, 3, 'ngutang ke jeni', 1, 200000.00, '2022-06-17 17:00:00', 'ok', 0, '2022-06-18 04:45:03', '2022-06-18 04:45:03', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `piutang`
--

CREATE TABLE `piutang` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `nama_piutang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_telepon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deksripsi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jumlah_hutang` double(12,2) NOT NULL,
  `jumlah_piutang_dibayar` double(12,2) NOT NULL,
  `currency_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal_piutang` timestamp NULL DEFAULT NULL,
  `status_piutang` tinyint(1) NOT NULL,
  `tanggal_piutang_dibayar` timestamp NULL DEFAULT NULL,
  `is_delete` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `piutang`
--

INSERT INTO `piutang` (`id`, `user_id`, `nama_piutang`, `no_telepon`, `deksripsi`, `jumlah_hutang`, `jumlah_piutang_dibayar`, `currency_id`, `tanggal_piutang`, `status_piutang`, `tanggal_piutang_dibayar`, `is_delete`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Ngasih pinjaman uang makan ke cecep', '085262525', 'lapar mau makan', 40000.00, 0.00, 1, '2022-04-12 03:18:54', 0, NULL, 1, '2022-04-11 21:12:33', '2022-04-12 20:05:56', '2022-04-12 20:05:56'),
(2, 1, 'Ngasih hutang ke ahmad', '081727272', 'ok mantap sejahtera', 30000.00, 30000.00, 1, '2022-05-24 17:00:00', 1, '2022-06-18 05:17:05', 0, '2022-05-24 19:14:08', '2022-06-18 05:17:05', NULL),
(3, 1, 'ngasih utang ke rojo', '0817272727', 'buat bayar kos', 30000.00, 30000.00, 1, '2022-06-17 17:00:00', 1, '2022-06-18 05:19:32', 0, '2022-06-18 05:18:41', '2022-06-18 05:19:32', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `currency_id` bigint(20) UNSIGNED NOT NULL,
  `bahasa` tinyint(1) NOT NULL,
  `settings_component_1` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `user_id`, `currency_id`, `bahasa`, `settings_component_1`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, '2022-04-06 23:10:27', '2022-04-06 23:10:27'),
(2, 2, 1, 1, 1, '2022-05-15 22:52:39', '2022-05-15 22:52:39'),
(3, 3, 1, 1, 1, '2022-05-15 23:15:07', '2022-05-15 23:15:07'),
(4, 4, 1, 1, 1, '2022-05-15 23:17:24', '2022-05-15 23:17:24'),
(5, 5, 1, 1, 1, '2022-05-18 18:35:15', '2022-05-18 18:35:15');

-- --------------------------------------------------------

--
-- Table structure for table `simpanan`
--

CREATE TABLE `simpanan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `tujuan_simpanan_id` bigint(20) UNSIGNED NOT NULL,
  `jumlah_simpanan` double(12,2) NOT NULL,
  `deskripsi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_simpanan` tinyint(1) NOT NULL,
  `jenis_simpanan_id` bigint(20) UNSIGNED NOT NULL,
  `currency_id` bigint(20) UNSIGNED NOT NULL,
  `is_delete` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `simpanan`
--

INSERT INTO `simpanan` (`id`, `user_id`, `tujuan_simpanan_id`, `jumlah_simpanan`, `deskripsi`, `status_simpanan`, `jenis_simpanan_id`, `currency_id`, `is_delete`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 1, 1, 250000.00, 'Tabungan Buat Nikah', 0, 3, 1, 1, '2022-04-12 19:58:06', '2022-04-12 20:09:22', '2022-04-12 20:09:22'),
(3, 1, 1, 200000.00, 'Tabungan Buat Nikah', 0, 3, 1, 1, '2022-04-12 20:06:17', '2022-05-22 20:22:33', '2022-05-22 20:22:33'),
(4, 1, 1, 300000.00, 'Tabungan Buat Nikah', 0, 3, 1, 1, '2022-04-13 01:03:19', '2022-05-22 20:22:38', '2022-05-22 20:22:38'),
(6, 1, 1, 500000.00, 'Simpanan tabungan', 0, 5, 1, 1, '2022-05-21 02:42:34', '2022-05-21 04:18:11', '2022-05-21 04:18:11'),
(7, 1, 1, 1000000.00, 'Simpanan Uang Kuliah', 0, 5, 1, 0, '2022-05-22 20:24:12', '2022-05-22 20:24:12', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tagihan`
--

CREATE TABLE `tagihan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `nama_tagihan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori_tagihan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `no_rekening` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_tagihan` int(20) DEFAULT NULL,
  `kode_bank` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deksripsi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jumlah_tagihan` double(8,2) NOT NULL,
  `status_tagihan` tinyint(1) DEFAULT NULL,
  `tanggal_tagihan` timestamp NULL DEFAULT NULL,
  `status_tagihan_lunas` tinyint(1) DEFAULT NULL,
  `tanggal_tagihan_lunas` timestamp NULL DEFAULT NULL,
  `is_delete` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tagihan`
--

INSERT INTO `tagihan` (`id`, `user_id`, `nama_tagihan`, `kategori_tagihan_id`, `no_rekening`, `no_tagihan`, `kode_bank`, `deksripsi`, `jumlah_tagihan`, `status_tagihan`, `tanggal_tagihan`, `status_tagihan_lunas`, `tanggal_tagihan_lunas`, `is_delete`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Tagihan Listrik', 1, NULL, 1726632553, 'KDB', 'Pembayaran listrik bulanan ', 100000.00, 1, '2022-05-24 13:32:46', 0, NULL, 0, '2022-05-23 13:32:46', NULL, NULL),
(2, 1, 'bayar listrik', 1, NULL, 12342424, NULL, NULL, 100000.00, 1, '2022-04-11 03:44:27', 0, NULL, 0, '2022-05-24 23:56:01', '2022-05-24 23:56:01', NULL),
(3, 1, 'Tagihan Listrik 2', 1, '082737364', 82737364, NULL, 'Tagihan Listrik kontrakan 2', 20000.00, 1, '2022-05-24 17:00:00', 0, NULL, 0, '2022-05-25 02:28:18', '2022-05-25 02:28:18', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tujuan_keuangan`
--

CREATE TABLE `tujuan_keuangan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `nama_tujuan_keuangan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_fleksibel` tinyint(1) NOT NULL,
  `nominal` double(12,2) NOT NULL,
  `nominal_goals` double(12,2) NOT NULL,
  `kategori_tujuan_keuangan_id` bigint(20) UNSIGNED NOT NULL,
  `simpanan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `currency_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal` timestamp NULL DEFAULT NULL,
  `status_tujuan_keuangan` tinyint(1) NOT NULL,
  `hutang_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_delete` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tujuan_keuangan`
--

INSERT INTO `tujuan_keuangan` (`id`, `user_id`, `nama_tujuan_keuangan`, `status_fleksibel`, `nominal`, `nominal_goals`, `kategori_tujuan_keuangan_id`, `simpanan_id`, `currency_id`, `tanggal`, `status_tujuan_keuangan`, `hutang_id`, `is_delete`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'pengen beli baju', 1, 2000000.00, 20000.00, 1, NULL, 1, '2022-04-12 03:18:54', 0, NULL, 0, '2022-04-13 01:02:31', '2022-04-13 03:04:19', NULL),
(2, 1, 'bayar kuliah', 1, 2000000.00, 0.00, 1, NULL, 1, '2022-04-12 03:18:54', 0, NULL, 1, '2022-04-13 01:03:10', '2022-04-13 01:24:57', '2022-04-13 01:24:57'),
(3, 1, 'bayar uang kuliah', 1, 200000.00, 200000.00, 1, NULL, 1, '2022-06-17 17:00:00', 1, NULL, 0, '2022-06-18 00:19:59', '2022-06-18 01:36:53', NULL),
(4, 1, 'bayar uang kuliah', 1, 200000.00, 20000.00, 1, NULL, 1, '2022-06-17 17:00:00', 0, NULL, 0, '2022-06-18 00:20:04', '2022-06-18 00:52:45', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tujuan_simpanan`
--

CREATE TABLE `tujuan_simpanan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_tujuan_simpanan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi_tujuan_simpanan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `is_delete` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tujuan_simpanan`
--

INSERT INTO `tujuan_simpanan` (`id`, `nama_tujuan_simpanan`, `deskripsi_tujuan_simpanan`, `is_active`, `is_delete`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Untuk Bayar uang nikah', 'Bayaran Uang nikah ', 1, 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'didin nur yahya', 'didinnuryahya@gmail.com', NULL, '$2y$10$1gwN0YeWLGFJiq31RmzzM.GWP.3MgHgJaTYMRV/39TtbBT38r2Y1G', NULL, '2022-04-06 23:10:27', '2022-04-06 23:10:27'),
(2, 'salma aulia', 'salmaaulia@gmail.com', NULL, '$2y$10$UB62PQVPDRR12z58m2Vm..PXOtvlY4dRZ.PctVYUJyqWukzORVFLm', NULL, '2022-05-15 22:52:39', '2022-05-15 22:52:39'),
(3, 'francesco', 'francescovanboteng@gmail.com', NULL, '$2y$10$ZXGwr2ra8albeSmtK/T3UewSUlaW1lkHzFVtI.QP2y7MgnvHW6mnO', NULL, '2022-05-15 23:15:07', '2022-05-15 23:15:07'),
(4, 'jquera', 'jquerasantos@gmail.com', NULL, '$2y$10$TYMN3dv0u5rIhxCGfh0yhe4n780Dn2Q6cPoA5qlyqwjqHF2mZIDPy', NULL, '2022-05-15 23:17:24', '2022-05-15 23:17:24'),
(5, 'salma aulia', 'salmaaulia2@gmail.com', NULL, '$2y$10$9d8HN/WOWBQLrh0GeUARpuosg9XF.VuXgrzBfGmz2w1cbXzPLWz/S', NULL, '2022-05-18 18:35:15', '2022-05-18 18:35:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_email_unique` (`email`);

--
-- Indexes for table `currency`
--
ALTER TABLE `currency`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `goals_tujuan_keuangan`
--
ALTER TABLE `goals_tujuan_keuangan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `goals_tujuan_keuangan_user_id_foreign` (`user_id`),
  ADD KEY `goals_tujuan_keuangan_tujuan_keuangan_id_foreign` (`tujuan_keuangan_id`);

--
-- Indexes for table `hutang`
--
ALTER TABLE `hutang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hutang_user_id_foreign` (`user_id`),
  ADD KEY `hutang_currency_id_foreign` (`currency_id`);

--
-- Indexes for table `jenis_simpanan`
--
ALTER TABLE `jenis_simpanan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kategori_laporan_keuangan`
--
ALTER TABLE `kategori_laporan_keuangan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kategori_pemasukan`
--
ALTER TABLE `kategori_pemasukan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kategori_pengeluaran`
--
ALTER TABLE `kategori_pengeluaran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kategori_tagihan`
--
ALTER TABLE `kategori_tagihan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kategori_tujuan_keuangan`
--
ALTER TABLE `kategori_tujuan_keuangan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `laporan_keuangan`
--
ALTER TABLE `laporan_keuangan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `laporan_keuangan_user_id_foreign` (`user_id`),
  ADD KEY `laporan_keuangan_currency_id_foreign` (`currency_id`),
  ADD KEY `laporan_keuangan_kategori_laporan_keuangan_id_foreign` (`kategori_laporan_keuangan_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_access_tokens_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_auth_codes`
--
ALTER TABLE `oauth_auth_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_auth_codes_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_clients_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `pemasukan`
--
ALTER TABLE `pemasukan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pemasukan_user_id_foreign` (`user_id`),
  ADD KEY `pemasukan_currency_id_foreign` (`currency_id`),
  ADD KEY `pemasukan_kategori_pemasukan_id_foreign` (`kategori_pemasukan_id`);

--
-- Indexes for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pengeluaran_user_id_foreign` (`user_id`),
  ADD KEY `pengeluaran_hutang_id_foreign` (`hutang_id`),
  ADD KEY `pengeluaran_currency_id_foreign` (`currency_id`),
  ADD KEY `pengeluaran_kategori_pengeluaran_id_foreign` (`kategori_pengeluaran_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `piutang`
--
ALTER TABLE `piutang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `piutang_user_id_foreign` (`user_id`),
  ADD KEY `piutang_currency_id_foreign` (`currency_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `settings_user_id_foreign` (`user_id`),
  ADD KEY `settings_currency_id_foreign` (`currency_id`);

--
-- Indexes for table `simpanan`
--
ALTER TABLE `simpanan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `simpanan_user_id_foreign` (`user_id`),
  ADD KEY `simpanan_currency_id_foreign` (`currency_id`),
  ADD KEY `simpanan_tujuan_simpanan_id_foreign` (`tujuan_simpanan_id`),
  ADD KEY `simpanan_jenis_simpanan_id_foreign` (`jenis_simpanan_id`);

--
-- Indexes for table `tagihan`
--
ALTER TABLE `tagihan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tagihan_user_id_foreign` (`user_id`),
  ADD KEY `tagihan_kategori_tagihan_id_foreign` (`kategori_tagihan_id`);

--
-- Indexes for table `tujuan_keuangan`
--
ALTER TABLE `tujuan_keuangan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tujuan_keuangan_user_id_foreign` (`user_id`),
  ADD KEY `tujuan_keuangan_kategori_tujuan_keuangan_id_foreign` (`kategori_tujuan_keuangan_id`),
  ADD KEY `tujuan_keuangan_hutang_id_foreign` (`hutang_id`),
  ADD KEY `tujuan_keuangan_currency_id_foreign` (`currency_id`),
  ADD KEY `tujuan_keuangan_simpanan_id_foreign` (`simpanan_id`);

--
-- Indexes for table `tujuan_simpanan`
--
ALTER TABLE `tujuan_simpanan`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `currency`
--
ALTER TABLE `currency`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `goals_tujuan_keuangan`
--
ALTER TABLE `goals_tujuan_keuangan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `hutang`
--
ALTER TABLE `hutang`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `jenis_simpanan`
--
ALTER TABLE `jenis_simpanan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `kategori_laporan_keuangan`
--
ALTER TABLE `kategori_laporan_keuangan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `kategori_pemasukan`
--
ALTER TABLE `kategori_pemasukan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `kategori_pengeluaran`
--
ALTER TABLE `kategori_pengeluaran`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `kategori_tagihan`
--
ALTER TABLE `kategori_tagihan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `kategori_tujuan_keuangan`
--
ALTER TABLE `kategori_tujuan_keuangan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `laporan_keuangan`
--
ALTER TABLE `laporan_keuangan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pemasukan`
--
ALTER TABLE `pemasukan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `piutang`
--
ALTER TABLE `piutang`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `simpanan`
--
ALTER TABLE `simpanan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tagihan`
--
ALTER TABLE `tagihan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tujuan_keuangan`
--
ALTER TABLE `tujuan_keuangan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tujuan_simpanan`
--
ALTER TABLE `tujuan_simpanan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `goals_tujuan_keuangan`
--
ALTER TABLE `goals_tujuan_keuangan`
  ADD CONSTRAINT `goals_tujuan_keuangan_tujuan_keuangan_id_foreign` FOREIGN KEY (`tujuan_keuangan_id`) REFERENCES `tujuan_keuangan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `goals_tujuan_keuangan_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hutang`
--
ALTER TABLE `hutang`
  ADD CONSTRAINT `hutang_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currency` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hutang_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `laporan_keuangan`
--
ALTER TABLE `laporan_keuangan`
  ADD CONSTRAINT `laporan_keuangan_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currency` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `laporan_keuangan_kategori_laporan_keuangan_id_foreign` FOREIGN KEY (`kategori_laporan_keuangan_id`) REFERENCES `kategori_laporan_keuangan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `laporan_keuangan_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pemasukan`
--
ALTER TABLE `pemasukan`
  ADD CONSTRAINT `pemasukan_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currency` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pemasukan_kategori_pemasukan_id_foreign` FOREIGN KEY (`kategori_pemasukan_id`) REFERENCES `kategori_pemasukan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pemasukan_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
  ADD CONSTRAINT `pengeluaran_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currency` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pengeluaran_hutang_id_foreign` FOREIGN KEY (`hutang_id`) REFERENCES `hutang` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pengeluaran_kategori_pengeluaran_id_foreign` FOREIGN KEY (`kategori_pengeluaran_id`) REFERENCES `kategori_pengeluaran` (`id`),
  ADD CONSTRAINT `pengeluaran_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `piutang`
--
ALTER TABLE `piutang`
  ADD CONSTRAINT `piutang_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currency` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `piutang_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `settings`
--
ALTER TABLE `settings`
  ADD CONSTRAINT `settings_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currency` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `settings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `simpanan`
--
ALTER TABLE `simpanan`
  ADD CONSTRAINT `simpanan_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currency` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `simpanan_jenis_simpanan_id_foreign` FOREIGN KEY (`jenis_simpanan_id`) REFERENCES `jenis_simpanan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `simpanan_tujuan_simpanan_id_foreign` FOREIGN KEY (`tujuan_simpanan_id`) REFERENCES `tujuan_simpanan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `simpanan_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tagihan`
--
ALTER TABLE `tagihan`
  ADD CONSTRAINT `tagihan_kategori_tagihan_id_foreign` FOREIGN KEY (`kategori_tagihan_id`) REFERENCES `kategori_tagihan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tagihan_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tujuan_keuangan`
--
ALTER TABLE `tujuan_keuangan`
  ADD CONSTRAINT `tujuan_keuangan_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currency` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tujuan_keuangan_hutang_id_foreign` FOREIGN KEY (`hutang_id`) REFERENCES `hutang` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tujuan_keuangan_kategori_tujuan_keuangan_id_foreign` FOREIGN KEY (`kategori_tujuan_keuangan_id`) REFERENCES `kategori_tujuan_keuangan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tujuan_keuangan_simpanan_id_foreign` FOREIGN KEY (`simpanan_id`) REFERENCES `simpanan` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tujuan_keuangan_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
