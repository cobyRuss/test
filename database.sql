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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_users`
--

LOCK TABLES `admin_users` WRITE;
/*!40000 ALTER TABLE `admin_users` DISABLE KEYS */;
INSERT INTO `admin_users` VALUES (5,'admin','$2y$12$Xl27pt4FR0aPGCRgsJhc/uHefxwIF/.BedBKj87ngfDaHlivGa0s6','2026-04-05 15:20:25');
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
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
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
-- Table structure for table `category_product`
--

DROP TABLE IF EXISTS `category_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category_product` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  UNIQUE KEY `category_product_product_id_category_id_unique` (`product_id`,`category_id`),
  KEY `category_product_category_id_foreign` (`category_id`),
  CONSTRAINT `category_product_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `category_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category_product`
--

LOCK TABLES `category_product` WRITE;
/*!40000 ALTER TABLE `category_product` DISABLE KEYS */;
INSERT INTO `category_product` VALUES (1,1),(2,2),(3,3),(4,4),(4,5),(5,5),(6,5),(7,1),(8,1),(9,1),(10,1),(11,1),(12,2),(13,2),(14,2),(15,2),(15,4),(16,2),(17,3),(18,3),(19,3),(20,3),(21,3),(22,4),(23,4),(24,4),(25,4),(26,4),(27,5),(28,5),(29,5),(30,5),(39,8),(40,7);
/*!40000 ALTER TABLE `category_product` ENABLE KEYS */;
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
  `remember_token` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES (1,'coby','caisduas@gmail.com','09944325125','Bangued','zone 4 Bangued, Baba redcross','$2y$12$RY6JJMpIQrleEOiHAUNn/.zI.BK8DJ38mDnTF.ofMIbyH..MgqkPa','2026-04-04 20:04:55',NULL,NULL,NULL,NULL,0,NULL),(2,'Alder Mangaliman','derrr21@gmail.com','09357591816','Bangued','Zone 7 Bangued, Abra','$2y$12$RY6JJMpIQrleEOiHAUNn/.zI.BK8DJ38mDnTF.ofMIbyH..MgqkPa','2026-04-05 06:02:31',NULL,NULL,NULL,NULL,0,NULL),(3,'Russel','coby.2339@gmail.com','09934568655','Bangued','Zone 1','$2y$12$RY6JJMpIQrleEOiHAUNn/.zI.BK8DJ38mDnTF.ofMIbyH..MgqkPa','2026-04-12 07:57:48',NULL,NULL,'621863','2026-04-12 10:14:59',0,NULL),(4,'Coby Barba Labuguen','coby.233@gmail.com','09357591816','Bangued','Zone 4, Arellano St., Bangued, Abra','$2y$12$RY6JJMpIQrleEOiHAUNn/.zI.BK8DJ38mDnTF.ofMIbyH..MgqkPa','2026-05-06 10:29:36',NULL,NULL,NULL,NULL,0,NULL),(5,'Test User','test@example.com','09171234567','Bangued','Zone 1, Bangued, Abra','$2y$12$RY6JJMpIQrleEOiHAUNn/.zI.BK8DJ38mDnTF.ofMIbyH..MgqkPa','2026-08-07 14:18:03',NULL,NULL,NULL,NULL,0,NULL),(6,'Gcash Tester','gcash_test_18194@example.com','09171234567','Bangued','Zone 1, Bangued, Abra','$2y$12$RY6JJMpIQrleEOiHAUNn/.zI.BK8DJ38mDnTF.ofMIbyH..MgqkPa','2026-08-07 14:22:19',NULL,NULL,NULL,NULL,0,NULL),(7,'Coby Barba Labuguen','test@gmail.com','09934568655','Bangued','Zone 6, 117 Arellano St., Bangued, Abra','$2y$12$RY6JJMpIQrleEOiHAUNn/.zI.BK8DJ38mDnTF.ofMIbyH..MgqkPa','2026-08-08 12:53:45',NULL,NULL,NULL,NULL,0,NULL),(8,'Jeslyn Bernal','bernaljeslyn0903@gmail.com','09361052909','Bangued','Sta.Rosa Bangued Abra, Bangued, Abra','$2y$12$oiYRSdSpAimCxQzgPmPIi.PeqYP0LFKbzqAuREtT.wxRFXIju0TT6','2026-08-11 02:47:40',NULL,NULL,NULL,NULL,0,NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customization_option_variants`
--

LOCK TABLES `customization_option_variants` WRITE;
/*!40000 ALTER TABLE `customization_option_variants` DISABLE KEYS */;
INSERT INTO `customization_option_variants` VALUES (1,1,'color','Red',0.00,'#e74c3c','flower_1786528192_f4tZLVDf.jpg',1,0),(2,1,'color','White',0.00,'#f9f3f4','flower_1786528177_W00OgiSA.jpg',1,0),(3,1,'color','Light pink',0.00,'#f8c8d4','flower_1786528202_wRf4R89A.jpg',1,0),(4,1,'color','Fuchsia pink',80.00,'#ff4d9d','flower_1786528380_NNG8MH9R.jpg',1,0),(5,27,'color','Red',0.00,'#e74c3c',NULL,1,0),(6,27,'color','White',0.00,'#f9f3f4',NULL,1,0),(7,27,'color','Pink',0.00,'#e8b4bc',NULL,1,0),(8,27,'color','Two-tone(White & Pink tip)',0.00,NULL,NULL,1,0),(9,27,'color','Yellow',0.00,'#f1c40f',NULL,1,0),(10,27,'color','Orange',0.00,'#ff991c',NULL,1,0),(11,27,'color','Violet',0.00,'#9b59b6',NULL,1,0),(12,33,'color','Red',0.00,'#e74c3c',NULL,1,0),(13,33,'color','White',0.00,'#f9f3f4',NULL,1,0),(14,33,'color','Pink',0.00,'#e8b4bc',NULL,1,0),(15,33,'color','Yellow',0.00,'#f1c40f',NULL,1,0),(16,33,'color','Orange',0.00,'#ff991c',NULL,1,0),(17,33,'color','Blue',0.00,'#4169e1',NULL,1,0),(18,6,'color','White',0.00,'#f9f3f4',NULL,1,0),(19,6,'color','Pink',0.00,'#e8b4bc',NULL,1,0),(20,6,'color','Red',0.00,'#e74c3c',NULL,1,0),(21,6,'color','Violet',0.00,'#9b59b6',NULL,1,0),(22,6,'color','Two tone(violet, pink)',0.00,NULL,NULL,1,0),(23,6,'color','Yellow',0.00,'#f1c40f',NULL,1,0),(24,6,'color','Orange',0.00,'#ff991c',NULL,1,0),(25,6,'color','Green',0.00,'#008000',NULL,1,0),(26,28,'color','White',0.00,'#f9f3f4',NULL,1,0),(27,28,'color','Yellow',0.00,'#f1c40f',NULL,1,0),(28,28,'color','Red',0.00,'#e74c3c',NULL,1,0),(29,28,'color','Pink',0.00,'#e8b4bc',NULL,1,0),(30,28,'color','Orange',0.00,'#ff991c',NULL,1,0),(31,2,'size','Petite',120.00,NULL,NULL,1,0),(32,2,'size','Regal',150.00,NULL,NULL,1,0),(33,41,'color','Red',0.00,'#e74c3c',NULL,1,0),(34,41,'color','Pink',0.00,'#e8b4bc',NULL,1,1),(35,41,'color','White',0.00,'#f9f3f4',NULL,1,2),(36,41,'color','Yellow',0.00,'#f1c40f',NULL,1,3),(37,41,'color','Purple',0.00,'#9b59b6',NULL,1,4),(38,41,'color','Gold',0.00,'#d4af37',NULL,1,5),(39,41,'size','1 inch',20.00,NULL,NULL,1,0),(40,41,'size','2 inches',35.00,NULL,NULL,1,1),(41,41,'size','3 inches',50.00,NULL,NULL,1,2),(42,42,'color','Red',0.00,'#e74c3c',NULL,1,0),(43,42,'color','White',0.00,'#f9f3f4',NULL,1,1),(44,42,'color','Pink',0.00,'#e8b4bc',NULL,1,2),(45,42,'size','1 inch',25.00,NULL,NULL,1,0),(46,42,'size','2 inches',40.00,NULL,NULL,1,1);
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
  `type` enum('flower','color','style','addon','filler','ribbon') NOT NULL,
  `name` varchar(100) NOT NULL,
  `display_name` varchar(100) NOT NULL,
  `price` decimal(10,2) DEFAULT 0.00,
  `image_url` varchar(500) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `hex_color` varchar(20) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `stock_quantity` int(10) unsigned NOT NULL DEFAULT 100,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customization_options`
--

LOCK TABLES `customization_options` WRITE;
/*!40000 ALTER TABLE `customization_options` DISABLE KEYS */;
INSERT INTO `customization_options` VALUES (1,'flower','rose','Local Roses',75.00,'flower_1786192659_hTqmhwZ8.jpg','roses',NULL,1,1,100,'2026-04-12 07:19:44'),(2,'flower','sunflower','Sunflowers',150.00,'flower_1786192946_fGHoZzN9.jpg','sunflowers',NULL,1,2,100,'2026-04-12 07:19:44'),(3,'flower','tulip','Tulips',300.00,'flower_1786192957_UhK6h9Yv.jpg','tulips',NULL,0,3,100,'2026-04-12 07:19:44'),(4,'flower','pink_rose','Pink Roses',100.00,'flower_1786193054_GXPrswEZ.jpg','arrangements',NULL,0,4,100,'2026-04-12 07:19:44'),(5,'flower','white_rose','White Roses',120.00,'flower_1786193085_olOb0gDo.jpg','arrangements',NULL,0,5,100,'2026-04-12 07:19:44'),(6,'flower','carnation','Carnations',150.00,'flower_1786193105_3dwsrkcs.jpg','arrangements',NULL,1,6,100,'2026-04-12 07:19:44'),(7,'color','red','Red',80.00,NULL,NULL,'#e74c3c',1,1,100,'2026-04-12 07:19:44'),(8,'color','pink','Pink',80.00,NULL,NULL,'#e8b4bc',1,2,100,'2026-04-12 07:19:44'),(9,'color','white','White',80.00,NULL,NULL,'#f9f3f4',1,3,100,'2026-04-12 07:19:44'),(10,'color','yellow','Yellow',80.00,NULL,NULL,'#f1c40f',1,4,100,'2026-04-12 07:19:44'),(11,'color','purple','Purple',80.00,NULL,NULL,'#9b59b6',1,5,100,'2026-04-12 07:19:44'),(12,'color','mixed','Mixed Colors',100.00,NULL,NULL,NULL,1,6,100,'2026-04-12 07:19:44'),(13,'style','bouquet','Hand-Tied Bouquet',300.00,'flower_1786290491_YakOlG4E.jpg',NULL,NULL,1,1,100,'2026-04-12 07:19:44'),(14,'style','vase','Vase Arrangement',500.00,'a3.jpg',NULL,NULL,1,2,100,'2026-04-12 07:19:44'),(15,'style','box','Flower Box',400.00,'flower_1786346428_67p4mRQh.jpg',NULL,NULL,1,3,100,'2026-04-12 07:19:44'),(16,'style','basket','Basket Arrangement',450.00,'flower_1786346403_kkE5tcsY.jpg',NULL,NULL,1,4,100,'2026-04-12 07:19:44'),(17,'addon','chocolate','Chocolate Box',450.00,NULL,NULL,NULL,1,1,100,'2026-04-12 07:19:44'),(18,'addon','teddy_bear','Teddy Bear',300.00,NULL,NULL,NULL,1,2,100,'2026-04-12 07:19:44'),(19,'addon','greeting_card','Greeting Card',50.00,NULL,NULL,NULL,1,3,100,'2026-04-12 07:19:44'),(20,'addon','balloon','Balloon',150.00,NULL,NULL,NULL,1,4,100,'2026-04-12 07:19:44'),(21,'addon','vase_upgrade','Premium Vase',200.00,NULL,NULL,NULL,1,5,100,'2026-04-12 07:19:44'),(26,'color','blue','Blue',80.00,NULL,NULL,'#4169e1',1,7,100,'2026-08-08 13:28:37'),(27,'flower','china_roses','China Roses',250.00,'flower_1786276975_C7ZAYupg.jpg',NULL,NULL,1,7,100,'2026-08-09 12:02:55'),(28,'flower','gerbera','Gerbera',150.00,'flower_1786290255_9bYqlKCB.jpg',NULL,NULL,1,8,100,'2026-08-09 15:44:15'),(29,'color','black','Black',80.00,NULL,NULL,'#000000',1,8,100,'2026-08-09 15:47:05'),(30,'color','green','Green',80.00,NULL,NULL,'#008000',1,9,100,'2026-08-10 07:30:45'),(31,'color','orange','Orange',80.00,NULL,NULL,'#ff991c',1,10,100,'2026-08-10 07:31:47'),(32,'color','brown','Brown',80.00,NULL,NULL,'#895129',1,11,100,'2026-08-10 07:33:04'),(33,'flower','ecudorian_roses','Ecuadorian Roses',350.00,'flower_1786348768_ckijLihv.jpg',NULL,NULL,1,9,100,'2026-08-10 07:57:18'),(34,'filler','golden_rod','Golden Rod fillers',0.00,NULL,NULL,NULL,1,1,100,'2026-08-10 08:20:15'),(35,'filler','asters','Asters fillers',0.00,NULL,NULL,NULL,1,2,100,'2026-08-10 08:20:15'),(36,'filler','queens_ann','Queens Ann fillers',0.00,NULL,NULL,NULL,1,3,100,'2026-08-10 08:20:15'),(37,'filler','gypsophila','Gypsophila fillers',0.00,NULL,NULL,NULL,1,4,100,'2026-08-10 08:20:15'),(38,'filler','misty','Misty fillers',0.00,NULL,NULL,NULL,1,5,100,'2026-08-10 08:20:15'),(39,'filler','eucalyptus','Eucalyptus fillers',0.00,NULL,NULL,NULL,1,6,100,'2026-08-10 08:20:15'),(40,'filler','statice_caspia','Statice/Caspia fillers',0.00,NULL,NULL,NULL,1,7,100,'2026-08-10 08:20:15'),(41,'ribbon','satin_ribbon','Satin Ribbon',0.00,NULL,NULL,NULL,1,1,100,'2026-08-12 09:09:40'),(42,'ribbon','organza_ribbon','Organza Ribbon',0.00,NULL,NULL,NULL,1,2,100,'2026-08-12 09:09:40');
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
-- Table structure for table `flower_product`
--

DROP TABLE IF EXISTS `flower_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `flower_product` (
  `product_id` int(11) NOT NULL,
  `flower_id` int(11) NOT NULL,
  UNIQUE KEY `flower_product_product_id_flower_id_unique` (`product_id`,`flower_id`),
  KEY `flower_product_flower_id_foreign` (`flower_id`),
  CONSTRAINT `flower_product_flower_id_foreign` FOREIGN KEY (`flower_id`) REFERENCES `customization_options` (`id`) ON DELETE CASCADE,
  CONSTRAINT `flower_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `flower_product`
--

LOCK TABLES `flower_product` WRITE;
/*!40000 ALTER TABLE `flower_product` DISABLE KEYS */;
INSERT INTO `flower_product` VALUES (1,1),(2,2),(3,3),(4,6),(4,27),(4,28),(5,6),(5,27),(5,28),(6,6),(6,27),(6,28),(7,1),(8,1),(9,1),(10,1),(11,1),(12,2),(13,2),(14,2),(15,2),(15,6),(15,27),(15,28),(16,2),(17,3),(18,3),(19,3),(20,3),(21,3),(22,6),(22,27),(22,28),(23,6),(23,27),(23,28),(24,6),(24,27),(24,28),(25,6),(25,27),(25,28),(26,6),(26,27),(26,28),(27,6),(27,27),(27,28),(28,6),(28,27),(28,28),(29,6),(29,27),(29,28),(30,6),(30,27),(30,28);
/*!40000 ALTER TABLE `flower_product` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_01_01_000001_create_admin_users_table',1),(5,'2026_01_01_000002_create_customers_table',1),(6,'2026_01_01_000003_create_product_categories_table',1),(7,'2026_01_01_000004_create_products_table',1),(8,'2026_01_01_000005_create_cart_table',1),(9,'2026_01_01_000006_create_orders_table',1),(10,'2026_01_01_000007_create_order_items_table',1),(11,'2026_01_01_000008_create_gcash_payments_table',1),(12,'2026_01_01_000009_create_contact_messages_table',1),(13,'2026_01_01_000010_create_customization_options_table',1),(14,'2026_01_01_000011_create_customization_presets_table',1),(15,'2026_01_01_000012_create_preset_items_table',1),(16,'2026_01_01_000013_create_saved_customizations_table',1),(17,'2026_01_01_000014_create_service_photos_table',1),(18,'2026_01_01_000015_add_hex_color_to_customization_options_table',2),(19,'2026_01_01_000016_create_customization_option_variants_table',3),(20,'2026_01_01_000017_add_filler_type_to_customization_options_table',3),(21,'2026_08_11_000001_add_remember_token_to_customers_table',4),(22,'2026_08_13_000001_create_category_product_table',5),(23,'2026_08_13_000002_add_product_availability',6);
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
INSERT INTO `orders` VALUES (1,'ORD-20260405-5702',2,5300.00,0.00,NULL,NULL,'cod','completed','delivered','Zone 7 Bangued, Abra',NULL,'2026-12-12','fadarfasdfasd','2026-04-05 06:03:51','2026-05-02 21:58:38',NULL,NULL,NULL),(2,'ORD-20260405-1384',2,1800.00,0.00,900.00,900.00,'gcash','pending_downpayment','cancelled','Zone 7 Bangued, Abra',NULL,'2026-12-12','','2026-04-05 16:14:16','2026-05-02 21:41:01',NULL,NULL,NULL),(3,'ORD-20260502-7968',1,4850.00,0.00,2425.00,2425.00,'gcash','completed','delivered','zone 4 Bangued, Baba redcross',NULL,'2026-12-23','aaaaaaaa','2026-05-02 21:21:15','2026-05-02 21:58:36',NULL,NULL,NULL),(4,'ORD-20260502-7569',1,1800.00,0.00,900.00,900.00,'gcash','completed','cancelled','zone 4 Bangued, Baba redcross',NULL,'2026-12-12','','2026-05-02 21:59:37','2026-05-06 14:21:52','Change of delivery address','','2026-05-06 17:48:35'),(5,'ORD-20260506-2461',1,2300.00,0.00,NULL,NULL,'cod','completed','delivered','zone 4 Bangued, Baba redcross',NULL,'2026-12-12','','2026-05-06 09:51:33','2026-05-06 09:52:04',NULL,NULL,NULL),(6,'ORD-20260506-3807',1,1450.00,0.00,NULL,NULL,'cod','completed','cancelled','zone 4 Bangued, Baba redcross',NULL,'2026-12-12','qqqqqqqqq','2026-05-06 09:52:31','2026-05-06 14:21:50','Change of delivery address','','2026-05-06 17:54:35'),(7,'ORD-20260506-6143',4,1550.00,100.00,775.00,775.00,'gcash','completed','delivered','Zone 4, Arellano St., Bangued, Abra, Bangued, Abra','Bangued','2026-12-12','aaaaaaaaaaaa','2026-05-06 10:30:57','2026-08-11 03:47:35','Change of delivery address','','2026-05-06 18:32:04'),(8,'ORD-20260506-4105',2,1700.00,600.00,850.00,850.00,'gcash','completed','delivered','Bugbog, Abra, Bucay, Abra','Bucay','2026-05-07','qadasdasd','2026-05-06 14:15:53','2026-05-06 14:18:23',NULL,NULL,NULL),(9,'ORD-20260506-3069',2,2600.00,100.00,1300.00,1300.00,'gcash','completed','delivered','Zone 7 Bangued, Abra, Bangued, Abra','Bangued','2026-05-07','qqqq','2026-05-06 14:16:26','2026-05-06 14:18:47',NULL,NULL,NULL),(10,'ORD-20260506-6075',4,1550.00,100.00,775.00,775.00,'gcash','partial','delivered','Zone 4, Arellano St., Bangued, Abra, Bangued, Abra','Bangued','2026-05-07','qqq','2026-05-06 14:17:22','2026-08-03 15:58:07',NULL,NULL,NULL),(11,'ORD-20260803-6307',2,2400.00,100.00,1200.00,1200.00,'gcash','pending_downpayment','confirmed','Zone 7 Bangued, Abra, Bangued, Abra','Bangued','2026-12-23','werwerwer','2026-08-03 15:55:23','2026-08-11 03:47:24',NULL,NULL,NULL),(12,'ORD-20260807-2512',5,2400.00,100.00,NULL,NULL,'cod','pending_cod','preparing','Zone 1 Bangued, Bangued, Abra','Bangued','2026-08-10','Leave at gate','2026-08-07 06:18:11','2026-08-08 12:56:12',NULL,NULL,NULL),(13,'ORD-20260807-5794',6,2400.00,100.00,1200.00,1200.00,'gcash','partial','confirmed','Zone 1 Block 3, Bangued, Abra','Bangued','2026-08-09',NULL,'2026-08-07 06:22:37','2026-08-07 14:23:59',NULL,NULL,NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_categories`
--

LOCK TABLES `product_categories` WRITE;
/*!40000 ALTER TABLE `product_categories` DISABLE KEYS */;
INSERT INTO `product_categories` VALUES (1,'roses','Roses','2026-05-02 21:39:59'),(2,'sunflowers','Sunflowers','2026-05-02 21:39:59'),(3,'tulips','Tulips','2026-05-02 21:39:59'),(4,'seasonal','Seasonal','2026-05-02 21:39:59'),(5,'arrangements','Arrangements','2026-05-02 21:39:59'),(7,'carnation','Carnation','2026-08-11 03:31:58'),(8,'gerbera','Gerbera','2026-08-11 03:33:14');
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
  `image_url` varchar(500) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'Red Romance Bouquet','A classic arrangement of deep red roses, symbolizing love and passion.',2300.00,'rs.jpg',1,'2026-04-04 19:39:27'),(2,'Sunflower Symphony','A vibrant arrangement of cheerful sunflowers to brighten any room with sunny energy.',10500.00,'sf.jpg',1,'2026-04-04 19:39:27'),(3,'Pastel Tulip Elegance','Soft pastel tulips arranged to create an elegant and sophisticated spring display.',1450.00,'t1.jpg',1,'2026-04-04 19:39:27'),(4,'Mixed Garden Bouquet','A beautiful assortment of seasonal flowers for a natural garden feel.',1550.00,'mg.jpg',1,'2026-04-04 19:39:27'),(5,'Tropical Paradise','Exotic flowers that bring a vibrant, tropical feel to any space.',2550.00,'tp.jpg',1,'2026-04-04 19:39:27'),(6,'Sunshine Daisies','Cheerful daisies that spread happiness and brighten your day.',1800.00,'sd.jpg',1,'2026-04-04 19:39:27'),(7,'Pink Perfection','Delicate pink roses that express admiration and gratitude.',1800.00,'pr.jpg',1,'2026-04-04 19:39:27'),(8,'White Innocence','Pure white roses symbolizing purity, innocence, and new beginnings.',1550.00,'wr.jpg',1,'2026-04-04 19:39:27'),(9,'Golden Friendship','Bright yellow roses representing friendship, joy, and caring.',3400.00,'yr.jpg',1,'2026-04-04 19:39:27'),(10,'Lavender Enchantment','Mystical lavender roses that convey enchantment and love at first sight.',5200.00,'lr.jpg',1,'2026-04-04 19:39:27'),(11,'Red Gratitude','Red roses expressing appreciation and thankfulness.',4900.00,'ra.jpg',1,'2026-04-04 19:39:27'),(12,'Golden Sunshine','A vibrant mix of sunflowers that brings warmth and happiness to any space.',1400.00,'s2.jpg',1,'2026-04-04 19:39:27'),(13,'Summer Field','An abundant arrangement that captures the essence of a sunny summer field.',900.00,'s3.jpg',1,'2026-04-04 19:39:27'),(14,'Mini Delight','Charming mini sunflowers perfect for adding a cheerful touch to any room.',1300.00,'s4.jpg',1,'2026-04-04 19:39:27'),(15,'Sunny Mix','A delightful combination of sunflowers and daisies for a bright, cheerful display.',2300.00,'s5.jpg',1,'2026-04-04 19:39:27'),(16,'Autumn Picnic','Rich, warm sunflowers in a box.',2500.00,'s6.jpg',1,'2026-04-04 19:39:27'),(17,'Gradient Delight','A vibrant mix of colorful white and pink that brings joy and cheer to any space.',1600.00,'ct.jpg',1,'2026-04-04 19:39:27'),(18,'Soft Passion','Light pink tulips that symbolize perfect love and passion.',2750.00,'pt.jpg',1,'2026-04-04 19:39:27'),(19,'Blue Royalty','Regal Blue tulips that represent royalty and elegance.',4200.00,'bt.jpg',1,'2026-04-04 19:39:27'),(20,'Yellow Sunshine','Cheerful mixed tulips that bring sunshine and happiness wherever they go.',3000.00,'mt.avif',1,'2026-04-04 19:39:27'),(21,'White Purity','Pure white tulips symbolizing forgiveness, respect, and purity.',2500.00,'wt.jpg',1,'2026-04-04 19:39:27'),(22,'Spring Blossoms','Fresh spring flowers that capture the renewal and beauty of the season.',1100.00,'ss1.jpg',1,'2026-04-04 19:39:27'),(23,'Summer Blooms','Vibrant summer flowers that bring warmth and energy to any space.',1150.00,'ss2.jpg',1,'2026-04-04 19:39:27'),(24,'Autumn Harvest','Rich, warm flowers that capture the cozy essence of autumn.',1350.00,'ss3.jpg',1,'2026-04-04 19:39:27'),(25,'Winter Wonder','Elegant winter flowers that bring beauty to the coldest season.',1200.00,'ss4.jpg',1,'2026-04-04 19:39:27'),(26,'Year-Round Beauty','A timeless arrangement of flowers that brings joy in every season.',2200.00,'ss5.jpg',1,'2026-04-04 19:39:27'),(27,'Classic Elegance','A sophisticated arrangement of exotic tropical flowers for timeless beauty.',2600.00,'a1.jpg',1,'2026-04-04 19:39:27'),(28,'Contemporary Charm','A modern floral arrangement perfect as a stunning centerpiece.',1300.00,'a2.jpg',1,'2026-04-04 19:39:27'),(29,'Lush Garden','An abundant arrangement that brings the beauty of a garden indoors.',1500.00,'a3.jpg',1,'2026-04-04 19:39:27'),(30,'Artistic Display','A creatively designed floral arrangement that showcases artistic flair.',2800.00,'a4.jpg',1,'2026-04-04 19:39:27'),(39,'Rosa Bliss','Charming and vibrant flower that captures the beauty of joy, love, and happiness',1500.00,'flower_1786422531_22ArvTAG.jpg',1,'2026-08-11 04:28:51'),(40,'Rosy Embrace','A dreamy blend of soft pink, peach, coral, and white carnations, wrapped in delicate pink tones and finished with a vibrant orange ribbon',2500.00,'flower_1786427169_ifWTHUF4.jpg',1,'2026-08-11 05:46:09');
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
INSERT INTO `sessions` VALUES ('0j81duBaBUOn6nlVhOoLNx2E3tU84OtWpabHKJke',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoidUV6U3lSNUxzR21RVngxMlNsemtpWGZxdlNycGFjVndrcEFTZzFnMyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoxMToiYWRtaW4ubG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1786616711),('1kEyFV0iNBD722nM19qLkpuvSaKHi1MSrFJXCaKZ',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSWhNS1J6M25vYUxxdmpEbjN0Qzh0b09HbEFONzhYREdNVVJsMUNhMCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wcm9kdWN0cy8xIjtzOjU6InJvdXRlIjtzOjEzOiJwcm9kdWN0cy5zaG93Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1786616710),('1UjhwlzwVuioGNOHYqprEfPlFNnaRLrAWuIZccj7',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiekQ4RlRLRXZRR3drQnMyejlleXNFZVhQUHRtcUlHeGY0anZEUVA5ZCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wcm9kdWN0cyI7czo1OiJyb3V0ZSI7czoxNDoicHJvZHVjdHMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1786620152),('1x22OpvWGrH51O3JdfntgtaHrkxqa93bQGH363Tl',5,'127.0.0.1','curl/8.21.0','YTo3OntzOjY6Il90b2tlbiI7czo0MDoiQ2EyTG9DYTRnVGo1VERFNU5iWVdkNDVISmduMFhHQWUyZ1NEQ2FGdyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9kYXNoYm9hcmQ/dGFiPXByb2R1Y3RzIjtzOjU6InJvdXRlIjtzOjE1OiJhZG1pbi5kYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YToxOntpOjA7czo3OiJtZXNzYWdlIjt9czozOiJuZXciO2E6MDp7fX1zOjUyOiJsb2dpbl9hZG1pbl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjU7czo4OiJhZG1pbl9pZCI7aTo1O3M6MTQ6ImFkbWluX3VzZXJuYW1lIjtzOjU6ImFkbWluIjtzOjc6Im1lc3NhZ2UiO3M6Mjk6IlByb2R1Y3QgZGVsZXRlZCBzdWNjZXNzZnVsbHkhIjt9',1786615850),('3J7NPrcYR0KblVVM0dfXRX0vEDooetQ0KVcSXwGA',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiaXd5ZkVEUTExZUpUS0RIYXc0UnRKdjFuaXRlSGhyaTFvYktyQlJQOSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wcm9kdWN0cy8xIjtzOjU6InJvdXRlIjtzOjEzOiJwcm9kdWN0cy5zaG93Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1786618768),('BmvkOyS2w8exKvyACGXiG44B9Ikc5SfpHHdcDQqU',NULL,'127.0.0.1','curl/8.21.0','YToyOntzOjY6Il90b2tlbiI7czo0MDoibVZXa05hQVRBaFpra2c0SzJpUkVHSm9Ya3pZODJJVVlrV0FPaXRvbyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1786615808),('dGzzub50MFG2zD9buinLKnfgxZ51Cn0BYC7ss0r7',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoib1AxanBMVmkwemRLcm5aYVBrZUxwbVdjNzY1a0xlQzJsbjNVcXJ1eSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jdXN0b21pemUiO3M6NToicm91dGUiO3M6MTU6ImN1c3RvbWl6ZS5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1786616776),('EySWLoj3OfqcqPMp2cYzc5WZ3SKF4aVIp4VpOJAv',NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.133.0 Chrome/148.0.7778.280 Electron/42.8.0 Safari/537.36','YTo2OntzOjY6Il90b2tlbiI7czo0MDoiTXBvWmtJdm03UGZYVjhOVEpreXlnenY5OFlWd3R3OTRWUUVweTBZNyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9jYXJ0L2NvdW50IjtzOjU6InJvdXRlIjtzOjEwOiJjYXJ0LmNvdW50Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MjoibG9naW5fYWRtaW5fNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo1O3M6ODoiYWRtaW5faWQiO2k6NTtzOjE0OiJhZG1pbl91c2VybmFtZSI7czo1OiJhZG1pbiI7fQ==',1786620109),('f9YxVMqHTXUwxR8h0RHc7vKjINNkySUV41ap7HTt',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWWVhdVh6VkptZjlIR0VheG12Y21HZ1FmdTBGTUxOVVc3ZHRua3U5NSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jdXN0b21pemUiO3M6NToicm91dGUiO3M6MTU6ImN1c3RvbWl6ZS5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1786620153),('fXXi9eVEDbCmPxpEZHwmE540oAji1KRH0EK78fBm',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoickM5eXpYbGtNd01EaFAxR25Rd1FBeEVvcVZqS0pPYXo0QmNINU5FMCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jdXN0b21pemUiO3M6NToicm91dGUiO3M6MTU6ImN1c3RvbWl6ZS5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1786618769),('g9r16jJG12AIsaXzhs4cjwIYRfB1be5odIi8qpwf',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiemhwSU5PTVIxejJqcHNKVXpqTm95Qmc3SUd1djhBWENsQXJ6T2JWViI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jdXN0b21pemUiO3M6NToicm91dGUiO3M6MTU6ImN1c3RvbWl6ZS5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1786616775),('gciEuSjlZatzMDLr6csuq9m2gIXyKlfkCsDoADYD',NULL,'127.0.0.1','curl/8.21.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTlR5ME9QTFlnUGliQ09UeEk5cWxRS0dMRGJRcE4yaWdTRUxHd3hKUiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo1MDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2Rhc2hib2FyZD90YWI9cHJvZHVjdHMiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czo1MDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2Rhc2hib2FyZD90YWI9cHJvZHVjdHMiO3M6NToicm91dGUiO3M6MTU6ImFkbWluLmRhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1786615827),('H5n4estLJR6aLVhXYEDKywB0S9UQ0eOVXwFPbnNC',NULL,'127.0.0.1','curl/8.21.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWWU3bXNSVnZTQXNiYVVnMmt1cm1Bd0VKSUM3eFBaekZlRGx2M3VUQiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo1MDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2Rhc2hib2FyZD90YWI9cHJvZHVjdHMiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czo1MDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2Rhc2hib2FyZD90YWI9cHJvZHVjdHMiO3M6NToicm91dGUiO3M6MTU6ImFkbWluLmRhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1786615807),('hm95ipa3FDBAUAZrATooukWmw1JNL5RoSoZbgzcq',7,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','YTo5OntzOjY6Il90b2tlbiI7czo0MDoiYWJTYUxJQlhKNWlBNzlUQ3dWeG9zZ2VPMVlORHRnWWVKdDVMb2l1QiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9jYXJ0L2NvdW50IjtzOjU6InJvdXRlIjtzOjEwOiJjYXJ0LmNvdW50Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NztzOjEzOiJjdXN0b21lcl9uYW1lIjtzOjE5OiJDb2J5IEJhcmJhIExhYnVndWVuIjtzOjE0OiJjdXN0b21lcl9lbWFpbCI7czoxNDoidGVzdEBnbWFpbC5jb20iO3M6NTI6ImxvZ2luX2FkbWluXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NTtzOjg6ImFkbWluX2lkIjtpOjU7czoxNDoiYWRtaW5fdXNlcm5hbWUiO3M6NToiYWRtaW4iO30=',1786620109),('iLmy9PiQgLH9WXnsBgOZLG2Y7L7uiIe5Pe7R16W8',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoia01reFY5WEwyd2pjTzRVZFpnOXpScVJON1ZYY2ttZUprajNKTmdnRiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wcm9kdWN0cy8xIjtzOjU6InJvdXRlIjtzOjEzOiJwcm9kdWN0cy5zaG93Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1786620152),('IXw9Kn01JGaK47myPUFjSzQFpZ1STXoNr31gN8kL',5,'127.0.0.1','curl/8.21.0','YTo4OntzOjY6Il90b2tlbiI7czo0MDoiTHl3M0RCTkJiOHVSUTFONmJ5NGN4VGRQSGJjWWxOYWhJMHA5VzJ4eCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6MTU6ImFkbWluLmRhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjE6e2k6MDtzOjc6Im1lc3NhZ2UiO31zOjM6Im5ldyI7YTowOnt9fXM6NTI6ImxvZ2luX2FkbWluXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NTtzOjg6ImFkbWluX2lkIjtpOjU7czoxNDoiYWRtaW5fdXNlcm5hbWUiO3M6NToiYWRtaW4iO3M6MTA6ImFjdGl2ZV90YWIiO3M6MTM6ImN1c3RvbWl6YXRpb24iO3M6NzoibWVzc2FnZSI7czoyODoiRmxvd2VyIGRlbGV0ZWQgc3VjY2Vzc2Z1bGx5ISI7fQ==',1786618751),('KOyhxVwZK3290bpxmP4gqB3tBUuyVed1Li3xzDkw',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoieEdrQ2Y2M090U01mdFFnV0gxVGswQ1JIZk04RG5OMnJ6NVgwWTlobSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wcm9kdWN0cyI7czo1OiJyb3V0ZSI7czoxNDoicHJvZHVjdHMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1786616710),('lD6P48nIMtbP8iicQMDqUIVIF5kGLHuABIRd4O54',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiaGlIdW1ha0hodFhOaXJVdUVMNHpQMm05d29mTnRjMnZ2RjYyUGVaRSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wcm9kdWN0cyI7czo1OiJyb3V0ZSI7czoxNDoicHJvZHVjdHMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1786616722),('NjhdBuuJxZmalYIGuOfO1X9UzMqHR3SRqtEHPzpt',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMVhtUDRCNTltWjB4YmtSWFlEV2xsYXQ2eVRZRm50Z0tBSWs1cVFkOCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoxMToiYWRtaW4ubG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1786620153),('Nwc4UBW146XqswGmuX6C4oFt0caP7WrO2npbkZIK',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoia21PUHNuYlhBTXc0d1NBQkxvTzJ2WUF6YUtUQnhCbzZpeWlCSTd4ZiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1786620152),('OjfkziiDrO2JCMla9xUJgAfQhGp0pChJYxpaoJoo',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSFVMRWJhTjVBOE1UOUdrbDl5aUpzV0lpV0hiM1dFSVZaYWl3YXpqdCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jdXN0b21pemUiO3M6NToicm91dGUiO3M6MTU6ImN1c3RvbWl6ZS5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1786616711),('OZNd5oYN1JO7yPqmBD2PWa4s556MwXimkgQ1KtER',5,'127.0.0.1','curl/8.21.0','YTo2OntzOjY6Il90b2tlbiI7czo0MDoiU1owSnJ1ZW9Fbm9XMjVhdzhlUmlXa3RRSFRWWWRSbzR1QkZVRkVBTCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6MTU6ImFkbWluLmRhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTI6ImxvZ2luX2FkbWluXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NTtzOjg6ImFkbWluX2lkIjtpOjU7czoxNDoiYWRtaW5fdXNlcm5hbWUiO3M6NToiYWRtaW4iO30=',1786616766),('OzPbb16zgO0Mb1uJ0CUm6gkMzLDMoGI60Bk5wC0s',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiU243NWVCbWNIT0x0dFN2Vzdyd1JWaG1oM0U3dFJ1MDRuT3RtVVBhWSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoxMToiYWRtaW4ubG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1786616758),('pxwvJIUGumIZGDMdSwbfv8Fh3UTlHeoEMdsoQghX',NULL,'127.0.0.1','curl/8.21.0','YToyOntzOjY6Il90b2tlbiI7czo0MDoid0VubTh3YjFDbTVyeHhGUGZJdkZmcVJPOUtGdzhXV3pIOXdCU0VURiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1786616782),('sicgy6Ilp45M8Bwf5sTG8KwLG353tA7QJmbLDkOz',NULL,'127.0.0.1','curl/8.21.0','YToyOntzOjY6Il90b2tlbiI7czo0MDoibnFIZWRsZG1HRnQ4R1dMSjNuN0lFU0RKVldWOGhFc29KS3ZwRjZZaCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1786616783),('sY4VjWksikRekehrYKXvpswvvUWWfa5keYNSyrJc',NULL,'127.0.0.1','curl/8.21.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiYkdnaW5ienBwYjZkY3NCYkpzcGVHZTJ1OGEwdlNsMlJXTW9YVFZiRCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MzoidXJsIjthOjE6e3M6ODoiaW50ZW5kZWQiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9kYXNoYm9hcmQiO319',1786616747),('tX2AEOJX9tug0f9F3rU2j8MfXBuHvscIPDkKJYvs',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWU1NQ2huWm54b1NPemU0Z29sVUFhajlJVW00ZDhmelJVY0d0dzZSTSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1786616709),('UA6ipKI2Hn8oa6ZyEQo13kGQTozthikR76R4IUym',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiV082WWx5bDNNeGlUWXplZFEzMnZzTEQ1TDdGZ1BWQWhxQ2tsbnlUUCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1786616723),('UEmFRD9Dr14QlDJc8MZcMSUPprrjyfg5qk8x08G5',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoia1lMSWd0SjdDRXM5eFA2QnJLc1dBUkZKS1VJUU5TbWt2ckdWRklqMiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wcm9kdWN0cyI7czo1OiJyb3V0ZSI7czoxNDoicHJvZHVjdHMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1786616727),('UJvpBPx18QvzMtGFLBTAG7AEwgyX0p4lFE7FEjWR',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRDVVSVVFcXF1NElDT3BLRnZUSGUwNXBWSVJXVFBjamxlOXpTa0I4NyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1786616704),('uYosfMQcahoPJpF8zGoBXH6VfaekB8MRxegTnchr',5,'127.0.0.1','curl/8.21.0','YTo4OntzOjY6Il90b2tlbiI7czo0MDoiVmhtbUM2VUpEb3Q1Q1BTWnloQkw0V0swenh5R2dZSXBDTVIyMVdXZiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6MTU6ImFkbWluLmRhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjE6e2k6MDtzOjc6Im1lc3NhZ2UiO31zOjM6Im5ldyI7YTowOnt9fXM6NTI6ImxvZ2luX2FkbWluXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NTtzOjg6ImFkbWluX2lkIjtpOjU7czoxNDoiYWRtaW5fdXNlcm5hbWUiO3M6NToiYWRtaW4iO3M6MTA6ImFjdGl2ZV90YWIiO3M6MTM6ImN1c3RvbWl6YXRpb24iO3M6NzoibWVzc2FnZSI7czoyODoiRmlsbGVyIGRlbGV0ZWQgc3VjY2Vzc2Z1bGx5ISI7fQ==',1786620144),('VxhIcz4N0OXFJ76dJgKoEA5DGxzACasaYRcOSDfT',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiaXBXN1NuSDRKb1ZlQjlqY0l4ZUN5UUdSb1lpbzZOSXg4NEM3cUxqVSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1786620102),('w0PdmbELqypxBCCgTrdT0JgXDWpOCyvwthTmNatS',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWEVtbnpkTmhVb1dHUU9iSHB3MG9yRHJJRU15WFFJYTNDeHdUTHQxNSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wcm9kdWN0cyI7czo1OiJyb3V0ZSI7czoxNDoicHJvZHVjdHMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1786618768),('Y78y5m1jhDQFehq4PWy8HnbggJH56w4W2WRvHeVl',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoibkV3eWd6cUVUMmJtbUNZc09ic1FqNXFOU2xiOHd3clQwMXJ6R0czUyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1786618768),('yenBZ4gsFF3heB1dGtTQ2ODUDKaJxmtKZV4Amipb',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoibmtURUZVelV6R3hlcldXdnJEOUtEaElUeXQ4TXZzZkZwOXdrUXZqQSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wcm9kdWN0cy80IjtzOjU6InJvdXRlIjtzOjEzOiJwcm9kdWN0cy5zaG93Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1786616722);
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

-- Dump completed on 2026-08-13 19:22:34
