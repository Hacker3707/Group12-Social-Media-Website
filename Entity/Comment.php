<?php 
class Comment {
    private $CommentID;
    private $replyToCommentId; // Optional, if this comment is a reply to another comment
    private $PostID;
    private $UserID;
    private $Username; // Optional, for easier access to the username of the commenter
    private $Content;
    private array $MediaList; // Array to hold media objects (images, videos, etc.)
    private $CreatedAt;
    private $Avatar;
    

    public function __construct($comment_id, $reply_to_comment_id, $post_id, $user_id, $content, $created_at, $media_list = []) {
        $this->CommentID = $comment_id;
        $this->replyToCommentId = $reply_to_comment_id;
        $this->PostID = $post_id;
        $this->UserID = $user_id;
        $this->Content = $content;
        $this->MediaList = $media_list;
        $this->CreatedAt = $created_at;
    }

    public function getCommentId() {
        return $this->CommentID;
    }

    public function getPostId() {
        return $this->PostID;
    }

    public function getUserId() {
        return $this->UserID;
    }

    public function getUsername() {
        return $this->Username;
    }

    public function getContent() {
        return $this->Content;
    }

    public function getCreatedAt() {
        return $this->CreatedAt;
    }

    public function getParentCommentId() {
        return $this->replyToCommentId;
    }

    public function getMediaList() {
        return $this->MediaList;
    }

    public function addToMediaList($media) {
    $this->MediaList[] = $media;
    }

    public function setMediaList($mediaList) {
        $this->MediaList = $mediaList;
    }

    public function setUsername($username) {
        $this->Username = $username;
    }

    public function getAvatar() {
        return $this->Avatar;
    }

    public function setAvatar($avatar) {
        $this->Avatar = $avatar;
    }
}
?>