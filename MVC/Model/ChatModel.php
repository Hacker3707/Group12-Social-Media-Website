<?php
include_once __DIR__ . "/AppModel.php";

class ChatModel extends AppModel {

    // Lấy hoặc tạo conversation giữa 2 user
    public function getOrCreateConversation($user1, $user2) {
        $a = min((int)$user1, (int)$user2);
        $b = max((int)$user1, (int)$user2);

        $res = $this->query("SELECT ConversationID FROM Conversations WHERE User1ID=$a AND User2ID=$b");
        $row = mysqli_fetch_assoc($res);
        if ($row) return (int)$row['ConversationID'];

        $this->execute("INSERT INTO Conversations (User1ID, User2ID) VALUES ($a, $b)");
        return (int)$this->getLastInsertId();
    }

    // Gửi tin nhắn
    public function sendMessage($convId, $senderId, $content, $imagePath = null) {
        $convId   = (int)$convId;
        $senderId = (int)$senderId;
        $content  = mysqli_real_escape_string($this->link, $content);
        $imgVal   = $imagePath
            ? "'" . mysqli_real_escape_string($this->link, $imagePath) . "'"
            : "NULL";
        $this->execute(
            "INSERT INTO Messages (ConversationID, SenderID, Content, ImagePath)
             VALUES ($convId, $senderId, '$content', $imgVal)"
        );
        return (int)$this->getLastInsertId();
    }

    // React vào tin nhắn
    public function reactMessage($msgId, $userId, $emoji) {
        $msgId = (int)$msgId;
        $emoji = mysqli_real_escape_string($this->link, $emoji);
        $this->execute("UPDATE Messages SET Reaction='$emoji' WHERE MessageID=$msgId");
    }

    // Lấy toàn bộ tin nhắn trong conversation
    public function getMessages($convId, $limit = 60) {
        $convId = (int)$convId;
        $limit  = (int)$limit;
        $sql = "SELECT m.*, u.Username, u.AvatarFP AS AvatarFP
                FROM Messages m
                JOIN Users u ON m.SenderID = u.UserID
                WHERE m.ConversationID = $convId
                ORDER BY m.CreatedAt ASC
                LIMIT $limit";
        $res  = $this->query($sql);
        $rows = [];
        while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
        return $rows;
    }

    // Polling — chỉ lấy tin mới hơn lastId
    public function getNewMessages($convId, $lastId) {
        $convId = (int)$convId;
        $lastId = (int)$lastId;
        $sql = "SELECT m.*, u.Username, u.AvatarFP AS AvatarFP
                FROM Messages m
                JOIN Users u ON m.SenderID = u.UserID
                WHERE m.ConversationID = $convId AND m.MessageID > $lastId
                ORDER BY m.CreatedAt ASC";
        $res  = $this->query($sql);
        $rows = [];
        while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
        return $rows;
    }

    // Đánh dấu đã đọc
    public function markAsRead($convId, $userId) {
        $convId = (int)$convId;
        $userId = (int)$userId;
        $this->execute(
            "UPDATE Messages SET IsRead=1
             WHERE ConversationID=$convId AND SenderID!=$userId AND IsRead=0"
        );
    }

    // Danh sách hội thoại của 1 user
    public function getConversations($userId) {
        $userId = (int)$userId;
        $sql = "SELECT c.*,
                    IF(c.User1ID=$userId, c.User2ID, c.User1ID) AS OtherUserID,
                    u.Username AS OtherUsername,
                    u.AvatarFP AS OtherAvatar,
                    (SELECT Content   FROM Messages WHERE ConversationID=c.ConversationID ORDER BY CreatedAt DESC LIMIT 1) AS LastMessage,
                    (SELECT ImagePath FROM Messages WHERE ConversationID=c.ConversationID ORDER BY CreatedAt DESC LIMIT 1) AS LastImage,
                    (SELECT CreatedAt FROM Messages WHERE ConversationID=c.ConversationID ORDER BY CreatedAt DESC LIMIT 1) AS LastMessageAt,
                    (SELECT COUNT(*)  FROM Messages WHERE ConversationID=c.ConversationID AND SenderID!=$userId AND IsRead=0) AS UnreadCount
                FROM Conversations c
                JOIN Users u ON u.UserID = IF(c.User1ID=$userId, c.User2ID, c.User1ID)
                WHERE c.User1ID=$userId OR c.User2ID=$userId
                ORDER BY LastMessageAt DESC";
        $res  = $this->query($sql);
        $rows = [];
        while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
        return $rows;
    }

    // Đếm tổng tin chưa đọc
    public function countUnread($userId) {
        $userId = (int)$userId;
        $sql = "SELECT COUNT(*) AS cnt
                FROM Messages m
                JOIN Conversations c ON m.ConversationID = c.ConversationID
                WHERE (c.User1ID=$userId OR c.User2ID=$userId)
                  AND m.SenderID!=$userId AND m.IsRead=0";
        $res = $this->query($sql);
        $row = mysqli_fetch_assoc($res);
        return (int)($row['cnt'] ?? 0);
    }
}
?>