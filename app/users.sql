-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: localhost
-- 生成日時: 2025 年 3 月 27 日 03:28
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
-- テーブルの構造 `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `username` varchar(100) NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `username`, `created`, `updated`) VALUES
(1, 'aaa@aaa', '$2y$10$6BDXl31gpGma.4eJuqme9ukLg6gBebnHsMbB5hSdf24YgcVrJNG8W', 'おためし太郎', '2025-03-21 04:53:32', '2025-03-24 05:47:35'),
(2, 'bbb@bbb.bbb', '$2y$10$PwDGDhdlft6ata6KXzysbeEXNUOeTlZ4uQqlHg7aEUlrralMv/MKq', 'おためしさんだよー&lt;&gt;', '2025-03-21 07:38:12', '2025-03-24 06:22:35'),
(3, 'bbbb@bbb.bbb', '$2y$10$HfllBvBf1G8gi5kRdiqN6OQrkP6Zyw0OXOMkt3vWsU6asNk1YAoWC', 'おためし次郎', '2025-03-24 07:01:23', '2025-03-24 07:01:23'),
(4, 'ccc@ccc.ccc', '$2y$10$5AIlLLLgzIga9fdnppwagetvwrjEhJyGpG9GsD32FvsvlsKuxFXp6', '<script>window.location.href= \"https://google.com\";</script>', '2025-03-25 01:26:32', '2025-03-27 01:42:11'),
(5, 'ddd@ddd.dd', '$2y$10$lZtJz5DPEGUgnioWq9jY7OtzgFv1BgeCiBEwvq/dovIdqAwjDJS.W', 'おためし', '2025-03-25 03:34:55', '2025-03-25 04:14:58'),
(6, 'eee@eee.eee', '$2y$10$Nn5UA5fGXynlc806hr5hvuIpTU9rz7RfFAbYb4H4eY7LWmPq5zqGm', 'おためし五郎', '2025-03-25 04:27:04', '2025-03-25 04:27:04'),
(7, 'fff@fff.fff', '$2y$10$uxWtMPKbLaqqtJCN4eaxI./Pk5UZKGIeEq4AVNKFpWZytIZRLqC5O', 'おためしむつごろう', '2025-03-25 04:32:42', '2025-03-25 04:32:42'),
(8, 'neko@neko.neko', '$2y$10$xFD1IjW2QEBpi2u3MUAv5.Hs4vmJbYrNsR.SYPVUw/hFu2hfWZOHG', 'ねこさま', '2025-03-26 04:29:55', '2025-03-26 04:29:55'),
(9, 'aa@aa.aa', '$2y$10$SlHFjjpmHPOM26Z0ryNHjunmlSszXS3EF6gzSoX0OrNIQDD0gXHIW', '<>', '2025-03-26 07:58:15', '2025-03-26 07:58:15');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
