<?php
include_once "MVC/Model/UserModel.php";
include_once "Entity/User.php";
include_once "Entity/Admin.php";
include_once "Entity/Member.php";

class UserController {

    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
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

    public function register() {
        include_once "MVC/View/User/register.php";
    }

    public function login() {
        include_once "MVC/View/User/login.php";
    }

    // ================= XỬ LÝ ĐĂNG NHẬP =================
    public function authenticate() {
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $user = $this->userModel->checkLogin($username, $password);

            if ($user) {
                // 1. Lưu thông tin vào Session
                $_SESSION['user_id'] = $user['UserID'];
                $_SESSION['username'] = $user['Username'];
                $_SESSION['role'] = $user['UserRole']; 

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
                    $_SESSION['user_id'] = $newUser['UserID'];
                    $_SESSION['username'] = $newUser['Username'];
                    $_SESSION['role'] = $newUser['UserRole'];
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

        $keyword = trim($_GET['keyword'] ?? '');
        
        if ($keyword !== '') {
            $users = $this->userModel->searchUsers($keyword);
        } else {
            $users = $this->userModel->getAll();
        }

        include_once "MVC/View/User/list.php";
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

            // Nếu không phải Admin và không phải đang tự sửa hồ sơ của mình -> Chặn
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

            $result = $this->userModel->update($userId, $username, $email, $bio, $phone);

            if ($result) {
                if ($_SESSION['user_id'] == $userId) {
                    $_SESSION['username'] = $username; 
                }
                // Nếu Admin sửa thì đưa về trang List, nếu User tự sửa thì đưa về Profile
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

        if (!$id) {
            $this->redirect('/index.php?controller=user&action=login', 'Vui lòng đăng nhập!');
        }

        $user = $this->userModel->getById((int)$id);

        if (!$user || $user['AccountStatus'] === 'deleted') {
            $this->back('Người dùng không tồn tại hoặc đã bị xóa.');
        }

        include_once "MVC/View/User/profile_view.php";
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
                    $this->redirect('/index.php?controller=user&action=login', 'Đặt lại mật khẩu thành công! Vui lòng đăng nhập với mật khẩu mới.');
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