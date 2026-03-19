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
-- LẦN 2: Sửa các bảng reaction, post, comment, media, notification, group -> groups; Thêm mới bảng post_media, photo_media
-- ==============================
-- VERSION 2 (15/03/2026)
-- Schema Update
-- ==============================

--
-- Update table `reaction`
--
ALTER TABLE `reaction`
  DROP COLUMN `TargetID`,
  DROP COLUMN `TargetType`,
  ADD COLUMN `PostID` INT(11) DEFAULT NULL,
  ADD COLUMN `CommentID` INT(11) DEFAULT NULL,
  ADD COLUMN ReactionType VARCHAR(20) DEFAULT 'like',
  ADD COLUMN CreatedAt DATETIME DEFAULT CURRENT_TIMESTAMP,
  ADD KEY `PostID` (`PostID`),
  ADD KEY `CommentID` (`CommentID`);


--
-- Create table `post_media`
--
CREATE TABLE `post_media` (
  `PostID` INT(11) NOT NULL,
  `MediaID` INT(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `post_media`
  ADD PRIMARY KEY (`PostID`, `MediaID`),
  ADD KEY `PostID` (`PostID`);


--
-- Create table `comment_media`
--
CREATE TABLE `comment_media` (
  `CommentID` INT(11) NOT NULL,
  `MediaID` INT(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `comment_media` 
  ADD PRIMARY KEY (`CommentID`, `MediaID`),
  ADD KEY `CommentID` (`CommentID`);


--
-- Update table `media`
--
ALTER TABLE `media`
    DROP COLUMN `PostID`,
    DROP COLUMN `MediaType`,
    ADD COLUMN FilePath VARCHAR(255),
    ADD COLUMN CreatedAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN `MediaType` ENUM('photo','video');


--
-- Update table `post`
--
ALTER TABLE `post`
  ADD COLUMN `CreatedAt` datetime DEFAULT CURRENT_TIMESTAMP,
  ADD COLUMN `CategoryID` int(11) DEFAULT NULL,
  ADD COLUMN `Title` VARCHAR(255) DEFAULT NULL,
  ADD COLUMN `GroupID` int(11) DEFAULT NULL;

ALTER TABLE `post`
  ADD KEY `CategoryID` (`CategoryID`),
  ADD KEY `GroupID` (`GroupID`);


--
-- Update table `notification`
--
ALTER TABLE notification
  ADD COLUMN IsRead BOOLEAN DEFAULT FALSE,
  ADD COLUMN CreatedAt DATETIME DEFAULT CURRENT_TIMESTAMP,
  ADD COLUMN SenderID INT,
  ADD KEY SenderID (SenderID),
  ADD COLUMN notiType ENUM('like', 'comment', 'follow', 'group_invite');


--
-- Update table `users`
--
ALTER TABLE users
ADD COLUMN AccountPassword VARCHAR(255),
ADD COLUMN AvatarFP VARCHAR(255),
ADD COLUMN Phone VARCHAR(20),
ADD COLUMN Bio TEXT,
ADD COLUMN CreatedAt DATETIME DEFAULT CURRENT_TIMESTAMP,
ADD COLUMN LastLogin DATETIME,
ADD COLUMN UserRole ENUM('user','admin') DEFAULT 'user',
ADD COLUMN AccountStatus ENUM('active','suspended','deleted') DEFAULT 'active';


--
-- Update table `group` thành `groups` (do trùng tên thành phần hệ thống)
-- 
DROP TABLE IF EXISTS `group`;

CREATE TABLE `groups` (
  `GroupID` int(11) NOT NULL,
  `CategoryID` int(11) DEFAULT NULL,
  `GroupName` varchar(255) NOT NULL,
  `Description` varchar(150) NOT NULL,
  `Privacy` ENUM('public','private') NOT NULL,
  `CreatedAt` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `groups`
  ADD PRIMARY KEY (`GroupID`),
  ADD KEY `CategoryID` (`CategoryID`);


--
-- Update table `comment`
--
ALTER TABLE `comment`
  ADD COLUMN `CommentParentID` INT(11) DEFAULT NULL,
  ADD KEY `CommentParentID` (`CommentParentID`);


--
--  Ràng buộc duy nhất và các ràng buộc bổ sung (Unique Keys)
--
ALTER TABLE users
ADD CONSTRAINT unique_username 
UNIQUE (Username);

ALTER TABLE reaction
ADD CONSTRAINT unique_post_reaction
UNIQUE (UserID, PostID);

ALTER TABLE reaction
ADD CONSTRAINT unique_comment_reaction 
UNIQUE (UserID, CommentID);

ALTER TABLE post_media
ADD CONSTRAINT unique_post_media
UNIQUE (PostID, MediaID);

ALTER TABLE comment_media
ADD CONSTRAINT unique_comment_media
UNIQUE (CommentID, MediaID);


--
-- Ràng buộc khóa ngoại (Foreign Keys)
--


--
--  Ràng buộc kiểm tra (Check Constraints)
--
ALTER TABLE reaction -- 1: Đảm bảo mỗi reaction chỉ liên kết với một loại đối tượng (post hoặc comment)
ADD CONSTRAINT chk_reaction_target
CHECK (
  (PostID IS NOT NULL AND CommentID IS NULL)
OR
  (PostID IS NULL AND CommentID IS NOT NULL)
  );