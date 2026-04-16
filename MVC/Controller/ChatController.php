<?php
include_once __DIR__ . "/AppController.php";
include_once __DIR__ . "/../Model/ChatModel.php";
include_once __DIR__ . "/../Model/UserModel.php";
include_once __DIR__ . "/../../Entity/Chat.php";

class ChatController extends AppController {

    private $chatModel;
    private $userModel;

    public function __construct() {
        $this->chatModel = new ChatModel();
        $this->userModel = new UserModel();
    }

    private function requireLogin() {
        if (!isset($_SESSION['user_id'])) {
            $this->json([
                'status' => 'error',
                'message' => 'not_logged_in'
            ]);
        }
    }

    private function json($data) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    private function serializeMessageList(array $messages): array {
        $payload = [];

        foreach ($messages as $m) {
            if ($m instanceof Chat) {
                $payload[] = [
                    'MessageID' => $m->getMessageId(),
                    'ConversationID' => $m->getConversationId(),
                    'SenderID' => $m->getSenderId(),
                    'Username' => $m->getUsername(),
                    'AvatarFP' => $m->getAvatarFp(),
                    'Content' => $m->getContent(),
                    'ImagePath' => $m->getImagePath(),
                    'Reaction' => $m->getReaction(),
                    'IsRead' => $m->getIsRead(),
                    'CreatedAt' => $m->getCreatedAt(),
                ];
            } else {
                $payload[] = $m;
            }
        }

        return $payload;
    }

    private function serializeConversationList(array $conversations): array {
        $payload = [];

        foreach ($conversations as $c) {
            if ($c instanceof Chat) {
                $payload[] = [
                    'ConversationID' => $c->getConversationId(),
                    'User1ID' => $c->getUser1Id(),
                    'User2ID' => $c->getUser2Id(),
                    'OtherUserID' => $c->getOtherUserId(),
                    'OtherUsername' => $c->getOtherUsername(),
                    'OtherAvatar' => $c->getOtherAvatar(),
                    'LastMessage' => $c->getLastMessage(),
                    'LastImage' => $c->getLastImage(),
                    'LastMessageAt' => $c->getLastMessageAt(),
                    'UnreadCount' => $c->getUnreadCount(),
                ];
            } else {
                $payload[] = $c;
            }
        }

        return $payload;
    }

    public function inbox() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?controller=user&action=login");
            exit;
        }

        $isSystemAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
        $isPageAdmin = false;

        $myId = (int)$_SESSION['user_id'];

        
        $friends = $this->chatModel->getFriendList($myId);

        include_once __DIR__ . "/../View/chat_inbox.php";
    }

    public function open() {
        $this->requireLogin();

        $myId    = (int)$_SESSION['user_id'];
        $otherId = (int)($_GET['user_id'] ?? 0);

        if (!$otherId || $otherId === $myId) {
            $this->json([
                'status' => 'error',
                'message' => 'invalid_user'
            ]);
        }

        $convId = $this->chatModel->getOrCreateConversation($myId, $otherId);

        if ($convId === false) {
            $this->json([
                'status' => 'error',
                'message' => 'conversation_error',
                'debug' => $this->chatModel->getLastError()
            ]);
        }

        $messages = $this->chatModel->getMessages($convId);
        $other    = $this->userModel->getById($otherId);

        $this->chatModel->markAsRead($convId, $myId);

        $this->json([
            'status'          => 'ok',
            'conversation_id' => $convId,
            'other_user'      => [
                'id'       => $otherId,
                'username' => $other['Username'] ?? 'User',
                'avatar'   => $other['AvatarFP'] ?? null,
            ],
            'messages' => $this->serializeMessageList($messages),
        ]);
    }

    public function send() {
        $this->requireLogin();

        $myId    = (int)$_SESSION['user_id'];
        $convId  = (int)($_POST['conversation_id'] ?? 0);
        $content = trim($_POST['content'] ?? '');
        $imagePath = null;

        if (!$convId) {
            $this->json([
                'status' => 'error',
                'message' => 'missing_conv'
            ]);
        }

        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $mime = mime_content_type($_FILES['image']['tmp_name']);

            if (strpos($mime, 'image/') === 0) {
                $uploadDir = __DIR__ . "/../../../uploads/chat/";
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $safeName = time() . '_' . $myId . '_' . basename($_FILES['image']['name']);
                $destPath = $uploadDir . $safeName;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $destPath)) {
                    $imagePath = 'uploads/chat/' . $safeName;
                }
            }
        }

        if ($content === '' && !$imagePath) {
            $this->json([
                'status' => 'error',
                'message' => 'empty_message'
            ]);
        }

        $msgId = $this->chatModel->sendMessage($convId, $myId, $content, $imagePath);

        if ($msgId === false) {
            $this->json([
                'status' => 'error',
                'message' => 'db_error',
                'debug' => $this->chatModel->getLastError()
            ]);
        }

        $this->json([
            'status'     => 'ok',
            'message_id' => $msgId,
            'sender_id'  => $myId,
            'username'   => $_SESSION['username'] ?? 'Bạn',
            'content'    => $content,
            'image_path' => $imagePath,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function poll() {
        $this->requireLogin();

        $myId   = (int)$_SESSION['user_id'];
        $convId = (int)($_GET['conversation_id'] ?? 0);
        $lastId = (int)($_GET['last_id'] ?? 0);

        if (!$convId) {
            $this->json([
                'status' => 'error'
            ]);
        }

        $messages = $this->chatModel->getNewMessages($convId, $lastId);
        $this->chatModel->markAsRead($convId, $myId);

        $this->json([
            'status'   => 'ok',
            'messages' => $this->serializeMessageList($messages)
        ]);
    }

    public function react() {
        $this->requireLogin();

        $msgId  = (int)($_POST['message_id'] ?? 0);
        $emoji  = $_POST['emoji'] ?? '';
        $userId = (int)$_SESSION['user_id'];

        if (!$msgId || !$emoji) {
            $this->json([
                'status' => 'error'
            ]);
        }

        $this->chatModel->reactMessage($msgId, $userId, $emoji);

        $this->json([
            'status'     => 'ok',
            'message_id' => $msgId,
            'emoji'      => $emoji
        ]);
    }

    public function conversations() {
        $this->requireLogin();

        $myId = (int)$_SESSION['user_id'];
        $list = $this->chatModel->getConversations($myId);

        $this->json([
            'status' => 'ok',
            'conversations' => $this->serializeConversationList($list)
        ]);
    }

    public function unread() {
        $this->requireLogin();

        $myId  = (int)$_SESSION['user_id'];
        $count = $this->chatModel->countUnread($myId);

        $this->json([
            'status' => 'ok',
            'count'  => $count
        ]);
    }
}
?>