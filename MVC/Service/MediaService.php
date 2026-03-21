<?php
include_once "MVC/Model/Media.php";
include_once "MVC/Module/db_module.php";

class MediaService {

    // Thêm media cho post
    public function createMediaForPost($UserID, $PostID, $MediaType, $FilePath) {
        $link = null;
        taoKetNoi($link);

        $UserID    = mysqli_real_escape_string($link, $UserID);
        $PostID    = mysqli_real_escape_string($link, $PostID);
        $MediaType = mysqli_real_escape_string($link, $MediaType);
        $FilePath  = mysqli_real_escape_string($link, $FilePath);

        $query = "CALL insertMediaToPost($UserID, $PostID, '$MediaType', '$FilePath')";

        $result = chayTruyVanKhongTraVeDL($link, $query);

        giaiPhongKetNoi($link);

        if (!$result) {
            return "Failed to create media.";
        }
        return "Media created successfully.";
    }

    public function getMediaByPostIDs($postIdList) {

        $link = null;
        taoKetNoi($link);

        $query = "SELECT * FROM media WHERE PostID IN ($postIdList)";
        $result = chayTruyVanTraVeDL($link, $query);

        $mediaMap = [];

        while ($row = mysqli_fetch_assoc($result)) {

            $postId = $row['PostID'];

            $media = new Media(
                $row['MediaID'],
                $row['UserID'],
                $row['MediaType'],
                $row['FilePath'],
                $row['CreatedAt'],
                $row['CommentID'],
                $row['PostID']
            );

            $mediaMap[$postId][] = $media;
        }

        giaiPhongKetNoi($link);

        return $mediaMap;
    }

    // Thêm media cho comment
    public function createMediaForComment($UserID, $CommentID, $MediaType, $FilePath) {
        $link = null;
        taoKetNoi($link);

        $UserID    = mysqli_real_escape_string($link, $UserID);
        $CommentID = mysqli_real_escape_string($link, $CommentID);
        $MediaType = mysqli_real_escape_string($link, $MediaType);
        $FilePath  = mysqli_real_escape_string($link, $FilePath);

        $query = "INSERT INTO media (UserID, CommentID, MediaType, FilePath)
                  VALUES ($UserID, $CommentID, '$MediaType', '$FilePath')";

        $result = chayTruyVanKhongTraVeDL($link, $query);

        giaiPhongKetNoi($link);

        if (!$result) {
            return "Failed to create media.";
        }
        return "Media created successfully.";
    }

    // Lấy tất cả media của một bài post
    public function getMediaByPostID($PostID) {
        $link = null;
        taoKetNoi($link);

        $PostID = mysqli_real_escape_string($link, $PostID);

        $query = "SELECT * FROM media WHERE PostID = $PostID";
        $result = chayTruyVanTraVeDL($link, $query);

        $mediaList = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $mediaList[] = new Media(
                $row['MediaID'],
                $row['UserID'],
                $row['MediaType'],
                $row['FilePath'],
                $row['CreatedAt'],
                $row['CommentID'],
                $row['PostID']
            );
        }

        giaiPhongKetNoi($link);
        return $mediaList;
    }

    // Lấy tất cả media của một comment
    public function getMediaByCommentID($CommentID) {
        $link = null;
        taoKetNoi($link);

        $CommentID = mysqli_real_escape_string($link, $CommentID);

        $query = "SELECT * FROM media WHERE CommentID = $CommentID";
        $result = chayTruyVanTraVeDL($link, $query);

        $mediaList = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $mediaList[] = new Media(
                $row['MediaID'],
                $row['UserID'],
                $row['MediaType'],
                $row['FilePath'],
                $row['CreatedAt'],
                $row['CommentID'],
                $row['PostID']
            );
        }

        giaiPhongKetNoi($link);
        return $mediaList;
    }

    // Lấy tất cả media của một user
    public function getMediaByUserID($UserID) {
        $link = null;
        taoKetNoi($link);

        $UserID = mysqli_real_escape_string($link, $UserID);

        $query = "SELECT * FROM media WHERE UserID = $UserID";
        $result = chayTruyVanTraVeDL($link, $query);

        $mediaList = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $mediaList[] = new Media(
                $row['MediaID'],
                $row['UserID'],
                $row['MediaType'],
                $row['FilePath'],
                $row['CreatedAt'],
                $row['CommentID'],
                $row['PostID']
            );
        }

        giaiPhongKetNoi($link);
        return $mediaList;
    }

    // Lấy 1 media theo MediaID
    public function getMediaByID($MediaID) {
        $link = null;
        taoKetNoi($link);

        $MediaID = mysqli_real_escape_string($link, $MediaID);

        $query = "SELECT * FROM media WHERE MediaID = $MediaID";
        $result = chayTruyVanTraVeDL($link, $query);

        $row = mysqli_fetch_assoc($result);
        giaiPhongKetNoi($link);

        if (!$row) {
            return null;
        }

        return new Media(
            $row['MediaID'],
            $row['UserID'],
            $row['MediaType'],
            $row['FilePath'],
            $row['CreatedAt'],
            $row['CommentID'],
            $row['PostID']
        );
    }

    // Xoá media theo MediaID
    public function deleteMedia($MediaID) {
        $link = null;
        taoKetNoi($link);

        $MediaID = mysqli_real_escape_string($link, $MediaID);

        $query = "DELETE FROM media WHERE MediaID = $MediaID";
        $result = chayTruyVanKhongTraVeDL($link, $query);

        giaiPhongKetNoi($link);

        if (!$result) {
            return "Failed to delete media.";
        }
        return "Media deleted successfully.";
    }
}
?>