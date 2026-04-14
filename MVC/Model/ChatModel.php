<?php
include_once __DIR__ . "/AppModel.php";

class ChatModel extends AppModel {

    private $lastError = '';

    public function getLastError() {
        return $this->lastError;
    }

    public function getOrCreateConversation($user1, $user2) {
        $a = min((int)$user1, (int)$user2);
        $b = max((int)$user1, (int)$user2);

        $sql = "SELECT ConversationID FROM conversations WHERE User1ID=$a AND User2ID=$b";
        $res = mysqli_query($this->link, $sql);

        if (!$res) {
            $this->lastError = mysqli_error($this->link);
            return false;
        }

        $row = mysqli_fetch_assoc($res);
        if ($row) {
            return (int)$row['ConversationID'];
        }

        $sql = "INSERT INTO conversations (User1ID, User2ID) VALUES ($a, $b)";
        $ok = mysqli_query($this->link, $sql);

        if (!$ok) {
            $this->lastError = mysqli_error($this->link);
            return false;
        }

        return (int)mysqli_insert_id($this->link);
    }

    public function sendMessage($convId, $senderId, $content, $imagePath = null) {
        $convId   = (int)$convId;
        $senderId = (int)$senderId;
        $content  = mysqli_real_escape_string($this->link, $content);

        $imgVal = $imagePath
            ? "'" . mysqli_real_escape_string($this->link, $imagePath) . "'"
            : "NULL";

        $sql = "INSERT INTO messages (ConversationID, SenderID, Content, ImagePath)
                VALUES ($convId, $senderId, '$content', $imgVal)";

        $ok = mysqli_query($this->link, $sql);

        if (!$ok) {
            $this->lastError = mysqli_error($this->link);
            return false;
        }

        return (int)mysqli_insert_id($this->link);
    }

    public function getMessages($convId, $limit = 200) {
        $convId = (int)$convId;
        $limit  = (int)$limit;

        $sql = "SELECT m.*, u.Username, u.AvatarFP AS Avatar
                FROM messages m
                JOIN users u ON m.SenderID = u.UserID
                WHERE m.ConversationID = $convId
                ORDER BY m.CreatedAt ASC
                LIMIT $limit";

        $res = mysqli_query($this->link, $sql);

        if (!$res) {
            $this->lastError = mysqli_error($this->link);
            return [];
        }

        $rows = [];
        while ($r = mysqli_fetch_assoc($res)) {
            $rows[] = $r;
        }
        return $rows;
    }

    public function getNewMessages($convId, $lastId) {
        $convId = (int)$convId;
        $lastId = (int)$lastId;

        $sql = "SELECT m.*, u.Username, u.AvatarFP AS Avatar
                FROM messages m
                JOIN users u ON m.SenderID = u.UserID
                WHERE m.ConversationID = $convId
                  AND m.MessageID > $lastId
                ORDER BY m.CreatedAt ASC";

        $res = mysqli_query($this->link, $sql);

        if (!$res) {
            $this->lastError = mysqli_error($this->link);
            return [];
        }

        $rows = [];
        while ($r = mysqli_fetch_assoc($res)) {
            $rows[] = $r;
        }
        return $rows;
    }

    public function markAsRead($convId, $userId) {
        $convId = (int)$convId;
        $userId = (int)$userId;

        $sql = "UPDATE messages
                SET IsRead = 1
                WHERE ConversationID = $convId
                  AND SenderID != $userId
                  AND IsRead = 0";

        mysqli_query($this->link, $sql);
    }

    public function reactMessage($msgId, $userId, $emoji) {
        $msgId = (int)$msgId;
        $emoji = mysqli_real_escape_string($this->link, $emoji);

        $sql = "UPDATE messages SET Reaction = '$emoji' WHERE MessageID = $msgId";
        mysqli_query($this->link, $sql);
    }

    public function getConversations($userId) {
        $userId = (int)$userId;

        $sql = "SELECT c.*,
                    IF(c.User1ID=$userId, c.User2ID, c.User1ID) AS OtherUserID,
                    u.Username AS OtherUsername,
                    u.AvatarFP AS OtherAvatar,
                    (SELECT Content FROM messages WHERE ConversationID=c.ConversationID ORDER BY CreatedAt DESC LIMIT 1) AS LastMessage,
                    (SELECT ImagePath FROM messages WHERE ConversationID=c.ConversationID ORDER BY CreatedAt DESC LIMIT 1) AS LastImage,
                    (SELECT CreatedAt FROM messages WHERE ConversationID=c.ConversationID ORDER BY CreatedAt DESC LIMIT 1) AS LastMessageAt,
                    (SELECT COUNT(*) FROM messages
                        WHERE ConversationID=c.ConversationID
                          AND SenderID != $userId
                          AND IsRead = 0) AS UnreadCount
                FROM conversations c
                JOIN users u ON u.UserID = IF(c.User1ID=$userId, c.User2ID, c.User1ID)
                WHERE c.User1ID=$userId OR c.User2ID=$userId
                ORDER BY LastMessageAt DESC";

        $res = mysqli_query($this->link, $sql);

        if (!$res) {
            $this->lastError = mysqli_error($this->link);
            return [];
        }

        $rows = [];
        while ($r = mysqli_fetch_assoc($res)) {
            $rows[] = $r;
        }
        return $rows;
    }

    public function countUnread($userId) {
        $userId = (int)$userId;

        $sql = "SELECT COUNT(*) AS cnt
                FROM messages m
                JOIN conversations c ON m.ConversationID = c.ConversationID
                WHERE (c.User1ID=$userId OR c.User2ID=$userId)
                  AND m.SenderID != $userId
                  AND m.IsRead = 0";

        $res = mysqli_query($this->link, $sql);

        if (!$res) {
            $this->lastError = mysqli_error($this->link);
            return 0;
        }

        $row = mysqli_fetch_assoc($res);
        return (int)($row['cnt'] ?? 0);
    }

    public function getFriendList($userId) {
        $userId = (int)$userId;

        $sql = "SELECT UserID, Username, Email, AvatarFP, Bio
                FROM users
                WHERE AccountStatus = 'active'
                  AND UserID != $userId
                ORDER BY Username ASC";

        $res = mysqli_query($this->link, $sql);

        if (!$res) {
            $this->lastError = mysqli_error($this->link);
            return [];
        }

        $rows = [];
        while ($r = mysqli_fetch_assoc($res)) {
            $rows[] = $r;
        }
        return $rows;
    }
}
?>