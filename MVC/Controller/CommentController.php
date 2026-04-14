<?php 
include_once __DIR__ . "/../Model/CommentModel.php";

class CommentController {
    private $commentModel;
   
    public function __construct() {
        $this->commentModel = new CommentModel();
    }

   public function addComment() {
     header('Content-Type: application/json');
     ini_set('display_errors', 1);
error_reporting(E_ALL);
    

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
    $username = $_SESSION['username'];
    $content = $_POST['content'];

    // 🔥 FIX: thống nhất biến
    $parentCommentId = !empty($_POST['parent_comment_id']) 
    ? (int)$_POST['parent_comment_id'] 
    : null;

    $comment = new Comment(null, $parentCommentId, $postId, $userId, $content, null, []);
    $result = $this->commentModel->createComment($comment);

    if(!$result){
        echo json_encode([
            "status" => "error",
            "message" => "Insert failed"
        ]);
        exit;
    }

    // ================= 🔔 NOTIFICATION =================
    include_once __DIR__ . "/../Model/NotificationModel.php";
    include_once __DIR__ . "/../Model/PostModel.php";

    $notiModel = new NotificationModel();
    $postModel = new PostModel();

    $post = $postModel->getById($postId);

    if ($post) {

        $postOwnerId = $post->getUserId();

        // 🔵 COMMENT vào bài
        if ($parentCommentId === null) {

            if ($postOwnerId != $userId) {

                $notiModel->insert(
                    $postOwnerId,
                    $userId,
                    "<b>$username</b> đã bình luận bài viết của bạn",
                    "comment"
                );
            }
        }
         // 🟣 REPLY comment
else {


    $parentComment = $this->commentModel->getById($parentCommentId);

   if ($parentComment)  {

       $commentOwnerId = $parentComment->getUserId(); 
        // không gửi cho chính mình
        if ($commentOwnerId != $userId) {

            $notiModel->insert(
                $commentOwnerId,
                $userId,
                "<b>$username</b> đã trả lời bình luận của bạn",
                "reply"
            );
        }
    }

        
    }
   
}
    // ==================================================

    echo json_encode([
        "status" => "success",
        "comment" => [
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