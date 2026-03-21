<?php
class Follow {
    private $FollowerID;
    private $FollowingID;

    public function __construct($FollowerID = null, $FollowingID = null){
        $this->FollowerID = $FollowerID;
        $this->FollowingID = $FollowingID;
    }

    // Getter & Setter
    public function getFollowerID(){
        return $this->FollowerID;
    }

    public function setFollowerID($FollowerID){
        $this->FollowerID = $FollowerID;
    }

    public function getFollowingID(){
        return $this->FollowingID;
    }

    public function setFollowingID($FollowingID){
        $this->FollowingID = $FollowingID;
    }

    public function __toString(){
        return "Follow(FollowerID={$this->FollowerID}, FollowingID={$this->FollowingID})";
    }
}
?>