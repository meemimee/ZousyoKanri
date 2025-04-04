-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: localhost
-- 生成日時: 2025 年 3 月 27 日 03:21
-- サーバのバージョン： 10.4.28-MariaDB
-- PHP のバージョン: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `zoushokanri`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(100) DEFAULT NULL,
  `star` int(20) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `star`, `created`, `updated`) VALUES
(1, 'あおさ王', 'ろーずまりさとくりふ', 5, '2025-03-25 17:37:31', '2025-03-25 17:37:31'),
(5, 'おにからさんご', 'おにやば', 3, '2025-03-25 17:48:12', '2025-03-25 17:48:12'),
(6, 'みけっていだね', NULL, 3, '2025-03-25 19:22:36', '2025-03-25 19:29:21'),
(8, 'ねこにっき', 'にゃんこ王', 3, '2025-03-25 19:55:06', '2025-03-25 22:49:36'),
(9, 'あああ', 'にゃんこ王', 2, '2025-03-25 22:49:46', '2025-03-25 22:49:46'),
(12, '$', '$', 0, '2025-03-25 23:56:19', '2025-03-25 23:56:19'),
(19, '<>', '<>', 3, '2025-03-26 00:42:13', '2025-03-26 05:09:25'),
(20, '<>', '<>', 4, '2025-03-26 05:03:15', '2025-03-26 05:03:15'),
(22, '<>', '<>', 2, '2025-03-26 05:15:46', '2025-03-26 05:16:52'),
(24, '%_\\$<>', '%_\\%_\\$$', 3, '2025-03-26 05:30:34', '2025-03-26 16:58:14');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
