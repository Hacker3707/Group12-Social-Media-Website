<?php 
include_once "MVC/Model/AppModel.php";
include_once "Entity/Comment.php";

class CommentModel extends AppModel {

    public function createComment($userId, $postId, $content, $parentCommentId = null) {
        $content = mysqli_real_escape_string($this->link, $content);
        $sql = "CALL createComment($userId, $postId, '$content', $parentCommentId)";
        if ($this->execute($sql)) {
            return $this->getLastInsertId();
        }
        return false;
    }

    public function fetchByField($field, $value) 
    {
        $allowed = ['UserID','PostID', 'CommentID'];
        if (!in_array($field,$allowed)) {
            return false;
        }
        $value = mysqli_real_escape_string($this->link, $value);
        $data = array();
        $sql = "SELECT * FROM comment WHERE $field = $value ORDER BY CreatedAt DESC";
        $result = $this->query($sql);
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($data, new Comment(
                $row['CommentID'], $row['ParentCommentID'],
                $row['PostID'], $row['UserID'],
                $row['Content'], [],
                $row['CreatedAt']
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