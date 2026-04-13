<?php
include_once __DIR__ . "/../Model/ReactionModel.php";

class ReactionController {

    private $reactionModel;

    public function __construct() {
        $this->reactionModel = new ReactionModel();
    }

    public function addReaction() {

    $postId = $_POST['postId'] ?? null;
    $userId = $_SESSION['user_id'] ?? null;
    $commentId = $_POST['commentId'] ?? null;
    $type = $_POST['type'] ?? 'like';

    if (empty($userId) || (empty($postId) && empty($commentId))) {
        http_response_code(400);
        echo "fail";
        return;
    }

    $reaction = new Reaction(null, $postId, $commentId, $userId, $type);
    $result = $this->reactionModel->insertReaction($reaction);

    if (!$result) {
        echo "fail";
        exit;
    }

    // ================= 🔔 NOTIFICATION =================
    include_once __DIR__ . "/../Model/NotificationModel.php";
    include_once __DIR__ . "/../Model/PostModel.php";
    include_once __DIR__ . "/../Model/CommentModel.php";

    $notiModel = new NotificationModel();
    $username = $_SESSION['username'];

    // 🔵 LIKE POST
    if ($postId) {

        $postModel = new PostModel();
        $post = $postModel->getById($postId);

        if ($post) {
            $postOwnerId = $post->getUserId();

            if ($postOwnerId != $userId) {
                $notiModel->insert(
                    $postOwnerId,
                    $userId,
                    "<b>$username</b> đã thích bài viết của bạn",
                    "like"
                );
            }
        }
    }

    // 🟣 LIKE COMMENT
    if ($commentId) {

        $commentModel = new CommentModel();
        $comment = $commentModel->getById($commentId);

        if ($comment) {
            $commentOwnerId = $comment->getUserId();

            if ($commentOwnerId != $userId) {
                $notiModel->insert(
                    $commentOwnerId,
                    $userId,
                    "<b>$username</b> đã thích bình luận của bạn",
                    "like"
                );
            }
        }
    }
    // ==================================================

    echo "success";
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