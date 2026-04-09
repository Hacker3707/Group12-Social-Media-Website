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
    // Gọi bằng AJAX từ createpost.php sau khi tạo post thành công
    // Nhận: $_FILES['media'], $_POST['post_id'], $_SESSION['user_id']
    public function uploadForPost() {

        if (!isset($_SESSION['user_id'])) {
            echo "unauthorized";
            exit;
        }

        $userId = (int)$_SESSION['user_id'];
        $postId = isset($_POST['post_id']) ? (int)$_POST['post_id'] : null;

        if (!$postId) {
            echo "fail";
            exit;
        }

        if (!isset($_FILES['media']) || $_FILES['media']['error'] !== 0) {
            echo "fail";
            exit;
        }

        $result = $this->handleUpload($_FILES['media'], $userId, $postId, null);

        echo $result ? "success" : "fail";
        exit;
    }

    // ================= UPLOAD MEDIA CHO COMMENT =================
    // Nhận: $_FILES['media'], $_POST['comment_id'], $_SESSION['user_id']
    public function uploadForComment() {

        if (!isset($_SESSION['user_id'])) {
            echo "unauthorized";
            exit;
        }

        $userId    = (int)$_SESSION['user_id'];
        $commentId = isset($_POST['comment_id']) ? (int)$_POST['comment_id'] : null;

        if (!$commentId) {
            echo "fail";
            exit;
        }

        if (!isset($_FILES['media']) || $_FILES['media']['error'] !== 0) {
            echo "fail";
            exit;
        }

        $result = $this->handleUpload($_FILES['media'], $userId, null, $commentId);

        echo $result ? "success" : "fail";
        exit;
    }

    // ================= XÓA MEDIA =================
    // Nhận: $_POST['media_id']
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

        // Lấy thông tin để xóa file vật lý trên server trước
        $media = $this->mediaModel->getById($mediaId);

        if ($media && file_exists($media->getFilePath())) {
            unlink($media->getFilePath());
        }

        $result = $this->mediaModel->deleteById($mediaId);

        echo $result ? "success" : "fail";
        exit;
    }

    // ================= LẤY MEDIA THEO POST (AJAX) =================
    // Trả về JSON — dùng cho modal xem ảnh/video trong postview.php
    // Nhận: $_GET['post_id']
    public function getByPost() {

        $postId = isset($_GET['post_id']) ? (int)$_GET['post_id'] : null;

        if (!$postId) {
            echo json_encode([]);
            exit;
        }

        $mediaList = $this->mediaModel->getByPostId($postId);

        $output = [];
        foreach ($mediaList as $media) {
            $output[] = [
                'media_id'   => $media->getMediaId(),
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
    // Dùng chung cho uploadForPost() và uploadForComment()
    private function handleUpload($file, $userId, $postId, $commentId) {

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

        // 2. Tạo thư mục uploads/ nếu chưa tồn tại
       $uploadDir = __DIR__ . "/../../uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // 3. Đặt tên file: timestamp_userId_tênGốc để tránh trùng
        $safeName = time() . "_" . $userId . "_" . basename($file['name']);
        $destPath = $uploadDir . $safeName;
        $dbPath   = "uploads/" . $safeName; // Đường dẫn tương đối lưu vào DB

        if (!move_uploaded_file($file['tmp_name'], $destPath)) {
            return false;
        }

        // 4. Ghi vào bảng media
        if ($postId !== null) {
            $mediaId = $this->mediaModel->insertMediaForPost($userId, $postId, $mediaType, $dbPath);
        } else {
            $mediaId = $this->mediaModel->insertMediaForComment($userId, $commentId, $mediaType, $dbPath);
        }

        return $mediaId !== null;
    }
}
?>
