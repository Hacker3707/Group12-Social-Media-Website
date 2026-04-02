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
        // Cần có dòng này (và include file GroupModel) để các hàm bên dưới gọi được Database
        include_once "MVC/Model/GroupModel.php";
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
        include_once "MVC/View/Group/create.php";
    }

    // ================= ACTION: XỬ LÝ LƯU NHÓM MỚI =================
    public function store() {
        if (isset($_POST['group_name']) && isset($_POST['privacy'])) {
            $groupName = $_POST['group_name'];
            $privacy = $_POST['privacy'];

            // Dùng Entity Group để chứa dữ liệu
            // Giả định Description đang trống và CategoryID = NULL
            $group = new Group(null, $groupName, $privacy);
            $group->setDescription("Chào mừng đến với $groupName");

            // Gọi Model để lưu thật vào DB và lấy ID thật
            $newGroupId = $this->groupModel->insert($group); 

            if ($newGroupId) {
                // Tự động cho người tạo làm thành viên đầu tiên luôn (Tùy chọn)
                $this->groupModel->joinGroup($_SESSION['user_id'], $newGroupId, 'admin');

                // Chuyển hướng sang đúng ID thật
                echo "<script>alert('Tạo nhóm thành công!'); window.location.href='index.php?controller=group&action=detail&id=$newGroupId';</script>";
            } else {
                echo "<script>alert('Lỗi khi lưu vào Database!'); window.history.back();</script>";
            }
        }
    }

    // ================= ACTION: HIỂN THỊ TRANG CHI TIẾT NHÓM =================
    public function detail() {
        $groupId = $_GET['id'] ?? 0;
        $userId = $_SESSION['user_id'] ?? 0;
        
        // 1. LẤY DỮ LIỆU THẬT TỪ DATABASE
        $group = $this->groupModel->getById($groupId);
        
        // 2. Nếu nhóm không tồn tại (ai đó gõ bậy ID lên URL) -> Đuổi về trang chủ
        if (!$group) {
            echo "<script>alert('Nhóm này không tồn tại hoặc đã bị xóa!'); window.location.href='index.php';</script>";
            return;
        }

        // 3. Lấy số liệu thật
        $memberCount = $this->groupModel->getMemberCount($groupId);
        $joinStatus = $this->groupModel->getJoinStatus($userId, $groupId);
        $userRole = $this->groupModel->getUserRole($userId, $groupId);
        
        // Gọi giao diện
        include_once "MVC/View/Group/detail.php";
    }

    public function toggleFollow() {
        if (!isset($_SESSION['user_id'])) return;

        if (isset($_POST['group_id'])) {
            $userId = (int)$_SESSION['user_id'];
            $groupId = (int)$_POST['group_id'];

            $status = $this->groupModel->getJoinStatus($userId, $groupId);

            if ($status) {
                // Nếu đang 'pending' hoặc 'approved' -> Bấm nút là Hủy/Rời nhóm
                $this->groupModel->leaveGroup($userId, $groupId);
            } else {
                // Nếu CHƯA THAM GIA -> Kiểm tra Privacy của nhóm
                $group = $this->groupModel->getById($groupId);
                $newStatus = ($group['Privacy'] === 'private') ? 'pending' : 'approved';
                $this->groupModel->joinGroup($userId, $groupId, 'member', $newStatus);
                
                if ($newStatus === 'pending') {
                    echo "<script>alert('Đã gửi yêu cầu tham gia! Vui lòng chờ Admin duyệt.');</script>";
                }
            }
            echo "<script>window.location.href='index.php?controller=group&action=detail&id=$groupId';</script>";
        }
    }

    // ================= ACTION: CHỈNH SỬA THÔNG TIN NHÓM =================
    public function edit() {
        $groupId = $_GET['id'] ?? 0;
        $userId = $_SESSION['user_id'] ?? 0;

        // Chặn nếu không phải Admin
        if ($this->groupModel->getUserRole($userId, $groupId) !== 'admin') {
            echo "<script>alert('Bạn không có quyền chỉnh sửa nhóm này!'); window.history.back();</script>";
            return;
        }

        $group = $this->groupModel->getById($groupId);
        include_once "MVC/View/Group/edit.php";
    }

    public function update() {
        if (isset($_POST['groupId'])) {
            $groupId = (int)$_POST['groupId'];
            $userId = $_SESSION['user_id'] ?? 0;

            if ($this->groupModel->getUserRole($userId, $groupId) !== 'admin') {
                echo "<script>alert('Lỗi quyền truy cập!'); window.history.back();</script>"; return;
            }

            $name = $_POST['group_name'];
            $desc = $_POST['description'];
            $privacy = $_POST['privacy'];
            $categoryId = 0; // Tạm thời để 0 hoặc lấy từ form nếu bạn có làm danh mục

            $this->groupModel->update($groupId, $name, $desc, $privacy, $categoryId);
            echo "<script>alert('Cập nhật thông tin nhóm thành công!'); window.location.href='index.php?controller=group&action=detail&id=$groupId';</script>";
        }
    }

    public function manageMembers() {
        $groupId = $_GET['id'] ?? 0;
        $userId = $_SESSION['user_id'] ?? 0;

        if ($this->groupModel->getUserRole($userId, $groupId) !== 'admin') {
            echo "<script>alert('Chỉ quản trị viên mới được vào trang này!'); window.history.back();</script>"; return;
        }

        $group = $this->groupModel->getById($groupId);
        $members = $this->groupModel->getGroupMembers($groupId);
        // THÊM DÒNG NÀY: Lấy danh sách đang chờ duyệt
        $pendingMembers = $this->groupModel->getPendingMembers($groupId); 
        
        include_once "MVC/View/Group/members.php";
    }

    public function processMember() {
        $groupId = (int)($_POST['group_id'] ?? 0);
        $targetUserId = (int)($_POST['target_user_id'] ?? 0);
        $action = $_POST['action_type'] ?? '';
        $currentUserId = $_SESSION['user_id'] ?? 0;

        if ($this->groupModel->getUserRole($currentUserId, $groupId) !== 'admin') {
            echo "<script>alert('Lỗi quyền truy cập!'); window.history.back();</script>"; return;
        }

        if ($action === 'kick' || $action === 'reject') {
            $this->groupModel->leaveGroup($targetUserId, $groupId);
            $msg = ($action === 'reject') ? "Đã từ chối yêu cầu!" : "Đã xóa thành viên khỏi nhóm!";
        } elseif ($action === 'approve') {
            $this->groupModel->approveMember($targetUserId, $groupId);
            $msg = "Đã duyệt thành viên!";
        } elseif ($action === 'promote') {
            $this->groupModel->updateMemberRole($targetUserId, $groupId, 'admin');
            $msg = "Đã phong làm Quản trị viên!";
        } elseif ($action === 'demote') {
            $this->groupModel->updateMemberRole($targetUserId, $groupId, 'member');
            $msg = "Đã giáng cấp!";
        }

        echo "<script>alert('$msg'); window.location.href='index.php?controller=group&action=manageMembers&id=$groupId';</script>";
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

    // ================= ACTION: XÓA NHÓM (Chỉ Admin mới được xóa) =================
    public function deleteGroup() {
        if (!isset($_SESSION['user_id'])) return;

        $groupId = (int)($_GET['id'] ?? 0);
        $userId = (int)$_SESSION['user_id'];

        // Kiểm tra xem User này có phải là 'admin' của nhóm không
        if ($this->groupModel->getUserRole($userId, $groupId) === 'admin') {
            $this->groupModel->delete($groupId);
            
            // Note: Nhờ bạn thiết kế DB có ON DELETE CASCADE, khi xóa Group, 
            // toàn bộ thành viên trong group_member cũng sẽ tự động được MySQL dọn dẹp sạch sẽ!
            echo "<script>alert('Đã xóa nhóm vĩnh viễn!'); window.location.href='index.php?controller=group&action=myGroups';</script>";
        } else {
            echo "<script>alert('Lỗi: Bạn không phải quản trị viên của nhóm này!'); window.history.back();</script>";
        }
    }

    // ================= ACTION: KHÁM PHÁ / TÌM KIẾM NHÓM =================
    public function discover() {
        $keyword = $_GET['q'] ?? '';
        $groups = $this->groupModel->searchGroups($keyword);
        include_once "MVC/View/Group/discover.php";
    }

    // ================= ACTION: HIỂN THỊ DANH SÁCH NHÓM CỦA TÔI =================
    public function myGroups() {
        // Kiểm tra xem đã đăng nhập chưa
        if (!isset($_SESSION['user_id'])) {
            echo "<script>alert('Vui lòng đăng nhập để xem nhóm!'); window.location.href='index.php?controller=user&action=login';</script>";
            return;
        }

        $userId = $_SESSION['user_id'];

        // Lấy danh sách nhóm THẬT từ Database
        $myGroups = $this->groupModel->getGroupsByUser($userId);

        // Gọi file giao diện hiển thị
        include_once "MVC/View/Group/my_groups.php";
    }
}