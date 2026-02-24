-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th2 23, 2026 lúc 04:13 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `socialnetworkdb`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `category`
--

CREATE TABLE `category` (
  `CategoryID` int(11) NOT NULL,
  `CategoryName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `comment`
--

CREATE TABLE `comment` (
  `CommentID` int(11) NOT NULL,
  `PostID` int(11) DEFAULT NULL,
  `UserID` int(11) DEFAULT NULL,
  `Content` text DEFAULT NULL,
  `CreatedAt` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `follow`
--

CREATE TABLE `follow` (
  `FollowerID` int(11) NOT NULL,
  `FollowingID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `group`
--

CREATE TABLE `group` (
  `GroupID` int(11) NOT NULL,
  `CategoryID` int(11) DEFAULT NULL,
  `GroupName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `group_member`
--

CREATE TABLE `group_member` (
  `UserID` int(11) NOT NULL,
  `GroupID` int(11) NOT NULL,
  `Role` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `media`
--

CREATE TABLE `media` (
  `MediaID` int(11) NOT NULL,
  `PostID` int(11) DEFAULT NULL,
  `UserID` int(11) DEFAULT NULL,
  `MediaType` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `notification`
--

CREATE TABLE `notification` (
  `NotificationID` int(11) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `Content` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `photo`
--

CREATE TABLE `photo` (
  `MediaID` int(11) NOT NULL,
  `Resolution` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `post`
--

CREATE TABLE `post` (
  `PostID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `Content` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `reaction`
--

CREATE TABLE `reaction` (
  `ReactionID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `TargetID` int(11) DEFAULT NULL,
  `TargetType` ENUM('post','comment') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `Username` varchar(100) NOT NULL,
  `Email` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `video`
--

CREATE TABLE `video` (
  `MediaID` int(11) NOT NULL,
  `Duration` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`CategoryID`);

--
-- Chỉ mục cho bảng `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`CommentID`),
  ADD KEY `PostID` (`PostID`),
  ADD KEY `UserID` (`UserID`);

--
-- Chỉ mục cho bảng `follow`
--
ALTER TABLE `follow`
  ADD PRIMARY KEY (`FollowerID`,`FollowingID`),
  ADD KEY `FollowingID` (`FollowingID`);

--
-- Chỉ mục cho bảng `group`
--
ALTER TABLE `group`
  ADD PRIMARY KEY (`GroupID`),
  ADD KEY `CategoryID` (`CategoryID`);

--
-- Chỉ mục cho bảng `group_member`
--
ALTER TABLE `group_member`
  ADD PRIMARY KEY (`UserID`,`GroupID`),
  ADD KEY `GroupID` (`GroupID`);

--
-- Chỉ mục cho bảng `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`MediaID`),
  ADD KEY `PostID` (`PostID`),
  ADD KEY `UserID` (`UserID`);

--
-- Chỉ mục cho bảng `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`NotificationID`),
  ADD KEY `UserID` (`UserID`);

--
-- Chỉ mục cho bảng `photo`
--
ALTER TABLE `photo`
  ADD PRIMARY KEY (`MediaID`);

--
-- Chỉ mục cho bảng `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`PostID`),
  ADD KEY `UserID` (`UserID`);

--
-- Chỉ mục cho bảng `reaction`
--
ALTER TABLE `reaction`
  ADD PRIMARY KEY (`ReactionID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `TargetID` (`TargetID`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Chỉ mục cho bảng `video`
--
ALTER TABLE `video`
  ADD PRIMARY KEY (`MediaID`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `category`
--
ALTER TABLE `category`
  MODIFY `CategoryID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `comment`
--
ALTER TABLE `comment`
  MODIFY `CommentID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `group`
--
ALTER TABLE `group`
  MODIFY `GroupID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `media`
--
ALTER TABLE `media`
  MODIFY `MediaID` int(11)  NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `notification`
--
ALTER TABLE `notification`
  MODIFY `NotificationID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `post`
--
ALTER TABLE `post`
  MODIFY `PostID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `reaction`
--
ALTER TABLE `reaction`
  MODIFY `ReactionID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`PostID`) REFERENCES `post` (`PostID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `follow`
--
ALTER TABLE `follow`
  ADD CONSTRAINT `follow_ibfk_1` FOREIGN KEY (`FollowerID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `follow_ibfk_2` FOREIGN KEY (`FollowingID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `group`
--
ALTER TABLE `group`
  ADD CONSTRAINT `group_ibfk_1` FOREIGN KEY (`CategoryID`) REFERENCES `category` (`CategoryID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `group_member`
--
ALTER TABLE `group_member`
  ADD CONSTRAINT `group_member_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `group_member_ibfk_2` FOREIGN KEY (`GroupID`) REFERENCES `group` (`GroupID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `media`
--
ALTER TABLE `media`
  ADD CONSTRAINT `media_ibfk_1` FOREIGN KEY (`PostID`) REFERENCES `post` (`PostID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `media_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `notification_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `photo`
--
ALTER TABLE `photo`
  ADD CONSTRAINT `photo_ibfk_1` FOREIGN KEY (`MediaID`) REFERENCES `media` (`MediaID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `post_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `reaction`
--
ALTER TABLE `reaction`
  ADD CONSTRAINT `reaction_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;
--
-- Các ràng buộc cho bảng `video`
--
ALTER TABLE `video`
  ADD CONSTRAINT `video_ibfk_1` FOREIGN KEY (`MediaID`) REFERENCES `media` (`MediaID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
