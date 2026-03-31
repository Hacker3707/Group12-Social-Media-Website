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
            echo "Invalid action";
        }
    }

    // ================= ACTION: HIỂN THỊ FORM ĐĂNG KÝ =================
    public function register() {
        // Nạp file giao diện đăng ký
        include_once "MVC/View/User/register.php";
    }

    // ================= ACTION: HIỂN THỊ FORM ĐĂNG NHẬP =================
    public function login() {
        // Nạp file giao diện đăng nhập
        include_once "MVC/View/User/login.php";
    }

    // ================= ACTION: XỬ LÝ ĐĂNG NHẬP (Chạy khi bấm nút submit) =================
    public function authenticate() {
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Gọi Model để check DB
            $user = $this->userModel->checkLogin($username, $password);

            if ($user) {
                // LƯU THÔNG TIN VÀO SESSION
                $_SESSION['user_id'] = $user['UserID'];
                $_SESSION['username'] = $user['Username'];
                $_SESSION['role'] = $user['UserRole']; // Để dành phân quyền Admin sau này

                echo "<script>alert('Đăng nhập thành công!'); window.location.href='index.php';</script>";
            } else {
                echo "<script>alert('Sai tài khoản, mật khẩu hoặc tài khoản bị khóa!'); window.history.back();</script>";
            }
        }
    }

    // ================= XỬ LÝ ĐĂNG XUẤT =================
    public function logout() {
        session_destroy(); // Xóa sạch trí nhớ của web về user này
        echo "<script>window.location.href='index.php';</script>";
    }

    // ================= ACTION: CREATE (ĐĂNG KÝ / THÊM USER) =================
    public function create() {
        if (isset($_POST['username']) && isset($_POST['email'])) {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'] ?? '123456';

            // 1. Kiểm tra trùng Username
            if ($this->userModel->existsByUsername($username)) {
                echo "<script>alert('Lỗi: Username đã tồn tại!'); window.history.back();</script>";
                return;
            }

            // 2. Thực hiện lưu vào Database
            $user = new User(null, $username, $email);
            $result = $this->userModel->insert($user, $password);

            if ($result) {
                // 3. Phân luồng sau khi lưu thành công
                
                // Trường hợp 1: Admin đang thêm người dùng từ trang quản lý
                if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
                    echo "<script>alert('Thêm user thành công!'); window.location.href='index.php?controller=user&action=list';</script>";
                } 
                // Trường hợp 2: Khách vãng lai tự đăng ký ngoài trang chủ
                else {
                    // Tự động đăng nhập luôn bằng cách gọi lại hàm checkLogin
                    $newUser = $this->userModel->checkLogin($username, $password);
                    if ($newUser) {
                        $_SESSION['user_id'] = $newUser['UserID'];
                        $_SESSION['username'] = $newUser['Username'];
                        $_SESSION['role'] = $newUser['UserRole'];
                    }
                    
                    // Chuyển hướng về trang chủ
                    echo "<script>alert('Đăng ký thành công! Chào mừng bạn gia nhập Passo.'); window.location.href='index.php';</script>";
                }
            } else {
                echo "<script>alert('Lỗi hệ thống khi đăng ký.'); window.history.back();</script>";
            }
        }
    }

    // ================= ACTION: LIST =================
    public function list() {
        $users = $this->userModel->getAll();
        echo "<h2>User Management</h2>";
        echo "<table border='1' cellpadding='10'>";
        echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Status</th><th>Actions</th></tr>";
        foreach ($users as $u) {
            echo "<tr>
                    <td>{$u->getUserId()}</td>
                    <td>{$u->getUsername()}</td>
                    <td>{$u->getEmail()}</td>
                    <td>{$u->getUserRole()}</td>
                    <td>{$u->getAccountStatus()}</td>
                    <td>
                        <a href='index.php?controller=user&action=edit&id={$u->getUserId()}'>Edit</a> | 
                        <a href='index.php?controller=user&action=delete&id={$u->getUserId()}' onclick='return confirm(\"Xóa user này?\")'>Delete</a>
                    </td>
                  </tr>";
        }
        echo "</table>";
    }

    // ================= ACTION: EDIT (LOAD DATA) =================
    public function edit() {
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $user = $this->userModel->getById($id);

            if (!$user) {
                echo "User not found!";
                return;
            }

            echo "
                <h2>Edit User Profile</h2>
                <form method='POST' action='index.php?controller=user&action=update'>
                    <input type='hidden' name='userId' value='{$user['UserID']}'>
                    <p>Username: <input type='text' name='username' value='{$user['Username']}' required></p>
                    <p>Email: <input type='email' name='email' value='{$user['Email']}' required></p>
                    <p>Phone: <input type='text' name='phone' value='{$user['Phone']}'></p>
                    <p>Bio: <br><textarea name='bio' rows='4' cols='30'>{$user['Bio']}</textarea></p>
                    <button type='submit'>Save Changes</button>
                </form>
            ";
        }
    }

    // ================= ACTION: UPDATE =================
    public function update() {
        if (isset($_POST['userId'])) {
            $userId = (int)$_POST['userId'];
            $username = $_POST['username'];
            $email = $_POST['email'];
            $bio = $_POST['bio'];
            $phone = $_POST['phone'];

            if ($this->userModel->existsByUsername($username, $userId)) {
                echo "<script>alert('Username này đã được sử dụng!'); window.history.back();</script>";
                return;
            }

            $result = $this->userModel->update($userId, $username, $email, $bio, $phone);
            $msg = $result ? "Cập nhật thành công!" : "Không có thay đổi.";
            echo "<script>alert('$msg'); window.location.href='index.php?controller=user&action=list';</script>";
        }
    }

    // ================= ACTION: DELETE =================
    public function delete() {
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $result = $this->userModel->delete($id);
            $msg = $result ? "Đã chuyển trạng thái user sang Deleted!" : "Lỗi khi xóa.";
            echo "<script>alert('$msg'); window.location.href='index.php?controller=user&action=list';</script>";
        }
    }
}