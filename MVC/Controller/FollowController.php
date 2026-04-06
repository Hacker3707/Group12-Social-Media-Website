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

        // ✅ check login
        if (!isset($_SESSION['user_id'])) {
            $this->jsonResponse(["status" => "error", "message" => "not_logged_in"]);
        }

        $followerId = (int)$_SESSION['user_id'];
        $followingId = (int)($_POST['following_id'] ?? 0);

        // ✅ validate
        if (!$followingId || $followerId === $followingId) {
            $this->jsonResponse(["status" => "error", "message" => "invalid_data"]);
        }

        if ($this->followModel->exists($followerId, $followingId)) {
            $this->jsonResponse(["status" => "already"]);
        }

        $this->followModel->insert($followerId, $followingId);

      $list = $this->followModel->getFollowers($followingId) ?? [];
    $count = count($list);

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

        $this->followModel->delete($followerId, $followingId);

        $count = count($this->followModel->getFollowers($followingId));

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