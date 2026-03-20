<?php
class Reaction {
    private $reactionId;
    private $postId;
    private $userId;
    private $commentId; // Optional, if the reaction is for a comment instead of a post
    private $type; // e.g., 'like', 'love', 'haha', etc.
    private $created_at;

    public function __construct($reaction_id, $post_id, $comment_id, $user_id, $type, $created_at) {
        $this->reactionId = $reaction_id;
        $this->postId = $post_id;
        $this->commentId = $comment_id;
        $this->userId = $user_id;
        $this->type = $type;
        $this->created_at = $created_at;
    }

    public function getReactionId() {
        return $this->reactionId;
    }

    public function getPostId() {
        return $this->postId;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getType() {
        return $this->type;
    }

    public function getCommentId() {
        return $this->commentId;
    }

    public function getCreatedAt() {
        return $this->created_at;
    }
}
?>