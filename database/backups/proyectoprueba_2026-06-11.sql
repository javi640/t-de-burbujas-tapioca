-- MySQL dump 10.13  Distrib 8.0.45, for Linux (x86_64)
--
-- Host: localhost    Database: proyectoprueba
-- ------------------------------------------------------
-- Server version	8.0.45

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
-- Table structure for table `audit_logs`
--

DROP TABLE IF EXISTS `audit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `audit_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `action` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model_id` bigint unsigned DEFAULT NULL,
  `old_values` json DEFAULT NULL,
  `new_values` json DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `audit_logs_model_model_id_index` (`model`,`model_id`),
  KEY `audit_logs_user_id_index` (`user_id`),
  KEY `audit_logs_created_at_index` (`created_at`),
  KEY `idx_audit_user_date` (`user_id`,`created_at`),
  KEY `idx_audit_action_date` (`action`,`created_at`),
  CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_logs`
--

LOCK TABLES `audit_logs` WRITE;
/*!40000 ALTER TABLE `audit_logs` DISABLE KEYS */;
INSERT INTO `audit_logs` VALUES (1,1,'login',NULL,NULL,NULL,NULL,'172.20.0.1','2026-06-11 18:25:53'),(2,1,'void_sale','Sale',6,'{\"status\": \"COMPLETED\"}','{\"status\": \"VOIDED\", \"void_reason\": \"Pedido cancelado por el cliente.\"}','127.0.0.1','2026-06-01 14:02:00'),(3,1,'open_shift_for_cajero','Shift',1,NULL,'{\"cajero_id\": 2, \"initial_cash\": 200}','127.0.0.1','2026-06-01 13:00:00'),(4,2,'close_shift','Shift',1,'{\"expected_cash\": 339}','{\"reported_cash\": 337, \"cash_difference\": -2, \"inconsistency_class\": \"INCONSISTENCIA_LEVE\"}','127.0.0.1','2026-06-01 22:22:00'),(5,1,'open_shift_for_cajero','Shift',2,NULL,'{\"cajero_id\": 2, \"initial_cash\": 200}','127.0.0.1','2026-06-02 13:00:00'),(6,2,'close_shift','Shift',2,'{\"expected_cash\": 234}','{\"reported_cash\": 191, \"cash_difference\": -43, \"inconsistency_class\": \"INCONSISTENCIA_LEVE\"}','127.0.0.1','2026-06-02 22:12:00'),(7,1,'void_sale','Sale',23,'{\"status\": \"COMPLETED\"}','{\"status\": \"VOIDED\", \"void_reason\": \"Pedido cancelado por el cliente.\"}','127.0.0.1','2026-06-03 18:49:00'),(8,1,'void_sale','Sale',25,'{\"status\": \"COMPLETED\"}','{\"status\": \"VOIDED\", \"void_reason\": \"Pedido cancelado por el cliente.\"}','127.0.0.1','2026-06-03 17:48:00'),(9,2,'cash_movement','CashMovement',1,NULL,'{\"amount\": \"15.00\", \"movement_type\": \"EXPENSE\"}','127.0.0.1','2026-06-03 18:36:00'),(10,1,'open_shift_for_cajero','Shift',3,NULL,'{\"cajero_id\": 2, \"initial_cash\": 200}','127.0.0.1','2026-06-03 13:00:00'),(11,2,'close_shift','Shift',3,'{\"expected_cash\": 256}','{\"reported_cash\": 239, \"cash_difference\": -17, \"inconsistency_class\": \"INCONSISTENCIA_CRITICA\"}','127.0.0.1','2026-06-03 22:12:00'),(12,1,'void_sale','Sale',32,'{\"status\": \"COMPLETED\"}','{\"status\": \"VOIDED\", \"void_reason\": \"Pedido cancelado por el cliente.\"}','127.0.0.1','2026-06-04 21:14:00'),(13,1,'void_sale','Sale',35,'{\"status\": \"COMPLETED\"}','{\"status\": \"VOIDED\", \"void_reason\": \"Pedido cancelado por el cliente.\"}','127.0.0.1','2026-06-04 16:48:00'),(14,1,'open_shift_for_cajero','Shift',4,NULL,'{\"cajero_id\": 2, \"initial_cash\": 200}','127.0.0.1','2026-06-04 13:00:00'),(15,2,'close_shift','Shift',4,'{\"expected_cash\": 383}','{\"reported_cash\": 375, \"cash_difference\": -8, \"inconsistency_class\": \"INCONSISTENCIA_CRITICA\"}','127.0.0.1','2026-06-04 22:21:00'),(16,1,'void_sale','Sale',52,'{\"status\": \"COMPLETED\"}','{\"status\": \"VOIDED\", \"void_reason\": \"Pedido cancelado por el cliente.\"}','127.0.0.1','2026-06-05 21:01:00'),(17,2,'cash_movement','CashMovement',2,NULL,'{\"amount\": \"25.00\", \"movement_type\": \"EXPENSE\"}','127.0.0.1','2026-06-05 17:11:00'),(18,1,'open_shift_for_cajero','Shift',5,NULL,'{\"cajero_id\": 2, \"initial_cash\": 200}','127.0.0.1','2026-06-05 13:00:00'),(19,2,'close_shift','Shift',5,'{\"expected_cash\": 485}','{\"reported_cash\": 485, \"cash_difference\": 0, \"inconsistency_class\": \"SIN_INCONSISTENCIA\"}','127.0.0.1','2026-06-05 22:03:00'),(20,1,'void_sale','Sale',59,'{\"status\": \"COMPLETED\"}','{\"status\": \"VOIDED\", \"void_reason\": \"Pedido cancelado por el cliente.\"}','127.0.0.1','2026-06-06 19:03:00'),(21,2,'cash_movement','CashMovement',3,NULL,'{\"amount\": \"46.00\", \"movement_type\": \"EXPENSE\"}','127.0.0.1','2026-06-06 17:05:00'),(22,1,'open_shift_for_cajero','Shift',6,NULL,'{\"cajero_id\": 2, \"initial_cash\": 200}','127.0.0.1','2026-06-06 13:00:00'),(23,2,'close_shift','Shift',6,'{\"expected_cash\": 456}','{\"reported_cash\": 456, \"cash_difference\": 0, \"inconsistency_class\": \"SIN_INCONSISTENCIA\"}','127.0.0.1','2026-06-06 22:02:00'),(24,1,'void_sale','Sale',65,'{\"status\": \"COMPLETED\"}','{\"status\": \"VOIDED\", \"void_reason\": \"Pedido cancelado por el cliente.\"}','127.0.0.1','2026-06-08 19:13:00'),(25,2,'cash_movement','CashMovement',4,NULL,'{\"amount\": \"48.00\", \"movement_type\": \"EXPENSE\"}','127.0.0.1','2026-06-08 16:59:00'),(26,1,'open_shift_for_cajero','Shift',7,NULL,'{\"cajero_id\": 2, \"initial_cash\": 200}','127.0.0.1','2026-06-08 13:00:00'),(27,2,'close_shift','Shift',7,'{\"expected_cash\": 312}','{\"reported_cash\": 338, \"cash_difference\": 26, \"inconsistency_class\": \"INCONSISTENCIA_LEVE\"}','127.0.0.1','2026-06-08 22:24:00'),(28,1,'void_sale','Sale',78,'{\"status\": \"COMPLETED\"}','{\"status\": \"VOIDED\", \"void_reason\": \"Pedido cancelado por el cliente.\"}','127.0.0.1','2026-06-09 13:14:00'),(29,2,'cash_movement','CashMovement',5,NULL,'{\"amount\": \"38.00\", \"movement_type\": \"EXPENSE\"}','127.0.0.1','2026-06-09 18:01:00'),(30,1,'open_shift_for_cajero','Shift',8,NULL,'{\"cajero_id\": 2, \"initial_cash\": 200}','127.0.0.1','2026-06-09 13:00:00'),(31,2,'close_shift','Shift',8,'{\"expected_cash\": 415}','{\"reported_cash\": 415, \"cash_difference\": 0, \"inconsistency_class\": \"SIN_INCONSISTENCIA\"}','127.0.0.1','2026-06-09 22:26:00'),(32,1,'void_sale','Sale',91,'{\"status\": \"COMPLETED\"}','{\"status\": \"VOIDED\", \"void_reason\": \"Pedido cancelado por el cliente.\"}','127.0.0.1','2026-06-10 18:29:00'),(33,1,'void_sale','Sale',92,'{\"status\": \"COMPLETED\"}','{\"status\": \"VOIDED\", \"void_reason\": \"Pedido cancelado por el cliente.\"}','127.0.0.1','2026-06-10 16:39:00'),(34,2,'cash_movement','CashMovement',6,NULL,'{\"amount\": \"36.00\", \"movement_type\": \"EXPENSE\"}','127.0.0.1','2026-06-10 19:59:00'),(35,1,'open_shift_for_cajero','Shift',9,NULL,'{\"cajero_id\": 2, \"initial_cash\": 200}','127.0.0.1','2026-06-10 13:00:00'),(36,2,'close_shift','Shift',9,'{\"expected_cash\": 450}','{\"reported_cash\": 393, \"cash_difference\": -57, \"inconsistency_class\": \"INCONSISTENCIA_CRITICA\"}','127.0.0.1','2026-06-10 22:30:00'),(37,1,'void_sale','Sale',98,'{\"status\": \"COMPLETED\"}','{\"status\": \"VOIDED\", \"void_reason\": \"Pedido cancelado por el cliente.\"}','127.0.0.1','2026-06-11 19:53:00'),(38,2,'cash_movement','CashMovement',7,NULL,'{\"amount\": \"29.00\", \"movement_type\": \"EXPENSE\"}','127.0.0.1','2026-06-11 19:01:00'),(39,1,'open_shift_for_cajero','Shift',10,NULL,'{\"cajero_id\": 2, \"initial_cash\": 200}','127.0.0.1','2026-06-11 13:00:00'),(40,2,'close_shift','Shift',10,'{\"expected_cash\": 479}','{\"reported_cash\": 479, \"cash_difference\": 0, \"inconsistency_class\": \"SIN_INCONSISTENCIA\"}','127.0.0.1','2026-06-11 22:06:00'),(41,1,'open_shift_for_cajero','Shift',11,NULL,'{\"cajero_id\": 2, \"initial_cash\": 200}','127.0.0.1','2026-06-12 13:00:00'),(42,2,'close_shift','Shift',11,'{\"expected_cash\": 418}','{\"reported_cash\": 418, \"cash_difference\": 0, \"inconsistency_class\": \"SIN_INCONSISTENCIA\"}','127.0.0.1','2026-06-12 22:23:00'),(43,2,'cash_movement','CashMovement',8,NULL,'{\"amount\": \"33.00\", \"movement_type\": \"EXPENSE\"}','127.0.0.1','2026-06-13 19:11:00'),(44,1,'open_shift_for_cajero','Shift',12,NULL,'{\"cajero_id\": 2, \"initial_cash\": 200}','127.0.0.1','2026-06-13 13:00:00'),(45,2,'close_shift','Shift',12,'{\"expected_cash\": 369}','{\"reported_cash\": 396, \"cash_difference\": 27, \"inconsistency_class\": \"INCONSISTENCIA_LEVE\"}','127.0.0.1','2026-06-13 22:04:00'),(46,1,'void_sale','Sale',129,'{\"status\": \"COMPLETED\"}','{\"status\": \"VOIDED\", \"void_reason\": \"Pedido cancelado por el cliente.\"}','127.0.0.1','2026-06-15 18:03:00'),(47,1,'open_shift_for_cajero','Shift',13,NULL,'{\"cajero_id\": 2, \"initial_cash\": 200}','127.0.0.1','2026-06-15 13:00:00'),(48,2,'close_shift','Shift',13,'{\"expected_cash\": 741}','{\"reported_cash\": 741, \"cash_difference\": 0, \"inconsistency_class\": \"SIN_INCONSISTENCIA\"}','127.0.0.1','2026-06-15 22:03:00'),(49,2,'cash_movement','CashMovement',9,NULL,'{\"amount\": \"56.00\", \"movement_type\": \"EXPENSE\"}','127.0.0.1','2026-06-16 19:54:00'),(50,1,'open_shift_for_cajero','Shift',14,NULL,'{\"cajero_id\": 2, \"initial_cash\": 200}','127.0.0.1','2026-06-16 13:00:00'),(51,2,'close_shift','Shift',14,'{\"expected_cash\": 416}','{\"reported_cash\": 401, \"cash_difference\": -15, \"inconsistency_class\": \"INCONSISTENCIA_LEVE\"}','127.0.0.1','2026-06-16 22:21:00');
/*!40000 ALTER TABLE `audit_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `branches`
--

DROP TABLE IF EXISTS `branches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `branches` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `branches`
--

LOCK TABLES `branches` WRITE;
/*!40000 ALTER TABLE `branches` DISABLE KEYS */;
INSERT INTO `branches` VALUES (1,'Panda Naicha - Principal','La Paz, Bolivia',1,'2026-06-11 18:24:27','2026-06-11 18:24:27');
/*!40000 ALTER TABLE `branches` ENABLE KEYS */;
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
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('panda-naicha-cache-dashboard.active_shifts','O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}',1781203498),('panda-naicha-cache-dashboard.period.hoy.2026-06-11.2026-06-11','a:8:{s:16:\"periodSalesCount\";i:7;s:10:\"periodCash\";d:308;s:8:\"periodQr\";d:174;s:11:\"recentSales\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:7:{i:0;O:15:\"App\\Models\\Sale\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:5:\"sales\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:10:{s:2:\"id\";i:103;s:8:\"shift_id\";i:10;s:12:\"total_amount\";s:5:\"73.00\";s:14:\"payment_method\";s:4:\"CASH\";s:6:\"status\";s:9:\"COMPLETED\";s:9:\"sale_time\";s:19:\"2026-06-11 14:56:00\";s:9:\"voided_by\";N;s:11:\"void_reason\";N;s:10:\"created_at\";s:19:\"2026-06-11 14:42:19\";s:10:\"updated_at\";s:19:\"2026-06-11 14:42:19\";}s:11:\"\0*\0original\";a:10:{s:2:\"id\";i:103;s:8:\"shift_id\";i:10;s:12:\"total_amount\";s:5:\"73.00\";s:14:\"payment_method\";s:4:\"CASH\";s:6:\"status\";s:9:\"COMPLETED\";s:9:\"sale_time\";s:19:\"2026-06-11 14:56:00\";s:9:\"voided_by\";N;s:11:\"void_reason\";N;s:10:\"created_at\";s:19:\"2026-06-11 14:42:19\";s:10:\"updated_at\";s:19:\"2026-06-11 14:42:19\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:2:{s:12:\"total_amount\";s:9:\"decimal:2\";s:9:\"sale_time\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:5:\"shift\";O:16:\"App\\Models\\Shift\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"shifts\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:17:{s:2:\"id\";i:10;s:7:\"user_id\";i:2;s:9:\"opened_by\";i:1;s:6:\"status\";s:6:\"CLOSED\";s:10:\"start_time\";s:19:\"2026-06-11 09:00:00\";s:15:\"scheduled_start\";s:19:\"2026-06-11 09:00:00\";s:17:\"tolerance_minutes\";i:10;s:17:\"cajero_login_time\";s:19:\"2026-06-11 09:02:00\";s:17:\"attendance_status\";s:7:\"PUNTUAL\";s:8:\"end_time\";s:19:\"2026-06-11 18:06:00\";s:12:\"initial_cash\";s:6:\"200.00\";s:13:\"reported_cash\";s:6:\"479.00\";s:15:\"cash_difference\";s:4:\"0.00\";s:19:\"inconsistency_class\";s:18:\"SIN_INCONSISTENCIA\";s:5:\"notes\";N;s:10:\"created_at\";s:19:\"2026-06-11 14:42:19\";s:10:\"updated_at\";s:19:\"2026-06-11 14:42:19\";}s:11:\"\0*\0original\";a:17:{s:2:\"id\";i:10;s:7:\"user_id\";i:2;s:9:\"opened_by\";i:1;s:6:\"status\";s:6:\"CLOSED\";s:10:\"start_time\";s:19:\"2026-06-11 09:00:00\";s:15:\"scheduled_start\";s:19:\"2026-06-11 09:00:00\";s:17:\"tolerance_minutes\";i:10;s:17:\"cajero_login_time\";s:19:\"2026-06-11 09:02:00\";s:17:\"attendance_status\";s:7:\"PUNTUAL\";s:8:\"end_time\";s:19:\"2026-06-11 18:06:00\";s:12:\"initial_cash\";s:6:\"200.00\";s:13:\"reported_cash\";s:6:\"479.00\";s:15:\"cash_difference\";s:4:\"0.00\";s:19:\"inconsistency_class\";s:18:\"SIN_INCONSISTENCIA\";s:5:\"notes\";N;s:10:\"created_at\";s:19:\"2026-06-11 14:42:19\";s:10:\"updated_at\";s:19:\"2026-06-11 14:42:19\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:7:{s:10:\"start_time\";s:8:\"datetime\";s:8:\"end_time\";s:8:\"datetime\";s:15:\"scheduled_start\";s:8:\"datetime\";s:17:\"cajero_login_time\";s:8:\"datetime\";s:12:\"initial_cash\";s:9:\"decimal:2\";s:13:\"reported_cash\";s:9:\"decimal:2\";s:15:\"cash_difference\";s:9:\"decimal:2\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:4:\"user\";O:15:\"App\\Models\\User\":35:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:5:\"users\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:11:{s:2:\"id\";i:2;s:9:\"branch_id\";i:1;s:7:\"role_id\";i:2;s:4:\"name\";s:13:\"Cajero Prueba\";s:8:\"username\";s:7:\"cajero1\";s:5:\"email\";N;s:8:\"password\";s:60:\"$2y$12$AyD6XYoUQ5Da6iTr7QCJwuLQu2O8r48Pu0wRJxucYpEx9kbNbseka\";s:9:\"is_active\";i:1;s:14:\"remember_token\";N;s:10:\"created_at\";s:19:\"2026-06-11 14:24:28\";s:10:\"updated_at\";s:19:\"2026-06-11 14:24:28\";}s:11:\"\0*\0original\";a:11:{s:2:\"id\";i:2;s:9:\"branch_id\";i:1;s:7:\"role_id\";i:2;s:4:\"name\";s:13:\"Cajero Prueba\";s:8:\"username\";s:7:\"cajero1\";s:5:\"email\";N;s:8:\"password\";s:60:\"$2y$12$AyD6XYoUQ5Da6iTr7QCJwuLQu2O8r48Pu0wRJxucYpEx9kbNbseka\";s:9:\"is_active\";i:1;s:14:\"remember_token\";N;s:10:\"created_at\";s:19:\"2026-06-11 14:24:28\";s:10:\"updated_at\";s:19:\"2026-06-11 14:24:28\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:2:{s:9:\"is_active\";s:7:\"boolean\";s:8:\"password\";s:6:\"hashed\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:2:{i:0;s:8:\"password\";i:1;s:14:\"remember_token\";}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:7:{i:0;s:9:\"branch_id\";i:1;s:7:\"role_id\";i:2;s:8:\"username\";i:3;s:4:\"name\";i:4;s:5:\"email\";i:5;s:8:\"password\";i:6;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:19:\"\0*\0authPasswordName\";s:8:\"password\";s:20:\"\0*\0rememberTokenName\";s:14:\"remember_token\";}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:14:{i:0;s:7:\"user_id\";i:1;s:9:\"opened_by\";i:2;s:6:\"status\";i:3;s:10:\"start_time\";i:4;s:8:\"end_time\";i:5;s:15:\"scheduled_start\";i:6;s:17:\"tolerance_minutes\";i:7;s:17:\"cajero_login_time\";i:8;s:17:\"attendance_status\";i:9;s:12:\"initial_cash\";i:10;s:13:\"reported_cash\";i:11;s:15:\"cash_difference\";i:12;s:19:\"inconsistency_class\";i:13;s:5:\"notes\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:7:{i:0;s:8:\"shift_id\";i:1;s:12:\"total_amount\";i:2;s:14:\"payment_method\";i:3;s:6:\"status\";i:4;s:9:\"sale_time\";i:5;s:9:\"voided_by\";i:6;s:11:\"void_reason\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:1;O:15:\"App\\Models\\Sale\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:5:\"sales\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:10:{s:2:\"id\";i:97;s:8:\"shift_id\";i:10;s:12:\"total_amount\";s:5:\"72.00\";s:14:\"payment_method\";s:4:\"CASH\";s:6:\"status\";s:9:\"COMPLETED\";s:9:\"sale_time\";s:19:\"2026-06-11 13:23:00\";s:9:\"voided_by\";N;s:11:\"void_reason\";N;s:10:\"created_at\";s:19:\"2026-06-11 14:42:19\";s:10:\"updated_at\";s:19:\"2026-06-11 14:42:19\";}s:11:\"\0*\0original\";a:10:{s:2:\"id\";i:97;s:8:\"shift_id\";i:10;s:12:\"total_amount\";s:5:\"72.00\";s:14:\"payment_method\";s:4:\"CASH\";s:6:\"status\";s:9:\"COMPLETED\";s:9:\"sale_time\";s:19:\"2026-06-11 13:23:00\";s:9:\"voided_by\";N;s:11:\"void_reason\";N;s:10:\"created_at\";s:19:\"2026-06-11 14:42:19\";s:10:\"updated_at\";s:19:\"2026-06-11 14:42:19\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:2:{s:12:\"total_amount\";s:9:\"decimal:2\";s:9:\"sale_time\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:5:\"shift\";r:54;}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:7:{i:0;s:8:\"shift_id\";i:1;s:12:\"total_amount\";i:2;s:14:\"payment_method\";i:3;s:6:\"status\";i:4;s:9:\"sale_time\";i:5;s:9:\"voided_by\";i:6;s:11:\"void_reason\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:2;O:15:\"App\\Models\\Sale\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:5:\"sales\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:10:{s:2:\"id\";i:100;s:8:\"shift_id\";i:10;s:12:\"total_amount\";s:5:\"56.00\";s:14:\"payment_method\";s:2:\"QR\";s:6:\"status\";s:9:\"COMPLETED\";s:9:\"sale_time\";s:19:\"2026-06-11 12:56:00\";s:9:\"voided_by\";N;s:11:\"void_reason\";N;s:10:\"created_at\";s:19:\"2026-06-11 14:42:19\";s:10:\"updated_at\";s:19:\"2026-06-11 14:42:19\";}s:11:\"\0*\0original\";a:10:{s:2:\"id\";i:100;s:8:\"shift_id\";i:10;s:12:\"total_amount\";s:5:\"56.00\";s:14:\"payment_method\";s:2:\"QR\";s:6:\"status\";s:9:\"COMPLETED\";s:9:\"sale_time\";s:19:\"2026-06-11 12:56:00\";s:9:\"voided_by\";N;s:11:\"void_reason\";N;s:10:\"created_at\";s:19:\"2026-06-11 14:42:19\";s:10:\"updated_at\";s:19:\"2026-06-11 14:42:19\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:2:{s:12:\"total_amount\";s:9:\"decimal:2\";s:9:\"sale_time\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:5:\"shift\";r:54;}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:7:{i:0;s:8:\"shift_id\";i:1;s:12:\"total_amount\";i:2;s:14:\"payment_method\";i:3;s:6:\"status\";i:4;s:9:\"sale_time\";i:5;s:9:\"voided_by\";i:6;s:11:\"void_reason\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:3;O:15:\"App\\Models\\Sale\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:5:\"sales\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:10:{s:2:\"id\";i:96;s:8:\"shift_id\";i:10;s:12:\"total_amount\";s:5:\"45.00\";s:14:\"payment_method\";s:4:\"CASH\";s:6:\"status\";s:9:\"COMPLETED\";s:9:\"sale_time\";s:19:\"2026-06-11 12:30:00\";s:9:\"voided_by\";N;s:11:\"void_reason\";N;s:10:\"created_at\";s:19:\"2026-06-11 14:42:19\";s:10:\"updated_at\";s:19:\"2026-06-11 14:42:19\";}s:11:\"\0*\0original\";a:10:{s:2:\"id\";i:96;s:8:\"shift_id\";i:10;s:12:\"total_amount\";s:5:\"45.00\";s:14:\"payment_method\";s:4:\"CASH\";s:6:\"status\";s:9:\"COMPLETED\";s:9:\"sale_time\";s:19:\"2026-06-11 12:30:00\";s:9:\"voided_by\";N;s:11:\"void_reason\";N;s:10:\"created_at\";s:19:\"2026-06-11 14:42:19\";s:10:\"updated_at\";s:19:\"2026-06-11 14:42:19\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:2:{s:12:\"total_amount\";s:9:\"decimal:2\";s:9:\"sale_time\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:5:\"shift\";r:54;}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:7:{i:0;s:8:\"shift_id\";i:1;s:12:\"total_amount\";i:2;s:14:\"payment_method\";i:3;s:6:\"status\";i:4;s:9:\"sale_time\";i:5;s:9:\"voided_by\";i:6;s:11:\"void_reason\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:4;O:15:\"App\\Models\\Sale\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:5:\"sales\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:10:{s:2:\"id\";i:102;s:8:\"shift_id\";i:10;s:12:\"total_amount\";s:5:\"39.00\";s:14:\"payment_method\";s:2:\"QR\";s:6:\"status\";s:9:\"COMPLETED\";s:9:\"sale_time\";s:19:\"2026-06-11 12:16:00\";s:9:\"voided_by\";N;s:11:\"void_reason\";N;s:10:\"created_at\";s:19:\"2026-06-11 14:42:19\";s:10:\"updated_at\";s:19:\"2026-06-11 14:42:19\";}s:11:\"\0*\0original\";a:10:{s:2:\"id\";i:102;s:8:\"shift_id\";i:10;s:12:\"total_amount\";s:5:\"39.00\";s:14:\"payment_method\";s:2:\"QR\";s:6:\"status\";s:9:\"COMPLETED\";s:9:\"sale_time\";s:19:\"2026-06-11 12:16:00\";s:9:\"voided_by\";N;s:11:\"void_reason\";N;s:10:\"created_at\";s:19:\"2026-06-11 14:42:19\";s:10:\"updated_at\";s:19:\"2026-06-11 14:42:19\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:2:{s:12:\"total_amount\";s:9:\"decimal:2\";s:9:\"sale_time\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:5:\"shift\";r:54;}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:7:{i:0;s:8:\"shift_id\";i:1;s:12:\"total_amount\";i:2;s:14:\"payment_method\";i:3;s:6:\"status\";i:4;s:9:\"sale_time\";i:5;s:9:\"voided_by\";i:6;s:11:\"void_reason\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:5;O:15:\"App\\Models\\Sale\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:5:\"sales\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:10:{s:2:\"id\";i:101;s:8:\"shift_id\";i:10;s:12:\"total_amount\";s:6:\"118.00\";s:14:\"payment_method\";s:4:\"CASH\";s:6:\"status\";s:9:\"COMPLETED\";s:9:\"sale_time\";s:19:\"2026-06-11 10:39:00\";s:9:\"voided_by\";N;s:11:\"void_reason\";N;s:10:\"created_at\";s:19:\"2026-06-11 14:42:19\";s:10:\"updated_at\";s:19:\"2026-06-11 14:42:19\";}s:11:\"\0*\0original\";a:10:{s:2:\"id\";i:101;s:8:\"shift_id\";i:10;s:12:\"total_amount\";s:6:\"118.00\";s:14:\"payment_method\";s:4:\"CASH\";s:6:\"status\";s:9:\"COMPLETED\";s:9:\"sale_time\";s:19:\"2026-06-11 10:39:00\";s:9:\"voided_by\";N;s:11:\"void_reason\";N;s:10:\"created_at\";s:19:\"2026-06-11 14:42:19\";s:10:\"updated_at\";s:19:\"2026-06-11 14:42:19\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:2:{s:12:\"total_amount\";s:9:\"decimal:2\";s:9:\"sale_time\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:5:\"shift\";r:54;}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:7:{i:0;s:8:\"shift_id\";i:1;s:12:\"total_amount\";i:2;s:14:\"payment_method\";i:3;s:6:\"status\";i:4;s:9:\"sale_time\";i:5;s:9:\"voided_by\";i:6;s:11:\"void_reason\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:6;O:15:\"App\\Models\\Sale\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:5:\"sales\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:10:{s:2:\"id\";i:99;s:8:\"shift_id\";i:10;s:12:\"total_amount\";s:5:\"79.00\";s:14:\"payment_method\";s:2:\"QR\";s:6:\"status\";s:9:\"COMPLETED\";s:9:\"sale_time\";s:19:\"2026-06-11 09:27:00\";s:9:\"voided_by\";N;s:11:\"void_reason\";N;s:10:\"created_at\";s:19:\"2026-06-11 14:42:19\";s:10:\"updated_at\";s:19:\"2026-06-11 14:42:19\";}s:11:\"\0*\0original\";a:10:{s:2:\"id\";i:99;s:8:\"shift_id\";i:10;s:12:\"total_amount\";s:5:\"79.00\";s:14:\"payment_method\";s:2:\"QR\";s:6:\"status\";s:9:\"COMPLETED\";s:9:\"sale_time\";s:19:\"2026-06-11 09:27:00\";s:9:\"voided_by\";N;s:11:\"void_reason\";N;s:10:\"created_at\";s:19:\"2026-06-11 14:42:19\";s:10:\"updated_at\";s:19:\"2026-06-11 14:42:19\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:2:{s:12:\"total_amount\";s:9:\"decimal:2\";s:9:\"sale_time\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:5:\"shift\";r:54;}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:7:{i:0;s:8:\"shift_id\";i:1;s:12:\"total_amount\";i:2;s:14:\"payment_method\";i:3;s:6:\"status\";i:4;s:9:\"sale_time\";i:5;s:9:\"voided_by\";i:6;s:11:\"void_reason\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:14:\"salesByCashier\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:15:\"App\\Models\\Sale\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:5:\"sales\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:5:{s:12:\"cashier_name\";s:13:\"Cajero Prueba\";s:10:\"cash_total\";s:6:\"308.00\";s:8:\"qr_total\";s:6:\"174.00\";s:11:\"grand_total\";s:6:\"482.00\";s:10:\"sale_count\";i:7;}s:11:\"\0*\0original\";a:5:{s:12:\"cashier_name\";s:13:\"Cajero Prueba\";s:10:\"cash_total\";s:6:\"308.00\";s:8:\"qr_total\";s:6:\"174.00\";s:11:\"grand_total\";s:6:\"482.00\";s:10:\"sale_count\";i:7;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:2:{s:12:\"total_amount\";s:9:\"decimal:2\";s:9:\"sale_time\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:7:{i:0;s:8:\"shift_id\";i:1;s:12:\"total_amount\";i:2;s:14:\"payment_method\";i:3;s:6:\"status\";i:4;s:9:\"sale_time\";i:5;s:9:\"voided_by\";i:6;s:11:\"void_reason\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:10:\"hourlyData\";a:6:{i:0;a:4:{s:5:\"label\";s:5:\"09:00\";s:5:\"total\";d:79;s:5:\"count\";i:1;s:7:\"isToday\";b:0;}i:1;a:4:{s:5:\"label\";s:5:\"10:00\";s:5:\"total\";d:118;s:5:\"count\";i:1;s:7:\"isToday\";b:0;}i:2;a:4:{s:5:\"label\";s:5:\"12:00\";s:5:\"total\";d:140;s:5:\"count\";i:3;s:7:\"isToday\";b:0;}i:3;a:4:{s:5:\"label\";s:5:\"13:00\";s:5:\"total\";d:72;s:5:\"count\";i:1;s:7:\"isToday\";b:0;}i:4;a:4:{s:5:\"label\";s:5:\"14:00\";s:5:\"total\";d:73;s:5:\"count\";i:1;s:7:\"isToday\";b:1;}i:5;a:4:{s:5:\"label\";s:5:\"15:00\";s:5:\"total\";i:0;s:5:\"count\";i:0;s:7:\"isToday\";b:0;}}s:11:\"topProducts\";O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:5:{i:0;O:8:\"stdClass\":3:{s:4:\"name\";s:13:\"Naicha Matcha\";s:10:\"units_sold\";s:1:\"7\";s:7:\"revenue\";s:6:\"140.00\";}i:1;O:8:\"stdClass\":3:{s:4:\"name\";s:15:\"Naicha Frutilla\";s:10:\"units_sold\";s:1:\"4\";s:7:\"revenue\";s:5:\"68.00\";}i:2;O:8:\"stdClass\":3:{s:4:\"name\";s:10:\"Taro Latte\";s:10:\"units_sold\";s:1:\"4\";s:7:\"revenue\";s:5:\"88.00\";}i:3;O:8:\"stdClass\":3:{s:4:\"name\";s:16:\"Naicha Maracuyá\";s:10:\"units_sold\";s:1:\"4\";s:7:\"revenue\";s:5:\"68.00\";}i:4;O:8:\"stdClass\":3:{s:4:\"name\";s:12:\"Naicha Mango\";s:10:\"units_sold\";s:1:\"4\";s:7:\"revenue\";s:5:\"68.00\";}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:9:\"chartMode\";s:3:\"day\";}',1781203498);
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
-- Table structure for table `cash_movements`
--

DROP TABLE IF EXISTS `cash_movements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cash_movements` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `shift_id` bigint unsigned NOT NULL,
  `created_by` bigint unsigned NOT NULL,
  `movement_type` enum('INCOME','EXPENSE') COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cash_movements_created_by_foreign` (`created_by`),
  KEY `cash_movements_shift_id_index` (`shift_id`),
  CONSTRAINT `cash_movements_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `cash_movements_shift_id_foreign` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cash_movements`
--

LOCK TABLES `cash_movements` WRITE;
/*!40000 ALTER TABLE `cash_movements` DISABLE KEYS */;
INSERT INTO `cash_movements` VALUES (1,3,2,'EXPENSE',15.00,'Compra de insumos (vasos, sorbetes).','2026-06-11 18:42:17','2026-06-11 18:42:17'),(2,5,2,'EXPENSE',25.00,'Compra de insumos (vasos, sorbetes).','2026-06-11 18:42:18','2026-06-11 18:42:18'),(3,6,2,'EXPENSE',46.00,'Compra de insumos (vasos, sorbetes).','2026-06-11 18:42:18','2026-06-11 18:42:18'),(4,7,2,'EXPENSE',48.00,'Compra de insumos (vasos, sorbetes).','2026-06-11 18:42:18','2026-06-11 18:42:18'),(5,8,2,'EXPENSE',38.00,'Compra de insumos (vasos, sorbetes).','2026-06-11 18:42:18','2026-06-11 18:42:18'),(6,9,2,'EXPENSE',36.00,'Compra de insumos (vasos, sorbetes).','2026-06-11 18:42:19','2026-06-11 18:42:19'),(7,10,2,'EXPENSE',29.00,'Compra de insumos (vasos, sorbetes).','2026-06-11 18:42:19','2026-06-11 18:42:19'),(8,12,2,'EXPENSE',33.00,'Compra de insumos (vasos, sorbetes).','2026-06-11 18:42:19','2026-06-11 18:42:19'),(9,14,2,'EXPENSE',56.00,'Compra de insumos (vasos, sorbetes).','2026-06-11 18:42:20','2026-06-11 18:42:20');
/*!40000 ALTER TABLE `cash_movements` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_04_05_012555_create_branches_table',1),(5,'2026_04_05_012556_create_roles_table',1),(6,'2026_04_05_012557_create_products_table',1),(7,'2026_04_05_012558_create_shifts_table',1),(8,'2026_04_05_012559_create_sales_table',1),(9,'2026_04_05_012600_create_cash_movements_table',1),(10,'2026_04_05_012732_create_audit_logs_table',1),(11,'2026_04_05_013659_create_shift_stock_table',1),(12,'2026_04_05_013700_create_sale_details_table',1),(13,'2026_04_05_032215_create_sessions_table',1),(14,'2026_04_05_170610_add_email_to_users_table',1),(15,'2026_04_27_214828_add_inconsistency_class_to_shifts',1),(16,'2026_05_12_000001_add_performance_indexes_to_sales_table',1),(17,'2026_05_12_000002_add_scheduling_to_shifts_table',1),(18,'2026_05_13_000001_replace_topping_extra_with_specific_toppings',2),(19,'2026_05_18_000001_add_missing_performance_indexes',3),(20,'2026_06_01_000001_add_category_to_products_table',3);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` bigint unsigned NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `category` enum('BEBIDA','TOPPING') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'BEBIDA',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `products_branch_id_is_active_index` (`branch_id`,`is_active`),
  CONSTRAINT `products_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (9,1,'Naicha Original',15.00,'BEBIDA',1,'2026-06-11 18:24:28','2026-06-11 18:24:28'),(10,1,'Naicha Frutilla',17.00,'BEBIDA',1,'2026-06-11 18:24:28','2026-06-11 18:24:28'),(11,1,'Naicha Mango',17.00,'BEBIDA',1,'2026-06-11 18:24:28','2026-06-11 18:24:28'),(12,1,'Naicha Maracuyá',17.00,'BEBIDA',1,'2026-06-11 18:24:28','2026-06-11 18:24:28'),(13,1,'Naicha Matcha',20.00,'BEBIDA',1,'2026-06-11 18:24:28','2026-06-11 18:24:28'),(14,1,'Taro Latte',22.00,'BEBIDA',1,'2026-06-11 18:24:28','2026-06-11 18:24:28'),(15,1,'Tapioca Pearls',5.00,'TOPPING',1,'2026-06-11 18:24:28','2026-06-11 18:24:28'),(16,1,'Nata de Coco',5.00,'TOPPING',1,'2026-06-11 18:24:28','2026-06-11 18:24:28'),(17,1,'Pudding Jelly',5.00,'TOPPING',1,'2026-06-11 18:24:28','2026-06-11 18:24:28'),(18,1,'Oreo Crumbs',5.00,'TOPPING',1,'2026-06-11 18:24:28','2026-06-11 18:24:28'),(19,1,'Mango Boba',5.00,'TOPPING',1,'2026-06-11 18:24:28','2026-06-11 18:24:28'),(20,1,'Strawberry Boba',5.00,'TOPPING',1,'2026-06-11 18:24:28','2026-06-11 18:24:28'),(21,1,'Passion Boba',5.00,'TOPPING',1,'2026-06-11 18:24:28','2026-06-11 18:24:28'),(22,1,'Coffee Jelly',5.00,'TOPPING',1,'2026-06-11 18:24:28','2026-06-11 18:24:28');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`),
  UNIQUE KEY `roles_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Administrador','admin','2026-06-11 18:24:28','2026-06-11 18:24:28'),(2,'Cajero','cajero','2026-06-11 18:24:28','2026-06-11 18:24:28');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sale_details`
--

DROP TABLE IF EXISTS `sale_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sale_details` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sale_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `quantity` int unsigned NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sale_details_product_id_foreign` (`product_id`),
  KEY `sale_details_sale_id_index` (`sale_id`),
  KEY `idx_sale_details_sale_product` (`sale_id`,`product_id`),
  CONSTRAINT `sale_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  CONSTRAINT `sale_details_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=347 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sale_details`
--

LOCK TABLES `sale_details` WRITE;
/*!40000 ALTER TABLE `sale_details` DISABLE KEYS */;
INSERT INTO `sale_details` VALUES (1,1,13,1,20.00,20.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(2,1,14,2,22.00,44.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(3,1,13,1,20.00,20.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(4,2,12,2,17.00,34.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(5,2,10,2,17.00,34.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(6,2,11,2,17.00,34.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(7,3,9,2,15.00,30.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(8,3,9,1,15.00,15.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(9,3,13,1,20.00,20.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(10,3,16,1,5.00,5.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(11,4,14,1,22.00,22.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(12,4,10,1,17.00,17.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(13,5,12,1,17.00,17.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(14,5,13,1,20.00,20.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(15,6,12,1,17.00,17.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(16,6,11,1,17.00,17.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(17,6,13,2,20.00,40.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(18,6,21,1,5.00,5.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(19,7,12,2,17.00,34.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(20,7,9,1,15.00,15.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(21,7,19,1,5.00,5.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(22,8,13,1,20.00,20.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(23,9,9,1,15.00,15.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(24,9,19,1,5.00,5.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(25,10,11,1,17.00,17.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(26,10,11,1,17.00,17.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(27,11,14,1,22.00,22.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(28,11,10,1,17.00,17.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(29,11,14,1,22.00,22.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(30,12,9,2,15.00,30.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(31,12,11,1,17.00,17.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(32,12,12,1,17.00,17.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(33,13,9,2,15.00,30.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(34,13,10,2,17.00,34.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(35,14,10,2,17.00,34.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(36,14,11,2,17.00,34.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(37,14,16,1,5.00,5.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(38,15,12,1,17.00,17.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(39,15,9,1,15.00,15.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(40,15,15,1,5.00,5.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(41,16,10,2,17.00,34.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(42,16,10,1,17.00,17.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(43,16,21,1,5.00,5.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(44,17,10,2,17.00,34.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(45,17,9,1,15.00,15.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(46,17,17,1,5.00,5.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(47,18,11,2,17.00,34.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(48,19,11,2,17.00,34.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(49,19,9,1,15.00,15.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(50,19,21,1,5.00,5.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(51,20,13,1,20.00,20.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(52,21,9,2,15.00,30.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(53,21,14,2,22.00,44.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(54,21,21,1,5.00,5.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(55,22,11,1,17.00,17.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(56,22,12,2,17.00,34.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(57,23,11,1,17.00,17.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(58,23,11,1,17.00,17.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(59,23,10,2,17.00,34.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(60,23,16,1,5.00,5.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(61,24,13,1,20.00,20.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(62,24,14,2,22.00,44.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(63,25,9,2,15.00,30.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(64,25,12,1,17.00,17.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(65,26,13,1,20.00,20.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(66,26,10,2,17.00,34.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(67,27,10,2,17.00,34.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(68,27,10,1,17.00,17.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(69,28,13,1,20.00,20.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(70,28,14,2,22.00,44.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(71,28,16,1,5.00,5.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(72,29,10,2,17.00,34.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(73,29,11,1,17.00,17.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(74,30,13,1,20.00,20.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(75,30,12,2,17.00,34.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(76,30,10,2,17.00,34.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(77,30,18,1,5.00,5.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(78,31,14,2,22.00,44.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(79,31,11,2,17.00,34.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(80,31,10,2,17.00,34.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(81,31,18,1,5.00,5.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(82,32,13,2,20.00,40.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(83,33,14,2,22.00,44.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(84,33,14,1,22.00,22.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(85,33,14,2,22.00,44.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(86,34,14,2,22.00,44.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(87,35,14,1,22.00,22.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(88,35,22,1,5.00,5.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(89,36,10,2,17.00,34.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(90,36,12,1,17.00,17.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(91,36,11,1,17.00,17.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(92,37,14,2,22.00,44.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(93,37,21,1,5.00,5.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(94,38,14,1,22.00,22.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(95,38,11,2,17.00,34.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(96,39,9,1,15.00,15.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(97,39,12,1,17.00,17.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(98,39,10,2,17.00,34.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(99,40,10,2,17.00,34.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(100,41,9,1,15.00,15.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(101,41,12,1,17.00,17.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(102,41,11,2,17.00,34.00,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(103,42,11,2,17.00,34.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(104,42,11,2,17.00,34.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(105,42,15,1,5.00,5.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(106,43,11,2,17.00,34.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(107,44,10,1,17.00,17.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(108,44,13,1,20.00,20.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(109,44,14,2,22.00,44.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(110,45,14,1,22.00,22.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(111,45,13,1,20.00,20.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(112,45,10,2,17.00,34.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(113,45,20,1,5.00,5.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(114,46,14,2,22.00,44.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(115,46,14,1,22.00,22.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(116,47,12,1,17.00,17.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(117,47,14,1,22.00,22.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(118,48,12,1,17.00,17.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(119,48,15,1,5.00,5.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(120,49,14,2,22.00,44.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(121,50,12,2,17.00,34.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(122,50,10,1,17.00,17.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(123,50,14,1,22.00,22.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(124,51,12,2,17.00,34.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(125,51,11,1,17.00,17.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(126,52,14,2,22.00,44.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(127,52,12,1,17.00,17.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(128,52,13,2,20.00,40.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(129,53,11,2,17.00,34.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(130,53,14,2,22.00,44.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(131,53,13,2,20.00,40.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(132,53,21,1,5.00,5.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(133,54,12,2,17.00,34.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(134,55,12,2,17.00,34.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(135,56,14,2,22.00,44.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(136,56,9,1,15.00,15.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(137,56,9,1,15.00,15.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(138,56,19,1,5.00,5.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(139,57,14,2,22.00,44.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(140,57,14,2,22.00,44.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(141,58,10,1,17.00,17.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(142,59,10,2,17.00,34.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(143,59,10,1,17.00,17.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(144,59,12,1,17.00,17.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(145,59,17,1,5.00,5.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(146,60,11,2,17.00,34.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(147,60,14,2,22.00,44.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(148,60,15,1,5.00,5.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(149,61,14,2,22.00,44.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(150,61,17,1,5.00,5.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(151,62,13,2,20.00,40.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(152,62,12,2,17.00,34.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(153,62,10,1,17.00,17.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(154,62,17,1,5.00,5.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(155,63,11,2,17.00,34.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(156,63,10,1,17.00,17.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(157,63,12,1,17.00,17.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(158,64,13,1,20.00,20.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(159,64,10,1,17.00,17.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(160,65,10,1,17.00,17.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(161,65,12,1,17.00,17.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(162,65,12,1,17.00,17.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(163,66,9,1,15.00,15.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(164,66,12,2,17.00,34.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(165,67,9,2,15.00,30.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(166,67,14,1,22.00,22.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(167,68,14,2,22.00,44.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(168,68,13,2,20.00,40.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(169,68,10,2,17.00,34.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(170,68,16,1,5.00,5.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(171,69,11,2,17.00,34.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(172,69,11,1,17.00,17.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(173,69,17,1,5.00,5.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(174,70,9,1,15.00,15.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(175,70,9,2,15.00,30.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(176,70,19,1,5.00,5.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(177,71,14,2,22.00,44.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(178,71,11,2,17.00,34.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(179,72,9,1,15.00,15.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(180,72,9,1,15.00,15.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(181,73,13,2,20.00,40.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(182,74,12,2,17.00,34.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(183,74,20,1,5.00,5.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(184,75,11,2,17.00,34.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(185,75,14,2,22.00,44.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(186,75,11,1,17.00,17.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(187,75,21,1,5.00,5.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(188,76,11,1,17.00,17.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(189,77,11,2,17.00,34.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(190,77,13,2,20.00,40.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(191,78,11,2,17.00,34.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(192,78,13,1,20.00,20.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(193,79,11,2,17.00,34.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(194,79,13,2,20.00,40.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(195,79,14,2,22.00,44.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(196,80,11,1,17.00,17.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(197,81,14,1,22.00,22.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(198,82,9,2,15.00,30.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(199,82,10,2,17.00,34.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(200,82,21,1,5.00,5.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(201,83,13,2,20.00,40.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(202,83,11,2,17.00,34.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(203,83,19,1,5.00,5.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(204,84,9,2,15.00,30.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(205,84,12,2,17.00,34.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(206,84,10,2,17.00,34.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(207,85,14,1,22.00,22.00,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(208,85,9,2,15.00,30.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(209,85,11,2,17.00,34.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(210,85,21,1,5.00,5.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(211,86,9,2,15.00,30.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(212,87,14,2,22.00,44.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(213,87,9,1,15.00,15.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(214,88,9,2,15.00,30.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(215,89,13,2,20.00,40.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(216,89,12,2,17.00,34.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(217,90,10,1,17.00,17.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(218,90,16,1,5.00,5.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(219,91,14,1,22.00,22.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(220,91,9,2,15.00,30.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(221,91,9,1,15.00,15.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(222,92,11,1,17.00,17.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(223,92,9,1,15.00,15.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(224,92,9,1,15.00,15.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(225,92,15,1,5.00,5.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(226,93,13,1,20.00,20.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(227,93,14,1,22.00,22.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(228,93,15,1,5.00,5.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(229,94,10,1,17.00,17.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(230,94,20,1,5.00,5.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(231,95,10,1,17.00,17.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(232,95,9,1,15.00,15.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(233,96,13,2,20.00,40.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(234,96,18,1,5.00,5.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(235,97,9,2,15.00,30.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(236,97,14,1,22.00,22.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(237,97,13,1,20.00,20.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(238,98,14,2,22.00,44.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(239,98,21,1,5.00,5.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(240,99,10,2,17.00,34.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(241,99,13,2,20.00,40.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(242,99,16,1,5.00,5.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(243,100,11,1,17.00,17.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(244,100,12,2,17.00,34.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(245,100,17,1,5.00,5.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(246,101,13,2,20.00,40.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(247,101,14,2,22.00,44.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(248,101,12,2,17.00,34.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(249,102,11,1,17.00,17.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(250,102,14,1,22.00,22.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(251,103,11,2,17.00,34.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(252,103,10,2,17.00,34.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(253,103,17,1,5.00,5.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(254,104,13,1,20.00,20.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(255,105,14,1,22.00,22.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(256,105,10,2,17.00,34.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(257,105,9,2,15.00,30.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(258,106,11,1,17.00,17.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(259,106,12,1,17.00,17.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(260,106,9,2,15.00,30.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(261,106,19,1,5.00,5.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(262,107,14,1,22.00,22.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(263,108,11,2,17.00,34.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(264,109,14,1,22.00,22.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(265,109,9,1,15.00,15.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(266,109,10,2,17.00,34.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(267,109,20,1,5.00,5.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(268,110,10,2,17.00,34.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(269,110,13,2,20.00,40.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(270,110,14,2,22.00,44.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(271,111,14,1,22.00,22.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(272,111,22,1,5.00,5.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(273,112,9,2,15.00,30.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(274,112,13,1,20.00,20.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(275,112,14,2,22.00,44.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(276,113,12,2,17.00,34.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(277,113,14,1,22.00,22.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(278,113,12,2,17.00,34.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(279,113,18,1,5.00,5.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(280,114,13,1,20.00,20.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(281,114,16,1,5.00,5.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(282,115,14,2,22.00,44.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(283,116,14,1,22.00,22.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(284,116,17,1,5.00,5.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(285,117,9,1,15.00,15.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(286,117,11,1,17.00,17.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(287,118,14,2,22.00,44.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(288,118,10,1,17.00,17.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(289,118,13,2,20.00,40.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(290,118,22,1,5.00,5.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(291,119,14,1,22.00,22.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(292,119,11,2,17.00,34.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(293,119,9,1,15.00,15.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(294,120,13,2,20.00,40.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(295,121,9,1,15.00,15.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(296,121,10,2,17.00,34.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(297,121,21,1,5.00,5.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(298,122,12,2,17.00,34.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(299,122,18,1,5.00,5.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(300,123,9,2,15.00,30.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(301,123,12,2,17.00,34.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(302,123,13,1,20.00,20.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(303,124,13,2,20.00,40.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(304,124,14,2,22.00,44.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(305,124,12,2,17.00,34.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(306,124,19,1,5.00,5.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(307,125,11,1,17.00,17.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(308,125,17,1,5.00,5.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(309,126,9,2,15.00,30.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(310,126,13,1,20.00,20.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(311,126,9,2,15.00,30.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(312,126,15,1,5.00,5.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(313,127,13,2,20.00,40.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(314,127,13,1,20.00,20.00,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(315,128,11,2,17.00,34.00,'2026-06-11 18:42:20','2026-06-11 18:42:20'),(316,128,13,1,20.00,20.00,'2026-06-11 18:42:20','2026-06-11 18:42:20'),(317,129,13,1,20.00,20.00,'2026-06-11 18:42:20','2026-06-11 18:42:20'),(318,129,10,2,17.00,34.00,'2026-06-11 18:42:20','2026-06-11 18:42:20'),(319,129,9,2,15.00,30.00,'2026-06-11 18:42:20','2026-06-11 18:42:20'),(320,130,13,2,20.00,40.00,'2026-06-11 18:42:20','2026-06-11 18:42:20'),(321,130,11,2,17.00,34.00,'2026-06-11 18:42:20','2026-06-11 18:42:20'),(322,131,13,1,20.00,20.00,'2026-06-11 18:42:20','2026-06-11 18:42:20'),(323,132,9,2,15.00,30.00,'2026-06-11 18:42:20','2026-06-11 18:42:20'),(324,132,11,2,17.00,34.00,'2026-06-11 18:42:20','2026-06-11 18:42:20'),(325,132,11,1,17.00,17.00,'2026-06-11 18:42:20','2026-06-11 18:42:20'),(326,133,12,1,17.00,17.00,'2026-06-11 18:42:20','2026-06-11 18:42:20'),(327,133,9,1,15.00,15.00,'2026-06-11 18:42:20','2026-06-11 18:42:20'),(328,133,9,1,15.00,15.00,'2026-06-11 18:42:20','2026-06-11 18:42:20'),(329,134,14,2,22.00,44.00,'2026-06-11 18:42:20','2026-06-11 18:42:20'),(330,134,9,1,15.00,15.00,'2026-06-11 18:42:20','2026-06-11 18:42:20'),(331,134,21,1,5.00,5.00,'2026-06-11 18:42:20','2026-06-11 18:42:20'),(332,135,14,2,22.00,44.00,'2026-06-11 18:42:20','2026-06-11 18:42:20'),(333,135,14,1,22.00,22.00,'2026-06-11 18:42:20','2026-06-11 18:42:20'),(334,136,14,2,22.00,44.00,'2026-06-11 18:42:20','2026-06-11 18:42:20'),(335,136,13,2,20.00,40.00,'2026-06-11 18:42:20','2026-06-11 18:42:20'),(336,136,10,2,17.00,34.00,'2026-06-11 18:42:20','2026-06-11 18:42:20'),(337,137,11,2,17.00,34.00,'2026-06-11 18:42:20','2026-06-11 18:42:20'),(338,137,12,1,17.00,17.00,'2026-06-11 18:42:20','2026-06-11 18:42:20'),(339,137,13,2,20.00,40.00,'2026-06-11 18:42:20','2026-06-11 18:42:20'),(340,138,14,1,22.00,22.00,'2026-06-11 18:42:20','2026-06-11 18:42:20'),(341,138,9,2,15.00,30.00,'2026-06-11 18:42:20','2026-06-11 18:42:20'),(342,138,12,2,17.00,34.00,'2026-06-11 18:42:20','2026-06-11 18:42:20'),(343,139,13,2,20.00,40.00,'2026-06-11 18:42:20','2026-06-11 18:42:20'),(344,140,14,2,22.00,44.00,'2026-06-11 18:42:20','2026-06-11 18:42:20'),(345,140,9,2,15.00,30.00,'2026-06-11 18:42:20','2026-06-11 18:42:20'),(346,141,13,2,20.00,40.00,'2026-06-11 18:42:20','2026-06-11 18:42:20');
/*!40000 ALTER TABLE `sale_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sales`
--

DROP TABLE IF EXISTS `sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sales` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `shift_id` bigint unsigned NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` enum('CASH','QR') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('COMPLETED','VOIDED') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'COMPLETED',
  `sale_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `voided_by` bigint unsigned DEFAULT NULL,
  `void_reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sales_voided_by_foreign` (`voided_by`),
  KEY `sales_shift_id_index` (`shift_id`),
  KEY `sales_sale_time_index` (`sale_time`),
  KEY `sales_shift_id_payment_method_index` (`shift_id`,`payment_method`),
  KEY `sales_shift_id_status_index` (`shift_id`,`status`),
  KEY `idx_sales_status_time` (`status`,`sale_time`),
  KEY `idx_sales_payment` (`payment_method`),
  CONSTRAINT `sales_shift_id_foreign` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`id`),
  CONSTRAINT `sales_voided_by_foreign` FOREIGN KEY (`voided_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=142 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales`
--

LOCK TABLES `sales` WRITE;
/*!40000 ALTER TABLE `sales` DISABLE KEYS */;
INSERT INTO `sales` VALUES (1,1,84.00,'QR','COMPLETED','2026-06-01 19:37:00',NULL,NULL,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(2,1,102.00,'CASH','COMPLETED','2026-06-01 19:31:00',NULL,NULL,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(3,1,70.00,'QR','COMPLETED','2026-06-01 21:14:00',NULL,NULL,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(4,1,39.00,'QR','COMPLETED','2026-06-01 17:56:00',NULL,NULL,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(5,1,37.00,'CASH','COMPLETED','2026-06-01 15:20:00',NULL,NULL,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(6,1,79.00,'CASH','VOIDED','2026-06-01 14:02:00',1,'Pedido cancelado por el cliente.','2026-06-11 18:42:17','2026-06-11 18:42:17'),(7,1,54.00,'QR','COMPLETED','2026-06-01 17:15:00',NULL,NULL,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(8,1,20.00,'QR','COMPLETED','2026-06-01 13:23:00',NULL,NULL,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(9,1,20.00,'QR','COMPLETED','2026-06-01 15:41:00',NULL,NULL,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(10,1,34.00,'QR','COMPLETED','2026-06-01 18:04:00',NULL,NULL,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(11,1,61.00,'QR','COMPLETED','2026-06-01 13:59:00',NULL,NULL,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(12,1,64.00,'QR','COMPLETED','2026-06-01 15:13:00',NULL,NULL,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(13,2,64.00,'QR','COMPLETED','2026-06-02 21:48:00',NULL,NULL,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(14,2,73.00,'QR','COMPLETED','2026-06-02 14:14:00',NULL,NULL,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(15,2,37.00,'QR','COMPLETED','2026-06-02 20:45:00',NULL,NULL,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(16,2,56.00,'QR','COMPLETED','2026-06-02 14:38:00',NULL,NULL,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(17,2,54.00,'QR','COMPLETED','2026-06-02 14:26:00',NULL,NULL,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(18,2,34.00,'CASH','COMPLETED','2026-06-02 14:31:00',NULL,NULL,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(19,3,54.00,'QR','COMPLETED','2026-06-03 13:29:00',NULL,NULL,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(20,3,20.00,'CASH','COMPLETED','2026-06-03 15:23:00',NULL,NULL,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(21,3,79.00,'QR','COMPLETED','2026-06-03 13:42:00',NULL,NULL,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(22,3,51.00,'QR','COMPLETED','2026-06-03 14:10:00',NULL,NULL,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(23,3,73.00,'QR','VOIDED','2026-06-03 18:49:00',1,'Pedido cancelado por el cliente.','2026-06-11 18:42:17','2026-06-11 18:42:17'),(24,3,64.00,'QR','COMPLETED','2026-06-03 14:49:00',NULL,NULL,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(25,3,47.00,'QR','VOIDED','2026-06-03 17:48:00',1,'Pedido cancelado por el cliente.','2026-06-11 18:42:17','2026-06-11 18:42:17'),(26,3,54.00,'QR','COMPLETED','2026-06-03 14:25:00',NULL,NULL,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(27,3,51.00,'CASH','COMPLETED','2026-06-03 20:20:00',NULL,NULL,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(28,3,69.00,'QR','COMPLETED','2026-06-03 14:14:00',NULL,NULL,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(29,3,51.00,'QR','COMPLETED','2026-06-03 18:37:00',NULL,NULL,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(30,3,93.00,'QR','COMPLETED','2026-06-03 20:57:00',NULL,NULL,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(31,4,117.00,'QR','COMPLETED','2026-06-04 16:25:00',NULL,NULL,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(32,4,40.00,'QR','VOIDED','2026-06-04 21:14:00',1,'Pedido cancelado por el cliente.','2026-06-11 18:42:17','2026-06-11 18:42:17'),(33,4,110.00,'QR','COMPLETED','2026-06-04 21:51:00',NULL,NULL,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(34,4,44.00,'QR','COMPLETED','2026-06-04 13:53:00',NULL,NULL,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(35,4,27.00,'QR','VOIDED','2026-06-04 16:48:00',1,'Pedido cancelado por el cliente.','2026-06-11 18:42:17','2026-06-11 18:42:17'),(36,4,68.00,'CASH','COMPLETED','2026-06-04 20:03:00',NULL,NULL,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(37,4,49.00,'CASH','COMPLETED','2026-06-04 15:34:00',NULL,NULL,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(38,4,56.00,'QR','COMPLETED','2026-06-04 18:35:00',NULL,NULL,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(39,4,66.00,'CASH','COMPLETED','2026-06-04 17:07:00',NULL,NULL,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(40,4,34.00,'QR','COMPLETED','2026-06-04 14:10:00',NULL,NULL,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(41,4,66.00,'QR','COMPLETED','2026-06-04 14:00:00',NULL,NULL,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(42,5,73.00,'QR','COMPLETED','2026-06-05 18:36:00',NULL,NULL,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(43,5,34.00,'QR','COMPLETED','2026-06-05 16:25:00',NULL,NULL,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(44,5,81.00,'QR','COMPLETED','2026-06-05 21:18:00',NULL,NULL,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(45,5,81.00,'CASH','COMPLETED','2026-06-05 14:36:00',NULL,NULL,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(46,5,66.00,'QR','COMPLETED','2026-06-05 21:50:00',NULL,NULL,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(47,5,39.00,'CASH','COMPLETED','2026-06-05 19:05:00',NULL,NULL,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(48,5,22.00,'CASH','COMPLETED','2026-06-05 18:20:00',NULL,NULL,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(49,5,44.00,'CASH','COMPLETED','2026-06-05 19:43:00',NULL,NULL,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(50,5,73.00,'CASH','COMPLETED','2026-06-05 15:01:00',NULL,NULL,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(51,5,51.00,'CASH','COMPLETED','2026-06-05 19:31:00',NULL,NULL,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(52,5,101.00,'CASH','VOIDED','2026-06-05 21:01:00',1,'Pedido cancelado por el cliente.','2026-06-11 18:42:18','2026-06-11 18:42:18'),(53,6,123.00,'CASH','COMPLETED','2026-06-06 16:05:00',NULL,NULL,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(54,6,34.00,'QR','COMPLETED','2026-06-06 17:40:00',NULL,NULL,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(55,6,34.00,'CASH','COMPLETED','2026-06-06 14:03:00',NULL,NULL,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(56,6,79.00,'CASH','COMPLETED','2026-06-06 18:10:00',NULL,NULL,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(57,6,88.00,'QR','COMPLETED','2026-06-06 21:46:00',NULL,NULL,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(58,6,17.00,'CASH','COMPLETED','2026-06-06 17:53:00',NULL,NULL,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(59,6,73.00,'QR','VOIDED','2026-06-06 19:03:00',1,'Pedido cancelado por el cliente.','2026-06-11 18:42:18','2026-06-11 18:42:18'),(60,6,83.00,'QR','COMPLETED','2026-06-06 17:36:00',NULL,NULL,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(61,6,49.00,'CASH','COMPLETED','2026-06-06 16:38:00',NULL,NULL,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(62,6,96.00,'QR','COMPLETED','2026-06-06 15:53:00',NULL,NULL,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(63,7,68.00,'QR','COMPLETED','2026-06-08 14:18:00',NULL,NULL,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(64,7,37.00,'CASH','COMPLETED','2026-06-08 16:28:00',NULL,NULL,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(65,7,51.00,'QR','VOIDED','2026-06-08 19:13:00',1,'Pedido cancelado por el cliente.','2026-06-11 18:42:18','2026-06-11 18:42:18'),(66,7,49.00,'QR','COMPLETED','2026-06-08 19:36:00',NULL,NULL,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(67,7,52.00,'QR','COMPLETED','2026-06-08 19:01:00',NULL,NULL,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(68,7,123.00,'CASH','COMPLETED','2026-06-08 20:46:00',NULL,NULL,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(69,7,56.00,'QR','COMPLETED','2026-06-08 20:13:00',NULL,NULL,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(70,7,50.00,'QR','COMPLETED','2026-06-08 13:18:00',NULL,NULL,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(71,8,78.00,'QR','COMPLETED','2026-06-09 19:42:00',NULL,NULL,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(72,8,30.00,'QR','COMPLETED','2026-06-09 13:55:00',NULL,NULL,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(73,8,40.00,'QR','COMPLETED','2026-06-09 14:49:00',NULL,NULL,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(74,8,39.00,'QR','COMPLETED','2026-06-09 16:43:00',NULL,NULL,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(75,8,100.00,'CASH','COMPLETED','2026-06-09 17:07:00',NULL,NULL,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(76,8,17.00,'QR','COMPLETED','2026-06-09 13:30:00',NULL,NULL,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(77,8,74.00,'CASH','COMPLETED','2026-06-09 15:58:00',NULL,NULL,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(78,8,54.00,'QR','VOIDED','2026-06-09 13:14:00',1,'Pedido cancelado por el cliente.','2026-06-11 18:42:18','2026-06-11 18:42:18'),(79,8,118.00,'QR','COMPLETED','2026-06-09 13:19:00',NULL,NULL,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(80,8,17.00,'QR','COMPLETED','2026-06-09 15:45:00',NULL,NULL,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(81,8,22.00,'QR','COMPLETED','2026-06-09 19:29:00',NULL,NULL,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(82,8,69.00,'QR','COMPLETED','2026-06-09 14:05:00',NULL,NULL,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(83,8,79.00,'CASH','COMPLETED','2026-06-09 19:54:00',NULL,NULL,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(84,8,98.00,'QR','COMPLETED','2026-06-09 16:57:00',NULL,NULL,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(85,9,91.00,'CASH','COMPLETED','2026-06-10 19:20:00',NULL,NULL,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(86,9,30.00,'QR','COMPLETED','2026-06-10 19:25:00',NULL,NULL,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(87,9,59.00,'QR','COMPLETED','2026-06-10 19:08:00',NULL,NULL,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(88,9,30.00,'CASH','COMPLETED','2026-06-10 13:07:00',NULL,NULL,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(89,9,74.00,'CASH','COMPLETED','2026-06-10 21:18:00',NULL,NULL,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(90,9,22.00,'CASH','COMPLETED','2026-06-10 18:59:00',NULL,NULL,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(91,9,67.00,'QR','VOIDED','2026-06-10 18:29:00',1,'Pedido cancelado por el cliente.','2026-06-11 18:42:19','2026-06-11 18:42:19'),(92,9,52.00,'QR','VOIDED','2026-06-10 16:39:00',1,'Pedido cancelado por el cliente.','2026-06-11 18:42:19','2026-06-11 18:42:19'),(93,9,47.00,'CASH','COMPLETED','2026-06-10 18:04:00',NULL,NULL,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(94,9,22.00,'CASH','COMPLETED','2026-06-10 20:46:00',NULL,NULL,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(95,9,32.00,'QR','COMPLETED','2026-06-10 19:57:00',NULL,NULL,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(96,10,45.00,'CASH','COMPLETED','2026-06-11 16:30:00',NULL,NULL,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(97,10,72.00,'CASH','COMPLETED','2026-06-11 17:23:00',NULL,NULL,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(98,10,49.00,'QR','VOIDED','2026-06-11 19:53:00',1,'Pedido cancelado por el cliente.','2026-06-11 18:42:19','2026-06-11 18:42:19'),(99,10,79.00,'QR','COMPLETED','2026-06-11 13:27:00',NULL,NULL,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(100,10,56.00,'QR','COMPLETED','2026-06-11 16:56:00',NULL,NULL,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(101,10,118.00,'CASH','COMPLETED','2026-06-11 14:39:00',NULL,NULL,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(102,10,39.00,'QR','COMPLETED','2026-06-11 16:16:00',NULL,NULL,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(103,10,73.00,'CASH','COMPLETED','2026-06-11 18:56:00',NULL,NULL,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(104,11,20.00,'CASH','COMPLETED','2026-06-12 17:52:00',NULL,NULL,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(105,11,86.00,'QR','COMPLETED','2026-06-12 13:20:00',NULL,NULL,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(106,11,69.00,'QR','COMPLETED','2026-06-12 20:49:00',NULL,NULL,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(107,11,22.00,'QR','COMPLETED','2026-06-12 16:50:00',NULL,NULL,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(108,11,34.00,'QR','COMPLETED','2026-06-12 15:03:00',NULL,NULL,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(109,11,76.00,'CASH','COMPLETED','2026-06-12 14:33:00',NULL,NULL,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(110,11,118.00,'QR','COMPLETED','2026-06-12 19:00:00',NULL,NULL,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(111,11,27.00,'CASH','COMPLETED','2026-06-12 19:55:00',NULL,NULL,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(112,11,94.00,'QR','COMPLETED','2026-06-12 21:02:00',NULL,NULL,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(113,11,95.00,'CASH','COMPLETED','2026-06-12 15:20:00',NULL,NULL,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(114,12,25.00,'CASH','COMPLETED','2026-06-13 19:25:00',NULL,NULL,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(115,12,44.00,'CASH','COMPLETED','2026-06-13 17:46:00',NULL,NULL,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(116,12,27.00,'CASH','COMPLETED','2026-06-13 17:31:00',NULL,NULL,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(117,12,32.00,'QR','COMPLETED','2026-06-13 18:32:00',NULL,NULL,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(118,12,106.00,'CASH','COMPLETED','2026-06-13 21:08:00',NULL,NULL,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(119,12,71.00,'QR','COMPLETED','2026-06-13 13:12:00',NULL,NULL,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(120,12,40.00,'QR','COMPLETED','2026-06-13 21:02:00',NULL,NULL,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(121,12,54.00,'QR','COMPLETED','2026-06-13 16:44:00',NULL,NULL,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(122,13,39.00,'CASH','COMPLETED','2026-06-15 14:52:00',NULL,NULL,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(123,13,84.00,'CASH','COMPLETED','2026-06-15 16:29:00',NULL,NULL,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(124,13,123.00,'CASH','COMPLETED','2026-06-15 17:49:00',NULL,NULL,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(125,13,22.00,'CASH','COMPLETED','2026-06-15 14:52:00',NULL,NULL,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(126,13,85.00,'CASH','COMPLETED','2026-06-15 19:21:00',NULL,NULL,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(127,13,60.00,'CASH','COMPLETED','2026-06-15 15:46:00',NULL,NULL,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(128,13,54.00,'CASH','COMPLETED','2026-06-15 13:30:00',NULL,NULL,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(129,13,84.00,'CASH','VOIDED','2026-06-15 18:03:00',1,'Pedido cancelado por el cliente.','2026-06-11 18:42:20','2026-06-11 18:42:20'),(130,13,74.00,'CASH','COMPLETED','2026-06-15 17:02:00',NULL,NULL,'2026-06-11 18:42:20','2026-06-11 18:42:20'),(131,13,20.00,'QR','COMPLETED','2026-06-15 16:21:00',NULL,NULL,'2026-06-11 18:42:20','2026-06-11 18:42:20'),(132,14,81.00,'QR','COMPLETED','2026-06-16 18:59:00',NULL,NULL,'2026-06-11 18:42:20','2026-06-11 18:42:20'),(133,14,47.00,'QR','COMPLETED','2026-06-16 15:36:00',NULL,NULL,'2026-06-11 18:42:20','2026-06-11 18:42:20'),(134,14,64.00,'QR','COMPLETED','2026-06-16 18:08:00',NULL,NULL,'2026-06-11 18:42:20','2026-06-11 18:42:20'),(135,14,66.00,'QR','COMPLETED','2026-06-16 20:43:00',NULL,NULL,'2026-06-11 18:42:20','2026-06-11 18:42:20'),(136,14,118.00,'CASH','COMPLETED','2026-06-16 16:12:00',NULL,NULL,'2026-06-11 18:42:20','2026-06-11 18:42:20'),(137,14,91.00,'QR','COMPLETED','2026-06-16 19:25:00',NULL,NULL,'2026-06-11 18:42:20','2026-06-11 18:42:20'),(138,14,86.00,'QR','COMPLETED','2026-06-16 15:12:00',NULL,NULL,'2026-06-11 18:42:20','2026-06-11 18:42:20'),(139,14,40.00,'CASH','COMPLETED','2026-06-16 21:26:00',NULL,NULL,'2026-06-11 18:42:20','2026-06-11 18:42:20'),(140,14,74.00,'CASH','COMPLETED','2026-06-16 17:19:00',NULL,NULL,'2026-06-11 18:42:20','2026-06-11 18:42:20'),(141,14,40.00,'CASH','COMPLETED','2026-06-16 20:15:00',NULL,NULL,'2026-06-11 18:42:20','2026-06-11 18:42:20');
/*!40000 ALTER TABLE `sales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
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
INSERT INTO `sessions` VALUES ('8UhG7lw9tWpgbGm7LMuLrk91SjPC62NdN9tLAzww',1,'172.20.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiYU10RVpXaHBVRmN2a0ZjeEJtNlVpcWVyZVVSakRVMkM5R2J4eHM0TiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly9sb2NhbGhvc3Q6ODA4MC9hZG1pbi91c3VhcmlvcyI7czo1OiJyb3V0ZSI7czoxNzoiYWRtaW4udXNlcnMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=',1781203569),('vqRWFYAWUbqGXK2qJEyFuAW4eYC8BYEFKFXgdq4B',NULL,'172.20.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUjJrUThRUk5CN0JzTE50NDVPeDNkdEFQR3FQWG1teW02MjZmMHNTaCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo3OToiaHR0cDovL2xvY2FsaG9zdDo4MDgwL2FkbWluL3JlcG9ydGVzL2NpZXJyZS1kaWFyaW8/ZmVjaGE9MjAyNi0wNi0xMCZtb2RvPWNpZXJyZSI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjI3OiJodHRwOi8vbG9jYWxob3N0OjgwODAvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1781197851);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shift_stock`
--

DROP TABLE IF EXISTS `shift_stock`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shift_stock` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `shift_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `initial_quantity` int unsigned NOT NULL,
  `sold_quantity` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `shift_stock_shift_id_product_id_unique` (`shift_id`,`product_id`),
  UNIQUE KEY `idx_shift_stock_unique` (`shift_id`,`product_id`),
  KEY `shift_stock_product_id_foreign` (`product_id`),
  KEY `shift_stock_shift_id_index` (`shift_id`),
  CONSTRAINT `shift_stock_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  CONSTRAINT `shift_stock_shift_id_foreign` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=197 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shift_stock`
--

LOCK TABLES `shift_stock` WRITE;
/*!40000 ALTER TABLE `shift_stock` DISABLE KEYS */;
INSERT INTO `shift_stock` VALUES (1,1,9,42,7,'2026-06-11 18:42:16','2026-06-11 18:42:17'),(2,1,10,32,4,'2026-06-11 18:42:16','2026-06-11 18:42:17'),(3,1,11,40,5,'2026-06-11 18:42:16','2026-06-11 18:42:17'),(4,1,12,28,6,'2026-06-11 18:42:16','2026-06-11 18:42:17'),(5,1,13,39,5,'2026-06-11 18:42:16','2026-06-11 18:42:17'),(6,1,14,45,5,'2026-06-11 18:42:16','2026-06-11 18:42:17'),(7,1,15,39,0,'2026-06-11 18:42:16','2026-06-11 18:42:16'),(8,1,16,25,1,'2026-06-11 18:42:16','2026-06-11 18:42:17'),(9,1,17,24,0,'2026-06-11 18:42:16','2026-06-11 18:42:16'),(10,1,18,30,0,'2026-06-11 18:42:16','2026-06-11 18:42:16'),(11,1,19,35,2,'2026-06-11 18:42:16','2026-06-11 18:42:17'),(12,1,20,26,0,'2026-06-11 18:42:16','2026-06-11 18:42:16'),(13,1,21,35,0,'2026-06-11 18:42:16','2026-06-11 18:42:16'),(14,1,22,25,0,'2026-06-11 18:42:16','2026-06-11 18:42:16'),(15,2,9,27,4,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(16,2,10,35,9,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(17,2,11,29,4,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(18,2,12,31,1,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(19,2,13,41,0,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(20,2,14,25,0,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(21,2,15,33,1,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(22,2,16,21,1,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(23,2,17,37,1,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(24,2,18,32,0,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(25,2,19,30,0,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(26,2,20,20,0,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(27,2,21,36,1,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(28,2,22,22,0,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(29,3,9,45,3,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(30,3,10,26,9,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(31,3,11,27,4,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(32,3,12,30,4,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(33,3,13,42,5,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(34,3,14,39,6,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(35,3,15,40,0,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(36,3,16,20,1,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(37,3,17,29,0,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(38,3,18,28,1,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(39,3,19,32,0,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(40,3,20,31,0,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(41,3,21,35,2,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(42,3,22,30,0,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(43,4,9,27,2,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(44,4,10,25,8,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(45,4,11,40,7,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(46,4,12,34,3,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(47,4,13,43,0,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(48,4,14,31,12,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(49,4,15,37,0,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(50,4,16,32,0,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(51,4,17,31,0,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(52,4,18,21,1,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(53,4,19,20,0,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(54,4,20,23,0,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(55,4,21,21,1,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(56,4,22,25,0,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(57,5,9,32,0,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(58,5,10,41,4,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(59,5,11,41,7,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(60,5,12,44,6,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(61,5,13,29,2,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(62,5,14,40,10,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(63,5,15,21,2,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(64,5,16,37,0,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(65,5,17,33,0,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(66,5,18,28,0,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(67,5,19,39,0,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(68,5,20,38,1,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(69,5,21,38,0,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(70,5,22,26,0,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(71,6,9,31,2,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(72,6,10,33,2,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(73,6,11,42,4,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(74,6,12,34,6,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(75,6,13,38,4,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(76,6,14,45,12,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(77,6,15,29,1,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(78,6,16,26,0,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(79,6,17,35,2,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(80,6,18,30,0,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(81,6,19,20,1,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(82,6,20,25,0,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(83,6,21,26,1,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(84,6,22,21,0,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(85,7,9,27,6,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(86,7,10,45,4,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(87,7,11,40,5,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(88,7,12,43,3,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(89,7,13,42,3,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(90,7,14,42,3,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(91,7,15,29,0,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(92,7,16,39,1,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(93,7,17,33,1,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(94,7,18,25,0,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(95,7,19,22,1,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(96,7,20,25,0,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(97,7,21,26,0,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(98,7,22,38,0,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(99,8,9,45,6,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(100,8,10,43,4,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(101,8,11,25,13,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(102,8,12,42,4,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(103,8,13,42,8,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(104,8,14,33,7,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(105,8,15,22,0,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(106,8,16,36,0,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(107,8,17,22,0,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(108,8,18,20,0,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(109,8,19,29,1,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(110,8,20,34,1,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(111,8,21,39,2,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(112,8,22,40,0,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(113,9,9,26,8,'2026-06-11 18:42:18','2026-06-11 18:42:19'),(114,9,10,38,3,'2026-06-11 18:42:18','2026-06-11 18:42:19'),(115,9,11,45,2,'2026-06-11 18:42:18','2026-06-11 18:42:19'),(116,9,12,38,2,'2026-06-11 18:42:18','2026-06-11 18:42:19'),(117,9,13,36,3,'2026-06-11 18:42:18','2026-06-11 18:42:19'),(118,9,14,29,4,'2026-06-11 18:42:18','2026-06-11 18:42:19'),(119,9,15,38,1,'2026-06-11 18:42:18','2026-06-11 18:42:19'),(120,9,16,23,1,'2026-06-11 18:42:18','2026-06-11 18:42:19'),(121,9,17,40,0,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(122,9,18,26,0,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(123,9,19,25,0,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(124,9,20,32,1,'2026-06-11 18:42:18','2026-06-11 18:42:19'),(125,9,21,28,1,'2026-06-11 18:42:18','2026-06-11 18:42:19'),(126,9,22,38,0,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(127,10,9,41,2,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(128,10,10,39,4,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(129,10,11,31,4,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(130,10,12,40,4,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(131,10,13,28,7,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(132,10,14,39,4,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(133,10,15,30,0,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(134,10,16,35,1,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(135,10,17,24,2,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(136,10,18,33,1,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(137,10,19,24,0,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(138,10,20,35,0,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(139,10,21,36,0,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(140,10,22,37,0,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(141,11,9,41,7,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(142,11,10,35,6,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(143,11,11,36,3,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(144,11,12,29,5,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(145,11,13,30,4,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(146,11,14,28,9,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(147,11,15,20,0,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(148,11,16,30,0,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(149,11,17,29,0,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(150,11,18,27,1,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(151,11,19,28,1,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(152,11,20,26,1,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(153,11,21,40,0,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(154,11,22,25,1,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(155,12,9,26,3,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(156,12,10,38,3,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(157,12,11,45,3,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(158,12,12,30,0,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(159,12,13,43,5,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(160,12,14,25,6,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(161,12,15,33,0,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(162,12,16,33,1,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(163,12,17,22,1,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(164,12,18,30,0,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(165,12,19,30,0,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(166,12,20,29,0,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(167,12,21,39,1,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(168,12,22,21,1,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(169,13,9,43,6,'2026-06-11 18:42:19','2026-06-11 18:42:20'),(170,13,10,34,0,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(171,13,11,32,5,'2026-06-11 18:42:19','2026-06-11 18:42:20'),(172,13,12,39,6,'2026-06-11 18:42:19','2026-06-11 18:42:20'),(173,13,13,39,11,'2026-06-11 18:42:19','2026-06-11 18:42:20'),(174,13,14,36,2,'2026-06-11 18:42:19','2026-06-11 18:42:20'),(175,13,15,26,1,'2026-06-11 18:42:19','2026-06-11 18:42:20'),(176,13,16,28,0,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(177,13,17,27,1,'2026-06-11 18:42:19','2026-06-11 18:42:20'),(178,13,18,28,1,'2026-06-11 18:42:19','2026-06-11 18:42:20'),(179,13,19,25,1,'2026-06-11 18:42:19','2026-06-11 18:42:20'),(180,13,20,32,0,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(181,13,21,40,0,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(182,13,22,38,0,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(183,14,9,35,9,'2026-06-11 18:42:20','2026-06-11 18:42:20'),(184,14,10,29,2,'2026-06-11 18:42:20','2026-06-11 18:42:20'),(185,14,11,40,5,'2026-06-11 18:42:20','2026-06-11 18:42:20'),(186,14,12,30,4,'2026-06-11 18:42:20','2026-06-11 18:42:20'),(187,14,13,43,8,'2026-06-11 18:42:20','2026-06-11 18:42:20'),(188,14,14,42,10,'2026-06-11 18:42:20','2026-06-11 18:42:20'),(189,14,15,28,0,'2026-06-11 18:42:20','2026-06-11 18:42:20'),(190,14,16,28,0,'2026-06-11 18:42:20','2026-06-11 18:42:20'),(191,14,17,25,0,'2026-06-11 18:42:20','2026-06-11 18:42:20'),(192,14,18,30,0,'2026-06-11 18:42:20','2026-06-11 18:42:20'),(193,14,19,30,0,'2026-06-11 18:42:20','2026-06-11 18:42:20'),(194,14,20,29,0,'2026-06-11 18:42:20','2026-06-11 18:42:20'),(195,14,21,25,1,'2026-06-11 18:42:20','2026-06-11 18:42:20'),(196,14,22,39,0,'2026-06-11 18:42:20','2026-06-11 18:42:20');
/*!40000 ALTER TABLE `shift_stock` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shifts`
--

DROP TABLE IF EXISTS `shifts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shifts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `opened_by` bigint unsigned DEFAULT NULL,
  `status` enum('OPEN','CLOSED') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'OPEN',
  `start_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `scheduled_start` timestamp NULL DEFAULT NULL,
  `tolerance_minutes` smallint unsigned NOT NULL DEFAULT '0',
  `cajero_login_time` timestamp NULL DEFAULT NULL,
  `attendance_status` enum('PENDIENTE','PUNTUAL','TARDANZA') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDIENTE',
  `end_time` timestamp NULL DEFAULT NULL,
  `initial_cash` decimal(10,2) NOT NULL DEFAULT '0.00',
  `reported_cash` decimal(10,2) DEFAULT NULL,
  `cash_difference` decimal(10,2) DEFAULT NULL,
  `inconsistency_class` enum('SIN_INCONSISTENCIA','INCONSISTENCIA_LEVE','INCONSISTENCIA_CRITICA') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `shifts_user_id_status_index` (`user_id`,`status`),
  KEY `shifts_opened_by_foreign` (`opened_by`),
  CONSTRAINT `shifts_opened_by_foreign` FOREIGN KEY (`opened_by`) REFERENCES `users` (`id`),
  CONSTRAINT `shifts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shifts`
--

LOCK TABLES `shifts` WRITE;
/*!40000 ALTER TABLE `shifts` DISABLE KEYS */;
INSERT INTO `shifts` VALUES (1,2,1,'CLOSED','2026-06-01 13:00:00','2026-06-01 13:00:00',10,'2026-06-01 13:05:00','PUNTUAL','2026-06-01 22:22:00',200.00,337.00,-2.00,'INCONSISTENCIA_LEVE',NULL,'2026-06-11 18:42:16','2026-06-11 18:42:17'),(2,2,1,'CLOSED','2026-06-02 13:00:00','2026-06-02 13:00:00',10,'2026-06-02 12:57:00','PUNTUAL','2026-06-02 22:12:00',200.00,191.00,-43.00,'INCONSISTENCIA_LEVE',NULL,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(3,2,1,'CLOSED','2026-06-03 13:00:00','2026-06-03 13:00:00',10,'2026-06-03 12:59:00','PUNTUAL','2026-06-03 22:12:00',200.00,239.00,-17.00,'INCONSISTENCIA_CRITICA',NULL,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(4,2,1,'CLOSED','2026-06-04 13:00:00','2026-06-04 13:00:00',10,'2026-06-04 13:32:00','TARDANZA','2026-06-04 22:21:00',200.00,375.00,-8.00,'INCONSISTENCIA_CRITICA',NULL,'2026-06-11 18:42:17','2026-06-11 18:42:17'),(5,2,1,'CLOSED','2026-06-05 13:00:00','2026-06-05 13:00:00',10,'2026-06-05 13:05:00','PUNTUAL','2026-06-05 22:03:00',200.00,485.00,0.00,'SIN_INCONSISTENCIA',NULL,'2026-06-11 18:42:17','2026-06-11 18:42:18'),(6,2,1,'CLOSED','2026-06-06 13:00:00','2026-06-06 13:00:00',10,'2026-06-06 13:01:00','PUNTUAL','2026-06-06 22:02:00',200.00,456.00,0.00,'SIN_INCONSISTENCIA',NULL,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(7,2,1,'CLOSED','2026-06-08 13:00:00','2026-06-08 13:00:00',10,'2026-06-08 13:33:00','TARDANZA','2026-06-08 22:24:00',200.00,338.00,26.00,'INCONSISTENCIA_LEVE',NULL,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(8,2,1,'CLOSED','2026-06-09 13:00:00','2026-06-09 13:00:00',10,'2026-06-09 13:03:00','PUNTUAL','2026-06-09 22:26:00',200.00,415.00,0.00,'SIN_INCONSISTENCIA',NULL,'2026-06-11 18:42:18','2026-06-11 18:42:18'),(9,2,1,'CLOSED','2026-06-10 13:00:00','2026-06-10 13:00:00',10,'2026-06-10 13:01:00','PUNTUAL','2026-06-10 22:30:00',200.00,393.00,-57.00,'INCONSISTENCIA_CRITICA',NULL,'2026-06-11 18:42:18','2026-06-11 18:42:19'),(10,2,1,'CLOSED','2026-06-11 13:00:00','2026-06-11 13:00:00',10,'2026-06-11 13:02:00','PUNTUAL','2026-06-11 22:06:00',200.00,479.00,0.00,'SIN_INCONSISTENCIA',NULL,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(11,2,1,'CLOSED','2026-06-12 13:00:00','2026-06-12 13:00:00',10,'2026-06-12 13:00:00','PUNTUAL','2026-06-12 22:23:00',200.00,418.00,0.00,'SIN_INCONSISTENCIA',NULL,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(12,2,1,'CLOSED','2026-06-13 13:00:00','2026-06-13 13:00:00',10,'2026-06-13 13:00:00','PUNTUAL','2026-06-13 22:04:00',200.00,396.00,27.00,'INCONSISTENCIA_LEVE',NULL,'2026-06-11 18:42:19','2026-06-11 18:42:19'),(13,2,1,'CLOSED','2026-06-15 13:00:00','2026-06-15 13:00:00',10,'2026-06-15 13:00:00','PUNTUAL','2026-06-15 22:03:00',200.00,741.00,0.00,'SIN_INCONSISTENCIA',NULL,'2026-06-11 18:42:19','2026-06-11 18:42:20'),(14,2,1,'CLOSED','2026-06-16 13:00:00','2026-06-16 13:00:00',10,'2026-06-16 13:00:00','PUNTUAL','2026-06-16 22:21:00',200.00,401.00,-15.00,'INCONSISTENCIA_LEVE',NULL,'2026-06-11 18:42:20','2026-06-11 18:42:20');
/*!40000 ALTER TABLE `shifts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` bigint unsigned DEFAULT NULL,
  `role_id` bigint unsigned DEFAULT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,1,1,'Administrador','admin',NULL,'$2y$12$JPOknF/XF4cuzgO/24UMouE90hRxmp5gyv23WL5g6Os92Eg1cgSIu',1,NULL,'2026-06-11 18:24:28','2026-06-11 18:24:28'),(2,1,2,'Cajero Prueba','cajero1',NULL,'$2y$12$AyD6XYoUQ5Da6iTr7QCJwuLQu2O8r48Pu0wRJxucYpEx9kbNbseka',1,NULL,'2026-06-11 18:24:28','2026-06-11 18:24:28');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'proyectoprueba'
--

--
-- Dumping routines for database 'proyectoprueba'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-11 15:05:04
