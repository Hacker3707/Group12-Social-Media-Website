<?php
include_once "MVC/Model/UserModel.php";
include_once "Entity/User.php";
include_once "Entity/Admin.php";
include_once "Entity/Member.php";
include_once __DIR__ . "/../Model/PostModel.php";
include_once __DIR__ . "/../Model/ReactionModel.php";
include_once __DIR__ . "/../../Entity/Media.php";
include_once __DIR__ . "/../Model/CategoryModel.php";
include_once __DIR__ . "/../Model/CommentModel.php";
include_once __DIR__ . "/../Model/GroupModel.php";
include_once __DIR__ . "/../Model/MediaModel.php";
include_once "MVC/Model/FollowModel.php";
include_once "MVC/Service/Supabase/SupabaseService.php";
include_once "MVC/Service/Cloudinary/CloudinaryService.php";

class UserController {
    private const LOGIN_CAPTCHA_TTL = 30;

    private $postModel;
    private $reactionModel;
    private $categoryModel;
    private $commentModel;
    private $userModel;
    private $groupModel;
    private $mediaModel;

    private $followModel;

    public function __construct() {
       $this->postModel     = new PostModel();
        $this->reactionModel = new ReactionModel();
        $this->categoryModel = new CategoryModel();
        $this->commentModel = new CommentModel();
        $this->userModel = new UserModel();
        $this->groupModel = new GroupModel();
        $this->mediaModel    = new MediaModel();
        $this -> followModel = new FollowModel();
    }

    public function handleRequest() {
        if (!isset($_GET['action'])) return;
        $action = $_GET['action'];
        if (method_exists($this, $action)) {
            $this->$action();
        } else {
            $this->redirect('/index.php', 'Hành động không hợp lệ!');
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

    private function generateLoginCaptcha() {
        $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
        $captcha = '';

        for ($i = 0; $i < 5; $i++) {
            $captcha .= $alphabet[random_int(0, strlen($alphabet) - 1)];
        }

        $_SESSION['login_captcha_question'] = implode(' ', str_split($captcha));
        $_SESSION['login_captcha_answer'] = $captcha;
        $_SESSION['login_captcha_generated_at'] = time();
        $_SESSION['login_captcha_ttl'] = self::LOGIN_CAPTCHA_TTL;
    }

    public function register() {
        include_once "MVC/View/User/register.php";
    }

    public function login() {
        $this->generateLoginCaptcha();
        include_once "MVC/View/User/login.php";
    }

    public function googleLogin() {
        header('Location: ' . SupabaseService::getGoogleLoginUrl());
        exit();
    }

    // ================= XỬ LÝ ĐĂNG NHẬP =================
    public function authenticate() {
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $captchaInput = strtoupper(trim($_POST['captcha_answer'] ?? ''));
            $expectedCaptcha = strtoupper((string)($_SESSION['login_captcha_answer'] ?? ''));
            $captchaGeneratedAt = (int)($_SESSION['login_captcha_generated_at'] ?? 0);

            if (
                $expectedCaptcha === '' ||
                $captchaGeneratedAt <= 0 ||
                (time() - $captchaGeneratedAt) > self::LOGIN_CAPTCHA_TTL ||
                $captchaInput === '' ||
                $captchaInput !== $expectedCaptcha
            ) {
                unset($_SESSION['login_captcha_answer'], $_SESSION['login_captcha_question'], $_SESSION['login_captcha_generated_at'], $_SESSION['login_captcha_ttl']);
                $this->back('CAPTCHA không đúng hoặc đã hết hạn. Vui lòng thử lại!');
            }

            unset($_SESSION['login_captcha_answer'], $_SESSION['login_captcha_question'], $_SESSION['login_captcha_generated_at'], $_SESSION['login_captcha_ttl']);

            $username = $_POST['username'];
            $password = $_POST['password'];

            $user = $this->userModel->checkLogin($username, $password);

            if ($user) {
                session_regenerate_id(true);

                // 1. Lưu thông tin vào Session
                $_SESSION['user_id'] = $user['UserID'];
                $_SESSION['username'] = $user['Username'];
                $_SESSION['role'] = $user['UserRole']; 
                $_SESSION['avatar'] = $user['AvatarFP'] ?? null;
                $_SESSION['last_activity'] = time();

                // 2. PHÂN LUỒNG ĐIỀU HƯỚNG (ĐIỂM SỬA CHÍNH Ở ĐÂY)
                if ($user['UserRole'] === 'admin') {
                    // Nếu là Admin -> Cho bay thẳng vào trang Quản lý (Dashboard)
                    $this->redirect('index.php?controller=user&action=list', 'Đăng nhập thành công! Chào mừng Quản trị viên.');
                } else {
                    // Nếu là Member thường -> Cho ra Trang chủ (Newsfeed)
                    $this->redirect('index.php', 'Đăng nhập thành công!');
                }

            } else {
                $this->back('Sai tài khoản, mật khẩu hoặc tài khoản của bạn đã bị khóa/xóa!');
            }
        }
    }

    public function logout() {
        session_destroy();
        header("Location: index.php");
        exit();
    }

    // ================= XỬ LÝ ĐĂNG KÝ =================
    public function create() {
        if (isset($_POST['username']) && isset($_POST['email'])) {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            if ($this->userModel->existsByUsername($username)) {
                $this->back('Tên đăng nhập đã tồn tại! Vui lòng chọn tên khác.');
            }

            // Đăng ký mặc định là Member
            $user = new Member(null, $username, $email, $password);
            
            $result = $this->userModel->insert($user, $password);

            if ($result) {
                $newUser = $this->userModel->checkLogin($username, $password);
                
                if ($newUser) {
                    session_regenerate_id(true);

                    $_SESSION['user_id'] = $newUser['UserID'];
                    $_SESSION['username'] = $newUser['Username'];
                    $_SESSION['role'] = $newUser['UserRole'];
                    $_SESSION['avatar'] = $newUser['AvatarFP'] ?? null;
                    $_SESSION['last_activity'] = time();
                }

                $this->redirect('index.php', 'Đăng ký thành công! Chào mừng bạn gia nhập Passo.');
            } else {
                $this->back('Lỗi hệ thống khi đăng ký.');
            }
        }
    }

    // ================= QUẢN LÝ USER DÀNH CHO ADMIN =================
    public function list() {
        // Chỉ Admin mới được vào trang này
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            $this->redirect('index.php', 'Bạn không có quyền truy cập trang quản trị!');
        }

        $isSystemAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
        $isPageAdmin = ($_GET['controller'] === 'user' && $_GET['action'] === 'list');

        $keyword = trim($_GET['keyword'] ?? '');
        
        if ($keyword !== '') {
            $users = $this->userModel->searchUsers($keyword, true);
        } else {
            $users = $this->userModel->getAll();
        }

        include_once "MVC/View/User/list.php";
    }

    public function adminCategories() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            $this->redirect('index.php', 'Bạn không có quyền truy cập trang quản trị!');
        }

        $isSystemAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
        $isPageAdmin = true;
        $categories = $this->categoryModel->getAll();
        $totalCategories = is_array($categories) ? count($categories) : 0;
        $totalUsers = $this->userModel->countAll();
        $totalGroups = $this->groupModel->countAll();
        $totalPosts = $this->postModel->countAll();

        include_once "MVC/View/Admin/Category/list.php";
    }

    public function adminStatistics() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            $this->redirect('index.php', 'Bạn không có quyền truy cập trang quản trị!');
        }

        $isSystemAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
        $isPageAdmin = true;

        $totalUsers = $this->userModel->countAll();
        $totalGroups = $this->groupModel->countAll();
        $totalPosts = $this->postModel->countAll();
        $totalCategories = $this->categoryModel->getAll();
        $totalCategories = is_array($totalCategories) ? count($totalCategories) : 0;

        include_once "MVC/View/Admin/Statistics/list.php";
    }

    public function adminCreateCategory() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            $this->back('Lỗi quyền truy cập!');
        }

        if (!isset($_POST['categoryName'])) {
            $this->back('Thiếu tên danh mục!');
        }

        $categoryName = trim($_POST['categoryName']);

        if ($categoryName === '') {
            $this->back('Tên danh mục không được để trống!');
        }

        if ($this->categoryModel->existsByName($categoryName)) {
            $this->back('Danh mục đã tồn tại!');
        }

        $this->categoryModel->insert($categoryName);
        $this->redirect('index.php?controller=user&action=adminCategories', 'Đã thêm danh mục thành công!');
    }

    public function adminDeleteCategory() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            $this->back('Lỗi quyền truy cập!');
        }

        if (!isset($_GET['id'])) {
            $this->back('Không tìm thấy danh mục cần xóa!');
        }

        $categoryId = (int)$_GET['id'];
        if ($categoryId <= 0) {
            $this->back('ID danh mục không hợp lệ!');
        }

        $postCount = $this->categoryModel->countPostsByCategory($categoryId);
        if ($postCount > 0) {
            $this->back('Không thể xóa danh mục vì đang được dùng bởi ' . $postCount . ' bài viết.');
        }

        $this->categoryModel->delete($categoryId);
        $this->redirect('index.php?controller=user&action=adminCategories', 'Đã xóa danh mục!');
    }

    public function delete() {
        if (isset($_GET['id'])) {
            if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
                $this->back('Lỗi quyền truy cập!');
            }

            $id = (int)$_GET['id'];
            if ($id === (int)$_SESSION['user_id']) {
                $this->back('Lỗi: Bạn không thể tự xóa chính mình!');
            }

            $this->userModel->delete($id);
            $this->redirect('index.php?controller=user&action=list', 'Đã xóa người dùng!');
        }
    }

    // ================= KHÓA / MỞ KHÓA TÀI KHOẢN =================
    public function ban() {
        if (isset($_GET['id'])) {
            if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
                $this->back('Lỗi quyền truy cập!');
            }

            $id = (int)$_GET['id'];
            if ($id === (int)$_SESSION['user_id']) {
                $this->back('Lỗi: Bạn không thể tự khóa tài khoản của mình!');
            }

            $this->userModel->banUser($id);
            $this->redirect('index.php?controller=user&action=list', 'Đã khóa tài khoản thành công!');
        }
    }

    public function unban() {
        if (isset($_GET['id'])) {
            if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
                $this->back('Lỗi quyền truy cập!');
            }

            $id = (int)$_GET['id'];
            $this->userModel->unbanUser($id);
            $this->redirect('index.php?controller=user&action=list', 'Đã mở khóa tài khoản!');
        }
    }

    // ================= CHỈNH SỬA & HỒ SƠ =================
    public function edit() {
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $user = $this->userModel->getById($id);

            if (!$user) {
                $this->back('Không tìm thấy người dùng!');
            }

            include_once "MVC/View/User/edit.php";
        }
    }

    public function update() {
        if (isset($_POST['userId'])) {
            $userId = (int)$_POST['userId'];

            if ($_SESSION['role'] !== 'admin' && $_SESSION['user_id'] != $userId) {
                $this->back('Lỗi: Bạn không có quyền chỉnh sửa hồ sơ của người khác!');
            }

            $username = $_POST['username'];
            $email = $_POST['email'];
            $bio = $_POST['bio'] ?? '';
            $phone = $_POST['phone'] ?? '';

            if ($this->userModel->existsByUsername($username, $userId)) {
                $this->back('Username này đã được người khác sử dụng!');
            }

            // ================= XỬ LÝ UPLOAD AVATAR LÊN CLOUDINARY =================
            $avatarUrl = null;

            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                try {
                    // Gọi Singleton Cloudinary
                    $cloudinary = CloudinaryService::getInstance();
                    
                    // Upload file tmp_name lên Cloudinary
                    $uploadResult = $cloudinary->uploadApi()->upload($_FILES['avatar']['tmp_name'], [
                        'folder' => 'avatars', // Lưu vào thư mục passo_avatars trên Cloudinary
                        'transformation' => [
                            'width' => 400, 
                            'height' => 400, 
                            'crop' => 'fill', 
                            'gravity' => 'face' // Tự động nhận diện khuôn mặt và cắt vuông ảnh
                        ]
                    ]);
                    
                    // Lấy link ảnh an toàn (https) trả về từ Cloudinary
                    $avatarUrl = $uploadResult['secure_url'];

                } catch (Exception $e) {
                    $this->back('Lỗi khi tải ảnh lên Cloudinary: ' . $e->getMessage());
                }
            }
            // =====================================================================

            // Truyền thêm $avatarUrl vào hàm update của Model
            $result = $this->userModel->update($userId, $username, $email, $bio, $phone, $avatarUrl);

            if ($result) {
                if ($_SESSION['user_id'] == $userId) {
                    $_SESSION['username'] = $username; 
                    // Nếu muốn, có thể lưu luôn Avatar vào Session để hiện trên Navbar
                    if ($avatarUrl) {
                        $_SESSION['avatar'] = $avatarUrl; 
                    }
                }
                
                $redirectUrl = ($_SESSION['role'] === 'admin' && $_SESSION['user_id'] != $userId) 
                                ? "index.php?controller=user&action=list" 
                                : "index.php?controller=user&action=profile&id=$userId";
                $this->redirect($redirectUrl, 'Cập nhật hồ sơ thành công!');
            } else {
                $this->back('Chưa có thay đổi nào được lưu hoặc có lỗi xảy ra.');
            }
        }
    }

    public function profile() {
        $id = $_GET['id'] ?? ($_SESSION['user_id'] ?? null);

        $sameUser = false;
        if (isset($_SESSION['user_id'], $_GET['id']) && $_SESSION['user_id'] == $id) {
            $sameUser = true;
        }

        $navbarCategories = $this->categoryModel->getAll() ?? [];

        if (!$id) {
            $this->redirect('./index.php?controller=user&action=login', 'Vui lòng đăng nhập!');
        }

        $user = $this->userModel->getById((int)$id);

        if (!$user || $user['AccountStatus'] === 'deleted') {
            $this->back('Người dùng không tồn tại hoặc đã bị xóa.');
        }
         // 🔥 THÊM ĐOẠN NÀY

        $currentUserId = $_SESSION['user_id'] ?? 0;
        $profileUserId = $user['UserID'];

        $isFollowing = false;

        if ($currentUserId && $currentUserId != $profileUserId) {
            $isFollowing = $this -> followModel->exists($currentUserId, $profileUserId);
        }

        // (optional) lấy số follower luôn
        $followerCount = $this -> followModel->countFollowers($profileUserId);

            // 🔥 truyền userId vào
            $data = $this -> getPostforUserId($id);

            extract($data); // tạo biến $posts, $comments,...

            include_once "MVC/View/User/profile.php";
    }

    public function getPostforUserId($userId) {

        // 🔥 chỉ lấy post của user này
        $posts = $this->postModel->fetchByField('UserID', $userId) ?? [];

        $userid = $_SESSION['user_id'] ?? null;

        // =======================
        // FILTER GROUP
        // =======================
        $filteredPosts = [];
        $canInteract = [];
        $isSystemAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');

        foreach ($posts as $post) {
            $postId = $post->getPostId();
            $groupId = $post->getGroupId();

            $viewFlag = true;
            $interactFlag = true;

            $group = null;

            if ($groupId) {
                $group = $this->groupModel->getById($groupId);
                $userRoleInGroup = $this->groupModel->getUserRole($userid, $groupId);

                if (!$isSystemAdmin) {
                    if ($group && strtolower($group['Privacy']) === 'private' && !$userRoleInGroup) {
                        $viewFlag = false;
                    }

                    if (!$userRoleInGroup) {
                        $interactFlag = false;
                    }
                }
            }

            if ($viewFlag) {
                $filteredPosts[] = $post;
                $canInteract[$postId] = $interactFlag;
            }
        }

        $posts = $filteredPosts;

        $isOwnerPost = [];
        $canDel_EditPost = [];

        foreach ($posts as $post) {
            $postId = $post->getPostId();

            // check chủ bài
            $isOwnerPost[$postId] = ($post->getUserId() == $userid);

            // check quyền delete
            $canDel_EditPost[$postId] =
            $isOwnerPost[$postId] || $isSystemAdmin;
        }

        // =======================
        // REACTIONS POST
        // =======================
        $reactions_forPost = [];
        $isSameUser_reactPost = [];

        foreach ($posts as $post) {
            $postId = $post->getPostId();

            $reactions_forPost[$postId] = $this->reactionModel->selectReactionsForPost($postId);

            $isSameUser_reactPost[$postId] = false;
            foreach ($reactions_forPost[$postId] as $reaction) {
                if ($reaction->getUserId() == $userid) {
                    $isSameUser_reactPost[$postId] = true;
                    break;
                }
            }
        }

        // =======================
        // COMMENTS
        // =======================
        $comments = [];
        foreach ($posts as $post) {
            $comments[$post->getPostId()] = $this->commentModel->fetchByField('PostID', $post->getPostId());
        }

        // TREE
        $commentTree = [];
        foreach ($posts as $post) {
            $postId = $post->getPostId();
            $commentTree[$postId] = [];

            foreach ($comments[$postId] as $c) {
                $parent = $c->getParentCommentId();
                $commentTree[$postId][$parent][] = $c;
            }
        }

        // =======================
        // REACTION COMMENT
        // =======================
        $reactions_forComment = [];
        $isSameUser_reactCmt = [];

        foreach ($comments as $postComments) {
            foreach ($postComments as $comment) {
                $commentId = $comment->getCommentId();

                $reactions_forComment[$commentId] =
                    $this->reactionModel->selectReactionsForComment($commentId);

                $isSameUser_reactCmt[$commentId] = false;
                foreach ($reactions_forComment[$commentId] as $reaction) {
                    if ($reaction->getUserId() == $userid) {
                        $isSameUser_reactCmt[$commentId] = true;
                        break;
                    }
                }
            }
        }

        // =======================
        // MEDIA
        // =======================
        $mediaForPost = [];
        foreach ($posts as $post) {
            $mediaForPost[$post->getPostId()] =
                $this->mediaModel->getByPostId($post->getPostId());
        }

        // 🔥 trả data về controller
        return [
            'posts' => $posts,
            'canInteract' => $canInteract,
            'reactions_forPost' => $reactions_forPost,
            'isSameUser' => $isSameUser_reactPost,
            'comments' => $comments,
            'commentTree' => $commentTree,
            'reactions_forComment' => $reactions_forComment,
            'isSameUser_reactCmt' => $isSameUser_reactCmt,
            'isSameUser_reactPost' => $isSameUser_reactPost,
            'mediaForPost' => $mediaForPost,
            'canDel_EditPost' => $canDel_EditPost,
            'isOwnerPost' => $isOwnerPost
        ];
}

    // ================= QUÊN MẬT KHẨU =================
    public function forgotPassword() {
        include_once "MVC/View/User/forgot_password.php";
    }

    public function processReset() {
        if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['new_password'])) {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $newPassword = $_POST['new_password'];

            $user = $this->userModel->verifyUserForReset($username, $email);

            if ($user) {
                if ($user['AccountStatus'] === 'banned') {
                    $this->back('Tài khoản này đang bị khóa, không thể đổi mật khẩu!');
                }
                
                $result = $this->userModel->updatePassword($user['UserID'], $newPassword);
                if ($result) {
                    $this->redirect('./index.php?controller=user&action=login', 'Đặt lại mật khẩu thành công! Vui lòng đăng nhập với mật khẩu mới.');
                } else {
                    $this->back('Lỗi hệ thống khi cập nhật mật khẩu.');
                }
            } else {
                $this->back('Tên đăng nhập hoặc Email không chính xác!');
            }
        }
    }
}
?>