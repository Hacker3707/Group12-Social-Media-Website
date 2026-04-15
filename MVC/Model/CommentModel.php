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

        $commentId = 0;

        if ($result instanceof mysqli_result) {
            $row = mysqli_fetch_assoc($result);
            if ($row) {
                if (isset($row['CommentID'])) {
                    $commentId = (int)$row['CommentID'];
                } elseif (isset($row['id'])) {
                    $commentId = (int)$row['id'];
                }
            }
            mysqli_free_result($result);
        }

        while(mysqli_more_results($this->link)){
            mysqli_next_result($this->link);
        }

        if ($commentId <= 0) {
            $commentId = (int)mysqli_insert_id($this->link);
        }

        // Fallback an toàn cho trường hợp CALL không trả insert_id
        if ($commentId <= 0) {
            $fallbackSql = "SELECT CommentID
                            FROM comment
                            WHERE PostID = $postId AND UserID = $userId
                            ORDER BY CommentID DESC
                            LIMIT 1";
            $fallbackResult = $this->query($fallbackSql);
            if ($fallbackResult instanceof mysqli_result) {
                $fallbackRow = mysqli_fetch_assoc($fallbackResult);
                if ($fallbackRow && isset($fallbackRow['CommentID'])) {
                    $commentId = (int)$fallbackRow['CommentID'];
                }
                mysqli_free_result($fallbackResult);
            }
        }

        return $commentId > 0 ? $commentId : false;
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
        // Thêm c.AvatarFP vào câu SELECT
        $sql = "SELECT 
            c.CommentID, c.CommentParentID, c.PostID, c.UserID, c.Content, c.CreatedAt,
            u.Username, u.AvatarFP
        FROM comment c
        JOIN users u ON c.UserID = u.UserID
        WHERE c.$field = $value
        ORDER BY c.CreatedAt DESC";

        $result = $this->query($sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $comment = new Comment(
                $row['CommentID'], $row['CommentParentID'], $row['PostID'], $row['UserID'],
                $row['Content'], $row['CreatedAt'], []
            );
            $comment->setUsername($row['Username']);
            
            // DÒNG THÊM MỚI
            $comment->setAvatar($row['AvatarFP']);

            array_push($data, $comment);
        }
        return $data;
    }   

    public function deleteComment($commentId) {
        $commentId = (int)$commentId;
        $sql = "DELETE FROM comment WHERE CommentID = $commentId";
        return $this->execute($sql);
    }

    public function softDeleteComment($commentId) {
        $commentId = (int)$commentId;
        $deletedText = mysqli_real_escape_string($this->link, 'Tin nhắn đã bị xóa');
        $sql = "UPDATE comment SET Content = '$deletedText' WHERE CommentID = $commentId";
        return $this->execute($sql);
    }

    public function hasReplies($commentId) {
        $commentId = (int)$commentId;
        $sql = "SELECT COUNT(*) AS total FROM comment WHERE CommentParentID = $commentId";
        $result = $this->query($sql);

        if ($result instanceof mysqli_result) {
            $row = mysqli_fetch_assoc($result);
            mysqli_free_result($result);
            return ((int)($row['total'] ?? 0)) > 0;
        }

        return false;
    }

    public function getById($commentId) {

    $commentId = (int)$commentId;

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
    WHERE c.CommentID = $commentId
    LIMIT 1";

    $result = $this->query($sql);

    if($row = mysqli_fetch_assoc($result)){
        return [
            "id" => $row['CommentID'],
            "parent_id" => $row['CommentParentID'],
            "post_id" => $row['PostID'],
            "user_id" => $row['UserID'],
            "username" => $row['Username'],
            "content" => htmlspecialchars($row['Content']),
            "created_at" => $row['CreatedAt']
        ];
    }

    return null;
}

    
    
}

?>