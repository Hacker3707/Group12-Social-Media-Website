<?php 
include_once "MVC/Model/AppModel.php";
include_once "Entity/Comment.php";

class CommentModel extends AppModel {

    public function createComment(Comment $comment) {
        $parentCommentId = $comment->getReplyToCommentId() === null ? "NULL" : (int)$comment->getReplyToCommentId();
        $content = mysqli_real_escape_string($this->link, $comment->getContent());
        $userId = (int)$comment->getUserId();
        $postId = (int)$comment->getPostId();
        $sql = "CALL createComment($postId, $userId, '$content', $parentCommentId)";
        $result = $this -> query($sql);

        if(!$result){
            return false;
        }

        /* flush result set của procedure */
        while(mysqli_more_results($this->link)){
            mysqli_next_result($this->link);
        }

        return true;
    }

    public function fetchByField($field, $value) 
    {
        $allowed = ['UserID','PostID', 'CommentID'];
        if (!in_array($field,$allowed)) {
            return false;
        }
        $value = mysqli_real_escape_string($this->link, $value);
        $value = (int)$value;
        $data = array();
        $sql = "SELECT * FROM comment WHERE $field = $value ORDER BY CreatedAt DESC";
        $result = $this->query($sql);
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($data, new Comment(
                $row['CommentID'], $row['CommentParentID'],
                $row['PostID'], $row['UserID'],
                $row['Content'], $row['CreatedAt'], []
            ));
        }
        return $data;
    }   

    public function deleteComment($commentId) {
        $sql = "DELETE FROM comment WHERE CommentID = $commentId";
        return $this->execute($sql);
    }

    
    
}

?>