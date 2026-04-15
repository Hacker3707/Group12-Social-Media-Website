<?php 
include_once __DIR__ . "/../Model/CommentModel.php";
include_once __DIR__ . "/../Model/NotificationModel.php";
include_once __DIR__ . "/../Model/PostModel.php";

class CommentController {
    private $commentModel;
    private $notiModel;
    private $postModel;
    public function __construct() {
        $this->commentModel = new CommentModel();
        $this -> notiModel = new NotificationModel();
        $this -> postModel = new PostModel();
    }

   public function addComment() {
     header('Content-Type: application/json');
    

    if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        "status" => "error",
        "message" => "not_logged_in"
    ]);
    exit;
}

    if(empty($_POST['postId']) || empty($_POST['content'])){
        echo json_encode([
            "status" => "error",
            "message" => "Missing data"
        ]);
        exit;
    }

    $postId = (int)$_POST['postId'];
    $userId = $_SESSION['user_id'];
    $username = $_SESSION['username'] ?? 'User';
    $content = trim($_POST['content']);

    if ($content === '') {
        echo json_encode([
            "status" => "error",
            "message" => "Missing data"
        ]);
        exit;
    }

    // 🔥 FIX: thống nhất biến
    $parentCommentId = !empty($_POST['parent_comment_id']) 
    ? (int)$_POST['parent_comment_id'] 
    : null;

    $comment = new Comment(null, $parentCommentId, $postId, $userId, $content, null, []);
    $newCommentId = $this->commentModel->createComment($comment);

    if($newCommentId === false){
        echo json_encode([
            "status" => "error",
            "message" => "Insert failed"
        ]);
        exit;
    }

    // ================= 🔔 NOTIFICATION =================
    

    $post = $this -> postModel->getById($postId);

    if ($post) {

        $postOwnerId = $post->getUserId();

        // 🔵 COMMENT vào bài
        if ($parentCommentId === null) {

            if ($postOwnerId != $userId) {

                $this -> notiModel->insert(
                    $postOwnerId,
                    $userId,
                    "<b>$username</b> đã bình luận bài viết của bạn",
                    "comment"
                );
            }
        } else {
            // 🟣 REPLY comment: thông báo cho cả chủ comment và chủ bài viết
            $parentComment = $this->commentModel->getById($parentCommentId);
            $notifiedUsers = [];

            if ($parentComment && isset($parentComment['user_id'])) {
                $commentOwnerId = (int)$parentComment['user_id'];

                // Không gửi cho chính mình và không gửi trùng
                if ($commentOwnerId !== (int)$userId && !isset($notifiedUsers[$commentOwnerId])) {
                    $this -> notiModel->insert(
                        $commentOwnerId,
                        $userId,
                        "<b>$username</b> đã trả lời bình luận của bạn",
                        "reply"
                    );
                    $notifiedUsers[$commentOwnerId] = true;
                }
            }

            // Chủ bài viết cũng nhận thông báo khi có reply
            if ((int)$postOwnerId !== (int)$userId && !isset($notifiedUsers[(int)$postOwnerId])) {
                $this -> notiModel->insert(
                    $postOwnerId,
                    $userId,
                    "<b>$username</b> đã trả lời trong bài viết của bạn",
                    "reply"
                );
                $notifiedUsers[(int)$postOwnerId] = true;
            }
        }
   
}
    // ==================================================

    echo json_encode([
        "status" => "success",
        "comment" => [
            "id" => $newCommentId,
            "user_id" => $userId,
            "username" => $username,
            "content" => htmlspecialchars($content),
            "created_at" => date("Y-m-d H:i")
        ]
    ]);
     exit;
}

    // ================= XÓA BÌNH LUẬN =================

        public function deleteComment() {

        header('Content-Type: application/json');

        $commentId = $_POST['commentId'] ?? null;

        if(!$commentId){
            echo json_encode([
                "status" => "error",
                "message" => "Missing commentId"
            ]);
            exit;
        }

        $result = $this->commentModel->deleteComment($commentId);

        if($result){
            echo json_encode([
                "status" => "success",
                "commentId" => $commentId
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Delete failed"
            ]);
            exit;
        }
    }

    // ================= ADMIN: XÓA BÌNH LUẬN =================
    public function adminDelete() {
        // Chỉ Admin mới được dùng chức năng này
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: index.php");
            exit();
        }
        
        $commentId = (int)($_GET['id'] ?? 0);
        $postId = (int)($_GET['post_id'] ?? 0); // Kèm theo post_id để biết đường quay lại
        
        if ($commentId) {
            $this->commentModel->deleteComment($commentId);
            $_SESSION['flash_message'] = "Đã xóa bình luận vi phạm!";
        }
        
        // Trở về trang chi tiết của đúng bài viết đó
        header("Location: index.php?controller=post&action=adminDetail&id=$postId");
        exit();
    }
}
?>