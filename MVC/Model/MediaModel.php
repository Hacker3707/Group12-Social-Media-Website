<?php
include_once "MVC/Model/AppModel.php";
include_once "Entity/Media.php";

class MediaModel extends AppModel {

    // ================= THÊM MEDIA CHO POST =================
    public function insertMediaForPost($userId, $postId, $mediaType, $filePath) {

        $userId    = (int)$userId;
        $postId    = (int)$postId;
        $mediaType = mysqli_real_escape_string($this->link, $mediaType);
        $filePath  = mysqli_real_escape_string($this->link, $filePath);

        $sql = "INSERT INTO media (UserID, PostID, MediaType, FilePath)
                VALUES ($userId, $postId, '$mediaType', '$filePath')";

        if ($this->execute($sql)) {
            return $this->getLastInsertId();
        }
        return null;
    }

    // ================= THÊM MEDIA CHO COMMENT =================
    public function insertMediaForComment($userId, $commentId, $mediaType, $filePath) {

        $userId    = (int)$userId;
        $commentId = (int)$commentId;
        $mediaType = mysqli_real_escape_string($this->link, $mediaType);
        $filePath  = mysqli_real_escape_string($this->link, $filePath);

        $sql = "INSERT INTO media (UserID, CommentID, MediaType, FilePath)
                VALUES ($userId, $commentId, '$mediaType', '$filePath')";

        if ($this->execute($sql)) {
            return $this->getLastInsertId();
        }
        return null;
    }

    // ================= LẤY MEDIA THEO POST =================
    public function getByPostId($postId) {

        $postId = (int)$postId;

        $sql = "SELECT * FROM media WHERE PostID = $postId";
        $result = $this->query($sql);

        $list = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $list[] = new Media(
                $row['MediaID'],
                $row['UserID'],
                $row['MediaType'],
                $row['FilePath'],
                $row['CreatedAt'],
                $row['CommentID'],
                $row['PostID']
            );
        }
        return $list;
    }

    // ================= LẤY MEDIA THEO COMMENT =================
    public function getByCommentId($commentId) {

        $commentId = (int)$commentId;

        $sql = "SELECT * FROM media WHERE CommentID = $commentId";
        $result = $this->query($sql);

        $list = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $list[] = new Media(
                $row['MediaID'],
                $row['UserID'],
                $row['MediaType'],
                $row['FilePath'],
                $row['CreatedAt'],
                $row['CommentID'],
                $row['PostID']
            );
        }
        return $list;
    }

    // ================= LẤY MEDIA THEO USER =================
    public function getByUserId($userId) {

        $userId = (int)$userId;

        $sql = "SELECT * FROM media WHERE UserID = $userId";
        $result = $this->query($sql);

        $list = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $list[] = new Media(
                $row['MediaID'],
                $row['UserID'],
                $row['MediaType'],
                $row['FilePath'],
                $row['CreatedAt'],
                $row['CommentID'],
                $row['PostID']
            );
        }
        return $list;
    }

    // ================= LẤY 1 MEDIA THEO ID =================
    public function getById($mediaId) {

        $mediaId = (int)$mediaId;

        $sql = "SELECT * FROM media WHERE MediaID = $mediaId";
        $result = $this->query($sql);

        if ($row = mysqli_fetch_assoc($result)) {
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
        return null;
    }

    // ================= XÓA MEDIA THEO ID =================
    public function deleteById($mediaId) {

        $mediaId = (int)$mediaId;

        $sql = "DELETE FROM media WHERE MediaID = $mediaId";
        return $this->execute($sql);
    }
}
?>