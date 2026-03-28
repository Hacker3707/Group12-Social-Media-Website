<?php 
include_once __DIR__ . "/AppModel.php";
include_once __DIR__ . "/../../Entity/Reaction.php";

class ReactionModel extends AppModel {

    public function insertReaction($userId, $postId, $commentId, $type) {
        $postId = $postId === null ? "NULL" : (int)$postId;
        $commentId = $commentId === null ? "NULL" : (int)$commentId;
        $sql = "CALL createReaction($postId, $userId, $commentId, '$type')";
        $result = mysqli_query($this->link, $sql);

        if(!$result){
            echo mysqli_error($this->link);
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
                $row['ReactionType'], $row['CreatedAt']
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