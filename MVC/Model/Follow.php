<?php
class Follow{
    private $followerId;
    private $followingId;
    private $followDate;

    public function getFollowerId(){
        return $this->followerId;
    }

    public function setFollowerId($followerId){
        $this->followerId = $followerId;
    }

    public function getFollowingId(){
        return $this->followingId;
    }

    public function setFollowingId($followingId){
        $this->followingId = $followingId;
    }

    public function getFollowDate(){
        return $this->followDate;
    }

    public function setFollowDate($followDate){
        $this->followDate = $followDate;
    }

    public function __construct(){
        $this->followerId = "";
        $this->followingId = "";
        $this->followDate = date("Y-m-d H:i:s");
    }

    public function __toString(){
        return "Follow(followerId=$this->followerId, followingId=$this->followingId, followDate=$this->followDate)";
    }
}
?>