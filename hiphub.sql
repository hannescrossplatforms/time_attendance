-- MySQL dump 10.13  Distrib 5.5.40, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: hiphub
-- ------------------------------------------------------
-- Server version	5.5.40-0ubuntu0.12.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `brands`
--

DROP TABLE IF EXISTS `brands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brands` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remotedb_id` int(11) NOT NULL,
  `countrie_id` int(11) NOT NULL,
  `welcome` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `uru` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ssid` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `limit` int(11) NOT NULL,
  `hipwifi` int(11) NOT NULL,
  `hiprm` int(11) NOT NULL,
  `hipjam` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `brands_name_unique` (`name`),
  UNIQUE KEY `brands_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `brands`
--

LOCK TABLES `brands` WRITE;
/*!40000 ALTER TABLE `brands` DISABLE KEYS */;
INSERT INTO `brands` VALUES (1,'Heineken','Heineken Brand','HEINEK',1,1,'          ','          ','          ',0,1,0,0,'2015-02-20 11:46:09','2015-02-20 12:22:23'),(2,'SABHansa','SAB Hansa Brand','XHANSA',2,1,'          asdf','          w','          asdf',0,1,0,0,'2015-02-20 11:46:09','2015-02-25 13:20:29'),(3,'  SABCastle  ','SAB Castle Brand','CASTLE',2,1,'      ','      ','      ',0,1,0,0,'2015-02-20 11:46:09','2015-02-22 05:06:17'),(4,'Pernod','Pernod Brand','PERNOD',1,1,'  ','  ','  ',0,1,0,0,'2015-02-20 11:46:09','2015-02-22 05:06:41'),(5,'Coke','','XXCOKE',1,1,'  ','  ','  ',0,1,0,0,'2015-02-20 12:18:51','2015-02-22 05:07:05'),(9,'Absolut VODKA','','ABSOLU',1,1,'www','uuu','sss',200,1,0,0,'2015-02-24 17:18:48','2015-02-24 17:19:20'),(12,'Kauai','','XKAUAI',1,1,'Welcome to Kauai Free Wifi','http://www.kauai.co.za','Kauai',200,1,1,0,'2015-03-05 05:49:30','2015-03-05 05:49:30'),(13,'Hudsons','','HUDSON',1,1,'Welcome to Hudsons Wifi','http://www.bing.com','Hudsons',200,1,0,0,'2015-03-05 08:13:36','2015-03-05 08:13:36'),(14,'Vida e Cafe','','XXVIDA',1,1,'Welcome to Vida Wifi','http://www.vidaecaffe.co.za','Vida',200,1,0,0,'2015-03-05 08:15:27','2015-03-05 08:15:27'),(15,'Independent','','INDPNT',1,1,'Welcom','http://www.bing.com','Independent',200,1,0,0,'2015-03-05 08:16:49','2015-03-05 08:16:49'),(16,'Bungalow','','BUNGLW',1,1,'Welcome','http://www.bing.com','Bungalow',200,1,0,0,'2015-03-05 08:17:54','2015-03-05 08:17:54'),(17,'Wimpy','','XWIMPY',1,1,'Welcome','http://www.bing.com','Wimpy',200,1,0,0,'2015-03-05 08:19:21','2015-03-05 08:19:21'),(18,'Trvest','','TRVEST',1,1,'Welcome','http://www.bing.com','Trvest',200,1,0,0,'2015-03-05 08:20:38','2015-03-05 08:20:38'),(19,'Alphen','','ALPHEN',1,1,'Welcome','http://www.bing.com','Alphen',200,1,0,0,'2015-03-05 08:22:02','2015-03-05 08:22:02'),(20,'Pepenero','','PEPENO',1,1,'Welcome','http://www.bing.com','Pepenero',200,1,0,0,'2015-03-05 08:23:02','2015-03-05 08:23:02'),(21,'Parnga','','PARNGA',1,1,'Welcome','http://www.bing.com','Parnga',200,1,0,0,'2015-03-05 08:24:00','2015-03-05 08:24:00'),(22,'Zenzero','','ZENZRO',1,1,'Welcome','http://www.bing.com','Zenzero',200,1,0,0,'2015-03-05 08:25:09','2015-03-05 08:25:09'),(23,'Hemelhuis','','HEMELH',1,1,'Welcome','http://www.bing.com','Hemel',200,1,0,0,'2015-03-05 08:26:01','2015-03-05 08:26:01'),(24,'Nandos','','NANDOS',1,1,'Welcome','http://www.nandos.co.za','Nandos',200,1,0,0,'2015-03-05 08:26:45','2015-03-05 08:26:45'),(25,'Moving Tactics','','MOVING',1,1,'Welcome','http://www.bing.com','Moving',200,1,0,0,'2015-03-05 08:27:37','2015-03-05 08:27:37');
/*!40000 ALTER TABLE `brands` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `brands_countries`
--

DROP TABLE IF EXISTS `brands_countries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brands_countries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `brand_id` int(11) NOT NULL,
  `countrie_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `brands_countries`
--

LOCK TABLES `brands_countries` WRITE;
/*!40000 ALTER TABLE `brands_countries` DISABLE KEYS */;
/*!40000 ALTER TABLE `brands_countries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `brands_servers`
--

DROP TABLE IF EXISTS `brands_servers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brands_servers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `brand_id` int(11) NOT NULL,
  `server_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `brands_servers`
--

LOCK TABLES `brands_servers` WRITE;
/*!40000 ALTER TABLE `brands_servers` DISABLE KEYS */;
/*!40000 ALTER TABLE `brands_servers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cities`
--

DROP TABLE IF EXISTS `cities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cities` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `province_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cities`
--

LOCK TABLES `cities` WRITE;
/*!40000 ALTER TABLE `cities` DISABLE KEYS */;
INSERT INTO `cities` VALUES (1,'Johannesburg','JNB','1','2015-02-20 11:46:11','2015-02-20 11:46:11'),(2,'Pretoria','PTY','1','2015-02-20 11:46:11','2015-02-20 11:46:11'),(3,'Durban','DUR','2','2015-02-20 11:46:11','2015-02-20 11:46:11'),(4,'Cape Town','CPT','3','2015-02-20 11:46:11','2015-02-20 11:46:11'),(5,'London','LHR','4','2015-02-20 11:46:11','2015-02-20 11:46:11'),(6,'Birmingham','BHX','5','2015-02-20 11:46:11','2015-02-20 11:46:11'),(7,'Newcastle','BHX','6','2015-02-20 11:46:12','2015-02-20 11:46:12'),(8,'Bloemfontein','BFN','7','0000-00-00 00:00:00','0000-00-00 00:00:00'),(9,'George','GRJ','3','0000-00-00 00:00:00','0000-00-00 00:00:00'),(10,'East London','ELS','12','0000-00-00 00:00:00','0000-00-00 00:00:00'),(11,'Port Elizabeth','PLZ','12','0000-00-00 00:00:00','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `cities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `countries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `region` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `countries_name_unique` (`name`),
  UNIQUE KEY `countries_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `countries`
--

LOCK TABLES `countries` WRITE;
/*!40000 ALTER TABLE `countries` DISABLE KEYS */;
INSERT INTO `countries` VALUES (1,'South Africa','ZA','','2015-02-20 11:46:10','2015-02-20 11:46:10'),(2,'Kuwait','KW','','2015-02-20 11:46:10','2015-02-20 11:46:10'),(3,'United Kingdom','UK','','2015-02-20 11:46:10','2015-02-20 11:46:10');
/*!40000 ALTER TABLE `countries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `isps`
--

DROP TABLE IF EXISTS `isps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `isps` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `isps`
--

LOCK TABLES `isps` WRITE;
/*!40000 ALTER TABLE `isps` DISABLE KEYS */;
INSERT INTO `isps` VALUES (1,'Hipzone','HIP','0000-00-00 00:00:00','0000-00-00 00:00:00'),(2,'Wefi','WFI','0000-00-00 00:00:00','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `isps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `levels`
--

DROP TABLE IF EXISTS `levels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `levels` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `levels_code_unique` (`code`),
  UNIQUE KEY `levels_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `levels`
--

LOCK TABLES `levels` WRITE;
/*!40000 ALTER TABLE `levels` DISABLE KEYS */;
INSERT INTO `levels` VALUES (1,'superadmin','superadmin','Has full rights to administer system','2015-02-20 11:46:13','2015-02-20 11:46:13'),(2,'admin','Admin','Has rights to administer ...','2015-02-20 11:46:13','2015-02-20 11:46:13'),(3,'reseller','Reseller','Has rights to administer ...','2015-02-20 11:46:13','2015-02-20 11:46:13'),(4,'brandadmin','Brand Admin','Has  rights to administer brands','2015-02-20 11:46:13','2015-02-20 11:46:13');
/*!40000 ALTER TABLE `levels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `medias`
--

DROP TABLE IF EXISTS `medias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `medias` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `target` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `isp_id` int(11) NOT NULL DEFAULT '1',
  `brand_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dt_registration` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mb_registration` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `countrie_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `province_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `citie_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `venue_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `location` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `medias`
--

LOCK TABLES `medias` WRITE;
/*!40000 ALTER TABLE `medias` DISABLE KEYS */;
INSERT INTO `medias` VALUES (19,'',1,'12',NULL,NULL,'1','0','','','HIPXKAUAIXXXXXXXXXXXXXXXXZA','2015-03-06 08:57:03','2015-03-06 08:57:03'),(20,'',1,'12',NULL,NULL,'1','3','0','','HIPXKAUAIXXXXXXXXXXXWCXXXZA','2015-03-06 08:57:28','2015-03-06 08:57:28'),(21,'',1,'12',NULL,NULL,'1','3','4','Please select','HIPXKAUAIXXXXXXXXXXXWCCPTZA','2015-03-06 08:57:52','2015-03-06 08:57:52'),(22,'',1,'12',NULL,NULL,'1','3','4','182','HIPXKAUAIXXSEAPOINTXWCCPTZA','2015-03-06 08:58:27','2015-03-06 08:58:27'),(23,'',1,'12',NULL,NULL,'1','12','0','','HIPXKAUAIXXXXXXXXXXXECXXXZA','2015-03-06 09:09:25','2015-03-06 09:09:25'),(25,'',1,'12',NULL,NULL,'1','1','1','178','HIPXKAUAIXXXXCRESTAXGTJNBZA','2015-03-09 09:08:38','2015-03-09 09:08:38'),(26,'',1,'12',NULL,NULL,'1','3','4','212','HIPXKAUAIGREENPOINTXWCCPTZA','2015-03-09 09:13:50','2015-03-09 09:13:50'),(27,'',1,'12',NULL,NULL,'1','1','0','','HIPXKAUAIXXXXXXXXXXXGTXXXZA','2015-03-09 09:34:07','2015-03-09 09:34:07'),(28,'',2,'2',NULL,NULL,'1','0','','','WFIXHANSAXXXXXXXXXXXXXXXXZA','2015-03-10 08:00:22','2015-03-10 08:00:22'),(29,'',2,'3',NULL,NULL,'1','0','','','WFICASTLEXXXXXXXXXXXXXXXXZA','2015-03-10 08:13:50','2015-03-10 08:13:50'),(30,'',2,'3',NULL,NULL,'1','1','0','Please select','WFICASTLEXXXXXXXXXXXGTXXXZA','2015-03-10 08:16:42','2015-03-10 08:16:42'),(31,'',2,'3',NULL,NULL,'1','3','0','','WFICASTLEXXXXXXXXXXXWCXXXZA','2015-03-10 08:26:22','2015-03-10 08:26:22'),(32,'',2,'3',NULL,NULL,'1','7','8','Please select','WFICASTLEXXXXXXXXXXXFSBFNZA','2015-03-10 08:43:43','2015-03-10 08:43:43'),(33,'',2,'3',NULL,NULL,'1','1','2','Please select','WFICASTLEXXXXXXXXXXXGTPTYZA','2015-03-10 08:46:55','2015-03-10 08:46:55'),(34,'',2,'13',NULL,NULL,'1','0','','','WFIHUDSONXXXXXXXXXXXXXXXXZA','2015-03-10 09:13:35','2015-03-10 09:13:35'),(35,'',2,'13',NULL,NULL,'1','1','0','','WFIHUDSONXXXXXXXXXXXGTXXXZA','2015-03-10 09:15:24','2015-03-10 09:15:24'),(36,'',2,'3',NULL,NULL,'1','3','9','0','WFICASTLEXXXXXXXXXXXWCGRJZA','2015-03-10 09:20:35','2015-03-10 09:20:35'),(39,'',2,'3',NULL,NULL,'1','3','4','260','WFICASTLEUMTATATAVNXWCCPTZA','2015-03-10 10:00:34','2015-03-10 10:00:34'),(40,'',1,'1',NULL,NULL,'1','2','0','','HIPHEINEKXXXXXXXXXXXNLXXXZA','2015-03-10 17:29:28','2015-03-10 17:29:28'),(41,'',1,'1',NULL,NULL,'1','10','0','','HIPHEINEKXXXXXXXXXXXNWXXXZA','2015-03-10 17:38:49','2015-03-10 17:38:49');
/*!40000 ALTER TABLE `medias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES ('2015_01_07_114839_create_users_table',1),('2015_01_16_153428_create_permissions_table',1),('2015_01_16_154535_create_users_permissions_table',1),('2015_01_17_073253_create_brands_table',1),('2015_01_17_073323_create_users_brands_table',1),('2015_01_19_120305_create_users_products_table',1),('2015_01_19_120438_create_products_table',1),('2015_01_30_132511_create_countries_table',1),('2015_01_31_080708_create_brands_countries_table',1),('2015_02_05_150333_create_servers_table',1),('2015_02_05_150744_create_brands_servers_table',1),('2015_02_11_074506_create_levels_table',1),('2015_02_13_091829_create_venues_table',1),('2015_02_13_132149_create_cities_table',1),('2015_02_13_132203_create_provinces_table',1),('2015_02_23_110803_create_targets_table',2),('2015_02_23_113528_create_medias_table',3),('2015_03_04_103826_create_databases_table',4),('2015_03_04_110329_create_remotedbs_table',5),('2015_03_09_084339_create_isps_table',6);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'ques_rw','Add/remove Questions','2015-02-20 11:46:08','2015-02-20 11:46:08'),(2,'media_rw','Manage media backgrounds','2015-02-20 11:46:08','2015-02-20 11:46:08'),(3,'uru_rw','Change User Redirect URL','2015-02-20 11:46:09','2015-02-20 11:46:09'),(4,'rep_rw','Access Reports Server','2015-02-20 11:46:09','2015-02-20 11:46:09');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'HipWifi','HipWifi Desc','2015-02-20 11:46:09','2015-02-20 11:46:09'),(2,'HipRM','HipRM Desc','2015-02-20 11:46:09','2015-02-20 11:46:09'),(3,'HipJAM','HipJAM Desc','2015-02-20 11:46:10','2015-02-20 11:46:10');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `provinces`
--

DROP TABLE IF EXISTS `provinces`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `provinces` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `countrie_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `provinces`
--

LOCK TABLES `provinces` WRITE;
/*!40000 ALTER TABLE `provinces` DISABLE KEYS */;
INSERT INTO `provinces` VALUES (1,'Gauteng','XGT','1','2015-02-20 11:46:10','2015-02-20 11:46:10'),(2,'Kwazulu Natal','XNL','1','2015-02-20 11:46:10','2015-02-20 11:46:10'),(3,'Western Cape','XWC','1','2015-02-20 11:46:10','2015-02-20 11:46:10'),(4,'Greater London','XGL','3','2015-02-20 11:46:11','2015-02-20 11:46:11'),(5,'West Midlands','XWM','3','2015-02-20 11:46:11','2015-02-20 11:46:11'),(7,'Free State','XFS','1','0000-00-00 00:00:00','0000-00-00 00:00:00'),(8,'Limpopo','XLP','1','0000-00-00 00:00:00','0000-00-00 00:00:00'),(9,'Mpumalanga','XMP','1','0000-00-00 00:00:00','0000-00-00 00:00:00'),(10,'North West','XNW','1','0000-00-00 00:00:00','0000-00-00 00:00:00'),(11,'Northern Cape','XNC','1','0000-00-00 00:00:00','0000-00-00 00:00:00'),(12,'Eastern Cape','XEC','1','0000-00-00 00:00:00','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `provinces` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `remotedbs`
--

DROP TABLE IF EXISTS `remotedbs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `remotedbs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dbname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `remotedbs`
--

LOCK TABLES `remotedbs` WRITE;
/*!40000 ALTER TABLE `remotedbs` DISABLE KEYS */;
INSERT INTO `remotedbs` VALUES (1,'HipLogger','hiplogger.hip-zone.co.za','radius_hiplogger','radius','0000-00-00 00:00:00','0000-00-00 00:00:00'),(2,'Hipwifi Staging','wifistag.hipzone.co.za','radius_wifistag','radius','0000-00-00 00:00:00','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `remotedbs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `servers`
--

DROP TABLE IF EXISTS `servers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `servers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hostname` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `remotedb_id` int(11) NOT NULL,
  `notes` varchar(254) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `servers_hostname_unique` (`hostname`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `servers`
--

LOCK TABLES `servers` WRITE;
/*!40000 ALTER TABLE `servers` DISABLE KEYS */;
INSERT INTO `servers` VALUES (1,'server1.hipzone.co.za',1,'','2015-02-20 11:46:12','2015-03-03 12:05:12'),(2,'server2.hipzone.co.za',1,'','2015-02-20 11:46:12','2015-02-20 11:46:12'),(3,'server3.hipzone.co.za',2,'qwrqwrwq','2015-02-20 11:46:12','2015-02-20 11:46:12');
/*!40000 ALTER TABLE `servers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `systemconfig`
--

DROP TABLE IF EXISTS `systemconfig`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `systemconfig` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `systemconfig`
--

LOCK TABLES `systemconfig` WRITE;
/*!40000 ALTER TABLE `systemconfig` DISABLE KEYS */;
INSERT INTO `systemconfig` VALUES (1,'assetsdir','/home/clickheat/assets/'),(2,'assetsserver','http://localhost/assets/');
/*!40000 ALTER TABLE `systemconfig` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fullname` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `level_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'superadmin User','superadmin@hipzone.com','$2y$10$YH502mEqNVsqD9OPF34oyOHfUIJASFUNh84dFbhNUsCxE2V/6s9D.','superadmin','MXIjiNYclYEBRtMCV2qZ2pz0focGrnwdY7jZ07NLbOnFMwD3XtRKoDA10NV6','2015-02-20 11:46:08','2015-03-05 10:44:09'),(2,'Admin User','adminuser@hipzone.com','$2y$10$8E1dUlvKcNQikNO3svfFjeO.WjoMek/ldZqq5RbYYxR6opMGqyWxC','admin','qDnfU87UjIS3GhZMjTEW0mQLkAoaVhdEU4uFw3l1yIfqiyTy3eXmQ6cr64IL','2015-02-20 11:46:08','2015-02-25 17:18:40'),(3,'Reseller User','reselleruser@hipzone.com','$2y$10$GEOMYg60htoKMQuRpJJ8OO4bwB9bb91DUj755C/9.ENVwqnYiPFXW','reseller','0','2015-02-20 11:46:08','2015-02-20 11:46:08'),(12,'Hipwifi Brand Admin User','hipwifibrandadminuser@hipzone.com','$2y$10$t5C5zJlHZ/.mqPrL3UsxquYaUk1hoPpK8Wbk3qgVSRr66FRrFsHhS','brandadmin','','2015-02-25 17:24:17','2015-02-25 17:24:17');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_brands`
--

DROP TABLE IF EXISTS `users_brands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_brands` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_brands`
--

LOCK TABLES `users_brands` WRITE;
/*!40000 ALTER TABLE `users_brands` DISABLE KEYS */;
INSERT INTO `users_brands` VALUES (22,12,1,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(23,12,2,'0000-00-00 00:00:00','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `users_brands` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_permissions`
--

DROP TABLE IF EXISTS `users_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_permissions`
--

LOCK TABLES `users_permissions` WRITE;
/*!40000 ALTER TABLE `users_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `users_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_products`
--

DROP TABLE IF EXISTS `users_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_products` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_products`
--

LOCK TABLES `users_products` WRITE;
/*!40000 ALTER TABLE `users_products` DISABLE KEYS */;
INSERT INTO `users_products` VALUES (10,12,1,'0000-00-00 00:00:00','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `users_products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `venues`
--

DROP TABLE IF EXISTS `venues`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `venues` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sitename` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `location` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `medialocation` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remotedb_id` int(11) NOT NULL,
  `countrie_id` int(11) NOT NULL,
  `province_id` int(11) NOT NULL,
  `citie_id` int(11) NOT NULL,
  `isp_id` int(11) NOT NULL DEFAULT '1',
  `brand_id` int(11) NOT NULL,
  `macaddress` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `latitude` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `longitude` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `server_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `contact` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `notes` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `venues_location_unique` (`location`)
) ENGINE=InnoDB AUTO_INCREMENT=264 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `venues`
--

LOCK TABLES `venues` WRITE;
/*!40000 ALTER TABLE `venues` DISABLE KEYS */;
INSERT INTO `venues` VALUES (174,'Kauai vvvvvvvvv','HIPXKAUAIXKILLARNEYXGTJNBZA','HIPXKAUAIXXXXXXXXXXXGTXXXZA',1,1,1,1,1,12,'00-23-69-21-9E-EF','','','later','1','','','2015-03-05 10:40:07','2015-03-10 17:38:50'),(175,'Kauai Ort','HIPXKAUAIXXXORTAMBOXGTJNBZA','HIPXKAUAIXXXXXXXXXXXGTXXXZA',1,1,1,1,1,12,'',NULL,NULL,NULL,'1',NULL,NULL,'2015-03-05 10:40:07','2015-03-10 17:38:50'),(176,'Kauai Wedgeeeeee','HIPXKAUAIXXXXXWEDGEXGTJNBZA','HIPXKAUAIXXXXXXXXXXXGTXXXZA',1,1,1,1,1,12,'00-1E-E5-84-B8-28','','','later','1','','','2015-03-05 10:40:07','2015-03-10 17:38:50'),(177,'Kauai Rosebankab','HIPXKAUAIXXROSEBANKXGTJNBZA','HIPXKAUAIXXXXXXXXXXXGTXXXZA',1,1,1,1,1,12,'00-23-69-21-C0-F1','ddd','sss','Shop 6, Regents Place,Mutual Gardens,Craddock Avenue,Rosebank2196','1','234234','asdfasf','2015-03-05 10:40:07','2015-03-10 17:38:50'),(178,'Kauai Cresta','HIPXKAUAIXXXXCRESTAXGTJNBZA','HIPXKAUAIXXXXCRESTAXGTJNBZA',1,1,1,1,1,12,'00-23-69-21-BD-B2','26.12969','27.97187','Shop UO37C \r\nCresta Shopping Centre \r\nCorner of Beyers Naude Drive & Weltevrede Road \r\nRandburg ','1','011 476 5727',NULL,'2015-03-05 10:40:07','2015-03-10 17:38:50'),(179,'Kauai Benmore','HIPXKAUAIXXXBENMOREXGTJNBZA','HIPXKAUAIXXXXXXXXXXXGTXXXZA',1,1,1,1,1,12,'00-23-69-21-E0-C2','26.099470','28.04971','Benmore Shopping Centre \r\nGreystone Drive, \r\nBenmore Gardens Sandton ','1','011 883 7065',NULL,'2015-03-05 10:40:08','2015-03-10 17:38:50'),(180,'Kauai Wfront','HIPXKAUAIWATERFRONTXWCCPTZA','HIPXKAUAIXXXXXXXXXXXWCCPTZA',1,1,3,4,1,12,'',NULL,NULL,NULL,'1',NULL,NULL,'2015-03-05 10:40:08','2015-03-10 17:38:50'),(181,'Kauai Constantia','HIPXKAUAICONSTANTIAXWCCPTZA','HIPXKAUAIXXXXXXXXXXXWCCPTZA',1,1,3,4,1,12,'00-1E-E5-84-C1-61','34.02006','18.44534','Shop 27A,\r\nThe Constantia Village,\r\nCorner Main & Spaanschemat Road,\r\nConstantia\r\n7806\r\n','1','021 794 7705',NULL,'2015-03-05 10:40:08','2015-03-10 17:38:50'),(182,'Kauai Seapoint','HIPXKAUAIXXSEAPOINTXWCCPTZA','HIPXKAUAIXXSEAPOINTXWCCPTZA',1,1,3,4,1,12,'00-1E-E5-84-B5-E8','','','','1','',NULL,'2015-03-05 10:40:08','2015-03-10 17:38:51'),(183,'Kauai Willowbridge','HIPXKAUAIWILLOBRDGEXWCCPTZA','HIPXKAUAIXXXXXXXXXXXWCCPTZA',1,1,3,4,1,12,'00-23-69-21-A0-42','','','Shop G 05,\r\nWillowbridge Centre, \r\nCarl Cronje Drive,\r\nTygervalley,\r\nBellville\r\n7530       \r\n','1','',NULL,'2015-03-05 10:40:08','2015-03-10 17:38:51'),(184,'Kauai Bayside','HIPXKAUAIXXXBAYSIDEXWCCPTZA','HIPXKAUAIXXXXXXXXXXXWCCPTZA',1,1,3,4,1,12,'00-23-69-F9-DF-83','','','Shop 1,\r\nBayside Centre,\r\nCorner of Blaauberg & West Coast Road,\r\nTable View\r\n7441        \r\n','1','',NULL,'2015-03-05 10:40:08','2015-03-10 17:38:51'),(185,'Kauai Stellenbosch','HIPXKAUAISTELLBOSCHXWCCPTZA','HIPXKAUAIXXXXXXXXXXXWCCPTZA',1,1,3,4,1,12,'00-23-69-F9-E2-17','','','Shop 6,\r\nDe Wet Centre,\r\nCorner Bird & Church Street,\r\nStellenbosch       \r\n7600\r\n','1','',NULL,'2015-03-05 10:40:09','2015-03-10 17:38:51'),(186,'Kauai Bedford','HIPXKAUAIBEDFORDSQRXGTJNBZA','HIPXKAUAIXXXXXXXXXXXGTXXXZA',1,1,1,1,1,12,'00-23-69-21-9E-74','26.18802','28.12289','Shop U48 \r\nBedford Centre \r\nSmith Road \r\nBedford Gardens ','1','011 615 0230',NULL,'2015-03-05 10:40:09','2015-03-10 17:38:51'),(187,'Kauai Camps Bay','HIPXKAUAIXXCAMPSBAYXWCCPTZA','HIPXKAUAIXXXXXXXXXXXWCCPTZA',1,1,3,4,1,12,'00-23-69-21-98-E6','33.951044','18.378442','Shop 2 Isaacs Corner \r\n55-61 Victoria Road \r\nCamps Bay ','1','021 438 4607',NULL,'2015-03-05 10:40:09','2015-03-10 17:38:51'),(188,'Kauai Sandton','HIPXKAUAIXXXSANDTONXGTJNBZA','HIPXKAUAIXXXXXXXXXXXGTXXXZA',1,1,1,1,1,12,'00-23-69-21-E1-0D','26.10800 ','28.05382','Shop 11 \r\nSandton City Shopping Centre \r\nRivonia Rd \r\nSandton ','1','011 784 3868',NULL,'2015-03-05 10:40:09','2015-03-10 17:38:51'),(189,'Kauai Canalwalk','HIPXKAUAIXCANALWALKXWCCPTZA','HIPXKAUAIXXXXXXXXXXXWCCPTZA',1,1,3,4,1,12,'',NULL,NULL,NULL,'1',NULL,NULL,'2015-03-05 10:40:09','2015-03-10 17:38:51'),(190,'Kauai Cavendish','HIPXKAUAIXCAVENDISHXWCCPTZA','HIPXKAUAIXXXXXXXXXXXWCCPTZA',1,1,3,4,1,12,'00-23-69-21-C0-88','34.041','18.54','Shop L40 \r\nCavendish Square \r\nDreyer Street \r\nClaremont ','1','021 674 1755',NULL,'2015-03-05 10:40:09','2015-03-10 17:38:52'),(191,'Kauai Menlyn','HIPXKAUAIXXXXMENLYNXGTPTYZA','HIPXKAUAIXXXXXXXXXXXGTXXXZA',1,1,1,2,1,12,'00-23-69-21-E0-BC','25.78489','25.27382','Shop UF 31/32\r\nMenlyn Park Shopping Centre \r\nCorner of Atterbury & Lois Avenue, Menlyn ','1','012 368 1199',NULL,'2015-03-05 10:40:09','2015-03-10 17:38:52'),(192,'Kauai Cedar','HIPXKAUAIXXXXXCEDARXGTJNBZA','HIPXKAUAIXXXXXXXXXXXGTXXXZA',1,1,1,1,1,12,'00-23-69-21-CB-C2','26.02141','28.00946','Shop U5 \r\nCedar Road Shopping Centre \r\nCedar Road \r\nFourways ','1','011 465 2121',NULL,'2015-03-05 10:40:10','2015-03-10 17:38:52'),(193,'Kauai SRG','HIPXKAUAIXXXXXXXSRGXWCCPTZA','HIPXKAUAIXXXXXXXXXXXWCCPTZA',1,1,3,4,1,12,'00-23-69-F9-E1-2A','33.925990','18.422066','SRG   \r\n1 Mostert Street \r\nCape Town ','1','021 461 7867',NULL,'2015-03-05 10:40:10','2015-03-10 17:38:52'),(194,'Kauai Rondebosch','HIPXKAUAIRONDEBOSCHXWCCPTZA','HIPXKAUAIXXXXXXXXXXXWCCPTZA',1,1,3,4,1,12,'00-23-69-33-84-3F','','','','1','',NULL,'2015-03-05 10:40:10','2015-03-10 17:38:52'),(195,'Kauai Umhlanga','HIPXKAUAIXXUMHLANGAXNLDURZA','HIPXKAUAIXXXXXXXXXXXXXXXXZA',1,1,2,3,1,12,'00-23-69-F9-E0-10','','','','1','',NULL,'2015-03-05 10:40:10','2015-03-10 17:38:52'),(196,'Kauai Musgrave','HIPXKAUAIXXMUSGRAVEXNLDURZA','HIPXKAUAIXXXXXXXXXXXXXXXXZA',1,1,2,3,1,12,'00-25-9C-27-DE-5E','','','','1','',NULL,'2015-03-05 10:40:10','2015-03-10 17:38:52'),(197,'Kauai Gateway','HIPXKAUAIXXXGATEWAYXNLDURZA','HIPXKAUAIXXXXXXXXXXXXXXXXZA',1,1,2,3,1,12,'00-23-69-F9-DF-B6','','','Shop no G 326,\r\nGateway Theatre of Shopping,\r\nUmhlanga,\r\nDurban \r\n4319\r\n','1','',NULL,'2015-03-05 10:40:11','2015-03-10 17:38:52'),(199,'Kauai Blueroute','HIPXKAUAIXBLUEROUTEXWCCPTZA','HIPXKAUAIXXXXXXXXXXXWCCPTZA',1,1,3,4,1,12,'00-25-9C-27-DE-31','','','','1','',NULL,'2015-03-05 10:40:11','2015-03-10 17:38:53'),(200,'Kauai Westville','HIPXKAUAIXWESTVILLEXNLDURZA','HIPXKAUAIXXXXXXXXXXXXXXXXZA',1,1,2,3,1,12,'00-23-69-F9-DF-D1','','','','1','','','2015-03-05 10:40:11','2015-03-10 17:38:53'),(201,'Kauai Walmer Park','HIPXKAUAIWALMERPARKXECPLZZA','HIPXKAUAIXXXXXXXXXXXECXXXZA',1,1,12,11,1,12,'',NULL,NULL,NULL,'1',NULL,NULL,'2015-03-05 10:40:11','2015-03-10 17:38:53'),(202,'Kauai Shortmarket','HIPXKAUAISHORTMARKTXWCCPTZA','HIPXKAUAIXXXXXXXXXXXWCCPTZA',1,1,3,4,1,12,'00-23-69-21-A0-4E','','','','1','',NULL,'2015-03-05 10:40:12','2015-03-10 17:38:53'),(203,'Kauai Bloem Loch Logan','HIPXKAUAIXLOCHLOGANXFSBFNZA','HIPXKAUAIXXXXXXXXXXXXXXXXZA',1,1,7,8,1,12,'',NULL,NULL,NULL,'1',NULL,NULL,'2015-03-05 10:40:12','2015-03-10 17:38:53'),(204,'Kauai Eastgate','HIPXKAUAIXXEASTGATEXGTPTYZA','HIPXKAUAIXXXXXXXXXXXGTXXXZA',1,1,1,2,1,12,'00-23-69-21-D1-7A','','','','1','',NULL,'2015-03-05 10:40:12','2015-03-10 17:38:53'),(205,'Kauai Hillcrest','HIPXKAUAIXHILLCRESTXNLDURZA','HIPXKAUAIXXXXXXXXXXXXXXXXZA',1,1,2,3,1,12,'00-23-69-F9-DF-95','','','','1','',NULL,'2015-03-05 10:40:12','2015-03-10 17:38:53'),(206,'Kauai Vincentpark','HIPXKAUAIVICENTPARKXECPLZZA','HIPXKAUAIXXXXXXXXXXXECXXXZA',1,1,12,11,1,12,'00-1E-E5-84-BE-AC','','','','1','',NULL,'2015-03-05 10:40:12','2015-03-10 17:38:53'),(207,'Vida WalmerPark','HIPXXVIDAWALMERPARKXECPLZZA',NULL,1,1,12,11,1,14,'00-21-29-A2-4F-DD','','','','1','',NULL,'2015-03-05 10:40:13','2015-03-05 10:40:13'),(208,'Kauai Claremont_old','HIPXKAUAIXCLAREMONTXWCCPTZA','HIPXKAUAIXXXXXXXXXXXWCCPTZA',1,1,3,4,1,12,'',NULL,NULL,NULL,'1',NULL,NULL,'2015-03-05 10:40:13','2015-03-10 17:38:54'),(209,'Kauai Tygervalley','HIPXKAUAITYGERVALLYXWCCPTZA','HIPXKAUAIXXXXXXXXXXXWCCPTZA',1,1,3,4,1,12,'00-23-69-33-83-B5','33.87301','18.63601','Shop 609, \r\nFoodcourt Upperlevel,\r\nWillie Schoor Avenue,\r\nTygervalley Centre,\r\nBellville ','1','021 914 9216',NULL,'2015-03-05 10:40:13','2015-03-10 17:38:54'),(210,'Ind Hudsonkloof','HIPHUDSONKLOOFSTREEXWCCPTZA',NULL,1,1,3,4,1,13,'68-7F-74-06-1C-DC','','','','1','',NULL,'2015-03-05 10:40:13','2015-03-05 10:40:13'),(211,'Kauai Constancia','HIPXKAUAICONSTANCIAXWCCPTZA','HIPXKAUAIXXXXXXXXXXXWCCPTZA',1,1,3,4,1,12,'',NULL,NULL,NULL,'1',NULL,NULL,'2015-03-05 10:40:14','2015-03-10 17:38:54'),(212,'Kauai Green Point','HIPXKAUAIGREENPOINTXWCCPTZA','HIPXKAUAIGREENPOINTXWCCPTZA',1,1,3,4,1,12,'',NULL,NULL,NULL,'1',NULL,NULL,'2015-03-05 10:40:14','2015-03-10 17:38:54'),(213,'Kauai Longstreet','HIPXKAUAILONGSTREETXWCCPTZA','HIPXKAUAIXXXXXXXXXXXWCCPTZA',1,1,3,4,1,12,'',NULL,NULL,NULL,'1',NULL,NULL,'2015-03-05 10:40:14','2015-03-10 17:38:54'),(214,'Kauai Pavilion','HIPXKAUAIXPAVILLIONXGTJNBZA','HIPXKAUAIXXXXXXXXXXXGTXXXZA',1,1,1,1,1,12,'',NULL,NULL,NULL,'1',NULL,NULL,'2015-03-05 10:40:14','2015-03-10 17:38:54'),(215,'Kauai Vincent Park','HIPXKAUAIVINCENTPRKXECELSZA','HIPXKAUAIXXXXXXXXXXXECXXXZA',1,1,12,10,1,12,'',NULL,NULL,NULL,'1',NULL,NULL,'2015-03-05 10:40:15','2015-03-10 17:38:54'),(216,'Kauai Westville Mall','HIPXKAUAIWESTVILLEMXNLDURZA','HIPXKAUAIXXXXXXXXXXXXXXXXZA',1,1,2,3,1,12,'',NULL,NULL,NULL,'1',NULL,NULL,'2015-03-05 10:40:15','2015-03-10 17:38:54'),(217,'Ind GreenValley','HIPINDPNTGREENVALLYXWCCPTZA',NULL,1,1,3,4,1,15,'00-27-22-E8-11-C0','','','','1','',NULL,'2015-03-05 10:40:15','2015-03-05 10:40:15'),(218,'Kauai Hatfield','HIPXKAUAIXXHATFIELDXGTPTYZA','HIPXKAUAIXXXXXXXXXXXGTXXXZA',1,1,1,2,1,12,'00-27-22-E8-12-2A','','','','1','',NULL,'2015-03-05 10:40:15','2015-03-10 17:38:55'),(219,'Wimpy BlueRoute','HIPXWIMPYXBLUEROUTEXWCCPTZA',NULL,1,1,3,4,1,17,'00-27-22-E8-12-37','','','','1','',NULL,'2015-03-05 10:40:15','2015-03-05 10:40:15'),(220,'Tourvest Berthas','HIPTRVESTXXXBERTHASXWCCPTZA',NULL,1,1,3,4,1,18,'00-27-22-E8-11-B1','','','','1','',NULL,'2015-03-05 10:40:16','2015-03-05 10:40:16'),(221,'Kauai Ballito','HIPXKAUAIXXXBALLITOXNLDURZA','HIPXKAUAIXXXXXXXXXXXXXXXXZA',1,1,2,3,1,12,'00-27-22-E4-09-8E','','','','1','',NULL,'2015-03-05 10:40:16','2015-03-10 17:38:55'),(222,'Kauai MelroseArch','HIPXKAUAIMELROSEARCXGTJNBZA','HIPXKAUAIXXXXXXXXXXXGTXXXZA',1,1,1,1,1,12,'00-27-22-FC-42-AC','','','','1','',NULL,'2015-03-05 10:40:16','2015-03-10 17:38:55'),(223,'Alphen LaBelle','HIPALPHENXXXLABELLEXWCCPTZA',NULL,1,1,3,4,1,19,'00-27-22-FC-42-8E','','','','1','',NULL,'2015-03-05 10:40:16','2015-03-05 10:40:16'),(224,'Pepenero Unit1','HIPPEPENOXXXXXUNIT1XWCCPTZA',NULL,1,1,3,4,1,20,'00-27-22-FC-43-50','','','','1','',NULL,'2015-03-05 10:40:16','2015-03-05 10:40:16'),(225,'Alphen 5Rooms','HIPALPHENXXXX5ROOMSXWCCPTZA',NULL,1,1,3,4,1,19,'00-27-22-FC-43-51','','','','1','',NULL,'2015-03-05 10:40:16','2015-03-05 10:40:16'),(226,'Alphen Rosebar','HIPALPHENXXXROSEBARXWCCPTZA',NULL,1,1,3,4,1,19,'00-27-22-FC-42-C7','','','','1','',NULL,'2015-03-05 10:40:16','2015-03-05 10:40:16'),(227,'Pepenero Unit2','HIPPEPENOXXXXXUNIT2XWCCPTZA',NULL,1,1,3,4,1,20,'00-27-22-FC-43-2B','','','','1','',NULL,'2015-03-05 10:40:16','2015-03-05 10:40:16'),(228,'Paranga','HIPPARNGAXXXPARANGAXWCCPTZA',NULL,1,1,3,4,1,21,'DC-9F-DB-95-DD-7A','','','','1','',NULL,'2015-03-05 10:40:17','2015-03-05 10:40:17'),(229,'Bungalow Unit1','HIPBUNGLWBNGLWUNIT1XWCCPTZA',NULL,1,1,3,4,1,16,'00-9F-DB-94-DD-75','','','','1','',NULL,'2015-03-05 10:40:17','2015-03-05 10:40:17'),(230,'Bungalow Unit2','HIPBUNGLWBNGLWUNIT2XWCCPTZA',NULL,1,1,3,4,1,16,'00-9F-DB-94-DE-05','','','','1','',NULL,'2015-03-05 10:40:17','2015-03-05 10:40:17'),(231,'Zenzero Unit1','HIPZENZROXXXXXUNIT1XWCCPTZA',NULL,1,1,3,4,1,22,'00-9F-DB-94-DE-0B','','','','1','',NULL,'2015-03-05 10:40:17','2015-03-05 10:40:17'),(232,'Zenzero Unit2','HIPZENZROXXXXXUNIT2XWCCPTZA',NULL,1,1,3,4,1,22,'DC-9F-DB-95-DD-5F','','','','1','',NULL,'2015-03-05 10:40:17','2015-03-05 10:40:17'),(233,'Hemelhuijs','HIPHEMELHHEMELHUIJSXWCCPTZA',NULL,1,1,3,4,1,23,'DC-9F-DB-95-DD-EC','','','','1','',NULL,'2015-03-05 10:40:17','2015-03-05 10:40:17'),(234,'Nandos N1City','HIPNANDOSXXXXN1CITYXWCCPTZA',NULL,1,1,3,4,1,24,'DC-9F-DB-95-DD-C5','','','','1','',NULL,'2015-03-05 10:40:17','2015-03-05 10:40:17'),(235,'Moving HeadOffice','HIPMOVINGHEADOFFICEXWCCPTZA',NULL,1,1,3,4,1,25,'00-27-22-FE-A2-78','','','','1','',NULL,'2015-03-05 10:40:18','2015-03-05 10:40:18'),(236,'Nandos Tygervalley','HIPNANDOSTYGERVALLYXWCCPTZA',NULL,1,1,3,4,1,24,'00-27-22-FE-A2-6E','','','','1','',NULL,'2015-03-05 10:40:18','2015-03-05 10:40:18'),(237,'Heineken Tollies','HIPHEINEKXXXTOLLIESXWCCPTZA',NULL,1,1,3,4,1,1,'00-27-22-FE-A1:24','','','','1','',NULL,'2015-03-05 10:40:18','2015-03-05 10:40:18'),(238,'Kauai Lalucia','HIPXKAUAIXXXLALUCIAXNLDURZA','HIPXKAUAIXXXXXXXXXXXXXXXXZA',1,1,2,3,1,12,'00-27-22-FE-A1-72','','','','1','',NULL,'2015-03-05 10:40:18','2015-03-10 17:38:55'),(239,'Heineken Cashs','HIPHEINEKXXXXXCASHSXWCCPTZA',NULL,1,1,3,4,1,1,'00-27-22-FE-A1-04','','','','1','',NULL,'2015-03-05 10:40:18','2015-03-05 10:40:18'),(240,'Heineken Pistos','HIPHEINEKXXXXPISTOSXWCCPTZA',NULL,1,1,3,4,1,1,'00:27:22:FE:A2:83','','','','1','',NULL,'2015-03-05 10:40:18','2015-03-05 10:40:18'),(241,'Heineken CasaDelSol','HIPHEINEKCASADELSOLXWCCPTZA',NULL,1,1,3,4,1,1,'00-27-22-FF-A1-4E','','','','1','',NULL,'2015-03-05 10:40:18','2015-03-05 10:40:18'),(242,'Heineken Sway','HIPHEINEKXXXXXXSWAYXWCCPTZA',NULL,1,1,3,4,1,1,'DC-9F-DB-95-DD-EC','','','','1','',NULL,'2015-03-05 10:40:19','2015-03-05 10:40:19'),(243,'Heineken TheSands','HIPHEINEKXXTHESANDSXWCCPTZA',NULL,1,1,3,4,1,1,'00-27-22-FE-A2-63','','','','1','',NULL,'2015-03-05 10:40:19','2015-03-05 10:40:19'),(251,'Heineken Durbantest','HIPHEINEKDURBANTE01XNLDURZA','HIPHEINEKXXXXXXXXXXXNLXXXZA',0,1,2,3,1,1,'AA:BB:CC:DB:EE:FF','','','','1','','','2015-03-06 09:01:21','2015-03-10 17:38:55'),(252,'Kauai PEtest','HIPXKAUAIXXPETEST01XECPLZZA','HIPXKAUAIXXXXXXXXXXXECXXXZA',0,1,12,11,1,12,'AA:BB:FC:DD:EE:FF','','','','1','','','2015-03-06 09:08:29','2015-03-10 17:38:55'),(254,'Kauai grgggg','HIPXKAUAIXXGRGGGG01XWCGRJZA','HIPXKAUAIXXXXXXXXXXXWCXXXZA',0,1,3,9,1,12,'AA:BA:CC:DD:EE:FF','','','','1','','','2015-03-06 10:02:19','2015-03-10 17:38:55'),(255,'Heineken heintest','HIPHEINEKHEINTEST01XGTJNBZA',NULL,0,1,1,1,1,1,'AA:CB:CC:DD:EE:FF','','','','1','','','2015-03-09 08:23:52','2015-03-09 08:23:52'),(256,'Heineken sabtest','WFIHEINEKXSABTEST01XGTJNBZA',NULL,0,1,1,1,2,1,'AA:BB:CC:ED:EE:FF','','','','1','','','2015-03-09 08:29:36','2015-03-09 08:29:36'),(257,'Sab Cocomika','WFICASTLEXXCOCOMIKAXWCCPTZA','WFICASTLEXXXXXXXXXXXWCXXXZA',2,1,3,4,2,3,'4C-5E-0C-34-99-04','','','','3','',NULL,'2015-03-09 17:40:35','2015-03-10 17:38:55'),(258,'Sab Greenhouse','WFIXHANSAGREENHOUSEXWCCPTZA','WFIXHANSAXXXXXXXXXXXXXXXXZA',2,1,3,4,2,2,'4C-5E-0C-4F-9D-5A','','','','3','',NULL,'2015-03-09 17:40:35','2015-03-10 17:38:56'),(259,'Sab Olwethusplace','WFICASTLEOLWETHUUSPXWCCPTZA','WFICASTLEXXXXXXXXXXXWCXXXZA',2,1,3,4,2,3,'4C-5E-0C-36-77-38','','','','3','',NULL,'2015-03-09 17:40:35','2015-03-10 17:38:56'),(260,'Sab Umtatatavern','WFICASTLEUMTATATAVNXWCCPTZA','WFICASTLEUMTATATAVNXWCCPTZA',2,1,3,4,2,3,'4C-5E-0C-34-99-06','','','','3','',NULL,'2015-03-09 17:40:35','2015-03-10 17:38:56'),(261,'Sab Legendsplace','WFICASTLELEGENDSPLAXWCCPTZA','WFICASTLEXXXXXXXXXXXWCXXXZA',2,1,3,4,2,3,'4C-5E-0C-34-99-dd','','','','3','',NULL,'2015-03-09 17:40:35','2015-03-10 17:38:56'),(262,'Sab Daniel','WFICASTLEXXXXDANIELXWCCPTZA','WFICASTLEXXXXXXXXXXXWCXXXZA',2,1,3,4,2,3,'4C:5E:0C:34:99:04','','','','3','',NULL,'2015-03-09 17:40:35','2015-03-10 17:38:56'),(263,'Sab Change','WFICASTLEXXXXCHANGEXWCCPTZA','WFICASTLEXXXXXXXXXXXWCXXXZA',2,1,3,4,2,3,'4C:5E:0C:51:62:95','','','','3','',NULL,'2015-03-09 17:40:35','2015-03-10 17:38:56');
/*!40000 ALTER TABLE `venues` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-03-10 22:10:50
