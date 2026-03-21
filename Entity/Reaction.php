<?php
class Reaction {
    private $ReactionID;
    private $PostID;
    private $UserID;
    private $CommentID; // Optional, if the reaction is for a comment instead of a post
    private $Type; // e.g., 'like', 'love', 'haha', etc.
    private $CreatedAt;

    public function __construct($reaction_id, $post_id, $comment_id, $user_id, $type, $created_at) {
        $this->ReactionID = $reaction_id;
        $this->PostID = $post_id;
        $this->UserID = $user_id;
        $this->CommentID = $comment_id;
        $this->Type = $type;
        $this->CreatedAt = $created_at;
    }

    public function getReactionId() {
        return $this->ReactionID;
    }

    public function getPostId() {
        return $this->PostID;
    }

    public function getUserId() {
        return $this->UserID;
    }

    public function getType() {
        return $this->Type;
    }

    public function getCommentId() {
        return $this->CommentID;
    }

    public function getCreatedAt() {
        return $this->CreatedAt;
    }
}
?>