<?php
class ReactionService
{
    
    public function addReactiontoPost($userId, $postId, $reactionType)
    {
        $link = null;
        taoKetNoi($link);
        $query = "CALL AddReaction($userId, $postId, NULL, '$reactionType')";
        $result = chayTruyVanTraVeDL($link, $query);
        giaiPhongKetNoi($link);
        return $result;
    }

    public function addReactiontoComment($userId, $commentId, $reactionType)
    {
        $link = null;
        taoKetNoi($link);
        $query = "CALL AddReaction($userId, NULL, $commentId, '$reactionType')";
        $result = chayTruyVanTraVeDL($link, $query);
        giaiPhongKetNoi($link);
        return $result;
    }

    public function removeReactionfromPost($userId, $postId)
    {
        $link = null;
        taoKetNoi($link);
        $query = "DELETE FROM reaction WHERE PostID = $postId AND UserID = $userId";
        $result = chayTruyVanKhongTraVeDL($link, $query);
        giaiPhongKetNoi($link);
        return $result;
    }

    public function removeReactionfromComment($userId, $commentId)
    {
        $link = null;
        taoKetNoi($link);
        $query = "DELETE FROM reaction WHERE CommentID = $commentId AND UserID = $userId";
        $result = chayTruyVanKhongTraVeDL($link, $query);
        giaiPhongKetNoi($link);
        return $result;
    }
    
    public function getReactionsForPost($postId)
    {
        $link = null;
        taoKetNoi($link);
        $query = "SELECT * FROM reaction WHERE PostID = $postId";
        $result = chayTruyVanTraVeDL($link, $query);
        $reactions = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $reactions[] = new Reaction(
                $row['ReactionID'], $row['PostID'], $row['CommentID'],
                $row['UserID'], $row['Type'], $row['CreatedAt']
            );
        }
        giaiPhongKetNoi($link);
        return $reactions;
    }

    public function getReactionsForComment($commentId)
    {
        $link = null;
        taoKetNoi($link);
        $query = "SELECT * FROM reaction WHERE CommentID = $commentId";
        $result = chayTruyVanTraVeDL($link, $query);
        $reactions = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $reactions[] = new Reaction(
                $row['ReactionID'], $row['PostID'], $row['CommentID'],
                $row['UserID'], $row['Type'], $row['CreatedAt']
            );
        }
        giaiPhongKetNoi($link);
        return $reactions;
    }
}   
?>