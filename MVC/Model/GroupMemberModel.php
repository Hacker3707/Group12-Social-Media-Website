<?php
include_once "MVC/Model/AppModel.php";
include_once "Entity/GroupMember.php";

class GroupMemberModel extends AppModel {

    // ================= THÊM THÀNH VIÊN =================
    public function addMember($userId, $groupId, $role = 'member') {
        $userId = (int)$userId;
        $groupId = (int)$groupId;
        $role = mysqli_real_escape_string($this->link, $role);

        $sql = "INSERT INTO group_member (UserID, GroupID, Role) 
                VALUES ($userId, $groupId, '$role')";
        
        return $this->execute($sql);
    }

    // ================= LẤY DANH SÁCH THÀNH VIÊN =================
    public function getMembers($groupId) {
        $groupId = (int)$groupId;
        // Join với bảng users để lấy tên thành viên hiển thị
        $sql = "SELECT gm.*, u.Username 
                FROM group_member gm
                JOIN users u ON gm.UserID = u.UserID
                WHERE gm.GroupID = $groupId";
        
        $result = $this->query($sql);
        $list = [];

        while ($row = mysqli_fetch_assoc($result)) {
            // Bạn có thể trả về mảng hoặc Object GroupMember tùy ý
            $list[] = $row; 
        }
        return $list;
    }

    // ================= CẬP NHẬT VAI TRÒ (Admin/Member) =================
    public function updateRole($userId, $groupId, $newRole) {
        $userId = (int)$userId;
        $groupId = (int)$groupId;
        $newRole = mysqli_real_escape_string($this->link, $newRole);

        $sql = "UPDATE group_member 
                SET Role = '$newRole' 
                WHERE UserID = $userId AND GroupID = $groupId";

        return $this->execute($sql);
    }

    // ================= XÓA THÀNH VIÊN (RỜI NHÓM) =================
    public function removeMember($userId, $groupId) {
        $userId = (int)$userId;
        $groupId = (int)$groupId;

        $sql = "DELETE FROM group_member 
                WHERE UserID = $userId AND GroupID = $groupId";

        return $this->execute($sql);
    }
}