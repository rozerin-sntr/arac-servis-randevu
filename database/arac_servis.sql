-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 01 Mar 2026, 13:06:08
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `arac_servis`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kullanicilar`
--

CREATE TABLE `kullanicilar` (
  `id` int(11) NOT NULL,
  `ad` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `sifre` varchar(100) DEFAULT NULL,
  `rol` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `kullanicilar`
--

INSERT INTO `kullanicilar` (`id`, `ad`, `email`, `sifre`, `rol`) VALUES
(4, 'Rozerin Santor', 'rozerin@mail.com', '$2y$10$ynsXQQDi6o3xKjZSAwtesOck.AFLxssF4n2fldYJysDdT.GE6Ze.O', 'admin'),
(6, 'Fatma Kipçak', 'fatmakipcak@mail.com', '$2y$10$mYiEtrR9NqTiajwG1.mZh.Jt0Kn3yf9rGlHUOtlUEfDq86b6YMbR6', 'servis'),
(7, 'Sude Nur Koç', 'sudenurkoc@mail.com', '$2y$10$370BTOU3TY2cww2SEgvDU.wvTW11y9zLN6zVxuL5Fho7ztFNZKiqu', 'musteri');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `randevular`
--

CREATE TABLE `randevular` (
  `id` int(11) NOT NULL,
  `musteri_id` int(11) NOT NULL,
  `servis_id` int(11) DEFAULT NULL,
  `arac_bilgisi` varchar(255) NOT NULL,
  `randevu_tarihi` date NOT NULL,
  `randevu_saati` time NOT NULL,
  `aciklama` text DEFAULT NULL,
  `durum` enum('beklemede','onaylandi','islemde','tamamlandi','iptal') DEFAULT 'beklemede',
  `olusturma_tarihi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `randevular`
--

INSERT INTO `randevular` (`id`, `musteri_id`, `servis_id`, `arac_bilgisi`, `randevu_tarihi`, `randevu_saati`, `aciklama`, `durum`, `olusturma_tarihi`) VALUES
(2, 7, 6, '65ABC65', '2026-03-12', '15:00:00', '', 'tamamlandi', '2026-02-27 18:16:30'),
(3, 7, 6, '04asd04-ferrari', '2026-02-28', '09:30:00', '', 'tamamlandi', '2026-02-27 18:30:39'),
(5, 7, 6, '65ABC65', '2026-03-08', '12:50:00', '', 'tamamlandi', '2026-02-27 18:50:48'),
(6, 7, 6, '65ABC65', '2026-03-13', '06:00:00', '', 'islemde', '2026-02-27 18:53:47'),
(8, 7, NULL, '65ABC65', '2026-03-08', '22:32:00', '', 'beklemede', '2026-02-27 19:32:24'),
(9, 7, NULL, '65ABC65', '2026-03-15', '12:00:00', '', 'beklemede', '2026-02-28 18:40:11');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `ad` varchar(100) DEFAULT NULL,
  `soyad` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `sifre` varchar(255) DEFAULT NULL,
  `rol` enum('admin','servis','kullanici') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `ad`, `soyad`, `email`, `sifre`, `rol`) VALUES
(1, 'Rozerin', 'Santor', 'admin@mail.com', '123456', 'admin');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `kullanicilar`
--
ALTER TABLE `kullanicilar`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `randevular`
--
ALTER TABLE `randevular`
  ADD PRIMARY KEY (`id`),
  ADD KEY `musteri_id` (`musteri_id`),
  ADD KEY `servis_id` (`servis_id`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `kullanicilar`
--
ALTER TABLE `kullanicilar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Tablo için AUTO_INCREMENT değeri `randevular`
--
ALTER TABLE `randevular`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `randevular`
--
ALTER TABLE `randevular`
  ADD CONSTRAINT `randevular_ibfk_1` FOREIGN KEY (`musteri_id`) REFERENCES `kullanicilar` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `randevular_ibfk_2` FOREIGN KEY (`servis_id`) REFERENCES `kullanicilar` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
