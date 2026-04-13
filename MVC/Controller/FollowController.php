<?php
include_once __DIR__ . "/AppController.php";
include_once __DIR__ . "/../Model/FollowModel.php";

class FollowController extends AppController {

    private $followModel;

    public function __construct() {
        $this->followModel = new FollowModel();
    }

    private function jsonResponse($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    // follow user
    public function follow() {

    if (!isset($_SESSION['user_id'])) {
        $this->jsonResponse(["status" => "error", "message" => "not_logged_in"]);
    }

    $followerId = (int)$_SESSION['user_id'];
    $followingId = (int)($_POST['following_id'] ?? 0);

    if (!$followingId || $followerId === $followingId) {
        $this->jsonResponse(["status" => "error", "message" => "invalid_data"]);
    }

    if ($this->followModel->exists($followerId, $followingId)) {
        $this->jsonResponse(["status" => "already"]);
    }

    // 🔥 FOLLOW
    $this->followModel->followUser($followerId, $followingId);

    // ================= 🔔 NOTIFICATION =================
    include_once __DIR__ . "/../Model/NotificationModel.php";
    $notiModel = new NotificationModel();

    if ($followerId != $followingId) {
        $content = "<b>" . $_SESSION['username'] . "</b> vừa theo dõi bạn";

        $notiModel->insert(
            $followingId,
            $followerId,
            $content,
            "follow"
        );
    }
    // ==================================================

    $count = $this->followModel->countFollowers($followingId);

    $this->jsonResponse([
        "status" => "followed",
        "count" => $count
    ]);
}

    // unfollow
    public function unfollow() {

        if (!isset($_SESSION['user_id'])) {
            $this->jsonResponse(["status" => "error", "message" => "not_logged_in"]);
        }

        $followerId = (int)$_SESSION['user_id'];
        $followingId = (int)($_POST['following_id'] ?? 0);

        if (!$followingId) {
            $this->jsonResponse(["status" => "error", "message" => "invalid_data"]);
        }

       $this->followModel->unfollowUser($followerId, $followingId);

        $count = $this->followModel->countFollowers($followingId);

        $this->jsonResponse([
            "status" => "unfollowed",
            "count" => $count
        ]);
    }

    public function getFollowers() {

        $userId = (int)($_GET['user_id'] ?? 0);

        if (!$userId) {
            echo "fail";
            exit;
        }

        $followers = $this->followModel->getFollowers($userId);

        include __DIR__ . "/../View/followers.php";
    }

    public function getFollowing() {

        $userId = (int)($_GET['user_id'] ?? 0);

        if (!$userId) {
            echo "fail";
            exit;
        }

        $following = $this->followModel->getFollowing($userId);

        include __DIR__ . "/../View/following.php";
    }
    

    
}
?>