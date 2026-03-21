<?php
include_once "MVC/Model/Media.php";
include_once "MVC/Module/db_module.php";

class MediaService {

    // Thêm media cho post
    public function createMediaForPost($UserID, $PostID, $MediaType, $FilePath) {
        $link = null;
        taoKetNoi($link);

        $query = "INSERT INTO media (UserID, PostID, MediaType, FilePath)
                  VALUES ($UserID, $PostID, '$MediaType', '$FilePath')";

        $result = chayTruyVanKhongTraVeDL($link, $query);

        giaiPhongKetNoi($link);

        if (!$result) {
            return "Failed to create media.";
        }
        return "Media created successfully.";
    }

    // Thêm media cho comment
    public function createMediaForComment($UserID, $CommentID, $MediaType, $FilePath) {
        $link = null;
        taoKetNoi($link);

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