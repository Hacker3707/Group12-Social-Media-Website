<?php
include_once "MVC/Model/AppModel.php";
include_once "Entity/Notification.php";

class NotificationModel extends AppModel {

    // ================= CREATE =================
    public function insert($userId, $senderId, $content, $notiType) {

        $content = mysqli_real_escape_string($this->link, $content);
        $notiType = mysqli_real_escape_string($this->link, $notiType);

        $sql = "INSERT INTO notification (UserID, SenderID, Content, NotiType)
                VALUES ($userId, $senderId, '$content', '$notiType')";

        return $this->execute($sql);
    }


    // ================= GET BY USER =================
    public function getByUserId($userId) {

        $userId = (int)$userId;

        $sql = "SELECT * FROM notification
                WHERE UserID = $userId
                ORDER BY CreatedAt DESC";

        $result = $this->query($sql);

        $list = [];

        while ($row = mysqli_fetch_assoc($result)) {

            $list[] = new Notification(
                $row['UserID'],
                $row['Content'],
                $row['NotiType'],
                $row['SenderID'],
                $row['IsRead'],
                $row['CreatedAt'],
                $row['NotificationID']
            );
        }

        return $list;
    }


    // ================= MARK AS READ =================
    public function markAsRead($notificationId) {

        $notificationId = (int)$notificationId;

        $sql = "UPDATE notification
                SET IsRead = 1
                WHERE NotificationID = $notificationId";

        return $this->execute($sql);
    }


    // ================= MARK ALL AS READ =================
    public function markAllAsRead($userId) {

        $userId = (int)$userId;

        $sql = "UPDATE notification
                SET IsRead = 1
                WHERE UserID = $userId";

        return $this->execute($sql);
    }


    // ================= DELETE =================
    public function delete($notificationId) {

        $notificationId = (int)$notificationId;

        $sql = "DELETE FROM notification
                WHERE NotificationID = $notificationId";

        return $this->execute($sql);
    }


    // ================= GET UNREAD COUNT =================
    public function countUnread($userId) {

        $userId = (int)$userId;

        $sql = "SELECT COUNT(*) as total
                FROM notification
                WHERE UserID = $userId AND IsRead = 0";

        $result = $this->query($sql);

        if ($row = mysqli_fetch_assoc($result)) {
            return (int)$row['total'];
        }

        return 0;
    }
}
?>