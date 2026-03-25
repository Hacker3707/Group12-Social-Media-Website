<?php
include_once "MVC/Model/AppModel.php";
include_once "Entity/GroupMember.php";

class GroupMemberModel extends AppModel {

    public function getMembers($groupId) {
        $groupId = (int)$groupId;
        $sql = "SELECT * FROM group_member WHERE GroupID = $groupId";
        $result = $this->query($sql);
        $list = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $list[] = new GroupMember(
                $row['UserID'],
                $row['GroupID'],
                $row['Role']
            );
        }
        return $list;
    }

    public function addMember($userId, $groupId, $role = 'member') {
        $userId = (int)$userId;
        $groupId = (int)$groupId;
        $role = mysqli_real_escape_string($this->link, $role);

        $sql = "INSERT INTO group_member (UserID, GroupID, Role) 
                VALUES ($userId, $groupId, '$role')";
        
        return $this->execute($sql);
    }
}