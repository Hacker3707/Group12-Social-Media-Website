<?php
include_once __DIR__ . "/AppController.php";
include_once __DIR__ . "/../Model/NotificationModel.php";

class NotificationController extends AppController {

    private $notificationModel;

    public function __construct() {
        $this->notificationModel = new NotificationModel();
    }

    // ================= HELPER JSON =================
    private function jsonResponse($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    // ================= CREATE =================
    public function create() {

        if (!isset($_SESSION['user_id'])) {
            $this->jsonResponse(["status" => "error", "message" => "not_logged_in"]);
        }

        $userId = (int)($_POST['user_id'] ?? 0);
        $senderId = (int)$_SESSION['user_id'];
        $content = $_POST['content'] ?? '';
        $type = $_POST['type'] ?? 'system';

        if (!$userId || !$content) {
            $this->jsonResponse(["status" => "error", "message" => "invalid_data"]);
        }

        $result = $this->notificationModel->insert($userId, $senderId, $content, $type);

        $this->jsonResponse([
            "status" => $result ? "success" : "fail"
        ]);
    }

    // ================= GET ALL =================
    public function getMyNotifications() {

        if (!isset($_SESSION['user_id'])) {
            echo "fail";
            exit;
        }

        $userId = $_SESSION['user_id'];

        $notifications = $this->notificationModel->getByUserId($userId);

        include __DIR__ . "/../View/notifications.php";
    }

    // ================= MARK AS READ =================
    public function markRead() {

        $id = (int)($_POST['notification_id'] ?? 0);

        if (!$id) {
            $this->jsonResponse(["status" => "error"]);
        }

        $result = $this->notificationModel->markAsRead($id);

        $this->jsonResponse([
            "status" => $result ? "success" : "fail"
        ]);
    }

    // ================= MARK ALL =================
    public function markAllRead() {

        if (!isset($_SESSION['user_id'])) {
            $this->jsonResponse(["status" => "error"]);
        }

        $userId = $_SESSION['user_id'];

        $result = $this->notificationModel->markAllAsRead($userId);

        $this->jsonResponse([
            "status" => $result ? "success" : "fail"
        ]);
    }

    // ================= DELETE =================
    public function delete() {

        $id = (int)($_POST['notification_id'] ?? 0);

        if (!$id) {
            $this->jsonResponse(["status" => "error"]);
        }

        $result = $this->notificationModel->delete($id);

        $this->jsonResponse([
            "status" => $result ? "success" : "fail"
        ]);
    }

    // ================= COUNT UNREAD =================
    public function countUnread() {

        if (!isset($_SESSION['user_id'])) {
            $this->jsonResponse(["count" => 0]);
        }

        $userId = $_SESSION['user_id'];

        $count = $this->notificationModel->countUnread($userId);

        $this->jsonResponse([
            "count" => $count
        ]);
    }
    public function get() {
        
    $this->getMyNotifications();

    }
     public function count() {
    $this->countUnread();
}
}
?>