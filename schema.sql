DROP DATABASE hafalan_tracker;
CREATE DATABASE hafalan_tracker;

USE hafalan_tracker;
DROP TABLE IF EXISTS orangtua_santri, setoran_hafalan, setoran_murajaah, target_hafalan, notifikasi, logs, santri, kelas, users;

USE hafalan_tracker;
-- ----------------------------------------------------------------------
-- Hafalan Tracker - Database Schema
-- Generated: 2025-06-03
-- ----------------------------------------------------------------------
SET FOREIGN_KEY_CHECKS = 1;

-- 1. Tabel users
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','ustadz','orangtua') NOT NULL DEFAULT 'orangtua',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Tabel kelas
CREATE TABLE `kelas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_kelas` varchar(255) NOT NULL,
  `deskripsi` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Tabel santri
CREATE TABLE `santri` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `tahun_masuk` int NOT NULL,
  `ustadz_id` bigint unsigned NOT NULL,
  `kelas_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `santri_ustadz_id_foreign` (`ustadz_id`),
  KEY `santri_kelas_id_foreign` (`kelas_id`),
  CONSTRAINT `santri_ustadz_id_foreign` FOREIGN KEY (`ustadz_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `santri_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Tabel orangtua_santri
CREATE TABLE `orangtua_santri` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `orangtua_id` bigint unsigned NOT NULL,
  `santri_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `orangtua_santri_orangtua_id_foreign` (`orangtua_id`),
  KEY `orangtua_santri_santri_id_foreign` (`santri_id`),
  CONSTRAINT `orangtua_santri_orangtua_id_foreign` FOREIGN KEY (`orangtua_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `orangtua_santri_santri_id_foreign` FOREIGN KEY (`santri_id`) REFERENCES `santri` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Tabel target_hafalan
CREATE TABLE `target_hafalan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `santri_id` bigint unsigned NOT NULL,
  `target_ayat` varchar(255) NOT NULL,
  `deadline` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `target_hafalan_santri_id_foreign` (`santri_id`),
  CONSTRAINT `target_hafalan_santri_id_foreign` FOREIGN KEY (`santri_id`) REFERENCES `santri` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Tabel setoran_hafalan
CREATE TABLE `setoran_hafalan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `santri_id` bigint unsigned NOT NULL,
  `surat` varchar(255) NOT NULL,
  `ayat_mulai` int NOT NULL,
  `ayat_selesai` int NOT NULL,
  `jumlah_ayat` int NOT NULL,
  `nilai_quality` enum('A','B','C','D') NOT NULL DEFAULT 'B',
  `catatan` text,
  `tgl_setor` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `setoran_hafalan_santri_id_foreign` (`santri_id`),
  CONSTRAINT `setoran_hafalan_santri_id_foreign` FOREIGN KEY (`santri_id`) REFERENCES `santri` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Tabel setoran_murajaah
CREATE TABLE `setoran_murajaah` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `santri_id` bigint unsigned NOT NULL,
  `surat` varchar(255) NOT NULL,
  `ayat` int NOT NULL,
  `jumlah_ulangan` int NOT NULL,
  `tgl_murajaah` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `setoran_murajaah_santri_id_foreign` (`santri_id`),
  CONSTRAINT `setoran_murajaah_santri_id_foreign` FOREIGN KEY (`santri_id`) REFERENCES `santri` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Tabel notifikasi
CREATE TABLE `notifikasi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `pesan` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifikasi_user_id_foreign` (`user_id`),
  CONSTRAINT `notifikasi_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. Tabel logs (untuk Monolog opsional)
CREATE TABLE `logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `level` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `context` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. Tabel bookmarks
CREATE TABLE `bookmarks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `surah` int NOT NULL,
  `ayat` int NOT NULL,
  `surah_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bookmarks_user_id_foreign` (`user_id`),
  CONSTRAINT `bookmarks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. Tabel quran_quotes
CREATE TABLE `quran_quotes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `surah_name` varchar(255) NOT NULL,
  `surah_number` int DEFAULT NULL,
  `ayat_number` int NOT NULL,
  `arabic_text` text NOT NULL,
  `translation` text NOT NULL,
  `theme` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Selesai
SET FOREIGN_KEY_CHECKS = 1;