<?php
class Reaction {
    private $reaction_id;
    private $post_id;
    private $user_id;
    private $comment_id; // Optional, if the reaction is for a comment instead of a post
    private $type; // e.g., 'like', 'love', 'haha', etc.
    private $created_at;

    public function __construct($reaction_id, $post_id, $comment_id, $user_id, $type, $created_at) {
        $this->reaction_id = $reaction_id;
        $this->post_id = $post_id;
        $this->comment_id = $comment_id;
        $this->user_id = $user_id;
        $this->type = $type;
        $this->created_at = $created_at;
    }

    public function getReactionId() {
        return $this->reaction_id;
    }

    public function getPostId() {
        return $this->post_id;
    }

    public function getUserId() {
        return $this->user_id;
    }

    public function getType() {
        return $this->type;
    }

    public function getCommentId() {
        return $this->comment_id;
    }

    public function getCreatedAt() {
        return $this->created_at;
    }
}
?>