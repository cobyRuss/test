-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: 127.0.0.1    Database: happystem_db
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
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart`
--

LOCK TABLES `cart` WRITE;
/*!40000 ALTER TABLE `cart` DISABLE KEYS */;
INSERT INTO `cart` VALUES (31,2,1,1,'2026-08-03 15:59:11'),(35,3,1,1,'2026-08-11 03:53:01'),(36,3,2,2,'2026-08-11 04:09:23'),(37,3,3,2,'2026-08-11 04:09:30');
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
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL,
  `reset_code` varchar(10) DEFAULT NULL,
  `reset_code_expires` datetime DEFAULT NULL,
  `reset_code_attempts` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES (1,'coby','test@happy.test','09944325125','Bangued','zone 4 Bangued, Baba redcross','$2y$12$71tEFsneOCFA.l6ADLO5KenENycfjBzJeqiosE2CT4swGevjpTd96','7ENFrVn41dQKT8XkJYMnmNnpS1BQNUau2mhd1IJ9dNSDGQ9SbaVIV15ONsiZ','2026-04-04 20:04:55',NULL,NULL,NULL,NULL,0),(2,'Alder Mangaliman','derrr21@gmail.com','09357591816','Bangued','Zone 7 Bangued, Abra','$2y$12$RY6JJMpIQrleEOiHAUNn/.zI.BK8DJ38mDnTF.ofMIbyH..MgqkPa',NULL,'2026-04-05 06:02:31',NULL,NULL,NULL,NULL,0),(3,'Russel','coby.2339@gmail.com','09934568655','Bangued','Zone 1','$2y$12$XbOhfYNYV/7q/mluELVqSeRVNoRA070JkFTjzupXKrZXO7qkIkFXi',NULL,'2026-04-12 07:57:48',NULL,NULL,NULL,NULL,0),(4,'Coby Barba Labuguen','coby.233@gmail.com','09357591816','Bangued','Zone 4, Arellano St., Bangued, Abra','$2y$12$RY6JJMpIQrleEOiHAUNn/.zI.BK8DJ38mDnTF.ofMIbyH..MgqkPa',NULL,'2026-05-06 10:29:36',NULL,NULL,NULL,NULL,0),(5,'Test User','test@example.com','09171234567','Bangued','Zone 1, Bangued, Abra','$2y$12$RY6JJMpIQrleEOiHAUNn/.zI.BK8DJ38mDnTF.ofMIbyH..MgqkPa',NULL,'2026-08-07 14:18:03',NULL,NULL,NULL,NULL,0),(6,'Gcash Tester','gcash_test_18194@example.com','09171234567','Bangued','Zone 1, Bangued, Abra','$2y$12$RY6JJMpIQrleEOiHAUNn/.zI.BK8DJ38mDnTF.ofMIbyH..MgqkPa',NULL,'2026-08-07 14:22:19',NULL,NULL,NULL,NULL,0),(7,'Coby Barba Labuguen','test@gmail.com','09934568655','Bangued','Zone 6, 117 Arellano St., Bangued, Abra','$2y$12$RY6JJMpIQrleEOiHAUNn/.zI.BK8DJ38mDnTF.ofMIbyH..MgqkPa',NULL,'2026-08-08 12:53:45',NULL,NULL,NULL,NULL,0);
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customization_option_variants`
--

DROP TABLE IF EXISTS `customization_option_variants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customization_option_variants` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `customization_option_id` bigint(20) unsigned NOT NULL,
  `variant_type` varchar(255) NOT NULL,
  `display_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `hex_color` varchar(255) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customization_option_variants`
--

LOCK TABLES `customization_option_variants` WRITE;
/*!40000 ALTER TABLE `customization_option_variants` DISABLE KEYS */;
INSERT INTO `customization_option_variants` VALUES (1,1,'color','Red',0.00,'#ff0000',NULL,1,0),(2,1,'color','White',0.00,'#f9f3f4',NULL,1,0),(3,1,'color','Light pink',0.00,'#f8c8d4',NULL,1,0),(4,1,'color','Fuchsia pink',0.00,'#ff4d9d',NULL,1,0),(5,27,'color','Red',0.00,'#e74c3c',NULL,1,0),(6,27,'color','White',0.00,'#f9f3f4',NULL,1,0),(7,27,'color','Pink',0.00,'#e8b4bc',NULL,1,0),(8,27,'color','Two-tone(White & Pink tip)',0.00,NULL,NULL,1,0),(9,27,'color','Yellow',0.00,'#f1c40f',NULL,1,0),(10,27,'color','Orange',0.00,'#ff991c',NULL,1,0),(11,27,'color','Violet',0.00,'#9b59b6',NULL,1,0),(12,33,'color','Red',0.00,'#e74c3c',NULL,1,0),(13,33,'color','White',0.00,'#f9f3f4',NULL,1,0),(14,33,'color','Pink',0.00,'#e8b4bc',NULL,1,0),(15,33,'color','Yellow',0.00,'#f1c40f',NULL,1,0),(16,33,'color','Orange',0.00,'#ff991c',NULL,1,0),(18,6,'color','White',0.00,'#f9f3f4',NULL,1,0),(19,6,'color','Pink',0.00,'#e8b4bc',NULL,1,0),(20,6,'color','Red',0.00,'#e74c3c',NULL,1,0),(21,6,'color','Violet',0.00,'#9b59b6',NULL,1,0),(22,6,'color','Two tone(violet, pink)',0.00,NULL,NULL,1,0),(23,6,'color','Yellow',0.00,'#f1c40f',NULL,1,0),(24,6,'color','Orange',0.00,'#ff991c',NULL,1,0),(25,6,'color','Green',0.00,'#008000',NULL,1,0),(26,28,'color','White',0.00,'#f9f3f4',NULL,1,0),(27,28,'color','Yellow',0.00,'#f1c40f',NULL,1,0),(28,28,'color','Red',0.00,'#e74c3c',NULL,1,0),(29,28,'color','Pink',0.00,'#e8b4bc',NULL,1,0),(30,28,'color','Orange',0.00,'#ff991c',NULL,1,0),(31,2,'size','Petite',120.00,NULL,NULL,1,0),(32,2,'size','Regal',150.00,NULL,NULL,1,0),(33,33,'color','Blue',0.00,'#0000ff',NULL,1,2);
/*!40000 ALTER TABLE `customization_option_variants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customization_options`
--

DROP TABLE IF EXISTS `customization_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customization_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` enum('flower','color','style','addon','filler') NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customization_options`
--

LOCK TABLES `customization_options` WRITE;
/*!40000 ALTER TABLE `customization_options` DISABLE KEYS */;
INSERT INTO `customization_options` VALUES (1,'flower','rose','Local Roses',75.00,'flower_1786192659_hTqmhwZ8.jpg','roses',NULL,1,1,'2026-04-12 07:19:44'),(2,'flower','sunflower','Sunflowers',150.00,'flower_1786192946_fGHoZzN9.jpg','sunflowers',NULL,1,2,'2026-04-12 07:19:44'),(3,'flower','tulip','Tulips',300.00,'flower_1786192957_UhK6h9Yv.jpg','tulips',NULL,0,3,'2026-04-12 07:19:44'),(4,'flower','pink_rose','Pink Roses',100.00,'flower_1786193054_GXPrswEZ.jpg','arrangements',NULL,0,4,'2026-04-12 07:19:44'),(5,'flower','white_rose','White Roses',120.00,'flower_1786193085_olOb0gDo.jpg','arrangements',NULL,0,5,'2026-04-12 07:19:44'),(6,'flower','carnation','Carnations',150.00,'flower_1786193105_3dwsrkcs.jpg','arrangements',NULL,1,6,'2026-04-12 07:19:44'),(7,'color','red','Red',80.00,NULL,NULL,'#e74c3c',1,1,'2026-04-12 07:19:44'),(8,'color','pink','Pink',80.00,NULL,NULL,'#e8b4bc',1,2,'2026-04-12 07:19:44'),(9,'color','white','White',80.00,NULL,NULL,'#f9f3f4',1,3,'2026-04-12 07:19:44'),(10,'color','yellow','Yellow',80.00,NULL,NULL,'#f1c40f',1,4,'2026-04-12 07:19:44'),(11,'color','purple','Purple',80.00,NULL,NULL,'#9b59b6',1,5,'2026-04-12 07:19:44'),(12,'color','mixed','Mixed Colors',100.00,NULL,NULL,NULL,1,6,'2026-04-12 07:19:44'),(13,'style','bouquet','Hand-Tied Bouquet',300.00,'flower_1786290491_YakOlG4E.jpg',NULL,NULL,1,1,'2026-04-12 07:19:44'),(14,'style','vase','Vase Arrangement',500.00,'a3.jpg',NULL,NULL,1,2,'2026-04-12 07:19:44'),(15,'style','box','Flower Box',400.00,'flower_1786346428_67p4mRQh.jpg',NULL,NULL,1,3,'2026-04-12 07:19:44'),(16,'style','basket','Basket Arrangement',450.00,'flower_1786346403_kkE5tcsY.jpg',NULL,NULL,1,4,'2026-04-12 07:19:44'),(17,'addon','chocolate','Chocolate Box',450.00,NULL,NULL,NULL,1,1,'2026-04-12 07:19:44'),(18,'addon','teddy_bear','Teddy Bear',300.00,NULL,NULL,NULL,1,2,'2026-04-12 07:19:44'),(19,'addon','greeting_card','Greeting Card',50.00,NULL,NULL,NULL,1,3,'2026-04-12 07:19:44'),(20,'addon','balloon','Balloon',150.00,NULL,NULL,NULL,1,4,'2026-04-12 07:19:44'),(21,'addon','vase_upgrade','Premium Vase',200.00,NULL,NULL,NULL,1,5,'2026-04-12 07:19:44'),(26,'color','blue','Blue',80.00,NULL,NULL,'#4169e1',1,7,'2026-08-08 13:28:37'),(27,'flower','china_roses','China Roses',250.00,'flower_1786276975_C7ZAYupg.jpg',NULL,NULL,1,7,'2026-08-09 12:02:55'),(28,'flower','gerbera','Gerbera',150.00,'flower_1786290255_9bYqlKCB.jpg',NULL,NULL,1,8,'2026-08-09 15:44:15'),(29,'color','black','Black',80.00,NULL,NULL,'#000000',1,8,'2026-08-09 15:47:05'),(30,'color','green','Green',80.00,NULL,NULL,'#008000',1,9,'2026-08-10 07:30:45'),(31,'color','orange','Orange',80.00,NULL,NULL,'#ff991c',1,10,'2026-08-10 07:31:47'),(32,'color','brown','Brown',80.00,NULL,NULL,'#895129',1,11,'2026-08-10 07:33:04'),(33,'flower','ecudorian_roses','Ecuadorian Roses',350.00,'flower_1786348768_ckijLihv.jpg',NULL,NULL,1,9,'2026-08-10 07:57:18'),(34,'filler','golden_rod','Golden Rod fillers',250.00,'flower_1786354124_2qbARij8.jpg',NULL,NULL,1,1,'2026-08-10 08:20:15'),(35,'filler','asters','Asters fillers',250.00,'flower_1786354139_7kr2CbIe.png',NULL,NULL,1,2,'2026-08-10 08:20:15'),(36,'filler','queens_ann','Queens Ann fillers',250.00,'flower_1786354157_NZVz2o7P.jpg',NULL,NULL,1,3,'2026-08-10 08:20:15'),(37,'filler','gypsophila','Gypsophila fillers',500.00,'flower_1786354171_OpzeS2kV.jpg',NULL,NULL,1,4,'2026-08-10 08:20:15'),(38,'filler','misty','Misty Purple fillers',500.00,'flower_1786354204_9ZwhZ3mV.jpg',NULL,NULL,1,5,'2026-08-10 08:20:15'),(39,'filler','eucalyptus','Eucalyptus fillers',500.00,'flower_1786354221_ydEw736K.jpg',NULL,NULL,1,6,'2026-08-10 08:20:15'),(40,'filler','statice_caspia','Statice/Caspia fillers',500.00,'flower_1786354306_vnXmyUsr.jpg',NULL,NULL,1,7,'2026-08-10 08:20:15');
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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_01_01_000001_create_admin_users_table',1),(5,'2026_01_01_000002_create_customers_table',1),(6,'2026_01_01_000003_create_product_categories_table',1),(7,'2026_01_01_000004_create_products_table',1),(8,'2026_01_01_000005_create_cart_table',1),(9,'2026_01_01_000006_create_orders_table',1),(10,'2026_01_01_000007_create_order_items_table',1),(11,'2026_01_01_000008_create_gcash_payments_table',1),(12,'2026_01_01_000009_create_contact_messages_table',1),(13,'2026_01_01_000010_create_customization_options_table',1),(14,'2026_01_01_000011_create_customization_presets_table',1),(15,'2026_01_01_000012_create_preset_items_table',1),(16,'2026_01_01_000013_create_saved_customizations_table',1),(17,'2026_01_01_000014_create_service_photos_table',1),(18,'2026_01_01_000015_add_hex_color_to_customization_options_table',2),(19,'2026_01_01_000016_create_customization_option_variants_table',3),(20,'2026_01_01_000017_add_filler_type_to_customization_options_table',3),(21,'2026_08_11_000001_add_remember_token_to_customers_table',4);
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
INSERT INTO `orders` VALUES (1,'ORD-20260405-5702',2,5300.00,0.00,NULL,NULL,'cod','completed','delivered','Zone 7 Bangued, Abra',NULL,'2026-12-12','fadarfasdfasd','2026-04-05 06:03:51','2026-05-02 21:58:38',NULL,NULL,NULL),(2,'ORD-20260405-1384',2,1800.00,0.00,900.00,900.00,'gcash','pending_downpayment','cancelled','Zone 7 Bangued, Abra',NULL,'2026-12-12','','2026-04-05 16:14:16','2026-05-02 21:41:01',NULL,NULL,NULL),(3,'ORD-20260502-7968',1,4850.00,0.00,2425.00,2425.00,'gcash','completed','delivered','zone 4 Bangued, Baba redcross',NULL,'2026-12-23','aaaaaaaa','2026-05-02 21:21:15','2026-05-02 21:58:36',NULL,NULL,NULL),(4,'ORD-20260502-7569',1,1800.00,0.00,900.00,900.00,'gcash','completed','cancelled','zone 4 Bangued, Baba redcross',NULL,'2026-12-12','','2026-05-02 21:59:37','2026-05-06 14:21:52','Change of delivery address','','2026-05-06 17:48:35'),(5,'ORD-20260506-2461',1,2300.00,0.00,NULL,NULL,'cod','completed','delivered','zone 4 Bangued, Baba redcross',NULL,'2026-12-12','','2026-05-06 09:51:33','2026-05-06 09:52:04',NULL,NULL,NULL),(6,'ORD-20260506-3807',1,1450.00,0.00,NULL,NULL,'cod','completed','cancelled','zone 4 Bangued, Baba redcross',NULL,'2026-12-12','qqqqqqqqq','2026-05-06 09:52:31','2026-05-06 14:21:50','Change of delivery address','','2026-05-06 17:54:35'),(7,'ORD-20260506-6143',4,1550.00,100.00,775.00,775.00,'gcash','completed','cancelled','Zone 4, Arellano St., Bangued, Abra, Bangued, Abra','Bangued','2026-12-12','aaaaaaaaaaaa','2026-05-06 10:30:57','2026-05-06 14:21:48','Change of delivery address','','2026-05-06 18:32:04'),(8,'ORD-20260506-4105',2,1700.00,600.00,850.00,850.00,'gcash','completed','delivered','Bugbog, Abra, Bucay, Abra','Bucay','2026-05-07','qadasdasd','2026-05-06 14:15:53','2026-05-06 14:18:23',NULL,NULL,NULL),(9,'ORD-20260506-3069',2,2600.00,100.00,1300.00,1300.00,'gcash','completed','delivered','Zone 7 Bangued, Abra, Bangued, Abra','Bangued','2026-05-07','qqqq','2026-05-06 14:16:26','2026-05-06 14:18:47',NULL,NULL,NULL),(10,'ORD-20260506-6075',4,1550.00,100.00,775.00,775.00,'gcash','partial','delivered','Zone 4, Arellano St., Bangued, Abra, Bangued, Abra','Bangued','2026-05-07','qqq','2026-05-06 14:17:22','2026-08-03 15:58:07',NULL,NULL,NULL),(11,'ORD-20260803-6307',2,2400.00,100.00,1200.00,1200.00,'gcash','pending_downpayment','pending','Zone 7 Bangued, Abra, Bangued, Abra','Bangued','2026-12-23','werwerwer','2026-08-03 15:55:23','2026-08-03 15:55:23',NULL,NULL,NULL),(12,'ORD-20260807-2512',5,2400.00,100.00,NULL,NULL,'cod','pending_cod','preparing','Zone 1 Bangued, Bangued, Abra','Bangued','2026-08-10','Leave at gate','2026-08-07 06:18:11','2026-08-08 12:56:12',NULL,NULL,NULL),(13,'ORD-20260807-5794',6,2400.00,100.00,1200.00,1200.00,'gcash','partial','confirmed','Zone 1 Block 3, Bangued, Abra','Bangued','2026-08-09',NULL,'2026-08-07 06:22:37','2026-08-07 14:23:59',NULL,NULL,NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_categories`
--

LOCK TABLES `product_categories` WRITE;
/*!40000 ALTER TABLE `product_categories` DISABLE KEYS */;
INSERT INTO `product_categories` VALUES (1,'roses','Roses','2026-05-02 21:39:59'),(2,'sunflowers','Sunflowers','2026-05-02 21:39:59'),(3,'tulips','Tulips','2026-05-02 21:39:59'),(4,'seasonal','Seasonal','2026-05-02 21:39:59'),(5,'arrangements','Arrangements','2026-05-02 21:39:59');
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
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'Red Romance Bouquet','A classic arrangement of deep red roses, symbolizing love and passion.',2300.00,'roses','rs.jpg','2026-04-04 19:39:27'),(2,'Sunflower Symphony','A vibrant arrangement of cheerful sunflowers to brighten any room with sunny energy.',10500.00,'sunflowers','sf.jpg','2026-04-04 19:39:27'),(3,'Pastel Tulip Elegance','Soft pastel tulips arranged to create an elegant and sophisticated spring display.',1450.00,'tulips','t1.jpg','2026-04-04 19:39:27'),(4,'Mixed Garden Bouquet','A beautiful assortment of seasonal flowers for a natural garden feel.',1550.00,'seasonal','mg.jpg','2026-04-04 19:39:27'),(5,'Tropical Paradise','Exotic flowers that bring a vibrant, tropical feel to any space.',2550.00,'arrangements','tp.jpg','2026-04-04 19:39:27'),(6,'Sunshine Daisies','Cheerful daisies that spread happiness and brighten your day.',1800.00,'arrangements','sd.jpg','2026-04-04 19:39:27'),(7,'Pink Perfection','Delicate pink roses that express admiration and gratitude.',1800.00,'roses','pr.jpg','2026-04-04 19:39:27'),(8,'White Innocence','Pure white roses symbolizing purity, innocence, and new beginnings.',1550.00,'roses','wr.jpg','2026-04-04 19:39:27'),(9,'Golden Friendship','Bright yellow roses representing friendship, joy, and caring.',3400.00,'roses','yr.jpg','2026-04-04 19:39:27'),(10,'Lavender Enchantment','Mystical lavender roses that convey enchantment and love at first sight.',5200.00,'roses','lr.jpg','2026-04-04 19:39:27'),(11,'Red Gratitude','Red roses expressing appreciation and thankfulness.',4900.00,'roses','ra.jpg','2026-04-04 19:39:27'),(12,'Golden Sunshine','A vibrant mix of sunflowers that brings warmth and happiness to any space.',1400.00,'sunflowers','s2.jpg','2026-04-04 19:39:27'),(13,'Summer Field','An abundant arrangement that captures the essence of a sunny summer field.',900.00,'sunflowers','s3.jpg','2026-04-04 19:39:27'),(14,'Mini Delight','Charming mini sunflowers perfect for adding a cheerful touch to any room.',1300.00,'sunflowers','s4.jpg','2026-04-04 19:39:27'),(15,'Sunny Mix','A delightful combination of sunflowers and daisies for a bright, cheerful display.',2300.00,'sunflowers','s5.jpg','2026-04-04 19:39:27'),(16,'Autumn Picnic','Rich, warm sunflowers in a box.',2500.00,'sunflowers','s6.jpg','2026-04-04 19:39:27'),(17,'Gradient Delight','A vibrant mix of colorful white and pink that brings joy and cheer to any space.',1600.00,'tulips','ct.jpg','2026-04-04 19:39:27'),(18,'Soft Passion','Light pink tulips that symbolize perfect love and passion.',2750.00,'tulips','pt.jpg','2026-04-04 19:39:27'),(19,'Blue Royalty','Regal Blue tulips that represent royalty and elegance.',4200.00,'tulips','bt.jpg','2026-04-04 19:39:27'),(20,'Yellow Sunshine','Cheerful mixed tulips that bring sunshine and happiness wherever they go.',3000.00,'tulips','mt.avif','2026-04-04 19:39:27'),(21,'White Purity','Pure white tulips symbolizing forgiveness, respect, and purity.',2500.00,'tulips','wt.jpg','2026-04-04 19:39:27'),(22,'Spring Blossoms','Fresh spring flowers that capture the renewal and beauty of the season.',1100.00,'seasonal','ss1.jpg','2026-04-04 19:39:27'),(23,'Summer Blooms','Vibrant summer flowers that bring warmth and energy to any space.',1150.00,'seasonal','ss2.jpg','2026-04-04 19:39:27'),(24,'Autumn Harvest','Rich, warm flowers that capture the cozy essence of autumn.',1350.00,'seasonal','ss3.jpg','2026-04-04 19:39:27'),(25,'Winter Wonder','Elegant winter flowers that bring beauty to the coldest season.',1200.00,'seasonal','ss4.jpg','2026-04-04 19:39:27'),(26,'Year-Round Beauty','A timeless arrangement of flowers that brings joy in every season.',2200.00,'seasonal','ss5.jpg','2026-04-04 19:39:27'),(27,'Classic Elegance','A sophisticated arrangement of exotic tropical flowers for timeless beauty.',2600.00,'arrangements','a1.jpg','2026-04-04 19:39:27'),(28,'Contemporary Charm','A modern floral arrangement perfect as a stunning centerpiece.',1300.00,'arrangements','a2.jpg','2026-04-04 19:39:27'),(29,'Lush Garden','An abundant arrangement that brings the beauty of a garden indoors.',1500.00,'arrangements','a3.jpg','2026-04-04 19:39:27'),(30,'Artistic Display','A creatively designed floral arrangement that showcases artistic flair.',2800.00,'arrangements','a4.jpg','2026-04-04 19:39:27'),(40,'Rosas',NULL,1200.00,'roses','flower_1786423018_3YOll5GL.png','2026-08-11 04:36:58');
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
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service_photos`
--

LOCK TABLES `service_photos` WRITE;
/*!40000 ALTER TABLE `service_photos` DISABLE KEYS */;
INSERT INTO `service_photos` VALUES (1,'weddings','w1.jpg','Bridal Bouquet','2026-04-04 19:39:27'),(2,'weddings','w2.jpg','Church Flowers','2026-04-04 19:39:27'),(3,'weddings','w4.jpg','Reception Centerpieces','2026-04-04 19:39:27'),(4,'weddings','w3.jpg','Bridesmaid Bouquets','2026-04-04 19:39:27'),(5,'events','p1.jpg','U.A Pageant 2025','2026-04-04 19:39:27'),(6,'events','p2.jpg','Miss Abra Pageant','2026-04-04 19:39:27'),(7,'events','p3.jpg','Mr & Miss Bucay','2026-04-04 19:39:27'),(8,'events','lr1.jpg','Anniversary Surprise','2026-04-04 19:39:27'),(9,'corporate','bb1.jpg','BBM Building Grand Opening','2026-04-04 19:39:27'),(10,'corporate','bbm.jpg','BBM Lobby Arrangement','2026-04-04 19:39:27'),(11,'corporate','aa.jpg','Government Events Table Flowers','2026-04-04 19:39:27'),(12,'corporate','aaa.jpg','Corporate Welcome Flowers','2026-04-04 19:39:27'),(13,'sympathy','ccccc.jpg','Funeral Wreath','2026-04-04 19:39:27'),(14,'sympathy','ccc.jpg','Memorial Stand','2026-04-04 19:39:27'),(15,'sympathy','cccc.jpg','Condolence Basket','2026-04-04 19:39:27'),(16,'sympathy','cc.jpg','Memorial Spray','2026-04-04 19:39:27'),(17,'romance','lr1.jpg','Valentine\'s Day Roses','2026-04-04 19:39:27'),(18,'romance','lr2.jpg','Anniversary Arrangement','2026-04-04 19:39:27'),(19,'getwell','sfb.jpg','Get Well Soon Basket','2026-04-04 19:39:27'),(20,'getwell','gw.jpg','Hospital Arrangement','2026-04-04 19:39:27'),(21,'getwell','gw1.jpg','Flowers with Chocolates','2026-04-04 19:39:27'),(22,'events','flower_1786255015_YxPQvdwJ.jpg','Ms. Teen Lagangilang 2026','2026-08-09 05:48:16');
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
INSERT INTO `sessions` VALUES ('2lovT7ReNOa6W4VLlPzJelZl6JcDCKbozG6dtXZH',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZGY2REhjT0plZWtVMWszeUkzbDFLQllTeVE3VVRpUzAxcmp3QXVjcyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9fZGVidWcta2V5IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1786420782),('2tsCXGBoEI7vEKNkGPIFQjysQpYgB8lkT9ODhe4g',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZmtJSVdvVDlObGgyalRYV20xOTdMSTZ5TkVOUWZuVElseTVUUjVHRSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9fZGVidWcta2V5IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1786420779),('5P0WOJgROoeK5BfDTEeip6JJQShiu63AuvqO4IAi',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTHR2RGFvTFhlZXZzam1CT2F0ZkMzVm1rTmtTT2l1TzB2MThGNDBmSCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1786420598),('6rhxLdMnVFqTE4Ebhmg5FMr4Hr8OQxX42V3ZbLDc',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZW1vVlg1V3daUlRJdUIxNmo1Z2Q0R2MyakthRnRwWkpsWWgxdVVzRyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9fZGVidWcta2V5IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1786420771),('7Xp7l8hfNKwdQIIfSVIcU5S645Ije7mk7AvrP1w5',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVTBFeU50SVdacU9OTTJ0bzlrQ0tySEpFSFlQcThWRHpNbTVHVmNHOSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9fZGVidWcta2V5IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1786420766),('805faM8OEWwkpkuVqBqiyKmhiqky8qY2rbeseWmQ',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZ1dJRmQxQ2lqNThXN25FUG1ja3pGRnRuSmFYWERVTnphZWxKOFZlZiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1786419712),('83bucOYGFyBdMTD8LGopY6BxNCwRp5cLScNpwyQ8',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMkZDMjh2N3JsT0JJSHVRYW1ST0NjWDJrd2x5Z2pVWXJDUjBIcTFWYiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9fZGVidWcta2V5IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1786420763),('8FiI9rR2JiA0ZYnXrjAhyddW30woAMzyQZoWGXob',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiOXhFak1SaVVvYUxnaXdMR3ZLSnFkM1hUR3ZDRnRIUWpUTTJENERtUyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9fZGVidWcta2V5IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1786420645),('8ufyJQPKfan5EHtdTQUgJDnMadCmMZLMr5DbfonH',NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.132.0 Chrome/148.0.7778.280 Electron/42.7.1 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoicmpiU2NXNmxEdmJVVEpYeW5MdDg3ZE1FaElFUnVKRWRvR1NGWndVSiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9jYXJ0L2NvdW50IjtzOjU6InJvdXRlIjtzOjEwOiJjYXJ0LmNvdW50Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1786423173),('9mumYFoxduZlVPVxDPhN4RjWPG6SG9umhD6IMQ6I',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoicWFRZGJ4VEl2YzM2U2habXdHelcxY1VtZFdaZ0ZORXJZeGM2eDVhSSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9fZGVidWcta2V5IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1786420769),('9pXmY9h66A6QgYwtoOVixosXFc7XuiC83KkIvhtK',5,'127.0.0.1','curl/8.21.0','YTo3OntzOjY6Il90b2tlbiI7czo0MDoiSFhGdVczbHZ1TzNieGNQdFpBakpMOVNNR2xYNnBOVnlOdjB4WmUzZCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9hZG1pbi9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6MTU6ImFkbWluLmRhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjE6e2k6MDtzOjc6Im1lc3NhZ2UiO31zOjM6Im5ldyI7YTowOnt9fXM6NTI6ImxvZ2luX2FkbWluXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NTtzOjg6ImFkbWluX2lkIjtpOjU7czoxNDoiYWRtaW5fdXNlcm5hbWUiO3M6NToiYWRtaW4iO3M6NzoibWVzc2FnZSI7czoyNzoiUHJvZHVjdCBhZGRlZCBzdWNjZXNzZnVsbHkhIjt9',1786421635),('9vl6PbMAuz3McFff5PABWO7seHp0htKqFy47Q1ze',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWTlDbzBuYlJSTGJIblhra2FWaGZibUloZWxIRlNoYmhEUEZHZFd2RCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1786419995),('A31lSdVpIc7sChcwqoCSvFwiT7CvwWJnbKOycO7q',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiaVdoOVFEckVBRHhpclEwSTY5ZENKQXVkSmlnSUNkUkhKQlhsdm5DcSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9fZGVidWcta2V5IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1786420671),('ACmR7cJqZYrLsORDj9JtVtDsOMS14VtEooANBBM0',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTDZLd0VnS0ViWVJaa3ZkR0dtUElTb2FENWpLcXB4Y0Q3a3d5WmpFYiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9fZGVidWcta2V5IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1786420649),('bkRZWWdOUuy4vzMGUy003omgoMHeJbyUOWQN2F3z',NULL,'127.0.0.1','curl/8.21.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSmRPYUxlOFdQYjBPeTBLbFNxb0hFMkJaV2syVWtiU0kySnRRbDljMSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjE6e2k6MDtzOjU6ImVycm9yIjt9czozOiJuZXciO2E6MDp7fX1zOjU6ImVycm9yIjtzOjI1OiJJbnZhbGlkIGVtYWlsIG9yIHBhc3N3b3JkIjt9',1786420819),('bNkMw5dvoFSb6Y00xtBKu3rzKLRPt2EC5p1Fucyu',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSlRwUUdXR3NLejlsb1oxbzVIRDdKZTluT08zcVdUZXlPRm9tY1h3cSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1786420598),('clW8DSHZgKPfYmeIfqqLNfngW9tuPaQJeVxhjygr',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQmhLQnF6a2F0bjNjbks3UUc2RlRhRHpnU0MxMmJycG1VQjdUbmczeCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9fZGVidWcta2V5IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1786420778),('dQBzbIlOxSJK550P798PBQFv3gxSzKRNqRyu5cPL',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiekFhbVNiYlpabUhYRVQ2UEtlV0VSbUxhb1E3azZkaFI1WE92aWl0dSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9fZGVidWcta2V5IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1786419878),('ehKNR58b4kVfRB9h2WGfbMrd35tNPvxlIhkCMJpy',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiaFh1MlNxZ2ZkUGdrWUs0ZFF5TGJ0UlFVWW52OXRyTHV1cjJmM2pQOCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9fZGVidWcta2V5IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1786420784),('F7BXhWdPWob9hwl77zU8KthPk29I2Tzcq0tqvjH6',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWWNHTjNDcFdPSGJKSWRNRFZlQlNkUUUxbTVnclZXa1JaSGY0aXNwNiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1786420302),('FOIv8tWZLaen4fvcyT2kuwqh0RabRjlr7qcPOtmD',NULL,'127.0.0.1','curl/8.21.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZVgzbkRSc0xxM3doRkRndEFwTHd1MlNJRGczajJhVm9QMGRoWDdVeCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjE6e2k6MDtzOjU6ImVycm9yIjt9czozOiJuZXciO2E6MDp7fX1zOjU6ImVycm9yIjtzOjI1OiJJbnZhbGlkIGVtYWlsIG9yIHBhc3N3b3JkIjt9',1786420818),('foKRXVhNus4JPhsyeYiGcxpN3qX2vusqYxYMy8xg',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMDBBU1JRZHY3VDJ3MGlIMEFpUUd4STNNY1dXY2x6eXR0N2g1emRlYSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1786420357),('fpFWQZdSL7FMmzK2TAHUgupFIJi6lB3HGBBD4ezL',1,'127.0.0.1','curl/8.21.0','YTo2OntzOjY6Il90b2tlbiI7czo0MDoiajZWTlJSY29ya2p4N3ZZMzQ2cWJrdmprak1aaER4b0c4MnFPTEY0QiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9hY2NvdW50IjtzOjU6InJvdXRlIjtzOjc6ImFjY291bnQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6MTM6ImN1c3RvbWVyX25hbWUiO3M6NDoiY29ieSI7czoxNDoiY3VzdG9tZXJfZW1haWwiO3M6MTU6InRlc3RAaGFwcHkudGVzdCI7fQ==',1786421006),('GBXfFwCv9zOzjnywtXrpRhmBmCZutTgioslQsJMn',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiY0ZOeXdiMHlWUjh2Q3J0eXJpdDRCVEtjYUVNUE1SV1k0enI3THk2NCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1786420599),('Ge7rejsZV0g3LWBF2y75xUeIW8477EBcWSPEQBLV',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoicG5VNEg3RnpBZ3VKeHJEd01GMFptem5zb05EUmZWSTAwblBrUUVaTSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9fZGVidWcta2V5IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1786420395),('hcxB99VEkvqDFuoJGkg6lUOG52btsUys7UCRRee6',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoicXRsUEdlSlE0ejgyOGJ5QnpiNjQwdlEwV1BXVWdrZ1BCMGhqR3Y4YyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9fZGVidWcta2V5IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1786420646),('hKwnnwiSI64CYOgGflUjC8kd0h9cXuyk1cuyuxeZ',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTUs3NWQ3U2xQa3ZoWERPYnJjMURIRGVCSFU5czJrVzgxQm81MXg4WiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9fZGVidWcta2V5IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1786420776),('i78DhG8O4eqOseLMGOmlSCsVxCPkkGQY46zOjgWB',NULL,'127.0.0.1','curl/8.21.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiak5yeDZCNTYwSThsSGthaFFIbWFjeks3bzlpUlg5dU85bzUyRTA0MSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9fZGVidWcta2V5IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YToxOntpOjA7czo1OiJlcnJvciI7fXM6MzoibmV3IjthOjA6e319czo1OiJlcnJvciI7czoyNToiSW52YWxpZCBlbWFpbCBvciBwYXNzd29yZCI7fQ==',1786420379),('iOs2GnppqBbQNdN11ezQuGQRWpLJFr7L6DonubDU',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiR3dKOFRTTWRlbk16d0hXVVBkMUpaRXo0bjBYbVY5dExBMDJEcnVybSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1786420599),('izNrs4wZOIYuKnVmkBg2KRrYJpJjsMmncXBBTMdS',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoidnVNWmxGQUUzRDVpVlZ5Tjh1N3M0cmw2djRtaUc2dHQyeGQ0b0tMcCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1786420377),('JgLzOrgEGI0nKqXKaMtbXmqQeCKYvTNhXHEK1aI3',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTnVCSE5vT0FHMUJ3cGlYamVybVdUdHVLaEs0YzVUQXFCdVlPZlQwZyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1786420597),('jHXVQLp6BaiWotQKdOsuj4Ge3dOaqRTslEsiUrlZ',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVmpPZlhvcHNSZWdIaTBjbFhWMHhzOFprNktGMTFqY283N1oxYXJ4ZiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1786420596),('JzELdjmz99pA5UyxxBnFVSiFM1jTnua01UkgMLTx',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiemRHVHBHeGJVNjRlbDBGcHE3VWlHN2JQY2xFa1I1WWdXN24ydGR0diI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9fZGVidWcta2V5IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1786420513),('KbpLdTQbupD4AyPeFFja90A75HOb6ftXSCw00r9z',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWlRnckF4OVNJZkFRaGVpTE1peGU2SERoTzBWM3hVQ3lkdU9tdVUyWSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9fZGVidWcta2V5IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1786420651),('KIuvMsE4Y4ikgESD9XrTRiD6VPJK2xYMqa7gOQTU',1,'127.0.0.1','curl/8.21.0','YTo2OntzOjY6Il90b2tlbiI7czo0MDoieXZjOTI1UjFVeGFJM2xtNzRKTW5LOVNnQ256ODdZaDZYdEkzM1JSRCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czoxMzoiY3VzdG9tZXJfbmFtZSI7czo0OiJjb2J5IjtzOjE0OiJjdXN0b21lcl9lbWFpbCI7czoxNToidGVzdEBoYXBweS50ZXN0Ijt9',1786420985),('kjuinXbLNOLsbKGEZyAkjyU2F5h2Sb0FsZzI7c57',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoibklFSndrQVgwOHBrT0lXR3V5ZjdrOFhCRnVja3I0NHlwQ05LYmcwYiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9fZGVidWcta2V5IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1786420761),('kJvVxW3Ra8vtGrz3y9WH7YW3bMU2Uq1yxm0qppNP',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSDVmWkVoSUZYZ0RoNkh3bjRWdUpjTWxaaXQ3V1RPdGFKckJpVVlVQSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9fZGVidWcta2V5IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1786420260),('KZeU2CnaFiIp2yfqWvCKrZcPaiRlfVvBQI3wopXy',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSWN1RWU1dE5ETmM2YWJwMWRKbEpNbm8xRXNPTUg2ajVPZDNqQXZNWCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9fZGVidWcta2V5IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1786419877),('LJWBfhefsmr5GlhvDhbbFKMJhWe2WhPGnnHBHEQk',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNnlBd0ZROUhNRVllanh5Z01WVlp4bzFBOWR3RDBsTkt0YTFMcGFyQSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1786420597),('M1KKsSQrwMrVqSKqlO3VcqJZ9Mu3k2LlE7dOKB2y',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZkhWVjlNOHJzUHNOMnFjZzRQT1k3UE0zNXNyVFExTDJPa29lb2U5MSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1786420600),('m20UM9oP1BqPTK429BW1QVYDPbzf1mW0lC6R36Sv',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiT3J6cVZsQThET285aXhlTWdCcXBmMldPbmRlNVQxVFlvU1BCekNlTyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9fZGVidWcta2V5IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1786420775),('mCFEqcShxUiH6cicVghZ8H3T39xXwpR9IwXbt1mo',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMExxbm5uOXFYMlJqeFFmV0xoZEk1aHpWVEdJREcxQkMyWVE1QzVXaCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1786420994),('MCgH01Od2HML78F2W8mjSEEtT2caK0jEUGD0oDc8',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRkpiQVhhVEJ0Q3BiYW1oTlVwVVdacWVReW95YmtZU3hacTM5WkNmSyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9fZGVidWcta2V5IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1786420768),('MCZTfrhbrSrkZXJUIaWx2du2kf5mmugITlOivZqL',NULL,'127.0.0.1','curl/8.21.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTFFIUnllbkhFWVhLOFhOeGVIN2dxUmtoTWxFSVFOMVJ5UXpKWUtqTCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoxMToiYWRtaW4ubG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YToxOntpOjA7czo1OiJlcnJvciI7fXM6MzoibmV3IjthOjA6e319czo1OiJlcnJvciI7czoyODoiSW52YWxpZCB1c2VybmFtZSBvciBwYXNzd29yZCI7fQ==',1786420909),('Mfuiqms9FmI31WOQ7tyBOcjQs9iEjKUNw2a1ItGW',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVndKMXZlcDJyY2oyaHhYMHVqTzQ5a1o0RGtjU3J3clk3YnE2cTI0VCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1786420800),('MihHfna5Qe3qy5ml2tC43z6AqtNIkF0v5ONRqUpa',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMzVyVjBIYTZ6Rm5XMWRhR2dOekh2VW9ON2gyVTM4Qk05dnVuYWhYeSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9fZGVidWcta2V5IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1786420765),('MJeouwWjL3uCIeOwBpkMfyuDYK9jsf5Y0wSxhYuf',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoib2xsU2xvc2RrczZxRERDMlNQRDdoQjlWWWpUWjlieEVmTW5rVU5pVyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1786419817),('MNMUw40NYiGyRHeuoFE10zPAQhfgt95Ma87jokBh',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoicDVvWW5xNHRoTnBGdHFGV1F3NkpzeFBJT3ZYRlhqeThCOVZQa0M0YSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9fZGVidWcta2V5IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1786419950),('MPTdLViqYHQIkkrVQ3CJ5mbIOCFCE08Fs9WCymG4',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNm9SZGp5NVpLR3lPMnRENjIxNmt1aDhCMGx4R1hvT3dvUnlPblFUWSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1786420378),('oddpHpvDj7mgU1ZB6pMNWaCl1lKPqrBeaw2h6tV2',NULL,'127.0.0.1','curl/8.21.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiaDgzM1BBd2hDVUNLYXY1aWNkMG9yeTJ5aUlyV0Zadm5jdUpPc0IzbiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjE6e2k6MDtzOjU6ImVycm9yIjt9czozOiJuZXciO2E6MDp7fX1zOjU6ImVycm9yIjtzOjI1OiJJbnZhbGlkIGVtYWlsIG9yIHBhc3N3b3JkIjt9',1786420820),('P8qSkj23uMzjmJIP6JsDLwGYe8y6cfZ0mYl0FSgT',NULL,'127.0.0.1','curl/8.21.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoicllNcDhPbmx4VTR3V3YzRzZMRlUwV2dzU2RvTDB3dkFjU2VTS3R2MyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjE6e2k6MDtzOjU6ImVycm9yIjt9czozOiJuZXciO2E6MDp7fX1zOjU6ImVycm9yIjtzOjI1OiJJbnZhbGlkIGVtYWlsIG9yIHBhc3N3b3JkIjt9',1786420820),('PBDWHB1JOBd9bDFTiwli6jgsJOoN9XjPij3g8XOv',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoieEtNSFhLa09wVGtmaXhKTUY2M1F4MzJjVVNpTXpzSUZHeEN1TEtYayI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1786419736),('pqkctYLwQvkvFiyrFM1Rj8djAztJPEjsRNpGCHmh',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZm9FZ2t0N1ZrVjBuZzg5eWF1Ujluak1DZlJ6ekM1ZTRsMGQ4WFhiWCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9fZGVidWcta2V5IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1786420651),('pTd2HT0zEKTnscIROD8o99zSZcPc1uWmWzZAy4aS',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTk1ma1BFOUZXU2xSaVR1WURqWTFtZnJQdkgxVjk4OHRlaGZ1bWZmTSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9fZGVidWcta2V5IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1786420770),('QKdO7TLe9NemUKUXga3kEAbU0FXjeLDIxTMC4wMp',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMmVQUmxWQUFwTXdlYlFhVHpybXF2bHZpNTgyNDluRlJpMGJWeWhzTCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1786420600),('rjQXcheN4E7nu5xSp7SUDz7ufT2SbP57MEAHRqc3',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiaUF1bTJoc3BsNVN1bUpXQUI5M0NWcnNadG4zWDhSZVZaVHZwU0JTYiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1786419574),('rmjxkxh4FXWrJPsSJW2lft3AMHGvL13YPJSKIHce',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRFN5WTJMa2VXcmJJVFplWDFxSlA0SEdOSW1pOU9Vb0tuNXA5UlFTTyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9fZGVidWcta2V5IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1786420760),('RSH4bSoXTLpyfFGDgE06TF8p6JeGJ57zPJyYZDJr',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoicGdhcGdxdjJDaHhmSjFBVTc5c2FpMEdXaFliR3pOc2M4b3V0NWIySiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9fZGVidWcta2V5IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1786420437),('scf0QKBVfV2TXDMUsnnmnFrDLhYa0BcrGjVjawkd',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSElyN1U5cllqN0hwczVGUlNKaldDaU91OGxGc2I4VWNRa0NWaXEzQSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9fZGVidWcta2V5IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1786420647),('SIGv3HI7rIhrG8NnymvMQphjzi3kXITi8FE5zCl8',NULL,'127.0.0.1','curl/8.21.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiV1NOQWhzSnhrbHVicTVsQVRvS2dIV0VKU2tqRnF3WmR6TGR1UzdPcyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyOToiaHR0cDovLzEyNy4wLjAuMTo4MDAxL2FjY291bnQiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czoyNzoiaHR0cDovLzEyNy4wLjAuMTo4MDAxL2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1786420986),('tdjsnH9pehzrsZqKu4woQY5QB22eNAdX0178HxBS',3,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','YToxMDp7czo2OiJfdG9rZW4iO3M6NDA6Ilo2STBwUzYxQUthSk1GMnNRd2VrQ2J0UTNVR21wT1NSMlFlZUhKYTQiO3M6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjMyOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvY2FydC9jb3VudCI7czo1OiJyb3V0ZSI7czoxMDoiY2FydC5jb3VudCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjM7czoxMzoiY3VzdG9tZXJfbmFtZSI7czo2OiJSdXNzZWwiO3M6MTQ6ImN1c3RvbWVyX2VtYWlsIjtzOjE5OiJjb2J5LjIzMzlAZ21haWwuY29tIjtzOjUyOiJsb2dpbl9hZG1pbl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjU7czo4OiJhZG1pbl9pZCI7aTo1O3M6MTQ6ImFkbWluX3VzZXJuYW1lIjtzOjU6ImFkbWluIjtzOjE4OiJjdXN0b21fYXJyYW5nZW1lbnQiO2E6NDp7czo0OiJuYW1lIjtzOjI1OiJDdXN0b20gRmxvd2VyIEFycmFuZ2VtZW50IjtzOjU6InByaWNlIjtkOjI1ODA7czoxMToiZGVzY3JpcHRpb24iO3M6NzY6IkNoaW5hIFJvc2VzIHg3OyBHb2xkZW4gUm9kIGZpbGxlcnMgeDE7IFdyYXBwZXI6IFJlZDsgU3R5bGU6IFZhc2UgQXJyYW5nZW1lbnQiO3M6ODoicXVhbnRpdHkiO2k6MTt9fQ==',1786423173),('tnSzsWpQXPlBU23YcwKnT0NWgAIPLl1l51woA5v6',NULL,'127.0.0.1','curl/8.21.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZjdCZld0cmFtVEhIN0NXc3RWdDRUcnJjUm1DTDI2TjJzV0pKcjlIaSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9fZGVidWcta2V5IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YToxOntpOjA7czo1OiJlcnJvciI7fXM6MzoibmV3IjthOjA6e319czo1OiJlcnJvciI7czoyNToiSW52YWxpZCBlbWFpbCBvciBwYXNzd29yZCI7fQ==',1786420333),('UnnCZfArxL4DHexEvuYEieHucOHLtlSYu1LD7Qb3',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRFNvSE80MGd2Z2pHdWVvcTBHdHFORlpLSlZ4Ukk0Um5TcHBmbUt4aiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1786420595),('uPxQ1x1PeBLaZhhk8oIkOaWpSUiaVnS3rLstaveI',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRzc3emxJVEo5NEdaMEU3NmUxWXNuMG54Q3pHandxUk5OT2lBUmhSayI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9fZGVidWcta2V5IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1786420764),('V7kHndOKgV6RbXmxe6YjzTAnVGxdFIrD8qnRfliY',NULL,'127.0.0.1','curl/8.21.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVVZWa2xQa3pIdVJydnNNVVNsVktpMzdTazlxYk9ib093VXpmMU9yTCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjE6e2k6MDtzOjU6ImVycm9yIjt9czozOiJuZXciO2E6MDp7fX1zOjU6ImVycm9yIjtzOjI1OiJJbnZhbGlkIGVtYWlsIG9yIHBhc3N3b3JkIjt9',1786420817),('W4xvDgHEqJjHvDYbz2LQCcGIqX7PWokTTLLxmt6d',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoicHBpeDMxVmJsVUJJd0t1RTNOeUpVTVhLN2VMMjJQS2Z0cVhVOWNCQSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9fZGVidWcta2V5IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1786420774),('WwZIghgVeitN9vDS7ssMCPVw7XKr23nHBOhqzOLg',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoidlVxeHQ5N3cxWTlHVnlKcXRLU3Q1d3dBTjdOWUJnU2NQV1cxeU5UYSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9fZGVidWcta2V5IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1786420781),('xpwrLXGwUSVkjyBoJfD7ACJE5Itv0qjmYQOnfM90',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVVRjazduNkVuQWxiYUo3c3FFQ3JOSmhaekJ1ODZtTVYzOGlXb2dzVyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9fZGVidWcta2V5IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1786420773),('xuJKDoQzLwrtn7DFgI6HuWbFdb1qKiFvO67KdeqH',NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMGMxUkNaYnV4MFBMSnVnR2dMS3N1WFNmdmNkVWdLMjhIN2dPUHZObyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9jYXJ0L2NvdW50IjtzOjU6InJvdXRlIjtzOjEwOiJjYXJ0LmNvdW50Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1786418555),('XumklLkHNmrOrc6pnZ73LMJpOTv73U3BbSIvHnjQ',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUEZhSlRqS1RZR2FMNzA4R0dLRURQWkExTmlOOGZZSFJjYVJJWXJSaiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9fZGVidWcta2V5IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1786420099),('y6Z1QI4IT7YzUI2JwZwpx9x1GRQL0b3IRGEcmJv3',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiYjdrVlR2NndiNVVWeGNPRDdVT09JeWtaZTRYdFhFWms5MHpETXRWNyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1786420394),('yQdSgKbX2rgyyT8Q1X3y2cwq4iHKmgW73q9rfrul',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMUhvS0RrYWliSzRwMmhiVUZneHRUekFOQmZCWVU2YjZRclFjdVVsYSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9fZGVidWcta2V5IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1786420780),('z1oiRnWu4qU2Qz7z8vNfIXpRMKjuwYzl4qnctVsa',NULL,'127.0.0.1','curl/8.21.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWm84a1BuN2ltb2ZTRTB5RWY1MURXQ1hYazB5ZnBsUzV5cThKMVNiSCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9hY2NvdW50IjtzOjU6InJvdXRlIjtzOjc6ImFjY291bnQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjM6InVybCI7YToxOntzOjg6ImludGVuZGVkIjtzOjI5OiJodHRwOi8vMTI3LjAuMC4xOjgwMDEvYWNjb3VudCI7fX0=',1786420994),('zvnRFLgtdSHBtRT7cdHkndI6RAQDWzlyvcVeSh9I',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiM1V0MTkycXIwZGdWYndpanBUMXlZZ0JNZXVOZGRQQVhYcXZGR1VtSSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1786420420);
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

-- Dump completed on 2026-08-11 12:39:35
