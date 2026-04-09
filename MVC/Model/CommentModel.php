<?php 
include_once "MVC/Model/AppModel.php";
include_once "Entity/Comment.php";

class CommentModel extends AppModel {

    public function createComment(Comment $comment) {

        $parentCommentId = $comment->getParentCommentId() === null 
            ? "NULL" 
            : (int)$comment->getParentCommentId();

        $content = mysqli_real_escape_string($this->link, $comment->getContent());
        $userId = (int)$comment->getUserId();
        $postId = (int)$comment->getPostId();

        $sql = "CALL createComment($postId, $userId, '$content', $parentCommentId)";
        $result = $this->query($sql);

        if(!$result){
            return false;
        }

        $commentId = mysqli_insert_id($this->link);

        while(mysqli_more_results($this->link)){
            mysqli_next_result($this->link);
        }

        return [
            "id" => $commentId,
            "user_id" => $userId,
            "content" => $content
        ];
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
        $sql = "SELECT 
            c.CommentID,
            c.CommentParentID,
            c.PostID,
            c.UserID,
            c.Content,
            c.CreatedAt,
            u.Username
        FROM comment c
        JOIN users u ON c.UserID = u.UserID
        WHERE c.$field = $value
        ORDER BY c.CreatedAt DESC";
        $result = $this->query($sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $comment = new Comment(
                $row['CommentID'], $row['CommentParentID'],
                $row['PostID'], $row['UserID'],
                $row['Content'], $row['CreatedAt'], []
            );
            $comment->setUsername($row['Username']);
            array_push($data, $comment);
        }
        return $data;
    }   

    public function deleteComment($commentId) {
        $sql = "DELETE FROM comment WHERE CommentID = $commentId";
        return $this->execute($sql);
    }

    
    
}

?>