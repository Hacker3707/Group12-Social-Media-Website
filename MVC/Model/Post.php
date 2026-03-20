<?php
class Post {
    private $postId;
    private $userId;
    private $groupId;
    private $categoryId;
    private $title;
    private $content;
    private array $mediaList; // Array to hold media objects (images, videos, etc.)
    private $created_at;
    

    public function __construct($post_id, $user_id, $group_id, $category_id, $title, $content, $media_list = [], $created_at) {
        $this->postId = $post_id;
        $this->userId = $user_id;
        $this->groupId = $group_id;
        $this->categoryId = $category_id;
        $this->title = $title;
        $this->content = $content;
        $this->mediaList = $media_list;
        $this->created_at = $created_at;
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

    public function getTitle() {
        return $this->title;
    }

    public function getGroupId() {
        return $this->groupId;
    }
    
    public function getCategoryId() {
        return $this->categoryId;
    }

    public function getMediaList() {
        return $this->mediaList;
    }
}
?>
