<?php
include_once "MVC/Model/UserModel.php";
include_once "Entity/User.php";

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
            $this->redirect('/Group12-Social-Media-Website/index.php', 'Hành động không hợp lệ!');
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

    public function authenticate() {
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $user = $this->userModel->checkLogin($username, $password);

            if ($user) {
                $_SESSION['user_id'] = $user['UserID'];
                $_SESSION['username'] = $user['Username'];
                $_SESSION['role'] = $user['UserRole']; 

                $this->redirect('/Group12-Social-Media-Website/index.php', 'Đăng nhập thành công!');
            } else {
                $this->back('Sai tài khoản, mật khẩu hoặc tài khoản bị khóa!');
            }
        }
    }

    public function logout() {
        session_destroy();
        // Không dùng $this->redirect để tránh lưu flash message vào session vừa bị hủy
        header("Location: index.php");
        exit();
    }

    public function create() {
        if (isset($_POST['username']) && isset($_POST['email'])) {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            if ($this->userModel->existsByUsername($username)) {
                $this->back('Tên đăng nhập đã tồn tại! Vui lòng chọn tên khác.');
            }

            $user = new User(null, $username, $email);
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

    public function list() {
        $users = $this->userModel->getAll();
        include_once "MVC/View/User/list.php";
    }

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

            if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != $userId) {
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
                $_SESSION['username'] = $username; 
                $this->redirect("/Group12-Social-Media-Website/index.php?controller=user&action=profile&id=$userId", 'Cập nhật hồ sơ cá nhân thành công!');
            } else {
                $this->back('Chưa có thay đổi nào được lưu hoặc có lỗi xảy ra.');
            }
        }
    }

    public function delete() {
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $result = $this->userModel->delete($id);
            $msg = $result ? "Đã chuyển trạng thái user sang Deleted!" : "Lỗi khi xóa.";
            $this->redirect('/Group12-Social-Media-Website/index.php?controller=user&action=list', $msg);
        }
    }

    public function profile() {
        $id = $_GET['id'] ?? ($_SESSION['user_id'] ?? null);

        if (!$id) {
            $this->redirect('/Group12-Social-Media-Website/index.php?controller=user&action=login', 'Vui lòng đăng nhập!');
        }

        $user = $this->userModel->getById((int)$id);

        if (!$user) {
            $this->back('Người dùng không tồn tại hoặc đã bị xóa.');
        }

        include_once "MVC/View/User/profile.php";
    }

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
                $result = $this->userModel->updatePassword($user['UserID'], $newPassword);
                if ($result) {
                    $this->redirect('/Group12-Social-Media-Website/index.php?controller=user&action=login', 'Đặt lại mật khẩu thành công! Vui lòng đăng nhập với mật khẩu mới.');
                } else {
                    $this->back('Lỗi hệ thống khi cập nhật mật khẩu.');
                }
            } else {
                $this->back('Tên đăng nhập hoặc Email không chính xác!');
            }
        }
    }
}