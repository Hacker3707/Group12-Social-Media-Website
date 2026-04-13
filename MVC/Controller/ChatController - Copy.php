<?php
include_once __DIR__ . "/AppController.php";
include_once __DIR__ . "/../Model/ChatModel.php";
include_once __DIR__ . "/../Model/UserModel.php";

class ChatController extends AppController {

    private $chatModel;
    private $userModel;

    public function __construct() {
        $this->chatModel = new ChatModel();
        $this->userModel = new UserModel();
    }

    private function requireLogin() {
        if (!isset($_SESSION['user_id'])) {
            $this->json(['status' => 'error', 'message' => 'not_logged_in']);
        }
    }

    private function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    // ── Mở chat với 1 user ──────────────────────────────────────────
    // GET: ?controller=chat&action=open&user_id=X
    public function open() {
        $this->requireLogin();
        $myId    = (int)$_SESSION['user_id'];
        $otherId = (int)($_GET['user_id'] ?? 0);

        if (!$otherId || $otherId === $myId) {
            $this->json(['status' => 'error', 'message' => 'invalid_user']);
        }

        $convId   = $this->chatModel->getOrCreateConversation($myId, $otherId);
        $messages = $this->chatModel->getMessages($convId);
        $other    = $this->userModel->getById($otherId);
        $this->chatModel->markAsRead($convId, $myId);

        $this->json([
            'status'          => 'ok',
            'conversation_id' => $convId,
            'other_user'      => [
                'id'       => $otherId,
                'username' => $other['Username'] ?? 'User',
                'avatar'   => $other['Avatar']   ?? null,
            ],
            'messages' => $messages,
        ]);
    }

    // ── Gửi tin nhắn (text hoặc ảnh) ───────────────────────────────
    // POST: ?controller=chat&action=send
    public function send() {
        $this->requireLogin();
        $myId   = (int)$_SESSION['user_id'];
        $convId = (int)($_POST['conversation_id'] ?? 0);
        $content = trim($_POST['content'] ?? '');
        $imagePath = null;

        if (!$convId) {
            $this->json(['status' => 'error', 'message' => 'missing_conv']);
        }

        // Xử lý upload ảnh
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $mime = mime_content_type($_FILES['image']['tmp_name']);
            if (strpos($mime, 'image/') === 0) {
                $uploadDir = __DIR__ . "/../../../uploads/chat/";
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                $safeName  = time() . '_' . $myId . '_' . basename($_FILES['image']['name']);
                $destPath  = $uploadDir . $safeName;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $destPath)) {
                    $imagePath = 'uploads/chat/' . $safeName;
                }
            }
        }

        if ($content === '' && !$imagePath) {
            $this->json(['status' => 'error', 'message' => 'empty_message']);
        }

        $msgId = $this->chatModel->sendMessage($convId, $myId, $content, $imagePath);

        $this->json([
            'status'     => 'ok',
            'message_id' => $msgId,
            'sender_id'  => $myId,
            'username'   => $_SESSION['username'],
            'content'    => htmlspecialchars($content),
            'image_path' => $imagePath,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    // ── Polling ─────────────────────────────────────────────────────
    // GET: ?controller=chat&action=poll&conversation_id=X&last_id=Y
    public function poll() {
        $this->requireLogin();
        $myId   = (int)$_SESSION['user_id'];
        $convId = (int)($_GET['conversation_id'] ?? 0);
        $lastId = (int)($_GET['last_id'] ?? 0);

        if (!$convId) $this->json(['status' => 'error']);

        $messages = $this->chatModel->getNewMessages($convId, $lastId);
        $this->chatModel->markAsRead($convId, $myId);
        $this->json(['status' => 'ok', 'messages' => $messages]);
    }

    // ── React vào tin nhắn ──────────────────────────────────────────
    // POST: ?controller=chat&action=react
    public function react() {
        $this->requireLogin();
        $msgId = (int)($_POST['message_id'] ?? 0);
        $emoji = $_POST['emoji'] ?? '';
        $userId = (int)$_SESSION['user_id'];

        if (!$msgId || !$emoji) $this->json(['status' => 'error']);

        $this->chatModel->reactMessage($msgId, $userId, $emoji);
        $this->json(['status' => 'ok', 'message_id' => $msgId, 'emoji' => $emoji]);
    }

    // ── Danh sách hội thoại ─────────────────────────────────────────
    // GET: ?controller=chat&action=conversations
    public function conversations() {
        $this->requireLogin();
        $myId = (int)$_SESSION['user_id'];
        $list = $this->chatModel->getConversations($myId);
        $this->json(['status' => 'ok', 'conversations' => $list]);
    }

    // ── Đếm tin chưa đọc (badge) ────────────────────────────────────
    // GET: ?controller=chat&action=unread
    public function unread() {
        $this->requireLogin();
        $myId  = (int)$_SESSION['user_id'];
        $count = $this->chatModel->countUnread($myId);
        $this->json(['status' => 'ok', 'count' => $count]);
    }
}
?>