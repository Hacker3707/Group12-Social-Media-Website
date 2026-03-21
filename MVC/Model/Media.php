<?php
class Media{
    private $mediaId;
    private $userId;
    private $mediaType;
    private $filePath;
    private $createdAt;
    private $commentId;
    private $postId;

    public function getMediaId(){
        return $this->mediaId;
    }

    public function setMediaId($mediaId){
        $this->mediaId = $mediaId;
    }

    public function getUserId(){
        return $this->userId;
    }

    public function setUserId($userId){
        $this->userId = $userId;
    }

    public function getMediaType(){
        return $this->mediaType;
    }

    public function setMediaType($mediaType){
        $this->mediaType = $mediaType;
    }

    public function getFilePath(){
        return $this->filePath;
    }

    public function setFilePath($filePath){
        $this->filePath = $filePath;
    }

    public function getCreatedAt(){
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt){
        $this->createdAt = $createdAt;
    }

    public function getCommentId(){
        return $this->commentId;
    }

    public function setCommentId($commentId){
        $this->commentId = $commentId;
    }

    public function getPostId(){
        return $this->postId;
    }

    public function setPostId($postId){
        $this->postId = $postId;
    }

    public function __construct(
        $mediaId = "",
        $userId = "",
        $mediaType = "",
        $filePath = "",
        $createdAt = "",
        $commentId = "",
        $postId = ""
    ){
        $this->mediaId   = $mediaId;
        $this->userId    = $userId;
        $this->mediaType = $mediaType;
        $this->filePath  = $filePath;
        $this->createdAt = $createdAt ?: date("Y-m-d H:i:s");
        $this->commentId = $commentId;
        $this->postId    = $postId;
    }

    public function __toString(){
        return "Media(mediaId=$this->mediaId, userId=$this->userId, " .
               "mediaType=$this->mediaType, filePath=$this->filePath, " .
               "createdAt=$this->createdAt, commentId=$this->commentId, " .
               "postId=$this->postId)";
    }
}
?>
