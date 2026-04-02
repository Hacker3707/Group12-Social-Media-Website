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

    // ================= ACTION: XỬ LÝ ĐĂNG KÝ =================
    public function create() {
        if (isset($_POST['username']) && isset($_POST['email'])) {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            // 1. Kiểm tra xem tên đăng nhập đã có ai dùng chưa
            if ($this->userModel->existsByUsername($username)) {
                echo "<script>alert('Tên đăng nhập đã tồn tại! Vui lòng chọn tên khác.'); window.history.back();</script>";
                return;
            }

            // 2. Lưu user mới vào Database
            $user = new User(null, $username, $email);
            $result = $this->userModel->insert($user, $password);

            if ($result) {
                // 3. TỰ ĐỘNG ĐĂNG NHẬP SAU KHI ĐĂNG KÝ THÀNH CÔNG
                // Lấy lại thông tin user vừa tạo từ DB để lấy UserID
                $newUser = $this->userModel->checkLogin($username, $password);
                
                if ($newUser) {
                    // Gán Session (Chính cái này sẽ làm cho Navbar hiện "Chào, Tên")
                    $_SESSION['user_id'] = $newUser['UserID'];
                    $_SESSION['username'] = $newUser['Username'];
                    $_SESSION['role'] = $newUser['UserRole'];
                }

                // 4. Chuyển hướng về trang chủ
                echo "<script>alert('Đăng ký thành công! Chào mừng bạn gia nhập Passo.'); window.location.href='index.php';</script>";
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

    // ================= ACTION: EDIT (LOAD FORM) =================
    public function edit() {
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            
            // Lấy thông tin user từ DB
            $user = $this->userModel->getById($id);

            if (!$user) {
                echo "Không tìm thấy người dùng!";
                return;
            }

            // GỌI FILE GIAO DIỆN (Thay vì dùng echo như lúc trước)
            include_once "MVC/View/User/edit.php";
        }
    }

    // ================= ACTION: UPDATE (CẬP NHẬT HỒ SƠ) =================
    public function update() {
        if (isset($_POST['userId'])) {
            $userId = (int)$_POST['userId'];

            // 1. BẢO MẬT: Chặn mọi hành vi sửa hồ sơ người khác (Kể cả Admin)
            if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != $userId) {
                echo "<script>alert('Lỗi: Bạn không có quyền chỉnh sửa hồ sơ của người khác!'); window.history.back();</script>";
                return; // Dừng chạy code ngay lập tức
            }

            // Lấy các dữ liệu còn lại
            $username = $_POST['username'];
            $email = $_POST['email'];
            $bio = $_POST['bio'] ?? '';
            $phone = $_POST['phone'] ?? '';

            // 2. Kiểm tra xem Username mới có bị trùng với ai khác không
            if ($this->userModel->existsByUsername($username, $userId)) {
                echo "<script>alert('Username này đã được người khác sử dụng!'); window.history.back();</script>";
                return;
            }

            // 3. Thực hiện cập nhật vào Database
            $result = $this->userModel->update($userId, $username, $email, $bio, $phone);

            if ($result) {
                // 4. Lưu thành công -> Cập nhật lại Session và về thẳng trang Profile
                $_SESSION['username'] = $username; 
                echo "<script>alert('Cập nhật hồ sơ cá nhân thành công!'); window.location.href='index.php?controller=user&action=profile&id=$userId';</script>";
            } else {
                echo "<script>alert('Chưa có thay đổi nào được lưu hoặc có lỗi xảy ra.'); window.history.back();</script>";
            }
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

    // ================= ACTION: XEM TRANG CÁ NHÂN (PROFILE) =================
    public function profile() {
        // Lấy ID từ URL, nếu không có thì lấy ID của chính mình đang đăng nhập
        $id = $_GET['id'] ?? ($_SESSION['user_id'] ?? null);

        if (!$id) {
            echo "<script>alert('Vui lòng đăng nhập!'); window.location.href='index.php?controller=user&action=login';</script>";
            return;
        }

        // Lấy thông tin user từ Model
        $user = $this->userModel->getById((int)$id);

        if (!$user) {
            echo "Người dùng không tồn tại hoặc đã bị xóa.";
            return;
        }

        // Nạp giao diện trang cá nhân
        include_once "MVC/View/User/profile.php";
    }

    // ================= ACTION: HIỂN THỊ FORM QUÊN MẬT KHẨU =================
    public function forgotPassword() {
        include_once "MVC/View/User/forgot_password.php";
    }

    // ================= ACTION: XỬ LÝ ĐẶT LẠI MẬT KHẨU =================
    public function processReset() {
        if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['new_password'])) {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $newPassword = $_POST['new_password'];

            // Kiểm tra xem Username và Email có khớp với nhau trong DB không
            $user = $this->userModel->verifyUserForReset($username, $email);

            if ($user) {
                // Nếu khớp -> Tiến hành đổi mật khẩu
                $result = $this->userModel->updatePassword($user['UserID'], $newPassword);
                if ($result) {
                    echo "<script>alert('Đặt lại mật khẩu thành công! Vui lòng đăng nhập với mật khẩu mới.'); window.location.href='index.php?controller=user&action=login';</script>";
                } else {
                    echo "<script>alert('Lỗi hệ thống khi cập nhật mật khẩu.'); window.history.back();</script>";
                }
            } else {
                // Nếu nhập sai
                echo "<script>alert('Tên đăng nhập hoặc Email không chính xác!'); window.history.back();</script>";
            }
        }
    }
}