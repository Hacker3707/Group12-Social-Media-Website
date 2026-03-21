<?php
class CommentService
{
    public function addComment($userId, $postId, $content, $parentCommentId = null, $mediaList = [])
    {
        $link = null;
        taoKetNoi($link);
        $query = "INSERT INTO comment (UserID, PostID, Content, ParentCommentID) VALUES ($userId, $postId, '$content', $parentCommentId)";
        $result = chayTruyVanKhongTraVeDL($link, $query);
        giaiPhongKetNoi($link);
        return $result;
    }

    public function removeReaction($userId, $postId)
    {
        
    }

    public function getReactionsForPost($postId)
    {
        $link = null;
        taoKetNoi($link);
        $query = "SELECT * FROM comment WHERE PostID = $postId";
        $result = chayTruyVanTraVeDL($link, $query);
        $comments = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $comments[] = new Comment(
                $row['CommentID'], $row['PostID'], 
                $row['UserID'], $row['Content'], 
                $row['CreatedAt']
            );
        }
        giaiPhongKetNoi($link);
        return $comments;
    }
}
?>