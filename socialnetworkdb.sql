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
  `Role` ENUM('admin', 'member') NOT NULL DEFAULT 'member'
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
  `UserID` int(11) NOT NULL,
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
-- Update table `media`
--
ALTER TABLE `media`
    DROP COLUMN `PostID`,
    DROP COLUMN `MediaType`,
    ADD COLUMN `MediaType` ENUM('photo','video') NOT NULL,
    ADD COLUMN FilePath VARCHAR(255),
    ADD COLUMN CreatedAt DATETIME DEFAULT CURRENT_TIMESTAMP;


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
  ADD COLUMN NotiType ENUM('like', 'comment', 'follow', 'group_invite');


--
-- Update table `users`
--
ALTER TABLE users
ADD COLUMN AccountPassword VARCHAR(255),
ADD COLUMN AvatarFP VARCHAR(255),
ADD COLUMN Phone VARCHAR(20),
ADD COLUMN Bio TEXT,
ADD COLUMN CreatedAt DATETIME DEFAULT CURRENT_TIMESTAMP,
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



--
-- Ràng buộc khóa ngoại (Foreign Keys)
--

-- Ràng buộc khóa ngoại cho bảng `comment`

ALTER TABLE comment
ADD CONSTRAINT fk_comment_post
FOREIGN KEY (PostID) REFERENCES post(PostID)
ON DELETE CASCADE;

ALTER TABLE comment
ADD CONSTRAINT fk_comment_user
FOREIGN KEY (UserID) REFERENCES users(UserID)
ON DELETE CASCADE;

ALTER TABLE comment
ADD CONSTRAINT fk_comment_parent
FOREIGN KEY (CommentParentID) REFERENCES comment(CommentID)
ON DELETE SET NULL;


-- Ràng buộc khóa ngoại cho bảng `follow`

ALTER TABLE follow
ADD CONSTRAINT fk_follow_follower
FOREIGN KEY (FollowerID) REFERENCES users(UserID)
ON DELETE CASCADE;

ALTER TABLE follow
ADD CONSTRAINT fk_follow_following
FOREIGN KEY (FollowingID) REFERENCES users(UserID)
ON DELETE CASCADE;


-- Ràng buộc khóa ngoại cho bảng `groups`

ALTER TABLE groups
ADD CONSTRAINT fk_group_category
FOREIGN KEY (CategoryID) REFERENCES category(CategoryID) 
ON DELETE SET NULL;


-- Ràng buộc khóa ngoại cho bảng `group_member`

ALTER TABLE group_member
ADD CONSTRAINT fk_groupmember_user
FOREIGN KEY (UserID) REFERENCES users(UserID)
ON DELETE CASCADE;

ALTER TABLE group_member
ADD CONSTRAINT fk_groupmember_group
FOREIGN KEY (GroupID) REFERENCES groups(GroupID)
ON DELETE CASCADE;


-- Ràng buộc khóa ngoại cho bảng `media`

ALTER TABLE media
ADD CONSTRAINT fk_media_user
FOREIGN KEY (UserID) REFERENCES users(UserID)
ON DELETE CASCADE;


-- Ràng buộc khóa ngoại cho bảng `notification`

ALTER TABLE notification
ADD CONSTRAINT fk_notification_sender
FOREIGN KEY (SenderID) REFERENCES users(UserID)
ON DELETE SET NULL;

ALTER TABLE notification
ADD CONSTRAINT fk_notification_user
FOREIGN KEY (UserID) REFERENCES users(UserID)
ON DELETE CASCADE;


-- Ràng buộc khóa ngoại cho bảng `post`

ALTER TABLE post
ADD CONSTRAINT fk_post_user
FOREIGN KEY (UserID) REFERENCES users(UserID)
ON DELETE CASCADE;

ALTER TABLE post
ADD CONSTRAINT fk_post_category
FOREIGN KEY (CategoryID) REFERENCES category(CategoryID);

ALTER TABLE post
ADD CONSTRAINT fk_post_group
FOREIGN KEY (GroupID) REFERENCES groups(GroupID)
ON DELETE SET NULL;


-- Ràng buộc khóa ngoại cho bảng `reaction`

ALTER TABLE reaction
ADD CONSTRAINT fk_reaction_user
FOREIGN KEY (UserID) REFERENCES users(UserID)
ON DELETE CASCADE;

ALTER TABLE reaction
ADD CONSTRAINT fk_reaction_post
FOREIGN KEY (PostID) REFERENCES post(PostID)
ON DELETE CASCADE;

ALTER TABLE reaction
ADD CONSTRAINT fk_reaction_comment
FOREIGN KEY (CommentID) REFERENCES comment(CommentID)
ON DELETE CASCADE;


-- Ràng buộc khóa ngoại cho bảng `photo`

ALTER TABLE photo
ADD CONSTRAINT fk_photo_media
FOREIGN KEY (MediaID) REFERENCES media(MediaID)
ON DELETE CASCADE;

-- Ràng buộc khóa ngoại cho bảng `video`

ALTER TABLE video
ADD CONSTRAINT fk_video_media
FOREIGN KEY (MediaID) REFERENCES media(MediaID)
ON DELETE CASCADE;



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

ALTER TABLE follow -- 2: Đảm bảo không có người dùng nào có thể theo dõi chính mình
ADD CONSTRAINT chk_follow_self
CHECK (FollowerID <> FollowingID);


--
-- Cập nhật 20/03/2026 (LẦN 3): Thêm cột mới, ràng buộc mới, sửa lỗi ràng buộc
--

DROP TABLE IF EXISTS `post_media`;
DROP TABLE IF EXISTS `comment_media`;

ALTER TABLE `media`
ADD COLUMN `CommentID` INT(11) DEFAULT NULL,
ADD COLUMN `PostID` INT(11) DEFAULT NULL,
ADD KEY `CommentID` (`CommentID`),
ADD KEY `PostID` (`PostID`),
ADD CONSTRAINT fk_media_comment
FOREIGN KEY (CommentID) REFERENCES comment(CommentID)
ON DELETE CASCADE,
ADD CONSTRAINT fk_media_post
FOREIGN KEY (PostID) REFERENCES post(PostID)
ON DELETE CASCADE;

ALTER TABLE media
ADD CONSTRAINT chk_media_target
CHECK (
 (PostID IS NOT NULL AND CommentID IS NULL)
 OR
 (PostID IS NULL AND CommentID IS NOT NULL)
 OR
 (UserID IS NOT NULL AND PostID IS NULL AND CommentID IS NULL)
);

ALTER TABLE users
MODIFY AccountPassword VARCHAR(255) NOT NULL;

ALTER TABLE post
ADD CONSTRAINT chk_post_content
CHECK (Content IS NOT NULL OR Title IS NOT NULL);

ALTER TABLE `notification`
MODIFY NotiType ENUM('like','comment','follow','group_invite') NOT NULL;

ALTER TABLE comment
MODIFY PostID INT NOT NULL;

ALTER TABLE comment
MODIFY Content TEXT NOT NULL;

ALTER TABLE `notification`
MODIFY Content TEXT NOT NULL;

ALTER TABLE groups
MODIFY `Description` VARCHAR(150) DEFAULT NULL;

ALTER TABLE reaction
MODIFY ReactionType ENUM('like','love','haha','wow','sad','angry')
DEFAULT 'like';

ALTER TABLE category
ADD CONSTRAINT unique_category_name
UNIQUE (CategoryName);

DROP TABLE IF EXISTS `photo`;
DROP TABLE IF EXISTS `video`;

ALTER TABLE post
ADD COLUMN Price INT DEFAULT NULL,

--
-- UPDATE 01/04/2026 (LẦN 4): Thêm cột mới cho bảng post
--

ADD COLUMN ProductCondition ENUM(
    'new',
    'like_new',
    'very_good',
    'good',
    'fair',
    'for_parts'
) DEFAULT 'good',

ADD COLUMN Location ENUM(
    'hcm',
    'hanoi',
    'danang',
    'cantho',
    'haiphong',
    'other'
) DEFAULT 'other',

ADD COLUMN Brand VARCHAR(100) DEFAULT NULL,

ADD COLUMN PostStatus ENUM(
    'selling',
    'reserved',
    'sold',
    'hidden'
) DEFAULT 'selling';

--
-- UPDATE 02/04/2026 (LẦN 5): Thêm tăng tự động GroupID, thêm cột cho bảng group_member
--

ALTER TABLE groups MODIFY GroupID int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE group_member 
ADD COLUMN Status ENUM('pending', 'approved') NOT NULL DEFAULT 'approved';

-- INSERT NEW CATEGORIES --
INSERT INTO category (CategoryName) VALUES
('Clothes'),
('Shoes'),
('Electronics'),
('Toys'),
('Books'),
('Furniture'),
('Cosmetic'),
('Other');

-- TEST DATA --

INSERT INTO category (CategoryName) VALUES
('Sports');

INSERT INTO users 
(Username, Email, AccountPassword, AvatarFP, Phone, Bio)
VALUES
('alice', 'alice@gmail.com', '$2y$10$alicepasshash', 'avatar1.jpg', '0900000001', 'Love fashion and books'),
('bob', 'bob@gmail.com', '$2y$10$bobpasshash', 'avatar2.jpg', '0900000002', 'Tech enthusiast'),
('charlie', 'charlie@gmail.com', '$2y$10$charliepasshash', 'avatar3.jpg', '0900000003', 'Gaming lover'),
('david', 'david@gmail.com', '$2y$10$davidpasshash', 'avatar4.jpg', '0900000004', 'Selling electronics'),
('emma', 'emma@gmail.com', '$2y$10$emmapasshash', 'avatar5.jpg', '0900000005', 'Book collector'),
('frank', 'frank@gmail.com', '$2y$10$frankpasshash', 'avatar6.jpg', '0900000006', 'Toy trader'),
('grace', 'grace@gmail.com', '$2y$10$gracepasshash', 'avatar7.jpg', '0900000007', 'Cosmetic lover'),
('henry', 'henry@gmail.com', '$2y$10$henrypasshash', 'avatar8.jpg', '0900000008', 'Furniture reseller'),
('ivy', 'ivy@gmail.com', '$2y$10$ivypasshash', 'avatar9.jpg', '0900000009', 'Sport equipment seller');

INSERT INTO groups
(CategoryID, GroupName, Description, Privacy)
VALUES
(1, 'Fashion Market', 'Buy and sell clothes', 'public'),
(2, 'Sneaker Exchange', 'Sneaker trading group', 'public'),
(3, 'Tech Trading', 'Electronics marketplace', 'public'),
(4, 'Toy Collectors', 'Group for toy collectors', 'public'),
(5, 'Book Market', 'Buy and sell books', 'public'),
(6, 'Furniture Deals', 'Used furniture market', 'public'),
(7, 'Beauty Products', 'Cosmetic trading group', 'public'),
(8, 'Misc Marketplace', 'Everything else', 'public'),
(9, 'Sports Gear Market', 'Sports equipment trading', 'public');

INSERT INTO group_member (UserID, GroupID, Role, Status) VALUES
(1,1,'admin','approved'),
(2,3,'admin','approved'),
(3,2,'admin','approved'),
(4,3,'member','approved'),
(5,5,'admin','approved'),
(6,4,'admin','approved'),
(7,7,'admin','approved'),
(8,6,'admin','approved'),
(9,9,'admin','approved'),

(1,2,'member','approved'),
(2,1,'member','approved'),
(3,3,'member','approved'),
(4,4,'member','approved'),
(5,1,'member','approved'),
(6,8,'member','approved'),
(7,5,'member','approved'),
(8,3,'member','approved'),
(9,7,'member','approved');