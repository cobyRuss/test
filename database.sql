-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: happystem_db
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `happystem_db`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `happystem_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `happystem_db`;

--
-- Table structure for table `admin_users`
--

DROP TABLE IF EXISTS `admin_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_users`
--

LOCK TABLES `admin_users` WRITE;
/*!40000 ALTER TABLE `admin_users` DISABLE KEYS */;
INSERT INTO `admin_users` VALUES (5,'admin','$2y$12$.9lwvrxikEge49Q36LWA8exFd2vmp6wjgXKTzReMWbpMJgLr96xee','2026-04-05 15:20:25');
/*!40000 ALTER TABLE `admin_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
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
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
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
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart`
--

LOCK TABLES `cart` WRITE;
/*!40000 ALTER TABLE `cart` DISABLE KEYS */;
INSERT INTO `cart` VALUES (14,3,2,1,'2026-04-12 12:00:05'),(31,2,1,1,'2026-08-03 15:59:11');
/*!40000 ALTER TABLE `cart` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_messages`
--

DROP TABLE IF EXISTS `contact_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_messages`
--

LOCK TABLES `contact_messages` WRITE;
/*!40000 ALTER TABLE `contact_messages` DISABLE KEYS */;
INSERT INTO `contact_messages` VALUES (1,'Russel Marzo','coby.2339@gmail.com','n','2026-04-05 16:00:58'),(2,'Russel Marzo','caisduas@gmail.com','aaaaaaaaaaaaaaaaaaaaaaa','2026-05-02 21:22:32'),(3,'Alice','alice@test.com','Hello HappyStem','2026-08-07 14:24:11');
/*!40000 ALTER TABLE `contact_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `municipality` varchar(100) DEFAULT 'Bangued',
  `address` text NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL,
  `reset_code` varchar(10) DEFAULT NULL,
  `reset_code_expires` datetime DEFAULT NULL,
  `reset_code_attempts` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES (1,'coby','caisduas@gmail.com','09944325125','Bangued','zone 4 Bangued, Baba redcross','$2y$12$xw5mHeDiHC2ddu2XanaBteycI6zPGfVa6fgPcQ.C973rCZ0/ytfwq','2026-04-04 20:04:55',NULL,NULL,NULL,NULL,0),(2,'Alder Mangaliman','derrr21@gmail.com','09357591816','Bangued','Zone 7 Bangued, Abra','$2y$12$xw5mHeDiHC2ddu2XanaBteycI6zPGfVa6fgPcQ.C973rCZ0/ytfwq','2026-04-05 06:02:31',NULL,NULL,NULL,NULL,0),(3,'Russel','coby.2339@gmail.com','09934568655','Bangued','Zone 1','$2y$12$xw5mHeDiHC2ddu2XanaBteycI6zPGfVa6fgPcQ.C973rCZ0/ytfwq','2026-04-12 07:57:48',NULL,NULL,'621863','2026-04-12 10:14:59',0),(4,'Coby Barba Labuguen','coby.233@gmail.com','09357591816','Bangued','Zone 4, Arellano St., Bangued, Abra','$2y$12$xw5mHeDiHC2ddu2XanaBteycI6zPGfVa6fgPcQ.C973rCZ0/ytfwq','2026-05-06 10:29:36',NULL,NULL,NULL,NULL,0),(5,'Test User','test@example.com','09171234567','Bangued','Zone 1, Bangued, Abra','$2y$12$xw5mHeDiHC2ddu2XanaBteycI6zPGfVa6fgPcQ.C973rCZ0/ytfwq','2026-08-07 14:18:03',NULL,NULL,NULL,NULL,0),(6,'Gcash Tester','gcash_test_18194@example.com','09171234567','Bangued','Zone 1, Bangued, Abra','$2y$12$xw5mHeDiHC2ddu2XanaBteycI6zPGfVa6fgPcQ.C973rCZ0/ytfwq','2026-08-07 14:22:19',NULL,NULL,NULL,NULL,0);
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customization_options`
--

DROP TABLE IF EXISTS `customization_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customization_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` enum('flower','color','style','addon') NOT NULL,
  `name` varchar(100) NOT NULL,
  `display_name` varchar(100) NOT NULL,
  `price` decimal(10,2) DEFAULT 0.00,
  `image_url` varchar(500) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `hex_color` varchar(20) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customization_options`
--

LOCK TABLES `customization_options` WRITE;
/*!40000 ALTER TABLE `customization_options` DISABLE KEYS */;
INSERT INTO `customization_options` VALUES (1,'flower','rose','Roses',120.00,'rs.jpg','roses',NULL,1,1,'2026-04-12 07:19:44'),(2,'flower','sunflower','Sunflowers',200.00,'sf.jpg','sunflowers',NULL,1,2,'2026-04-12 07:19:44'),(3,'flower','tulip','Tulips',150.00,'t1.jpg','tulips',NULL,1,3,'2026-04-12 07:19:44'),(4,'flower','lily','Lilies',180.00,'mg.jpg','arrangements',NULL,1,4,'2026-04-12 07:19:44'),(5,'flower','orchid','Orchids',250.00,'tp.jpg','arrangements',NULL,1,5,'2026-04-12 07:19:44'),(6,'flower','carnation','Carnations',80.00,'sd.jpg','arrangements',NULL,1,6,'2026-04-12 07:19:44'),(7,'color','red','Red',0.00,NULL,NULL,'#e74c3c',1,1,'2026-04-12 07:19:44'),(8,'color','pink','Pink',0.00,NULL,NULL,'#e8b4bc',1,2,'2026-04-12 07:19:44'),(9,'color','white','White',0.00,NULL,NULL,'#f9f3f4',1,3,'2026-04-12 07:19:44'),(10,'color','yellow','Yellow',0.00,NULL,NULL,'#f1c40f',1,4,'2026-04-12 07:19:44'),(11,'color','purple','Purple',0.00,NULL,NULL,'#9b59b6',1,5,'2026-04-12 07:19:44'),(12,'color','mixed','Mixed Colors',50.00,NULL,NULL,NULL,1,6,'2026-04-12 07:19:44'),(13,'style','bouquet','Hand-Tied Bouquet',300.00,'a1.jpg',NULL,NULL,1,1,'2026-04-12 07:19:44'),(14,'style','vase','Vase Arrangement',500.00,'a3.jpg',NULL,NULL,1,2,'2026-04-12 07:19:44'),(15,'style','box','Flower Box',400.00,'1.jpg',NULL,NULL,1,3,'2026-04-12 07:19:44'),(16,'style','basket','Basket Arrangement',450.00,'a2.jpg',NULL,NULL,1,4,'2026-04-12 07:19:44'),(17,'addon','chocolate','Chocolate Box',450.00,NULL,NULL,NULL,1,1,'2026-04-12 07:19:44'),(18,'addon','teddy_bear','Teddy Bear',300.00,NULL,NULL,NULL,1,2,'2026-04-12 07:19:44'),(19,'addon','greeting_card','Greeting Card',50.00,NULL,NULL,NULL,1,3,'2026-04-12 07:19:44'),(20,'addon','balloon','Balloon',150.00,NULL,NULL,NULL,1,4,'2026-04-12 07:19:44'),(21,'addon','vase_upgrade','Premium Vase',200.00,NULL,NULL,NULL,1,5,'2026-04-12 07:19:44');
/*!40000 ALTER TABLE `customization_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customization_presets`
--

DROP TABLE IF EXISTS `customization_presets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customization_presets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `base_price` decimal(10,2) NOT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customization_presets`
--

LOCK TABLES `customization_presets` WRITE;
/*!40000 ALTER TABLE `customization_presets` DISABLE KEYS */;
INSERT INTO `customization_presets` VALUES (1,'Romantic Red','Classic romantic arrangement with red roses',2200.00,'rs.jpg',1,'2026-04-12 07:19:44'),(2,'Sunny Delight','Bright and cheerful sunflower arrangement',2500.00,'sf.jpg',1,'2026-04-12 07:19:44'),(3,'Elegant White','Sophisticated white lily arrangement',2800.00,'wr.jpg',1,'2026-04-12 07:19:44'),(4,'Mixed Garden','Beautiful mix of seasonal flowers',3000.00,'mg.jpg',1,'2026-04-12 07:19:44');
/*!40000 ALTER TABLE `customization_presets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
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
-- Table structure for table `gcash_payments`
--

DROP TABLE IF EXISTS `gcash_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gcash_payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `reference_number` varchar(100) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_type` enum('down_payment','full_payment') NOT NULL,
  `screenshot_path` varchar(500) DEFAULT NULL,
  `verified` tinyint(1) DEFAULT 0,
  `verified_by` int(11) DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `verified_by` (`verified_by`),
  CONSTRAINT `gcash_payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `gcash_payments_ibfk_2` FOREIGN KEY (`verified_by`) REFERENCES `admin_users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gcash_payments`
--

LOCK TABLES `gcash_payments` WRITE;
/*!40000 ALTER TABLE `gcash_payments` DISABLE KEYS */;
INSERT INTO `gcash_payments` VALUES (1,3,'1112222222222345',2425.00,'down_payment',NULL,1,5,'2026-05-02 21:41:26','2026-05-02 21:21:34'),(2,4,'11111111111123212',900.00,'down_payment',NULL,1,5,'2026-05-06 14:18:00','2026-05-02 22:00:13'),(3,7,'123123123123',775.00,'down_payment',NULL,1,5,'2026-05-06 14:17:58','2026-05-06 10:31:09'),(4,8,'12315123123',850.00,'down_payment',NULL,1,5,'2026-05-06 14:17:57','2026-05-06 14:16:03'),(5,9,'6546',1300.00,'down_payment',NULL,1,5,'2026-05-06 14:17:56','2026-05-06 14:16:32'),(6,10,'4567546',775.00,'down_payment',NULL,1,5,'2026-05-06 14:17:53','2026-05-06 14:17:31'),(7,10,'4567546',775.00,'down_payment',NULL,1,5,'2026-08-03 15:58:07','2026-05-06 14:18:28'),(8,13,'GCASH-REF-777125',1200.00,'down_payment',NULL,0,NULL,NULL,'2026-08-07 14:23:09'),(9,13,'GCASH-REF-777128',1200.00,'down_payment','uploads/gcash/gcash_13_1786112607.txt',1,5,'2026-08-07 06:23:52','2026-08-07 14:23:27');
/*!40000 ALTER TABLE `gcash_payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
  `finished_at` int(11) DEFAULT NULL,
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
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
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
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_01_01_000001_create_admin_users_table',1),(5,'2026_01_01_000002_create_customers_table',1),(6,'2026_01_01_000003_create_product_categories_table',1),(7,'2026_01_01_000004_create_products_table',1),(8,'2026_01_01_000005_create_cart_table',1),(9,'2026_01_01_000006_create_orders_table',1),(10,'2026_01_01_000007_create_order_items_table',1),(11,'2026_01_01_000008_create_gcash_payments_table',1),(12,'2026_01_01_000009_create_contact_messages_table',1),(13,'2026_01_01_000010_create_customization_options_table',1),(14,'2026_01_01_000011_create_customization_presets_table',1),(15,'2026_01_01_000012_create_preset_items_table',1),(16,'2026_01_01_000013_create_saved_customizations_table',1),(17,'2026_01_01_000014_create_service_photos_table',1),(18,'2026_01_01_000015_add_hex_color_to_customization_options_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (1,1,1,'Red Romance Bouquet',2300.00,1),(2,1,3,'Pastel Tulip Elegance',1450.00,1),(3,1,4,'Mixed Garden Bouquet',1550.00,1),(4,2,6,'Sunshine Daisies',1800.00,1),(5,3,1,'Red Romance Bouquet',2300.00,1),(6,3,5,'Tropical Paradise',2550.00,1),(7,4,6,'Sunshine Daisies',1800.00,1),(8,5,1,'Red Romance Bouquet',2300.00,1),(9,6,3,'Pastel Tulip Elegance',1450.00,1),(10,7,3,'Pastel Tulip Elegance',1450.00,1),(11,8,22,'Spring Blossoms',1100.00,1),(12,9,21,'White Purity',2500.00,1),(13,10,3,'Pastel Tulip Elegance',1450.00,1),(14,11,1,'Red Romance Bouquet',2300.00,1),(15,12,1,'Red Romance Bouquet',2300.00,1),(16,13,1,'Red Romance Bouquet',2300.00,1);
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_number` varchar(20) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `delivery_fee` decimal(10,2) DEFAULT 0.00,
  `down_payment` decimal(10,2) DEFAULT NULL,
  `remaining_balance` decimal(10,2) DEFAULT NULL,
  `payment_method` enum('gcash','cod') NOT NULL,
  `payment_status` enum('pending_downpayment','partial','completed','pending_cod') DEFAULT 'pending_downpayment',
  `order_status` enum('pending','confirmed','preparing','ready','delivered','cancelled') DEFAULT 'pending',
  `delivery_address` text NOT NULL,
  `municipality` varchar(100) DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `special_instructions` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `cancel_reason` varchar(255) DEFAULT NULL,
  `cancel_note` text DEFAULT NULL,
  `cancelled_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_number` (`order_number`),
  KEY `customer_id` (`customer_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,'ORD-20260405-5702',2,5300.00,0.00,NULL,NULL,'cod','completed','delivered','Zone 7 Bangued, Abra',NULL,'2026-12-12','fadarfasdfasd','2026-04-05 06:03:51','2026-05-02 21:58:38',NULL,NULL,NULL),(2,'ORD-20260405-1384',2,1800.00,0.00,900.00,900.00,'gcash','pending_downpayment','cancelled','Zone 7 Bangued, Abra',NULL,'2026-12-12','','2026-04-05 16:14:16','2026-05-02 21:41:01',NULL,NULL,NULL),(3,'ORD-20260502-7968',1,4850.00,0.00,2425.00,2425.00,'gcash','completed','delivered','zone 4 Bangued, Baba redcross',NULL,'2026-12-23','aaaaaaaa','2026-05-02 21:21:15','2026-05-02 21:58:36',NULL,NULL,NULL),(4,'ORD-20260502-7569',1,1800.00,0.00,900.00,900.00,'gcash','completed','cancelled','zone 4 Bangued, Baba redcross',NULL,'2026-12-12','','2026-05-02 21:59:37','2026-05-06 14:21:52','Change of delivery address','','2026-05-06 17:48:35'),(5,'ORD-20260506-2461',1,2300.00,0.00,NULL,NULL,'cod','completed','delivered','zone 4 Bangued, Baba redcross',NULL,'2026-12-12','','2026-05-06 09:51:33','2026-05-06 09:52:04',NULL,NULL,NULL),(6,'ORD-20260506-3807',1,1450.00,0.00,NULL,NULL,'cod','completed','cancelled','zone 4 Bangued, Baba redcross',NULL,'2026-12-12','qqqqqqqqq','2026-05-06 09:52:31','2026-05-06 14:21:50','Change of delivery address','','2026-05-06 17:54:35'),(7,'ORD-20260506-6143',4,1550.00,100.00,775.00,775.00,'gcash','completed','cancelled','Zone 4, Arellano St., Bangued, Abra, Bangued, Abra','Bangued','2026-12-12','aaaaaaaaaaaa','2026-05-06 10:30:57','2026-05-06 14:21:48','Change of delivery address','','2026-05-06 18:32:04'),(8,'ORD-20260506-4105',2,1700.00,600.00,850.00,850.00,'gcash','completed','delivered','Bugbog, Abra, Bucay, Abra','Bucay','2026-05-07','qadasdasd','2026-05-06 14:15:53','2026-05-06 14:18:23',NULL,NULL,NULL),(9,'ORD-20260506-3069',2,2600.00,100.00,1300.00,1300.00,'gcash','completed','delivered','Zone 7 Bangued, Abra, Bangued, Abra','Bangued','2026-05-07','qqqq','2026-05-06 14:16:26','2026-05-06 14:18:47',NULL,NULL,NULL),(10,'ORD-20260506-6075',4,1550.00,100.00,775.00,775.00,'gcash','partial','delivered','Zone 4, Arellano St., Bangued, Abra, Bangued, Abra','Bangued','2026-05-07','qqq','2026-05-06 14:17:22','2026-08-03 15:58:07',NULL,NULL,NULL),(11,'ORD-20260803-6307',2,2400.00,100.00,1200.00,1200.00,'gcash','pending_downpayment','pending','Zone 7 Bangued, Abra, Bangued, Abra','Bangued','2026-12-23','werwerwer','2026-08-03 15:55:23','2026-08-03 15:55:23',NULL,NULL,NULL),(12,'ORD-20260807-2512',5,2400.00,100.00,NULL,NULL,'cod','pending_cod','pending','Zone 1 Bangued, Bangued, Abra','Bangued','2026-08-10','Leave at gate','2026-08-07 06:18:11','2026-08-07 06:18:11',NULL,NULL,NULL),(13,'ORD-20260807-5794',6,2400.00,100.00,1200.00,1200.00,'gcash','partial','confirmed','Zone 1 Block 3, Bangued, Abra','Bangued','2026-08-09',NULL,'2026-08-07 06:22:37','2026-08-07 14:23:59',NULL,NULL,NULL);
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `preset_items`
--

DROP TABLE IF EXISTS `preset_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `preset_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `preset_id` int(11) NOT NULL,
  `flower_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `preset_id` (`preset_id`),
  KEY `flower_id` (`flower_id`),
  CONSTRAINT `preset_items_ibfk_1` FOREIGN KEY (`preset_id`) REFERENCES `customization_presets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `preset_items_ibfk_2` FOREIGN KEY (`flower_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `preset_items`
--

LOCK TABLES `preset_items` WRITE;
/*!40000 ALTER TABLE `preset_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `preset_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_categories`
--

DROP TABLE IF EXISTS `product_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(100) NOT NULL,
  `display_name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_categories`
--

LOCK TABLES `product_categories` WRITE;
/*!40000 ALTER TABLE `product_categories` DISABLE KEYS */;
INSERT INTO `product_categories` VALUES (1,'roses','Roses','2026-05-02 21:39:59'),(2,'sunflowers','Sunflowers','2026-05-02 21:39:59'),(3,'tulips','Tulips','2026-05-02 21:39:59'),(4,'seasonal','Seasonal','2026-05-02 21:39:59'),(5,'arrangements','Arrangements','2026-05-02 21:39:59'),(6,'wrappers','Wrappers','2026-05-02 21:39:59');
/*!40000 ALTER TABLE `product_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `category` enum('roses','sunflowers','tulips','seasonal','arrangements','wrappers') NOT NULL,
  `image_url` varchar(500) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'Red Romance Bouquet','A classic arrangement of deep red roses, symbolizing love and passion.',2300.00,'roses','rs.jpg','2026-04-04 19:39:27'),(2,'Sunflower Symphony','A vibrant arrangement of cheerful sunflowers to brighten any room with sunny energy.',10500.00,'sunflowers','sf.jpg','2026-04-04 19:39:27'),(3,'Pastel Tulip Elegance','Soft pastel tulips arranged to create an elegant and sophisticated spring display.',1450.00,'tulips','t1.jpg','2026-04-04 19:39:27'),(4,'Mixed Garden Bouquet','A beautiful assortment of seasonal flowers for a natural garden feel.',1550.00,'seasonal','mg.jpg','2026-04-04 19:39:27'),(5,'Tropical Paradise','Exotic flowers that bring a vibrant, tropical feel to any space.',2550.00,'arrangements','tp.jpg','2026-04-04 19:39:27'),(6,'Sunshine Daisies','Cheerful daisies that spread happiness and brighten your day.',1800.00,'arrangements','sd.jpg','2026-04-04 19:39:27'),(7,'Pink Perfection','Delicate pink roses that express admiration and gratitude.',1800.00,'roses','pr.jpg','2026-04-04 19:39:27'),(8,'White Innocence','Pure white roses symbolizing purity, innocence, and new beginnings.',1550.00,'roses','wr.jpg','2026-04-04 19:39:27'),(9,'Golden Friendship','Bright yellow roses representing friendship, joy, and caring.',3400.00,'roses','yr.jpg','2026-04-04 19:39:27'),(10,'Lavender Enchantment','Mystical lavender roses that convey enchantment and love at first sight.',5200.00,'roses','lr.jpg','2026-04-04 19:39:27'),(11,'Red Gratitude','Red roses expressing appreciation and thankfulness.',4900.00,'roses','ra.jpg','2026-04-04 19:39:27'),(12,'Golden Sunshine','A vibrant mix of sunflowers that brings warmth and happiness to any space.',1400.00,'sunflowers','s2.jpg','2026-04-04 19:39:27'),(13,'Summer Field','An abundant arrangement that captures the essence of a sunny summer field.',900.00,'sunflowers','s3.jpg','2026-04-04 19:39:27'),(14,'Mini Delight','Charming mini sunflowers perfect for adding a cheerful touch to any room.',1300.00,'sunflowers','s4.jpg','2026-04-04 19:39:27'),(15,'Sunny Mix','A delightful combination of sunflowers and daisies for a bright, cheerful display.',2300.00,'sunflowers','s5.jpg','2026-04-04 19:39:27'),(16,'Autumn Picnic','Rich, warm sunflowers in a box.',2500.00,'sunflowers','s6.jpg','2026-04-04 19:39:27'),(17,'Gradient Delight','A vibrant mix of colorful white and pink that brings joy and cheer to any space.',1600.00,'tulips','ct.jpg','2026-04-04 19:39:27'),(18,'Soft Passion','Light pink tulips that symbolize perfect love and passion.',2750.00,'tulips','pt.jpg','2026-04-04 19:39:27'),(19,'Blue Royalty','Regal Blue tulips that represent royalty and elegance.',4200.00,'tulips','bt.jpg','2026-04-04 19:39:27'),(20,'Yellow Sunshine','Cheerful mixed tulips that bring sunshine and happiness wherever they go.',3000.00,'tulips','mt.avif','2026-04-04 19:39:27'),(21,'White Purity','Pure white tulips symbolizing forgiveness, respect, and purity.',2500.00,'tulips','wt.jpg','2026-04-04 19:39:27'),(22,'Spring Blossoms','Fresh spring flowers that capture the renewal and beauty of the season.',1100.00,'seasonal','ss1.jpg','2026-04-04 19:39:27'),(23,'Summer Blooms','Vibrant summer flowers that bring warmth and energy to any space.',1150.00,'seasonal','ss2.jpg','2026-04-04 19:39:27'),(24,'Autumn Harvest','Rich, warm flowers that capture the cozy essence of autumn.',1350.00,'seasonal','ss3.jpg','2026-04-04 19:39:27'),(25,'Winter Wonder','Elegant winter flowers that bring beauty to the coldest season.',1200.00,'seasonal','ss4.jpg','2026-04-04 19:39:27'),(26,'Year-Round Beauty','A timeless arrangement of flowers that brings joy in every season.',2200.00,'seasonal','ss5.jpg','2026-04-04 19:39:27'),(27,'Classic Elegance','A sophisticated arrangement of exotic tropical flowers for timeless beauty.',2600.00,'arrangements','a1.jpg','2026-04-04 19:39:27'),(28,'Contemporary Charm','A modern floral arrangement perfect as a stunning centerpiece.',1300.00,'arrangements','a2.jpg','2026-04-04 19:39:27'),(29,'Lush Garden','An abundant arrangement that brings the beauty of a garden indoors.',1500.00,'arrangements','a3.jpg','2026-04-04 19:39:27'),(30,'Artistic Display','A creatively designed floral arrangement that showcases artistic flair.',2800.00,'arrangements','a4.jpg','2026-04-04 19:39:27'),(31,'Mixed Wrapper','Beautiful mixed wrapper for flowers.',25.00,'wrappers','mixed.webp','2026-04-04 19:39:27'),(32,'Pink & Blue','Lovely pink and blue wrapper.',10.00,'wrappers','pinkblue.webp','2026-04-04 19:39:27'),(33,'Lilac','Elegant lilac colored wrapper.',15.00,'wrappers','lilac.webp','2026-04-04 19:39:27'),(34,'Beautiful Wrapper 4','Light brown natural wrapper.',18.00,'wrappers','lightbrown.webp','2026-04-04 19:39:27'),(35,'Beautiful Wrapper 5','Dark red sophisticated wrapper.',14.00,'wrappers','darkred.webp','2026-04-04 19:39:27'),(36,'Beautiful Wrapper 6','Green and blue artistic wrapper.',20.00,'wrappers','greenblue.webp','2026-04-04 19:39:27');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `saved_customizations`
--

DROP TABLE IF EXISTS `saved_customizations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `saved_customizations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `design_name` varchar(100) DEFAULT NULL,
  `design_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`design_data`)),
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`),
  CONSTRAINT `saved_customizations_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `saved_customizations`
--

LOCK TABLES `saved_customizations` WRITE;
/*!40000 ALTER TABLE `saved_customizations` DISABLE KEYS */;
/*!40000 ALTER TABLE `saved_customizations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `service_photos`
--

DROP TABLE IF EXISTS `service_photos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `service_photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` enum('weddings','events','corporate','sympathy','romance','getwell') NOT NULL,
  `image_url` varchar(500) NOT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service_photos`
--

LOCK TABLES `service_photos` WRITE;
/*!40000 ALTER TABLE `service_photos` DISABLE KEYS */;
INSERT INTO `service_photos` VALUES (1,'weddings','w1.jpg','Bridal Bouquet','2026-04-04 19:39:27'),(2,'weddings','w2.jpg','Church Flowers','2026-04-04 19:39:27'),(3,'weddings','w4.jpg','Reception Centerpieces','2026-04-04 19:39:27'),(4,'weddings','w3.jpg','Bridesmaid Bouquets','2026-04-04 19:39:27'),(5,'events','p1.jpg','U.A Pageant 2025','2026-04-04 19:39:27'),(6,'events','p2.jpg','Miss Abra Pageant','2026-04-04 19:39:27'),(7,'events','p3.jpg','Mr & Miss Bucay','2026-04-04 19:39:27'),(8,'events','lr1.jpg','Anniversary Surprise','2026-04-04 19:39:27'),(9,'corporate','bb1.jpg','BBM Building Grand Opening','2026-04-04 19:39:27'),(10,'corporate','bbm.jpg','BBM Lobby Arrangement','2026-04-04 19:39:27'),(11,'corporate','aa.jpg','Government Events Table Flowers','2026-04-04 19:39:27'),(12,'corporate','aaa.jpg','Corporate Welcome Flowers','2026-04-04 19:39:27'),(13,'sympathy','ccccc.jpg','Funeral Wreath','2026-04-04 19:39:27'),(14,'sympathy','ccc.jpg','Memorial Stand','2026-04-04 19:39:27'),(15,'sympathy','cccc.jpg','Condolence Basket','2026-04-04 19:39:27'),(16,'sympathy','cc.jpg','Memorial Spray','2026-04-04 19:39:27'),(17,'romance','lr1.jpg','Valentine\'s Day Roses','2026-04-04 19:39:27'),(18,'romance','lr2.jpg','Anniversary Arrangement','2026-04-04 19:39:27'),(19,'getwell','sfb.jpg','Get Well Soon Basket','2026-04-04 19:39:27'),(20,'getwell','gw.jpg','Hospital Arrangement','2026-04-04 19:39:27'),(21,'getwell','gw1.jpg','Flowers with Chocolates','2026-04-04 19:39:27');
/*!40000 ALTER TABLE `service_photos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('4eO7rq3vAE06qwSvFpHXv94qo8buOpTwkPpsAyib',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.132.0 Chrome/148.0.7778.280 Electron/42.7.1 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiellqd0xvdjNOS1RwcFpoNmpQN1hUZ0NKWVRQNVIwcTNNTGp0Y09pcCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAzMy9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoxMToiYWRtaW4ubG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1786113203),('7dN5QuyenTRdEx1ifuytSsYTBaRbagvRlh9KTUEg',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiU04zeGpxTUwyamVzYU1jek54eUtVT2U2ZjkyN2NZd2lScFNvOVhvVSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAzMy9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoxMToiYWRtaW4ubG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1786112149),('ABOpbNuR6j2FVHg36lYONqdjvb8ixu3k3x7cbvTi',NULL,'127.0.0.1','curl/8.21.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVGtVczBtdFZZSDdDTEI3N1dMOHVBZTJsVVZOdUlSMHFYM1JFQzFtNCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNzoiaHR0cDovLzEyNy4wLjAuMTo4MDMzL2FkbWluL2Rhc2hib2FyZCI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM3OiJodHRwOi8vMTI3LjAuMC4xOjgwMzMvYWRtaW4vZGFzaGJvYXJkIjtzOjU6InJvdXRlIjtzOjE1OiJhZG1pbi5kYXNoYm9hcmQiO319',1786112265),('C71LxnHElNsjC3SAxN7Y11SsXAuPmFneMJiMV6rK',6,'127.0.0.1','curl/8.21.0','YTo4OntzOjY6Il90b2tlbiI7czo0MDoib2drQVlucFo3bDRFUDlOV2Q2M3lGNUR4VUlqRnNmeUN0T0lMa0NyTiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAzMy9vcmRlcnMvMTMvZ2Nhc2giO3M6NToicm91dGUiO3M6MTI6Im9yZGVycy5nY2FzaCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjY7czoxMzoiY3VzdG9tZXJfbmFtZSI7czoxMjoiR2Nhc2ggVGVzdGVyIjtzOjE0OiJjdXN0b21lcl9lbWFpbCI7czoyODoiZ2Nhc2hfdGVzdF8xODE5NEBleGFtcGxlLmNvbSI7czoxMzoibGFzdF9vcmRlcl9pZCI7aToxMztzOjE3OiJsYXN0X29yZGVyX251bWJlciI7czoxNzoiT1JELTIwMjYwODA3LTU3OTQiO30=',1786112607),('d3rq6finN6xGou0wWWoi1r1wrvCqVhqdrVOPeqp4',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQUpndWlRajFpMUNDOHpVekRNWERxeWttek9PYWNIaW5rR0Y5SHMzWCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAzMy9mb3Jnb3QiO3M6NToicm91dGUiO3M6NjoiZm9yZ290Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1786112149),('eFjLFV5gwdFgoUNiUYElL1MLhobFlceA8SJhQEmA',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTG1LWFNDZk92U1BhWkxNUWVEVmJyWnd2OTR1cDg2SFBiWWl3RFYzVyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAzMyI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1786112652),('es4B1tMbejUqoH8ArqxOlQu1KnLkvNzvh2qUAUlR',NULL,'127.0.0.1','curl/8.21.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiYlJjVU9xVnNuUW92SnhvYUc5bUJsVVFsR1ByTkluVm1DcmJuckphSiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyOToiaHR0cDovLzEyNy4wLjAuMTo4MDMzL2FjY291bnQiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czoyOToiaHR0cDovLzEyNy4wLjAuMTo4MDMzL2FjY291bnQiO3M6NToicm91dGUiO3M6NzoiYWNjb3VudCI7fX0=',1786112267),('EZYJSTGbk5UHzK36ZGvt3ZcF9pwZQif2yZuDvvUZ',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiaTVrY1ZuY1N3YVZVbjBuZXpvREZCdVlzZ3hhc2JHOEZqR28wZjJWaCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAzMy9yZWdpc3RlciI7czo1OiJyb3V0ZSI7czo4OiJyZWdpc3RlciI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1786112275),('hDCTmeju3rrbLMGH2WxVp3VB6CvTcM7XXMUr7CDJ',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoieHBzRGVnME9sbElWTlgyUHhvYTVGN3Z1aGhJRmlzallLaXlDMWM0SiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAzMy9jdXN0b21pemUiO3M6NToicm91dGUiO3M6MTU6ImN1c3RvbWl6ZS5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1786112147),('IdRnqyehHPGQY0mhD1CApqrk7jZT7rOqD7x5HxOP',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiaHQ2QTB6Z24ycW9sNmpDNGNRUmdyWWdEWkQ3dVZSMHcxSGhYak1wQSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAzMy9wcm9kdWN0cyI7czo1OiJyb3V0ZSI7czoxNDoicHJvZHVjdHMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1786112144),('ienJhLU7EtDdCt1s8HIrJZOVpUhfYfkABH1X28rC',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZjR1MUlHUE01V2lMNkNlcnBxWkdFNE5kY1Nla3pLUVV6dU1NMzlaUSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAzMy9jYXJ0IjtzOjU6InJvdXRlIjtzOjEwOiJjYXJ0LmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1786112147),('IRYYqh8xpCylHPkCWpzCPikNcbQQ9oJkJjvRMW4K',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoid0xxM3ZsQTJMaDg3ZjF3Y3dKQVI2ckNzMDRLSmUzcEIycDJZZ1JmMyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAzMy9yZWdpc3RlciI7czo1OiJyb3V0ZSI7czo4OiJyZWdpc3RlciI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1786112266),('jluAzlhMTQnZLvyUKzPLxdzxqxvDcs9WDf5mVmCr',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQUV3ZVlOTFhkZUxRQXZCZWhXbTJSb1hwQWRGRWJJNW9CUzZ6Vk9YZSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAzMyI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1786112144),('KyEuLH8jEm9Ih2H4a1aNV7Eg4YufJbrCGSHMOe7j',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUkVnWmMzb2dWRmYxWEZoNmx6ZW51NFJ5anY0bWVSdkNYRzlqZWNmbiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAzMy9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1786112148),('njwZJZmbEK65t8TXgP8JR0yd5hAJQhVi9UsSYHZt',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoic1oxeTFjT2dMMkpRWHVRSlRRNU90eU5QeFBXMEt5TldEb3hOanBoMCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAzMyI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1786112567),('ns8ocwcZk4TBBqUmqJ9ci3GWHnAfklddAwjpIAvm',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZ1R3Rm1jczBsZlU1RjdJQnBzekJSMjhGRzJsUmNnS1FudHkyWXBQRiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAzMyI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1786112440),('nweBERBBTNqLljtnSamHTY3WGaFAcBzBi3mYKOi5',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiM3ZEMWZvVFp6dkx0TUxSUlFER05rcjBUcnpIV3FNWVdDWk1JY2RteCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAzMyI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1786112578),('Nxg5ZycAqipdrYuNQqqYPi8N3cjo4nHnIKTqA0lN',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZndUUkJ0eDQxY2FURFEwcDN0d3ljV1ZEOXVpZ2hsTmNkUlZrY3JBSiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAzMy9zZXJ2aWNlcyI7czo1OiJyb3V0ZSI7czoxNDoic2VydmljZXMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1786112146),('pvZujL0YS9owmHnA5v3FWshl6q5Ri1yiO353S3kD',NULL,'127.0.0.1','curl/8.21.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoielJJUnh3ZkRTdDB1WGlobmtjRnBNU3M2TVZUY0x6ekVMMmRWM0E0ZyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyOToiaHR0cDovLzEyNy4wLjAuMTo4MDMzL2FjY291bnQiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czoyOToiaHR0cDovLzEyNy4wLjAuMTo4MDMzL2FjY291bnQiO3M6NToicm91dGUiO3M6NzoiYWNjb3VudCI7fX0=',1786112275),('rc6lYdzE6IovS60trWtaswuTw4wgKkgQ0IvCCiNv',5,'127.0.0.1','curl/8.21.0','YTo2OntzOjY6Il90b2tlbiI7czo0MDoiVDl2V0ZPNmhKSkJ4T1BWUHF6cFA4T0hyYTlwdFh2ZnNFNnVtOHNZSSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAzMy9hZG1pbi9kYXNoYm9hcmQ/dGFiPXJlcG9ydHMiO3M6NToicm91dGUiO3M6MTU6ImFkbWluLmRhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTI6ImxvZ2luX2FkbWluXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NTtzOjg6ImFkbWluX2lkIjtpOjU7czoxNDoiYWRtaW5fdXNlcm5hbWUiO3M6NToiYWRtaW4iO30=',1786113757),('SivOAtkAzj67gYFrpAv7YgXJOOcj94PfAFBW9VVU',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','YTo2OntzOjY6Il90b2tlbiI7czo0MDoiR3dVN0tOeW0wd2tGeFdYT090elZIS0R0cklPUUVReHpnNmdIYnVJbiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAzMy9jYXJ0L2NvdW50IjtzOjU6InJvdXRlIjtzOjEwOiJjYXJ0LmNvdW50Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MjoibG9naW5fYWRtaW5fNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo1O3M6ODoiYWRtaW5faWQiO2k6NTtzOjE0OiJhZG1pbl91c2VybmFtZSI7czo1OiJhZG1pbiI7fQ==',1786114984),('uZtODS8W5FClcJstprM9vsTXyW6mVhSLTruLp4zB',5,'127.0.0.1','curl/8.21.0','YTo4OntzOjY6Il90b2tlbiI7czo0MDoiTjBHdnBhSGVFbFJ6cG04N2RHU0xSZU9zd0JMV00xVkE4eWVlQ0x6MCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAzMy9vcmRlcnMvMTIvY2FuY2VsIjtzOjU6InJvdXRlIjtzOjEzOiJvcmRlcnMuY2FuY2VsIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NTtzOjEzOiJjdXN0b21lcl9uYW1lIjtzOjk6IlRlc3QgVXNlciI7czoxNDoiY3VzdG9tZXJfZW1haWwiO3M6MTY6InRlc3RAZXhhbXBsZS5jb20iO3M6MTM6Imxhc3Rfb3JkZXJfaWQiO2k6MTI7czoxNzoibGFzdF9vcmRlcl9udW1iZXIiO3M6MTc6Ik9SRC0yMDI2MDgwNy0yNTEyIjt9',1786112300),('w5LyxPe5EsIQyUorRySqDf2NDhOnduhKOhfIAhNk',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNzFaUzZOVVhkWEhOUHBaR3hvcm5Ed0VPcE1maDhqNjVYOUltTDdCZiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAzMy9jYXJ0L2NvdW50IjtzOjU6InJvdXRlIjtzOjEwOiJjYXJ0LmNvdW50Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1786112150),('wHdntyE6Aqx09KpVcrcutCvdAtVW5hAwHLG1FEcc',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoid0NnemFZbHZ0RWx5VUNrRGFrTUp3dTZCSFUwZ0V0MlZaZXNDR2tCMCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAzMy9yZWdpc3RlciI7czo1OiJyb3V0ZSI7czo4OiJyZWdpc3RlciI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1786112148),('wNRnTDdN80huLm34KeeEY8bxIDsoWpa5lOkSjT37',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUkxkNU5VWVNMVmNIS3hFd0tlMmpXYVNWU1B6SHg5ak1yOUZsZzU3UiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAzMy9zZXJ2aWNlLXBob3Rvcz9zZXJ2aWNlPXdlZGRpbmdzIjtzOjU6InJvdXRlIjtzOjE1OiJzZXJ2aWNlcy5waG90b3MiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1786112149),('WV4e15S4JhSZoJjuayrwkr1mfGAMlhnznr8qz7a7',NULL,'127.0.0.1','curl/8.21.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoid2ZDMlo5NEdldU5xODB0UXlmUjJzeHRkRmw5Y2p4YTh6b0lLQnZwTyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyOToiaHR0cDovLzEyNy4wLjAuMTo4MDMzL2FjY291bnQiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czoyOToiaHR0cDovLzEyNy4wLjAuMTo4MDMzL2FjY291bnQiO3M6NToicm91dGUiO3M6NzoiYWNjb3VudCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1786112150),('Y6YP5YRmjfDUtoZNG89k2AsglqQ6U0OgnoXvmpm9',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoidUlUaHBZZ3JYN2o1THVaaFBMTjc3VEN3MmszQ1V0N2thRGhiWTFjaCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAzMy9wcm9kdWN0cy8xIjtzOjU6InJvdXRlIjtzOjEzOiJwcm9kdWN0cy5zaG93Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1786112145),('YZUMzg7DMuBLGW1nj7Wiz667IdPzsyLnqqgJ3EJj',NULL,'127.0.0.1','curl/8.21.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWkdjOTVHaHVoTUNacmlCMUhVdTM0SktzaGY2MXlIY1JhUUN3Nk44cyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAzMy9hZG1pbi9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6MTU6ImFkbWluLmRhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MzoidXJsIjthOjE6e3M6ODoiaW50ZW5kZWQiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAzMy9hZG1pbi9kYXNoYm9hcmQiO319',1786112313);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-08-07 23:03:43
