<?php
include_once "MVC/Model/GroupModel.php";
include_once "MVC/Model/GroupMemberModel.php";
include_once "MVC/Model/CategoryModel.php";
include_once "MVC/Model/PostModel.php";
include_once "MVC/Model/ReactionModel.php";
include_once "MVC/Model/CommentModel.php";
include_once "MVC/Model/FollowModel.php";
include_once "Entity/Group.php";

class GroupController {

    private $groupModel;
    private $memberModel;
    private $categoryModel;
    private $followModel;

    private $commentModel;

    private $postModel;

    private $reactionModel;

    public function __construct() {
        $this->groupModel = new GroupModel();
        $this->memberModel = new GroupMemberModel();
        $this->categoryModel = new CategoryModel();
        $this->postModel = new PostModel();
        $this->reactionModel = new ReactionModel();
        $this->commentModel = new CommentModel();
        $this->followModel = new FollowModel();
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

    // ================= ACTION: LIST (Cho Admin Hệ Thống) =================
    public function list() {
        // Chỉ Admin hệ thống mới xem được toàn bộ danh sách nhóm
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            $this->redirect('index.php', 'Bạn không có quyền truy cập!');
        }

        $isSystemAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
        $isPageAdmin = true;

        $groups = $this->groupModel->getAll();
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
                // Người tạo sẽ tự động thành Admin của nhóm
                $this->groupModel->joinGroup($_SESSION['user_id'], $newGroupId, 'admin');
                $this->redirect("index.php?controller=group&action=detail&id=$newGroupId", "Tạo nhóm thành công!");
            } else {
                $this->back("Lỗi khi lưu vào Database!");
            }
        }
    }

    // ================= ACTION: HIỂN THỊ TRANG CHI TIẾT NHÓM =================
    public function detail() {
        
        $navbarCategories = $this->categoryModel->getAll() ?? [];

        $groupId = $_GET['id'] ?? 0;
        $userId = $_SESSION['user_id'] ?? 0;
        
        $group = $this->groupModel->getById($groupId);

        
        
        if (!$group) {
            $this->redirect('index.php', 'Nhóm này không tồn tại hoặc đã bị xóa!');
        }

        $memberCount = $this->groupModel->getMemberCount($groupId);
        $joinStatus = $this->groupModel->getJoinStatus($userId, $groupId);
        $userRole = $this->groupModel->getUserRole($userId, $groupId);
        $isGroupAdmin = ($userRole === 'admin');

        // ==========================================================
        // 🔥 BỘ LỌC QUYỀN TRUY CẬP VÀ TƯƠNG TÁC (NEW)
        // ==========================================================
        $isSystemAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
        $canViewPosts = true;
        $canInteract = true;
        $groupModerationContext = false;

        if (!$isSystemAdmin) {
            // Luật 1: Nhóm Private + Chưa tham gia -> Cấm xem bài
            if (strtolower($group['Privacy']) === 'private' && !$userRole) {
                $canViewPosts = false; 
            }
            // Luật 2: Chưa tham gia -> Cấm tương tác (Comment/React)
            if (!$userRole) {
                $canInteract = false; 
            }
        }

        // 🔥 Lấy filter nếu có
        $categoryId = $_SESSION['category_filter'] ?? null;

        // CHỈ TRUY VẤN BÀI VIẾT NẾU ĐƯỢC PHÉP XEM
        if ($canViewPosts) {
            $posts = $this->postModel->fetchByField('GroupID', $groupId) ;
        } else {
            $posts = []; // Không được xem thì gán mảng rỗng để các hàm dưới khỏi tốn công chạy
        }

        $userid = $_SESSION['user_id'] ?? null;
        $canEditPost = [];
        $canDeletePost = [];
        $groupModerationContext = $isSystemAdmin || $isGroupAdmin;

        foreach ($posts as $post) {
            $postId = $post->getPostId();
            $isOwnerPost = ($userid && (int)$post->getUserId() === (int)$userid);
            $canEditPost[$postId] = $isOwnerPost || $isSystemAdmin;
            $canDeletePost[$postId] = $isOwnerPost || $isSystemAdmin || $isGroupAdmin;
        }

        
        // =======================
        // 🔥 REACTIONS POST
        // =======================
        $reactions_forPost = [];

        foreach($posts as $post){
            $postId = $post->getPostId();
            $reactions_forPost[$postId] = $this->reactionModel->selectReactionsForPost($postId);
        }

        // =======================
        // 🔥 CHECK USER LIKE POST
        // =======================
        $isSameUser_reactPost = [];

        foreach($posts as $post){
            $postId = $post->getPostId();
            $isSameUser_reactPost[$postId] = false;

            foreach($reactions_forPost[$postId] as $reaction){
                if($reaction->getUserId() == $userid){
                    $isSameUser_reactPost[$postId] = true;
                    break;
                }
            }
        }

        $comments = [];
        foreach($posts as $post) {
            $comments[$post->getPostId()] = $this->commentModel->fetchByField('PostID', $post->getPostId());
        }

        $commentTree = [];
        foreach($posts as $post){
            $postId = $post->getPostId();
            $commentTree[$postId] = [];

            foreach($comments[$postId] as $c){
                /* Lưu ý: Đảm bảo class Comment của bạn có hàm getParentCommentId() như code bạn viết nhé */
                $parent = $c->getParentCommentId();
                $commentTree[$postId][$parent][] = $c;
            }
        }

        // =======================
        // 🔥 REACTION COMMENT
        // =======================
        $reactions_forComment = [];

        foreach($comments as $postComments){
            foreach($postComments as $comment){
                $commentId = $comment->getCommentId();
                $reactions_forComment[$commentId] = $this->reactionModel->selectReactionsForComment($commentId);
            }
        }

        // =======================
        // 🔥 CHECK USER LIKE COMMENT
        // =======================
        $isSameUser_reactCmt = [];

        foreach($comments as $postComments){
            foreach($postComments as $comment){
                $commentId = $comment->getCommentId();
                $isSameUser_reactPost_reactCmt[$commentId] = false;

                foreach($reactions_forComment[$commentId] as $reaction){
                    if($reaction->getUserId() == $userid){
                        $isSameUser_reactCmt[$commentId] = true;
                        break;
                    }
                }
            }
        }

        // =======================
        // 🔥 RENDER VIEW
        // =======================
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
        $isSystemAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');

        // Admin hệ thống HOẶC Admin của nhóm mới được sửa
        if (!$isSystemAdmin && $this->groupModel->getUserRole($userId, $groupId) !== 'admin') {
            $this->back("Bạn không có quyền chỉnh sửa nhóm này!");
        }

        $group = $this->groupModel->getById($groupId);
        if (!$group) $this->back("Nhóm không tồn tại!");
        
        include_once "MVC/View/Group/edit.php";
    }

    public function update() {
        if (isset($_POST['groupId'])) {
            $groupId = (int)$_POST['groupId'];
            $userId = $_SESSION['user_id'] ?? 0;
            $isSystemAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');

            if (!$isSystemAdmin && $this->groupModel->getUserRole($userId, $groupId) !== 'admin') {
                $this->back("Lỗi quyền truy cập!");
            }

            $group = $this->groupModel->getById($groupId);
            if (!$group) {
                $this->back("Nhóm không tồn tại!");
            }

            $name = trim((string)($_POST['group_name'] ?? ''));
            $desc = trim((string)($_POST['description'] ?? ''));
            $privacy = strtolower((string)($_POST['privacy'] ?? 'public'));

            if ($name === '') {
                $this->back("Tên nhóm không được để trống!");
            }

            if (!in_array($privacy, ['public', 'private'], true)) {
                $privacy = 'public';
            }

            if (isset($_POST['category_id']) && $_POST['category_id'] !== '') {
                $categoryId = (int)$_POST['category_id'];
            } else {
                $categoryId = isset($group['CategoryID']) && $group['CategoryID'] !== null
                    ? (int)$group['CategoryID']
                    : null;
            }

            $this->groupModel->update($groupId, $name, $desc, $privacy, $categoryId);
            $this->redirect("index.php?controller=group&action=detail&id=$groupId", "Cập nhật thông tin nhóm thành công!");
        }
    }

    // ================= ACTION: QUẢN LÝ / XEM THÀNH VIÊN =================
    public function manageMembers() {
        $this->viewMembers(); // Gộp chung xử lý với viewMembers cho gọn
    }

    public function viewMembers() {
        $groupId = $_GET['id'] ?? 0;
        $userId = $_SESSION['user_id'] ?? 0;
        $isSystemAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');

        // Phân quyền: Ai cũng có thể vào xem, nhưng các nút Quản lý sẽ do View tự ẩn hiện
        $group = $this->groupModel->getById($groupId);
        
        if (!$group) {
            $this->back("Nhóm không tồn tại!");
        }

        // Lấy đúng danh sách bằng hàm getGroupMembers (chứa cả Email để View không báo lỗi)
        $members = $this->groupModel->getGroupMembers($groupId);
        $pendingMembers = $this->groupModel->getPendingMembers($groupId); 
        
        include_once "MVC/View/Group/members.php";
    }

    // ================= ACTION: XỬ LÝ (DUYỆT, KICK, PHONG ADMIN) =================
    public function processMember() {
        $groupId = (int)($_POST['group_id'] ?? 0);
        $targetUserId = (int)($_POST['target_user_id'] ?? 0);
        $action = $_POST['action_type'] ?? '';
        $currentUserId = $_SESSION['user_id'] ?? 0;

        // KIỂM TRA QUYỀN LỰC: Phải là Admin Hệ Thống HOẶC Admin của Nhóm thì mới được thao tác
        $isSystemAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
        $isGroupAdmin = ($this->groupModel->getUserRole($currentUserId, $groupId) === 'admin');

        if (!$isSystemAdmin && !$isGroupAdmin) {
            $this->back("Lỗi quyền truy cập! Bạn không phải Quản trị viên.");
        }

        if ($action === 'kick' || $action === 'reject') {
            $this->groupModel->leaveGroup($targetUserId, $groupId);
            $msg = ($action === 'reject') ? "Đã từ chối yêu cầu!" : "Đã xóa thành viên khỏi nhóm!";
        } elseif ($action === 'approve') {
            $this->groupModel->approveMember($targetUserId, $groupId);
            $msg = "Đã duyệt thành viên!";
        } elseif ($action === 'promote') {
            $this->groupModel->updateMemberRole($targetUserId, $groupId, 'admin');
            $msg = "Đã phong làm Quản trị viên nhóm!";
        } elseif ($action === 'demote') {
            $this->groupModel->updateMemberRole($targetUserId, $groupId, 'member');
            $msg = "Đã giáng cấp thành viên!";
        }

        // Dùng back() để quay lại đúng trang (bất kể đang đứng ở manageMembers hay viewMembers)
        $this->back($msg);
    }

    public function removeUser() {
        // Hàm này giữ lại phòng hờ nếu bạn có nút xóa riêng
        if (isset($_GET['groupId']) && isset($_GET['userId'])) {
            $result = $this->memberModel->removeMember($_GET['userId'], $_GET['groupId']);
            $msg = $result ? "Đã xóa thành viên!" : "Lỗi thao tác.";
            $this->back($msg);
        }
    }

    // ================= ACTION: XÓA NHÓM VĨNH VIỄN =================
    public function deleteGroup() {
        if (!isset($_SESSION['user_id'])) return;

        $groupId = (int)($_GET['id'] ?? 0);
        $userId = (int)$_SESSION['user_id'];
        $isSystemAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');

        // Admin hệ thống HOẶC Admin nhóm mới được quyền xóa
        if ($isSystemAdmin || $this->groupModel->getUserRole($userId, $groupId) === 'admin') {
            $this->groupModel->delete($groupId);
            
            // Xóa xong, nếu là admin hệ thống thì đẩy về danh sách All, nếu user thì đẩy về myGroups
            $redirectUrl = $isSystemAdmin ? 'index.php?controller=group&action=list' : 'index.php?controller=group&action=myGroups';
            $this->redirect($redirectUrl, 'Đã xóa nhóm vĩnh viễn!');
        } else {
            $this->back('Lỗi: Bạn không có quyền xóa nhóm này!');
        }
    }

    public function discover() {
        $navbarCategories = $this->categoryModel->getAll() ?? [];

        $keyword = $_GET['q'] ?? '';
        $groups = $this->groupModel->searchGroups($keyword);
        include_once "MVC/View/Group/discover.php";
    }

    public function myGroups() {
        $navbarCategories = $this->categoryModel->getAll() ?? [];
        
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('index.php?controller=user&action=login', 'Vui lòng đăng nhập để xem nhóm!');
        }

        $userId = $_SESSION['user_id'];
        $myGroups = $this->groupModel->getGroupsByUser($userId);
        include_once "MVC/View/Group/my_groups.php";
    }

    public function showCreateForm(){
        $navbarCategories = $this->categoryModel->getAll() ?? [];

        $categories = $this->categoryModel->getAll(); // 👈 lấy từ DB

        $isSystemAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
        $isPageAdmin = false;

        $groupId = $_GET['id'];

        $group = $this->groupModel->getObjById($groupId);

        include __DIR__ . "/../View/createpost_view.php";
        
        die();
    }

    public function viewMembersinDetailGroup(){
        $navbarCategories = $this->categoryModel->getAll() ?? [];

        $groupId = $_GET['id'] ?? 0;
        $userId = $_SESSION['user_id'] ?? 0;
        
        $group = $this->groupModel->getById($groupId);

        
        
        if (!$group) {
            $this->redirect('index.php', 'Nhóm này không tồn tại hoặc đã bị xóa!');
        }

        $memberCount = $this->groupModel->getMemberCount($groupId);
        $joinStatus = $this->groupModel->getJoinStatus($userId, $groupId);
        $userRole = $this->groupModel->getUserRole($userId, $groupId);
        $membersInGroup = $this->groupModel->getGroupMembers($groupId);
        $userFollowStatus = [];

        if ($userId && !empty($membersInGroup)) {
            foreach ($membersInGroup as $member) {
                if (isset($member['UserID']) && (int)$member['UserID'] !== (int)$userId) {
                    $userFollowStatus[$member['UserID']] = $this->followModel->exists($userId, $member['UserID']);
                }
            }
        }

        // ==========================================================
        // 🔥 BỘ LỌC QUYỀN TRUY CẬP VÀ TƯƠNG TÁC (NEW)
        // ==========================================================
        $isSystemAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
        $canViewPosts = true;
        $canInteract = true;

        if (!$isSystemAdmin) {
            // Luật 1: Nhóm Private + Chưa tham gia -> Cấm xem bài
            if (strtolower($group['Privacy']) === 'private' && !$userRole) {
                $canViewPosts = false; 
            }
            // Luật 2: Chưa tham gia -> Cấm tương tác (Comment/React)
            if (!$userRole) {
                $canInteract = false; 
            }
        }

        

        include __DIR__ ."/../View/Group/memberlist_view.php";
    
    }

}
?>