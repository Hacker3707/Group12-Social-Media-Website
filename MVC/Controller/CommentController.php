<?php 
include_once __DIR__ . "/../Model/CommentModel.php";

class CommentController {
    private $commentModel;

    public function __construct() {
        $this->commentModel = new CommentModel();
    }

    public function addComment() {
        $postId = $_POST['postId'];
        $userId = $_SESSION['user_id']; // Assuming user ID is stored in session
        $content = $_POST['content'];
        $parentCommentId = $_POST['parent_comment_id'] ?? null; // Optional, for replies

        $comment = new Comment(null, $parentCommentId, $postId, $userId, $content, null, []);
        $result = $this->commentModel->createComment($comment);

        if(!$result){
            echo "fail";
            die(mysqli_error($this->commentModel->getConnection()));
        }

        echo "success";
        exit;
    }

    public function deleteComment() {
        $commentId = $_POST['comment_id'];
        $this->commentModel->deleteComment($commentId);
    
        header("Location: ./index.php");
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