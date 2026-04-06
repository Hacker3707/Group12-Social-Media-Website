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
}
?>