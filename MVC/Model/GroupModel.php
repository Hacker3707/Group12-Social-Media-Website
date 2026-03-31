<?php
include_once "MVC/Model/AppModel.php";
include_once "Entity/Group.php";

class GroupModel extends AppModel {

    // ================= CREATE =================
    public function insert(Group $group) {
        $name = mysqli_real_escape_string($this->link, $group->getGroupName());
        $desc = mysqli_real_escape_string($this->link, $group->getDescription());
        $privacy = $group->getPrivacy(); // 'public' hoặc 'private'
        $categoryId = (int)$group->getCategoryId();

        $sql = "INSERT INTO groups (GroupName, Description, Privacy, CategoryID) 
                VALUES ('$name', '$desc', '$privacy', $categoryId)";
        
        return $this->execute($sql);
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
        return $list;
    }

    // ================= GET BY ID =================
    public function getById($id) {
        $id = (int)$id;
        $sql = "SELECT * FROM groups WHERE GroupID = $id";
        $result = $this->query($sql);

        if ($row = mysqli_fetch_assoc($result)) {
            return $row; // Trả về mảng để Controller dễ đổ vào form Edit
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
}