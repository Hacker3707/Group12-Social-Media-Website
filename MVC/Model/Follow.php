<?php
class Follow {
    private $followerId;
    private $followingId;

    public function __construct($followerId = null, $followingId = null){
        $this->followerId = $followerId;
        $this->followingId = $followingId;
    }

    // Getter & Setter
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

    public function __toString(){
        return "Follow(followerId={$this->followerId}, followingId={$this->followingId})";
    }
}
?>