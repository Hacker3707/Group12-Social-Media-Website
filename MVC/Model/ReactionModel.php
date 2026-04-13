<?php 
include_once __DIR__ . "/AppModel.php";
include_once __DIR__ . "/../../Entity/Reaction.php";
include_once __DIR__ . "/../../Module/db_module.php";

class ReactionModel extends AppModel {

    public function insertReaction(Reaction $reaction) {

        $postId = $reaction->getPostId();
        $commentId = $reaction->getCommentId();

        $postId = $postId !== null ? intval($postId) : "NULL";
        $commentId = $commentId !== null ? intval($commentId) : "NULL";
        $userId = intval($reaction->getUserId());
        $type = mysqli_real_escape_string($this->link, $reaction->getType());

        $sql = "CALL createReaction($postId,$userId,$commentId,'$type')";

        $result = $this->query($sql);
        $row = mysqli_fetch_assoc($result);

        return [
            "reacted" => (int)$row['reacted'],
            "total" => (int)$row['total']
        ];
    }


    public function fetchByField($field, $value) 
    {
        $allowed = ['UserID','PostID','CommentID'];
        if (!in_array($field,$allowed)) {
            return false;
        }
        $value = mysqli_real_escape_string($this->link, $value);
        $value = (int)$value;
        $data = array();
        $sql = "SELECT 
                r.ReactionID,
                r.PostID,
                r.CommentID,
                r.UserID,
                r.ReactionType,
                r.CreatedAt,
                u.Username
            FROM reaction r
            JOIN users u ON r.UserID = u.UserID
            WHERE r.$field = $value
            ORDER BY r.CreatedAt DESC";
        $result = $this->query($sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $reaction = new Reaction(
                $row['ReactionID'], $row['PostID'],
                $row['CommentID'], $row['UserID'],
                $row['ReactionType'], $row['CreatedAt']
            );
            $reaction->setUsername($row['Username']);
            array_push($data, $reaction);
        }
        return $data;
    }   

    public function getReactionsfromPost_withUserId($postId, $userId) 
    {
        $postId = (int)$postId;
        $userId = (int)$userId;
        $sql = "SELECT 
            r.ReactionID,
            r.PostID,
            r.CommentID,
            r.UserID,
            r.ReactionType,
            r.CreatedAt,
            u.Username
        FROM reaction r
        JOIN users u ON r.UserID = u.UserID
        WHERE r.PostID = $postId AND r.UserID = $userId
        ORDER BY r.CreatedAt DESC";
        $result = $this->query($sql);
        $reactions = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $reaction = new Reaction(
                $row['ReactionID'], $row['PostID'],
                $row['CommentID'], $row['UserID'],
                $row['ReactionType'], $row['CreatedAt']
            );
            $reaction->setUsername($row['Username']);
            $reactions[] = $reaction;
        
        }
        return $reactions;
    }

    public function deleteReaction($reactionId) {
    $sql = "DELETE FROM reaction WHERE ReactionID = $reactionId";
    return $this->execute($sql);
    }

    public function selectReactionsForPost($postId) {
        $postId = intval($postId);
        $sql = "SELECT 
            r.ReactionID,
            r.PostID,
            r.CommentID,
            r.UserID,
            r.ReactionType,
            r.CreatedAt,
            u.Username
        FROM reaction r
        JOIN users u ON r.UserID = u.UserID
        WHERE r.PostID = $postId
        ORDER BY r.CreatedAt DESC";
        $result = $this->query($sql);
        $reactions = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $reaction = new Reaction(
                $row['ReactionID'], $row['PostID'],
                $row['CommentID'], $row['UserID'],
                $row['ReactionType'], $row['CreatedAt']
            );
            $reaction->setUsername($row['Username']);
            $reactions[] = $reaction;
        }
        return $reactions;
    }

    public function selectReactionsForComment($commentId) {
        $commentId = intval($commentId);
        $sql = "SELECT 
            r.ReactionID,
            r.PostID,
            r.CommentID,
            r.UserID,
            r.ReactionType,
            r.CreatedAt,
            u.Username
        FROM reaction r
        JOIN users u ON r.UserID = u.UserID
        WHERE r.CommentID = $commentId
        ORDER BY r.CreatedAt DESC";
        $result = $this->query($sql);
        $reactions = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $reaction = new Reaction(
                $row['ReactionID'], $row['PostID'],
                $row['CommentID'], $row['UserID'],
                $row['ReactionType'], $row['CreatedAt']
            );
            $reaction->setUsername($row['Username']);
            $reactions[] = $reaction;
        }
        return $reactions;
    }

}
?>