<?php
include_once __DIR__ . "/../Model/ReactionModel.php";
include_once __DIR__ . "/../Model/NotificationModel.php";
include_once __DIR__ . "/../Model/PostModel.php";
include_once __DIR__ . "/../Model/CommentModel.php";

class ReactionController {
    private $reactionModel;
    private $notiModel;
    public function __construct() {
        $this->reactionModel = new ReactionModel();
        $this -> notiModel = new NotificationModel();
    }

    public function action_forReaction() {
        header('Content-Type: application/json');

        $postId = $_POST['postId'] ?? null;
        $userId = $_SESSION['user_id'] ?? null;
        $commentId = $_POST['commentId'] ?? null;
        $type = $_POST['type'] ?? 'like';

        if (empty($userId) || (empty($postId) && empty($commentId))) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing data']);
            return;
        }

        $reaction = new Reaction(null, $postId, $commentId, $userId, $type);
        
        // CHỈ GỌI 1 LẦN DUY NHẤT
        $result = $this->reactionModel->insertReaction($reaction);

        // ================= 🔔 NOTIFICATION =================
        $notiModel = new NotificationModel();
        $username = $_SESSION['username'] ?? 'User';

        // Kiểm tra xem hành động vừa rồi là Like (thêm mới) hay Unlike (xóa). 
        // Giả sử $result mảng trả về ['reacted' => 1] khi like.
        $isLiked = (is_array($result) && isset($result['reacted']) && $result['reacted'] == 1);

        if ($isLiked) {
            // 🔵 LIKE POST
            if ($postId) {
                $postModel = new PostModel();
                $post = $postModel->getById($postId);
                if ($post && $post->getUserId() != $userId) {
                    $notiModel->insert($post->getUserId(), $userId, "<b>$username</b> đã thích bài viết của bạn", "like");
                }
            }

            // 🟣 LIKE COMMENT
            if ($commentId) {
                $commentModel = new CommentModel();
                $comment = $commentModel->getById($commentId);
                if ($comment && isset($comment['user_id']) && $comment['user_id'] != $userId) {
                    $notiModel->insert($comment['user_id'], $userId, "<b>$username</b> đã thích bình luận của bạn", "like");
                }
            }
        }
        // ==================================================

        // TRẢ KẾT QUẢ CHO JAVASCRIPT Ở CUỐI CÙNG
        echo json_encode($result);
        exit;
    }

    public function removeReaction() {
        $reactionId = $_POST['reactionId'];

        if (empty($reactionId)) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing reaction ID']);
            return;
        }

        $result = $this->reactionModel->deleteReaction($reactionId);
        echo $result ? "success" : "fail";
        exit;
    }
}
?>