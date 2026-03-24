<?php
class Post {
    private $PostID;
    private $UserID;
    private $GroupID;
    private $CategoryID;
    private $Title;
    private $Content;
    private array $MediaList; // Array to hold media objects (images, videos, etc.)
    private $CreatedAt;
    

    public function __construct($post_id, $user_id, $group_id, $category_id, $title, $content, $created_at, $media_list = []) {
        $this->PostID = $post_id;
        $this->UserID = $user_id;
        $this->GroupID = $group_id;
        $this->CategoryID = $category_id;
        $this->Title = $title;
        $this->Content = $content;
        $this->MediaList = $media_list;
        $this->CreatedAt = $created_at;
    }

    public function getPostId() {
        return $this->PostID;
    }

    public function getUserId() {
        return $this->UserID;
    }

    public function getContent() {
        return $this->Content;
    }

    public function getCreatedAt() {
        return $this->CreatedAt;
    }

    public function getTitle() {
        return $this->Title;
    }

    public function getGroupId() {
        return $this->GroupID;
    }
    
    public function getCategoryId() {
        return $this->CategoryID;
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
}

?>
