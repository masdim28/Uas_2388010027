-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 04, 2026 at 03:52 PM
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
-- Database: `adeafwa_boutique`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('ade-afwa-boutique-cache-77de68daecd823babbb58edb1c8e14d7106e83bb', 'i:2;', 1780460264),
('ade-afwa-boutique-cache-77de68daecd823babbb58edb1c8e14d7106e83bb:timer', 'i:1780460264;', 1780460264),
('ade-afwa-boutique-cache-da4b9237bacccdf19c0760cab7aec4a8359010b0', 'i:2;', 1780459502),
('ade-afwa-boutique-cache-da4b9237bacccdf19c0760cab7aec4a8359010b0:timer', 'i:1780459502;', 1780459502);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cart_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `qty` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `product_variant_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `parent_id`, `created_at`, `updated_at`) VALUES
(1, 'SARIMBIT', 'sarimbit', NULL, '2026-06-02 20:57:04', '2026-06-02 20:57:04'),
(2, 'Sarimbit Keluarga', 'sarimbit-keluarga', 1, '2026-06-02 20:57:04', '2026-06-02 20:57:04'),
(3, 'Sarimbit Couple', 'sarimbit-couple', 1, '2026-06-02 20:57:04', '2026-06-02 20:57:04'),
(4, 'Sarimbit Lebaran', 'sarimbit-lebaran', 1, '2026-06-02 20:57:04', '2026-06-02 20:57:04'),
(5, 'BEST SELLER', 'best-seller', NULL, '2026-06-02 20:57:04', '2026-06-02 20:57:04'),
(6, 'NEW ARRIVAL', 'new-arrival', NULL, '2026-06-02 20:57:04', '2026-06-02 20:57:04'),
(7, 'PERLENGKAPAN SALAT', 'perlengkapan-salat', NULL, '2026-06-02 20:57:04', '2026-06-02 20:57:04'),
(8, 'Mukena', 'mukena', 7, '2026-06-02 20:57:04', '2026-06-02 20:57:04'),
(9, 'Sajadah', 'sajadah', 7, '2026-06-02 20:57:04', '2026-06-02 20:57:04'),
(10, 'Tasbih', 'tasbih', 7, '2026-06-02 20:57:04', '2026-06-02 20:57:04'),
(11, 'Peci', 'peci', 7, '2026-06-02 20:57:04', '2026-06-02 20:57:04'),
(12, 'Al-Qur’an', 'al-quran', 7, '2026-06-02 20:57:04', '2026-06-02 20:57:04'),
(13, 'HIJAB', 'hijab', NULL, '2026-06-02 20:57:04', '2026-06-02 20:57:04'),
(14, 'Pashmina', 'pashmina', 13, '2026-06-02 20:57:04', '2026-06-02 20:57:04'),
(15, 'Segi Empat', 'segi-empat', 13, '2026-06-02 20:57:04', '2026-06-02 20:57:04'),
(16, 'Bergo', 'bergo', 13, '2026-06-02 20:57:04', '2026-06-02 20:57:04'),
(17, 'Khimar', 'khimar', 13, '2026-06-02 20:57:04', '2026-06-02 20:57:04'),
(18, 'ACCESSORIES', 'accessories', NULL, '2026-06-02 20:57:04', '2026-06-02 20:57:04'),
(19, 'Bros', 'bros', 18, '2026-06-02 20:57:04', '2026-06-02 20:57:04'),
(20, 'Ciput / Inner', 'ciput-inner', 18, '2026-06-02 20:57:04', '2026-06-02 20:57:04'),
(21, 'Peniti Hijab', 'peniti-hijab', 18, '2026-06-02 20:57:04', '2026-06-02 20:57:04'),
(22, 'Bandana / Headpiece', 'bandana-headpiece', 18, '2026-06-02 20:57:04', '2026-06-02 20:57:04');

-- --------------------------------------------------------

--
-- Table structure for table `category_product`
--

CREATE TABLE `category_product` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `category_product`
--

INSERT INTO `category_product` (`id`, `product_id`, `category_id`, `created_at`, `updated_at`) VALUES
(1, 1, 2, NULL, NULL),
(2, 1, 5, NULL, NULL),
(3, 1, 6, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_04_18_012402_create_categories_table', 1),
(5, '2026_04_18_012403_create_carts_table', 1),
(6, '2026_04_18_012403_create_products_table', 1),
(7, '2026_04_18_012404_create_cart_items_table', 1),
(8, '2026_04_18_012404_create_orders_table', 1),
(9, '2026_04_18_012406_create_order_items_table', 1),
(10, '2026_04_18_211537_add_role_to_users_table', 1),
(11, '2026_04_19_202802_add_details_to_users_table', 1),
(12, '2026_04_21_002743_add_parent_id_and_slug_to_categories_table', 1),
(13, '2026_04_21_071149_create_category_product_table', 1),
(14, '2026_04_22_021042_create_product_images_table', 1),
(15, '2026_04_27_115248_create_product_variants_table', 1),
(16, '2026_04_27_115321_add_variant_id_to_items', 1),
(17, '2026_04_29_025444_add_weight_to_products_table', 1),
(18, '2026_05_12_075600_create_payment_methods_table', 1),
(19, '2026_05_12_075601_add_payment_method_id_to_orders_table', 1),
(20, '2026_05_15_add_fields_to_product_variants_table', 1),
(21, '2026_05_16_101625_add_is_blocked_to_users_table', 1),
(22, '2026_05_16_205148_add_resi_and_dates_to_orders_table', 1),
(23, '2026_05_17_122628_change_status_payment_to_string_in_orders_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `total_price` int(11) NOT NULL,
  `status_payment` varchar(255) NOT NULL DEFAULT 'pending',
  `status_shipping` varchar(255) NOT NULL DEFAULT 'pending',
  `recipient_name` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address_details` text DEFAULT NULL,
  `courier` varchar(255) DEFAULT NULL,
  `shipping_cost` int(11) NOT NULL DEFAULT 0,
  `resi` varchar(255) DEFAULT NULL,
  `shipped_at` timestamp NULL DEFAULT NULL,
  `payment_method` varchar(255) NOT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `payment_method_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `qty` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `product_variant_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('bank_transfer','qris','ewallet','cod') NOT NULL,
  `account_number` varchar(255) DEFAULT NULL,
  `account_name` varchar(255) DEFAULT NULL,
  `qr_code` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `name`, `type`, `account_number`, `account_name`, `qr_code`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Qris', 'qris', NULL, 'Ade afwa Boutique', 'payment_methods/DM0zwCcc1FPNKVPaadaqNTyRWnwQBkPRijiJuiIZ.png', 1, '2026-06-02 21:07:55', '2026-06-02 21:07:55');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `weight` int(11) NOT NULL DEFAULT 500,
  `stock` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `clicks` int(11) NOT NULL DEFAULT 0,
  `status` varchar(255) NOT NULL DEFAULT 'ready',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `weight`, `stock`, `description`, `image`, `clicks`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Jaket', 250000, 500, 25, 'Gamis Daily Premium Nyaman & Anti Gerah\r\nSering merasa gerah atau tidak bebas bergerak saat pakai gamis seharian? [Nama Produk] hadir sebagai solusi terbaik! Didesain khusus dengan mengutamakan kenyamanan maksimal tanpa mengorbankan tampilan yang anggun dan rapi.\r\n\r\nCocok banget untuk aktivitas harian, mulai dari santai di rumah, belanja, hangout, hingga menghadiri acara formal.\r\n\r\n✨ KEUNGGULAN PRODUK\r\nKenyamanan Premium: Menggunakan bahan [Sebutkan jenis bahan, misal: Crinkle Airflow / Rayon Premium] yang super lembut, adem, dan memiliki sirkulasi udara yang baik. Dijamin tidak bikin gerah walau dipakai di cuaca terik.\r\n\r\nKarakter Kain Juara: Bahan jatuh (flowy), ringan namun TIDAK menerawang, dan tidak mudah kusut (ironless—cuci kering bisa langsung pakai!).\r\n\r\nCutting Proposional: Potongan longgar yang pas di badan, memberikan kesan jenjang dan ramping tanpa membatasi ruang gerakmu.\r\n\r\n🔎 DETAIL DESAIN\r\nBusui Friendly: Dilengkapi resleting depan (zipper) berkualitas yang praktis untuk ibu menyusui.\r\n\r\nWudhu Friendly: Bagian pergelangan tangan menggunakan [karet elastis lembut / kancing] yang mudah ditarik ke atas saat akan berwudhu.\r\n\r\nSaku Fungsional: Terdapat saku tersembunyi di bagian kanan untuk menyimpan handphone atau dompet kecil.\r\n\r\n🎨 PILIHAN WARNA\r\nTersedia dalam varian warna aesthetic yang mudah dipadukan:\r\n\r\n[Warna 1, misal: Sage Green]\r\n\r\n[Warna 2, misal: Mocca]\r\n\r\n[Warna 3, misal: Jetblack]\r\n\r\n[Warna 4, misal: Soft Pink]\r\n\r\n📏 PANDUAN UKURAN (SIZE CHART)\r\n(Ganti detail di bawah ini sesuai dengan ukuran asli produk Anda)\r\n\r\nSize M : Lingkar Dada (LD) 100 cm | Panjang Badan (PB) 135 cm\r\n\r\nSize L  : Lingkar Dada (LD) 105 cm | Panjang Badan (PB) 138 cm\r\n\r\nSize XL : Lingkar Dada (LD) 110 cm | Panjang Badan (PB) 140 cm\r\n(Toleransi jahitan 1-2 cm)\r\n\r\n📦 INFO PENGIRIMAN & GARANSI\r\nJam Operasional Pengiriman: Senin - Sabtu (Pesanan sebelum jam 15.00 WIB dikirim di hari yang sama).\r\n\r\nGaransi 100%: Jika produk yang diterima cacat, salah warna, atau salah ukuran, silakan hubungi admin via chat sebelum memberikan penilaian untuk proses retur/pengembalian dana. (Wajib menyertakan video unboxing).\r\n\r\nYuk, rasakan kenyamanan tampil anggun seharian penuh! Klik \"Beli Sekarang\" atau \"Masukkan Keranjang\" sebelum warna favoritmu kehabisan! ✨', 'products/sXtrVfe2vdne0IvIfag755yW1m3R1gXe9HCffyQJ.png', 2, 'ready', '2026-06-02 21:09:48', '2026-06-02 21:10:23');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `created_at`, `updated_at`) VALUES
(1, 1, 'products/sXtrVfe2vdne0IvIfag755yW1m3R1gXe9HCffyQJ.png', '2026-06-02 21:09:48', '2026-06-02 21:09:48');

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `color` varchar(255) DEFAULT NULL,
  `size` varchar(255) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `price` int(11) NOT NULL DEFAULT 0,
  `weight` int(11) NOT NULL DEFAULT 500,
  `status` varchar(255) NOT NULL DEFAULT 'ready',
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`id`, `product_id`, `color`, `size`, `stock`, `price`, `weight`, `status`, `image`, `created_at`, `updated_at`) VALUES
(1, 1, 'Hijau', 'L', 10, 300000, 500, 'ready', 'products/variants/MlRTzqKbMxUXr1w1ozjd8JIpBTmYMZKeVT2ffAOW.png', '2026-06-02 21:09:48', '2026-06-02 21:09:48'),
(2, 1, 'Putih', 'L', 15, 250000, 500, 'ready', 'products/variants/eYjAadGsRbq0BhU3h5jZ2cXTmX1cJ9uPht0wzUH4.png', '2026-06-02 21:09:48', '2026-06-02 21:09:48');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('3QxJefojze7PpP0JEiCACJneWbskStz6NsPNzRuU', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'eyJfdG9rZW4iOiI3aGxwY21SZUVuSUU3TVU4V0lEQmdyaVk4TlJreTdvMkFtOFpUeHB3IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hZG1pblwvYXBpXC9tb250aGx5LXNhbGVzLXJlcG9ydD9tb250aD0wNiZ5ZWFyPTIwMjYiLCJyb3V0ZSI6ImFkbWluLmRhc2hib2FyZC5tb250aGx5LXNhbGVzLXJlcG9ydCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxfQ==', 1780578661),
('8FgbkrySATmavxLm80BUFhIqhpEVIw3pHUraDfbi', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJwSktPb1pXb2FocTVTT1lHVzhNSFl2NzhEZGRFQVpyRnJ0Sml0elBmIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcLz92ZXJpZmllZD0xIiwicm91dGUiOiJob21lIn0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjozLCJ1cmwiOltdfQ==', 1780460218),
('tfCLQvkrejJWcX6ltZPKeKVsQIhOx0ZjihtYgQkx', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'eyJfdG9rZW4iOiJaY25zeDgyZll0NlNrYVNOTjV5eW8yR2d5MXl2bVcyb2p1U2xnQ0p6IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hZG1pblwvdXNlcnMiLCJyb3V0ZSI6ImFkbWluLnVzZXJzLmluZGV4In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjF9', 1780460012);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `is_blocked` tinyint(1) NOT NULL DEFAULT 0,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `address`, `gender`, `email_verified_at`, `password`, `is_blocked`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Ade afwa', 'adeafwa87@gmail.com', '087729015880', 'Jl. Sutawinangun No. 84 45153 Kedawung Jawa Barat', 'male', NULL, '$2y$12$QTk7rFmtFhEVjKaXUj6OkOo5JqcsbyfdpnDYJgvqn/1HZ8EgQnP.e', 0, 'admin', NULL, '2026-06-02 20:59:39', '2026-06-02 20:59:39'),
(3, 'Dimas Adriansah', 'dimasadriansah28@gmail.com', '088972042818', 'Kuningan', 'male', '2026-06-02 21:16:44', '$2y$12$GKkKpad9BrB/uy6iOEMqtu0IIjnIMosMDraG1NGGdFC9XOLmdUCXy', 0, 'user', NULL, '2026-06-02 21:15:07', '2026-06-02 21:16:44');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carts_user_id_foreign` (`user_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_items_cart_id_foreign` (`cart_id`),
  ADD KEY `cart_items_product_id_foreign` (`product_id`),
  ADD KEY `cart_items_product_variant_id_foreign` (`product_variant_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`),
  ADD KEY `categories_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `category_product`
--
ALTER TABLE `category_product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_product_product_id_foreign` (`product_id`),
  ADD KEY `category_product_category_id_foreign` (`category_id`);

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
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_payment_method_id_foreign` (`payment_method_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_product_id_foreign` (`product_id`),
  ADD KEY `order_items_product_variant_id_foreign` (`product_variant_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_images_product_id_foreign` (`product_id`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_variants_product_id_foreign` (`product_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

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
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `category_product`
--
ALTER TABLE `category_product`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_cart_id_foreign` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `category_product`
--
ALTER TABLE `category_product`
  ADD CONSTRAINT `category_product_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `category_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`);

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
