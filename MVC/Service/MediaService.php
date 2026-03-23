<?php
include_once "MVC/Model/Media.php";
include_once "MVC/Model/MediaModel.php";
include_once "MVC/Module/db_module.php";

class MediaService {

    private $mediaModel;

    public function __construct() {
        $this->mediaModel = new MediaModel();
    }

    // Thêm media cho post
    public function createMediaForPost($UserID, $PostID, $MediaType, $FilePath) {
        $link = null;
        taoKetNoi($link);

        $mediaId = $this->mediaModel->insertMediaForPost($link, $UserID, $PostID, $MediaType, $FilePath);

        giaiPhongKetNoi($link);

        if (!$mediaId) return "Failed to create media.";
        return "Media created successfully.";
    }

    // Thêm media cho comment
    public function createMediaForComment($UserID, $CommentID, $MediaType, $FilePath) {
        $link = null;
        taoKetNoi($link);

        $mediaId = $this->mediaModel->insertMediaForComment($link, $UserID, $CommentID, $MediaType, $FilePath);

        giaiPhongKetNoi($link);

        if (!$mediaId) return "Failed to create media.";
        return "Media created successfully.";
    }

    // Lấy tất cả media của một bài post
    public function getMediaByPostID($PostID) {
        $link = null;
        taoKetNoi($link);

        $mediaList = $this->mediaModel->getByPostID($link, $PostID);

        giaiPhongKetNoi($link);
        return $mediaList;
    }

    // Lấy tất cả media của một comment
    public function getMediaByCommentID($CommentID) {
        $link = null;
        taoKetNoi($link);

        $mediaList = $this->mediaModel->getByCommentID($link, $CommentID);

        giaiPhongKetNoi($link);
        return $mediaList;
    }

    // Lấy tất cả media của một user
    public function getMediaByUserID($UserID) {
        $link = null;
        taoKetNoi($link);

        $mediaList = $this->mediaModel->getByUserID($link, $UserID);

        giaiPhongKetNoi($link);
        return $mediaList;
    }

    // Lấy 1 media theo MediaID
    public function getMediaByID($MediaID) {
        $link = null;
        taoKetNoi($link);

        $media = $this->mediaModel->getByID($link, $MediaID);

        giaiPhongKetNoi($link);
        return $media;
    }

    // Xoá media theo MediaID
    public function deleteMedia($MediaID) {
        $link = null;
        taoKetNoi($link);

        $result = $this->mediaModel->deleteByID($link, $MediaID);

        giaiPhongKetNoi($link);

        if (!$result) return "Failed to delete media.";
        return "Media deleted successfully.";
    }
}
?>