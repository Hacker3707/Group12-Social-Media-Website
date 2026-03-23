<?php
include_once "MVC/Model/FollowModel.php";
include_once "Entity/Follow.php";

class FollowService {

    private $followModel;

    public function __construct() {
        $this->followModel = new FollowModel();
    }

    // ================= FOLLOW =================
    public function follow($followerId, $followingId) {

        // Không cho follow chính mình
        if ($followerId == $followingId) {
            return false;
        }

        // Check đã follow chưa
        if ($this->followModel->exists($followerId, $followingId)) {
            return false;
        }

        return $this->followModel->insert($followerId, $followingId);
    }

    // ================= UNFOLLOW =================
    public function unfollow($followerId, $followingId) {

        return $this->followModel->delete($followerId, $followingId);
    }

    // ================= CHECK FOLLOW =================
    public function isFollowing($followerId, $followingId) {

        return $this->followModel->exists($followerId, $followingId);
    }

    // ================= GET FOLLOWERS =================
    public function getFollowers($userId) {

        $list = $this->followModel->getFollowers($userId);

        return $list ? $list : [];
    }

    // ================= GET FOLLOWING =================
    public function getFollowing($userId) {

        $list = $this->followModel->getFollowing($userId);

        return $list ? $list : [];
    }
}
?>