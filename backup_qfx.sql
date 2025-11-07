-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: localhost    Database: qfx
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `alamat`
--

DROP TABLE IF EXISTS `alamat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `alamat` (
  `id_alamat` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_user` bigint unsigned NOT NULL,
  `label` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat_lengkap` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kota` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provinsi` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_alamat`),
  KEY `alamat_id_user_foreign` (`id_user`),
  CONSTRAINT `alamat_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `alamat`
--

LOCK TABLES `alamat` WRITE;
/*!40000 ALTER TABLE `alamat` DISABLE KEYS */;
INSERT INTO `alamat` VALUES (1,1,'Rumah','Jl. Mawar No. 12, Jakarta Selatan','Jakarta','DKI Jakarta',-6.2297000,106.6894000,'2025-10-22 10:04:08','2025-10-22 10:04:08'),(2,1,'Rumah','Jl. Melati No. 1, Jakarta Selatan','Jakarta','DKI Jakarta',-6.2297000,106.6894000,'2025-10-23 04:03:11','2025-10-23 04:03:11'),(3,3,'Kantor','Jl. Diponegoro No. 10, Bandung','Bandung','Jawa Barat',-6.9175000,107.6191000,'2025-10-23 04:03:11','2025-10-23 04:03:11'),(4,4,'Rumah','Jl. Ahmad Yani No. 23, Surabaya','Surabaya','Jawa Timur',-7.2575000,112.7521000,'2025-10-23 04:03:11','2025-10-23 04:03:11'),(5,1,'Rumah','Jl. Melati No. 1, Jakarta Selatan','Jakarta','DKI Jakarta',-6.2297000,106.6894000,'2025-10-23 04:04:30','2025-10-23 04:04:30'),(6,3,'Kantor','Jl. Diponegoro No. 10, Bandung','Bandung','Jawa Barat',-6.9175000,107.6191000,'2025-10-23 04:04:30','2025-10-23 04:04:30'),(7,4,'Rumah','Jl. Ahmad Yani No. 23, Surabaya','Surabaya','Jawa Timur',-7.2575000,112.7521000,'2025-10-23 04:04:30','2025-10-23 04:04:30'),(8,1,'Rumah','Jl. Melati No. 1, Jakarta Selatan','Jakarta','DKI Jakarta',-6.2297000,106.6894000,'2025-10-23 04:04:48','2025-10-23 04:04:48'),(9,3,'Kantor','Jl. Diponegoro No. 10, Bandung','Bandung','Jawa Barat',-6.9175000,107.6191000,'2025-10-23 04:04:48','2025-10-23 04:04:48'),(10,4,'Rumah','Jl. Ahmad Yani No. 23, Surabaya','Surabaya','Jawa Timur',-7.2575000,112.7521000,'2025-10-23 04:04:48','2025-10-23 04:04:48'),(11,1,'Rumah','Jl. Melati No. 1, Jakarta Selatan','Jakarta','DKI Jakarta',-6.2297000,106.6894000,'2025-10-23 04:05:32','2025-10-23 04:05:32'),(12,3,'Kantor','Jl. Diponegoro No. 10, Bandung','Bandung','Jawa Barat',-6.9175000,107.6191000,'2025-10-23 04:05:32','2025-10-23 04:05:32'),(13,4,'Rumah','Jl. Ahmad Yani No. 23, Surabaya','Surabaya','Jawa Timur',-7.2575000,112.7521000,'2025-10-23 04:05:32','2025-10-23 04:05:32');
/*!40000 ALTER TABLE `alamat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bukti_pekerjaan`
--

DROP TABLE IF EXISTS `bukti_pekerjaan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bukti_pekerjaan` (
  `id_bukti` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_pemesanan` bigint unsigned NOT NULL,
  `id_teknisi` bigint unsigned NOT NULL,
  `id_keahlian` bigint unsigned NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `foto_bukti` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_bukti`),
  KEY `fk_bukti_pemesanan` (`id_pemesanan`),
  KEY `fk_bukti_teknisi` (`id_teknisi`),
  KEY `fk_bukti_keahlian` (`id_keahlian`),
  CONSTRAINT `fk_bukti_keahlian` FOREIGN KEY (`id_keahlian`) REFERENCES `keahlian` (`id_keahlian`) ON DELETE CASCADE,
  CONSTRAINT `fk_bukti_pemesanan` FOREIGN KEY (`id_pemesanan`) REFERENCES `pemesanan` (`id_pemesanan`) ON DELETE CASCADE,
  CONSTRAINT `fk_bukti_teknisi` FOREIGN KEY (`id_teknisi`) REFERENCES `teknisi` (`id_teknisi`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bukti_pekerjaan`
--

LOCK TABLES `bukti_pekerjaan` WRITE;
/*!40000 ALTER TABLE `bukti_pekerjaan` DISABLE KEYS */;
INSERT INTO `bukti_pekerjaan` VALUES (30,8,1,1,'Perbaikan AC bocor dan penggantian freon berhasil dilakukan.','download.jpeg','2025-10-27 15:12:40','2025-10-30 18:13:39'),(31,9,2,2,'Servis motor: ganti busi, perbaikan sistem starter, dan pengecekan aki.','download_1.jpeg','2025-10-27 15:12:40','2025-10-30 18:13:36'),(32,10,3,3,'Renovasi dinding retak dan pengecatan ulang selesai dengan baik.','download_2.jpeg','2025-10-27 15:12:40','2025-10-30 18:13:32'),(33,11,4,4,'Perawatan AC dan penggantian kapasitor selesai dengan baik.','images.jpeg','2025-10-27 15:12:40','2025-10-30 18:13:28'),(34,12,5,5,'Kulkas diperbaiki, masalah pada kompresor sudah teratasi.','images_1.jpeg','2025-10-27 15:12:40','2025-10-30 18:13:24'),(35,13,1,1,'Servis lanjutan pada AC yang mati total, ganti fuse dan bersihkan evaporator.','images_2.jpeg','2025-10-27 15:12:40','2025-10-30 18:13:20'),(36,14,2,2,'Perbaikan sistem kelistrikan motor dan penggantian sekring.','images_3.jpeg','2025-10-27 15:12:40','2025-10-30 18:13:17'),(37,9,2,2,NULL,'DKFjafYaCVjJ9xP25rINue5N2TARy9hljFgHBXng.jpg','2025-10-30 10:52:56','2025-10-30 10:52:56'),(38,9,2,2,'7h7uha','jcRQ9Nm1jxsn9Iw9ULhGGNMKaBPC1mO7GBJD3cPO.jpg','2025-10-30 10:54:38','2025-10-30 10:54:38'),(39,9,2,2,'ss','tztcAYu0gLZh9fSnHPRExrutNArdqYAb9StKFaOj.jpg','2025-10-30 10:59:44','2025-10-30 10:59:44'),(40,9,2,2,'for','0wtRve0TuFuY2ykYnQ20A5xHBVTeUR9Ru2HBGEvK.jpg','2025-10-30 11:00:12','2025-10-30 11:00:12'),(41,9,2,2,'onok','Tgu4jT7qZOtDZ9HMyiInT39usvqmVVbVgtYrUlzJ.jpg','2025-10-30 11:05:04','2025-10-30 11:05:04');
/*!40000 ALTER TABLE `bukti_pekerjaan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gambar_layanan`
--

DROP TABLE IF EXISTS `gambar_layanan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gambar_layanan` (
  `id_gambar` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_teknisi` bigint unsigned NOT NULL,
  `id_keahlian` bigint unsigned NOT NULL,
  `nama_file` varchar(255) NOT NULL,
  `url_gambar` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_gambar`),
  KEY `fk_gambar_teknisi` (`id_teknisi`),
  KEY `fk_gambar_keahlian` (`id_keahlian`),
  CONSTRAINT `fk_gambar_keahlian` FOREIGN KEY (`id_keahlian`) REFERENCES `keahlian` (`id_keahlian`) ON DELETE CASCADE,
  CONSTRAINT `fk_gambar_teknisi` FOREIGN KEY (`id_teknisi`) REFERENCES `teknisi` (`id_teknisi`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gambar_layanan`
--

LOCK TABLES `gambar_layanan` WRITE;
/*!40000 ALTER TABLE `gambar_layanan` DISABLE KEYS */;
INSERT INTO `gambar_layanan` VALUES (1,1,1,'servis_ac_1.jpg','gambar_layanan/gambar_layanan2.jpeg','2025-10-23 11:46:11',NULL),(2,1,2,'servis_kulkas_1.jpg','gambar_layanan/gambar_layanan3.jpeg','2025-10-23 11:46:11',NULL),(3,2,4,'servis_motor_1.jpg','gambar_layanan/gambar_layanan5.jpeg','2025-10-23 11:46:11',NULL),(4,3,3,'renovasi_rumah_1.jpg','gambar_layanan/gambar_layanan6.jpeg','2025-10-23 11:46:11',NULL),(5,4,1,'ac_service_1.jpg','gambar_layanan/gambar_layanan4.jpeg','2025-10-23 11:46:11',NULL),(6,5,2,'kulkas_service_1.jpg','gambar_layanan/gambar_layanan5.jpeg','2025-10-23 11:46:11',NULL),(7,1,1,'gambar_layanan1.jpg','gambar_layanan/gambar_layanan1.jpg','2025-10-27 05:36:44','2025-10-27 05:36:44'),(8,1,1,'gambar_layanan2.jpg','gambar_layanan/gambar_layanan2.jpeg','2025-10-27 05:36:44','2025-10-27 05:36:44'),(9,2,4,'gambar_layanan3.jpg','gambar_layanan/gambar_layanan3.jpeg','2025-10-27 05:36:44','2025-10-27 05:36:44'),(10,3,3,'gambar_layanan4.jpg','gambar_layanan/gambar_layanan4.jpeg','2025-10-27 05:36:44','2025-10-27 05:36:44'),(11,4,1,'gambar_layanan5.jpg','gambar_layanan/gambar_layanan5.jpeg','2025-10-27 05:36:44','2025-10-27 05:36:44'),(12,5,2,'gambar_layanan6.jpg','gambar_layanan/gambar_layanan6.jpeg','2025-10-27 05:36:44','2025-10-27 05:36:44');
/*!40000 ALTER TABLE `gambar_layanan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kategori`
--

DROP TABLE IF EXISTS `kategori`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kategori` (
  `id_kategori` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_kategori`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kategori`
--

LOCK TABLES `kategori` WRITE;
/*!40000 ALTER TABLE `kategori` DISABLE KEYS */;
INSERT INTO `kategori` VALUES (1,'Elektronik',NULL,'2025-10-22 10:04:08','2025-10-22 10:04:08'),(2,'Bangunan',NULL,'2025-10-22 10:04:08','2025-10-22 10:04:08'),(3,'Elektronik','elektronik.png','2025-10-23 04:03:11','2025-10-23 04:03:11'),(4,'Bangunan','bangunan.png','2025-10-23 04:03:11','2025-10-23 04:03:11'),(5,'Kendaraan','kendaraan.png','2025-10-23 04:03:11','2025-10-23 04:03:11'),(6,'Elektronik','elektronik.png','2025-10-23 04:04:30','2025-10-23 04:04:30'),(7,'Bangunan','bangunan.png','2025-10-23 04:04:30','2025-10-23 04:04:30'),(8,'Kendaraan','kendaraan.png','2025-10-23 04:04:30','2025-10-23 04:04:30'),(9,'Elektronik','elektronik.png','2025-10-23 04:04:48','2025-10-23 04:04:48'),(10,'Bangunan','bangunan.png','2025-10-23 04:04:48','2025-10-23 04:04:48'),(11,'Kendaraan','kendaraan.png','2025-10-23 04:04:48','2025-10-23 04:04:48'),(12,'Elektronik','elektronik.png','2025-10-23 04:05:32','2025-10-23 04:05:32'),(13,'Bangunan','bangunan.png','2025-10-23 04:05:32','2025-10-23 04:05:32'),(14,'Kendaraan','kendaraan.png','2025-10-23 04:05:32','2025-10-23 04:05:32');
/*!40000 ALTER TABLE `kategori` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `keahlian`
--

DROP TABLE IF EXISTS `keahlian`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `keahlian` (
  `id_keahlian` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_kategori` bigint unsigned NOT NULL,
  `nama_keahlian` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_keahlian`),
  KEY `keahlian_id_kategori_foreign` (`id_kategori`),
  CONSTRAINT `keahlian_id_kategori_foreign` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `keahlian`
--

LOCK TABLES `keahlian` WRITE;
/*!40000 ALTER TABLE `keahlian` DISABLE KEYS */;
INSERT INTO `keahlian` VALUES (1,1,'Servis AC',NULL,'2025-10-22 10:04:08','2025-10-22 10:04:08'),(2,2,'Renovasi Rumah',NULL,'2025-10-22 10:04:08','2025-10-22 10:04:08'),(3,1,'Servis AC','Perbaikan dan perawatan AC rumah atau kantor','2025-10-23 04:03:11','2025-10-23 04:03:11'),(4,1,'Servis Kulkas','Perbaikan kulkas rusak atau tidak dingin','2025-10-23 04:03:11','2025-10-23 04:03:11'),(5,2,'Renovasi Rumah','Jasa renovasi bangunan kecil hingga besar','2025-10-23 04:03:11','2025-10-23 04:03:11'),(6,3,'Servis Motor','Perawatan motor dan ganti oli','2025-10-23 04:03:11','2025-10-23 04:03:11'),(7,1,'Servis AC','Perbaikan dan perawatan AC rumah atau kantor','2025-10-23 04:04:30','2025-10-23 04:04:30'),(8,1,'Servis Kulkas','Perbaikan kulkas rusak atau tidak dingin','2025-10-23 04:04:30','2025-10-23 04:04:30'),(9,2,'Renovasi Rumah','Jasa renovasi bangunan kecil hingga besar','2025-10-23 04:04:30','2025-10-23 04:04:30'),(10,3,'Servis Motor','Perawatan motor dan ganti oli','2025-10-23 04:04:30','2025-10-23 04:04:30'),(11,1,'Servis AC','Perbaikan dan perawatan AC rumah atau kantor','2025-10-23 04:04:48','2025-10-23 04:04:48'),(12,1,'Servis Kulkas','Perbaikan kulkas rusak atau tidak dingin','2025-10-23 04:04:48','2025-10-23 04:04:48'),(13,2,'Renovasi Rumah','Jasa renovasi bangunan kecil hingga besar','2025-10-23 04:04:48','2025-10-23 04:04:48'),(14,3,'Servis Motor','Perawatan motor dan ganti oli','2025-10-23 04:04:48','2025-10-23 04:04:48'),(15,1,'Servis AC','Perbaikan dan perawatan AC rumah atau kantor','2025-10-23 04:05:32','2025-10-23 04:05:32'),(16,1,'Servis Kulkas','Perbaikan kulkas rusak atau tidak dingin','2025-10-23 04:05:32','2025-10-23 04:05:32'),(17,2,'Renovasi Rumah','Jasa renovasi bangunan kecil hingga besar','2025-10-23 04:05:32','2025-10-23 04:05:32'),(18,3,'Servis Motor','Perawatan motor dan ganti oli','2025-10-23 04:05:32','2025-10-23 04:05:32');
/*!40000 ALTER TABLE `keahlian` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `keahlian_teknisi`
--

DROP TABLE IF EXISTS `keahlian_teknisi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `keahlian_teknisi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_teknisi` bigint unsigned NOT NULL,
  `id_keahlian` bigint unsigned NOT NULL,
  `harga_min` int DEFAULT NULL,
  `harga_max` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `keahlian_teknisi_id_teknisi_foreign` (`id_teknisi`),
  KEY `keahlian_teknisi_id_keahlian_foreign` (`id_keahlian`),
  CONSTRAINT `keahlian_teknisi_id_keahlian_foreign` FOREIGN KEY (`id_keahlian`) REFERENCES `keahlian` (`id_keahlian`) ON DELETE CASCADE,
  CONSTRAINT `keahlian_teknisi_id_teknisi_foreign` FOREIGN KEY (`id_teknisi`) REFERENCES `teknisi` (`id_teknisi`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `keahlian_teknisi`
--

LOCK TABLES `keahlian_teknisi` WRITE;
/*!40000 ALTER TABLE `keahlian_teknisi` DISABLE KEYS */;
INSERT INTO `keahlian_teknisi` VALUES (1,1,1,150000,300000,'2025-10-22 10:04:08','2025-10-22 10:04:08'),(2,1,1,200000,500000,'2025-10-23 04:04:30','2025-10-23 04:04:30'),(3,1,2,180000,450000,'2025-10-23 04:04:30','2025-10-23 04:04:30'),(4,2,4,120000,300000,'2025-10-23 04:04:30','2025-10-23 04:04:30'),(5,3,3,100000,250000,'2025-10-23 04:04:30','2025-10-23 04:04:30'),(6,4,1,220000,550000,'2025-10-23 04:04:30','2025-10-23 04:04:30'),(7,5,2,170000,380000,'2025-10-23 04:04:30','2025-10-23 04:04:30'),(8,1,1,160000,400000,'2025-10-23 04:04:48','2025-10-23 04:04:48'),(9,1,2,140000,320000,'2025-10-23 04:04:48','2025-10-23 04:04:48'),(10,2,4,250000,700000,'2025-10-23 04:04:48','2025-10-23 04:04:48'),(11,3,3,280000,750000,'2025-10-23 04:04:48','2025-10-23 04:04:48'),(12,4,1,130000,300000,'2025-10-23 04:04:48','2025-10-23 04:04:48'),(13,5,2,150000,340000,'2025-10-23 04:04:48','2025-10-23 04:04:48'),(14,1,1,120000,290000,'2025-10-23 04:05:32','2025-10-23 04:05:32'),(15,1,2,100000,260000,'2025-10-23 04:05:32','2025-10-23 04:05:32'),(16,2,4,180000,420000,'2025-10-23 04:05:32','2025-10-23 04:05:32'),(17,3,3,300000,800000,'2025-10-23 04:05:32','2025-10-23 04:05:32'),(18,4,1,500000,2000000,'2025-10-23 04:05:32','2025-10-23 04:05:32'),(19,5,2,350000,1500000,'2025-10-23 04:05:32','2025-10-23 04:05:32');
/*!40000 ALTER TABLE `keahlian_teknisi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000001_create_cache_table',1),(2,'0001_01_01_000002_create_jobs_table',1),(3,'2025_10_16_102944_create_personal_access_tokens_table',1),(4,'2025_10_20_165948_create_quickfix_tables',1),(5,'2025_10_22_165542_create_additional_tables',1),(6,'0001_01_01_000001_create_cache_table',1),(7,'0001_01_01_000002_create_jobs_table',1),(8,'2025_10_16_102944_create_personal_access_tokens_table',1),(9,'2025_10_20_165948_create_quickfix_tables',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifikasi`
--

DROP TABLE IF EXISTS `notifikasi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifikasi` (
  `id_notifikasi` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_user` bigint unsigned NOT NULL,
  `judul` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pesan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_notifikasi`),
  KEY `notifikasi_id_user_foreign` (`id_user`),
  CONSTRAINT `notifikasi_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifikasi`
--

LOCK TABLES `notifikasi` WRITE;
/*!40000 ALTER TABLE `notifikasi` DISABLE KEYS */;
INSERT INTO `notifikasi` VALUES (1,1,'Pemesanan Diterima','Teknisi telah menerima pemesanan kamu.',0,'2025-10-22 10:04:08','2025-10-22 10:04:08'),(2,1,'Pemesanan Diterima','Teknisi telah menerima pemesanan kamu.',0,'2025-10-23 04:05:32','2025-10-23 04:05:32'),(3,3,'Pembayaran Berhasil','Pembayaran telah dikonfirmasi.',1,'2025-10-23 04:05:32','2025-10-23 04:05:32'),(4,4,'Ulasan Diterima','Terima kasih telah memberi ulasan!',0,'2025-10-23 04:05:32','2025-10-23 04:05:32');
/*!40000 ALTER TABLE `notifikasi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pembayaran`
--

DROP TABLE IF EXISTS `pembayaran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pembayaran` (
  `id_pembayaran` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_pemesanan` bigint unsigned NOT NULL,
  `metode` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'transfer',
  `jumlah` decimal(12,2) NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `bukti_transfer` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_pembayaran`),
  KEY `pembayaran_id_pemesanan_foreign` (`id_pemesanan`),
  CONSTRAINT `pembayaran_id_pemesanan_foreign` FOREIGN KEY (`id_pemesanan`) REFERENCES `pemesanan` (`id_pemesanan`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pembayaran`
--

LOCK TABLES `pembayaran` WRITE;
/*!40000 ALTER TABLE `pembayaran` DISABLE KEYS */;
/*!40000 ALTER TABLE `pembayaran` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pemesanan`
--

DROP TABLE IF EXISTS `pemesanan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pemesanan` (
  `id_pemesanan` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kode_pemesanan` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_pelanggan` bigint unsigned NOT NULL,
  `id_teknisi` bigint unsigned NOT NULL,
  `id_keahlian` bigint unsigned NOT NULL,
  `tanggal_booking` date NOT NULL,
  `keluhan` text COLLATE utf8mb4_unicode_ci,
  `harga` decimal(12,2) DEFAULT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_pemesanan`),
  UNIQUE KEY `pemesanan_kode_pemesanan_unique` (`kode_pemesanan`),
  KEY `pemesanan_id_pelanggan_foreign` (`id_pelanggan`),
  KEY `pemesanan_id_teknisi_foreign` (`id_teknisi`),
  KEY `pemesanan_id_keahlian_foreign` (`id_keahlian`),
  CONSTRAINT `pemesanan_id_keahlian_foreign` FOREIGN KEY (`id_keahlian`) REFERENCES `keahlian` (`id_keahlian`) ON DELETE CASCADE,
  CONSTRAINT `pemesanan_id_pelanggan_foreign` FOREIGN KEY (`id_pelanggan`) REFERENCES `user` (`id_user`) ON DELETE CASCADE,
  CONSTRAINT `pemesanan_id_teknisi_foreign` FOREIGN KEY (`id_teknisi`) REFERENCES `teknisi` (`id_teknisi`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pemesanan`
--

LOCK TABLES `pemesanan` WRITE;
/*!40000 ALTER TABLE `pemesanan` DISABLE KEYS */;
INSERT INTO `pemesanan` VALUES (8,'ORD-001',2,1,1,'2025-10-20','AC tidak dingin dan berisik.',250000.00,'selesai','2025-10-27 15:11:28','2025-10-27 15:11:28'),(9,'ORD-002',3,2,2,'2025-10-21','Motor tidak bisa distarter.',150000.00,'completed','2025-10-27 15:11:28','2025-10-27 15:11:28'),(10,'ORD-003',4,3,3,'2025-10-22','Tembok retak dan perlu pengecatan ulang.',500000.00,'proses','2025-10-27 15:11:28','2025-10-27 15:11:28'),(11,'ORD-004',5,4,4,'2025-10-23','AC bocor dan tidak dingin.',300000.00,'selesai','2025-10-27 15:11:28','2025-10-27 15:11:28'),(12,'ORD-005',6,5,5,'2025-10-24','Kulkas tidak dingin.',200000.00,'selesai','2025-10-27 15:11:28','2025-10-27 15:11:28'),(13,'ORD-006',7,1,1,'2025-10-25','AC mati total setelah 1 jam pemakaian.',350000.00,'proses','2025-10-27 15:11:28','2025-10-27 15:11:28'),(14,'ORD-007',8,2,2,'2025-10-26','Kelistrikan motor sering korslet.',180000.00,'menunggu','2025-10-27 15:11:28','2025-10-27 15:11:28');
/*!40000 ALTER TABLE `pemesanan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
INSERT INTO `personal_access_tokens` VALUES (1,'App\\Models\\User',3,'mobile_token','e3513efff501436f9f96d2685b3c4c92592f17a69873fcd287e0f48599accce3','[\"*\"]',NULL,NULL,'2025-10-22 10:06:05','2025-10-22 10:06:05'),(2,'App\\Models\\User',4,'mobile_token','75d7749556cc3ac22f6bfd74ae18737fb2ba34d05b4e2dffe96788fa99e536fe','[\"*\"]',NULL,NULL,'2025-10-22 10:09:14','2025-10-22 10:09:14'),(3,'App\\Models\\User',5,'mobile_token','0f8d2f9110d36c451530626c6a2e0d464955582bd1a53326c3611d229bfc3d5d','[\"*\"]',NULL,NULL,'2025-10-22 10:21:06','2025-10-22 10:21:06'),(4,'App\\Models\\User',5,'mobile_token','3e48f64625ae377cf5cc0e2a038fd9fc6d120455d854b3cb04eb4fb7c183552b','[\"*\"]',NULL,NULL,'2025-10-22 10:21:36','2025-10-22 10:21:36'),(5,'App\\Models\\User',5,'mobile_token','aad2de6e16dbb0e162098e8a925043950298c0260da8ffda47333af6d6fd09e5','[\"*\"]',NULL,NULL,'2025-10-22 10:39:38','2025-10-22 10:39:38'),(6,'App\\Models\\User',5,'mobile_token','b4d6f29ff16638878e3f4e7dd4062f37bc01d5c87fe100fe8c024040ec05dc3b','[\"*\"]',NULL,NULL,'2025-10-22 10:41:11','2025-10-22 10:41:11'),(7,'App\\Models\\User',5,'mobile_token','3936ab19e89206fe2aaa3ee78d4875b3278b5c77bd86f4d7ff473d47e449c0c2','[\"*\"]',NULL,NULL,'2025-10-22 10:41:43','2025-10-22 10:41:43'),(8,'App\\Models\\User',5,'mobile_token','fd170727a6e48fd9b5cdd8e8ca559228149b4ba0860bb4894d5d124fff28f244','[\"*\"]',NULL,NULL,'2025-10-22 10:42:44','2025-10-22 10:42:44'),(9,'App\\Models\\User',5,'mobile_token','181c2e4fc48c5f1c5e5ead32c48fe2890eb0fdcafd73cc5e37873e0d00c90715','[\"*\"]',NULL,NULL,'2025-10-22 19:20:29','2025-10-22 19:20:29'),(10,'App\\Models\\User',5,'mobile_token','209948e96e025f6902bda1844c96b1066009f08f99e436bc946dd89e72e5eee9','[\"*\"]',NULL,NULL,'2025-10-22 19:53:32','2025-10-22 19:53:32'),(11,'App\\Models\\User',1,'mobile_token','token_andi_001','[\"*\"]',NULL,NULL,'2025-10-23 04:05:32','2025-10-23 04:05:32'),(12,'App\\Models\\User',2,'mobile_token','token_budi_002','[\"*\"]',NULL,NULL,'2025-10-23 04:05:32','2025-10-23 04:05:32'),(13,'App\\Models\\User',3,'mobile_token','token_citra_003','[\"*\"]',NULL,NULL,'2025-10-23 04:05:32','2025-10-23 04:05:32'),(14,'App\\Models\\User',6,'mobile_token','token_rudi_004','[\"*\"]',NULL,NULL,'2025-10-23 04:05:32','2025-10-23 04:05:32'),(15,'App\\Models\\User',7,'mobile_token','token_slamet_005','[\"*\"]',NULL,NULL,'2025-10-23 04:05:32','2025-10-23 04:05:32'),(16,'App\\Models\\User',8,'mobile_token','token_tono_006','[\"*\"]',NULL,NULL,'2025-10-23 04:05:32','2025-10-23 04:05:32'),(17,'App\\Models\\User',9,'mobile_token','token_agus_007','[\"*\"]',NULL,NULL,'2025-10-23 04:05:32','2025-10-23 04:05:32'),(18,'App\\Models\\User',1,'mobile_token','a9519e5cf5806e2e824206373afee7433457b3b955db57d951b3b0bf8ebda709','[\"*\"]','2025-10-22 21:49:50',NULL,'2025-10-22 21:20:54','2025-10-22 21:49:50'),(19,'App\\Models\\User',10,'mobile_token','d2db370eba6e92b06c0ea0010e7060547c07254e6dba476eb285bb90740bc6ad','[\"*\"]',NULL,NULL,'2025-10-23 01:53:20','2025-10-23 01:53:20'),(20,'App\\Models\\User',11,'mobile_token','6f7a6d01f2c92b03d6f89e51ffa35bc70f627a5f4cdf54251d38621d8d4a663d','[\"*\"]',NULL,NULL,'2025-10-23 01:53:51','2025-10-23 01:53:51'),(21,'App\\Models\\User',11,'mobile_token','aff853155e5ff436d4ec46c73377dfdcee4a8cd9e48ac842bbe917a457856907','[\"*\"]',NULL,NULL,'2025-10-23 01:54:58','2025-10-23 01:54:58'),(22,'App\\Models\\User',11,'mobile_token','0fd636fd68672f9c8f99662ebda8a26de2b9e111bf7e2973fe192813a39fd8b2','[\"*\"]',NULL,NULL,'2025-10-23 02:09:44','2025-10-23 02:09:44'),(23,'App\\Models\\User',11,'mobile_token','15dbcf5db585aa1d84b33ec350b420e413318bb4d40ced6966719edfaea98142','[\"*\"]',NULL,NULL,'2025-10-23 02:15:41','2025-10-23 02:15:41'),(24,'App\\Models\\User',11,'mobile_token','b88e8c882e7fa6fc755acc933aa4328f2eae002bd2569d58f87ba1d47da2b159','[\"*\"]',NULL,NULL,'2025-10-23 02:28:10','2025-10-23 02:28:10'),(25,'App\\Models\\User',11,'mobile_token','4311c78a5a5e0b02c9680d20c98bc116c4dab23ae3429aa574f5e811dff06555','[\"*\"]','2025-10-23 02:43:42',NULL,'2025-10-23 02:32:12','2025-10-23 02:43:42'),(26,'App\\Models\\User',11,'mobile_token','1d60afeacf070f50f2748a4a0b93a3a545aa1392cc2cbbab555aca8e7a9c7667','[\"*\"]',NULL,NULL,'2025-10-23 03:09:04','2025-10-23 03:09:04'),(27,'App\\Models\\User',11,'mobile_token','9375135e36420d8c2c9149b619d0b2edffc2d104fa49c5561b9ba4e47b6f556b','[\"*\"]','2025-10-23 04:28:54',NULL,'2025-10-23 03:20:35','2025-10-23 04:28:54'),(28,'App\\Models\\User',11,'mobile_token','fdbe0b678fdee512474c9f739a4f4162cf6b8cfef300ee311d4c9b9b53385646','[\"*\"]','2025-10-23 19:08:55',NULL,'2025-10-23 19:07:42','2025-10-23 19:08:55'),(29,'App\\Models\\User',11,'mobile_token','bbf7efd933e23ab780fffbc0936e03934136b4b6cc173ef678ff450dc99814f0','[\"*\"]','2025-10-23 23:30:55',NULL,'2025-10-23 23:03:09','2025-10-23 23:30:55'),(30,'App\\Models\\User',11,'mobile_token','9deb7063aff81a479939eed559823a61e495eee3610b75357a3c74e47521a9c4','[\"*\"]',NULL,NULL,'2025-10-26 09:29:18','2025-10-26 09:29:18'),(31,'App\\Models\\User',11,'mobile_token','c46afb2da627d1852480c9bbcaa2a356cf185786d40fd3310c1b49448c6a3c19','[\"*\"]','2025-10-26 22:41:47',NULL,'2025-10-26 22:28:14','2025-10-26 22:41:47'),(32,'App\\Models\\User',11,'mobile_token','0a4560a976b78846b0aeeb02fb3abd4e149990226a8c9932d6c95073395aa543','[\"*\"]',NULL,NULL,'2025-10-26 23:23:18','2025-10-26 23:23:18'),(33,'App\\Models\\User',11,'mobile_token','8a0f140395f7302c7bfbdafb88b829cc580a5932d7b74bb965e639b6eb065734','[\"*\"]',NULL,NULL,'2025-10-27 00:48:12','2025-10-27 00:48:12'),(34,'App\\Models\\User',11,'mobile_token','cf194c178f04f0ea53b72e513c5bee95db64f7e93a61733ead84cfc0c7cc91ce','[\"*\"]',NULL,NULL,'2025-10-27 00:58:36','2025-10-27 00:58:36'),(35,'App\\Models\\User',11,'mobile_token','9c178425d3d83820dc00e3c68016b803520a917e986a8fcde25e0e1172cb791c','[\"*\"]',NULL,NULL,'2025-10-27 01:12:56','2025-10-27 01:12:56'),(36,'App\\Models\\User',11,'mobile_token','0ad256a8d67822f3518a5b7a2e26360166f4ed7aca769b349d191115fd1c46cf','[\"*\"]',NULL,NULL,'2025-10-27 01:21:22','2025-10-27 01:21:22'),(37,'App\\Models\\User',11,'mobile_token','62c3cfb85ad67bf85229b076d0d2c726e7bd6f8fedea2398c5a044982bc5fb63','[\"*\"]','2025-10-27 23:31:16',NULL,'2025-10-27 05:21:25','2025-10-27 23:31:16'),(38,'App\\Models\\User',11,'mobile_token','2330147fd0a4c6e70e52320ba79c1d1b38845dbb34788ef5e6a71ec151c7c05b','[\"*\"]','2025-10-29 22:36:08',NULL,'2025-10-29 21:05:24','2025-10-29 22:36:08'),(39,'App\\Models\\User',12,'mobile_token','27c3b6134a42ab2082eeb1b8adfb3f8917c218702223c2de4b58cd31c4ef38bb','[\"*\"]',NULL,NULL,'2025-10-29 23:25:05','2025-10-29 23:25:05'),(40,'App\\Models\\User',12,'mobile_token','05948c833a2b6d7ece6be0516be1f879eead10d4348849ff1324af822556a0e3','[\"*\"]','2025-10-29 23:34:09',NULL,'2025-10-29 23:32:22','2025-10-29 23:34:09'),(41,'App\\Models\\User',12,'mobile_token','56aa07d458438fc189a9d7d3f74304d11010232718922ed0767ceb0a8e0614fc','[\"*\"]','2025-10-30 00:21:50',NULL,'2025-10-29 23:34:29','2025-10-30 00:21:50'),(42,'App\\Models\\User',12,'mobile_token','296e2dc2152dbc1eb80b0d7ee488f5d638ae5e62482dfea66b58b83a99052962','[\"*\"]','2025-10-30 00:22:49',NULL,'2025-10-30 00:22:12','2025-10-30 00:22:49'),(43,'App\\Models\\User',12,'mobile_token','e6b81ecd08960b164a51b31fb9ef904e1b955da39f5ec8bd60930693db329077','[\"*\"]','2025-10-30 00:30:50',NULL,'2025-10-30 00:30:25','2025-10-30 00:30:50'),(44,'App\\Models\\User',11,'mobile_token','1f305e503dc812847171256fdcb9d7f8e2746325e7a9d8bd30873b9725bf8752','[\"*\"]','2025-10-30 03:23:08',NULL,'2025-10-30 00:46:42','2025-10-30 03:23:08'),(45,'App\\Models\\User',12,'mobile_token','cc89b50e29ee002753d4cf951baf597d74145b412cfa67612543de9bd9bbce11','[\"*\"]',NULL,NULL,'2025-10-30 03:23:23','2025-10-30 03:23:23'),(46,'App\\Models\\User',12,'mobile_token','be0b8247b6528eb7d6abe07459cd65fb6ca647a655d7acdfbae36c7c223af4b8','[\"*\"]',NULL,NULL,'2025-10-30 03:56:54','2025-10-30 03:56:54'),(47,'App\\Models\\User',11,'mobile_token','7867650ef4485a1a5010b8a9127d7e04c6fafd01cd1af304e2cc18d158f3d9de','[\"*\"]','2025-10-30 08:37:24',NULL,'2025-10-30 08:37:07','2025-10-30 08:37:24'),(48,'App\\Models\\User',13,'mobile_token','ff1ae65a8be04cf55845c52516014084473fe8462ab4e5d4afef8fa79cd7ac77','[\"*\"]',NULL,NULL,'2025-10-30 08:46:31','2025-10-30 08:46:31'),(49,'App\\Models\\User',14,'mobile_token','707095aea40673180d2cceafd68eea6939da6a8ee4699fe44bc226c505bdf8ff','[\"*\"]',NULL,NULL,'2025-10-30 09:05:23','2025-10-30 09:05:23'),(50,'App\\Models\\User',14,'mobile_token','8eb7bfc2d476d6f2da7d12db94ed4af88eea0d932632e3e1a97a945bc91df873','[\"*\"]','2025-10-30 09:06:14',NULL,'2025-10-30 09:05:40','2025-10-30 09:06:14'),(51,'App\\Models\\User',11,'mobile_token','1c5e8bb9f48818ed847662ce5cf0940e885b73b3c9b4ad21c4a07513884b1f9a','[\"*\"]','2025-10-30 09:27:22',NULL,'2025-10-30 09:06:29','2025-10-30 09:27:22'),(52,'App\\Models\\User',12,'mobile_token','a445ad178ca2d1344588a20bb4f37386c1c7ce65f01f6964605df0ac1758bdc8','[\"*\"]',NULL,NULL,'2025-10-30 09:27:38','2025-10-30 09:27:38'),(53,'App\\Models\\User',12,'mobile_token','c3523a95c0ea775f255cd0f8cb9f9c91de78aa36e05247851ce342ab6263647a','[\"*\"]',NULL,NULL,'2025-10-30 10:35:46','2025-10-30 10:35:46'),(54,'App\\Models\\User',11,'mobile_token','d04cc794a64b4e7078e655b804c993c2ae9ce80e8bd54ea99007ee8babb1e9a1','[\"*\"]',NULL,NULL,'2025-10-30 11:06:20','2025-10-30 11:06:20'),(55,'App\\Models\\User',14,'mobile_token','e4430ae282f1f77d4ba64bc0426562df0bcd026096bd90fffafdb0d33fd849e9','[\"*\"]','2025-10-30 19:09:10',NULL,'2025-10-30 19:02:36','2025-10-30 19:09:10'),(56,'App\\Models\\User',12,'mobile_token','63c79145d5c016a39a3bd61908fd68a9246c1dab67d5e7118d3802f69add4832','[\"*\"]',NULL,NULL,'2025-10-30 19:10:08','2025-10-30 19:10:08');
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teknisi`
--

DROP TABLE IF EXISTS `teknisi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `teknisi` (
  `id_teknisi` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_user` bigint unsigned NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `rating_avg` double NOT NULL DEFAULT '0',
  `pengalaman` int NOT NULL DEFAULT '0',
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_teknisi`),
  KEY `teknisi_id_user_foreign` (`id_user`),
  CONSTRAINT `teknisi_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teknisi`
--

LOCK TABLES `teknisi` WRITE;
/*!40000 ALTER TABLE `teknisi` DISABLE KEYS */;
INSERT INTO `teknisi` VALUES (1,2,'Spesialis perbaikan AC dan kulkas.',4.8,5,'aktif','2025-10-23 04:05:32','2025-10-23 04:05:32'),(2,6,'Ahli servis motor dan kelistrikan kendaraan.',4.6,4,'aktif','2025-10-23 04:05:32','2025-10-23 04:05:32'),(3,7,'Spesialis renovasi rumah dan pengecatan.',4.7,6,'aktif','2025-10-23 04:05:32','2025-10-23 04:05:32'),(4,8,'Teknisi AC berpengalaman lebih dari 8 tahun.',4.9,8,'aktif','2025-10-23 04:05:32','2025-10-23 04:05:32'),(5,9,'Ahli servis kulkas dan mesin cuci.',4.5,5,'aktif','2025-10-23 04:05:32','2025-10-23 04:05:32'),(6,12,NULL,0,0,'aktif',NULL,NULL),(7,13,NULL,0,0,'aktif',NULL,NULL);
/*!40000 ALTER TABLE `teknisi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ulasan`
--

DROP TABLE IF EXISTS `ulasan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ulasan` (
  `id_ulasan` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_pemesanan` bigint unsigned NOT NULL,
  `id_pelanggan` bigint unsigned NOT NULL,
  `id_teknisi` bigint unsigned NOT NULL,
  `rating` tinyint NOT NULL DEFAULT '5',
  `komentar` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_ulasan`),
  KEY `ulasan_id_pemesanan_foreign` (`id_pemesanan`),
  KEY `ulasan_id_pelanggan_foreign` (`id_pelanggan`),
  KEY `ulasan_id_teknisi_foreign` (`id_teknisi`),
  CONSTRAINT `ulasan_id_pelanggan_foreign` FOREIGN KEY (`id_pelanggan`) REFERENCES `user` (`id_user`) ON DELETE CASCADE,
  CONSTRAINT `ulasan_id_pemesanan_foreign` FOREIGN KEY (`id_pemesanan`) REFERENCES `pemesanan` (`id_pemesanan`) ON DELETE CASCADE,
  CONSTRAINT `ulasan_id_teknisi_foreign` FOREIGN KEY (`id_teknisi`) REFERENCES `teknisi` (`id_teknisi`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ulasan`
--

LOCK TABLES `ulasan` WRITE;
/*!40000 ALTER TABLE `ulasan` DISABLE KEYS */;
/*!40000 ALTER TABLE `ulasan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id_user` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pelanggan',
  `no_hp` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto_profile` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `user_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'Andi Setiawan','adi@example.com','$2y$12$tJjeX/xyHG5fId1uP9RWO.LTfPs35OOqzTOfxR/7Jr5btYWFL5p5i','pelanggan','081234567890',NULL,1,NULL,NULL,'2025-10-23 04:05:32','2025-10-22 21:11:54'),(2,'Budi Santoso','bui@example.com','$2y$12$tJjeX/xyHG5fId1uP9RWO.LTfPs35OOqzTOfxR/7Jr5btYWFL5p5i','teknisi','081298765432','foto_teknisi/foto_teknisi1.jpeg',1,NULL,NULL,'2025-10-23 04:05:32','2025-10-22 21:11:54'),(3,'Citra Dewi','citra@example.com','$2y$12$tJjeX/xyHG5fId1uP9RWO.LTfPs35OOqzTOfxR/7Jr5btYWFL5p5i','pelanggan','081355577788',NULL,1,NULL,NULL,'2025-10-23 04:05:32','2025-10-22 21:11:55'),(4,'Dedi Firmansyah','dedi@example.com','$2y$12$tJjeX/xyHG5fId1uP9RWO.LTfPs35OOqzTOfxR/7Jr5btYWFL5p5i','pelanggan','081233344455',NULL,1,NULL,NULL,'2025-10-23 04:05:32','2025-10-22 21:11:55'),(5,'Eka Pratama','eka@example.com','$2y$12$tJjeX/xyHG5fId1uP9RWO.LTfPs35OOqzTOfxR/7Jr5btYWFL5p5i','pelanggan','081299988877',NULL,1,NULL,NULL,'2025-10-23 04:05:32','2025-10-22 21:11:55'),(6,'Rudi Hartono','rudi@qfx.com','$2y$12$tJjeX/xyHG5fId1uP9RWO.LTfPs35OOqzTOfxR/7Jr5btYWFL5p5i','teknisi','081234500001','foto_teknisi/foto_teknisi2.jpeg',1,NULL,NULL,'2025-10-23 04:05:32','2025-10-22 21:11:55'),(7,'Slamet Riyadi','slamet@qfx.com','$2y$12$tJjeX/xyHG5fId1uP9RWO.LTfPs35OOqzTOfxR/7Jr5btYWFL5p5i','teknisi','081234500002','foto_teknisi/foto_teknisi3.jpeg',1,NULL,NULL,'2025-10-23 04:05:32','2025-10-22 21:11:55'),(8,'Tono Wijaya','tono@qfx.com','$2y$12$tJjeX/xyHG5fId1uP9RWO.LTfPs35OOqzTOfxR/7Jr5btYWFL5p5i','teknisi','081234500003','foto_teknisi/foto_teknisi4.jpeg',1,NULL,NULL,'2025-10-23 04:05:32','2025-10-22 21:11:55'),(9,'Agus Prabowo','agus@qfx.com','$2y$12$tJjeX/xyHG5fId1uP9RWO.LTfPs35OOqzTOfxR/7Jr5btYWFL5p5i','teknisi','081234500004','foto_teknisi/foto_teknisi5.jpeg',1,NULL,NULL,'2025-10-23 04:05:32','2025-10-22 21:11:56'),(10,'Buda Santoso','buda@example.com','$2y$12$CPR1YvUinM0kqw.Wb90eCucDnJYMh4q3HPXLkJL6QiE0bygDschgK','pelanggan','08123456789',NULL,1,NULL,NULL,'2025-10-23 01:53:20','2025-10-23 01:53:20'),(11,'MSA','msa@example.com','$2y$12$/YdwMNn4Fa9Iz3C.dwkEcu/9lZLePg0WV1rCMnsu6WYEvrCcl6MIO','pelanggan','08123456089','storage/foto_profile/RT8DGdB5pLOWUHCJ3g1RwKmrMfxwslli2Y1AMmBB.jpg',1,NULL,NULL,'2025-10-23 01:53:51','2025-10-23 01:53:51'),(12,'Tkn','tkn@example.com','$2y$12$0tiFkRDeYANOzJVVzn45Y.O7oiXkeQlqB9W8mVz3Ao7T2.Co1ckcy','teknisi','08123456189',NULL,1,NULL,NULL,'2025-10-29 23:25:04','2025-10-29 23:25:04'),(13,'Test User','test@gmail.com','$2y$12$GT62LOQGLlGA4gfnkPLhR.rCdE2OTzQwm3jauLsylVD1eCA.89x6u','teknisi','08123456789',NULL,1,NULL,NULL,'2025-10-30 08:46:31','2025-10-30 08:46:31'),(14,'nhp','hanip@gmail.com','$2y$12$.xfKYlUjfe/qr8MoLBJTieqjkQrWURbu9fBeSDSObrfTu7b6uuybW','pelanggan',NULL,NULL,1,NULL,NULL,'2025-10-30 09:05:23','2025-10-30 09:05:23');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-10-31 10:04:24
