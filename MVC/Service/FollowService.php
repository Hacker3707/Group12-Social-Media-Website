<?php
include_once "MVC/Model/Follow.php";
include_once "MVC/Module/db_module.php";

class FollowService {

    // Follow user
    public function followUser($followerId, $followingId) {

        if ($followerId == $followingId) {
            return "Cannot follow yourself";
        }

        $link = null;
        taoKetNoi($link);

        // Check đã follow chưa
        $checkQuery = "SELECT * FROM follow 
                       WHERE FollowerID = $followerId 
                       AND FollowingID = $followingId";

        $checkResult = chayTruyVanTraVeDL($link, $checkQuery);

        if (mysqli_num_rows($checkResult) > 0) {
            giaiPhongKetNoi($link);
            return "Already followed";
        }

        // Transaction
        mysqli_begin_transaction($link);

        try {

            // Insert follow
            $query = "INSERT INTO follow (FollowerID, FollowingID)
                      VALUES ($followerId, $followingId)";

            $result = chayTruyVanKhongTraVeDL($link, $query);

            if (!$result) {
                throw new Exception("Insert follow failed");
            }

            // Insert notification
            $content = "started following you";

            $notiQuery = "INSERT INTO notification (UserID, SenderID, Content, NotiType)
                          VALUES ($followingId, $followerId, '$content', 'follow')";

            $notiResult = chayTruyVanKhongTraVeDL($link, $notiQuery);

            if (!$notiResult) {
                throw new Exception("Insert notification failed");
            }

            mysqli_commit($link);
            giaiPhongKetNoi($link);

            return "Followed successfully";

        } catch (Exception $e) {

            mysqli_rollback($link);
            giaiPhongKetNoi($link);

            return "Error";
        }
    }


    // Unfollow
    public function unfollowUser($followerId, $followingId) {

        $link = null;
        taoKetNoi($link);

        $query = "DELETE FROM follow 
                  WHERE FollowerID = $followerId 
                  AND FollowingID = $followingId";

        $result = chayTruyVanKhongTraVeDL($link, $query);

        giaiPhongKetNoi($link);

        return $result ? "Unfollowed successfully" : "Error";
    }


    // Get followers (JOIN users)
    public function getFollowers($userId) {

        $link = null;
        taoKetNoi($link);

        $query = "SELECT u.UserID, u.Username, u.AvatarFP
                  FROM follow f
                  JOIN users u ON f.FollowerID = u.UserID
                  WHERE f.FollowingID = $userId";

        $result = chayTruyVanTraVeDL($link, $query);

        $list = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $list[] = $row;
        }

        giaiPhongKetNoi($link);

        return $list;
    }


    // Check follow
    public function isFollowing($followerId, $followingId) {

        $link = null;
        taoKetNoi($link);

        $query = "SELECT * FROM follow 
                  WHERE FollowerID = $followerId 
                  AND FollowingID = $followingId";

        $result = chayTruyVanTraVeDL($link, $query);

        $isFollow = mysqli_num_rows($result) > 0;

        giaiPhongKetNoi($link);

        return $isFollow;
    }
}
?>