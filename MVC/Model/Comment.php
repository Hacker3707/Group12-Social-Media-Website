<?php 
class Comment {
    private $commentId;
    private $replyToCommentId; // Optional, if this comment is a reply to another comment
    private $postId;
    private $userId;
    private $content;
    private array $mediaList; // Array to hold media objects (images, videos, etc.)
    private $created_at;
    

    public function __construct($comment_id, $reply_to_comment_id, $post_id, $user_id, $content, $media_list = [], $created_at) {
        $this->commentId = $comment_id;
        $this->replyToCommentId = $reply_to_comment_id;
        $this->postId = $post_id;
        $this->userId = $user_id;
        $this->content = $content;
        $this->mediaList = $media_list;
        $this->created_at = $created_at;
    }

    public function getCommentId() {
        return $this->commentId;
    }

    public function getPostId() {
        return $this->postId;
    }

    public function getUserId() {
        return $this->userId;
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

    public function getMediaList() {
        return $this->mediaList;
    }
}

?>
