<?php
include_once "MVC/Model/AppModel.php";
include_once "Entity/Follow.php";

class FollowModel extends AppModel {

  public function followUser($followerId, $followingId) {

    $followerId = (int)$followerId;
    $followingId = (int)$followingId;

    $sql = "CALL FollowUser($followerId, $followingId)";
    
    $result = $this->execute($sql);

    mysqli_next_result($this->link); // 🔥 QUAN TRỌNG

    return $result;
}

  public function unfollowUser($followerId, $followingId) {

    $sql = "CALL UnfollowUser($followerId, $followingId)";
    
    $result = $this->execute($sql);

    mysqli_next_result($this->link);

    return $result;
}

    public function exists($followerId, $followingId) {

        $followerId = (int)$followerId;
        $followingId = (int)$followingId;

      $sql = "SELECT 1 FROM follow
        WHERE FollowerID = $followerId 
        AND FollowingID = $followingId
        LIMIT 1";
               

        $result = $this->query($sql);

        return mysqli_num_rows($result) > 0;
    }

   public function getFollowers($userId) {

    $sql = "CALL GetFollowers($userId)";
    $result = $this->query($sql);

    $list = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $list[] = new Follow(
            $row['UserID'],  //follower
            $userId         //following
        );
    }

    mysqli_next_result($this->link); // 🔥 BẮT BUỘC

    return $list;
}

    public function getFollowing($userId) {

    $sql = "CALL GetFollowing($userId)";
    $result = $this->query($sql);

    $list = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $list[] = new Follow(
            $userId,
            $row['UserID']
        );
    }

    mysqli_next_result($this->link);

    return $list;
}
public function countFollowers($userId) {

    $userId = (int)$userId;

    $sql = "CALL CountFollowers($userId)";
    $result = $this->query($sql);

    $row = mysqli_fetch_assoc($result);

    // 🔥 clear result để tránh lỗi CALL tiếp theo
    mysqli_next_result($this->link);

    return $row['total'] ?? 0;
}

public function getFollowerUsers($userId) {
    $userId = (int)$userId;

    $sql = "SELECT u.UserID, u.Username, u.AvatarFP
            FROM follow f
            JOIN users u ON u.UserID = f.FollowerID
            WHERE f.FollowingID = $userId
            ORDER BY f.FollowerID DESC";

    $result = $this->query($sql);
    $list = [];

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $list[] = $row;
        }
    }

    return $list;
}
}
?>