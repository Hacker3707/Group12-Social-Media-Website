<?php
include_once "MVC/Model/AppModel.php";
include_once "Entity/Group.php";

class GroupModel extends AppModel {

    // ================= CREATE =================
    public function insert(Group $group) {
        $name = mysqli_real_escape_string($this->link, $group->getGroupName());
        $desc = mysqli_real_escape_string($this->link, $group->getDescription());
        $privacy = $group->getPrivacy(); 
        $categoryId = (int)$group->getCategoryId(); // Nếu form chưa có Category, có thể tạm để 1 hoặc NULL

        $sql = "INSERT INTO groups (GroupName, Description, Privacy, CategoryID) 
                VALUES ('$name', '$desc', '$privacy', " . ($categoryId ?: "NULL") . ")";
        
        // Thay vì chỉ trả về true/false, ta trả về cái ID thật vừa tạo
        if ($this->execute($sql)) {
            return $this->getLastInsertId(); 
        }
        return false;
    }

    // ================= LIST =================
    public function getAll() {
        // Join với bảng category để lấy tên danh mục hiển thị
        $sql = "SELECT g.*, c.CategoryName 
                FROM groups g 
                LEFT JOIN category c ON g.CategoryID = c.CategoryID 
                ORDER BY g.GroupID DESC";
        $result = $this->query($sql);
        $list = [];

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $group = new Group(
                    $row['GroupID'],
                    $row['GroupName'],
                    $row['Privacy']
                );
                $group->setDescription($row['Description']);
                $group->setCategoryId($row['CategoryID']);
                $list[] = $group;
            }
        }
        return $list;
    }

    // ================= GET BY ID =================
    public function getById($id) {
        $id = (int)$id;
        $sql = "SELECT * FROM groups WHERE GroupID = $id";
        $result = $this->query($sql);

        if ($result && $row = mysqli_fetch_assoc($result)) {
            return $row; // Trả về mảng để Controller dễ đổ vào form Edit
        }
        return null;
    }

    // ================= GET OBJECT BY ID =================
    public function getObjById($id) {
        $id = (int)$id;
        $sql = "SELECT * FROM groups WHERE GroupID = $id";
        $result = $this->query($sql);

        $group = null;

        while ($row = mysqli_fetch_assoc($result)) {
            $group = new Group(
                    $row['GroupID'],
                    $row['GroupName'],
                    $row['Privacy']
                );
                $group->setDescription($row['Description']);
                $group->setCategoryId($row['CategoryID']);
            
            return $group;
        }

        return null;
    }

    // ================= UPDATE =================
    public function update($groupId, $name, $desc, $privacy, $categoryId) {
        $groupId = (int)$groupId;
        $name = mysqli_real_escape_string($this->link, $name);
        $desc = mysqli_real_escape_string($this->link, $desc);
        $privacy = mysqli_real_escape_string($this->link, $privacy);
        $categoryId = (int)$categoryId;

        $sql = "UPDATE groups 
                SET GroupName = '$name', 
                    Description = '$desc', 
                    Privacy = '$privacy', 
                    CategoryID = $categoryId
                WHERE GroupID = $groupId";

        return $this->execute($sql);
    }

    // ================= DELETE =================
    public function delete($groupId) {
        $groupId = (int)$groupId;
        $sql = "DELETE FROM groups WHERE GroupID = $groupId";
        return $this->execute($sql);
    }

    // ================= SỬA: ĐẾM THÀNH VIÊN (Chỉ đếm người đã được duyệt) =================
    public function getMemberCount($groupId) {
        $groupId = (int)$groupId;
        $sql = "SELECT COUNT(*) as total FROM group_member WHERE GroupID = $groupId AND Status = 'approved'";
        $result = $this->query($sql);
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            return $row['total'];
        }
        return 0;
    }

    // ================= THAY THẾ: KIỂM TRA TRẠNG THÁI THAM GIA =================
    public function getJoinStatus($userId, $groupId) {
        $userId = (int)$userId;
        $groupId = (int)$groupId;
        $sql = "SELECT Status FROM group_member WHERE UserID = $userId AND GroupID = $groupId";
        $result = $this->query($sql);
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            return $row['Status']; // Trả về 'pending' hoặc 'approved'
        }
        return false; // Chưa tham gia
    }

   // ================= SỬA: THÊM CỘT STATUS KHI JOIN GROUP =================
    public function joinGroup($userId, $groupId, $role = 'member', $status = 'approved') {
        $userId = (int)$userId;
        $groupId = (int)$groupId;
        $role = mysqli_real_escape_string($this->link, $role);
        $status = mysqli_real_escape_string($this->link, $status);
        
        $sql = "INSERT INTO group_member (UserID, GroupID, Role, Status) 
                VALUES ($userId, $groupId, '$role', '$status')";
        return $this->execute($sql);
    }

    // ================= THÊM MỚI: LẤY DANH SÁCH CHỜ DUYỆT =================
    public function getPendingMembers($groupId) {
        $groupId = (int)$groupId;
        $sql = "SELECT gm.*, u.Username, u.Email 
                FROM group_member gm
                JOIN users u ON gm.UserID = u.UserID
                WHERE gm.GroupID = $groupId AND gm.Status = 'pending'
                ORDER BY gm.UserID DESC";
        $result = $this->query($sql);
        $list = [];
        if ($result) { while ($row = mysqli_fetch_assoc($result)) { $list[] = $row; } }
        return $list;
    }

    // ================= THÊM MỚI: DUYỆT THÀNH VIÊN =================
    public function approveMember($userId, $groupId) {
        $userId = (int)$userId;
        $groupId = (int)$groupId;
        $sql = "UPDATE group_member SET Status = 'approved' WHERE UserID = $userId AND GroupID = $groupId";
        return $this->execute($sql);
    }

    // ================= THÊM MỚI: LẤY QUYỀN CỦA USER TRONG NHÓM =================
    public function getUserRole($userId, $groupId) {
        $userId = (int)$userId;
        $groupId = (int)$groupId;
        $sql = "SELECT Role FROM group_member WHERE UserID = $userId AND GroupID = $groupId";
        
        $result = $this->query($sql);
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            return $row['Role']; // Sẽ trả về 'admin' hoặc 'member'
        }
        return null; // Chưa tham gia
    }

    // ================= THÊM MỚI: TÌM KIẾM NHÓM =================
    public function searchGroups($keyword = "") {
        $keyword = mysqli_real_escape_string($this->link, $keyword);
        
        // Tìm kiếm theo tên nhóm hoặc mô tả
        $sql = "SELECT * FROM groups 
                WHERE GroupName LIKE '%$keyword%' OR Description LIKE '%$keyword%' 
                ORDER BY GroupID DESC";
                
        $result = $this->query($sql);
        $list = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $list[] = $row;
            }
        }
        return $list;
    }

    public function findGroups($keyword = "") {
        $keyword = mysqli_real_escape_string($this->link, $keyword);
        $sql = "SELECT * FROM groups 
                WHERE GroupName LIKE '%$keyword%' OR Description LIKE '%$keyword%' 
                ORDER BY GroupID DESC";
        $result = $this->query($sql);
        $list = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $group = new Group(
                    $row['GroupID'],
                    $row['GroupName'],
                    $row['Privacy']
                );
                $group->setDescription($row['Description']);
                $group->setCategoryId($row['CategoryID']);
                $list[] = $group;
            }
        }
        return $list;
    }

    // 4. THỰC HIỆN RỜI NHÓM
    public function leaveGroup($userId, $groupId) {
        $userId = (int)$userId;
        $groupId = (int)$groupId;
        
        $sql = "DELETE FROM group_member WHERE UserID = $userId AND GroupID = $groupId";
        
        return $this->execute($sql); // Dùng execute() trả về true/false
    }

    // ================= LẤY DANH SÁCH NHÓM CỦA MỘT USER =================
    public function getGroupsByUser($userId) {
        $userId = (int)$userId;
        // Kết hợp bảng groups và group_member để tìm nhóm mà user đang tham gia
        $sql = "SELECT g.* FROM groups g
                JOIN group_member gm ON g.GroupID = gm.GroupID
                WHERE gm.UserID = $userId
                ORDER BY g.GroupID DESC"; // Nhóm mới tham gia/tạo sẽ hiện lên đầu
                
        $result = $this->query($sql);
        $list = [];

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $list[] = $row; // Đẩy từng nhóm tìm được vào mảng
            }
        }
        return $list;
    }

    // ================= SỬA: LẤY DANH SÁCH THÀNH VIÊN ĐÃ DUYỆT =================
    public function getGroupMembers($groupId) {
        $groupId = (int)$groupId;
        $sql = "SELECT gm.*, u.Username, u.Email 
                FROM group_member gm
                JOIN users u ON gm.UserID = u.UserID
                WHERE gm.GroupID = $groupId AND gm.Status = 'approved'
                ORDER BY gm.Role ASC, u.Username ASC";
        $result = $this->query($sql);
        $list = [];
        if ($result) { while ($row = mysqli_fetch_assoc($result)) { $list[] = $row; } }
        return $list;
    }

    // ================= THAY ĐỔI QUYỀN THÀNH VIÊN =================
    public function updateMemberRole($userId, $groupId, $role) {
        $userId = (int)$userId;
        $groupId = (int)$groupId;
        $role = mysqli_real_escape_string($this->link, $role);
        
        $sql = "UPDATE group_member SET Role = '$role' 
                WHERE UserID = $userId AND GroupID = $groupId";
        return $this->execute($sql);
    }

}
?>