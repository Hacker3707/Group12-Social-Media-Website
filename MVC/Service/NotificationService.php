<?php
include_once "MVC/Model/Notification.php";
include_once "MVC/Module/db_module.php";

class NotificationService {

    // ================= CREATE =================
    public function createNotification($userId, $senderId, $content, $notiType) {

        $link = null;
        taoKetNoi($link);

        $query = "INSERT INTO notification (UserID, SenderID, Content, NotiType)
                  VALUES ($userId, $senderId, '$content', '$notiType')";

        $result = chayTruyVanKhongTraVeDL($link, $query);

        giaiPhongKetNoi($link);

        return $result ? "Notification created" : "Create failed";
    }


    // ================= GET ALL BY USER =================
    public function getNotificationsByUser($userId) {

        $link = null;
        taoKetNoi($link);

        $query = "SELECT * FROM notification 
                  WHERE UserID = $userId
                  ORDER BY CreatedAt DESC";

        $result = chayTruyVanTraVeDL($link, $query);

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

        giaiPhongKetNoi($link);

        return $list;
    }


    // ================= MARK AS READ =================
    public function markAsRead($notificationId) {

        $link = null;
        taoKetNoi($link);

        $query = "UPDATE notification 
                  SET IsRead = 1
                  WHERE NotificationID = $notificationId";

        $result = chayTruyVanKhongTraVeDL($link, $query);

        giaiPhongKetNoi($link);

        return $result ? "Marked as read" : "Update failed";
    }


    // ================= MARK ALL AS READ =================
    public function markAllAsRead($userId) {

        $link = null;
        taoKetNoi($link);

        $query = "UPDATE notification 
                  SET IsRead = 1
                  WHERE UserID = $userId";

        $result = chayTruyVanKhongTraVeDL($link, $query);

        giaiPhongKetNoi($link);

        return $result ? "All marked as read" : "Update failed";
    }


    // ================= DELETE =================
    public function deleteNotification($notificationId) {

        $link = null;
        taoKetNoi($link);

        $query = "DELETE FROM notification 
                  WHERE NotificationID = $notificationId";

        $result = chayTruyVanKhongTraVeDL($link, $query);

        giaiPhongKetNoi($link);

        return $result ? "Deleted successfully" : "Delete failed";
    }
}
?>