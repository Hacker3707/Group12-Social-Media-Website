<?php
include_once "MVC/Model/GroupModel.php";
include_once "MVC/Model/GroupMemberModel.php";
include_once "MVC/Model/CategoryModel.php";
include_once "Entity/Group.php";

class GroupController {

    private $groupModel;
    private $memberModel;
    private $categoryModel;

    public function __construct() {
        $this->groupModel = new GroupModel();
        $this->memberModel = new GroupMemberModel();
        $this->categoryModel = new CategoryModel();
    }

    public function handleRequest() {
        if (!isset($_GET['action'])) return;
        $action = $_GET['action'];
        if (method_exists($this, $action)) {
            $this->$action();
        } else {
            echo "Invalid action";
        }
    }

    // ================= ACTION: LIST =================
    public function list() {
        $groups = $this->groupModel->getAll();
        echo "<h2>Quản lý Nhóm</h2>";
        echo "<a href='index.php?controller=group&action=add'>+ Tạo nhóm mới</a><br><br>";
        echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
        echo "<tr><th>ID</th><th>Tên nhóm</th><th>Quyền riêng tư</th><th>Danh mục</th><th>Thao tác</th></tr>";
        foreach ($groups as $g) {
            echo "<tr>
                    <td>{$g->getGroupId()}</td>
                    <td><b>{$g->getGroupName()}</b></td>
                    <td>" . ucfirst($g->getPrivacy()) . "</td>
                    <td>{$g->getCategoryId()}</td> 
                    <td>
                        <a href='index.php?controller=group&action=viewMembers&id={$g->getGroupId()}'>Thành viên</a> | 
                        <a href='index.php?controller=group&action=edit&id={$g->getGroupId()}'>Sửa</a> | 
                        <a href='index.php?controller=group&action=delete&id={$g->getGroupId()}' onclick='return confirm(\"Xóa nhóm này?\")'>Xóa</a>
                    </td>
                  </tr>";
        }
        echo "</table>";
    }

    // ================= ACTION: CREATE =================
    public function create() {
        if (isset($_POST['groupName'])) {
            $group = new Group(null, $_POST['groupName'], $_POST['privacy']);
            $group->setDescription($_POST['description']);
            $group->setCategoryId($_POST['categoryId']);

            $result = $this->groupModel->insert($group);
            $msg = $result ? "Tạo nhóm thành công!" : "Lỗi khi tạo nhóm.";
            echo "<script>alert('$msg'); window.location.href='index.php?controller=group&action=list';</script>";
        }
    }

    // ================= ACTION: EDIT (LOAD FORM) =================
    public function edit() {
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $group = $this->groupModel->getById($id);
            $categories = $this->categoryModel->getAll(); // Để chọn lại danh mục

            if (!$group) {
                echo "Không tìm thấy nhóm!";
                return;
            }

            echo "
                <h2>Chỉnh sửa nhóm</h2>
                <form method='POST' action='index.php?controller=group&action=update'>
                    <input type='hidden' name='groupId' value='{$group['GroupID']}'>
                    <p>Tên nhóm: <input type='text' name='groupName' value='{$group['GroupName']}' required></p>
                    <p>Mô tả: <br><textarea name='description'>{$group['Description']}</textarea></p>
                    <p>Quyền riêng tư: 
                        <select name='privacy'>
                            <option value='public' " . ($group['Privacy'] == 'public' ? 'selected' : '') . ">Công khai</option>
                            <option value='private' " . ($group['Privacy'] == 'private' ? 'selected' : '') . ">Riêng tư</option>
                        </select>
                    </p>
                    <p>Danh mục: 
                        <select name='categoryId'>";
                        foreach ($categories as $cat) {
                            $selected = ($cat->getCategoryID() == $group['CategoryID']) ? 'selected' : '';
                            echo "<option value='{$cat->getCategoryID()}' $selected>{$cat->getCategoryName()}</option>";
                        }
            echo "      </select>
                    </p>
                    <button type='submit'>Lưu thay đổi</button>
                </form>
            ";
        }
    }

    // ================= ACTION: UPDATE =================
    public function update() {
        if (isset($_POST['groupId'])) {
            $result = $this->groupModel->update(
                $_POST['groupId'], 
                $_POST['groupName'], 
                $_POST['description'], 
                $_POST['privacy'], 
                $_POST['categoryId']
            );
            $msg = $result ? "Cập nhật thành công!" : "Lỗi cập nhật.";
            echo "<script>alert('$msg'); window.location.href='index.php?controller=group&action=list';</script>";
        }
    }

    // ================= ACTION: VIEW MEMBERS =================
    public function viewMembers() {
        if (isset($_GET['id'])) {
            $groupId = (int)$_GET['id'];
            $members = $this->memberModel->getMembers($groupId);

            echo "<h2>Thành viên của nhóm #$groupId</h2>";
            echo "<table border='1' cellpadding='5'>";
            echo "<tr><th>Username</th><th>Vai trò</th><th>Thao tác</th></tr>";
            foreach ($members as $m) {
                echo "<tr>
                        <td>{$m['Username']}</td>
                        <td>{$m['Role']}</td>
                        <td>
                            <a href='index.php?controller=group&action=removeUser&groupId=$groupId&userId={$m['UserID']}' onclick='return confirm(\"Trục xuất thành viên này?\")'>Xóa khỏi nhóm</a>
                        </td>
                      </tr>";
            }
            echo "</table><br><a href='index.php?controller=group&action=list'>Quay lại</a>";
        }
    }

    // ================= ACTION: REMOVE USER =================
    public function removeUser() {
        if (isset($_GET['groupId']) && isset($_GET['userId'])) {
            $result = $this->memberModel->removeMember($_GET['userId'], $_GET['groupId']);
            $msg = $result ? "Đã xóa thành viên!" : "Lỗi thao tác.";
            echo "<script>alert('$msg'); window.location.href='index.php?controller=group&action=viewMembers&id={$_GET['groupId']}';</script>";
        }
    }

    // ================= ACTION: DELETE GROUP =================
    public function delete() {
        if (isset($_GET['id'])) {
            $result = $this->groupModel->delete($_GET['id']);
            $msg = $result ? "Đã xóa nhóm thành công!" : "Lỗi khi xóa nhóm.";
            echo "<script>alert('$msg'); window.location.href='index.php?controller=group&action=list';</script>";
        }
    }
}