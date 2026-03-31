<?php 
class CommentControl {
    private $commentModel;

    public function __construct() {
        include_once __DIR__ . "/../Model/CommentModel.php";
        $this->commentModel = new CommentModel();
    }

    public function createComment() {
        $postId = $_POST['post_id'];
        $userId = $_POST['user_id'];
        $content = $_POST['content'];

        $comment = new Comment(null, $postId, $userId, $content);
        $this->commentModel->createComment($comment);

        header("Location: ./index.php");
    }


}
?>