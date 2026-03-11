<?php
class Post {
    private $post_id;
    private $user_id;
    private $group_id;
    private $title;
    private $content;
    private array $media_list; // Array to hold media objects (images, videos, etc.)
    private $created_at;
    

    public function __construct($post_id, $user_id, $group_id, $title, $content, $media_list = [], $created_at) {
        $this->post_id = $post_id;
        $this->user_id = $user_id;
        $this->group_id = $group_id;
        $this->title = $title;
        $this->content = $content;
        $this->media_list = $media_list;
        $this->created_at = $created_at;
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

    public function getTitle() {
        return $this->title;
    }
}
?>