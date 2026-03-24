<?php
include_once "MVC/Model/AppModel.php";
include_once "Entity/Follow.php";

class FollowModel extends AppModel {

    public function insert($followerId, $followingId) {

        $followerId = (int)$followerId;
        $followingId = (int)$followingId;

        $sql = "INSERT INTO follow (FollowerID, FollowingID)
                VALUES ($followerId, $followingId)";

        return $this->execute($sql);
    }

    public function delete($followerId, $followingId) {

        $followerId = (int)$followerId;
        $followingId = (int)$followingId;

        $sql = "DELETE FROM follow
                WHERE FollowerID = $followerId 
                AND FollowingID = $followingId";

        return $this->execute($sql);
    }

    public function exists($followerId, $followingId) {

        $followerId = (int)$followerId;
        $followingId = (int)$followingId;

        $sql = "SELECT * FROM follow
                WHERE FollowerID = $followerId 
                AND FollowingID = $followingId";

        $result = $this->query($sql);

        return mysqli_num_rows($result) > 0;
    }

    public function getFollowers($userId) {

        $userId = (int)$userId;

        $sql = "SELECT * FROM follow WHERE FollowingID = $userId";

        $result = $this->query($sql);

        $list = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $list[] = new Follow(
                $row['FollowerID'],
                $row['FollowingID']
            );
        }

        return $list;
    }

    public function getFollowing($userId) {

        $userId = (int)$userId;

        $sql = "SELECT * FROM follow WHERE FollowerID = $userId";

        $result = $this->query($sql);

        $list = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $list[] = new Follow(
                $row['FollowerID'],
                $row['FollowingID']
            );
        }

        return $list;
    }
}
?>