<?php
include_once __DIR__ . "/../Model/MediaModel.php";
include_once __DIR__ . "/AppController.php";

class MediaController extends AppController {

    private $mediaModel;

    public function __construct() {
        $this->mediaModel = new MediaModel();
    }

    // ================= HELPER: REDIRECT =================
    protected function redirect($url, $message = null) {
        if ($message) $_SESSION['flash_message'] = $message;
        header("Location: $url");
        exit();
    }

    // ================= UPLOAD MEDIA CHO POST =================
    public function uploadForPost() {

        if (!isset($_SESSION['user_id'])) {
            echo "unauthorized";
            exit;
        }

        $userId = (int)$_SESSION['user_id'];
        $postId = isset($_POST['post_id']) ? (int)$_POST['post_id'] : null;

        if (!$postId) {
            echo "fail:no_post_id";
            exit;
        }

        if (!isset($_FILES['media']) || $_FILES['media']['error'] !== 0) {
            echo "fail:no_file";
            exit;
        }

        $result = $this->handleUpload($_FILES['media'], $userId, $postId, null);

        echo $result ? "success" : "fail:upload_error";
        exit;
    }

    // ================= UPLOAD MEDIA CHO COMMENT =================
    public function uploadForComment() {

        if (!isset($_SESSION['user_id'])) {
            echo "unauthorized";
            exit;
        }

        $userId    = (int)$_SESSION['user_id'];
        $commentId = isset($_POST['comment_id']) ? (int)$_POST['comment_id'] : null;

        if (!$commentId) {
            echo "fail:no_comment_id";
            exit;
        }

        if (!isset($_FILES['media']) || $_FILES['media']['error'] !== 0) {
            echo "fail:no_file";
            exit;
        }

        $result = $this->handleUpload($_FILES['media'], $userId, null, $commentId);

        echo $result ? "success" : "fail:upload_error";
        exit;
    }

    // ================= XÓA MEDIA =================
    public function deleteMedia() {

        if (!isset($_SESSION['user_id'])) {
            echo "unauthorized";
            exit;
        }

        $mediaId = isset($_POST['media_id']) ? (int)$_POST['media_id'] : null;

        if (!$mediaId) {
            echo "fail";
            exit;
        }

        $media = $this->mediaModel->getById($mediaId);

        if ($media) {
            $absolutePath = __DIR__ . "/../../" . $media->getFilePath();
            if (file_exists($absolutePath)) {
                unlink($absolutePath);
            }
        }

        $result = $this->mediaModel->deleteById($mediaId);

        echo $result ? "success" : "fail";
        exit;
    }

    // ================= LẤY MEDIA THEO POST (AJAX) =================
    public function getByPost() {

        $postId = isset($_GET['post_id']) ? (int)$_GET['post_id'] : null;

        if (!$postId) {
            header('Content-Type: application/json');
            echo json_encode([]);
            exit;
        }

        $mediaList = $this->mediaModel->getByPostId($postId);

        $output = [];
        foreach ($mediaList as $media) {
            $output[] = [
                'media_id'   => $media->getMediaID(),
                'media_type' => $media->getMediaType(),
                'file_path'  => $media->getFilePath(),
                'created_at' => $media->getCreatedAt(),
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($output);
        exit;
    }

    // ================= PRIVATE HELPER: XỬ LÝ UPLOAD FILE =================
    private function handleUpload($file, $userId, $postId, $commentId) {

        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            return false;
        }

        // 1. Xác định loại file — chỉ chấp nhận ảnh và video
        $mimeType  = mime_content_type($file['tmp_name']);
        $mediaType = null;

        if (str_starts_with($mimeType, 'image/')) {
            $mediaType = 'photo';
        } elseif (str_starts_with($mimeType, 'video/')) {
            $mediaType = 'video';
        } else {
            return false;
        }

        // 2. Tạo thư mục uploads/ NGAY TRONG PROJECT
        $uploadDir = __DIR__ . "/../../uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // 3. Đặt tên file an toàn
        $safeName = time() . "_" . $userId . "_" . preg_replace('/[^A-Za-z0-9._-]/', '_', basename($file['name']));
        $destPath = $uploadDir . $safeName;

        // 4. Đường dẫn lưu vào DB để browser đọc được
        $dbPath = "uploads/" . $safeName;

        if (!move_uploaded_file($file['tmp_name'], $destPath)) {
            return false;
        }

        // 5. Ghi vào bảng media
        if ($postId !== null) {
            $mediaId = $this->mediaModel->insertMediaForPost($userId, $postId, $mediaType, $dbPath);
        } else {
            $mediaId = $this->mediaModel->insertMediaForComment($userId, $commentId, $mediaType, $dbPath);
        }

        return $mediaId !== null;
    }
}
?>