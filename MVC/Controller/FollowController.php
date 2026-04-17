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

    private function isAjaxRequest() {
        return strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest';
    }

    private function redirectBackWithMessage($message) {
        $_SESSION['flash_message'] = $message;
        $referer = $_SERVER['HTTP_REFERER'] ?? 'index.php';
        header('Location: ' . $referer);
        exit;
    }

    // follow user
    public function follow() {

    if (!isset($_SESSION['user_id'])) {
        if ($this->isAjaxRequest()) {
            $this->jsonResponse(["status" => "error", "message" => "not_logged_in"]);
        }

        $this->redirectBackWithMessage('Vui lòng đăng nhập để theo dõi người dùng.');
    }

    $followerId = (int)$_SESSION['user_id'];
    $followingId = (int)($_POST['following_id'] ?? ($_GET['id'] ?? 0));

    if (!$followingId || $followerId === $followingId) {
        if ($this->isAjaxRequest()) {
            $this->jsonResponse(["status" => "error", "message" => "invalid_data"]);
        }

        $this->redirectBackWithMessage('Bạn không thể tự theo dõi chính mình.');
    }

    if ($this->followModel->exists($followerId, $followingId)) {
        if ($this->isAjaxRequest()) {
            $this->jsonResponse(["status" => "already"]);
        }

        $this->redirectBackWithMessage('Bạn đã theo dõi tài khoản này rồi.');
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

    if ($this->isAjaxRequest()) {
        $this->jsonResponse([
            "status" => "followed",
            "count" => $count
        ]);
    }

    $this->redirectBackWithMessage('Đã theo dõi thành công.');
}

    // unfollow
    public function unfollow() {

        if (!isset($_SESSION['user_id'])) {
            if ($this->isAjaxRequest()) {
                $this->jsonResponse(["status" => "error", "message" => "not_logged_in"]);
            }

            $this->redirectBackWithMessage('Vui lòng đăng nhập để bỏ theo dõi.');
        }

        $followerId = (int)$_SESSION['user_id'];
        $followingId = (int)($_POST['following_id'] ?? ($_GET['id'] ?? 0));

        if (!$followingId) {
            if ($this->isAjaxRequest()) {
                $this->jsonResponse(["status" => "error", "message" => "invalid_data"]);
            }

            $this->redirectBackWithMessage('Không thể bỏ theo dõi tài khoản này.');
        }

       $this->followModel->unfollowUser($followerId, $followingId);

        $count = $this->followModel->countFollowers($followingId);

        if ($this->isAjaxRequest()) {
            $this->jsonResponse([
                "status" => "unfollowed",
                "count" => $count
            ]);
        }

        $this->redirectBackWithMessage('Đã bỏ theo dõi.');
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

    public function getFollowersJson() {
        $userId = (int)($_GET['user_id'] ?? 0);

        if (!$userId) {
            $this->jsonResponse([
                "status" => "error",
                "message" => "invalid_user"
            ]);
        }

        $followers = $this->followModel->getFollowerUsers($userId);

        $this->jsonResponse([
            "status" => "success",
            "followers" => $followers
        ]);
    }
    

    
}
?>