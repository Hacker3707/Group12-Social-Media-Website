<?php
include_once __DIR__ . '/../../Entity/Media.php';
include_once __DIR__ . "/../../Module/db_module.php";

class MediaModel {

    // Thêm media cho post
    public function insertMediaForPost($link, $UserID, $PostID, $MediaType, $FilePath) {
        $UserID    = mysqli_real_escape_string($link, $UserID);
        $PostID    = mysqli_real_escape_string($link, $PostID);
        $MediaType = mysqli_real_escape_string($link, $MediaType);
        $FilePath  = mysqli_real_escape_string($link, $FilePath);

        $query = "INSERT INTO media (UserID, PostID, MediaType, FilePath)
                  VALUES ($UserID, $PostID, '$MediaType', '$FilePath')";

        $result = chayTruyVanKhongTraVeDL($link, $query);
        if (!$result) return null;
        return mysqli_insert_id($link);
    }

    // Thêm media cho comment
    public function insertMediaForComment($link, $UserID, $CommentID, $MediaType, $FilePath) {
        $UserID    = mysqli_real_escape_string($link, $UserID);
        $CommentID = mysqli_real_escape_string($link, $CommentID);
        $MediaType = mysqli_real_escape_string($link, $MediaType);
        $FilePath  = mysqli_real_escape_string($link, $FilePath);

        $query = "INSERT INTO media (UserID, CommentID, MediaType, FilePath)
                  VALUES ($UserID, $CommentID, '$MediaType', '$FilePath')";

        $result = chayTruyVanKhongTraVeDL($link, $query);
        if (!$result) return null;
        return mysqli_insert_id($link);
    }

    // Lấy tất cả media của một bài post
    public function getByPostID($link, $PostID) {
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
        return $mediaList;
    }

    // Lấy tất cả media của một comment
    public function getByCommentID($link, $CommentID) {
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
        return $mediaList;
    }

    // Lấy tất cả media của một user
    public function getByUserID($link, $UserID) {
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
        return $mediaList;
    }

    // Lấy 1 media theo MediaID
    public function getByID($link, $MediaID) {
        $MediaID = mysqli_real_escape_string($link, $MediaID);

        $query = "SELECT * FROM media WHERE MediaID = $MediaID";
        $result = chayTruyVanTraVeDL($link, $query);

        $row = mysqli_fetch_assoc($result);
        if (!$row) return null;

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
    public function deleteByID($link, $MediaID) {
        $MediaID = mysqli_real_escape_string($link, $MediaID);

        $query = "DELETE FROM media WHERE MediaID = $MediaID";
        return chayTruyVanKhongTraVeDL($link, $query);
    }
}
?>