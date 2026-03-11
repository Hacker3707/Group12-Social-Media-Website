<?php 
class Comment {
    private $comment_id;
    private $reply_to_comment_id; // Optional, if this comment is a reply to another comment
    private $post_id;
    private $user_id;
    private $content;
    private array $media_list; // Array to hold media objects (images, videos, etc.)
    private $created_at;
    

    public function __construct($comment_id, $reply_to_comment_id, $post_id, $user_id, $content, $media_list = [], $created_at) {
        $this->comment_id = $comment_id;
        $this->reply_to_comment_id = $reply_to_comment_id;
        $this->post_id = $post_id;
        $this->user_id = $user_id;
        $this->content = $content;
        $this->media_list = $media_list;
        $this->created_at = $created_at;
    }

    public function getCommentId() {
        return $this->comment_id;
    }

    public function getPostId() {
        return $this->post_id;
    }

    public function getUserId() {
        return $this->user_id;
    }

    public function getContent() {
        return $this->content;
    }

    public function getCreatedAt() {
        return $this->created_at;
    }

    public function getReplyToCommentId() {
        return $this->reply_to_comment_id;
    }
}

?>
