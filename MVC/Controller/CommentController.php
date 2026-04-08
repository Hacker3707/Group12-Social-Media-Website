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
            echo json_encode([
                "status" => "error",
                "message" => "Create comment failed"
            ]);
            return;
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
}
?>