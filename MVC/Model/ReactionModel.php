<?php 
include_once "MVC/Model/AppModel.php";
include_once "Entity/Reaction.php";

class ReactionModel extends AppModel {

    public function insertReaction($userId, $postId, $commentId, $type) {
        $postId = $postId === null ? "NULL" : (int)$postId;
        $commentId = $commentId === null ? "NULL" : (int)$commentId;
        $sql = "CALL createReaction($userId, $postId, $commentId, '$type')";
        
        if ($this->execute($sql)) {
            return $this->getLastInsertId();
        }
        return false;
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
        $sql = "SELECT * FROM reaction WHERE $field = $value ORDER BY CreatedAt DESC";
        $result = $this->query($sql);
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($data, new Reaction(
                $row['ReactionID'], $row['PostID'],
                $row['CommentID'], $row['UserID'],
                $row['Type'], $row['CreatedAt']
            ));
        }
        return $data;
    }   

    public function deleteReaction($reactionId) {
    $sql = "DELETE FROM reaction WHERE ReactionID = $reactionId";
    return $this->execute($sql);
    }

    public function selectReactionsForPost($postId) {
        $postId = intval($postId);
        $sql = "SELECT * FROM reaction WHERE PostID = $postId ORDER BY CreatedAt DESC";
        $result = $this->query($sql);
        $reactions = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $reactions[] = new Reaction(
                $row['ReactionID'], $row['PostID'],
                $row['CommentID'], $row['UserID'],
                $row['Type'], $row['CreatedAt']
            );
        }
        return $reactions;
    }

    public function selectReactionsForComment($commentId) {
        $commentId = intval($commentId);
        $sql = "SELECT * FROM reaction WHERE CommentID = $commentId ORDER BY CreatedAt DESC";
        $result = $this->query($sql);
        $reactions = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $reactions[] = new Reaction(
                $row['ReactionID'], $row['PostID'],
                $row['CommentID'], $row['UserID'],
                $row['Type'], $row['CreatedAt']
            );
        }
        return $reactions;
    }

}
?>