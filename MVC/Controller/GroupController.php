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
            $this->redirect('index.php', 'Hành động không hợp lệ!');
        }
    }

    // ================= HELPER: CHUYỂN HƯỚNG =================
    protected function redirect($url, $message = null) {
        if ($message) $_SESSION['flash_message'] = $message;
        header("Location: $url");
        exit();
    }

    protected function back($message = null) {
        if ($message) $_SESSION['flash_message'] = $message;
        $referer = $_SERVER['HTTP_REFERER'] ?? 'index.php';
        header("Location: $referer");
        exit();
    }

    // ================= ACTION: LIST =================
    public function list() {
        $groups = $this->groupModel->getAll();
        // Không dùng echo HTML ở đây nữa, chuyển sang gọi View
        include_once "MVC/View/Group/list.php"; 
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

            $group = new Group(null, $groupName, $privacy);
            $group->setDescription("Chào mừng đến với $groupName");

            $newGroupId = $this->groupModel->insert($group); 

            if ($newGroupId) {
                $this->groupModel->joinGroup($_SESSION['user_id'], $newGroupId, 'admin');
                $this->redirect("index.php?controller=group&action=detail&id=$newGroupId", "Tạo nhóm thành công!");
            } else {
                $this->back("Lỗi khi lưu vào Database!");
            }
        }
    }

    // ================= ACTION: HIỂN THỊ TRANG CHI TIẾT NHÓM =================
    public function detail() {
        $groupId = $_GET['id'] ?? 0;
        $userId = $_SESSION['user_id'] ?? 0;
        
        $group = $this->groupModel->getById($groupId);
        
        if (!$group) {
            $this->redirect('index.php', 'Nhóm này không tồn tại hoặc đã bị xóa!');
        }

        $memberCount = $this->groupModel->getMemberCount($groupId);
        $joinStatus = $this->groupModel->getJoinStatus($userId, $groupId);
        $userRole = $this->groupModel->getUserRole($userId, $groupId);
        
        include_once "MVC/View/Group/detail.php";
    }

    public function toggleFollow() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('index.php?controller=user&action=login', 'Vui lòng đăng nhập!');
        }

        if (isset($_POST['group_id'])) {
            $userId = (int)$_SESSION['user_id'];
            $groupId = (int)$_POST['group_id'];

            $status = $this->groupModel->getJoinStatus($userId, $groupId);

            if ($status) {
                $this->groupModel->leaveGroup($userId, $groupId);
                $this->redirect("index.php?controller=group&action=detail&id=$groupId");
            } else {
                $group = $this->groupModel->getById($groupId);
                $newStatus = ($group['Privacy'] === 'private') ? 'pending' : 'approved';
                $this->groupModel->joinGroup($userId, $groupId, 'member', $newStatus);
                
                $msg = ($newStatus === 'pending') ? 'Đã gửi yêu cầu tham gia! Vui lòng chờ Admin duyệt.' : '';
                $this->redirect("index.php?controller=group&action=detail&id=$groupId", $msg);
            }
        }
    }

    // ================= ACTION: CHỈNH SỬA THÔNG TIN NHÓM =================
    public function edit() {
        $groupId = $_GET['id'] ?? 0;
        $userId = $_SESSION['user_id'] ?? 0;

        if ($this->groupModel->getUserRole($userId, $groupId) !== 'admin') {
            $this->back("Bạn không có quyền chỉnh sửa nhóm này!");
        }

        $group = $this->groupModel->getById($groupId);
        include_once "MVC/View/Group/edit.php";
    }

    public function update() {
        if (isset($_POST['groupId'])) {
            $groupId = (int)$_POST['groupId'];
            $userId = $_SESSION['user_id'] ?? 0;

            if ($this->groupModel->getUserRole($userId, $groupId) !== 'admin') {
                $this->back("Lỗi quyền truy cập!");
            }

            $name = $_POST['group_name'];
            $desc = $_POST['description'];
            $privacy = $_POST['privacy'];
            $categoryId = 0; 

            $this->groupModel->update($groupId, $name, $desc, $privacy, $categoryId);
            $this->redirect("index.php?controller=group&action=detail&id=$groupId", "Cập nhật thông tin nhóm thành công!");
        }
    }

    public function manageMembers() {
        $groupId = $_GET['id'] ?? 0;
        $userId = $_SESSION['user_id'] ?? 0;

        if ($this->groupModel->getUserRole($userId, $groupId) !== 'admin') {
            $this->back("Chỉ quản trị viên mới được vào trang này!");
        }

        $group = $this->groupModel->getById($groupId);
        $members = $this->groupModel->getGroupMembers($groupId);
        $pendingMembers = $this->groupModel->getPendingMembers($groupId); 
        
        include_once "MVC/View/Group/members.php";
    }

    public function processMember() {
        $groupId = (int)($_POST['group_id'] ?? 0);
        $targetUserId = (int)($_POST['target_user_id'] ?? 0);
        $action = $_POST['action_type'] ?? '';
        $currentUserId = $_SESSION['user_id'] ?? 0;

        if ($this->groupModel->getUserRole($currentUserId, $groupId) !== 'admin') {
            $this->back("Lỗi quyền truy cập!");
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

        $this->redirect("index.php?controller=group&action=manageMembers&id=$groupId", $msg);
    }

    // ================= ACTION: VIEW MEMBERS (Dành cho Admin tổng) =================
    public function viewMembers() {
        if (isset($_GET['id'])) {
            $groupId = (int)$_GET['id'];
            $members = $this->memberModel->getMembers($groupId);
            include_once "MVC/View/Group/members.php";
        }
    }

    public function removeUser() {
        if (isset($_GET['groupId']) && isset($_GET['userId'])) {
            $result = $this->memberModel->removeMember($_GET['userId'], $_GET['groupId']);
            $msg = $result ? "Đã xóa thành viên!" : "Lỗi thao tác.";
            $this->redirect("index.php?controller=group&action=viewMembers&id={$_GET['groupId']}", $msg);
        }
    }

    // ================= ACTION: XÓA NHÓM =================
    public function deleteGroup() {
        if (!isset($_SESSION['user_id'])) return;

        $groupId = (int)($_GET['id'] ?? 0);
        $userId = (int)$_SESSION['user_id'];

        if ($this->groupModel->getUserRole($userId, $groupId) === 'admin') {
            $this->groupModel->delete($groupId);
            $this->redirect('index.php?controller=group&action=myGroups', 'Đã xóa nhóm vĩnh viễn!');
        } else {
            $this->back('Lỗi: Bạn không phải quản trị viên của nhóm này!');
        }
    }

    public function discover() {
        $keyword = $_GET['q'] ?? '';
        $groups = $this->groupModel->searchGroups($keyword);
        include_once "MVC/View/Group/discover.php";
    }

    public function myGroups() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('index.php?controller=user&action=login', 'Vui lòng đăng nhập để xem nhóm!');
        }

        $userId = $_SESSION['user_id'];
        $myGroups = $this->groupModel->getGroupsByUser($userId);
        include_once "MVC/View/Group/my_groups.php";
    }
}