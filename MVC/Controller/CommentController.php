<?php 
include_once __DIR__ . "/../Model/CommentModel.php";

class CommentController {
    private $commentModel;

    public function __construct() {
        $this->commentModel = new CommentModel();
    }

    public function addComment() {

        header('Content-Type: application/json');

        if(empty($_POST['postId']) || empty($_POST['content'])){
            echo json_encode([
                "status" => "error",
                "message" => "Missing data"
            ]);
            return;
        }

        $postId = (int)$_POST['postId'];
        $userId = $_SESSION['user_id'];
        $username = $_SESSION['username']; // hoặc query DB
        $content = $_POST['content'];
        $parentCommentId = !empty($_POST['parentId']) ? $_POST['parentId'] : null;

        $comment = new Comment(null, $parentCommentId, $postId, $userId, $content, null, []);

        $result = $this->commentModel->createComment($comment);

        if(!$result){
            echo "fail";
            die(mysqli_error($this->commentModel->getConnection()));
        }
        include_once __DIR__ . "/../Model/NotificationModel.php";
    include_once __DIR__ . "/../Model/PostModel.php";

    $notiModel = new NotificationModel();
    $postModel = new PostModel();

    $post = $postModel->getById($postId);
    $postOwnerId = $post->getUserId();

 include_once __DIR__ . "/../Model/NotificationModel.php";
include_once __DIR__ . "/../Model/PostModel.php";

$notiModel = new NotificationModel();
$postModel = new PostModel();

$post = $postModel->getById($postId);
$postOwnerId = $post->getUserId();

// 🔥 FIX quan trọng
$parentCommentId = !empty($_POST['parent_comment_id']) ? $_POST['parent_comment_id'] : null;


// 🔵 CASE 1: COMMENT
if ($parentCommentId === null) {

    if ($postOwnerId != $userId) {

        $notiModel->insert(
            $postOwnerId,
            $userId,
            "<b>" . $_SESSION['username'] . "</b> đã bình luận bài viết của bạn",
            "comment"
        );
    }

}


        echo json_encode([
            "status" => "success",
            "comment" => [
                "id" => $result["id"],
                "user_id" => $userId,
                "username" => $username,
                "content" => htmlspecialchars($content),
                "created_at" => date("Y-m-d H:i")
            ]
        ]);
    }

        public function deleteComment() {

        header('Content-Type: application/json');

        $commentId = $_POST['commentId'] ?? null;

        if(!$commentId){
            echo json_encode([
                "status" => "error",
                "message" => "Missing commentId"
            ]);
            return;
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