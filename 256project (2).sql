-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1:3306
-- Üretim Zamanı: 15 May 2025, 17:36:13
-- Sunucu sürümü: 9.1.0
-- PHP Sürümü: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `256project`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `carts`
--

DROP TABLE IF EXISTS `carts`;
CREATE TABLE IF NOT EXISTS `carts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `cart_items`
--

DROP TABLE IF EXISTS `cart_items`;
CREATE TABLE IF NOT EXISTS `cart_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cart_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `added_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `cart_id` (`cart_id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `cities`
--

DROP TABLE IF EXISTS `cities`;
CREATE TABLE IF NOT EXISTS `cities` (
  `id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `cities`
--

INSERT INTO `cities` (`id`, `name`) VALUES
(6, 'Ankara'),
(34, 'İstanbul'),
(35, 'İzmir'),
(38, 'Kayseri'),
(61, 'Trabzon');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `districts`
--

DROP TABLE IF EXISTS `districts`;
CREATE TABLE IF NOT EXISTS `districts` (
  `district_id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `city_id` int NOT NULL,
  PRIMARY KEY (`district_id`),
  KEY `fk_city` (`city_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `districts`
--

INSERT INTO `districts` (`district_id`, `name`, `city_id`) VALUES
(10, 'Keçiören', 6),
(11, 'Çankaya', 6),
(12, 'Yenimahalle', 6),
(13, 'Sincan', 6),
(14, 'Esenler', 34),
(15, 'Bağcılar', 34),
(16, 'Şişli', 34),
(17, 'Arnavutköy', 34),
(18, 'Talas', 38),
(19, 'Melikgazi', 38),
(20, 'Kocasinan', 38),
(21, 'Yahyalı', 38),
(22, 'Yomra', 61),
(23, 'Vakfıkebir', 61),
(24, 'Maçka', 61),
(25, 'Çaykara', 61),
(26, 'Buca', 35),
(27, 'Karşıyaka', 35),
(28, 'Bornova', 35),
(29, 'Çeşme', 35);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `market_id` int NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `stock` int NOT NULL,
  `normal_price` decimal(10,2) NOT NULL,
  `discounted_price` decimal(10,2) NOT NULL,
  `expiration_date` date NOT NULL,
  `image_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `market_id` (`market_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- mock data for DB demo for products
INSERT INTO products (market_id, title, stock, normal_price, discounted_price, expiration_date, image_path)
VALUES
(8, 'Toblerone 100gr', 25, 20.00, 12.00, '2025-05-19', 'uploads/toblerone.jpg'),
(8, 'Magnolia Cake', 12, 35.00, 22.00, '2025-05-20', 'uploads/magnolia.jpg'),
(8, 'Milk 1L', 30, 10.00, 6.00, '2025-05-21', 'uploads/milk.jpg');
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `tokens`
--

DROP TABLE IF EXISTS `tokens`;
CREATE TABLE IF NOT EXISTS `tokens` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `expire_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `tokens`
--

INSERT INTO `tokens` (`id`, `user_id`, `token`, `expire_date`) VALUES
(1, 1, 'bb6649390b5b48bde0d253a03fa69d95fd6097046a388ebbfc36670b4ccd0f0a', '2025-06-14 00:00:00'),
(2, 3, 'a46e09736f4c2d2ec585f2c684c8bd1daa30f7b8c8c59e005004d93005e82db0', '2025-06-14 00:00:00'),
(3, 4, 'b260d4bb531f63a294575e9552e5dcd0aad883183b94559d8a21f5011670498d', '2025-06-14 00:00:00');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `city_id` int NOT NULL,
  `district_id` int NOT NULL,
  `role` enum('market','consumer') CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `time_for_create` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_users_city` (`city_id`),
  KEY `fk_users_district` (`district_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `name`, `city_id`, `district_id`, `role`, `time_for_create`) VALUES
(1, 'bkarub61@gmail.com', '$2y$10$0cJvPpugcAo./GrQb/KSLuW59oQp3Fl9UOI5Jth3OqSFDMXkbJwim', 'Name', 6, 12, 'consumer', '2025-05-15 16:40:29'),
(2, 'ahmetcanpolat0000@gmail.com', '$2y$10$A5nabnk87ZlqZivSxQ3CgewTc.1w3IquBNrPsJTs/qFrFOADogjNq', 'Amir', 34, 15, 'market', '2025-05-15 16:41:19'),
(3, 'acikstories@gmail.com', '$2y$10$l.gIigOKLv1oYwEEgR1z4.La1zIv9lod/1NGRkHfNz0H90wrivIXu', 'Yusuf Ethem', 38, 21, 'market', '2025-05-15 16:58:17'),
(4, 'storiesacik@gmail.com', '$2y$10$kVUNFmY/ey8UcPFc5oWlA.K.PviW0vbtZvRIYufsoOjjNq0tw2Lce', 'Murat', 61, 24, 'consumer', '2025-05-15 17:34:30');
(7, 'hasanhuseyinbalbicak@gmail.com', '$2y$10$TG6yE4R3nuFMHfGjYK6nr.JkCXSDGGJp3Ab3SV4xaDbiIcc4v2zLu', 'hasan', 6, 11, 'consumer', '2025-05-17 10:34:04'),
(8, 'mr.honneynive@gmail.com', '$2y$10$/IlaUgCUbhvNUyTlkJqmsOo/BFyq7vWr2u5x3vVXFyYFk.AVqheo6', 'MarketHasan', 6, 11, 'market', '2025-05-17 10:47:25');

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `districts`
--
ALTER TABLE `districts`
  ADD CONSTRAINT `fk_city` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Tablo kısıtlamaları `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`market_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `tokens`
--
ALTER TABLE `tokens`
  ADD CONSTRAINT `tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Tablo kısıtlamaları `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_city` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_users_district` FOREIGN KEY (`district_id`) REFERENCES `districts` (`district_id`) ON DELETE RESTRICT ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
