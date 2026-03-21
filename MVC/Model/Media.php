<?php
class Media{
    private $MediaID;
    private $UserID;
    private $MediaType;
    private $FilePath;
    private $CreatedAt;
    private $CommentID;
    private $PostID;

    public function getMediaID(){
        return $this->MediaID;
    }

    public function setMediaID($MediaID){
        $this->MediaID = $MediaID;
    }

    public function getUserID(){
        return $this->UserID;
    }

    public function setUserID($UserID){
        $this->UserID = $UserID;
    }

    public function getMediaType(){
        return $this->MediaType;
    }

    public function setMediaType($MediaType){
        $this->MediaType = $MediaType;
    }

    public function getFilePath(){
        return $this->FilePath;
    }

    public function setFilePath($FilePath){
        $this->FilePath = $FilePath;
    }

    public function getCreatedAt(){
        return $this->CreatedAt;
    }

    public function setCreatedAt($CreatedAt){
        $this->CreatedAt = $CreatedAt;
    }

    public function getCommentID(){
        return $this->CommentID;
    }

    public function setCommentID($CommentID){
        $this->CommentID = $CommentID;
    }

    public function getPostID(){
        return $this->PostID;
    }

    public function setPostID($PostID){
        $this->PostID = $PostID;
    }

    public function __construct(
        $MediaID = "",
        $UserID = "",
        $MediaType = "",
        $FilePath = "",
        $CreatedAt = "",
        $CommentID = "",
        $PostID = ""
    ){
        $this->MediaID   = $MediaID;
        $this->UserID    = $UserID;
        $this->MediaType = $MediaType;
        $this->FilePath  = $FilePath;
        $this->CreatedAt = $CreatedAt ?: date("Y-m-d H:i:s");
        $this->CommentID = $CommentID;
        $this->PostID    = $PostID;
    }

    public function __toString(){
        return "Media(MediaID=$this->MediaID, UserID=$this->UserID, " .
               "MediaType=$this->MediaType, FilePath=$this->FilePath, " .
               "CreatedAt=$this->CreatedAt, CommentID=$this->CommentID, " .
               "PostID=$this->PostID)";
    }
}
?>