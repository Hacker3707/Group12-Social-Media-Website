<?php
class Post {
    private $PostID;
    private $UserID;
    private $GroupID;
    private $CategoryID;
    private $Username;
    private $Title;
    private $Content;
    private array $MediaList; // Array to hold media objects (images, videos, etc.)
    private $CreatedAt;
    private $Price;
    private $ProductCondition;
    private $Location;
    private $Brand;
    private $PostStatus;
    private $CategoryName;
    private $Avatar;
    
    private $GroupName;

    public function __construct(
        $post_id, 
        $user_id, 
        $group_id,
        $category_id,
        $title, 
        $content,
        $price = null,
        $condition = 'good',
        $location = 'other',
        $brand = null,
        $status = 'selling')
     {
        $this->PostID = $post_id;
        $this->UserID = $user_id;
        $this->GroupID = $group_id;
        $this->CategoryID = $category_id;
        $this->Title = $title;
        $this->Content = $content;
        $this->Price = $price;
        $this->ProductCondition = $condition;
        $this->Location = $location;
        $this->Brand = $brand;
        $this->PostStatus = $status;


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

    public function setCreatedAt($createdAt){
    $this->CreatedAt = $createdAt;
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

    public function getUsername() {
        return $this->Username;
    }
    
    public function setUsername($username) {
        $this->Username = $username;
    }
      public function getPrice() {
        return $this->Price;
    }

    public function setPrice($price) {
        $this->Price = $price;
    }

    public function getCondition() {
        return $this->ProductCondition;
    }

    public function setCondition($condition) {
        $this->ProductCondition = $condition;
    }

    public function getLocation() {
        return $this->Location;
    }

    public function setLocation($location) {
        $this->Location = $location;
    }

    public function getBrand() {
        return $this->Brand;
    }

    public function setBrand($brand) {
        $this->Brand = $brand;
    }

    public function getStatus() {
        return $this->PostStatus;
    }

    public function setStatus($status) {
        $this->PostStatus = $status;
    }
      public function getCategoryName() {
    return $this->CategoryName;
     }

    public function setCategoryName($name) {
    $this->CategoryName = $name;
    }

    public function setGroupName($name) {
        $this->GroupName = $name;
    }

    public function getGroupName() {
        return $this->GroupName;
    }

    public function getAvatar() {
        return $this->Avatar;
    }

    public function setAvatar($avatar) {
        $this->Avatar = $avatar;
    }

}

?>